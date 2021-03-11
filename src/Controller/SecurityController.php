<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                      $request
     * @param SessionInterface             $session
     * @param EntityManagerInterface       $em
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return Response
     */
    public function register(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_my_account');
        }

        if ($request->isMethod('post')) {
            $username = $request->request->filter('username', null, FILTER_SANITIZE_STRING);

            if ($em->getRepository(User::class)->findOneBy(['username' => $username])) {
                $this->addFlash('danger', 'Username already in use.');

                return $this->render('security/register.html.twig');
            }

            $newUser = (new User())
                ->setUsername($username);

            $encodedPassword = $userPasswordEncoder->encodePassword($newUser, $request->request->filter('password', null, FILTER_SANITIZE_STRING));

            $newUser->setPassword($encodedPassword);

            $em->persist($newUser);
            $em->flush();
        }

        return $this->render('security/register.html.twig');
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
             return $this->redirectToRoute('app_my_account');
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
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
