<?php
declare(strict_types = 1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 */
abstract class AbstractCommand extends Command
{
	/**
	 * @var EntityManagerInterface
	 */
	protected $em;

	/**
	 * AbstractCommand constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		parent::__construct();
		$this->em = $em;
	}
}
