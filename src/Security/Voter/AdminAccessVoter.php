<?php 

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AdminAccessVoter implements VoterInterface
{
    public function supports(string $attribute, $subject): bool
    {
        return $subject instanceof \Symfony\Component\HttpFoundation\Request;
    }

    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $attribute = $attributes[0];
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté, retourne ACCESS_DENIED
        if (!$user instanceof Utilisateur) {
            return VoterInterface::ACCESS_DENIED;
        }

        // Si l'utilisateur a le rôle ADMIN, autorise l'accès
        if ($user->getRoles()[0] === 'ROLE_ADMIN') {
            return VoterInterface::ACCESS_GRANTED;
        }

        // Sinon, refuse l'accès
        return VoterInterface::ACCESS_DENIED;
    }
}
