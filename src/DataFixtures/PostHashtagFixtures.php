<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostHashtagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $posts = $manager->getRepository(Post::class)->findAll();
        $hashtags = $manager->getRepository('App\Entity\Hashtag')->findAll();

        if (!$posts || !$hashtags) {
            return;
        }

        foreach ($posts as $post) {
            $randomHashtags = $faker->randomElements($hashtags, rand(1, 3));

            foreach ($randomHashtags as $hashtag) {
                $post->addTag($hashtag);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
            HashtagFixtures::class,
        ];
    }
}
