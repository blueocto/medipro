<?php
//  WPPluginPattern Data Access Object
class WPPluginPatternDAO {
	
	//  Properties
	protected $plugin;
	protected $tableKey = '';
	protected $primaryKey = 'my_id';
	protected $fieldDefinitions = array();
	protected $row = false;
	
	//  Constructor
	public function __construct(&$plugin, $fields = array()) {
		
		//  Save the plugin by reference
		$this->plugin = $plugin;
		
		//  Set the field definitions
		$this->fieldDefinitions = $this->plugin->get_table_fields($this->tableKey);
		
		//  Save the actual table name using the key
		$this->table = $this->plugin->get_table($this->tableKey);
		
		//  If the passed ID is greater than zero, add it to the fields array
		if (!is_array($fields)) {
			$id = $fields;
			$fields = array();
			$fields[$this->_get_primary_key()] = $id;	
		}
		
		//  Try to get the database record based on fields to match against
		if (!empty($fields)) {
			$qry = 'SELECT * FROM ' . $this->_get_table() . ' WHERE ';
			foreach ($fields as $field=>$value) {
				$qry .= $field . " = '" . $value . "' AND ";	
			}
			$qry = substr($qry, 0, -5);
			//echo $qry . '<br>';
			$this->row = $this->plugin->db->get_row($qry, 'ARRAY_A');
			if (!is_array($this->row)) {
				$this->row = false;
			}
		}
		
	}
	
	//  Save a record
	public function save($fields = array()) {
		
		//  Only proceed if there were actually fields passed
		if (!empty($fields)) {
		
			//  Check the fields for errors
			$errors = $this->_validate_fields($fields);
			
			//  If there were no errors, proceed
			if (!$errors) {
				
				//  Sanitize the fields
				$fields = $this->_sanitize_fields($fields);
				
				//  Only proceed if there are still any fields to save after sanitation
				if (!empty($fields)) {
				
					//  If the row already exists, this is an update
					if ($this->row) {
						
						//  Update the row
						$where = array();
						$where[$this->_get_primary_key()] = $this->row[$this->_get_primary_key()];
						$this->plugin->db->update(
							$this->_get_table(),
							$fields,
							$where
						);
						
						//  Reload saved data
						$this->_reload_row();
						
					//  Otherwise this is an insert	
					} else {
					
						//  Insert the row
						$this->plugin->db->insert(
							$this->_get_table(),
							$fields
						);
						
						//  Reinitialize object
						$this->__construct($this->plugin, $this->plugin->db->insert_id);
							
					}
					
					return false;
				
				}
			
			//  Or else return the errors	
			} else {
				return $errors;
			}
		
		}
		
	}
	
	//  Create a new record
	public function create($fields = array()) {
		return $this->save($fields);
	}
	
	//  Update an record
	public function update($fields = array()) {
		return $this->save($fields);
	}
	
	//  Delete a record
	public function delete() {
		return $this->plugin->db->query("DELETE FROM " . $this->_get_table() . " WHERE " . $this->_get_primary_key() . " = " . $this->get_id());
	}
	
	//  Reload the row
	protected function _reload_row() {
		$this->row = $this->plugin->db->get_row('SELECT * FROM ' . $this->_get_table() . ' WHERE ' . $this->_get_primary_key() . ' = ' . $this->get_id(), 'ARRAY_A');	
	}
	
	//  Validate the fields -- By default does nothing, returns no errors
	protected function _validate_fields($fields = array()) { 
		return false;
	}
	
