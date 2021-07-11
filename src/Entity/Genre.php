<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="genres")
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Genre implements EntityInterface
{
	use IdTrait, TimestampTrait;

	/**
	 * @var RadioChannel[]|Collection
	 *
	 * @ORM\OneToMany(
	 *     targetEntity="RadioChannel",
	 *     mappedBy="genre",
	 *     cascade={"persist"},
	 *     fetch="EXTRA_LAZY"
	 * )
	 */
	private $radioChannels;

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
	 * Genre constructor.
	 */
	public function __construct()
	{
		$this->radioChannels = new ArrayCollection();
	}

	/**
	 * @return RadioChannel[]|Collection
	 */
	public function getRadioChannels(): Collection
	{
		return $this->radioChannels;
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
	 * @return self
	 */
	public function setName(?string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function __toString(): ?string
	{
		return (string) ucwords(strtolower($this->name));
	}
}
