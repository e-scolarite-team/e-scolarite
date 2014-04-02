<?php

namespace App\Bundle\BackOfficeBundle\DependencyInjection\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 
*/
class ConfigManagerService extends ContainerAware 
{
	
	public function __construct()
	{
		
	}

	/**
     * Set container
     *
     * @param ContainerInterface $container
     * @return Etudiant
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }

    public function getCurrentAcademicYear(){
    	return $this->container->getParameter("app_back_office.current_academic_year");
    }

    public function getCurrentSemester(){
    	return $this->container->getParameter("app_back_office.current_semester");
    }

    public function getDateFormat(){
    	return $this->container->getParameter("app_back_office.date_format");
    }

    public function getDateTimeFormat(){
    	return $this->container->getParameter("app_back_office.datetime_format");
    }

    public function getServiceStatus(){
    	return $this->container->getParameter("app_back_office.activate_service");
    }

    public function getAutoAnswersStatus(){
    	return $this->container->getParameter("app_back_office.auto_demands_answers.status");
    }

    public function getAutoAnswersAmount(){
    	return $this->container->getParameter("app_back_office.auto_demands_answers.amount");
    }
}