<?php
declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Warning;
use App\Form\Admin\RadioChannelResourceFormType;
use App\Repository\WarningRepository;
use App\Service\LoggerService;
use App\Utility\FlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WarningController
 *
 * @Route("/admin/warning")
 */
class WarningController extends AbstractController
{
	const PER_PAGE = 10;

	/**
	 * @Route("/", name="admin_warning_list", methods="GET")
	 *
	 * @param WarningRepository  $repo
	 * @param PaginatorInterface $paginator
	 * @param Request            $request
	 *
	 * @return Response
	 */
	public function list(WarningRepository $repo, PaginatorInterface $paginator, Request $request): Response
	{
		$warnings = $paginator->paginate(
			$repo->getActiveWarnings(),
			$request->query->getInt('page', 1),
			self::PER_PAGE
		);

		return $this->render(
			'admin/warning/list.html.twig',
			[
				'warnings' => $warnings,
			]
		);
	}

	/**
	 * @Route(
	 *     "/update/{id}",
	 *     name="admin_warning_update",
	 *     methods="GET|POST",
	 *     requirements={"id"="\d+"}
	 * )
	 *
	 * @param Warning                $warning
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
	public function update(
		Warning $warning,
		Request $request,
		EntityManagerInterface $em,
		LoggerService $logger
	): Response {
		$channel = $warning->getRadioChannel();
		$form    = $this->createForm(RadioChannelResourceFormType::class, $channel);
		$form->handleRequest($request);

		if (!$form->isSubmitted() or !$form->isValid()) {
			return $this->render(
				'admin/warning/alter_entity.html.twig',
				[
					'form' => $form->createView(),
				]
			);
		}

		try {
			$warning->setIsFixed(true);
			$em->flush();
			$this->addFlash('success', FlashMessage::SUCCESSFUL_EDITED);
		} catch (\Throwable $e) {
			$logger->critical($e, $channel, 'updateChannelResource');
			$this->addFlash('danger', FlashMessage::ALTER_FAILED);
		}

		return $this->redirectToRoute('admin_warning_list');
	}
}