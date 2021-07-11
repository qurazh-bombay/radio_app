<?php
declare(strict_types = 1);

namespace App\VichUploader;

use App\Entity\Interfaces\FileUploadInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * Class DirectoryNamer
 */
class DirectoryNamer implements DirectoryNamerInterface
{
	/**
	 * @param object          $object
	 * @param PropertyMapping $mapping
	 *
	 * @return string
	 * @throws \ReflectionException
	 */
	public function directoryName($object, PropertyMapping $mapping): string
	{
		$name = (new \ReflectionClass($object))->getShortName();

		// file collection
		if (!$object instanceof FileUploadInterface) {
			$name .= DIRECTORY_SEPARATOR . $mapping->getFileNamePropertyName();
		}

		return (new CamelCaseToSnakeCaseNameConverter())->normalize($name);
	}
}
