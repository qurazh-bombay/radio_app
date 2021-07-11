<?php
declare(strict_types = 1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserEventListener
 */
class UserEventListener
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * @param UserPasswordEncoderInterface $encoder
	 * @param EntityManagerInterface       $em
	 */
	public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
	{
		$this->encoder = $encoder;
		$this->em      = $em;
	}

	/**
	 * @param User $user
	 */
	public function prePersist(User $user): void
	{
		$this->setPassword($user);
	}

	/**
	 * @param User $user
	 */
	public function preUpdate(User $user): void
	{
		$this->setPassword($user);
	}

	/**
	 * @param User $user
	 */
	private function setPassword(User $user): void
	{
		if ($password = $user->getPlainPassword()) {
			$user->setPassword($this->encoder->encodePassword($user, $password));
		}
	}
}
