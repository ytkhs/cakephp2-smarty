# SmartyViewClass for CAKEPHP 2.1 with Smarty 3.x
 
## How to use
1. install Smarty3.x to vendors/, not app/Vendors
2. locate SmartyView.php to app/View/SmartyView.php
3. set ViewClass at Controller

	(example) app/Controller/AppController.php
	
		 class AppController extends Controller {
		 	public $viewClass = 'Smarty';
		 }

4. setup cache & complle directory
	- $ mkdir -p app/tmp/smarty/cache/
	- $ mkdir -p app/tmp/smarty/compile/
	- $ chmod 0775 app/tmp/smarty/cache/
	- $ chmod 0755 app/tmp/smarty/compile/
5. if you need, make your plugin and config directory
	- $ mkdir -p app/View/SmartyPlugins/
	- $ mkdir -p app/View/SmartyConfigs/