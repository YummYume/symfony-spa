<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use App\Enum\UserRoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class UserFixtures extends Fixture
{
    public const FIXTURE_RANGE = 20;

    public const REFERENCE_IDENTIFIER = 'user_';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $adminProfile = (new Profile())
            ->setUsername('Root')
        ;
        $admin = (new User())
            ->setEmail('root@root.com')
            ->setPlainPassword('root')
            ->setIsVerified(true)
            ->setRoles([UserRoleEnum::SuperAdmin->value, UserRoleEnum::AllowedToSwitch->value])
            ->setProfile($adminProfile)
        ;

        $manager->persist($admin);
        $this->addReference(self::REFERENCE_IDENTIFIER.'root', $admin);

        foreach (range(1, self::FIXTURE_RANGE) as $i) {
            $profile = (new Profile())
                ->setUsername($faker->userName())
            ;
            $user = (new User())
                ->setEmail($faker->unique()->safeEmail())
                ->setPlainPassword($faker->password(8))
                ->setIsVerified(true)
                ->setProfile($profile)
            ;

            $manager->persist($user);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $user);
        }

        $manager->flush();
    }
}