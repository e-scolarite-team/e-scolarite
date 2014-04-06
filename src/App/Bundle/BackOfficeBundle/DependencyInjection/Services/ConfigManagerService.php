<?php

namespace App\Bundle\BackOfficeBundle\DependencyInjection\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use App\Bundle\BackOfficeBundle\DependencyInjection\EscolariteConfiguration;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Config\Definition\Processor;

/**
* 
*/
class ConfigManagerService extends ContainerAware 
{
    /**
    * @var Dumper
    */
    protected $dumper;

    /**
    * @var Parser
    */
	protected $parser;

    /**
    * @var array
    */
    protected $config = null;

    /**
    * @var Processor
    */
    protected $processor;

	public function __construct()
	{
        $this->processor = new Processor();
        $this->dumper = new Dumper();
		$this->parser = new Parser();
	}

	/**
     * Set container
     *
     * @param ContainerInterface $container
     * 
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }

    public function parseConfiguration(){

        $esConfigFile = $this->getFilePath();

        $configuration = new EscolariteConfiguration();

        try {
            $configs = $this->parser->parse(file_get_contents($esConfigFile));
        } catch (ParseException $e) {
            printf("Unable to parse the escolarite config YAML string: %s", $e->getMessage());
        }
        
        
        
        $this->config = $this->processor->processConfiguration($configuration, $configs);

    }

    /**
     * Get CurrentAcademicYear
     *
     * @return  integer
     * 
     */
    public function getCurrentAcademicYear(){
    	return $this->config['current_academic_year'];
    }

    /**
     * Get CurrentSemester
     *
     * @return  integer
     * 
     */
    public function getCurrentSemester(){
    	return $this->config['current_semester'];
    }

    /**
     * Get DateFormat
     *
     * @return  string
     * 
     */
    public function getDateFormat(){
    	return $this->config['date_format'];
    }

    /**
     * Get DateTimeFormat
     *
     * @return  string
     * 
     */
    public function getDateTimeFormat(){
    	return $this->config['datetime_format'];
    }

    /**
     * Get ServiceStatus
     *
     * @return  string yes|no
     * 
     */
    public function getServiceStatus(){
    	return $this->config['activate_service'];
    }

    /**
     * Get AutoAnswersStatus
     *
     * @return  string activate|deactivate
     * 
     */
    public function getAutoAnswersStatus(){
    	return $this->config['auto_demands_answers']['status'];
    }

    /**
     * Get AutoAnswersAmount
     *
     * @return  integer
     * 
     */
    public function getAutoAnswersAmount(){
    	return $this->config['auto_demands_answers']['amount'];
    }

    /**
     * set CurrentAcademicYear
     *
     * @param  integer
     * 
     */
    public function setCurrentAcademicYear($year){
        $this->config['current_academic_year'] = $year;
    }

    /**
     * set CurrentSemester
     *
     * @param  integer
     * 
     */
    public function setCurrentSemester($semester){
        $this->config['current_semester'] = $semester;
    }


    /**
     * set DateFormat
     *
     * @param  string
     * 
     */
    public function setDateFormat($dateformat){
        $this->config['date_format'] = $dateformat;
    }

    /**
     * set DateTimeFormat
     *
     * @param  string
     * 
     */
    public function setDateTimeFormat($datetimeformat){
        $this->config['datetime_format'] = $datetimeformat;
    }

    /**
     * set ServiceStatus
     *
     * @param  string yes|no
     * 
     */
    public function setServiceStatus($serviceStatus){
        $this->config['activate_service'] = $serviceStatus;
    }

    /**
     * set AutoAnswersStatus
     *
     * @param  string activate|deactivate
     * 
     */
    public function setAutoAnswersStatus($autoAnsState){
        $this->config['auto_demands_answers']['status'] = $autoAnsState;
    }

    /**
     * set AutoAnswersAmount
     *
     * @param  integer
     * 
     */
    public function setAutoAnswersAmount($autoAnsAmount){
        $this->config['auto_demands_answers']['amount'] = $autoAnsAmount;
    }


    public function prepareDataStructure(){
        return array('escolarite' => $this->config);
    }

    public function save(){
        $yaml = $this->dumper->dump($this->prepareDataStructure(),3);
        file_put_contents($this->getFilePath(), $yaml);
    }

    /**
     * get config file path
     *
     * @return  string
     * 
     */
    private function getFilePath(){
        $rootDir =  $this->container->getParameter('kernel.root_dir');
        return $rootDir.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."escolarite_config.yml";
    }
}