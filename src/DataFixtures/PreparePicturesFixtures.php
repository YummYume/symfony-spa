<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;

final class PreparePicturesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        (new Filesystem())
            ->mirror(__DIR__.'/pictures', __DIR__.'/tmp')
        ;
    }
}
