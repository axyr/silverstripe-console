<?php


use Symfony\Component\Console\Input\InputOption;

class MakeControllerCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected $name = 'make:controller';

    /**
     * @var string
     */
    protected $description = 'Create a new Controller class and optional template';

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['template', 't', InputOption::VALUE_NONE, 'Create a new template file for the Controller.'],
        ];
    }
}
