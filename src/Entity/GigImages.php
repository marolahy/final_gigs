<?php
declare(strict_types=1);
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="gig_image")
 * @Vich\Uploadable
 */
class GigImages
{
    /**
     * The identifier of the product.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = null;

    /**
     * It only stores the name of the image associated with the product.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $image;
    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the product.
     *
     * @Vich\UploadableField(mapping="gig_images", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;

    /**
     * It only stores the name of the image associated with the product.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $thumb;
    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the product.
     *
     * @Vich\UploadableField(mapping="gig_images", fileNameProperty="thumb")
     *
     * @var File
     */
    private $thumbFile;


    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Gigs", inversedBy="images")
     * @ORM\JoinColumn(name="gig_id", referencedColumnName="id")
     */
    protected $gig;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @return mixed
     */
    public function getGig()
    {
        return $this->gig;
    }

    /**
     * @param mixed $gig
     */
    public function setGig(Gigs $gig)
    {
        $this->gig = $gig;
    }
    /**
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param string $image
     */
    public function setThumb(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return File
     */
    public function getThumbFile()
    {
        return $this->thumbFile;
    }

    /**
     * @param File $imageFile
     */
    public function setThumbFile(File $imageFile)
    {
        $this->thumbFile = $imageFile;
    }







}
