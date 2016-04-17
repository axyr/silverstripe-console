<?php


class SuperSakeChecker
{

    /**
     * Checks if the supersake file in webroot is protected with htaccess or web.config
     */
    public function superSakeIsNotProtected()
    {
        if($this->isApache()) {
            return $this->checkHtAccessProtection();
        }

        if($this->isIIS()) {
            return $this->checkWebConfigProtection();
        }

        return 'unknown server. please make sure the supersake has no direct access';
    }

    /**
     * @return bool|string
     */
    protected function checkHtAccessProtection()
    {
        $file = BASE_PATH . '/.htaccess';

        if(!is_file($file) || strpos(file_get_contents($file), '<Files supersake>') !== false) {
            return 'supersake is not protected in .htaccess';
        }

        return false;
    }

    /**
     * @return bool|string
     */
    protected function checkWebConfigProtection()
    {
        $file = BASE_PATH . '/web.config';

        if(!is_file($file) || strpos(file_get_contents($file), '<add fileExtension="supersake" allowed="false"/>') !== false) {
            return 'supersake is not protected in web.config';
        }

        return false;
    }

    /**
     * Check if the web server is IIS and version greater than the given version.
     * @return boolean
     */
    public function isIIS($fromVersion = 7) {
        if(strpos($this->findWebserver(), 'IIS/') === false) {
            return false;
        }
        return substr(strstr($this->findWebserver(), '/'), -3, 1) >= $fromVersion;
    }

    /**
     * @return bool
     */
    public function isApache() {
        if(strpos($this->findWebserver(), 'Apache') !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Find the webserver software running on the PHP host.
     * @return string|boolean Server software or boolean FALSE
     */
    public function findWebserver() {
        // Try finding from SERVER_SIGNATURE or SERVER_SOFTWARE
        if(!empty($_SERVER['SERVER_SIGNATURE'])) {
            $webserver = $_SERVER['SERVER_SIGNATURE'];
        } elseif(!empty($_SERVER['SERVER_SOFTWARE'])) {
            $webserver = $_SERVER['SERVER_SOFTWARE'];
        } else {
            return false;
        }

        return strip_tags(trim($webserver));
    }
}
