<?php
namespace API\Middleware;

class SwiftMailer extends \Slim\Middleware{
	private $swiftMailer;
    private $swiftMessage;

	public function __construct(){
		require_once '../vendor/swiftmailer/swiftmailer/lib/swift_required.php';

	}

	public function call(){
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		            ->setUsername('voipgus@gmail.com')
		            ->setPassword('szgdfb12');

		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance('Wonderful Subject')
		            ->setFrom(array('voipgus@gmail.com' => 'Ahmad Shadeed'))
		            ->setTo(array('gustavo3.8cc@gmail.com' => 'YOU'))
		            #->attach(Swift_Attachment::fromPath("path/to/file/file.zip"))
		            ->setBody('This is the text of the mail send by Swift using SMTP transport.');

		$mailer->send($message);
		$numSent = $mailer->send($message); 

	}

	public function spam(array $emails, $msg){
		foreach ($data as $item) {
            $to = $item['email'];
            try {
                $this->mailer->sendMessage($to, $this->twig->render('mail.twig', $item));
                $this->dispatcher->dispatch(MailEvent::EVENT_MAIL_SENT, new MailEvent\Sent($to));
            } catch (\Exception $e) {
                $this->dispatcher->dispatch(MailEvent::EVENT_SENT_ERROR, new MailEvent\Error($to, $e));
            }
        }
	
	}
		
 	public function sendMessage($to, $body){
        $this->swiftMessage->setTo($to);
        $this->swiftMessage->setBody(strip_tags($body));
        $this->swiftMessage->addPart($body, 'text/html');
        return $this->swiftMailer->send($this->swiftMessage);
    }		


}