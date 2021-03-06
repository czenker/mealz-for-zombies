<?php

namespace Mealz\MealBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Xopn\DoctrineFileBundle\Annotations as Xopn;
use Gedmo\Mapping\Annotation as Gedmo;
use Xopn\DoctrineFileBundle\File\File;

/**
 * Dish
 *
 * @ORM\Table(name="dish")
 * @ORM\Entity(repositoryClass="DishRepository")
 */
class Dish
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @Gedmo\Slug(fields={"title_en"})
	 * @ORM\Column(length=128, unique=true)
	 * @var string
	 */
	protected $slug;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Length(min=8, max=255)
	 * @ORM\Column(type="string", length=255, nullable=FALSE)
	 * @var string
	 */
	protected $title_en;

	/**
	 * @Assert\Length(max=4096)
	 * @ORM\Column(type="text", nullable=TRUE)
	 * @var null|string
	 */
	protected $description_en = NULL;

	/**
	 * @Assert\Length(min=8, max=255)
	 * @ORM\Column(type="string", length=255, nullable=TRUE)
	 * @var string
	 */
	protected $title_de;

	/**
	 * @Assert\Length(max=4096)
	 * @ORM\Column(type="text", nullable=TRUE)
	 * @var null|string
	 */
	protected $description_de = NULL;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=4, nullable=TRUE)
	 * @var null|float
	 */
	protected $price = NULL;

	/**
	 * @ORM\Column(type="boolean", nullable=FALSE)
	 * @var bool
	 */
	protected $enabled = TRUE;

	/**
	 * @ORM\OneToMany(targetEntity="Meal", mappedBy="dish")
	 * @var ArrayCollection
	 */
	protected $meals;

	/**
	 * @ORM\Column(type="string", length=256, nullable=true)
	 * @Xopn\File(path="%kernel.root_dir%/../web/media/dish")
	 * @var File
	 */
	protected $image;

	/**
	 * @var string
	 */
	protected $currentLocale = 'en';

	public function __construct() {
		$this->meals = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @deprecated use setDescriptionEn() instead
	 * @param null|string $description
	 */
	public function setDescription($description)
	{
		$this->setDescriptionEn($description);
	}

	/**
	 * @return null|string
	 */
	public function getDescription()
	{
		if($this->currentLocale == 'de' && $this->description_de) {
			return $this->getDescriptionDe();
		} else {
			return $this->getDescriptionEn();
		}
	}

	/**
	 * @param float|null $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}

	/**
	 * @return float|null
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param string $title
	 * @deprecated use setTitleEn() or setTitleDe() instead
	 */
	public function setTitle($title)
	{
		$this->setTitleEn($title);
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		if($this->currentLocale == 'de' && $this->title_de) {
			return $this->getTitleDe();
		} else {
			return $this->getTitleEn();
		}
	}

	/**
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}

	/**
	 * @return boolean
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getMeals() {
		return $this->meals;
	}

	/**
	 * @param string $currentLocale
	 */
	public function setCurrentLocale($currentLocale)
	{
		$this->currentLocale = $currentLocale;
	}

	/**
	 * @return string
	 */
	public function getCurrentLocale()
	{
		return $this->currentLocale;
	}

	/**
	 * @param null|string $description_de
	 */
	public function setDescriptionDe($description_de)
	{
		$this->description_de = $description_de;
	}

	/**
	 * @return null|string
	 */
	public function getDescriptionDe()
	{
		return $this->description_de;
	}

	/**
	 * @param null|string $description_en
	 */
	public function setDescriptionEn($description_en)
	{
		$this->description_en = $description_en;
	}

	/**
	 * @return null|string
	 */
	public function getDescriptionEn()
	{
		return $this->description_en;
	}

	/**
	 * @param string $title_de
	 */
	public function setTitleDe($title_de)
	{
		$this->title_de = $title_de;
	}

	/**
	 * @return string
	 */
	public function getTitleDe()
	{
		return $this->title_de;
	}

	/**
	 * @param string $title_en
	 */
	public function setTitleEn($title_en)
	{
		$this->title_en = $title_en;
	}

	/**
	 * @return string
	 */
	public function getTitleEn()
	{
		return $this->title_en;
	}

	/**
	 * @param mixed $image
	 */
	public function setImage($image = NULL) {
		// @see removeImage if you want to unset the image
		// this logic is needed if this entity is used in forms
		if(!empty($image)) {
			$this->image = $image;
		}
	}

	/**
	 * @return File|null
	 */
	public function getImage() {
		return $this->image;
	}

	public function removeImage() {
		$this->image = NULL;
	}



	public function __toString() {
		return $this->getTitle();
	}

}
