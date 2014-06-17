<?php
/**
 * Methods for displaying presentation data in the view.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
 /**
 * require Smarty 3.x
 *
 * @link http://www.smarty.net/
 * @copyright 2008 New Digital Group, Inc.
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author Uwe Tews
 * @author Rodney Rehm
 * @package Smarty
 */

class SmartySingleton {
	static private $instance = null;
	
	private function __construct() {}
	private function __clone() {}
	private function __wakeup() {}
	
	static public function instance() {
		if (is_null(self::$instance)) {
		
			App::import('Vendor', 'Smarty', array('file' => 'autoload.php'));
			$smarty = new Smarty();
			$smarty->template_dir = APP.'View'.DS;
			
			//plugins dir(s) must be set by array
			$smarty->plugins_dir = array(
				APP.'View'.DS.'SmartyPlugins',
				VENDORS.'smarty'.DS.'smarty'. DS. 'distribution'.DS.'libs'.DS.'plugins'
			);
			$smarty->config_dir = APP.'View'.DS.'SmartyConfigs'.DS;
			$smarty->compile_dir = TMP.'smarty'.DS.'compile'.DS;
			$smarty->cache_dir = TMP.'smarty'.DS.'cache'.DS;
			
			$smarty->error_reporting = 'E_ALL & ~E_NOTICE';
			$smarty->default_modifiers = array('escape:"html"');
			$smarty->caching = true;
			$smarty->compile_check = true;
			$smarty->cache_lifetime = 3600;
			$smarty->cache_modified_check = false;
			$smarty->force_compile = (Configure::read('debug') > 0) ? true :false;
			
			/*
			$smarty->autoload_filters = array(
				'pre' => array('hoge'),
				'output' => array('escape')
			);
			$smarty->left_delimiter = '{';
			$smarty->right_delimiter = '}';

			//for development
			$smarty->debugging = (Configure::read('debug') == 0) ? false : true;
			*/
			
			self::$instance = $smarty;
		}
		return self::$instance;
	}

}

class SmartyView extends View {
	
	function __construct (&$controller) {
		
		$this->Smarty = SmartySingleton::instance();
		
		//$this->subDir = 'smarty'.DS;
		$this->ext= '.tpl';
		$this->viewVars['params'] = $this->params;
		parent::__construct($controller);
	}

	protected function _render($___viewFn, $___dataForView = array()) {	
	
		$isCtpFile = (substr($___viewFn, -3) === 'ctp');
		
		if (empty($___dataForView)) {
			$___dataForView = $this->viewVars;
		}
		
		if ($isCtpFile) {
			$out = parent::_render($___viewFn, $___dataForView);
		}
		else {
		
			extract($___dataForView, EXTR_SKIP);
			
			foreach($___dataForView as $data => $value) {
				if(!is_object($data)) {
					$this->Smarty->assign($data, $value);
				}
			}
			$this->Smarty->assign('_view', $this);
			
			$out = $this->Smarty->fetch($___viewFn);
		}
		return $out;
	}
	
	//Load helpers to use in tpl, like {$html->link("Go Google", 'http://google.co.jp')}
	public function loadHelpers() { 
		$helpers = HelperCollection::normalizeObjectArray($this->helpers); 
		foreach ($helpers as $name => $properties) { 
			list($plugin, $class) = pluginSplit($properties['class']);
			$this->{$class} = $this->Helpers->load($properties['class'], $properties['settings']);
			$this->Smarty->assign(strtolower($name), $this->{$class});
		}
	}
}
//EOF
