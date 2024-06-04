<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends Voter
{
    public const PROJECT_ACCESS = 'PROJECT_ACCESS';
    public const TASK_ACCESS = 'TASK_ACCESS';

    public function __construct(private readonly ProjectRepository $projectRepository,
                                private readonly TaskRepository    $taskRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::PROJECT_ACCESS || $attribute === self::TASK_ACCESS;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if($attribute === self::PROJECT_ACCESS) {
            $project = $this->projectRepository->find($subject);
        } else {
            $task = $this->taskRepository->find($subject);
            $project = $task?->getProject();
        }

        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface || !$project instanceof Project) {
            return false;
        }

//        if (!$project instanceof Project) {
//            return false;
//        }

//        // ... (check conditions and return true to grant permission) ...
//        switch ($attribute) {
//            case self::PROJECT_ACCESS:
//            case self::TASK_ACCESS:
//                return $subject->getEmployees()->contains($user) || $user->isAdmin();
//                break;
//        }

        return $user->isAdmin() || $project->getEmployees()->contains($user);
    }
}
