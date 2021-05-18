<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Track;
use App\Entity\Comment;

class TrackFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 Catégories fakées
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category;
            $category->setTitle($faker->word())
                ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Créer entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(4, 6); $j++) {

                $track = new Track();

                $title = '' . join($faker->words(), ' ') . '';
                $duration = $faker->time('i:s', '5:00');
                $tempo = $faker->numberBetween(50, 180);
                $price = $faker->randomFloat(2, 19.99, 99.99);

                $track->setImage($faker->imageUrl(100, 100))
                    ->setTitle($title)
                    ->setCategory($category)
                    ->setType($faker->word())
                    ->setDuration($duration)
                    ->setTempo($tempo)
                    ->setPrice($price)
                    ->setCreatedAt($faker->dateTimeBetween('-1 year'));

                $manager->persist($track);

                // On donne des commentaires à la track
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();
                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    $days = (new \DateTime())->diff($track->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                        ->setTrack($track);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
