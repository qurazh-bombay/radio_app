<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Genre;
use App\Form\SearchRadioChannelFormType;
use App\Repository\CountryRepository;
use App\Repository\GenreRepository;
use App\Repository\RadioChannelRepository;
use App\Service\RadioChannelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 */
class MainController extends AbstractController
{
	/**
	 * @Route("/", name="main", methods="GET|POST")
	 *
	 * @param GenreRepository        $genreRepo
	 * @param CountryRepository      $countryRepo
	 * @param RadioChannelRepository $radioChannelRepo
	 * @param Request                $request
	 * @param RadioChannelService    $service
	 *
	 * @return Response
	 */
	public function main(
		GenreRepository $genreRepo,
		CountryRepository $countryRepo,
		RadioChannelRepository $radioChannelRepo,
		Request $request,
		RadioChannelService $service
	): Response {
		$form = $this->createForm(SearchRadioChannelFormType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() and $form->isValid()) {
			return $this->getRadioChannelListByCountryAndGenre(
				$form->getData(), $request, $radioChannelRepo, $service
			);
		}

		return $this->render(
			'site/main/index.html.twig',
			[
				'genreList'   => $genreRepo->findBy([], ['name' => 'ASC']),
				'countryList' => $countryRepo->findBy([], ['name' => 'ASC']),
				'form'        => $form->createView(),
			]
		);
	}

	/**
	 * @Route(
	 *     "/country/{id}",
	 *     name="country_radio_channels",
	 *     methods="GET",
	 *     requirements={"id"="\d+"}
	 * )
	 *
	 * @param Country                $country
	 * @param RadioChannelRepository $repo
	 * @param Request                $request
	 * @param RadioChannelService    $service
	 *
	 * @return Response
	 */
	public function getRadioChannelListByCountry(
		Country $country,
		RadioChannelRepository $repo,
		Request $request,
		RadioChannelService $service
	): Response {
		$radioChannels = $repo->findBy([
			'country' => $country
		]);

		$response = [
			'radioList' => $service->getRadioChannelData($radioChannels),
			'settings'  => $this->getSettings($repo, $request),
		];

		return $this->render(
			'site/main/player.html.twig',
			[
				'response' => $response,
			]
		);
	}

	/**
	 * @Route(
	 *     "/genre/{id}",
	 *     name="genre_radio_channels",
	 *     methods="GET",
	 *     requirements={"id"="\d+"}
	 * )
	 *
	 * @param Genre                  $genre
	 * @param RadioChannelRepository $repo
	 * @param Request                $request
	 * @param RadioChannelService    $service
	 *
	 * @return Response
	 */
	public function getRadioChannelListByGenre(
		Genre $genre,
		RadioChannelRepository $repo,
		Request $request,
		RadioChannelService $service
	): Response {
		$radioChannels = $repo->findBy([
			'genre' => $genre
		]);

		$response = [
			'radioList' => $service->getRadioChannelData($radioChannels),
			'settings'  => $this->getSettings($repo, $request),
		];

		return $this->render(
			'site/main/player.html.twig',
			[
				'response' => $response,
			]
		);
	}

	/**
	 * @Route("/search", name="search", methods="GET")
	 *
	 * @param RadioChannelRepository $repo
	 * @param Request                $request
	 * @param RadioChannelService    $service
	 *
	 * @return Response
	 */
	public function getRadioChannelListByQuery(
		RadioChannelRepository $repo,
		Request $request,
		RadioChannelService $service
	): Response {
		$response = $this->redirectToRoute('main');

		if ($query = trim($request->get('search-query'))) {
			$radioList = $service->getRadioChannelData($repo->searchRadioChannel($query));

			if ($radioList) {
				$response = $this->render(
					'site/main/player.html.twig',
					[
						'response' => [
							'radioList' => $radioList,
							'settings'  => $this->getSettings($repo, $request),
						],
					]
				);
			}
		}

		return $response;
	}

	/**
	 * Returns user settings
	 *
	 * @param RadioChannelRepository $repo
	 * @param Request                $request
	 *
	 * @return array
	 */
	private function getSettings(RadioChannelRepository $repo, Request $request): array
	{
		// default
		$source     = null;
		$sourceName = null;
		$volume     = 50;

		// if cookie exists
		if ($request->cookies->get('channel-source') !== null) {
			$id     = (int) $request->cookies->get('channel-source');
			$source = $repo->findSourceOrNull($id);
		}

		if ($request->cookies->get('channel-name') !== null) {
			$sourceName = $request->cookies->get('channel-name');
		}

		if ($request->cookies->get('volume') !== null) {
			$volume = (int) $request->cookies->get('volume');
			if ($volume < 0 or $volume > 100) {
				$volume = 50;
			}
		}

		return compact('source', 'volume', 'sourceName');
	}

	/**
	 * @param array                  $data
	 * @param Request                $request
	 * @param RadioChannelRepository $repo
	 * @param RadioChannelService    $service
	 *
	 * @return Response
	 */
	private function getRadioChannelListByCountryAndGenre(
		array $data,
		Request $request,
		RadioChannelRepository $repo,
		RadioChannelService $service
	): Response {
		$radioChannels = $repo->findRadioChannelListByCountryAndGenre($data);
		$response      = [
			'radioList' => $service->getRadioChannelData($radioChannels),
			'settings'  => $this->getSettings($repo, $request),
		];

		return $this->render(
			'site/main/player.html.twig',
			[
				'response' => $response,
			]
		);
	}
}
