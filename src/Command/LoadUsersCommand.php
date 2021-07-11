<?php
declare(strict_types = 1);

namespace App\Command;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class LoadUsersCommand
 *
 * php bin/console app:load:users
 */
class LoadUsersCommand extends AbstractCommand
{
	/**
	 * @var string
	 */
	protected static $defaultName = 'app:load:users';

	/**
	 * Set description.
	 */
	protected function configure()
	{
		$this->setDescription('This command loads users.');
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return integer|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		try {
			foreach ($this->getUserData() as $userData) {
				$this->em->persist(User::create($userData));
			}

			$this->em->flush();
		} catch (\Exception $e) {
			$io->error($e->getMessage());

			return Command::FAILURE;
		}

		$io->success('Users are created successfully');

		return Command::SUCCESS;
	}

	/**
	 * @return array|array[]
	 */
	private function getUserData(): array
	{
		// todo SET_ADMIN_PASSWORD_HERE
		return [
			[
				'admin',
				UserRoleEnum::ADMIN,
				'SET_ADMIN_PASSWORD_HERE',
				true,
			],
			[
				'visitor',
				UserRoleEnum::VISITOR,
				'111',
				false,
			],
		];
	}
}
