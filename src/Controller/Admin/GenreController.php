<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Form\Admin\GenreFormType;
use App\Repository\GenreRepository;
use App\Service\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GenreController
 *
 * @Route("/admin/genre")
 */
class GenreController extends AbstractCrudController
{
    const PER_PAGE = 10;

    /**
     * @Route("/", name="admin_genre_list", methods="GET")
     *
     * @param GenreRepository    $repo
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return Response
     */
    public function list(GenreRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $genres = $paginator->paginate(
            $repo->createQueryBuilder('genre'),
            $request->query->getInt('page', 1),
            self::PER_PAGE
        );

        return $this->render(
            'admin/genre/list.html.twig',
            [
                'genres' => $genres,
            ]
        );
    }

    /**
     * @Route("/create", name="admin_genre_create", methods="GET|POST")
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
        return $this->alterEntity(new Genre(), $request, $em, $logger);
    }

    /**
     * @Route(
     *     "/update/{id}",
     *     name="admin_genre_update",
     *     methods="GET|POST",
     *     requirements={"id"="\d+"}
     * )
     *
	 * @param Genre                  $genre
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function update(
        Genre $genre,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
        return $this->alterEntity($genre, $request, $em, $logger);
    }

    /**
     * @Route(
     *     "/delete/{id}",
     *     name="admin_genre_delete",
     *     methods="DELETE",
     *     requirements={"id"="\d+"}
     *     )
     *
	 * @param Genre                  $genre
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function delete(
        Genre $genre,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
		$this->handleDelete($genre, $request, $em, $logger);

		return $this->redirectToRoute('admin_genre_list');
    }

	/**
	 * @param Genre                  $genre
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    private function alterEntity(
        Genre $genre,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
		$form = $this->createForm(GenreFormType::class, $genre);
		$form->handleRequest($request);

		$error = $this->handleAlterEntity($genre, $form, $em, $logger);

		if ($error) {
			return $this->render(
				'admin/genre/alter_entity.html.twig',
				[
					'form' => $form->createView(),
				]
			);
		}

		return $this->redirectToRoute('admin_genre_list');
    }
}
