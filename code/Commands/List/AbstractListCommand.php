<?php


abstract class AbstractListCommand extends SilverstripeCommand
{
    public function fire()
    {
        $this->table($this->getTableHeaders(), $this->getTableData());
    }

    /**
     * @return string
     */
    abstract protected function getClassName();

    /**
     * @return array
     */
    protected function getTableData()
    {
        $classes = (array) ClassInfo::subclassesFor($this->getClassName());

        unset($classes[$this->getClassName()]);

        foreach ($classes as $key => $class) {
            $classes[$class] = [
                $class,
                implode("\n", (array) $this->getExtensions($class)),
                implode(' => ', (array) $this->getParentClasses($class)),
                $this->getModule($class),
            ];
        }

        ksort($classes);

        return $classes;
    }

    /**
     * @return array
     */
    protected function getTableHeaders()
    {
        return ['ClassName', 'Extensions', 'Extends', 'Module'];
    }

    /**
     * @param $className
     *
     * @return array
     */
    protected function getExtensions($className)
    {
        return (array) Config::inst()->get($className, 'extensions', Config::UNINHERITED);
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getModule($className)
    {
        $reflection = new \ReflectionClass($className);
        $file = $reflection->getFileName();

        $path = str_replace([BASE_PATH.'/'], '', $file);
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        return (string) array_shift($parts);
    }

    /**
     * @param string $className
     *
     * @return array
     */
    protected function getParentClasses($className)
    {
        return ClassInfo::ancestry($className);
    }
}
