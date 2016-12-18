<?php

namespace AppBundle\Security;

use AppBundle\Document\Catcher;
use AppBundle\Document\Project;
use AppBundle\Document\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CatcherVoter extends Voter
{
    const ACCESS = 'access';

    /**
     * {@inheritdoc}
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return strtolower($attribute) === self::ACCESS && $subject instanceof Catcher;
    }

    /**
     * {@inheritdoc}
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        /** @var Catcher $catcher */
        $catcher = $subject;

        /** @var Project $project */
        $project = $catcher->getProject();

        return $project->getUser()->getId() === $user->getId();
    }
}
