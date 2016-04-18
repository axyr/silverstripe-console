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
            $message  = 'Website is down for maintenance';
            return $this->owner->httpError(503, $message);
        }
    }

    /**
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return file_exists(BASE_PATH.'/mysite/down');
    }
}
