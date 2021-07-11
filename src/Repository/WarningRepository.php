<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Warning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class WarningRepository
 *
 * @method Warning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warning[]    findAll()
 * @method Warning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarningRepository extends ServiceEntityRepository
{
	/**
	 * WarningRepository constructor.
	 *
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Warning::class);
	}

	/**
	 * @return QueryBuilder
	 */
	public function getActiveWarnings(): QueryBuilder
	{
		return $this->createQueryBuilder('w')
			->leftJoin('w.radioChannel', 'channel')
			->andWhere('w.isFixed = 0');
	}
}
