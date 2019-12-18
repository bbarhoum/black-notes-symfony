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

    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const USER_REFERENCE = 'user';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUsersData() as [$username, $firstName, $lastName, $email, $reference]) {
            $user = new User();
            $user->setUsername($username)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPassword($this->encoder->encodePassword($user, $username));
            $manager->persist($user);

            $this->addReference($reference, $user);
        }
        $manager->flush();
    }

    private function getUsersData(): array
    {
        return [
            ['user', 'User', 'Name', 'user@email.com', self::USER_REFERENCE],
            ['admin', 'Admin', 'Name', 'admin@email.com', self::ADMIN_USER_REFERENCE],
        ];
    }
}
