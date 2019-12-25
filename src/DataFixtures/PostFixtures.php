<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            /** @var User $user */
            $user = $this->getReference('user_'.rand(0, 20));

            $post = new Post();
            $post->setTitle($faker->sentence)
                ->setDescription($faker->paragraph)
                ->setContent('<p>'.implode('</p><p>', $faker->paragraphs(rand(3, 10))).'</p>')
                ->setImage($faker->imageUrl(680, 280))
                ->setCreatedBy($user)
                ->setCreatedAt($faker->dateTimeBetween('last year'));
            $post->setSlug(Slugger::slugify($post->getTitle()));
            if (rand(0, 1) === 1) {
                $post->setUpdatedAt(
                    $faker->dateTimeBetween('-'.$post->getCreatedAt()->diff(new \DateTime())->days.' days')
                );
            }

            $manager->persist($post);

            for ($j = 0; $j < rand(0, 20); $j++) {
                /** @var User $user */
                $user = $this->getReference('user_'.rand(0, 20));

                $comment = new Comment();
                $comment->setPost($post)
                    ->setCreatedBy($user)
                    ->setContent(implode(' ', $faker->paragraphs(rand(1, 3))))
                    ->setCreatedAt(
                        $faker->dateTimeBetween('-'.$post->getCreatedAt()->diff(new \DateTime())->days.' days')
                    );

                if (rand(0, 1) === 1) {
                    $post->setUpdatedAt(
                        $faker->dateTimeBetween('-'.$comment->getCreatedAt()->diff(new \DateTime())->days.' days')
                    );
                }

                $manager->persist($comment);
            }
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
