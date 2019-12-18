<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TodoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $references = [
            UsersFixtures::USER_REFERENCE,
            UsersFixtures::ADMIN_USER_REFERENCE,
        ];

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < random_int(20, 40); $i++) {
            /** @var User $user */
            $user = $this->getReference($references[array_rand($references, 1)]);
            $todo = new Todo();
            $todo->setTitle($faker->sentence)
                ->setDueDate($faker->dateTimeBetween('now', 'next year'))
                ->setOwner($user);
            $manager->persist($todo);
        }

        $manager->flush();
    }


    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UsersFixtures::class
        ];
    }
}
