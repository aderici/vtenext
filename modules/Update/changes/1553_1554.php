<?php
global $adb, $table_prefix;

// fix conditionals old rules
$adb->query(
	"DELETE FROM tbl_s_conditionals_rules 
    WHERE ruleid NOT IN (
		SELECT DISTINCT ruleid FROM tbl_s_conditionals
	)"
);
