<?php

declare(strict_types=1);

namespace App\Controller;

use Error;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OAuthController extends AbstractController
{
    /**
     * @param ClientRegistry $clientRegistry
     *
     * @return RedirectResponse
     *
     * @Route("/connect/google", name="connect_google_start")
     */
    public function redirectToGoogleConnect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['email'],[]);
    }

    /**
     * @Route("/google/auth", name="google_auth")
     *
     * @return Response|RedirectResponse
     */
    public function connectGoogleCheck()
    {
        if (!$this->getUser()) {
            // return new JsonResponse(['status' => false, 'message' => "User not found!"]);
            return $this->render('security/login.html.twig', [
                'form_action' => '/login',
                'last_username' => '$lastUsername',
                'error' =>  new Error('Some error!'),
                'debug' => 'Fucking fuckup is fucking on !!! :('
            ]);
        } else {
            return $this->redirectToRoute('blog_posts');
        }
    }
}