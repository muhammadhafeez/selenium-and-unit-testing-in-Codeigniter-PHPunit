<?php

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

PHPUnit_Extensions_SeleniumTestCase::shareSession(true);

class selenium extends PHPUnit_Extensions_SeleniumTestCase {

    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = 'c:\wamp\www\phpunit\screenshot';
    protected $screenshotUrl = 'http://www.yourwebsite.com';

    protected function setUp() {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://www.yourwebsite.com');
        $this->setTimeout(3000000);
    }
    public function testSignup() {
        $this->open('http://www.yourwebsite.com/signup');
        $this->windowMaximize();
        $this->windowFocus();
        $this->type('name=emailId', 'testmail@gmail.com'); // insert linkedin longin email
        $this->type('name=userPassword', '2EIsle23'); // insert linkedin longin password
        $this->click('name=authorize'); // submit form to get authorization login
        $this->waitForPageToLoad("30000");
        $this->assertEquals("You have successfully signup", $this->getText("css=.message"));
    }
    public function testLogout() {
        $this->click("link=Logout");
        $this->deleteAllVisibleCookies();
    }

}
