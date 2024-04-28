<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly ProjectRepository $projectRepository,
                                private readonly TaskRepository $taskRepository,
                                private readonly EmployeeRepository $employeeRepository)
    {
    }

    #[Route('/project/create', name: 'project.create')]
    public function addProject(Request $request): Response
    {
        $project = new Project();
        $formProject = $this->createForm(ProjectType::class, $project);

        $formProject->handleRequest($request);

        if ($formProject->isSubmitted() && $formProject->isValid()) {

            $project->setStartDate(new \DateTime());

            $this->entityManager->persist($project);
            $this->entityManager->flush();

            if ($request->request->all()['project']['employees']) {
                foreach ($request->request->all()['project']['employees'] as $employeeId) {

                    $employee = $this->employeeRepository->findOneBy(['id' => $employeeId]);

                    if ($employee) {
                        $employee->addProject($project);
                        $this->entityManager->persist($employee);
                        $this->entityManager->flush();
                    }
                }
            }

            return $this->redirectToRoute('home.show');
        }

        return $this->render('project/create.html.twig', [
            'pageName' => 'Créer un projet',
            'formProject' => $formProject->createView(),
        ]);
    }

    #[Route('/project/{id}', name: 'project.show')]
    public function index(int $id): Response
    {
        $tasks = $this->taskRepository->findByProjectId($id);
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Aucun projet trouvé');
        }

        return $this->render('task/index.html.twig', [
            'pageName' => 'Projet ' . $project->getTitle(),
            'tasks' => $tasks,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'project.edit')]
    public function editProject(int $id, Request $request): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return $this->redirectToRoute('home.show');
        }

        $formProject = $this->createForm(ProjectType::class, $project);

        $formProject->handleRequest($request);

        if ($formProject->isSubmitted() && $formProject->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('project.show', ['id' => $id]);
        }

        return $this->render('project/edit.html.twig', [
            'pageName' => 'Modifier le projet' . $project->getTitle(),
            'formProject' => $formProject,
        ]);
    }

    #[Route('/project/{id}/delete', name: 'project.delete')]
    public function deleteProject(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return $this->redirectToRoute('home.show');
        }

        $this->entityManager->remove($project);
        $this->entityManager->flush();

        return $this->redirectToRoute('home.show');
    }
}
