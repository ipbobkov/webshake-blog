<?php

declare(strict_types=1);

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController
{
    /**
     * @var Twig_Environment $twig
     */
    private $twig;

    public function __construct(
        Environment $twig
    ) {
        $this->twig = $twig;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $debug="";
        
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        if($lastUsername) $debug .= " lastUsername: ".$lastUsername;    

        return new Response($this->twig->render('security/login.html.twig', [
            'form_action' => '/login',
            'last_username' => $lastUsername,
            'error' =>  $error,
            'debug' => $debug
            ])
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
