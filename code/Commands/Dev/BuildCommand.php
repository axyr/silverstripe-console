<?php

/**
 * Class BuildCommand.
 */
class BuildCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'dev:build';

    /**
     * @var string
     */
    protected $description = 'Rebuild the database';

    CONST CHANGE  = '+';
    CONST DELETE  = '-';
    CONST NOTICE  = '*';
    CONST ERROR   = '!';

    public function fire()
    {
        // make sure we have a fresh manifest
        $this->call('clear:cache');

        /** @var DatabaseAdmin $da */
        $da = DatabaseAdmin::create();

        // hack untill we have something better...
        ob_start();
        $da->doBuild();
        $content = ob_get_contents();
        ob_get_clean();
        foreach(explode("\n", $content) as $line) {
            $this->displayBuildMessage($line);
        }
    }

    protected function displayBuildMessage($buildMessage)
    {
        $message = trim($buildMessage);

        $change = BuildCommand::CHANGE;
        $delete = BuildCommand::DELETE;
        $notice = BuildCommand::NOTICE;
        $error  = BuildCommand::ERROR;

        if(Str::startsWith($message, $change)) {
            $this->info($this->parseMessage($message, $change));
        }elseif(Str::startsWith($message, $delete)) {
            $this->warn($this->parseMessage($message, $delete));
        }elseif(Str::startsWith($message, $notice)) {
            $this->comment($this->parseMessage($message, $notice));
        }elseif(Str::startsWith($message, $error)) {
            $this->error($this->parseMessage($message, $error));
        }elseif($message){
            $this->line($this->parseMessage($message));
        }
    }

    /**
     * @param string $message
     * @param string$replace
     * @return string
     */
    protected function parseMessage($message, $replace = '')
    {
        if($replace) {
            $message = Str::replaceFirst($replace, '', $message);
        }

        return trim($message);
    }
}
