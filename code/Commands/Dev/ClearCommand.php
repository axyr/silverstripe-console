<?php


class ClearCommand extends SilverstripeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cache by deleting the files and rebuild the manifest';

    public function fire()
    {
        $this->delTree(TEMP_FOLDER);
        $this->info('cache cleared!');
        // Rebuild the config manifest
        //$configManifest = new SS_ConfigManifest(BASE_PATH, false, true);
        //$configManifest->regenerate();
        // Rebuild the class manifest.
        //$manifest = new SS_ClassManifest(BASE_PATH, false, true);
        //$manifest->regenerate();

        //$this->info('cache rebuild!');
    }

    protected function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}
