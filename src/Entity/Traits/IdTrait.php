<?php
declare(strict_types = 1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait IdTrait
 */
trait IdTrait
{
	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned"=true})
	 */
	protected $id;

	/**
	 * @return integer|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
}
