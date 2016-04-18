<?php


use Symfony\Component\Console\Input\InputOption;

class MakePageCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected $name = 'make:page';

    /**
     * @var string
     */
    protected $description = 'Create a new Page/Controller class pair and optional Layout template';

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['template', 't', InputOption::VALUE_NONE, 'Create a new Layout file for the Page.'],
        ];
    }
}
