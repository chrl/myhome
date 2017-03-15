<?php
/**
 * Created by PhpStorm.
 * User: chrl
 * Date: 22/02/17
 * Time: 17:32
 */

namespace Tests\AppBundle\Command;
use AppBundle\Command\RunCronsCommand;
use AppBundle\Command\UpdateDisplayCommand;
use AppBundle\DataFixtures\ORM\LoadVariableData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


class RunCronsCommandTest extends KernelTestCase
{

	public function setUp()
	{
		self::bootKernel();

		$container = self::$kernel->getContainer();
		$doctrine = $container->get('doctrine');
		$entityManager = $doctrine->getManager();

		$fixture = new LoadVariableData();
		$fixture->load($entityManager);


		parent::setUp(); // TODO: Change the autogenerated stub
	}

	public function testExecute()
	{

		$application = new Application(self::$kernel);

		$application->add(new RunCronsCommand());

		$command = $application->find('home:run-crons');
		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'command'  => $command->getName(),
		));

		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertEmpty($output);

	}
}
