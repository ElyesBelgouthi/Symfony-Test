<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security-login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser() !== null)
        {
            return $this->redirectToRoute('app_home');
        }
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'controller_name' => 'app_login',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('/register', name: 'security-register',methods: ["GET", "POST"])]
    public function registration(Request $request, EntityManagerInterface $manager,
    UserPasswordHasherInterface $userPasswordHasher
    ) : Response
    {
        if($this->getUser() !== null)
        {
            return $this->redirectToRoute('app_home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $user = $form->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                "success",
                "Votre compte a bien été crée "
            );
            return $this->redirectToRoute('security-login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/logout', name: 'security-logout')]
    public function logout()
    {
    }

}
