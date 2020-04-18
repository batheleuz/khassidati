<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function index(AuthenticationUtils $authenticationUtils, Security $security)
    {
        if ($security->getUser() instanceof User) {
            return $this->forward("App\Controller\DefaultController::admin");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/admin", methods={"GET"})
     */
    public function admin()
    {
        return $this->render('admin.html.twig');
    }
}