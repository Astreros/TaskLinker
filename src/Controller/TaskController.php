<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly TaskRepository         $taskRepository,
                                private readonly StatusRepository       $statusRepository,
                                private readonly ProjectRepository      $projectRepository,
                                private readonly EmployeeRepository     $employeeRepository)
    {
    }

    #[Route('/tasks/{id}/create', name: 'task.create')]
    #[IsGranted('PROJECT_ACCESS', 'id')]
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

        $projectId = $task->getProject()->getId();

        $employees = $this->employeeRepository->findByProject($projectId);

        $formTask = $this->createForm(TaskType::class, $task, [
            'employees' => $employees,
        ]);

        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

//            $projectId = $task->getProject()->getId();

            return $this->redirectToRoute('project.show', ['id' => $projectId]);
        }

        return $this->render('task/create.html.twig', [
            'pageName' => 'Créer une tâche',
            'formTask' => $formTask->createView(),
        ]);
    }

    #[Route('/task', name: 'task.show')]
    #[IsGranted('TASK_ACCESS', 'id')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'pageName' => 'Tâche',
        ]);
    }

    #[Route('/task/{id}/edit', name: 'task.edit')]
    #[IsGranted('TASK_ACCESS', 'id')]
    public function editTask(int $id, Request $request): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return $this->redirectToRoute('project.show', ['id' => $id]);
        }

        $projectId = $task->getProject()->getId();

        $employees = $this->employeeRepository->findByProject($projectId);

        $formTask = $this->createForm(TaskType::class, $task, [
            'employees' => $employees,
        ]);

        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('project.show', ['id' => $projectId]);
        }

        return $this->render('task/edit.html.twig', [
            'pageName' => 'Modifier la tâche',
            'formTask' => $formTask->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/delete', name: 'task.delete')]
    #[IsGranted('TASK_ACCESS', 'id')]
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
