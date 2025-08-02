<?php

namespace App\DataFixtures;

use App\Entity\Hashtag;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $dummyPath = __DIR__ . '/files/dummy.avif';

        if (!file_exists($dummyPath)) {
            throw new \RuntimeException("L'image dummy.avif est introuvable à l'emplacement : $dummyPath");
        }

        for ($i = 1; $i <= 20; $i++) {
            $post = new Post();
            $post->setContent($faker->realText(200));
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setUpdatedAt(new \DateTimeImmutable());

            // Ajout de hashtags aléatoires (entre 1 et 3)
            $tagCount = rand(1, 3);
            $usedTags = [];

            while (count($usedTags) < $tagCount) {
                $index = rand(0, 19);
                if (!in_array($index, $usedTags)) {
                    $hashtag = $this->getReference('hashtag_' . $index, Hashtag::class);
                    $post->addTag($hashtag);
                    $usedTags[] = $index;
                }
            }

            // Attribution de l’auteur
            $user = $this->getReference('user_' . rand(1, 10), User::class);
            $post->setAuthor($user);

            // Gérer l’image .avif (copie temporaire pour UploadedFile)
            $tempPath = sys_get_temp_dir() . '/dummy_' . uniqid() . '.avif';
            copy($dummyPath, $tempPath);

            $thumbnailFile = new UploadedFile(
                $tempPath,
                basename($tempPath),
                'image/avif',
                null,
                true // test mode
            );

            $post->setThumbnailFile($thumbnailFile);

            $manager->persist($post);
            $this->addReference("post_$i", $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            HashtagFixtures::class,
        ];
    }
}
