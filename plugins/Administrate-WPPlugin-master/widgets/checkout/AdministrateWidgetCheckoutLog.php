<?php
//  Administrate checkout widget log
class AdministrateWidgetCheckoutLog extends WPPluginPatternDAO {
	
	//  Properties
	protected $tableKey = 'logs';
	protected $primaryKey = 'log_id';
	
	//  Create a new order
	public function create($msg, $order_id) {
		return parent::create(array(
			'log_msg'		=>	$msg,
			'log_order_id'	=>	$order_id,
			'log_time'		=>	time()
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