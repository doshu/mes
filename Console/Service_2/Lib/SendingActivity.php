<?php

	require_once 'Model'.DS.'Sending.php';
	require_once 'Model'.DS.'Recipient.php';
	require_once 'Model'.DS.'Mail.php';
	require_once 'Model'.DS.'Smtp.php';
	require_once 'Model'.DS.'Attachment.php';
	require_once 'MailParser.php';
	require_once 'Mailer.php';
	require_once 'FThread.php';
	require_once 'Logger.php';
	require_once 'Output.php';

	class SendingActivity extends FThread {
	
		public $entityId;
		public $MailParser;
		public $withErrors = 0;
		public $mutex;
		public $finish = false;
		public $__isDaemon = false;
		public $Logger;
		public $Output;
		
		public function __construct($sending) {
			$this->entityId = $sending;
			$this->MailParser = new MailParser();
			$this->Logger = new Logger(LOGFILE);
			$this->Output = new Output();
		}
	
		
		public function run() {
		 
		 	
			$SendingModel = Factory::getInstance('Model/Sending');
			$RecipientModel = Factory::getInstance('Model/Recipient');
			$MailModel = Factory::getInstance('Model/Mail');
			$SmtpModel = Factory::getInstance('Model/Smtp');
			$Attachment = Factory::getInstance('Model/Attachment');
			$entity = Factory::getInstance('Model/Sending')->load($this->entityId);
			
			$isToSend = $entity->isToSend();
			
			$this->notify();
			$this->wait();
			
			$entity = Factory::getInstance('Model/Sending')->load($this->entityId); //reload the entity after parent changes
			
			if($isToSend) {
			
				$recipients = $RecipientModel->getRecipientBySending($this->entityId);
				$lastCall = true;
				
				if(count($recipients)) {
				
					$toGet = RECIPIENT_PER_TIME(count($recipients));
					
					if($toGet < count($recipients)) {
						$lastCall = false;
					}
					$recipients = array_slice($recipients, 0, $toGet);
					
					$entity->data['started'] = time();
					
					$entity->save(); 
					
					$smtp = $SmtpModel->find('first', array('where' => array('id' => $entity->data['smtp_id'])));
					$mail = $MailModel->find('first', array('where' => array('id' => $entity->data['mail_id'])));
					
					if(empty($smtp) || empty($mail)) {
						return false;
					}
					
					$mailer = new Mailer(true);
					
					$mailer->IsSMTP();
					$mailer->Host = $smtp['host'];
					$mailer->SMTPAuth = true;
					$mailer->Username = $smtp['username'];
					$mailer->Password = $smtp['password'];
					//$mailer->Hostname = 'www.powamail.tk';
					
					$mailer->AuthType = $smtp['authtype'];
					
					if($smtp['enctype'] == 'tls' || $smtp['enctype'] == 'ssl') {
						$mailer->SMTPSecure = $smtp['enctype'];   
					}
					
					$mailer->From = $smtp['email'];
					$mailer->FromName = !empty($entity->data['sender_name'])?$entity->data['sender_name']:$smtp['name'];
					
					$attachments = $Attachment->getAttachmentsByMail($mail['id']);
					
					
					foreach($attachments as list($file, $name)) {
						if(file_exists($file)) {
							$mailer->AddAttachment($file, $name);
						}
					}
					
					
					$mailer->Subject = $mail['subject'];
					
					
					foreach($recipients as $recipient) {
						$recipient = Factory::getInstance('Model/Recipient')->load($recipient['id']);
						$mailer->resetAddresses();
						try {
							
							switch($entity->data['type']) {
								case Sending::$HTML:
									$mailer->IsHTML(true);
									$mailer->Body = $this->MailParser->replaceVariable($recipient, $entity, $mail['html']);
									$mailer->Body = $this->MailParser->parse($mailer->Body, $recipient);
								break;
								case Sending::$TEXT:
									$mailer->IsHTML(false);
									$mailer->Body = $this->MailParser->replaceVariable($recipient, $entity, $mail['text']);
								break;
								case Sending::$BOTH:
									$mailer->IsHTML(true);
									$mailer->Body = $this->MailParser->replaceVariable($recipient, $entity, $mail['html']);
									$mailer->AltBody = $this->MailParser->replaceVariable($recipient, $entity, $mail['text']);
									$mailer->Body = $this->MailParser->parse($mailer->Body, $recipient);
								break;
							}
							
							$mailer->AddAddress($recipient->data['member_email']);
							
							if(true || $mailer->Send()) {
								$this->log($recipient->data['member_email']." Sended Succesfully",'info');
								$recipient->data['sended'] = 1;
								$recipient->data['sended_time'] = time();
								$recipient->save();
							}
							else {
								throw new Exception('Error during sending to '.$recipient->data['member_email']);
							}
							
						}
						catch(Exception $e) {
							$this->log($e->getMessage(), 'error');
							$recipient->data['errors'] = 1;
							$entity->data['errors'] = 1;
							$this->withErrors = 1;
							$recipient->save();
						}
					}
				}
				
				if($lastCall) {
					$entity->data['status'] = Sending::$COMPLETED;
					$entity->data['ended'] = time();
				}
				else {
					$entity->data['stopped'] = 1;
					$entity->data['stopped_until'] = time() + STOPPING_TIME; 
				}
				
				try {
					$entity->save();
					if($lastCall) {
						$this->log("Ended ".$this->entityId, 'info');
					}
					else {
						$this->log("Stopped ".$this->entityId." until UTC ".date('d/m/Y H:i:s', $entity->data['stopped_until']), 'info');
					}
					$this->removeFromPool($this->entityId);
				}
				catch(Exception $e) {
					$this->log($e->getMessage(), 'error');
				}
				
				$this->finish = true;
			}
			
		}
		
		
		public function removeFromPool($id) {
			
			if(sem_acquire($this->mutex)) {
				$new = array();
				$old = fopen(SENDING_POOL, 'r');
				while($line = fgets($old)) {
					if(trim($line) != $id) {
						$new[] = $line;
					}
				}
				fclose($old);
				$new = implode("\n", $new);
				file_put_contents(SENDING_POOL, $new);
   			 	sem_release($this->mutex);
			}
		}
		
		// execute as parent
		public function onAfterForkParentFail($e, $childPid) {
			parent::onAfterForkParentFail($e, $childPid);
			Factory::getInstance('Model/Sending')->setSendingStatus($this->entityId, Sending::$SENDING);			
		}
		
		public function onStartChildFail($e) {
			die($this->entityId.': '.$e->getMessage());
		}
		
		
		public function log($msg, $type) {
			$this->Logger->$type(date('d/m/Y H:i:s').' '.$msg);
			if(!$this->__isDaemon) {
				$this->Output->$type(date('d/m/Y H:i:s').' '.$msg);
			}
		}
		
	}

?>
