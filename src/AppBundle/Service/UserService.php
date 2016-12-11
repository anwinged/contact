<?php

namespace AppBundle\Service;

use AppBundle\Document\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param ObjectManager                $om
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        ObjectManager $om,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->om = $om;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Creates new user with email and password.
     *
     * @param string $email
     * @param string $plainPassword
     *
     * @return User
     */
    public function createUser(string $email, string $plainPassword): User
    {
        $user = new User();
        $user->setEmail($email);
        $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);

        $this->om->persist($user);
        $this->om->flush();

        return $user;
    }
}
