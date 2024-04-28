<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly TaskRepository $taskRepository,
                                private readonly StatusRepository $statusRepository,
                                private readonly ProjectRepository $projectRepository)
    {
    }

    #[Route('/tasks/{id}/create', name: 'task.create')]
    public function addTask($id, Request $request): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException();
        }

        $task = new Task();

        $task->setProject($project);

        $statusName = $request->query->get('status');
        $status = $this->statusRepository->findOneBy(['name' => ucfirst($statusName)]);
        $task->setStatus($status);

        $formTask = $this->createForm(TaskType::class, $task);

        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $projectId = $task->getProject()->getId();

            return $this->redirectToRoute('project.show', ['id' => $projectId]);
        }

        return $this->render('task/create.html.twig', [
            'pageName' => 'Créer une tâche',
            'formTask' => $formTask->createView(),
        ]);
    }

    #[Route('/task', name: 'task.show')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'pageName' => 'Tâche',
        ]);
    }

    #[Route('/task/{id}/edit', name: 'task.edit')]
    public function editTask(int $id, Request $request): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return $this->redirectToRoute('project.show', ['id' => $id]);
        }

        $formTask = $this->createForm(TaskType::class, $task);

        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('project.show', ['id' => $id]);
        }

        return $this->render('task/edit.html.twig', [
            'pageName' => 'Modifier la tâche',
            'formTask' => $formTask->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/delete', name: 'task.delete')]
    public function deleteTask(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return $this->redirectToRoute('home.show');
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $this->redirectToRoute('home.show');
    }
}
