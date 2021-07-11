<?php
declare(strict_types = 1);

namespace App\Controller\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 *
 * @Route("/admin")
 */
class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", name="admin_login")
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 *
	 * @return Response
	 */
	public function adminLogin(AuthenticationUtils $authenticationUtils): Response
	{
		if ($this->getUser() instanceof User) {
			return $this->redirectToRoute('admin_main');
		}

		$error        = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render(
			'security/admin_login.html.twig',
			[
				'last_username' => $lastUsername,
				'error'         => $error,
			]
		);
	}

	/**
	 * @Route("/logout", name="admin_logout")
	 */
	public function adminLogout(): RedirectResponse
	{
		throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
	}
}
