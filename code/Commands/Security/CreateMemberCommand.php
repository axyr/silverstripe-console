<?php

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
        $member = $this->createMember();

        if ($member !== false) {
            $this->info('Member created with :');
            $this->line("ID \t\t: ".$member->ID);
            $this->line("Email \t\t: ".$member->Email);
            $this->line("Password \t: ".$this->getPasswordFromInputOrEmail($member->Email));
            $this->line("FirstName \t: ".$member->FirstName);
            if ((bool) $member->Surname) {
                $this->line("Surname \t: ".$member->Surname);
            }
        }
    }

    protected function createMember()
    {
        $email = $this->getValidEmailInput();
        if ($email !== false) {
            $member = new Member([
                'Email'     => $email,
                'Password'  => $this->getPasswordFromInputOrEmail($email),
                'FirstName' => $this->getFirstNameFromInputOrEmail($email),
                'Surname'   => $this->getLastNameInput(),
            ]);

            $member->write();

            return $member;
        }

        return false;
    }

    /**
     * @param string$email
     *
     * @return DataObject
     */
    protected function emailExists($email)
    {
        return (bool) Member::get()->where("Email = '".Convert::raw2sql($email)."'")->first();
    }

    /**
     * Get the desired email from the input.
     *
     * @return bool|string
     */
    protected function getValidEmailInput()
    {
        $email = (string) $this->argument('email');

        if (!Email::is_valid_address($email)) {
            $this->error($email.' is not a valid emailaddress');

            return false;
        }

        if ((bool) $this->emailExists($email)) {
            $this->error('A Member already exists with this emailaddress');

            return false;
        }

        return (string) $email;
    }

    /**
     * @param string $email
     *
     * @return string
     */
    protected function getPasswordFromInputOrEmail($email)
    {
        $password = (string) $this->option('password');

        return (bool) $password ? $password : $email;
    }

    /**
     * @param string $email
     *
     * @return string
     */
    protected function getFirstNameFromInputOrEmail($email)
    {
        $firstName = $this->option('firstname');

        if (!(bool) $firstName) {
            list($firstName) = explode('@', $email);
        }

        return (string) $firstName;
    }

    /**
     * Surname or Lastname, its the same
     * I rather prefer Lastname.
     */
    protected function getLastNameInput()
    {
        $surName = (string) $this->option('surname');
        $lastName = (string) $this->option('lastname');

        return (bool) $surName ? $surName : $lastName;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['password', 'p', InputOption::VALUE_OPTIONAL, 'Optional Password. Defaults to given emailaddress'],
            ['firstname', 'f', InputOption::VALUE_OPTIONAL, 'Optional FirstName. Default to name@ part of the emailaddress'],
            ['surname', 's', InputOption::VALUE_OPTIONAL, 'Optional Surname'],
            ['lastname', 'l', InputOption::VALUE_OPTIONAL, 'Alias for Surname'],
        ];
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['email', InputArgument::REQUIRED, 'The emailaddress of the Member'],
        ];
    }
}
