<?php


class ListInstalledModulesCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'list:modules';

    /**
     * @var string
     */
    protected $description = 'List all installed modules';

    public function fire()
    {

        $this->table($this->getTableHeaders(), $this->getTableData());
    }

    protected function getTableHeaders()
    {
        return ['Module', 'Version'];
    }

    protected function getTableData()
    {
        return $this->getInstalledModules();
    }

    /**
     * @param string $file
     * @return mixed
     */
    protected function parseComposerFile($file)
    {
        return json_decode(file_get_contents($file));
    }

    /**
     * @return string
     */
    protected function getRootComposerFile()
    {
        return BASE_PATH . '/vendor/composer/installed.json';
    }

    protected function getInstalledModules()
    {
        $content = $this->parseComposerFile($this->getRootComposerFile());

        $rows = [];

        foreach($content as $module) {
            $rows[] = [$module->name, $module->version];
        }

        return $rows;
    }
}
