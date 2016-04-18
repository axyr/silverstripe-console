<?php


class MakeCommandCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected $name = 'make:command';

    /**
     * @var string
     */
    protected $description = 'Create a new Command class';

    protected function buildClass($class)
    {
        $content = parent::buildClass($class);

        $content = str_replace('CommandName', strtolower($this->getNameInput()), $content);

        return $content;
    }
}
