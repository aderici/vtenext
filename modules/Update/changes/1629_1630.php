<?php 

/* crmv@150773 */

// clean up some wrong setypes
$delSetypes = array(' Attachment');
$adb->pquery("DELETE FROM {$table_prefix}_crmentity WHERE setype IN (".generateQuestionMarks($delSetypes).")", $delSetypes);

// remove orphaned attachments
$SBU = StorageBackendUtils::getInstance();
$SBU->deleteOrphanAttachments();

// get old attachments type
$attSetype = array();
$res = $adb->pquery(
	"SELECT setype FROM (
		SELECT DISTINCT setype FROM {$table_prefix}_crmentity
	) tt WHERE setype LIKE '% Attachment' AND setype NOT IN (?,?,?,?)",
	array('Documents Attachment', 'Myfiles Attachment', 'HelpDesk Attachment', 'Users Attachment')
);
while ($row = $adb->FetchByAssoc($res, -1, false)) {
	$attSetype[] = $row['setype'];
}

// now remove the orphan ones
if (count($attSetype) > 0) {
	$res = $adb->pquery(
		"SELECT c.crmid, a.* FROM {$table_prefix}_crmentity c
		LEFT JOIN {$table_prefix}_seattachmentsrel s ON s.attachmentsid = c.crmid
		LEFT JOIN {$table_prefix}_attachments a ON a.attachmentsid = c.crmid
		WHERE c.setype IN (".generateQuestionMarks($attSetype).") AND s.crmid IS NULL",
		$attSetype
	);
	while ($row = $adb->fetchByAssoc($res, -1, false)) {
		$attid = $row['crmid'];
		$path = $row['path'].$row['attachmentsid'].'_'.$row['name'];
		if ($path && file_exists($path)) {
			// delete the file
			@unlink($path);
		}
		// delete from tables
		$adb->pquery("DELETE FROM {$table_prefix}_attachments WHERE attachmentsid = ?", array($attid));
		$adb->pquery("DELETE FROM {$table_prefix}_crmentity WHERE crmid = ?", array($attid));
	}
}

