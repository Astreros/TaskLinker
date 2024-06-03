<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    #[Route('/registration', name: 'registration.show')]
    public function addEmployee(Request $request): Response
    {
        $employee = new Employee();
        $formRegistration = $this->createForm(RegistrationType::class, $employee);

        $formRegistration->handleRequest($request);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            $employee->setPassword($this->userPasswordHasher->hashPassword($employee, $employee->getPassword()));
            $employee->setContractType('CDI')->setJoiningDate(new \DateTime());

            $this->entityManager->persist($employee);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'pageName' => 'Inscription',
            'formRegistration' => $formRegistration->createView(),
        ]);
    }
}
