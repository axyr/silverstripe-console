<?php


class SuperSakeChecker
{

    /**
     * Checks if the supersake file in webroot is protected with htaccess or web.config
     *
     * From the command line we don't know on what server software we are running,
     * so we just checking if a .htaccess or web.config exists and has the protection lines in it.
     *
     * @return bool|string
     */
    public function superSakeIsNotProtected()
    {
        $file = BASE_PATH . '/.htaccess';
        if(is_file($file)) {
            return $this->checkHtAccessProtection($file);
        }

        $file = BASE_PATH . '/web.config';
        if(is_file($file)) {
            return $this->checkWebConfigProtection($file);
        }

        return '';
    }

    /**
     * @param string $file
     * @return bool|string
     */
    protected function checkHtAccessProtection($file)
    {
        if(!is_file($file) || strpos(file_get_contents($file), '<Files supersake>') !== false) {
            return 'supersake is not protected in .htaccess';
        }

        return false;
    }

    /**
     * @param string $file
     * @return bool|string
     */
    protected function checkWebConfigProtection($file)
    {
        if(!is_file($file) || strpos(file_get_contents($file), '<add fileExtension="supersake" allowed="false"/>') !== false) {
            return 'supersake is not protected in web.config';
        }

        return false;
    }
}
