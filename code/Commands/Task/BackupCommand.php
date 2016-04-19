<?php


use Symfony\Component\Console\Input\InputOption;

class BackupCommand extends SilverstripeCommand
{
    protected $name = 'task:backup';

    public function fire()
    {
        $this->error('Not implemented yet');

        if($this->option('assets')) {
            //$this->backupAssets();
        }

        if($this->option('database')) {
            //$this->backupDatabase();
        }
    }

    protected function backupAssets()
    {
        $this->info('Assets backupped in file :');
    }

    protected function backupDatabase()
    {
        $this->info('Database backupped in file :');
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['assets', 'a', InputOption::VALUE_OPTIONAL, 'Backup assets'],
            ['database', 'd', InputOption::VALUE_OPTIONAL, 'Backup database'],
        ];
    }
}
