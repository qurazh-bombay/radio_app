<?php
declare(strict_types = 1);

namespace App\Form\Admin;

use App\Entity\Country;
use App\Entity\Genre;
use App\Entity\RadioChannel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RadioChannelFormType
 */
class RadioChannelFormType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		/**
		 * @var RadioChannel
		 */
		$channel = $options['data'];

		$btnName = $channel->getId() ? 'Edit Channel' : 'Save Channel';

		$builder
			->add('name', TextType::class)
			->add('url', TextType::class)
			->add('imgFile', FileType::class, [
				'label' => 'Image',
			])
			->add('country', EntityType::class, [
				'placeholder'  => 'Select country',
				'class'        => Country::class,
				'choice_label' => 'name',
			])
			->add('genre', EntityType::class, [
				'placeholder'  => 'Select genre',
				'class'        => Genre::class,
				'choice_label' => 'name',
			])
			->add(
				'submit',
				SubmitType::class,
				[
					'label' => $btnName,
					'attr'  => [
						'class' => 'btn btn-success btn-fix-size'
					]
				]
			);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => RadioChannel::class
		]);
	}
}
