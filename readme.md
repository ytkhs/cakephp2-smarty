# SmartyViewClass for CAKEPHP 2.x with Smarty 3.x
 
## How to use

install Smarty3.x to /vendors, via Composer

	$ curl -s "http://getcomposer.org/installer" | php
	$ php composer.phar install 
	
locate SmartyView.php to app/View/SmartyView.php

set ViewClass at Controller

		 class AppController extends Controller {
		 	public $viewClass = 'Smarty';
		 }

setup cache & complle directory

	$ mkdir -p app/tmp/smarty/cache/
	$ mkdir -p app/tmp/smarty/compile/
	$ chmod 0777 app/tmp/smarty/cache/
	$ chmod 0777 app/tmp/smarty/compile/
	
if you need, make your plugin and config directory
	
	$ mkdir -p app/Lib/SmartyPlugins/
	$ mkdir -p app/Lib/SmartyConfigs/
	
