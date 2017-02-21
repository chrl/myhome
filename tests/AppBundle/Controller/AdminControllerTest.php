<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\Teardown;

class AdminControllerTest extends Teardown
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/admin/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Variable', $crawler->filter('h1.title')->text());


		$crawler = $client->request('GET', '/admin/?entity=Widget&action=list&menuIndex=3&submenuIndex=-1');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Widget', $crawler->filter('h1.title')->text());
    }
}
