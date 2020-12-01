<?php


namespace App\Service;


use Twig\Environment;

class Mailer
{
    private $mail;
    private $templating;
    public function __construct(\Swift_Mailer $mailer,Environment $templating)
    {
        $this->mail = $mailer;
        $this->templating = $templating;
    }

    public function sendMail($object,$target,$template,array $params,$sender="terangawebdevelopement@gmail.com")
    {
        $template = $this->templating->render($template,$params);
        $message = ( new \Swift_Message($object))
                    ->setFrom($sender)
                    ->setTo($target)
                    ->setBody(
                        $template,"text/html"
                    );
        $this->mail->send($message);
    }
}