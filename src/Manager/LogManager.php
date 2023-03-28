<?php

namespace App\Manager;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class LogManager
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function logException(\Exception $exception, string $level = LogLevel::CRITICAL): int
    {
        $exceptionTrace = $exception->getTrace();

        try {
            $this->logger->log($level, sprintf('%s - %s', static::class, $exceptionTrace[0]['function']), [
                'exception' => $exception->getMessage(),
                'trace' => $exceptionTrace,
            ]);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->critical(sprintf('Log using App\Manager\LogUtilsTrait::log with level "%s" failed', $level), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'originalException' => $exception->getMessage(),
                'originalTrace' => $exceptionTrace,
            ]);

            return self::FAILURE;
        }
    }
}
