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
    public const FIXTURE_RANGE = 200;

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
            ->setVerified(true)
            ->setRoles([UserRoleEnum::SuperAdmin->value, UserRoleEnum::AllowedToSwitch->value])
            ->setProfile($adminProfile)
        ;

        $manager->persist($admin);
        $this->addReference(self::REFERENCE_IDENTIFIER.'root', $admin);

        foreach (range(1, self::FIXTURE_RANGE) as $i) {
            $profile = (new Profile())
                ->setUsername(str_replace('.', ' ', $faker->unique()->userName()))
                ->setDescription($faker->paragraph(5))
            ;
            $user = (new User())
                ->setEmail($faker->unique()->safeEmail())
                ->setPlainPassword($faker->password(8))
                ->setVerified(true)
                ->setProfile($profile)
            ;

            $manager->persist($user);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $user);
        }

        $manager->flush();
    }
}
