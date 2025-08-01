<?php

namespace App\DataFixtures;

use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 40; $i++) {
            $like = new Like();
            $like->setCreatedAt(new \DateTimeImmutable());
            $like->setAuthor($this->getReference('user_' . rand(1, 10), User::class));
            $like->setPost($this->getReference('post_' . rand(1, 20), Post::class));

            $manager->persist($like);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, PostFixtures::class];
    }
}
