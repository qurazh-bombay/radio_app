<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use App\Enum\UserRoleEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Username already taken")
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({"App\EventListener\UserEventListener"})
 */
class User implements UserInterface
{
	use IdTrait, TimestampTrait;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", length=255, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 */
	private $username;

	/**
	 * @var string|null
	 *
	 * @Assert\NotBlank()
	 * @Assert\NotNull()
	 * @Assert\Length(max=10)
	 */
	private $plainPassword;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;

	/**
	 * @var array
	 *
	 * @ORM\Column(type="array")
	 */
	private $roles;

	/**
	 * @var boolean|null
	 *
	 * @ORM\Column(type="boolean", options={"default" : false})
	 * @Assert\Type(type="boolean")
	 */
	private $isAdmin;

	/**
	 * User constructor.
	 */
	public function __construct()
	{
		$this->roles   = [UserRoleEnum::USER];
		$this->isAdmin = false;
	}

	/**
	 * @return string|null
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function eraseCredentials()
	{
	}

	/**
	 * @return string|null
	 */
	public function getUsername(): ?string
	{
		return $this->username;
	}

	/**
	 * @param string|null $username
	 *
	 * @return self
	 */
	public function setUsername(?string $username): self
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}

	/**
	 * @param string|null $plainPassword
	 * allow NULL for correct validation
	 *
	 * @return self
	 */
	public function setPlainPassword(?string $plainPassword): self
	{
		$this->plainPassword = $plainPassword;

		try {
			$this->password = random_bytes(10); // trigger for class userEventListener
		} catch (\Exception $e) {
			$this->password = 'trigger for UserEventListener';
		}

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * @param string|null $password
	 *
	 * @return self
	 */
	public function setPassword(?string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @return boolean|null
	 */
	public function getIsAdmin(): ?bool
	{
		return $this->isAdmin;
	}

	/**
	 * @param boolean|null $isAdmin
	 *
	 * @return self
	 */
	public function setIsAdmin(?bool $isAdmin): self
	{
		$this->isAdmin = $isAdmin;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getRoles(): array
	{
		return array_unique($this->roles);
	}

	/**
	 * @param string|array $roles
	 *
	 * @return self
	 */
	public function setRoles($roles): self
	{
		$this->roles = (array) $roles;

		return $this;
	}

	/**
	 * @param string $role
	 *
	 * @return boolean
	 */
	public function hasRole(string $role): bool
	{
		return in_array($role, $this->roles, true);
	}

	/**
	 * @param string $role
	 *
	 * @return self
	 */
	public function addRole(string $role): self
	{
		try {
			if ($this->hasRole($role)) {
				return $this;
			}

			if (UserRoleEnum::checkValue($role)) {
				$this->roles[] = $role;
			}
		} catch (\Exception $e) {
		}

		return $this;
	}

	/**
	 * @param string $role
	 *
	 * @return $this
	 */
	public function removeRole(string $role): self
	{
		try {
			if ($this->hasRole($role) && UserRoleEnum::checkValue($role)) {
				foreach ($this->roles as $key => $roleName) {
					if ($roleName === $role) {
						unset($this->roles[$key]);
					}
				}
			}
		} catch (\Exception $e) {
		}

		return $this;
	}

	/**
	 * @param array $userData
	 *
	 * @return static
	 */
	public static function create(array $userData): self
	{
		$user = new self();

		$user->setUsername($userData[0])
			->setRoles($userData[1])
			->setPlainPassword($userData[2])
			->setIsAdmin($userData[3]);

		return $user;
	}
}
