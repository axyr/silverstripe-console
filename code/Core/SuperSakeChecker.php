<?php


class SuperSakeChecker
{
    /**
     * Checks if the supersake file in webroot is protected with htaccess or web.config.
     *
     * From the command line we don't know on what server software we are running,
     * so we just checking if a .htaccess or web.config exists and has the protection lines in it.
     *
     * If one of the files has access to the file denied, we consider this protected
     *
     * @return bool|string
     */
    public function superSakeIsNotProtected()
    {
        $htaccess = $this->hasHtAccessProtection(BASE_PATH.'/.htaccess');
        $webConfig = $this->hasWebConfigProtection(BASE_PATH.'/web.config');

        // nothing is done, add the instructions
        return !$htaccess && !$webConfig;
    }

    /**
     * @param string $file
     *
     * @return bool|string
     */
    protected function hasHtAccessProtection($file)
    {
        return is_file($file) &&
               strpos(file_get_contents($file), '<Files supersake>') !== false;
    }

    /**
     * @param string $file
     *
     * @return bool|string
     */
    protected function hasWebConfigProtection($file)
    {
        return is_file($file) &&
               strpos(file_get_contents($file), '<add fileExtension="supersake" allowed="false"/>') !== false;
    }

    public function htaccessContent()
    {
        $content = <<<'EOF'

# Deny access to supersake
<Files supersake>
    Order allow,deny
    Deny from all
</Files>

EOF;

        return $content;
    }

    public function webconfigContent()
    {
        $content = <<<'EOF'
<add fileExtension="supersake" allowed="false"/>
EOF;

        return $content;
    }
}
