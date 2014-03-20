<?php

	require_once 'Model'.DS.'Sending.php';
	require_once 'Model'.DS.'Recipient.php';
	require_once 'Model'.DS.'Mail.php';
	require_once 'Model'.DS.'Smtp.php';
	require_once 'Model'.DS.'Attachment.php';
	require_once 'MailParser.php';
	require_once 'Mailer.php';
	require_once 'FThread.php';

	class SendingActivity extends FThread {
	
		public $entityId;
		public $MailParser;
		public $withErrors = 0;
		public $mutex;
		public $finish = false;
		
		public function __construct($sending) {
			
			$this->entityId = $sending;
			$this->MailParser = new MailParser();
		}
	
		public function run() {
		 
			$SendingModel = new Sending();
			$RecipientModel = new Recipient();
			$MailModel = new Mail();
			$SmtpModel = new Smtp();
			$Attachment = new Attachment();
			$entity = (new Sending())->load($this->entityId);
			
			$isToSend = $entity->isToSend();
			
			$this->notify();
			$this->wait();
			
			if($isToSend) {
				$recipients = $RecipientModel->getRecipientBySending($this->entityId);
				
				
				if(count($recipients)) {
					
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
					
					//$secretPlaceholder = $this->MailParser->createPlaceholder($mail['html']);
					//$recipientPlaceholder = $this->MailParser->createPlaceholder($mail['html']);
					
					switch($entity->data['type']) {
						case Sending::$TEXT:
							$mailer->Body = $mail['text'];
							$mailer->IsHTML(false);
						break;
						case Sending::$HTML:
							//$mailer->Body = $this->MailParser->parse($mail['html'], $secretPlaceholder, $recipientPlaceholder);
							$mailer->Body = $mail['html'];
							$mailer->IsHTML(true);
						break;
						case Sending::$BOTH:
							//$mailer->Body = $this->MailParser->parse($mail['html'], $secretPlaceholder, $recipientPlaceholder);
							$mailer->Body = $mail['html'];
							$mailer->AltBody = $mail['text'];
							$mailer->IsHTML(true);
						break;
						
					}
					
					foreach($recipients as $recipient) {
						$recipient = (new Recipient())->load($recipient['id']);
						$mailer->resetAddresses();
						try {
							
							if($entity->data['type'] == Sending::$HTML || $entity->data['type'] == Sending::$BOTH) {
								
								/*
								$tmpBody = $this->MailParser->putRecipientInfo(
									$recipient,
									$recipientPlaceholder,
									$secretPlaceholder,
									$mailer->Body
								);
								*/
								
								$mailer->Body = $this->MailParser->replaceVariable($recipient, $entity, $mailer->Body);
								$mailer->AltBody = $this->MailParser->replaceVariable($recipient, $entity, $mailer->AltBody);
								
								$mailer->Body = $this->MailParser->parse($mailer->Body, $recipient);
							}
							
							$mailer->AddAddress($recipient->data['member_email']);
							echo $recipient->data['member_email']."\n";
							
							if($mailer->Send()) {
								echo "Inviata con successo\n";
								$recipient->data['sended'] = 1;
								$recipient->data['sended_time'] = time();
								$recipient->save();
							}
							else {
								throw new Exception();
							}
							
						}
						catch(Exception $e) {
							echo $e->getMessage()."\n";
							$recipient->data['errors'] = 1;
							$entity->data['errors'] = 1;
							$this->withErrors = 1;
							$recipient->save();
							if(!$this->withErrors) {
								$entity->save();
							}
						}
					}
					
				}
				$entity->data['status'] = Sending::$COMPLETED;
				$entity->data['ended'] = time();
				$entity->save();
				echo "Fine\n";
				$this->removeFromPool($this->entityId);
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
		
	}

?>
