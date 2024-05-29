<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
    }

    #[Route('/', name: 'home.show')]
    #[IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        if (!isset($_SESSION['user'])) {

            return $this->render('home/welcome.html.twig', [
                'pageName' => 'Bienvenue'
            ]);
        }

        $projects = $this->projectRepository->findAll();

        return $this->render('home/index.html.twig', [
            'pageName' => 'Projets',
            'projects' => $projects,
        ]);
    }

}
