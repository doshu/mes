<?php


	class MailParser {
	
	
		public $generatedPlaceholder = array();
		
	
		public function parse($code, $recipient) {
			
			//change the href a attribute
			$code = $this->replaceLinkIn('a', 'href', $code, $recipient);
			$code = $this->replaceLinkIn('img', 'src', $code, $recipient);
			$code = $this->pushFakeImage($code, $recipient);
			//insert the code in html page structure
			$code = '<!DOCTYPE html><html><head></head><body>'.$code.'</body></html>';
			return $code;
		}
		
		public function replaceLinkIn($tag, $attribute, $code, $recipient) {
			
			$code = preg_replace_callback(
				'/(<\s*'.$tag.'[^>]*'.$attribute.'=["\'])([^"\']*)(["\'][^<]*>)/i', 
				function($m) use ($recipient, $tag){
				
					if($tag == 'img') {
						$uriInfo = parse_url($m[2]);
						$extension = '';
						if(isset($uriInfo['path']) && !empty($uriInfo['path'])) {
							$uriInfo = pathinfo($uriInfo['path']);
							if(isset($uriInfo['extension']) && !empty($uriInfo['extension'])) {
								$extension = $uriInfo['extension'];
							}
						}
						
						$tagUrl = sprintf(
							OPEN_ME_IMAGE, 
							$recipient->data['id'], 
							$recipient->data['member_secret'], 
							base64_encode($m[2]),
							$extension
						);
					}
					else {
						$tagUrl = sprintf(
							OPEN_ME_LINK, 
							$recipient->data['id'], 
							$recipient->data['member_secret'], 
							base64_encode($m[2])
						);
					}
					
					return $m[1].$tagUrl.$m[3];
				}, 
				$code
			);
			return $code;
		}
		
		public function pushFakeImage($code, $recipient) {
			$imgUrl = sprintf(OPEN_ME_FAKE, $recipient->data['id'], $recipient->data['member_secret']);
			return $code.'<img src="'.$imgUrl.'"/>';
		}
		
		/*
		public function replaceLinkIn($tag, $attribute, $code, $recipient) {
			
			//preg_match_all('/<(\s*'.$tag.'[^>])'.$attribute.'=["\']([^"\']*)["\']([^<]*)>/i', $code, $matches);
			$code = preg_replace_callback(
				'/(<\s*'.$tag.'[^>]*'.$attribute.'=["\'])([^"\']*)(["\'][^<]*>)/i', 
				function($m) use ($recipient, $tag){
					$params = array();
					$params[] = 'uri='.urlencode($m[2]);
					$params[] = 'recipient='.$recipient->data['id'];
					$params[] = 'key='.$recipient->data['member_secret'];
					if($tag == 'img') {
						$params[] = 'fromimage=1';
					}
					$params = implode('&', $params);
					return $m[1].SERVICE_URL.'?'.$params.$m[3];
				}, 
				$code
			);
			return $code;
		}
		
		public function pushFakeImage($code, $recipient) {
			return $code.'<img src="'.FAKE_IMAGE_URL.'?recipient='.$recipient->data['id'].'&key='.$recipient->data['member_secret'].'"/>';
		}
		*/
		/*
		public function createPlaceholder($code) {
			$placeholder = '{{__insert_secret_%s__}}';
			do {
				$new = sprintf($placeholder, md5(rand(0,9999).time()));
			} while(strpos($code, $new) !== false || in_array($new, $this->generatedPlaceholder));
			
			$this->generatedPlaceholder[] = $new;
			return $new;
		}
		
		
		public function putRecipientInfo($recipient, $recipientPlaceholder, $secretPlaceholder, $code) {
			return str_replace(
				$recipientPlaceholder, 
				urlencode($recipient->data['id']), 
				str_replace($secretPlaceholder, urlencode($recipient->data['member_secret']), $code)
			);
		}
		*/
		
		public function replaceVariable($recipient, $sending, $body) {
			if(!empty($recipient->data['member_data'])) {
				$variables = json_decode($recipient->data['member_data'], true);
				if(is_array($variables)) {
					$find = array_map(
						function($el) {
							return '{{'.$el.'}}';
						},
						array_keys($variables)
					);
					$replace = array_values($variables);
					$body = str_replace($find, $replace, $body);
				}
			}
			
			$body = preg_replace_callback(
				'/{{unsubscribe\s*\(([^}]*)\)\s*}}/i',
				function($m) use($recipient, $sending) {
					return sprintf(
						UNSUSCRIBE_LINK, 
						$recipient->data['id'], 
						$recipient->data['member_secret'], 
						$sending->data['id'],
						isset($m[1]) && !empty($m[1])?urlencode($m[1]):false
					);
				},
				$body
			);
			
			return $body;
		}
	}

?>
