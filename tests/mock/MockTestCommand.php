<?php

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MockTestCommand extends SilverstripeCommand implements TestOnly
{
    protected $name = 'mock:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mock a TestCommand';

    public function fire()
    {
    }

    protected function getOptions()
    {
        return [
            ['none', 'n', InputOption::VALUE_NONE, 'Option without a value'],
            ['optional', 'o', InputOption::VALUE_OPTIONAL, 'Option with an optional value'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the argument'],
        ];
    }
}
