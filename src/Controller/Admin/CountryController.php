<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Country;
use App\Form\Admin\CountryFormType;
use App\Repository\CountryRepository;
use App\Service\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CountryController
 *
 * @Route("/admin/country")
 */
class CountryController extends AbstractCrudController
{
    const PER_PAGE = 10;

    /**
     * @Route("/", name="admin_country_list", methods="GET")
     *
     * @param CountryRepository  $repo
     * @param PaginatorInterface $paginator
     * @param Request            $request
     *
     * @return Response
     */
    public function list(CountryRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $countries = $paginator->paginate(
            $repo->createQueryBuilder('country'),
            $request->query->getInt('page', 1),
            self::PER_PAGE
        );

        return $this->render(
            'admin/country/list.html.twig',
            [
                'countries' => $countries,
            ]
        );
    }

    /**
     * @Route("/create", name="admin_country_create", methods="GET|POST")
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
        return $this->alterEntity(new Country(), $request, $em, $logger);
    }

    /**
     * @Route(
     *     "/update/{id}",
     *     name="admin_country_update",
     *     methods="GET|POST",
     *     requirements={"id"="\d+"}
     * )
	 *
	 * @param Country                $country
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function update(
        Country $country,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
        return $this->alterEntity($country, $request, $em, $logger);
    }

    /**
     * @Route(
     *     "/delete/{id}",
     *     name="admin_country_delete",
     *     methods="DELETE",
     *     requirements={"id"="\d+"}
     *     )
	 *
	 * @param Country                $country
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    public function delete(
        Country $country,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
    	$this->handleDelete($country, $request, $em, $logger);

        return $this->redirectToRoute('admin_country_list');
    }

	/**
	 * @param Country                $country
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 * @param LoggerService          $logger
	 *
	 * @return Response
	 * @throws \ReflectionException
	 */
    private function alterEntity(
        Country $country,
        Request $request,
        EntityManagerInterface $em,
		LoggerService $logger
    ): Response {
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);

        $isError = $this->handleAlterEntity($country, $form, $em, $logger);

        if ($isError) {
        	return $this->render(
				'admin/country/alter_entity.html.twig',
				[
					'form' => $form->createView(),
				]
			);
		}

        return $this->redirectToRoute('admin_country_list');
    }
}
