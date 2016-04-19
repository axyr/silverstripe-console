<?php

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application.
 *
 * Shameless copy/paste from Taylor Otwell's Laravel
 */
class SilverstripeApplication extends SymfonyApplication
{
    /**
     * The output from the previous command.
     *
     * @var \Symfony\Component\Console\Output\BufferedOutput
     */
    protected $lastOutput;

    public function __construct()
    {
        $name = 'Silverstripe Console';

        $version = '1';

        parent::__construct($name, $version);

        $this->loadCommands();

        $this->add($default = new DefaultCommand());
        $this->setDefaultCommand($default->getName());

        $this->setAutoExit(false);
        $this->setCatchExceptions(true);
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        return parent::run($input, $output);
    }

    /**
     * Run an Silverstripe console command by name.
     *
     * @param string $command
     * @param array  $parameters
     *
     * @return int
     */
    public function call($command, array $parameters = [])
    {
        $parameters = array_merge((array) $command, $parameters);

        $this->lastOutput = new BufferedOutput();

        $this->setCatchExceptions(false);

        $result = $this->run(new ArrayInput($parameters), $this->lastOutput);

        $this->setCatchExceptions(true);

        return $result;
    }

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output()
    {
        return $this->lastOutput ? $this->lastOutput->fetch() : '';
    }

    /**
     * Get the default input definitions for the applications.
     *
     * This is used to add the --env option to every available command.
     *
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();

        $definition->addOption($this->getEnvironmentOption());

        return $definition;
    }

    /**
     * Get the global environment option for the definition.
     *
     * @return \Symfony\Component\Console\Input\InputOption
     */
    protected function getEnvironmentOption()
    {
        $message = 'The environment the command should run under.';

        return new InputOption('--env', null, InputOption::VALUE_OPTIONAL, $message);
    }

    /**
     * Load all available commands into the console application.
     */
    protected function loadCommands()
    {
        // somehow ClassInfo::subclassesFor('SilverstripeCommand'); does not work
        $classes = SS_ClassLoader::instance()->getManifest()->getClasses();

        /* @var SilverstripeCommand $command */
        foreach ($classes as $class => $path) {
            $this->addCommandOrSilentlyFail($class, $path);
        }
    }

    /**
     * When developing and renaming or removing a Command, the manifest is not always updated.
     *
     * We don't want file_not_found or class_does_not_exists error for commands,
     * because that prevente running commands like cache:clear etc.
     *
     * @param string $class
     * @param string $path
     */
    protected function addCommandOrSilentlyFail($class, $path)
    {
        if (is_file($path) && class_exists($class)) {
            $reflection = new ReflectionClass($class);
            if (!$reflection->isAbstract() && $reflection->isSubclassOf('SilverstripeCommand')) {
                $this->add(new $class());
            }
        }
    }
}
