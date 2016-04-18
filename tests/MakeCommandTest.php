<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class MakeCommandTest.
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

    /**
     * @var Symfony\Component\Console\Application
     */
    protected $application;

    /**
     * @var MakeDataObjectCommand
     */
    protected $makeDataObjectCommand;

    /**
     * @var Symfony\Component\Console\Tester\CommandTester
     */
    protected $makeDataObjectTester;

    public function setUp()
    {
        parent::setUp();

        // setup tester
        $this->application = new Application();
        $this->application->add(new MakeDataObjectCommand());
        $this->application->add(new MakeDataExtensionCommand());

        $this->makeDataObjectCommand = $this->application->find('make:dataobject');
        $this->makeDataObjectTester = new CommandTester($this->makeDataObjectCommand);

        $this->consoleStubPath = BASE_PATH.'/console/stubs';
        $this->mysiteStubPath = BASE_PATH.'/mysite/stubs';
        $this->mysiteCodePath = BASE_PATH.'/mysite/code';
        $this->testsStubPath = BASE_PATH.'/console/tests/stubs';
        $this->testsCodePath = BASE_PATH.'/console/tests/code';
    }

    public function itGetsTheCorrectStubFileName()
    {
        $command = new MakeDataObjectCommand();
        $this->assertEquals('DataObject', $command->getCommandClass());

        $command = new MakeCommandCommand();
        $this->assertEquals('Command', $command->getCommandClass());
    }

    /**
     * MakeDataObjectCommand should find DataObject.php.stub from :
     * 1. Config setting if set
     * 2. mysite/stubs if found
     * 3. console/stubs by default.
     */
    public function testItFindsTheStubByNameConventionOrConfig()
    {
        $this->makeDataObjectTester->execute(['command' => $this->makeDataObjectCommand->getName(), 'name' => '']);

        // php stub
        $this->assertEquals($this->consoleStubPath.'/DataObject.php.stub', $this->makeDataObjectCommand->getPhpStub());
        // template stub
        $this->assertEquals($this->consoleStubPath.'/DataObject.ss.stub', $this->makeDataObjectCommand->getTemplateStub());

        // custom setting
        Config::inst()->update('MakeCommand', 'stub_dir', 'console/tests/stubs');
        $this->assertEquals($this->testsStubPath.'/DataObject.php.stub', $this->makeDataObjectCommand->getPhpStub());
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
        $this->makeDataObjectTester->execute(['command' => $this->makeDataObjectCommand->getName(), 'name' => '']);
        $this->assertEquals($this->mysiteCodePath.'/dataobjects', $this->makeDataObjectCommand->getTargetDirectory());
    }

    /**
     * When the developer wants all generated classes to be written a different directory/module.
     */
    public function testItUsesTheDefaultDirectoryStructureWithinAGivenDirectory()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', 'mymodule');
        $this->makeDataObjectTester->execute(['command' => $this->makeDataObjectCommand->getName(), 'name' => '']);

        $this->assertEquals(BASE_PATH.'/mymodule/code/dataobjects', $this->makeDataObjectCommand->getTargetDirectory());
    }

    /**
     * Like when a developer wants DataObjects to be in mysite/code/models.
     */
    public function testItOverridesASingleLocationForAClassType()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', ['DataObject' => 'mysite/code/models']);
        $this->makeDataObjectTester->execute(['command' => $this->makeDataObjectCommand->getName(), 'name' => '']);

        $this->assertEquals($this->mysiteCodePath.'/models', $this->makeDataObjectCommand->getTargetDirectory());

        // It should not override anything if the given array is not associative
        Config::inst()->remove('MakeCommand', 'default_dirs');
        Config::inst()->update('MakeCommand', 'default_dirs', ['mysite/code/models']);
        $this->makeDataObjectTester->execute(['command' => $this->makeDataObjectCommand->getName(), 'name' => '']);

        $this->assertEquals($this->mysiteCodePath.'/dataobjects', $this->makeDataObjectCommand->getTargetDirectory());
    }

    /**
     * Like when the developer want everything to be in mysite/code.
     */
    public function testAllGeneratedFilesNeedToBeWrittenInOneDirectory()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', 'mysite/code');
        $this->makeDataObjectTester->execute(['command' => $this->makeDataObjectCommand->getName(), 'name' => '']);

        $this->assertEquals($this->mysiteCodePath, $this->makeDataObjectCommand->getTargetDirectory());
    }

    /**
     * Like when the directory is set with the --dir option.
     */
    public function testTheTargetDirectoryCanBeSetAllongWithACommand()
    {
        $dataObect = 'MyTestDataObject';
        $directory = BASE_PATH.'/console/tests/customdir';
        $filePath = $directory.'/'.$dataObect.'.php';

        // run command
        $this->makeDataObjectTester->execute([
            'command'      => $this->makeDataObjectCommand->getName(),
            'name'         => $dataObect,
            '--dir'        => 'console/tests/customdir',
        ]);

        $this->assertTrue(is_file($filePath));

        unlink($filePath);
        rmdir($directory);
    }

    /**
     * This will write the file to the test directory and remove it afterwards.
     */
    public function testItGeneratesAClassFileFromStub()
    {
        Config::inst()->update('MakeCommand', 'default_dirs', 'console/tests/code');

        // so we unlink the file created by the command
        $dataObect = 'MyTestDataObject';
        $filePath = BASE_PATH.'/console/tests/code/'.$dataObect.'.php';

        // run command
        $this->makeDataObjectTester->execute([
            'command'      => $this->makeDataObjectCommand->getName(),
            'name'         => $dataObect,
        ]);

        $this->assertTrue(is_file($filePath));

        // run again, should fail because file exists
        $this->makeDataObjectTester->execute([
            'command'      => $this->makeDataObjectCommand->getName(),
            'name'         => $dataObect,
        ]);

        $msg = 'already exists';

        $this->assertTrue(strpos($this->makeDataObjectTester->getDisplay(), $msg) > 0);

        unlink($filePath);
    }
}
