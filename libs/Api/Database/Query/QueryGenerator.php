<?php
namespace Api\Database\Query;

use Api\Utils\ArrayUtils;

/**
* @author Fayssal tahtoub
* @version 0.1
*/
class QueryGenerator
{
	
	function __construct()
	{
		
	}

	public function generateSelect($table_name,$conditions){
		$query = "Select * From ".$table_name;
		if(count($conditions) > 0){
			$query .= " Where ";
			$i = 0;
			foreach ($conditions as $key => $value) {
				if($i != 0)
					$query .= " And ";

				$query .= $key." = :".$key;  
				$i++;
			}
		}

		return $query;
	}


	public function generateUpdate($entityMetadata){

		$escaped = $this->prepareColumns($entityMetadata['_columns'],'update','_is_primary');
		
		return sprintf("UPDATE %s SET %s WHERE %s",$entityMetadata['_table_name'],implode(',', $escaped[0]),implode(',', $escaped[1]));
		
	}

	public function generateRemove($entityMetadata){
		
		$escaped = $this->prepareColumns($entityMetadata['_columns'],'delete','_is_primary');
		
		return sprintf("DELETE  FROM %s WHERE %s",$entityMetadata['_table_name'],implode(',', $escaped[1]));
		
	}

	public function generateInsert($entityMetadata){

		$escaped = $this->prepareColumns($entityMetadata['_columns'],'insert');
		
		return sprintf("INSERT INTO  %s(%s)  VALUES(%s)",$entityMetadata['_table_name'],implode(',', $escaped[0]),implode(',', $escaped[1]));
		
	}

	private function prepareColumns($columns,$action,$remove = '_is_auto'){
		$escapedColumns = array();
		$escapedParams = array();
		foreach ($columns as $key => $column) {

			if(!in_array($remove,$column) || !$column[$remove]){
				if($action == 'insert'){
					$escapedColumns[] = $column['_column_name'];
					$escapedParams[] = ":".$key;
				}elseif ($action == 'update') {
					$escapedColumns[] = $column['_column_name']." = :".$key;
				}
				
			}elseif ($action == 'update' || $action == 'delete') {
				$escapedParams[] = $column['_column_name']." = :".$key;
			}
			
		}
		return array($escapedColumns,$escapedParams);
	}



}