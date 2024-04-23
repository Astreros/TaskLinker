<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'team.show')]
    public function index(): Response
    {
        return $this->render('team/index.html.twig', [
            'pageName' => 'Équipes',
        ]);
    }

    #[Route('/team/{id}/edit', name: 'team.edit')]
    public function editTeam(int $id): Response
    {
        return $this->render('team/edit.html.twig', [
            'pageName' => 'Modifier'
        ]);
    }
}
