<?php 
class SiteTest extends WebTestCase
{
    public function testIe6()
    {
        /*
        指示 Selenium RC 打开URL site/ie6，注意这是一个相对 URL，完整的URL应当是我们在基础中设置的基础 URL 再加上相对URL(例如 http://localhost/GitHub/CRM/index-test.php/site/ie6)
        */
        $this->open('site/ie6');
        $this->asserTextPresent('ie6');
        
    }
}