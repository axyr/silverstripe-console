<?php


class ListDataObjectCommand extends AbstractListCommand
{
    /**
     * @var string
     */
    protected $signature = 'list:dataobject';

    /**
     * @var string
     */
    protected $description = 'List all subclasses of DataObject';

    /**
     * @return string
     */
    protected function getClassName()
    {
        return 'DataObject';
    }

    /**
     * @param string $className
     *
     * @return array
     */
    protected function getParentClasses($className)
    {
        // removes $className and DataObject => ViewableData => Object
        $parentClasses = array_slice(parent::getParentClasses($className), 3, -1);

        return array_reverse($parentClasses);
    }
}
