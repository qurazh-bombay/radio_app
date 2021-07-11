<?php
declare(strict_types = 1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait CreatedAtTrait
 *
 * Note:
 * Entities using this must have HasLifecycleCallbacks annotation.
 */
trait CreatedAtTrait
{
	/**
	 * @var \DateTime|null
	 *
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @return \DateTime|null
	 */
	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 *
	 * @return self
	 */
	public function setCreatedAt(\DateTime $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist(): void
	{
		if (null === $this->createdAt) {
			$this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
		}
	}
}
