<?php

namespace App\DataFixtures;

use App\Entity\Hashtag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HashtagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $tag = '#' . $faker->unique()->word();

            $hashtag = new Hashtag();
            $hashtag->setTag($tag);

            $manager->persist($hashtag);
        }

        $manager->flush();
    }
}
