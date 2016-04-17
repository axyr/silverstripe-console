<?php

/**
 * Class InstallPublishTest
 *
 * @mixin PHPUnit_Framework_TestCase
 */
class InstallPublishTest extends SapphireTest
{

    /**
     * After Composer install, the supersake file should be copied to the webroot.
     */
    public function testTheSuperSakeFileIsInWebRoot()
    {
        $this->assertTrue(is_file(BASE_PATH . '/supersake'));
    }
    
    public function testSuperSakeProtectionMessageIsShown()
    {
        
    }
    
}
