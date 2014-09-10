#!/bin/bash
cd protected/tests/
phpunit functional/SiteTest.php
# phpunit ./functional/
# phpunit --verbose unit

 java -jar ~/notepad/selenium-server-standalone-2.39.0.jar 
 cd /k/GitHub/Yii/demos/blog/protected/tests
 phpunit functional/CommentTest.php