	//  Sanitize and pack up fields
	protected function _sanitize_fields($inputFields = array()) {
		
		//  Loop through field definitions
		$outputFields = array();
		foreach ($this->fieldDefinitions as $field=>$properties) {
			if (isset($inputFields[$field])) {
				
				//  If this is a serialized field ...
				if (isset($properties['serialize'])) {
					
					//  Loop through serialized fields
					$outputFields[$field] = array();
					foreach ($properties['serialize'] as $subfield) {
						$subfieldKey = $field . $this->plugin->get_key_delimiter() . $subfield;
						if (is_array($inputFields[$subfieldKey])) {
							$outputFields[$field][$subfield] = array();
							foreach ($inputFields[$subfieldKey] as $multiField) {
								array_push($outputFields[$field][$subfield], sanitize_text_field($multiField));
							}
						} else {
							$outputFields[$field][$subfield] = sanitize_text_field($inputFields[$subfieldKey]);
						}
					}
					$outputFields[$field] = serialize($outputFields[$field]);
					
				//  Or else just sanitize the field
				} else {
					$outputFields[$field] = sanitize_text_field($inputFields[$field]);
				}
				
			}
		}
		
		//  Return output fields
		return $outputFields;
		
	}
	
	//  Get the id
	public function get_id() {
		if ($this->row) {
			return $this->row[$this->_get_primary_key()];	
		} else {
			return 0;	
		}
	}
	
	//  Get table
	protected function _get_table() {
		return $this->table;	
	}
	
	//  Get the primary key
	protected function _get_primary_key() {
		return $this->primaryKey;	
	}
	
	//  Whether or not the passed ID exists
	public function exists() {
		return is_array($this->row);	
	}
	
	//  Get field
	public function get_field($key) {
		return $this->row[$key];	
	}

	/**
	 * @param bool $where - false or an array of 'where' clause, e.g. 'order_id' => 4, 'event_id' => 5
	 * @param bool $order - array for 'order' clause, e.g. 'order_id' => 'ASC'
	 * @param int $start - start results at N'th value - default 0
	 * @param int $limit - show only X results - defaults to MAX_INT (all results)
	 * @return array - array of objects, type dependant on subclass which made the call
	 */
	public function get_all($where = false, $order = false, $start = 0, $limit = PHP_INT_MAX) {

		$params = array();

		$where_string = "";
		$order_by_string = "";
		$limit_string = "";

		// If we have a where clause, build the parameterized
		// string, and ensure we populate the $params array.
		if($where) {
			$where_string = " WHERE ";
			$where_items = array();
			foreach($where as $field => $value) {
				$where_items[] = $field . ' = %s';
				$params[] = $value;
			}
			$where_string .= implode(' AND ', $where_items);
		}

		// Likewise for order - build this in a parameterized
		// fashion, so that it is safe.
		if($order) {
			$order_by_string = " ORDER BY ";
			$order_items = array();
			foreach($order as $field => $value) {

				// $field must be a valid column name
				if(!in_array($field, $this->plugin->db->get_col('DESC ' . $this->_get_table(), 0))) {
					continue;
				}

				// $value must be ASC or DESC
				if(!in_array($value, array('ASC', 'DESC'))) {
					continue;
				}

				// Can't parameterize these, because WP 'prepare'
				// will wrap them in quotes, which doesn't work.
				// However, we already checked they were valid.
				$order_items[] = $field . ' ' . $value;
			}
			$order_by_string .= implode(',', $order_items);
		}

		if($start > 0) {

			// Build 2-argument limit field, LIMIT <start>, <length>
			$limit_string = " LIMIT %d, %d";
			$params[] = $start;
			$params[] = $limit;
		} elseif($limit < PHP_INT_MAX) {

			// Build single-arguement limit: LIMIT <length>
			$limit_string = " LIMIT %d";
			$params[] = $limit;
		}

		// Build the query
		$qry = "SELECT * FROM " . $this->_get_table() . $where_string . $order_by_string . $limit_string;

		// Prepare it, this takes the SQL we've generated and the params we want.
		$stmt = $this->plugin->db->prepare($qry, $params);

		// Run it. Specify that we want the results as an associative array
		$rows = $this->plugin->db->get_results($stmt, 'ARRAY_A');

		// Which subclass requested this?
		$class = get_class($this);

		// For each row, build a class of this object type and return the array.
		$daos = array();
		foreach ($rows as $row) {
			array_push($daos, new $class($this->plugin, $row[$this->_get_primary_key()]));
		}
		return $daos;
	}
	
}
