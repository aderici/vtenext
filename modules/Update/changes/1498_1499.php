<?php

if (!function_exists('getPrimaryKeyName')) {
	function getPrimaryKeyName($tablename) {
		global $adb, $dbconfig;
		$ret = '';
		if ($adb->isMysql()) {
			// for mysql just check if it exists
			$res = $adb->query("SHOW KEYS FROM {$tablename} WHERE Key_name = 'PRIMARY'");
			if ($res && $adb->num_rows($res) > 0) $ret = 'PRIMARY';
		} elseif ($adb->isMssql()) {
			$res = $adb->pquery("SELECT CONSTRAINT_NAME as cn from INFORMATION_SCHEMA.TABLE_CONSTRAINTS where CONSTRAINT_CATALOG = ? and TABLE_NAME = ? and CONSTRAINT_TYPE = 'PRIMARY KEY'", array($dbconfig['db_name'], $tablename));
			if ($res) $ret = $adb->query_result_no_html($res, 0, 'cn');
		} elseif ($adb->isOracle()) {
			$res = $adb->pquery("SELECT CONSTRAINT_NAME as cn FROM all_constraints cons	WHERE cons.table_name = ? AND cons.constraint_type = 'P'", array(strtoupper($tablename)));
			if ($res) $ret = $adb->query_result_no_html($res, 0, 'cn');
		}
		return $ret;
	}
}


// crmv@121707
// remove duplicates from table

if ($adb->isMssql()) {
	$res = $adb->query(
		"SELECT t1.productid, t1.currencyid, count(*) AS cnt
		FROM {$table_prefix}_productcurrencyrel t1 
		INNER JOIN {$table_prefix}_productcurrencyrel t2 ON (t1.productid = t2.productid AND t1.currencyid = t2.currencyid)
		GROUP BY t1.productid, t1.currencyid
		HAVING count(*) > 1"
	);
} else {
	$res = $adb->query(
		"SELECT t1.productid, t1.currencyid, count(*) AS cnt
		FROM {$table_prefix}_productcurrencyrel t1 
		INNER JOIN {$table_prefix}_productcurrencyrel t2 ON (t1.productid = t2.productid AND t1.currencyid = t2.currencyid)
		GROUP BY t1.productid, t1.currencyid
		HAVING cnt > 1"
	);
}
if ($res && $adb->num_rows($res) > 0) {
	while ($row = $adb->fetchByAssoc($res, -1, false)) {
		// get the price values
		$res2 = $adb->limitPquery("SELECT * FROM {$table_prefix}_productcurrencyrel WHERE productid = ? AND currencyid = ?", 0, 1, array($row['productid'], $row['currencyid']));
		$row2 = $adb->fetchByAssoc($res2, -1, false);
		// delete the duplicates!
		$adb->pquery("DELETE FROM {$table_prefix}_productcurrencyrel WHERE productid = ? AND currencyid = ?", array($row['productid'], $row['currencyid']));
		// insert the single ROW
		$cols = array_keys($row2);
		$adb->format_columns($cols);
		$adb->pquery("INSERT INTO {$table_prefix}_productcurrencyrel (".implode(', ', $cols).") VALUES (".generateQuestionMarks($row2).")", $row2);
	}
}

// remove the existing index
$sql = (Array)$adb->datadict->DropIndexSQL('FK_vte_productcurrencyrel', "{$table_prefix}_productcurrencyrel");
$adb->datadict->ExecuteSQLArray($sql);

// and add the primary key
$prikey = getPrimaryKeyName("{$table_prefix}_productcurrencyrel");
if ($prikey == '') {
	$adb->query("ALTER TABLE {$table_prefix}_productcurrencyrel ADD PRIMARY KEY (productid, currencyid)");
}


//crmv@112756

SDK::setLanguageEntries('Messages', 'LBL_DOWNLOAD_TNEF', array('it_it'=>'Scarica allegati contenuti','en_us'=>'Download attachments contained'));
