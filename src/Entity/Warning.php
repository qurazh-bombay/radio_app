<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="warnings")
 * @ORM\Entity(repositoryClass="App\Repository\WarningRepository")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Warning
{
	use IdTrait, TimestampTrait;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 */
	private $message;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", length=3)
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Length(max="3")
	 */
	private $httpStatus;

	/**
	 * @var RadioChannel|null
	 *
	 * @ORM\ManyToOne(targetEntity="RadioChannel", inversedBy="warnings")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Valid()
	 */
	private $radioChannel;

	/**
	 * @var boolean|null
	 *
	 * @ORM\Column(type="boolean", options={"default" : false})
	 * @Assert\Type(type="boolean")
	 */
	private $isFixed;

	/**
	 * Warning constructor.
	 */
	public function __construct()
	{
		$this->isFixed = false;
	}

	/**
	 * @return string|null
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * @param string|null $message
	 *
	 * @return self
	 */
	public function setMessage(?string $message): self
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getHttpStatus(): ?string
	{
		return $this->httpStatus;
	}

	/**
	 * @param string|null $httpStatus
	 *
	 * @return self
	 */
	public function setHttpStatus(?string $httpStatus): self
	{
		$this->httpStatus = $httpStatus;

		return $this;
	}

	/**
	 * @return RadioChannel|null
	 */
	public function getRadioChannel(): ?RadioChannel
	{
		return $this->radioChannel;
	}

	/**
	 * @param RadioChannel|null $radioChannel
	 *
	 * @return self
	 */
	public function setRadioChannel(?RadioChannel $radioChannel): self
	{
		$this->radioChannel = $radioChannel;

		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function getIsFixed(): ?bool
	{
		return $this->isFixed;
	}

	/**
	 * @param boolean|null $isFixed
	 *
	 * @return self
	 */
	public function setIsFixed(?bool $isFixed): self
	{
		$this->isFixed = $isFixed;

		return $this;
	}
}
