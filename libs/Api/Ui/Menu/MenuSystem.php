<?php
namespace Api\Ui\Menu;

/**
* @author fayssal tahtoub
* @version 0.1
*/
class MenuSystem
{
	public static $_instance = null;

	public function __construct()
	{
		
	}

	/**
	* render the menuitem 
	* @param string $format the menuitem format
	* @param array $datas the menuitems datas
	* @param string $prefixes the prefixes tags
	* @param string $suffixes the suffixes tags
	* @access public
	*
	* @return string
	*/
	public function render($format,$datas,$is_class = false,$prefixes = "",$suffixes = ""){
		$sitepath = ($is_class)?'':__SITE_DNS__;
		$menuitems = $prefixes.PHP_EOL;
		foreach ($datas as $key => $data) {
			$menuitems .= sprintf($format,$data['_route'],$sitepath.$data['_icon'],$data['_text']).PHP_EOL;
		}
		$menuitems .= $suffixes.PHP_EOL;
		return $menuitems;
	}

	/**
	* Initializing the MenuSys tem with singleton
	* 
	* @access public
	*/
	public static function init(){

		if(self::$_instance == null)
			self::$_instance = new MenuSystem();

		return self::$_instance;
	}

	/**
	* render with singleton 
	* @param string $format the menuitem format
	* @param array $datas the menuitems datas
	* @access public
	*
	* @return mixed
	*/
	public static function createRender($format,$datas,$is_class = false,$prefixes = "",$suffixes = ""){
		$instance = self::init();
		return $instance->render($format,$datas,$is_class,$prefixes,$suffixes);
	}

}