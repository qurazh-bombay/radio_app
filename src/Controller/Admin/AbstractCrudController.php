<?php
declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Interfaces\EntityInterface;
use App\Service\LoggerService;
use App\Utility\FlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractCrudController
 */
abstract class AbstractCrudController extends AbstractController
{
	/**
	 * Handles delete entity
	 *
	 * @param EntityInterface        $entity
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return void
	 * @throws \ReflectionException
	 */
	protected function handleDelete(
		EntityInterface $entity,
		Request $request,
		EntityManagerInterface $em,
		LoggerService $logger
	): void {
		try {
			$submittedToken = $request->get('_csrf_token');
			$tokenName      = md5((string) $entity->getId());

			if (!$this->isCsrfTokenValid($tokenName, $submittedToken)) {
				throw new BadRequestException(FlashMessage::INVALID_DATA);
			}

			$em->remove($entity);
			$em->flush();

			$this->addFlash('success', FlashMessage::SUCCESSFUL_DELETED);
		} catch (\Throwable $e) {
			$logger->critical($e, $entity, 'delete');
			$this->addFlash('danger', FlashMessage::ALTER_FAILED);
		}
	}

	/**
	 * Handles create and edit entity, returns boolean about error happened
	 *
	 * @param EntityInterface        $entity
	 * @param FormInterface          $form
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return boolean
	 * @throws \ReflectionException
	 */
	protected function handleAlterEntity(
		EntityInterface $entity,
		FormInterface $form,
		EntityManagerInterface $em,
		LoggerService $logger
	): bool {
		$error = true;

		if (!$form->isSubmitted() or !$form->isValid()) {
			return $error;
		}

		$flashMsg = FlashMessage::SUCCESSFUL_EDITED;
		$isCreate = is_null($entity->getId());
		try {
			if ($isCreate) {
				$em->persist($entity);
				$flashMsg = FlashMessage::SUCCESSFUL_ADDED;
			}
			$em->flush();
			$this->addFlash('success', $flashMsg);
			$error = false;
		} catch (\Throwable $e) {
			$type = $isCreate ? 'create' : 'update';
			$logger->critical($e, $entity, $type);
			$this->addFlash('danger', FlashMessage::ALTER_FAILED);
		}

		return $error;
	}
}
