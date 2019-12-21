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
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < rand(20, 40); $i++) {
            /** @var User $user */
            $user = $this->getReference('user_'.rand(0, 19));

            $todo = new Todo();
            $todo->setTitle($faker->sentence)
                ->setDueDate($faker->dateTimeBetween('now', 'next year'))
                ->setIsDone(rand(0, 1) === 1)
                ->setOwner($user)
                ->setCreatedAt($faker->dateTimeBetween('last year', 'next year'));

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
