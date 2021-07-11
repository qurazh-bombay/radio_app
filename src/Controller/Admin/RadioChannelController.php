<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\RadioChannel;
use App\Form\Admin\RadioChannelFormType;
use App\Repository\RadioChannelRepository;
use App\Service\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RadioChannelController
 *
 * @Route("/admin/channel")
 */
class RadioChannelController extends AbstractCrudController
{
    const PER_PAGE = 10;

    /**
     * @Route("/", name="admin_channel_list", methods="GET")
     *
     * @param RadioChannelRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return Response
     */
    public function list(RadioChannelRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $channels = $paginator->paginate(
            $repo->getRadioChannelListQuery(),
            $request->query->getInt('page', 1),
            self::PER_PAGE
        );

        return $this->render(
            'admin/radio_channel/list.html.twig',
            [
                'channels' => $channels,
            ]
        );
    }

    /**
     * @Route("/create", name="admin_channel_create", methods="GET|POST")
     *
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function create(Request $request, EntityManagerInterface $em, LoggerService $logger): Response
    {
        return $this->alterEntity(new RadioChannel(), $request, $em, $logger);
    }

    /**
     * @Route(
     *     "/update/{id}",
     *     name="admin_channel_update",
     *     methods="GET|POST",
     *     requirements={"id"="\d+"}
     * )
     *
	 * @param RadioChannel           $channel
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function update(
        RadioChannel $channel,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
        return $this->alterEntity($channel, $request, $em, $logger);
    }

    /**
     * @Route(
     *     "/delete/{id}",
     *     name="admin_channel_delete",
     *     methods="DELETE",
     *     requirements={"id"="\d+"}
     *     )
     *
	 * @param RadioChannel           $channel
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function delete(
        RadioChannel $channel,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
		$this->handleDelete($channel, $request, $em, $logger);

		return $this->redirectToRoute('admin_channel_list');
    }

	/**
	 * @param RadioChannel           $channel
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    private function alterEntity(
        RadioChannel $channel,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
		$form = $this->createForm(RadioChannelFormType::class, $channel);
		$form->handleRequest($request);

		$error = $this->handleAlterEntity($channel, $form, $em, $logger);

		if ($error) {
			return $this->render(
				'admin/radio_channel/alter_entity.html.twig',
				[
					'form' => $form->createView(),
				]
			);
		}

		return $this->redirectToRoute('admin_channel_list');
    }
}
