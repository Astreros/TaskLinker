<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks/create', name: 'task.create')]
    public function addTask(): Response
    {
        return $this->render('task/create.html.twig', [
            'pageName' => 'Créer une tâche',
        ]);
    }

    #[Route('/task', name: 'task.show')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'pageName' => 'Nom du projet',
        ]);
    }

    #[Route('/task/{id}/edit', name: 'task.edit')]
    public function editTask(int $id): Response
    {
        return $this->render('task/edit.html.twig', [
            'pageName' => 'Modifier la tâche',
        ]);
    }
}
