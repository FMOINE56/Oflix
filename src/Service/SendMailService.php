<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService{

    private $mailer;
    private $adminMail;

    public function __construct(string $adminMail, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->adminMail = $adminMail;
        
    }

    public function send(string $subject, string $template, array $context) :void{

        // // On construit notre email avec le TemplatedEmail
        
        // $email = (new TemplatedEmail())
        // ->from($this->adminMail)
        // ->to($this->adminMail)
        // ->subject($subject)
        // ->htmlTemplate("email/$template")
        // ->context($context);

        // // L'email une fois construit est envoyé via le mailer 
        // $this->mailer->send($email);
    }
}