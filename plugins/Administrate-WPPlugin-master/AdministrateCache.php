<?php
//  Administrate Cache
class AdministrateCache extends WPPluginPatternDAO {
	
	//  Properties
	protected $tableKey = 'caches';
	protected $primaryKey = 'cache_id';
	
	//  Create a new cache
	public function create($method, $args, $result) {
		return parent::create(array(
			'cache_method'	=>	$method,
			'cache_args'	=>	$args,
			'cache_result'	=>	$result,
			'cache_time'	=>	time()
		));
	}
	
	//  Get the log time
	public function get_time() {
		return $this->row['log_time'];	
	}
	
	//  Get the log message
	public function get_message() {
		return $this->row['log_msg'];	
	}
	
}
