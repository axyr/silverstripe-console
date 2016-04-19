<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class MaintenanceModeExtensionTest.
 *
 * @mixin PHPUnit_Framework_TestCase
 */
class MaintenanceModeExtensionTest extends FunctionalTest
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

        $this->filePath = BASE_PATH.'/mysite/down';
    }

    public function testItThrowsA503WhenInMaintenanceMode()
    {
        $command = $this->application->find('down');
        $tester = new CommandTester($command);
        $tester->execute(['command' => $command]);

        $response = $this->get('/Security/login');
        $this->assertEquals(503, $response->getStatusCode());

        $command = $this->application->find('up');
        $tester = new CommandTester($command);
        $tester->execute(['command' => $command]);

        $response = $this->get('/Security/login');
        $this->assertEquals(200, $response->getStatusCode());

        @unlink($this->filePath);
    }

    public function testItAllowsAnIpAddressDuringMaintenanceMode()
    {
        Config::inst()->update('MaintenanceMode', 'allowed_ips', [
            '123.456.789.0',
        ]);

        $command = $this->application->find('down');
        $tester = new CommandTester($command);
        $tester->execute(['command' => $command]);

        $response = $this->get('/Security/login');
        $this->assertEquals(503, $response->getStatusCode());

        $_SERVER['REMOTE_ADDR'] = '123.456.789.0';
        $response = $this->get('/Security/login');
        $this->assertEquals(200, $response->getStatusCode());

        @unlink($this->filePath);
    }
}
