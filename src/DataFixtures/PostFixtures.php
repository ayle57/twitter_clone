<?php

namespace App\DataFixtures;

use App\Entity\Hashtag;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $post = new Post();
            $post->setContent($faker->realText(200));
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setUpdatedAt(new \DateTimeImmutable());
            for($j = 0; $j < rand(1, 3); $j++) {
                $hashtag = $this->getReference('hashtag_' . rand(0, 19), Hashtag::class);
                $post->addTag($hashtag);
            }

            $user = $this->getReference('user_' . rand(1, 10), User::class);
            $post->setAuthor($user);

            $manager->persist($post);
            $this->addReference("post_$i", $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, HashtagFixtures::class];
    }
}
