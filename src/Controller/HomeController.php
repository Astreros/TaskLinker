<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
    }

    #[Route('/', name: 'home.show')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $projects = null;

            if ($this->isGranted('ROLE_ADMIN')) {
                $projects = $this->projectRepository->findAll();

            } else {
                $user = $this->getUser();

                if ($user instanceof Employee) {
                    $projects = $user->getProject();
                }
            }

            return $this->render('home/index.html.twig', [
                'pageName' => 'Projets',
                'projects' => $projects,
            ]);
        }

        return $this->render('home/welcome.html.twig', [
            'pageName' => 'Bienvenue'
        ]);
    }
}
