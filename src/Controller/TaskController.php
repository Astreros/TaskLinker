<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task.show')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'pageName' => 'Nom du projet',
        ]);
    }
}
