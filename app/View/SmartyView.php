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

class SmartyView extends View {

	function __construct (&$controller) {
	
		parent::__construct($controller);
				
		if(!App::import('Vendor', 'Smarty', array('file' => 'autoload.php'))) {
			die('error Loading Smarty Class');
		}
		
		$this->Smarty = new Smarty();
		
		//UNDONE: set if you need
		//$this->subDir = 'smarty'.DS;
		//$this->Smarty->left_delimiter = '{';
		//$this->Smarty->right_delimiter = '}';
		
		// The extension for template file
		$this->ext= '.tpl';
		$this->Smarty->template_dir = APP.'View'.DS;
		//plugins dir(s) must be set by array
		$this->Smarty->plugins_dir = array(
			APP.'View'.DS.'SmartyPlugins',
			VENDORS.'smarty'.DS.'smarty'. DS. 'distribution'.DS.'libs'.DS.'plugins'
		);
		$this->Smarty->config_dir = APP.'View'.DS.'SmartyConfigs'.DS;
		$this->Smarty->compile_dir = TMP.'smarty'.DS.'compile'.DS;
		$this->Smarty->cache_dir = TMP.'smarty'.DS.'cache'.DS;
		
		
		$this->Smarty->error_reporting = 'E_ALL & ~E_NOTICE';
		$this->Smarty->default_modifiers = array('escape:"html"');
		
		//UNDONE: if you need , modify this array to call filter(s).
		/*
		$this->Smarty->autoload_filters = array(
			'pre' => array('hoge'),
			'output' => array('escape')
		);
		*/
		
		//for development
		$this->Smarty->debugging = (Configure::read('debug') == 0) ? false : true;
		$this->Smarty->compile_check = true;

		$this->viewVars['params'] = $this->params;
	}

	
	protected function _render($___viewFn, $___dataForView = array()) {	
		if (empty($___dataForView)) {
			$___dataForView = $this->viewVars;
		}
		
		extract($___dataForView, EXTR_SKIP);
		
		foreach($___dataForView as $data => $value) {
			if(!is_object($data)) {
				$this->Smarty->assign($data, $value);
			}
		}
		
		ob_start();
		$this->Smarty->display($___viewFn);
		$html = ob_get_clean();		
		return $html;
	}
	
	//Load helpers to use in tpl, like {$Html->link("Go Google", 'http://google.co.jp')}
	public function loadHelpers() { 
		$helpers = HelperCollection::normalizeObjectArray($this->helpers); 
		foreach ($helpers as $name => $properties) { 
			list($plugin, $class) = pluginSplit($properties['class']);
			$this->{$class} = $this->Helpers->load($properties['class'], $properties['settings']);
			$this->Smarty->assign($name, $this->{$class});
		}
		$this->_helpersLoaded = true;
	}
}
//EOF