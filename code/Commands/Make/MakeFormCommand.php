<?php


use Symfony\Component\Console\Input\InputOption;

class MakeFormCommand extends MakeCommand
{
    /**
     * @var string
     */
    protected $name = 'make:form';

    /**
     * @var string
     */
    protected $description = 'Create a new Form class and optional template';

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['template', 't', InputOption::VALUE_NONE, 'Create a custom template for this Form'],
        ];
    }
}
