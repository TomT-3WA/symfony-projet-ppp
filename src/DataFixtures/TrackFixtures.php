<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Track;

class TrackFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $track = new Track();
            $track->setImage("https://picsum.photos/52")
                ->setTitle("Titre de la piste n°$i")
                ->setType("Type de la piste n°$i")
                ->setDuration("Durée de la piste n°$i")
                ->setTempo("BPM de la piste n°$i")
                ->setPrice("29,99 €")
                ->setCreatedAt(new \DateTime());

            $manager->persist($track);
        }

        $manager->flush();
    }
}
