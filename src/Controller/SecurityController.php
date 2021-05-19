<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                      $request
     * @param EntityManagerInterface       $em
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param GuardAuthenticatorHandler    $guardAuthenticatorHandler
     * @param LoginFormAuthenticator       $loginFormAuthenticator
     *
     * @return Response
     */
    public function register(Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $userPasswordEncoder,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        LoginFormAuthenticator $loginFormAuthenticator
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        if (!$request->isMethod('post')) {
            return $this->redirectToRoute('app_login');
        }

        $username = $request->request->filter('username', null, FILTER_SANITIZE_STRING);

        if ($em->getRepository(User::class)->findOneBy(['username' => $username])) {
            $this->addFlash('danger', 'Username already in use.');

            return $this->render('security/login.html.twig', ['error' => null, 'last_username' => null]);
        }

        $newUser = (new User())
            ->setUsername($username);

        $encodedPassword = $userPasswordEncoder->encodePassword($newUser, $request->request->filter('password', null, FILTER_SANITIZE_STRING));

	$newUser->setPassword($encodedPassword);

	$email = $request->request->filter('email', null, FILTER_SANITIZE_STRING);
	
	dump($email);

	$newUser->setEmail($email);

	$mobileNumber = $request->request->filter('mobileNumber', null, FILTER_SANITIZE_STRING);

	dump($mobileNumber);

	$newUser->setMobileNumber($mobileNumber);

        $em->persist($newUser);
        $em->flush();

        return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess($newUser, $request, $loginFormAuthenticator, 'main');
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
