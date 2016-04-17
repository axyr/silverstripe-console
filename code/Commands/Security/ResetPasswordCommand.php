<?php


class ResetPasswordCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'security:resetpassword';

    /**
     * @var string
     */
    protected $description = 'Send a reset password link to an email address';

    public function fire()
    {
        $this->warn('Not implemented yet!');
    }

}
