<?php

namespace App\Controller;

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
        $projects = $this->projectRepository->findAll();

        return $this->render('home/index.html.twig', [
            'pageName' => 'Projets',
            'projects' => $projects,
        ]);
    }
}
