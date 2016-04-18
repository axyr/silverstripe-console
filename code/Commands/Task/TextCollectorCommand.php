<?php


use Symfony\Component\Console\Input\InputOption;

class TextCollectorCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'task:collecttext';

    /**
     * @var string
     */
    protected $description = 'Traverses through files in order to collect the \'entity master tables\' stored in each module.';

    public function fire()
    {
        increase_memory_limit_to();
        increase_time_limit_to();

        /** @var i18nTextCollector $collector */
        $collector = i18nTextCollector::create($this->option('locale'));

        if (!$this->option('locale')) {
            $this->info('Starting text collection. No Locale specified');
        } else {
            $this->info('Starting text collection for: '.$this->option('locale'));
        }

        $merge = $this->getIsMerge();

        if ($merge) {
            $this->info('New strings will be merged with existing strings');
        }

        // Custom writer
        $writerName = $this->option('writer');
        if ($writerName) {
            $writer = Injector::inst()->get($writerName);
            $collector->setWriter($writer);
            $this->info('Set collector writer to: '.$writer);
        }

        // Get restrictions
        $restrictModules = ($this->option('module'))
            ? explode(',', $this->option('module'))
            : null;

        $this->info('Collecting in modules: '.$this->option('module'));

        // hack untill we have something better
        ob_start();
        $collector->run($restrictModules, $merge);
        $this->warn(ob_get_contents());
        ob_get_clean();

        $this->info(__CLASS__.' completed!');
    }

    protected function getIsMerge()
    {
        $merge = $this->option('merge');

        // Default to false if not given
        if (!isset($merge)) {
            $this->error('merge will be enabled by default in 4.0. Please use merge=false if you do not want to merge.');

            return false;
        }

        // merge=0 or merge=false will disable merge
        return !in_array($merge, ['0', 'false']);
    }

    protected function getOptions()
    {
        return [
            ['module', 'm', InputOption::VALUE_REQUIRED, 'Select the modules to collect'],
            ['locale', 'l', InputOption::VALUE_REQUIRED, 'Please specify the target locale'],
            ['merge', 'mr', InputOption::VALUE_NONE, 'Merge with existing'],
            ['writer', 'w', InputOption::VALUE_OPTIONAL, 'Select a custom writer'],
        ];
    }
}
