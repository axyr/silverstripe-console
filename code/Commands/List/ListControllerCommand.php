<?php


class ListControllerCommand extends AbstractListCommand
{
    /**
     * @var string
     */
    protected $signature = 'list:controller';

    /**
     * @var string
     */
    protected $description = 'List all subclasses of Controller';

    protected function getClassName()
    {
        return 'Controller';
    }

    /**
     * @param $className
     *
     * @return array
     */
    protected function getParentClasses($className)
    {
        // removes $className and Controller => RequestHandler => ViewableData => Object
        $parentClasses = array_slice(parent::getParentClasses($className), 4, -1);

        return array_reverse($parentClasses);
    }
}
