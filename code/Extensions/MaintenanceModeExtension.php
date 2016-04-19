<?php

/**
 * Class MaintenanceModeExtension.
 * 
 * throw a 503 if mysite/down exists
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
        if ($this->isDownForMaintenance() && !$this->clientIpIsAllowedInMaintenanceMode()) {
            $this->throw503();
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
     * Somehow $this->owner->httpError keeps the website spinning.
     *
     * Maybe not very Silverstripe'sch, but at least this works
     *
     * @throws SS_HTTPResponse_Exception
     */
    protected function throw503()
    {
        $message = 'Website is down for maintenance';
        $errorFile = $this->get503File();
        $content = is_file($errorFile) ? file_get_contents($errorFile) : '<h1>'.$message.'</h1>';

        throw new SS_HTTPResponse_Exception(new SS_HTTPResponse($content, 503, $message));
    }

    /**
     * @return string
     */
    protected function get503File()
    {
        $custom = BASE_PATH.(string) Config::inst()->get('MaintenanceMode', 'file');

        return is_file($custom) ? $custom : BASE_PATH.'/assets/error-503.html';
    }

    /**
     * @return bool
     */
    protected function clientIpIsAllowedInMaintenanceMode()
    {
        $ip = $this->getClientIp();
        $allowed = $this->getAllowedIpAddresses();

        return (bool) $allowed && (bool) $ip && in_array($ip, $allowed);
    }

    /**
     * @return string|false
     */
    protected function getClientIp()
    {
        $request = $this->owner->getRequest();

        return (bool) $request ? $request->getIP() : false;
    }

    /**
     * @return array
     */
    protected function getAllowedIpAddresses()
    {
        return (array) Config::inst()->get('MaintenanceMode', 'allowed_ips');
    }
}
