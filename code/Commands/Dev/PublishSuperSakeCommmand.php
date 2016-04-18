<?php


class PublishSuperSakeCommmand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'console:publish';

    /**
     * @var string
     */
    protected $description = 'Copy the supersake to your webroot';

    public function fire()
    {
        $this->writeSuperSakeFileToWebRoot();
    }

    protected function writeSuperSakeFileToWebRoot()
    {
        try {
            file_put_contents(
                BASE_PATH.'/supersake',
                file_get_contents(BASE_PATH.'/console/publish/supersake')
            );
            $this->info('supersake file copied to webroot');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
