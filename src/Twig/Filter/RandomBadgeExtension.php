<?php
declare(strict_types = 1);

namespace App\Twig\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class RandomBadgeExtension
 */
class RandomBadgeExtension extends AbstractExtension
{
	/**
	 * @return array|TwigFunction[]
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('random_badge', [$this, 'getRandomBadge']),
		];
	}

	/**
	 * @return string
	 */
	public function getRandomBadge(): string
	{
		$badges = [
			'badge-secondary',
			'badge-primary',
			'badge-success',
			'badge-danger',
			'badge-warning',
			'badge-info',
			'badge-dark'
		];

		$key = array_rand($badges);

		return $badges[$key];
	}
}
