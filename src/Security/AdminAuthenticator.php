<?php
declare(strict_types = 1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class AdminAuthenticator
 */
class AdminAuthenticator extends AbstractFormLoginAuthenticator
{
	use TargetPathTrait;

	const LOGIN_CHECK_ROUTE = 'admin_login_check';
	const LOGIN_ROUTE = 'admin_login';

	/**
	 * @var UrlGeneratorInterface
	 */
	private $urlGenerator;

	/**
	 * @var CsrfTokenManagerInterface
	 */
	private $csrfTokenManager;

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * AdminAuthenticator constructor.
	 *
	 * @param UrlGeneratorInterface        $urlGenerator
	 * @param CsrfTokenManagerInterface    $csrfTokenManager
	 * @param UserRepository               $userRepository
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 */
	public function __construct(
		UrlGeneratorInterface $urlGenerator,
		CsrfTokenManagerInterface $csrfTokenManager,
		UserRepository $userRepository,
		UserPasswordEncoderInterface $passwordEncoder
	) {
		$this->urlGenerator     = $urlGenerator;
		$this->csrfTokenManager = $csrfTokenManager;
		$this->userRepository   = $userRepository;
		$this->passwordEncoder  = $passwordEncoder;
	}

	/**
	 * @param Request $request
	 *
	 * @return boolean
	 */
	public function supports(Request $request): bool
	{
		return self::LOGIN_CHECK_ROUTE === $request->attributes->get('_route')
			&& $request->isMethod('POST');
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	public function getCredentials(Request $request): array
	{
		$credentials = [
			'username'   => $request->request->get('username'),
			'password'   => $request->request->get('password'),
			'csrf_token' => $request->request->get('_csrf_token'),
		];
		$request->getSession()->set(
			Security::LAST_USERNAME,
			$credentials['username']
		);

		return $credentials;
	}

	/**
	 * @param mixed                 $credentials
	 * @param UserProviderInterface $userProvider
	 *
	 * @return UserInterface|null
	 */
	public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
	{
		$token = new CsrfToken('authenticate', $credentials['csrf_token']);
		if (!$this->csrfTokenManager->isTokenValid($token)) {
			throw new InvalidCsrfTokenException();
		}

		$user = $this->userRepository->findOneBy(['username' => $credentials['username']]);

		if (!$user) {
			throw new CustomUserMessageAuthenticationException('User could not be found.');
		}

		return $user;
	}

	/**
	 * @param mixed         $credentials
	 * @param UserInterface $user
	 *
	 * @return boolean
	 */
	public function checkCredentials($credentials, UserInterface $user): bool
	{
		return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
	}

	/**
	 * @param Request        $request
	 * @param TokenInterface $token
	 * @param string         $providerKey
	 *
	 * @return RedirectResponse
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): RedirectResponse
	{
		if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
			return new RedirectResponse($targetPath);
		}

		return new RedirectResponse($this->urlGenerator->generate('admin_main'));
	}

	/**
	 * @return string
	 */
	protected function getLoginUrl(): string
	{
		return $this->urlGenerator->generate(self::LOGIN_ROUTE);
	}
}
