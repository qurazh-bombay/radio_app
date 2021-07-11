<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\RadioChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class RadioChannelRepository
 *
 * @method RadioChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method RadioChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method RadioChannel[]    findAll()
 * @method RadioChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RadioChannelRepository extends ServiceEntityRepository
{
	/**
	 * RadioChannelRepository constructor.
	 *
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, RadioChannel::class);
	}

	/**
	 * @return QueryBuilder
	 */
	public function getRadioChannelListQuery(): QueryBuilder
	{
		return $this->createQueryBuilder('channel')
			->leftJoin('channel.genre', 'genre')
			->leftJoin('channel.country', 'country')
			->orderBy('channel.name', 'ASC');
	}

	/**
	 * @param integer $id
	 *
	 * @return string|null
	 */
	public function findSourceOrNull(int $id): ?string
	{
		try {
			$channel = $this->createQueryBuilder('channel')
				->select('channel.url')
				->andWhere('channel.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getOneOrNullResult();

			return $channel['url'];
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}

	/**
	 * @param string $query
	 *
	 * @return array
	 */
	public function searchRadioChannel(string $query): array
	{
		return $this->createQueryBuilder('channel')
			->andWhere('channel.name LIKE :q')
			->setParameter('q', "%{$query}%")
			->getQuery()
			->getResult();
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function findRadioChannelListByCountryAndGenre(array $data): array
	{
		$query = $this->createQueryBuilder('channel');

		if (!$data['genre']->isEmpty()) {
			$query->andWhere('channel.genre IN (:genres)')
				->setParameter('genres', $data['genre']);
		}

		if (!$data['country']->isEmpty()) {
			$query->andWhere('channel.country IN (:countries)')
				->setParameter('countries', $data['country']);
		}

		return $query->getQuery()->getResult();
	}
}
