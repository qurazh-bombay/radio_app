<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\Country;
use App\Entity\Genre;
use App\Repository\CountryRepository;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchRadioChannelFormType
 */
class SearchRadioChannelFormType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('genre', EntityType::class, [
				'class'        => Genre::class,
				'query_builder' => function (GenreRepository $repo) {
					return $repo->createQueryBuilder('o')
						->orderBy('o.name', 'ASC');
				},
				'choice_label' => 'name',
				'multiple'     => true,
				'attr'         => [
					'class' => 'selectpicker', # it needed for 'pretty' multi select with bootstrap-select
					'title' => 'Select genre', # @see https://developer.snapappointments.com/bootstrap-select/examples/#placeholder
				],
			])
			->add('country', EntityType::class, [
				'class'        => Country::class,
				'query_builder' => function (CountryRepository $repo) {
					return $repo->createQueryBuilder('o')
						->orderBy('o.name', 'ASC');
				},
				'choice_label' => 'name',
				'multiple'     => true,
				'attr'         => [
					'class' => 'selectpicker',
					'title' => 'Select country',
				],
			])
			->add('search', SubmitType::class, [
				'attr' => [
					'class' => 'btn btn-primary',
				],
			]);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([]);
	}
}
