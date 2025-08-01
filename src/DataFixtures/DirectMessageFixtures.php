<?php

namespace App\DataFixtures;

use App\Entity\DirectMessage;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DirectMessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $senderId = rand(1, 10);
            do {
                $receiverId = rand(1, 10);
            } while ($receiverId === $senderId);

            $dm = new DirectMessage();
            $dm->setContent($faker->text(100));
            $dm->setCreatedAt(new \DateTimeImmutable());
            $dm->setSender($this->getReference("user_$senderId", User::class));
            $dm->setReceiver($this->getReference("user_$receiverId", User::class));

            $manager->persist($dm);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
