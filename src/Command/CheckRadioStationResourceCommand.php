<?php
declare(strict_types = 1);

namespace App\Command;

use App\Entity\RadioChannel;
use App\Entity\Warning;
use App\Repository\RadioChannelRepository;
use App\Service\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CheckRadioStationResourceCommand
 *
 * cron task: 0 1 * * * php bin/console app:check:channel:resource
 */
class CheckRadioStationResourceCommand extends AbstractCommand
{
	/**
	 * @var LoggerService
	 */
	private $logger;

	/**
	 * @var string
	 */
	protected static $defaultName = 'app:check:channel:resource';

	/**
	 * CheckRadioStationResourceCommand constructor.
	 *
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 */
	public function __construct(EntityManagerInterface $em, LoggerService $logger)
	{
		parent::__construct($em);

		$this->logger = $logger;
	}

	/**
	 * Set description.
	 */
	protected function configure()
	{
		$description = <<<DESC
This command checks radio channel resource. 
If it is not available anymore, then adds warning notice
to data base.
DESC;

		$this->setDescription($description);
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return integer
	 * @throws \ReflectionException
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		try {
			/**
			 * @var RadioChannelRepository $repo
			 */
			$repo = $this->em->getRepository(RadioChannel::class);

			foreach ($repo->findAll() as $channel) {
				$url      = $channel->getUrl();
				$response = $this->getHeadersOrNull($url);

				if ($response === null) {
					$msg = 'Response is NULL on URL: ' . $url;
					$this->logger->info($msg, 'CheckRadioStationResourceCommand');
					continue;
				}

				if ($this->isStatusCodeValid($response->code)) {
					continue;
				}

				$warning = new Warning();
				$warning->setHttpStatus($response->code)
					->setMessage($response->message)
					->setRadioChannel($channel);

				$this->em->persist($warning);
			}

			$this->em->flush();
		} catch (\Throwable $e) {
			$io->error($e->getMessage());
			$this->logger->critical($e, $this, 'CLI');

			return Command::FAILURE;
		}

		$msg = 'Radio channels resources are checked successfully';
		$this->logger->info($msg, 'CheckRadioStationResourceCommand');
		$io->success($msg);

		return Command::SUCCESS;
	}

	/**
	 * @param string $url
	 *
	 * @return object|null
	 */
	private function getHeadersOrNull(string $url): ?object
	{
		$response = new \stdClass();
		$headers  = get_headers($url, 1);

		if ($headers) {
			$httpStatus        = explode(' ', $headers[0]);
			$response->code    = $httpStatus[1];
			$response->message = isset($httpStatus[2])
				? implode(' ', array_slice($httpStatus, 2))
				: 'no message in response';

			return $response;
		}

		return null;
	}

	/**
	 * @param string $code
	 *
	 * @return boolean
	 */
	private function isStatusCodeValid(string $code): bool
	{
		return !(str_starts_with($code, '4') or str_starts_with($code, '5'));
	}
}
