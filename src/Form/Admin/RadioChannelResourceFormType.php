<?php
declare(strict_types = 1);

namespace App\Form\Admin;

use App\Entity\RadioChannel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RadioChannelResourceFormType
 */
class RadioChannelResourceFormType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('url', TextType::class)
			->add(
				'submit',
				SubmitType::class,
				[
					'label' => 'Edit Channel',
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
