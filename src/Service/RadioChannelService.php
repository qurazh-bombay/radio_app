<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\RadioChannel;
use Symfony\Component\Asset\Packages;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class RadioChannelService
 */
class RadioChannelService
{
	const DEFAULT_RADIO_ICON_PATH = 'default/image/radio_icon.jpg';

	/**
	 * @var UploaderHelper
	 */
	private $helper;

	/**
	 * @var Packages
	 */
	private $asset;

	/**
	 * RadioChannelService constructor.
	 *
	 * @param UploaderHelper $helper
	 * @param Packages       $asset
	 */
	public function __construct(UploaderHelper $helper, Packages $asset)
	{
		$this->helper = $helper;
		$this->asset  = $asset;
	}

	/**
	 * @param array $radioChannels
	 *
	 * @return array
	 */
	public function getRadioChannelData(array $radioChannels): array
	{
		$radioList = [];

		foreach ($radioChannels as $channel) {
			/**@var $channel RadioChannel */
			$radioList[] = [
				'id'       => $channel->getId(),
				'name'     => $channel->getName(),
				'url'      => $channel->getUrl(),
				'iconPath' => $this->helper->asset($channel, 'imgFile')
					?? $this->asset->getUrl(self::DEFAULT_RADIO_ICON_PATH),
			];
		}

		return $radioList;
	}
}
