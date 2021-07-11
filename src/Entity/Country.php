<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 * @UniqueEntity(fields="label", message="Label already taken")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Country implements EntityInterface
{
	use IdTrait, TimestampTrait;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", unique=true, length=3)
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Length(max="3")
	 */
	private $label;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Length(max="50")
	 */
	private $name;

	/**
	 * @var RadioChannel[]|Collection
	 *
	 * @ORM\OneToMany(
	 *     targetEntity="RadioChannel",
	 *     mappedBy="country",
	 *     cascade={"persist"},
	 *     fetch="EXTRA_LAZY"
	 * )
	 */
	private $radioChannels;

	/**
	 * Country constructor.
	 */
	public function __construct()
	{
		$this->radioChannels = new ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return (string) ucwords(strtolower($this->name));
	}

	/**
	 * @return string|null
	 */
	public function getLabel(): ?string
	{
		return $this->label;
	}

	/**
	 * @param string|null $label
	 *
	 * @return $this
	 */
	public function setLabel(?string $label): self
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string|null $name
	 *
	 * @return $this
	 */
	public function setName(?string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return RadioChannel[]|Collection
	 */
	public function getRadioChannels(): Collection
	{
		return $this->radioChannels;
	}
}
