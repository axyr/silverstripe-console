<?php


class CreateMemberCommand extends SilverstripeCommand
{
    /**
     * @var string
     */
    protected $name = 'security:createmember';

    /**
     * @var string
     */
    protected $description = 'Create a new Member';

    public function fire()
    {
        $this->warn('Not implemented yet!');
    }
}
