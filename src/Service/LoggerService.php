<?php
declare(strict_types = 1);

namespace App\Service;

use Psr\Log\LoggerInterface;

/**
 * Class LoggerService
 */
class LoggerService
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * LoggerService constructor.
	 *
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * @param \Throwable $e
	 * @param object     $errObject
	 * @param string     $type
	 *
	 * @throws \ReflectionException
	 */
	public function critical(\Throwable $e, object $errObject, string $type): void
	{
		$className = (new \ReflectionClass($errObject))->getShortName();
		$logType   = ucfirst($type) . $className;
		$msg       = '[%s] message: %s | file: %s | line: %s';
		$this->logger
			->critical(sprintf($msg, $logType, $e->getMessage(), $e->getFile(), $e->getLine()));
	}

	/**
	 * @param string $message
	 * @param string $type
	 */
	public function info(string $message, string $type): void
	{
		$msg = sprintf('[%s] message: %s', $type, $message);
		$this->logger->info($msg);
	}
}
