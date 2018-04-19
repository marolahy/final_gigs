<?php
declare(strict_types=1);
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Table(name="gigs")
 * @ORM\Entity(repositoryClass="App\Repository\GigsRepository")
 * @Vich\Uploadable
 */

class Gigs
{
    /**
     * The identifier of the gigs.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * The price of the product.
     *
     * @var float
     * @ORM\Column(type="float")
     */
    protected $price = 0.0;
    /**
     * The name of the product.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;
    /**
     * The description of the product.
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * The description of the product.
     *
     * @var string
     * @ORM\Column(type="boolean")
     */
    protected $featured;


    /**
     * The description of the product.
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $stock;


    /**
     * The description of the product.
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $selled;



    /**
     * It only stores the name of the image associated with the product.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $icon_image;

    /**
     * It only stores the name of the image associated with the product.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $background_image;

    /**
     *
     * @Vich\UploadableField(mapping="gig_images", fileNameProperty="icon_image")
     *
     * @var File
     */
    protected $icon;



    /**
     *
     * @Vich\UploadableField(mapping="gig_images", fileNameProperty="background_image")
     *
     * @var File
     */
    protected $background;

    /**
     * @return string
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * @param string $featured
     */
    public function setFeatured(string $featured)
    {
        $this->featured = $featured;
    }


    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="gigs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * One Gigs has Many Images.
     * @ORM\OneToMany(targetEntity="GigImages", mappedBy="gig")
     */
    protected $images;


    public function __construct() {
        $this->images = new ArrayCollection();
    }

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
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getSelled()
    {
        return $this->selled;
    }

    /**
     * @param int $selled
     */
    public function setSelled(int $selled)
    {
        $this->selled = $selled;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return string
     */
    public function getIconImage()
    {
        return $this->icon_image;
    }

    /**
     * @param File $icon_image
     */
    public function setIconImage($icon_image)
    {
        $this->icon_image = $icon_image;
    }

    /**
     * @return string
     */
    public function getBackgroundImage()
    {
        return $this->background_image;
    }

    /**
     * @param File $background_image
     */
    public function setBackgroundImage($background_image)
    {
        $this->background_image = $background_image;
    }
    public function __toString()
    {
        return $this->getName();
    }


}
