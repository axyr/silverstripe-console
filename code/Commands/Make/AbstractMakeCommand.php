<?php


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractMakeCommand extends SilverstripeCommand
{
    /**
     * @config
     *
     * @var array
     */
    private static $default_dirs = [
        'Command'             => 'mysite/code/commands',
        'Controller'          => 'mysite/code/controllers',
        'ControllerExtension' => 'mysite/code/extensions',
        'DataObject'          => 'mysite/code/dataobjects',
        'DataExtension'       => 'mysite/code/extensions',
        'Extension'           => 'mysite/code/extensions',
        'Form'                => 'mysite/code/forms',
        'Page'                => 'mysite/code/pages',
        'Test'                => 'mysite/tests',
        'Theme'               => 'themes',
    ];

    public function fire()
    {
        $class = $this->getNameInput();

        if (!(bool) $class) {
            $this->error('Empty name given. Are you running a test?');

            return false;
        }

        $target = $this->getTargetFile($class);

        if ($this->classOrFileExists($target, $class)) {
            return false;
        }

        $this->writeFile($target, $class);

        $this->clearCache();
    }

    /**
     * @param string $target
     * @param string $class
     *
     * @return bool
     */
    protected function classOrFileExists($target, $class)
    {
        if ($this->classExists($class)) {
            $this->error('class '.$class.' already exists!');

            return true;
        } elseif ($this->fileExists($class)) {
            $this->error('file '.str_replace(BASE_PATH, '', $target).' already exists!');

            return true;
        }

        return false;
    }

    /**
     * @param string $target
     * @param string $class
     */
    protected function writeFile($target, $class)
    {
        $this->makeDirectory();

        file_put_contents($target, $this->buildClass($class));

        $this->info($class.' created successfully in '.str_replace(BASE_PATH, '', $target));
    }

    protected function clearCache()
    {
        if ($this->option('clearcache')) {
            $this->call('cache:clear');
        } else {
            $this->warn('to use the class, please run cache:clear or add the --clearcache next time');
        }
    }

    /**
     * @return string
     */
    public function getPhpStub()
    {
        return $this->getStubFilePath($this->getCommandClass());
    }

    /**
     * @return string
     */
    public function getTemplateStub()
    {
        return Str::replaceLast('.php.stub', '.ss.stub', $this->getPhpStub());
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        $class = $this->getCommandClass();
        $dirs = (array) self::$default_dirs;
        $custom = $this->getTargetDirectoryByOptionOrConfig();

        // --dir=mymodule || MakeCommand.default_dirs = mymodule || MakeCommand.default_dirs = mymodule/somedir
        if (is_string($custom)) {
            $dirs = $this->setTargetDirectoriesByString($custom, $dirs);
            if (is_string($dirs)) {
                return $dirs;
            }
        // MakeCommand.default_dirs = array()
        } elseif (is_array($custom)) {
            $dirs = $this->mergeCustomDirectoriesWithDefault($custom, $dirs);
        }

        return isset($dirs[$class]) ? BASE_PATH.'/'.$dirs[$class] : '';
    }

    /**
     * @param string $customDir
     * @param array  $defaultDirs
     *
     * @return string|array
     */
    protected function setTargetDirectoriesByString($customDir, $defaultDirs)
    {
        // MakeCommand.default_dirs = mymodule/somedir
        if (Str::contains($customDir, '/')) {
            return BASE_PATH.'/'.$customDir;
        }

        // MakeCommand.default_dirs = mymodule
        foreach ($defaultDirs as $key => $dir) {
            $defaultDirs[$key] = Str::replaceFirst('mysite', $customDir, $dir);
        }

        return $defaultDirs;
    }

    /**
     * @param array $customDirs
     * @param array $defaultDirs
     *
     * @return array
     */
    protected function mergeCustomDirectoriesWithDefault($customDirs, $defaultDirs)
    {
        foreach ($customDirs as $key => $dir) {
            if (is_string($key)) {
                $defaultDirs[$key] = $dir;
            }
        }

        return $defaultDirs;
    }

    /**
     * The absolute file path.
     *
     * @return string
     */
    public function getTargetFile($class)
    {
        return $this->getTargetDirectory().'/'.$class.'.php';
    }

    /**
     * Gets the Classname to generate from the called class like
     * MakeDataObjectCommand => DataObject class
     * MakeFormCommand       => Form class.
     *
     * @return string
     */
    public function getCommandClass()
    {
        $class = get_class($this);
        $class = Str::replaceFirst('Make', '', $class);
        $class = Str::replaceLast('Command', '', $class);

        return $class;
    }

    /**
     * @return string|array|int|float|bool
     */
    protected function getTargetDirectoryByOptionOrConfig()
    {
        if ($this->option('dir')) {
            return $this->option('dir');
        }

        return Config::inst()->get('MakeCommand', 'default_dirs');
    }

    /**
     * @return string
     */
    protected function getStubFilePath($stubName)
    {
        $customStubFilePath = $this->getCustomStubPath($stubName);
        if (is_file($customStubFilePath)) {
            return $customStubFilePath;
        }

        $mysiteStubFilePath = $this->getCustomStubPath($stubName);

        if (is_file($mysiteStubFilePath)) {
            return $mysiteStubFilePath;
        }

        return $this->getConsoleStubPath($stubName);
    }

    /**
     * @return string
     */
    protected function getConsoleStubPath($stubName)
    {
        return BASE_PATH.'/console/stubs/'.$stubName.'.php.stub';
    }

    /**
     * @return string
     */
    protected function getMySiteStubPath($stubName)
    {
        return BASE_PATH.'/mysite/stubs/'.$stubName.'.php.stub';
    }

    /**
     * @return string
     */
    protected function getCustomStubPath($stubName)
    {
        $stubDir = Config::inst()->get('MakeCommand', 'stub_dir');

        if ($stubDir) {
            return BASE_PATH.'/'.$stubDir.'/'.$stubName.'.php.stub';
        }

        return '';
    }

    /**
     * @param $class
     *
     * @return bool
     */
    protected function fileExists($class)
    {
        return is_file($this->getTargetFile($class));
    }

    /**
     * @param $class
     *
     * @return bool
     */
    protected function classExists($class)
    {
        $loader = SS_ClassLoader::instance();
        $exists = $loader->classExists($class);
        $path = $loader->getItemPath($class);

        return $exists && is_file($path);
    }

    /**
     * Build the directory for the class if necessary.
     */
    protected function makeDirectory()
    {
        $path = $this->getTargetDirectory();

        if (!is_dir($path)) {
            mkdir($path, 0777, true);

            $this->info('directory '.str_replace(BASE_PATH, '', $path).' created');
        }
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $class
     *
     * @return string
     */
    protected function buildClass($class)
    {
        return str_replace('DummyClass', $class, file_get_contents($this->getPhpStub()));
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return (string) $this->argument('name');
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['clearcache', 'c', InputOption::VALUE_NONE, 'Clear the cache after adding the class'],
            ['dir', 'd', InputOption::VALUE_OPTIONAL, 'Set the directory to write the file to'],
        ];
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
