<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadWidgetData;
use AppBundle\Entity\Widget;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\Teardown;

class WidgetControllerTest extends Teardown
{

	public function setUp()
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$doctrine = $container->get('doctrine');
		$entityManager = $doctrine->getManager();

		$fixture = new LoadWidgetData();
		$fixture->load($entityManager);
	}

	public function testWidgetIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/dashboard/widgets.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $widgets = json_decode($client->getResponse()->getContent(),true);
        $widget = array_shift($widgets['widgets']);
        unset($widget['id']);


        $this->assertEquals([
        	'name'=>'temperature',
			'x'=>0,
			'y'=>0,
			'width'=>2,
			'height'=>2,
			'value'=>20,
			'type'=>'value'
		],$widget);
    }

	public function testWindgetsUpdate()
	{
		$client = static::createClient();
		$client->request('GET', '/dashboard/update.json');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$widgets = json_decode($client->getResponse()->getContent(),true);
		$widget = array_shift($widgets['widgets']);
		unset($widget['id']);


		$this->assertEquals([
			'value' => '20',
			'type' => 'value',
			'lastchange' => null,
		],$widget);
	}


}
