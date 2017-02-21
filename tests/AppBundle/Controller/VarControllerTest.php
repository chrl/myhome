<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadVariableData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\Teardown;

class VarControllerTest extends Teardown
{

	public function setUp()
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$doctrine = $container->get('doctrine');
		$entityManager = $doctrine->getManager();

		$fixture = new LoadVariableData();
		$fixture->load($entityManager);
	}

	public function testEmptyVars()
    {
        $client = static::createClient();
        $client->request('GET', '/var');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"result":"ok","response":{"temperature":"20","humidity":"70"}}',$client->getResponse()->getContent());
    }

	public function testUnexistentVars()
	{
		$client = static::createClient();
		$client->request('GET', '/var/unexistent');

		$this->assertEquals(500, $client->getResponse()->getStatusCode());
	}

	public function testAddVarValue()
	{
		$client = static::createClient();

		$value = rand(0,300);

		$client->request('GET', '/set/temperature');
		$this->assertEquals('{"result":"ok","response":{"message":"Cannot set variable, maybe value param is missing?"}}',$client->getResponse()->getContent());


		$client->request('GET', '/set/temperature?value='.$value);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals('{"result":"ok","response":{"message":"Set: '.$value.'"}}',$client->getResponse()->getContent());

		$client->request('GET', '/var/temperature');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals('{"result":"ok","response":{"name":"temperature","value":"'.$value.'"}}',$client->getResponse()->getContent());

	}
}
