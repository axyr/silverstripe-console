<?php

/**
 * Class MaintenanceModeExtension
 *
 * @property Controller $owner
 */
class MaintenanceModeExtension extends Extension
{

    /**
     * @throws SS_HTTPResponse_Exception
     */
    public function onBeforeInit()
    {
        if($this->isDownForMaintenance())
        {
            $this->respond503();
        }
    }

    /**
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return file_exists(BASE_PATH.'/mysite/down');
    }

    /**
     * Somehow $this->owner->httpError puts a burden on de Database.
     *
     * Maybe not very Silverstripe'sch, but at least this works
     */
    protected function respond503()
    {
        $message = 'Website is down for maintenance';
        $content = '<h1>'.$message.'</h1>';

        header($message, true, 503);

        $errorFile = $this->get503File();

        if(is_file($errorFile)) {
            $content = file_get_contents($errorFile);
        }

        echo $content;
        exit();
    }

    protected function get503File()
    {
        $custom = BASE_PATH.(string)Config::inst()->get('MaintenanceMode', 'file');

        if(is_file($custom)) {
            return $custom;
        }

        return BASE_PATH.'/assets/error-503.html';
    }

}
