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
            $this->owner->httpError(503, 'Website is down for maintenance');
        }
    }

    /**
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return file_exists(BASE_PATH.'/framework/down');
    }
}
