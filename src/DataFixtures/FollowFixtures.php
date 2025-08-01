<?php

namespace App\DataFixtures;

use App\Entity\Follow;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FollowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $used = [];

        for ($i = 0; $i < 20; $i++) {
            $followerId = rand(1, 10);
            do {
                $followingId = rand(1, 10);
            } while ($followingId === $followerId || in_array([$followerId, $followingId], $used));

            $used[] = [$followerId, $followingId];

            $follow = new Follow();
            $follow->setFollower($this->getReference("user_$followerId", User::class));
            $follow->setFollowing($this->getReference("user_$followingId", User::class));

            $manager->persist($follow);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
