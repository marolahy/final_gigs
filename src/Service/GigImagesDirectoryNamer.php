<?php
namespace App\Service;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use App\Entity\GigImages;
class GigImagesDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * Returns the name of a directory where files will be uploaded
     *
     * Directory name is formed based on user ID and media type
     *
     * @param GigImages $gigs
     * @param PropertyMapping $mapping
     *
     * @return string
     */
     public function directoryName($gigs, PropertyMapping $mapping)
     {
          $id = $gigs->getGig()->getId();

          return $id.'/images/';
     }
}
