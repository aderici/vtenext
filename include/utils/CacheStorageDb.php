<?php

// move the class here so the autoupdater can find it easily

// trick to have a way to know we are in the update process
global $is_vte_updating;
$is_vte_updating = true;

// crmv@140903
/**
 * Database Cache storage
 * Store everything inside a db table
 */
class CacheStorageDb extends CacheStorage {
	
	private $db;
	private $table;
	private $table_col_key;
	private $table_col_value;
	
	public function __construct() {
		global $adb, $table_prefix;;
		$this->db = $adb;
		$this->table = $table_prefix.'_cache';
		$this->table_col_key = 'cache_key';
		$this->table_col_value = 'cache_value';
		
		$this->checkTable();
	}
	
	protected function checkTable() {
		if (!Vtiger_Utils::CheckTable($this->table)) {
			$schema_table =
			'<schema version="0.3">
				<table name="'.$this->table.'">
					<opt platform="mysql">ENGINE=InnoDB</opt>
					<field name="cache_key" type="C" size="50">
						<KEY/>
					</field>
					<field name="cache_value" type="XL"/>
				</table>
			</schema>';
			$schema_obj = new adoSchema($this->db->database);
			$schema_obj->ExecuteSchema($schema_obj->ParseSchemaString($schema_table));
		}
	}
	
	public function has($key) {
		$res = $this->db->pquery("SELECT COUNT(*) AS cnt FROM {$this->table} WHERE {$this->table_col_key} = ?", array($key));
		return ($res && $this->db->query_result_no_html($res,0,'cnt') > 0);
	}
	
	public function get($key) {
		$res = $this->db->pquery("SELECT {$this->table_col_value} AS val FROM {$this->table} WHERE {$this->table_col_key} = ?", array($key));
		if ($res && $this->db->num_rows($res) > 0) {
			$value = $this->db->query_result_no_html($res,0,'val');
			return $this->valueFromDb($value);
		}
		return null;
	}
	
	public function set($key, $value) {
		if ($this->readonly) return;
		$value = $this->valueToDb($value);
		// efficient way for an upsert
		// unfortunarely, it doesn't work during install... why?
		/* $res = $this->db->pquery("UPDATE {$this->table} SET {$this->table_col_value} = ? WHERE {$this->table_col_key} = ?", array($value, $key));
		if ($this->db->getAffectedRowCount($res) == 0) {
			$this->db->pquery("INSERT INTO {$this->table} ({$this->table_col_key},{$this->table_col_value}) VALUES (?,?)", array($key, $value));
		}
		*/
		// so let's use a standard approach
		if ($this->has($key)) {
			$this->db->pquery("UPDATE {$this->table} SET {$this->table_col_value} = ? WHERE {$this->table_col_key} = ?", array($value, $key));
		} else {
			$this->db->pquery("INSERT INTO {$this->table} ({$this->table_col_key},{$this->table_col_value}) VALUES (?,?)", array($key, $value));
		}
	}
	
	public function getAll() {
		$data = array();
		$res = $this->db->query("SELECT {$this->table_col_key} AS ckey, {$this->table_col_value} AS val FROM {$this->table}");
		if ($res && $this->db->num_rows($res) > 0) {
			while ($row=$this->db->fetchByAssoc($res,-1,false)) {
				$data[$row['ckey']] = $this->valueFromDb($row['val']);
			}
		}
		return $data;
	}
	
	public function getAllLike($like) {
		$data = array();
		$res = $this->db->query("SELECT {$this->table_col_key} AS ckey, {$this->table_col_value} AS val FROM {$this->table} WHERE {$this->table_col_key} LIKE '{$like}'");
		if ($res && $this->db->num_rows($res) > 0) {
			while ($row=$this->db->fetchByAssoc($res,-1,false)) {
				$data[$row['ckey']] = $this->valueFromDb($row['val']);
			}
		}
		return $data;
	}
	
	public function setMulti($values) {
		if ($this->readonly) return;
		
		$this->db->startTransaction();
		foreach($values as $key => $value) {
			$this->set($key, $value);
		}
		$this->db->completeTransaction();
	}
	
	public function clear($key) {
		if ($this->readonly) return;
		$this->db->pquery("DELETE FROM {$this->table} WHERE {$this->table_col_key} = ?", array($key));
	}
	
	public function clearAll() {
		if ($this->readonly) return;
		$this->db->query("DELETE FROM {$this->table}");
	}
	
	public function clearMatching($regexp) {
		if ($this->readonly) return;
		$keys = array();
		$res = $this->db->query("SELECT {$this->table_col_key} AS ckey FROM {$this->table}");
		if ($res && $this->db->num_rows($res) > 0) {
			while ($row=$this->db->fetchByAssoc($res,-1,false)) {
				$keys[] = $row['ckey'];
			}
		}
		foreach ($keys as $k) {
			if (preg_match($regexp, $k)) {
				$this->clear($k);
			}
		}
	}
	
	protected function valueToDb($value) {
		return json_encode($value);
	}
	
	protected function valueFromDb($value) {
		return  json_decode($value, true);
	}
}
// crmv@140903e
