<?php

namespace Mealz\MealBundle\Controller;

use Doctrine\ORM\UnitOfWork;
use Mealz\MealBundle\Entity\Meal;
use Mealz\MealBundle\Form\MealAdminForm;
use Mealz\MealBundle\Service\Workday;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MealAdminController extends BaseController {

	public function newAction(Request $request) {
		if(!$this->get('security.context')->isGranted('ROLE_KITCHEN_STAFF')) {
			throw new AccessDeniedException();
		}

		$meal = new Meal();
		$meal->setDateTime($this->guessNextMealDate());

		$form = $this->createForm(new MealAdminForm(), $meal);

		// handle form submission
		if($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				if($em->getUnitOfWork()->getEntityState($meal->getDish()) === UnitOfWork::STATE_NEW) {
					// if Dish is new
					$em->persist($meal->getDish());
				}

				$em->persist($meal);
				$em->flush();

				$this->addFlashMessage('Meal has been added.', 'success');

				return $this->redirect($this->generateUrl('MealzMealBundle_Meal_new'));
			}
		}

		return $this->render('MealzMealBundle:MealAdmin:form.html.twig', array(
			'form' => $form->createView()
		));
	}

	public function editAction(Request $request, Meal $meal) {
		if(!$this->get('security.context')->isGranted('ROLE_KITCHEN_STAFF')) {
			throw new AccessDeniedException();
		}

		$form = $this->createForm(new MealAdminForm(), $meal);

		// handle form submission
		if($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				if($em->getUnitOfWork()->getEntityState($meal->getDish()) === UnitOfWork::STATE_NEW) {
					// if Dish is new
					$em->persist($meal->getDish());
				}
				$em->persist($meal);
				$em->flush();

				$this->addFlashMessage('Meal was modified.', 'success');

				return $this->redirect($this->generateUrlTo($meal));
			}
		}

		return $this->render('MealzMealBundle:MealAdmin:form.html.twig', array(
			'meal' => $meal,
			'form' => $form->createView()
		));
	}

	public function deleteAction(Meal $meal) {
		if(!$this->get('security.context')->isGranted('ROLE_KITCHEN_STAFF')) {
			throw new AccessDeniedException();
		}

		$em = $this->getDoctrine()->getManager();

		if($meal->getParticipants()->count() > 0) {
			// if: there are already participants
			$this->addFlashMessage(
				'Removing this meal is not allowed, because there are already participants.',
				'danger'
			);
		} else {
			// else: no need to keep an unused record
			$em->remove($meal);
			$em->flush();

			$this->addFlashMessage(sprintf('Meal "%s" was deleted.', $meal->getDish()->getTitle()), 'success');
		}

		return $this->redirect($this->generateUrl('MealzMealBundle_Meal'));
	}

	/**
	 * guess when the next meal will take place
	 *
	 * @return \DateTime
	 */
	protected function guessNextMealDate() {
		$lastMeal = $this->getMealRepository()->getLastMealDate();
		if(!$lastMeal) {
			return new \DateTime();
		}
		$count = $this->getMealRepository()->countMealsAt($lastMeal);
		if($count >= 2) {
			/** @var Workday $workdayService */
			$workdayService = $this->get('mealz_meal.workday');
			return $workdayService->getNextWorkday($lastMeal);
		} else {
			return $lastMeal;
		}
	}
}