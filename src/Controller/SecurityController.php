<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Throwable;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                      $request
     * @param EntityManagerInterface       $em
     * @param UserPasswordHasherInterface  $passwordHasher
     * @param GuardAuthenticatorHandler    $guardAuthenticatorHandler
     * @param LoginFormAuthenticator       $loginFormAuthenticator
     *
     * @return Response
     */
    public function register(Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        LoginFormAuthenticator $loginFormAuthenticator
    ): Response {
        try {
            if ($this->getUser()) {
                return $this->redirectToRoute('app_index');
            }

            if (!$request->isMethod('post')) {
                throw new MethodNotAllowedHttpException(['POST'], 'Incorrect HTTP request method received.');
            }

            $username = $request->request->filter('username', null, FILTER_SANITIZE_STRING);

            if ($em->getRepository(User::class)->findOneBy(['username' => $username])) {
                throw new BadRequestHttpException('Username already in use.');
            }

            $newUser = (new User())
                ->setUsername($username)
                ->setHoseUser('n');

            $plainTextPassword = $request->request->filter('password', null, FILTER_SANITIZE_STRING);

            if ('dev' !== $this->getParameter('app.env') && (
                8 > strlen($plainTextPassword) ||
                0 === preg_match_all('/[^a-z0-9A-Z]/', $plainTextPassword) ||
                0 === preg_match_all('/[a-z]/', $plainTextPassword) ||
                0 === preg_match_all('/[A-Z]/', $plainTextPassword) ||
                0 === preg_match_all('/[\d]/', $plainTextPassword)
            )) {
                throw new BadRequestHttpException('Password requirements not met! You should provide a secure password of 8 characters or more with lowercase, uppercase, and special characters');
            }

            $encodedPassword = $passwordHasher->hashPassword($newUser, $plainTextPassword);

            $newUser->setPassword($encodedPassword);

            $em->persist($newUser);
            $em->flush();

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess($newUser, $request, $loginFormAuthenticator, 'main');
        } catch (Throwable $t) {
            $this->addFlash('danger', $t->getMessage());

            return $this->redirectToRoute('app_login');
        }
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
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
