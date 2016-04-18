<?php

use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SilverstripeListCommand.
 *
 * We really need NameSpaces in Silverstripe....
 */
class DefaultCommand extends ListCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->addProtectionWarningToOutput($output);
    }

    /**
     * @return bool|string
     */
    public function addProtectionWarningToOutput(OutputInterface $output)
    {
        $checker = new SuperSakeChecker();
        if ((bool) $checker->superSakeIsNotProtected()) {
            $output->writeln("\n");
            $output->writeln('<error>The supersake file is accessible by browsers</error>');
            $output->writeln('<error>Please lock down the file by either : </error>');
            $output->writeln('<comment>Adding this to your .htaccess file</comment>');
            $output->writeln($checker->htaccessContent());
            $output->writeln('<comment>Or add this to the <fileExtensions allowUnlisted="true"> section of your web.config file if you are running on iis</comment>');
            $output->writeln($checker->webconfigContent());
            $output->writeln("\n");
            $output->writeln('<comment>Or run $ `php supersake protect` to write these lines to the files</comment>');
            $output->writeln("\n");
        }
    }
}
