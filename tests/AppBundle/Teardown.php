<?php
/**
 * Created by PhpStorm.
 * User: chrl
 * Date: 22/02/17
 * Time: 00:23
 */

namespace Tests\AppBundle;

use AppBundle\Entity\Variable;
use AppBundle\Entity\VariableHistory;
use AppBundle\Entity\Widget;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Teardown extends WebTestCase
{
	public function tearDown()
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$doctrine = $container->get('doctrine');
		$entityManager = $doctrine->getManager();

		$vars = $entityManager->getRepository('AppBundle:Trigger')->findAll();
		/** @var Variable $var */
		foreach ($vars as $var) {
			$entityManager->remove($var);
		}


		$widgets = $entityManager->getRepository('AppBundle:Widget')->findAll();
		/** @var Widget $widget */
		foreach ($widgets as $widget) {
			$entityManager->remove($widget);
		}

		$vars = $entityManager->getRepository('AppBundle:VariableHistory')->findAll();
		/** @var VariableHistory $var */
		foreach ($vars as $var) {
			$entityManager->remove($var);
		}

		$vars = $entityManager->getRepository('AppBundle:Variable')->findAll();
		/** @var Variable $var */
		foreach ($vars as $var) {
			$entityManager->remove($var);
		}


		$vars = $entityManager->getRepository('AppBundle:ActionHistory')->findAll();
		/** @var Variable $var */
		foreach ($vars as $var) {
			$entityManager->remove($var);
		}

		$vars = $entityManager->getRepository('AppBundle:Action')->findAll();
		/** @var Variable $var */
		foreach ($vars as $var) {
			$entityManager->remove($var);
		}


		$vars = $entityManager->getRepository('AppBundle:Device')->findAll();
		/** @var Variable $var */
		foreach ($vars as $var) {
			$entityManager->remove($var);
		}


		$entityManager->flush();

		parent::tearDown();

	}
}