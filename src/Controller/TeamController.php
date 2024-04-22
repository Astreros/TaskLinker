<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeamController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private EmployeRepository $employeRepository)
    {
    }
    
    #[Route('/team', name: 'team.show')]
    public function index(): Response
    {
        $employees = $this->employeRepository->findAll();

        return $this->render('team/index.html.twig', [
            'pageName' => 'Équipes',
            'employees' => $employees,
        ]);
    }

    #[Route('/team/{id}/edit', name: 'team.edit')]
    public function editTeam(int $id, Request $request): Response
    {
        $employee = $this->employeRepository->find($id);

        if (!$employee) {
            return $this->redirectToRoute('team.show');
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee->setJoiningDate(new \DateTimeImmutable());
            $this->entityManager->flush();

            return $this->redirectToRoute('team.show');
        }

        return $this->render('team/edit.html.twig', [
            'pageName' => 'Modifier',
            'form' => $form,
            'employee' => $employee,
        ]);
    }
}
