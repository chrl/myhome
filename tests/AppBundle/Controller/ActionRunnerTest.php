<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadActionData;
use Tests\AppBundle\Teardown;

class ActionRunnerTest extends Teardown
{

	public function setUp()
	{
		$client = static::createClient();
		$container = $client->getContainer();
		$doctrine = $container->get('doctrine');
		$entityManager = $doctrine->getManager();

		$fixture = new LoadActionData();
		$fixture->load($entityManager);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testAddVarValue()
	{
		$client = static::createClient();

		$value = rand(1,300);

		$client->request('GET', '/set/hooktest');
		$this->assertEquals('{"result":"ok","response":{"message":"Cannot set variable, maybe value param is missing?"}}',$client->getResponse()->getContent());


		$client->request('GET', '/set/hooktest?value='.$value);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals('{"result":"ok","response":{"message":"Set: '.$value.'"}}',$client->getResponse()->getContent());

		$client->request('GET', '/var/hooktest');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals('{"result":"ok","response":{"name":"hooktest","value":"'.$value.'"}}',$client->getResponse()->getContent());

		$this->assertFileExists('/tmp/testfile.log');
		$this->assertEquals(file_get_contents('/tmp/testfile.log'),"test text");

	}
}
