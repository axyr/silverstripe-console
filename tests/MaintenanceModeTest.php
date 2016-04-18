<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class MaintenanceModeTest
 *
 * @mixin PHPUnit_Framework_TestCase
 */
class MaintenanceModeTest extends SapphireTest
{
    /**
     * @var Symfony\Component\Console\Application
     */
    protected $application;

    /**
     * @var string
     */
    protected $filePath;

    public function setUp()
    {
        parent::setUp();

        // setup tester
        $this->application = new Application();
        $this->application->add(new MaintenanceDownCommand());
        $this->application->add(new MaintenanceUpCommand());

        $this->filePath = BASE_PATH.'/framework/down';
    }

    public function testMaintenanceDownCommand()
    {
        $command = $this->application->find('down');
        $tester  = new CommandTester($command);
        $tester->execute(['command' => $command]);

        $this->assertEquals('Application is now in maintenance mode.', trim($tester->getDisplay()));
        $this->assertTrue(is_file($this->filePath));

        @unlink($this->filePath);
    }

    public function testMaintenanceUpCommand()
    {
        $command = $this->application->find('up');
        $tester  = new CommandTester($command);

        $tester->execute(['command' => $command]);
        $this->assertEquals('Application is now live.', trim($tester->getDisplay()));
        $this->assertTrue(!is_file($this->filePath));

        @unlink($this->filePath);
    }


}