// create description column for all modules with a description field
$res = $adb->query(
"SELECT t.tabid, t.name FROM {$table_prefix}_tab t
INNER JOIN {$table_prefix}_field f ON f.tabid = t.tabid AND f.fieldname = 'description'
WHERE isentitytype = 1");
while ($row = $adb->fetchByAssoc($res, -1, false)) {
	$tabid = $row['tabid'];
	$module = $row['name'];
	$focus = CRMEntity::getInstance($module);
	if ($focus && $focus->table_name) {
		if ($module != 'Messages') {
			$adb->addColumnToTable($focus->table_name, 'description', 'XL');
			// move the field in the module table
			$adb->pquery("UPDATE {$table_prefix}_field SET tablename = ? WHERE tabid = ? AND fieldname = ? AND tablename = ?", array(
				$focus->table_name, $tabid, 'description', $table_prefix.'_crmentity'
			));
			// move the content in the new column
			if ($adb->isMySQL()) {
				$adb->pquery(
					"UPDATE {$focus->table_name} t
					INNER JOIN {$table_prefix}_crmentity c ON c.crmid = t.{$focus->table_index}
					SET t.description = c.description
					WHERE c.setype = ? AND c.description IS NOT NULL",
					array($module)
				);
			} elseif ($adb->isMssql()) {
				$adb->pquery(
					"UPDATE t
					SET t.description = c.description
					FROM {$focus->table_name} t
					INNER JOIN {$table_prefix}_crmentity c ON c.crmid = t.{$focus->table_index}
					WHERE c.setype = ? AND c.description IS NOT NULL",
					array($module)
				);
			} else {
				$res2 = $adb->pquery("SELECT crmid, description FROM {$table_prefix}_crmentity WHERE setype = ? AND description IS NOT NULL", array($module));
				while ($row2 = $adb->fetchByAssoc($res2, -1, false)) {
					$adb->pquery("UPDATE {$focus->table_name} SET description = ? WHERE {$focus->table_index} = ?", array($row2['description'], $row2['crmid']));
				}
			}
			// and empty the original column
			$adb->pquery("UPDATE {$table_prefix}_crmentity SET description = NULL WHERE setype = ? AND description IS NOT NULL", array($module));
		}
		
		// fix customview
		$res2 = $adb->pquery(
			"SELECT cl.*
			FROM {$table_prefix}_cvcolumnlist cl
			INNER JOIN {$table_prefix}_customview c ON c.cvid = cl.cvid
			WHERE cl.columnname LIKE '{$table_prefix}_crmentity:description:%' AND c.entitytype = ?",
			array($module)
		);
		while ($row2 = $adb->fetchByAssoc($res2, -1, false)) {
			$pieces = explode(':', $row2['columnname']);
			$pieces[0] = $focus->table_name;
			$newcol = implode(':', $pieces);
			$adb->pquery("UPDATE {$table_prefix}_cvcolumnlist SET columnname = ? WHERE cvid = ? AND columnindex = ?", array($newcol, $row2['cvid'], $row2['columnindex']));
		}
		
		$res2 = $adb->pquery(
			"SELECT caf.*
			FROM {$table_prefix}_cvadvfilter caf
			INNER JOIN {$table_prefix}_customview c ON c.cvid = caf.cvid
			WHERE caf.columnname LIKE '{$table_prefix}_crmentity:description:%' AND c.entitytype = ?",
			array($module)
		);
		while ($row2 = $adb->fetchByAssoc($res2, -1, false)) {
			$pieces = explode(':', $row2['columnname']);
			$pieces[0] = $focus->table_name;
			$newcol = implode(':', $pieces);
			$adb->pquery("UPDATE {$table_prefix}_cvadvfilter SET columnname = ? WHERE cvid = ? AND columnindex = ?", array($newcol, $row2['cvid'], $row2['columnindex']));
		}
		
		$res2 = $adb->pquery(
			"SELECT co.*
			FROM tbl_s_cvorderby co
			INNER JOIN {$table_prefix}_customview c ON c.cvid = co.cvid
			WHERE co.columnname LIKE '{$table_prefix}_crmentity:description:%' AND c.entitytype = ?",
			array($module)
		);
		while ($row2 = $adb->fetchByAssoc($res2, -1, false)) {
			$pieces = explode(':', $row2['columnname']);
			$pieces[0] = $focus->table_name;
			$newcol = implode(':', $pieces);
			$adb->pquery("UPDATE tbl_s_cvorderby SET columnname = ? WHERE cvid = ? AND columnindex = ?", array($newcol, $row2['cvid'], $row2['columnindex']));
		}

	}
}

// fix pdf maker relblocks
$res2 = $adb->pquery(
	"SELECT rbc.*, rb.module, rb.secmodule
	FROM {$table_prefix}_pdfmaker_relblckcri rbc
	INNER JOIN {$table_prefix}_pdfmaker_relblocks rb ON rb.relblockid = rbc.relblockid
	WHERE rbc.columnname LIKE '%:description:%'",
	array($module)
);
while ($row2 = $adb->fetchByAssoc($res2, -1, false)) {
	$pieces = explode(':', $row2['columnname']);
	if ($pieces[0] == $table_prefix.'_crmentity') {
		// first module
		$module = $row['module'];
	} else {
		// second module
		$module = $row['secmodule'];
	}
	$focus = CRMEntity::getInstance($module);
	$pieces[0] = $focus->table_name;
	$newcol = implode(':', $pieces);
	$adb->pquery("UPDATE {$table_prefix}_pdfmaker_relblckcri SET columnname = ? WHERE relblockid = ? AND colid = ?", array($newcol, $row2['relblockid'], $row2['colid']));
}


// remove old unused tabled
if (Vtiger_Utils::CheckTable("{$table_prefix}_pdfmaker_relblockcriteria")) {
	$sqlarray = $adb->datadict->DropTableSQL("{$table_prefix}_pdfmaker_relblockcriteria");
	$adb->datadict->ExecuteSQLArray($sqlarray);
}
if (Vtiger_Utils::CheckTable("{$table_prefix}_pdfmaker_relblockcriteria_g")) {
	$sqlarray = $adb->datadict->DropTableSQL("{$table_prefix}_pdfmaker_relblockcriteria_g");
	$adb->datadict->ExecuteSQLArray($sqlarray);
}

// regenerate all script for custom modules
require_once('modules/Settings/ModuleMaker/ModuleMakerUtils.php');
require_once('modules/Settings/ModuleMaker/ModuleMakerSteps.php');
require_once('modules/Settings/ModuleMaker/ModuleMakerGenerator.php');
$MMUtils = new ModuleMakerUtils();
$MMSteps = new ModuleMakerSteps($MMUtils);
$MMGen = new ModuleMakerGenerator($MMUtils, $MMSteps);
$res = $adb->query("SELECT id FROM {$table_prefix}_modulemaker WHERE useredit = 0");
while ($row = $adb->fetchByAssoc($res, -1, false)) {
	$MMGen->generate($row['id']);
}


// show a message
Update::info("The 'description' column has been moved from the crmentity table to the module table,");
Update::info("please check any custom script that relies on this column.");
Update::info("");
