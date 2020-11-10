<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;


// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class Mailer
{
    public const FROM_ADDRESS = 'kafkiansky@webshake.ru';
    public const TO_ADDRESS = '9723942@mail.ru';

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig

    )  {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }

    /**
     * @param User $user
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendConfirmationMessage(User $user)
    {
        $messageBody = $this->twig->render('security/confirmation.html.twig', [
            'user' => $user
        ]);


        // mail ( $user->getUsername(), 'Confirmation message', $messageBody);

        // $message = new Swift_Message();
        // $message
        //     ->setSubject('Вы успешно прошли регистрацию!')
        //     ->setFrom(self::FROM_ADDRESS)
        //     ->setTo($user->getEmail())
        //     ->setBody($messageBody, 'text/html');

        // $this->mailer->send($message);



        $email = (new Email())
            ->from('pegtest.ru@gmail.com')
            ->to('info71@list.ru')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html($messageBody);

        $this->mailer->send($email);


    }
}
