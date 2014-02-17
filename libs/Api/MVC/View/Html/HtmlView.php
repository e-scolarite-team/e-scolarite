<?php

namespace Api\MVC\View\Html;

use Api\MVC\View\AbstractView;
use Api\Reader\ConfigReader;
use MVC\Views as views;
use Api\Ui\Menu\MenuSystem;
use Api\Http\Session\HttpSession;

/**
*
* @author Fayssal Tahtoub <fayssal.tahtoub@gmai.com> 
* @version 0.1
*/
class HtmlView extends AbstractView {

	/**
	*
	* @var string
	*/
	private $_layout = "";
	private $_view = "";



	public function __construct($datas = array(),$template="_template/default"){
		$this->_datas = $datas;
		$this->_layout = $template;
		$this->_session = new HttpSession();
	}

	public function setLayout($template){
		$this->_layout = $template;
	}

	public function getLayout(){
		return $this->_layout;
	}

	public function setView($view){
		$this->_view = $view;
	}

	public function getView(){
		return $this->_view;
	}
	

	public function render(){
		
		$tmpfile = ConfigReader::config('_template_path').ConfigReader::config($this->getLayout());
		
		if(!file_exists($tmpfile)){
			throw new \Exception("the template ".$tmpfile."file doesn't exist");
		}

		
		include($tmpfile);
		
	}

	/**
	* call include function
	*
	*/
	public function __call($name,$args){
		switch ($name) {
			case 'includeJs':
				$this->includeJs($args[0]);
				break;

			case 'includeCss':
				$this->includeCss($args[0]);
				break;

			case 'includeImg':
				$this->includeImg($args[0]);
				break;

			case 'includeAsset':
				$this->includeAsset($args[0]);
				break;

			case 'includeTemplate':
				$this->includeTemplate($args[0]);
				break;

			case 'content':
				$this->content();
				break;

			case 'breadcrumb':
				$this->breadcrumb();
				break;

			case 'menu':
				$this->menu();
				break;

			case 'secondaryMenu':
				$this->secondaryMenu();
				break;

			case 'link':
				$this->link($args[0],$args[1]);
				break;

			case 'affineText':
				$this->affineText($args[0]);
				break;
			
			default:
				throw new \Exception("not found helper ".$name);
				break;
		}
	}

	private function includeTemplate($item){

		$tmpfile = ConfigReader::config('_template_path').ConfigReader::config($item);
		
		if(!file_exists($tmpfile)){
			throw new \Exception("the template ".$tmpfile."file doesn't exist");
		}

		return include($tmpfile);
	}

	private function content(){
		
		$tmpfile = ConfigReader::config('_template_path').ConfigReader::config($this->getView());
		
		if(!file_exists($tmpfile)){
			throw new \Exception("the template ".$tmpfile."file doesn't exist");
		}

		return include($tmpfile);
	}

	private function includeJs($item){
		if(!file_exists($item)){
			throw new \Exception("the javascript ".$item." file doesn't exist!!");
		}
		return __SITE_DNS__.$item;
	}

	private function includeCss($item){
		if(!file_exists($item)){
			throw new \Exception("the CSS ".$item." file doesn't exist!!");
		}
		return __SITE_DNS__.$item;
	}

	private function includeImg($item){
		if(!file_exists($item)){
			throw new \Exception("the image ".$item." file doesn't exist!!");
		}
		return __SITE_DNS__.$item;
	}

	private function includeAsset($item){
		$item = ConfigReader::config('upload_dir_name').'/'.$item;
		if(!file_exists($item)){
			throw new \Exception("the image ".$item." file doesn't exist!!");
		}
		return __SITE_DNS__.$item;
	}

	public function link($route,$params = array()){
		
		$result = ConfigReader::config('_routes/'.$route,true);
		$ads = '';
		if($result === false){
			throw new \Exception("the route ".$route." doesn't exists!!");
		}
		
		if(count($params) != count($result['_params'])){
			throw new \Exception("invalide number of args on route ".$route);
		}

		if(count($params)>0)
			$ads = '/'.implode('/', $params);


		return __SITE_DNS__.$route.$ads;
	}

	private function breadcrumb(){
		$breadcrumb = "";
		$format = '<li class="%s"><a href="%s">%s</a></li>';

		if(isset($this->breadcrumb)){
			foreach ($this->breadcrumb as $text => $data) {
				$current = ((in_array('is_current',$data) && ($data['is_current'] == true) ))?'current':'';
				$breadcrumb .= sprintf($format,$current,$data['route'],$text);
			}
		}

		return $breadcrumb;
	}

	private function menu(){
		$datas = ConfigReader::config('_menu_system');
		$format = '<li><a href="%s" title=""><img src="%s" alt="" /><span>%s</span></a></li>';
		return MenuSystem::createRender($format,$datas);
	}

	private function secondaryMenu(){
		$datas = ConfigReader::config('_menu_system/'.$this->controller.'/_menuitems');
		$format = '<li><a href="%s" title="" class=""><span class="%s"></span>%s</a></li>';
		return MenuSystem::createRender($format,$datas,true);
	}

	private function affineText($text){
		if(strlen($text)>150){
			return substr($text,0,150).'...';
		}
		return $text;
	}
}