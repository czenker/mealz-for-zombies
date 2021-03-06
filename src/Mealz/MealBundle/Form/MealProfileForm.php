<?php

namespace Mealz\MealBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * form to add or edit a dish
 */
class MealProfileForm extends AbstractType {

	private $label;

	function __construct($inLabel) {
		$this->label = $inLabel;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {

		// TODO: limit users to those who are not yet in the participants list
		$builder
			->add('participant', 'entity', array(
			'class' => 'MealzUserBundle:Profile',
			'label' => false))
		->add($this->label, 'submit')
		;

	}

	/**
	 * Returns the name of this type.
	 *
	 * @return string The name of this type
	 */
	public function getName() {
		return 'participant';
	}
}
