<?php

namespace App\Tests\Manager;

use App\Manager\LogManager;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LogManagerTest extends KernelTestCase
{
    private LogManager $logManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->logManager = self::getContainer()->get('test.'.LogManager::class);
    }

    public function testCriticalLog(): void
    {
        $this->assertEquals(LogManager::SUCCESS, $this->logManager->logException(new \Exception('Test exception.')));
    }

    public function testLogCallable(): void
    {
        /** @var LoggerInterface|MockObject */
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo(LogLevel::EMERGENCY),
                $this->stringEndsWith(__FUNCTION__),
                $this->logicalAnd($this->arrayHasKey('exception'), $this->arrayHasKey('trace'))
            )
        ;
        $logManager = new LogManager($logger);

        $logManager->logException(new \Exception('Test exception.'), LogLevel::EMERGENCY);
    }
}
