<?php

use Symfony\Component\Console\Input\InputArgument;

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
        $member = $this->getMemberByEmailOrID();

        if (!(bool) $member) {
            $this->error('Member not found');
        } else {
            $link = $this->sendResetPasswordEmail($member);
            $this->info('Email sent to '.$member->getName().'<'.$member->Email.'>');
            $this->line($link);
        }
    }

    /**
     * @return Member|null
     */
    protected function getMemberByEmailOrID()
    {
        $emailorid = $this->argument('emailorid');

        $member = null;

        if (Str::contains($emailorid, '@')) {
            $member = Member::get()->where("Email = '".Convert::raw2sql($emailorid)."'")->first();
        } else {
            $member = Member::get()->byID($emailorid);
        }

        return $member;
    }

    /**
     * Send the reset password email and return the generated link.
     *
     * @param Member $member
     *
     * @return string
     */
    protected function sendResetPasswordEmail(Member $member)
    {
        // hack ?
        global $_FILE_TO_URL_MAPPING;

        if ($_FILE_TO_URL_MAPPING[BASE_PATH]) {
            $_SERVER['REQUEST_URI'] = $_FILE_TO_URL_MAPPING[BASE_PATH];
        }

        $token = $member->generateAutologinTokenAndStoreHash();
        $link = Security::getPasswordResetLink($member, $token);

        /* @var Member_ForgotPasswordEmail $email */
        $email = Member_ForgotPasswordEmail::create();
        $email->populateTemplate($member);
        $email->populateTemplate([
            'PasswordResetLink' => $link,
        ]);
        $email->setTo($member->Email);
        $email->send();

        return $link;
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['emailorid', InputArgument::REQUIRED, 'The emailaddress or ID of the Member'],
        ];
    }
}
