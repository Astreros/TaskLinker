<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login1', name: 'login.show')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'pageName' => 'Connexion',
        ]);
    }
}
