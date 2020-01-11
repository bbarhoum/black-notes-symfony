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

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public const ADMIN_USER_REFERENCE = 'admin-user';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUsersData() as [$username, $firstName, $lastName, $email, $reference, $roles]) {
            $user = new User();
            $user->setUsername($username)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setRoles($roles)
                ->setPassword($this->encoder->encodePassword($user, $username))
                ->setCreatedAt($this->faker->dateTimeBetween('last year'));
            $manager->persist($user);

            $this->addReference($reference, $user);
        }
        $manager->flush();
    }

    private function getUsersData(): array
    {
        $usersData = [
            ['admin', 'Admin', 'Name', 'admin@email.com', self::ADMIN_USER_REFERENCE, ['ROLE_ADMIN']],
            ['user', 'User', 'Name', 'user@email.com', 'user_20', ['ROLE_USER']],
        ];

        for ($i = 0; $i < 20; $i++) {
            $usersData[] = [
                $this->faker->userName,
                $this->faker->firstName,
                $this->faker->lastName,
                $this->faker->email,
                "user_$i",
                ['ROLE_USER']
            ];
        }

        return $usersData;
    }
}
