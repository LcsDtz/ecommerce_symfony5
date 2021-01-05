<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    protected $encoder;
    protected $em;
    protected $utils;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, AuthenticationUtils $utils)
    {
        $this->encoder = $encoder;
        $this->utils = $utils;
        $this->em = $em;
    }
    /**
     * @Route("/registration", name="security_registration")
     */
    public function Registration(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $hash = $this->encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form_registration' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function index(AuthenticationUtils $utils)
    {
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }
}
