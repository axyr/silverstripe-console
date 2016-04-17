<?php

use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SilverstripeListCommand
 *
 * We really need NameSpaces in Silverstripe....
 */
class DefaultCommand extends ListCommand
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $msg = $this->checkProtection();
        if((bool)$msg) {
            $output->writeln("\n");
            $output->writeln('<error>'.$msg.'</error>');
        }
    }

    /**
     * @return bool|string
     */
    public function checkProtection()
    {
        $checker =  new SuperSakeChecker();
        return $checker->superSakeIsNotProtected();
    }

}
