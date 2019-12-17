<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUsersData() as [$username, $firstName, $lastName, $email]) {
            $user = new User();
            $user->setUsername($username)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword($this->encoder->encodePassword($user, $username));

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUsersData(): array
    {
        return [
            ['user', 'User', 'Name', 'user@email.com'],
            ['admin', 'Admin', 'Name', 'admin@email.com'],
        ];
    }
}
