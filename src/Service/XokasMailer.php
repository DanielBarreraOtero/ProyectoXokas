<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class XokasMailer
{

    private MailerInterface $mailer;
    private ?Email $mail;
    private string $sender;


    public function __construct(MailerInterface $mailer, string $sender)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    public function setMail(Email $mail)
    {
        $this->mail = $mail;
    }

    public function setMailTest(string $for)
    {
        $this->mail = (new Email())
            ->from($this->sender)
            ->to($for)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('testeo de correo!')
            ->text('TA LLEGAO O NOOOO!');
        // ->html('<p>See Twig integration for better HTML integration!</p>');
    }

    public function setMailSpam(string $for)
    {
        $this->mail = (new TemplatedEmail())
            ->from($this->sender)
            ->to($for)
            ->subject('OTAKUU?!?! 🥵🔥')
            ->htmlTemplate('mail/mailTest.html.twig');
    }

    public function sendMail()
    {
        $this->mailer->send($this->mail);
    }
}
