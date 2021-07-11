<?php
declare(strict_types = 1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UpdatedAtTrait
 *
 * Note:
 * Entities using this must have HasLifecycleCallbacks annotation.
 */
trait UpdatedAtTrait
{
	/**
	 * @var \DateTime|null
	 *
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updatedAt;

	/**
	 * @return \DateTime|null
	 */
	public function getUpdatedAt(): ?\DateTime
	{
		return $this->updatedAt;
	}

	/**
	 * @param \DateTime $updatedAt
	 *
	 * @return self
	 */
	public function setUpdatedAt(\DateTime $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * @ORM\PreUpdate
	 */
	public function onPreUpdate(): void
	{
		$this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
	}
}
