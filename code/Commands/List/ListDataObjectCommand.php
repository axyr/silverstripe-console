<?php


class ListDataObjectCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $signature = 'list:dataobject';

    /**
     * @var string
     */
    protected $description = 'List all subclasses of DataObject';

    public function fire()
    {
        $headers = array('DataObject', 'ParentClasses', 'Location');

        $this->table($headers, $this->dataobjects());
    }

    public function dataobjects()
    {
        $dataObjects = ClassInfo::subclassesFor('DataObject');

        unset($dataObjects['DataObject']);

        $list = array();

        foreach($dataObjects as $key => $dataObject) {

            $parentClasses = ClassInfo::ancestry($dataObject);
            array_shift($parentClasses);
            array_shift($parentClasses);
            array_shift($parentClasses);
            array_pop($parentClasses);
            $parentClasses = array_reverse($parentClasses);

            $extensions = $this->extensions($dataObject);

            $reflection = new \ReflectionClass($dataObject);
            $file = $reflection->getFileName();

            $list[$dataObject] = array(
                $dataObject,
                implode(' => ', $parentClasses),
                str_replace(array(BASE_PATH . '/'), '', $file)
            );
        }

        ksort($list);

        return $list;
    }

    protected function extensions($className)
    {
        $extensionClasses = (array)ClassInfo::subclassesFor('Object');

        $owners = (array)array_filter($extensionClasses, function($class) use ($className) {
            $config = Config::inst()->get($class, 'extensions', Config::UNINHERITED);
            return ($config !== null && in_array($className, $config, null));
        });
        return $owners;
    }


}
