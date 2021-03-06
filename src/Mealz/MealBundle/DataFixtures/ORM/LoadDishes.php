<?php

namespace Mealz\MealBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mealz\MealBundle\Entity\Dish;


class LoadDishes extends AbstractFixture implements OrderedFixtureInterface {

	/**
	 * @var ObjectManager
	 */
	protected $objectManager;

	/**
	 * @var int
	 */
	protected $counter = 0;

	function load(ObjectManager $manager) {
		$this->objectManager = $manager;

		$this->addDish('Braaaaaiiinnnzzzzzz with Tomato-Sauce');
		$this->addDish('Tasty Worms');
		$this->addDish('Braaaaaiiinnnzzzzzz with Cheese-Sauce');
		$this->addDish('Fish (so juicy sweat)');
		$this->addDish('Limbs');

		$this->objectManager->flush();
	}

	protected function addDish($title) {
		$dish = new Dish();
		$dish->setTitleEn($title);

		$this->objectManager->persist($dish);
		$this->addReference('dish-' . $this->counter++, $dish);
	}

	public function getOrder()
	{
		return 1;
	}
}