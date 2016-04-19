<?php


class ClearTestDatabasesCommand extends SilverstripeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clear:tmpdbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the test databases';

    public function fire()
    {
        SapphireTest::delete_all_temp_dbs();

        $this->deleteAllTempDbs();

        $this->line(" ");
        $this->info("Test databases cleared");
    }

    protected function deleteAllTempDbs() {
        $prefix = defined('SS_DATABASE_PREFIX') ? SS_DATABASE_PREFIX : 'ss_';
        foreach(DB::get_schema()->databaseList() as $dbName) {
            if(preg_match(sprintf('/^%stmpdb[0-9]+$/', $prefix), $dbName)) {
                DB::get_schema()->dropDatabase($dbName);
                $this->info("Dropped database $dbName");
            }
        }
    }
}
