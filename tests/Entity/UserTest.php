<?php

namespace App\Tests\Entity;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public static function userProvider(): array
    {
        return [
            'Valid' => [0, (new User())
                ->setEmail('moderator@stack-up.tech')
                ->setPlainPassword('ERGZ$vdbr4320')
                ->setProfile((new Profile())->setUsername('TestModerator')),
            ],
            'Invalid email and password' => [2, (new User())
                ->setEmail('test123')
                ->setPlainPassword('test123')
                ->setProfile((new Profile())->setUsername('Test123')),
            ],
            'Invalid profile (username contains invalid characters)' => [1, (new User())
                ->setEmail('user@stack-up.tech')
                ->setPlainPassword('ERGZ$vdbr4320')
                ->setProfile((new Profile())->setUsername('Test$$$')),
            ],
            'Invalid profile (username is blank)' => [1, (new User())
                ->setEmail('user@stack-up.tech')
                ->setPlainPassword('ERGZ$vdbr4320')
                ->setProfile((new Profile())->setUsername(null)),
            ],
        ];
    }

    /**
     * @dataProvider userProvider
     */
    public function testUsers(int $numErrors, User $user): void
    {
        $errors = $this->validator->validate($user);
        $count = \count($errors);

        $this->assertEquals(
            $numErrors,
            $count,
            sprintf(
                'Expected %s error(s) but got %s error(s) instead. Errors : %s',
                $numErrors,
                $count,
                \PHP_EOL.implode(\PHP_EOL, array_map(static fn (ConstraintViolation $error): string => $error->getMessage(), [...$errors]))
            )
        );
    }
}
