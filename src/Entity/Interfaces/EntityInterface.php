<?php
declare(strict_types = 1);

namespace App\Entity\Interfaces;

/**
 * Interface EntityInterface
 */
interface EntityInterface
{
	/**
	 * @return integer|null
	 */
	public function getId(): ?int;
}
