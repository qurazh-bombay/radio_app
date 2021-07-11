<?php
declare(strict_types = 1);

namespace App\Twig\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class UcwordsExtension
 */
class UcwordsExtension extends AbstractExtension
{
	/**
	 * @return array|TwigFilter[]
	 */
	public function getFilters(): array
	{
		return [
			new TwigFilter('ucwords', [$this, 'getUcwords']),
		];
	}

	/**
	 * @param string|null $str
	 *
	 * @return string
	 */
	public function getUcwords(?string $str): string
	{
		if ($str === null) {
			return 'No set';
		}

		return ucwords(strtolower($str));
	}
}
