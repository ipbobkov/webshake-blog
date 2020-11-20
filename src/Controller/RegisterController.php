<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\RegisteredUserEvent;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\CodeGenerator;
// use App\Service\Mailer;
// use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
// use Symfony\Component\Serializer\Encoder\JsonEncoder;

class RegisterController extends AbstractController
{
   /**
     * @Route("/register", name="register")
     */
    public function register(
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request,
        CodeGenerator $codeGenerator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $debug="Default debug string";

        $user = new User(null, '', '', '', json_encode(User::ROLE_USER));
        $form = $this->createForm(
            UserType::class,
            $user
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setConfirmationCode($codeGenerator->getConfirmationCode());
            $user->setRoles(json_encode($user->getRoles()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $userRegisteredEvent = new RegisteredUserEvent($user);
            $eventDispatcher->dispatch($userRegisteredEvent, RegisteredUserEvent::NAME);
        }

        $debug .= "Debug: ". implode("<br>", $user->getRoles());       
        return $this->render('security/register.html.twig', ['form' => $form->createView(), 'debug' => $debug]);
        // return new Response($this->twig->render('security/login.html.twig', ['debug' => $debug]));
        // return new Response($this->twig->render('security/register.html.twig', ['form' => $form->createView(), 'debug' => $debug]));
    }

    /**
     * @Route("/confirm/{code}", name="email_confirmation")
     */
    public function confirmEmail(UserRepository $userRepository, string $code)
    {
        /** @var User $user */
        $user = $userRepository->findOneBy(['confirmationCode' => $code]);

        if ($user === null) {
          return new Response('404');
        }

        $user->setEnable(true);
        $user->setConfirmationCode('');

        $em = $this->getDoctrine()->getManager();

        $em->flush();

        return $this->render('security/account_confirm.html.twig', [
            'user' => $user,
        ]);
    }
}
