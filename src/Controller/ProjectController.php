<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/project/create', name: 'project.create')]
    public function addProject(): Response
    {
        return $this->render('project/create.html.twig', [
            'pageName' => 'Créer un projet',
        ]);
    }

    #[Route('/project/{id}', name: 'project.show')]
    public function index(int $id): Response
    {
        return $this->render('project/index.html.twig', [
            'pageName' => 'Projet ' . $id,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'project.edit')]
    public function editProject(int $id): Response
    {
        return $this->render('project/edit.html.twig', [
            'pageName' => 'Modifier le projet' . $id,
        ]);
    }
}
