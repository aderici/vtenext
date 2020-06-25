<?php

global $adb, $table_prefix;

if (!function_exists('moveFieldAfter')) {
	function moveFieldAfter($module, $field, $afterField) {
		global $adb, $table_prefix;
		
		$tabid = getTabid($module);
		if (empty($tabid)) return;
		
		$res = $adb->pquery("SELECT fieldid, sequence FROM {$table_prefix}_field WHERE tabid = ? AND fieldname = ?", array($tabid, $field));
		if ($res && $adb->num_rows($res) > 0) {
			$fieldid1 = intval($adb->query_result_no_html($res, 0, 'fieldid'));
			$sequence1 = intval($adb->query_result_no_html($res, 0, 'sequence'));
		}
		
		$res = $adb->pquery("SELECT fieldid, sequence FROM {$table_prefix}_field WHERE tabid = ? AND fieldname = ?", array($tabid, $afterField));
		if ($res && $adb->num_rows($res) > 0) {
			$fieldid2 = intval($adb->query_result_no_html($res, 0, 'fieldid'));
			$sequence2 = intval($adb->query_result_no_html($res, 0, 'sequence'));
		}
		
		if ($fieldid1 > 0 && $fieldid2 > 0) {
			// get the ids to update
			$updateIds = array();
			$res = $adb->pquery("SELECT fieldid FROM {$table_prefix}_field WHERE tabid = ? AND sequence > ?", array($tabid, $sequence2));
			if ($res && $adb->num_rows($res) > 0) {
				while ($row = $adb->fetchByAssoc($res)) {
					$updateIds[] = intval($row['fieldid']);
				}
			}
			if (count($updateIds) > 0) {
				$adb->pquery("UPDATE {$table_prefix}_field set sequence = sequence + 1 WHERE fieldid IN (".generateQuestionMarks($updateIds).")", $updateIds);
			}
			$adb->pquery("UPDATE {$table_prefix}_field set sequence = ? WHERE tabid = ? AND fieldid = ?", array($sequence2+1, $tabid, $fieldid1));
		}	
	}
}

// crmv@151466

$adb->addColumnToTable($table_prefix.'_emailtemplates', 'parentid', 'INT(11)', 'DEFAULT 0');

// crmv@151474

$fields = array(
	'language'			=> array('module'=>'Newsletter', 'block'=>'LBL_NEWSLETTER_INFORMATION', 'name'=>'language', 	'label'=>'Newsletter language',		'table'=>$table_prefix.'_newsletter', 	'columntype'=>'C(31)',	'typeofdata'=>'C~O',	'uitype'=>202),
	'replyto_address'	=> array('module'=>'Newsletter', 'block'=>'LBL_NEWSLETTER_INFORMATION', 'name'=>'replyto_address', 	'label'=>'Reply To Address',		'table'=>$table_prefix.'_newsletter', 	'columntype'=>'C(100)',	'typeofdata'=>'E~O',	'uitype'=>13),
);

Update::create_fields($fields);

moveFieldAfter('Newsletter', 'replyto_address', 'from_address');
moveFieldAfter('Newsletter', 'language', 'campaignid');

$trans = array(
	'Newsletter' => array(
		'it_it' => array(
			'YouCanSeeNewsletterPreview' => 'Puoi anche vedere un\'anteprima della newsletter cliccando su uno dei seguenti destinatari:',
			'Reply To Address' => 'Rispondi all\'indirizzo',
			'Newsletter language' => 'Lingua newsletter',
		),
		'en_us' => array(
			'YouCanSeeNewsletterPreview' => 'You can also see a preview of the newsletter by clicking one of the following recipients:',
			'Reply To Address' => 'Reply to address',
			'Newsletter language' => 'Newsletter language',
		),
	),
	'ChangeLog' => array(
		'it_it' => array(
			'LBL_RECORD_UNSUBSCRIBED_FROM' => 'Record disiscritto dalla newsletter %s',
			'LBL_RECORD_UNSUBSCRIBED_FROM_ALL' => 'Record disiscritto da tutte le newsletter',
			'LBL_RECORD_SUBSCRIBED_TO' => 'Record re-iscritto alla newsletter %s',
			'LBL_RECORD_SUBSCRIBED_TO_ALL' => 'Record re-iscritto a tutte le newsletter',
		),
		'en_us' => array(
			'LBL_RECORD_UNSUBSCRIBED_FROM' => 'Record unsubscribed from newsletter %s',
			'LBL_RECORD_UNSUBSCRIBED_FROM_ALL' => 'Record unsubscribed from all newsletters',
			'LBL_RECORD_SUBSCRIBED_TO' => 'Record re-enabled subscription to newsletter %s',
			'LBL_RECORD_SUBSCRIBED_TO_ALL' => 'Record re-enabled subscription to all newsletters',
		),
	),
);

$languages = vtlib_getToggleLanguageInfo();
foreach ($trans as $module => $modlang) {
	foreach ($modlang as $lang => $translist) {
		if (array_key_exists($lang, $languages)) {
			foreach ($translist as $label => $translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}
