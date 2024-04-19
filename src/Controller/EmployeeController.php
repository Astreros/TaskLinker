<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'employee.show')]
    public function index(): Response
    {
        return $this->render('employee/index.html.twig', [
            'pageName' => 'Employés',
        ]);
    }
}
