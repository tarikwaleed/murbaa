<?php
	//use PHPMailer\PHPMailer\PHPMailer;
	//use PHPMailer\PHPMailer\SMTP;
	//use PHPMailer\PHPMailer\Exception;

	class Email 
	{
		private $mailer;
		private $Error = "";
		
		function __construct()
		{
			//require LIB.'PHPMailer/Exception.php';
			require LIB.'PHPMailer/PHPMailer.php';
			require LIB.'PHPMailer/SMTP.php';
			
		    $this->mailer = new PHPMailer();
			
			try {
				//Server settings
				
				$this->mailer->isSMTP();                        // Send using SMTP
	            $this->mailer->CharSet      = 'UTF-8';						
				//$this->mailer->SMTPDebug    = 2;		// FOR ONLINE
				$this->mailer->SMTPDebug    = SMTP::DEBUG_OFF;		
				
				$this->mailer->Host 		= EMAIL_HOST;		// Set the SMTP server to send through
				$this->mailer->SMTPAuth 	= EMAIL_SMTP_AUTH;	// Enable SMTP authentication
				$this->mailer->Port 		= EMAIL_PORT; 
				$this->mailer->Username   	= EMAIL_SEND_ADD;	// SMTP username
				$this->mailer->Password		= EMAIL_SEND_PASS;	// SMTP password
				//$this->mailer->SMTPSecure 	= 'ssl';            // FOR ONLINE Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				$this->mailer->SMTPSecure 	= PHPMailer::ENCRYPTION_STARTTLS;
				
            }catch (Exception $e) {
				echo $e.ErrorMessage;
				$this->Error = $this->mailer->ErrorInfo;
			}
		}
		
		//Send Email Message
		public function send_email($TO,$TITLE,$MSG,$from = EMAIL_SEND_ADD,$from_title = TITLE,$ATTACH=array())
		{
			if(!empty($this->Error))
			{
				return $this->Error;
			}
			
			try{
				//Recipients
				$this->mailer->setFrom(EMAIL_SEND_ADD, 'مربع السوق العقاري');          //This is the email your form sends From

                //$this->mailer->addReplyTo($from, $from_title);

				//Add Recipients
				if(is_array($TO))
				{
					foreach($TO as $val)
					{
						$this->mailer->addAddress($val,'مستخدم murbaa'); 
					}
				}else
				{
					$this->mailer->addAddress($TO,'مستخدم murbaa'); 
				}
			
				//Add Attachments
				if(!empty($ATTACH))
				{
					if(is_array($ATTACH))
					{
						foreach($ATTACH as $val)
						{
							$this->mailer->addAttachment($val); 
						}
					}else
					{
						$this->mailer->addAttachment($ATTACH);  
					}
				}
			
				// Content
				$this->mailer->isHTML(true);                                  // Set email format to HTML
				$this->mailer->Subject	= $TITLE;
				//$this->mailer->msgHTML	= $MSG;
				//$this->mailer->AltBody	= $MSG;
				$this->mailer->Body		= $MSG;
				if(!$this->mailer->send())
				{
					$this->Error .="Mailer Error: " . $this->mailer->ErrorInfo;
				}
				
			}catch (Exception $e) {
				return array('Error'=>"Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
			}
			
			if(!empty($this->Error))
			{
				return $this->Error;
			}else
			{
				return true;
			}
		}
		
		//Send SMS Message
		public function send_SMS($to,$MSG)
		{
			return true;
		}
		
		public function forget($name,$email,$id,$time,$from = EMAIL_ADD)
		{
			$MSG = "<html><body>
					<div dir='rtl'>
						العميل $name <br/>
						لقد طلبت اعادة ضبط كلمة الدخول الخاصة بك<br/>
						اذا كان هذا الطلب منك, بامكانك اعادة ضبط كلمة المرور خلال 24 ساعة من $time باستخدام الرابط: 
                        <a href='".URL."login/resetpassword/".$id."'>".URL."login/resetpassword/".$id."</a><<br/>
						اذا لم تكن انت , تجاهل هذا الايميل <br/>
					</div>
					</body></html>";
			
            return $this->send_email($email,"إعادة ضبط كلمة المرور",$MSG,$from = EMAIL_SEND_ADD,$from_title = TITLE,$ATTACH=array());

			
		}
	}
?>