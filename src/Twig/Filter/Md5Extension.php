<?php
declare(strict_types = 1);

namespace App\Twig\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class Md5Extension
 */
class Md5Extension extends AbstractExtension
{
	/**
	 * @return array|TwigFilter[]
	 */
	public function getFilters(): array
	{
		return [
			new TwigFilter('hashMd5', [$this, 'getHash']),
		];
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	public function getHash(string $str): string
	{
		return md5($str);
	}
}
