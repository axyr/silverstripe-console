<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class MakeCommandTest
 *
 * @mixin PHPUnit_Framework_TestCase
 */
class MakeCommandTest extends SapphireTest
{
    /**
     * @var string
     */
    protected $consoleStubPath;

    /**
     * @var string
     */
    protected $mysiteStubPath;

    /**
     * @var string
     */
    protected $mysiteCodePath;

    /**
     * @var string
     */
    protected $testsStubPath;

    /**
     * @var string
     */
    protected $testsCodePath;

    public function setUp()
    {
        parent::setUp();

        $this->consoleStubPath = BASE_PATH . '/console/stubs';
        $this->mysiteStubPath  = BASE_PATH . '/mysite/stubs';
        $this->mysiteCodePath  = BASE_PATH . '/mysite/code';
        $this->testsStubPath   = BASE_PATH . '/console/tests/stubs';
        $this->testsCodePath   = BASE_PATH . '/console/tests/code';
    }

    /**
     * MakeDataObjectCommand should find DataObject.php.stub from :
     * 1. Config setting if set
     * 2. mysite/stubs if found
     * 3. console/stubs by default
     */
    public function testItFindsTheStubByNameConventionOrConfig()
    {
        // mysite and console path work the same, so we only test what we have for sure : console path
        $command = new MakeDataObjectCommand();
        // php stub
        $this->assertEquals($this->consoleStubPath .'/DataObject.php.stub', $command->getPhpStub());
        // template stub
        $this->assertEquals($this->consoleStubPath .'/DataObject.ss.stub', $command->getTemplateStub());

        // custom setting
        Config::inst()->update('MakeCommand', 'stub_dir', 'console/tests/stubs');
        $this->assertEquals($this->testsStubPath .'/DataObject.php.stub', $command->getPhpStub());
    }

    /**
     * Generated classes will be written to an opiniated directory,
     * but can be overridden.
     *
     * By default we write the files to folders in :
     * mysite
     *  - code
     *    - commands
     *    - controllers
     *    - dataobjects
     *    - extensions
     *    - forms
     *    - pages
     *
     * We test the overridden method by writing the files to the test/code folder.
     */
    public function testItFindsTheLocationToWriteTheGeneratedClassTo()
    {
        $command = new MakeDataObjectCommand();
        $this->assertEquals($this->mysiteCodePath . '/dataobjects', $command->getTargetDirectory());

        $command = new MakeCommandCommand();
        $this->assertEquals($this->mysiteCodePath . '/commands', $command->getTargetDirectory());
    }

    /**
     * When the developer wants all generated classes to be written a different directory/module.
     */
    public function testItUsesTheDefaultDirectoryStructureWithinAGivenDirectory()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', 'mymodule');

        $command = new MakeDataObjectCommand();
        $this->assertEquals(BASE_PATH . '/mymodule/code/dataobjects', $command->getTargetDirectory());

        $command = new MakeDataExtensionCommand();
        $this->assertEquals(BASE_PATH . '/mymodule/code/extensions', $command->getTargetDirectory());
    }

    /**
     * Like when a developer wants DataObjects to be in mysite/code/models.
     */
    public function testItOverridesASingleLocationForAClassType()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', [
            'DataObject' => 'mysite/code/models'
        ]);

        $command = new MakeDataObjectCommand();
        $this->assertEquals($this->mysiteCodePath . '/models', $command->getTargetDirectory());

        // It should not override anything if the given array is not associative
        Config::inst()->remove('MakeCommand', 'default_dirs');
        Config::inst()->update('MakeCommand', 'default_dirs', [
            'mysite/code/models'
        ]);

        $command = new MakeDataObjectCommand();
        $this->assertEquals($this->mysiteCodePath . '/dataobjects', $command->getTargetDirectory());
    }

    /**
     * Like when the developer want everything to be in mysite/code.
     */
    public function testAllGeneratedFilesNeedToBeWrittenInOneDirectory()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', 'mysite/code');

        $command = new MakeDataObjectCommand();
        $this->assertEquals($this->mysiteCodePath, $command->getTargetDirectory());

        $command = new MakeExtensionCommand();
        $this->assertEquals($this->mysiteCodePath, $command->getTargetDirectory());
    }

    /**
     * This will write the file to the test directory and remove it afterwards.
     */
    public function testItGeneratesAClassFileFromStub()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', 'console/tests/code');

        // so we unlink the file created by the command
        $dataObect = 'MyTestDataObject';
        $command = new MakeDataObjectCommand();
        $filePath = $command->getTargetFile($dataObect);

        // setup tester
        $application = new Application();
        $application->add(new MakeDataObjectCommand());

        $command = $application->find('make:dataobject');
        $commandTester = new CommandTester($command);

        // run command
        $commandTester->execute(array(
            'command'      => $command->getName(),
            'name'         => $dataObect
        ));

        $this->assertTrue(is_file($filePath));

        // run again, should fail because file exists
        $commandTester->execute(array(
            'command'      => $command->getName(),
            'name'         => $dataObect
        ));

        $msg = 'already exists';

        $this->assertTrue(strpos($commandTester->getDisplay(), $msg) > 0);
        
        unlink($filePath);
    }

}
