<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeamController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private EmployeeRepository $employeeRepository)
    {
    }
    #[Route('/team', name: 'team.show')]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('team/index.html.twig', [
            'pageName' => 'Équipes',
            'employees' => $employees,
        ]);
    }

    #[Route('/team/{id}/edit', name: 'team.edit')]
    public function editTeam(int $id, Request $request): Response
    {
        $employee = $this->employeeRepository->find($id);
        $formEmployee = $this->createForm(EmployeeType::class, $employee);

        $formEmployee->handleRequest($request);

        if ($formEmployee->isSubmitted() && $formEmployee->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Modifications enregistrées');
            return $this->redirectToRoute('team.show');
        }

        return $this->render('team/edit.html.twig', [
            'pageName' => 'Modifier',
            'formEmployee' => $formEmployee,
        ]);
    }
}
