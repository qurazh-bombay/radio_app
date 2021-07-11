<?php
declare(strict_types = 1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 *
 * @Route("/admin")
 */
class MainController extends AbstractController
{
	/**
	 * @Route("/main", name="admin_main", methods="GET")
	 *
	 * @return Response
	 */
	public function main(): Response
	{
		return $this->render('admin/main/index.html.twig');
	}
}
