<?php
namespace Api\Kernel;

use Api\Http\RouteProcessor;
use Api\Http\Request;
use MVC\Views;


/**
* @author Fayssal tahtoub <fayssal.tahtoub@gmail.com>
* @version 0.1
*/
class SysKernel
{
	/**
	*
	* @var RouteProcessor
	*/
	private $_route_processor = null;

	/**
	*
	* @var Request
	*/
	private $_request = null;

	function __construct()
	{
		$this->_request = Request::create();
		$this->_route_processor = new RouteProcessor($this->_request);
	}

	public function prepare(){
		return $this->getRouteProcessor()->route();
	}

	public function render($response){
		if(is_a($response, "Api\MVC\View\AbstractView"))
			$response->render();
		else if(is_array($response)){
			header('Content-type: application/json');
			echo json_encode($response);
		}else if(is_string($response)){
			header('Location: '.$response);
			exit;
			//echo "<script>window.location = '".$response."'</script>";
		}
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
	* Set _route_processor
	*
	* @param $routeProcessor
	* @access public
	*/
	public function setRouteProcessor(RouteProcessor $routeProcessor){
		$this->_route_processor = $routeProcessor;
	}


	/**
	* Get _route_processor
	*
	* @return RouteProcessor
	* @access public
	*/
	public function getRouteProcessor(){
		return $this->_route_processor;
	}

}