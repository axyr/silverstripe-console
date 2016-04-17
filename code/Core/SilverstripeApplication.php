<?php

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Class Application
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
        parent::__construct();

        $this->loadCommands();
        
        $this->add($default = new DefaultCommand());
        $this->setDefaultCommand($default->getName());

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);
    }

    public function run($input = null, $output = null)
    {
        return parent::run($input, $output);
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @return int
     */
    public function call($command, array $parameters = [])
    {
        $parameters = array_merge((array)$command, $parameters);

        $this->lastOutput = new BufferedOutput;

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
     * Add a command to the console.
     *
     * @param  \Symfony\Component\Console\Command\Command  $command
     * @return \Symfony\Component\Console\Command\Command
     */
    public function add(SymfonyCommand $command)
    {
        return $this->addToParent($command);
    }

    /**
     * Add the command to the parent instance.
     *
     * @param  \Symfony\Component\Console\Command\Command  $command
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function addToParent(SymfonyCommand $command)
    {
        return parent::add($command);
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
     * Load all available commands into the console application
     */
    protected function loadCommands()
    {
        /**
         * Why does this not work
         */
        ///$commands = ClassInfo::subclassesFor('SilverstripeCommand'); //var_dump($commands);exit();

        // This works, but does not load custom Commands
        $commands = ClassInfo::classes_for_folder(BASE_PATH . '/console/code/');

        /** @var SilverstripeCommand $command */
        foreach ($commands as $command) {
            $reflection = new ReflectionClass($command);
            if (!$reflection->isAbstract() && $reflection->isSubclassOf('SilverstripeCommand')) {
                $this->add(new $command());
            }
        }
    }

}
