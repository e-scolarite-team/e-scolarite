<?php 
namespace Api\Http;

use Api\Reader\ConfigReader;
use Api\Http\Request;
use Api\Database\Persistance\PersistanceManager;
use Api\Http\Session\HttpSession;
use \ReflectionClass;
use \ReflectionMethod;


/**
*
* @author Fayssal Tahtoub <fayssal.tahtoub@gmail.com>
* @version 0.1
*
*/
class RouteProcessor{

	/**
	*
	*
	* @var Request
	*/
	private $_request;


	public function __construct($request = null){
		$this->_request = $request;
	}

	/**
	* Set _request
	*
	* @param $request
	* @access public
	*/
	public function setRequest(Request $request){
		$this->_request = $request;
	}


	/**
	* Get _request
	*
	* @return Request
	* @access public
	*/
	public function getRequest(){
		return $this->_request;
	}

	/**
	* this function redirect the client request to the appropriete Controller:action
	* 
	* @access public
	*/
	public function route(){
		$route = $this->getRoute();
		//var_dump($route);
		if(!class_exists($route['_controller']))
			throw new \Exception("the controller ".$route['_controller']." does'nt exists!!", 1);

		$reflectionClass = new ReflectionClass($route['_controller']);

		$controller = $reflectionClass->newInstance();
		$controller->request = $this->getRequest();
		$session = new HttpSession();
		$session->start();
		$controller->session = $session;
		$controller->setName($route['_controller_name']);
		$controller->persistance_manager = new PersistanceManager();
		//$controller->view = $route['_hook'];

		if(!method_exists($controller, $route['_action'].'Action'))
			throw new \Exception($route['_action']." action does'nt exists in controller ".$route['_controller']." !!", 1);

		$reflectionMethod = $reflectionClass->getMethod($route['_action'].'Action');
		$response = $reflectionMethod->invokeArgs($controller,$route['_params']);
		
		return $response;
	}

	/**
	* match the coming request to the appropriate route
	* @param string $url the url for the comming request 
	* @access public 
	*  
	* @return array array( '_controller' => 'MVC\Controllers\AdminController' ,
	*                      '_controller_name' => 'admin',
	*                      '_action' => 'index',
	*                      '_route' => 'article/list',
	*                      '_params' => array(
	*                           'param1' => 'value1',
	*                           'param2' => 'value2',
	*                        )
	*                    )
	*/
	public function getRoute($url = null){

		if(isset($url))
			$routeUrl = $url;
		
		$routeUrl = $this->getRequest()->getRoute('route');

		$routeItems = explode('/', $routeUrl);

		$routes = ConfigReader::config('_routes');

		$controller = 'Index';
		$action = 'index';
		$route_url = '/';
		$params = array();
		$params_key = array();

		foreach ($routeItems as  $idx => $item) {
			if($idx == 0)
				$route_url = strtolower($item);
			else if($idx == 1)
				$route_url .= '/'.strtolower($item);
			else{
				$params[] = $item;
			}
		}
		
		if(!empty($route_url)){
			$routeConfig = ConfigReader::configFrom($route_url,false,$routes);
			$hook = ConfigReader::configFrom('_hook',false,$routeConfig);
			$params_key = ConfigReader::configFrom('_params',false,$routeConfig);
			
			$controller = explode(':', $hook)[0];
			$action = explode(':', $hook)[1];
		}

		return array('_controller' =>  ConfigReader::config('_controllers/'.$controller),
				'_controller_name' => strtolower($controller),
				'_action' => $action,
				'_route' => $route_url,
				'_hook' => $hook,
				'_params' => array_combine($params_key, $params),
				);
		
	}


}