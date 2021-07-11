<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\FileUploadInterface;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="radio_channels")
 * @ORM\Entity(repositoryClass="App\Repository\RadioChannelRepository")
 *
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class RadioChannel implements FileUploadInterface, EntityInterface
{
	use IdTrait, TimestampTrait;

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
	 * @var string|null
	 *
	 * @ORM\Column(type="string", length=2000)
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Url()
	 * @Assert\Length(max="2000")
	 */
	private $url;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", nullable=true, length=50)
	 * @Assert\Length(max="50")
	 */
	private $img;

	/**
	 * NOTE: This is not a mapped field of entity metadata, just a simple property.
	 *
	 * @Vich\UploadableField(mapping="default", fileNameProperty="img")
	 *
	 * @var File|null
	 */
	private $imgFile;

	/**
	 * @var Country|null
	 *
	 * @ORM\ManyToOne(targetEntity="Country", inversedBy="radioChannels")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Valid()
	 */
	private $country;

	/**
	 * @var Genre|null
	 *
	 * @ORM\ManyToOne(targetEntity="Genre", inversedBy="radioChannels")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Valid()
	 */
	private $genre;

	/**
	 * @var Warning[]|Collection
	 *
	 * @ORM\OneToMany(
	 *     targetEntity="Warning",
	 *     mappedBy="radioChannel",
	 *     cascade={"persist"},
	 *     fetch="EXTRA_LAZY"
	 * )
	 */
	private $warnings;

	/**
	 * RadioChannel constructor.
	 */
	public function __construct()
	{
		$this->warnings = new ArrayCollection();
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
	 * @return string|null
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}

	/**
	 * @param string|null $url
	 *
	 * @return $this
	 */
	public function setUrl(?string $url): self
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImg(): ?string
	{
		return $this->img;
	}

	/**
	 * @param string|null $img
	 *
	 * @return $this
	 */
	public function setImg(?string $img): self
	{
		$this->img = $img;

		return $this;
	}

	/**
	 * @return Country|null
	 */
	public function getCountry(): ?Country
	{
		return $this->country;
	}

	/**
	 * @param Country|null $country
	 *
	 * @return $this
	 */
	public function setCountry(?Country $country): self
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * @return Genre|null
	 */
	public function getGenre(): ?Genre
	{
		return $this->genre;
	}

	/**
	 * @param Genre|null $genre
	 *
	 * @return $this
	 */
	public function setGenre(?Genre $genre): self
	{
		$this->genre = $genre;

		return $this;
	}

	/**
	 * @return File|null
	 */
	public function getImgFile(): ?File
	{
		return $this->imgFile;
	}

	/**
	 * @param File|null $imgFile
	 *
	 * @return $this
	 */
	public function setImgFile(?File $imgFile = null): self
	{
		$this->imgFile = $imgFile;

		if (null !== $imgFile) {
			// It is required that at least one field changes if you are using doctrine
			// otherwise the event listeners won't be called and the file is lost
			$this->updatedAt = new \DateTimeImmutable();
		}

		return $this;
	}
}
