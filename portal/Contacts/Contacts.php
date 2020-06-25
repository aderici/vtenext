<?php
/* crmv@173271 */


class ContactsModule extends PortalModule {

	public $display_columns = 1;
	
	protected function postprocessDetailFields($id, $info) {
		$customerid = $_SESSION['customer_id'];
		
		if ($_REQUEST['update'] == 'yes' && $id == $customerid) {
			// alter the format of the fields
			$info = getblock_fieldlist(array_values($info));
		}
		
		return $info;
	}

	protected function prepareDetail($id) {
		$smarty = parent::prepareDetail($id);
		
		$customerid = $_SESSION['customer_id'];
		
		$smarty->assign('PERMISSION', array('perm_read' => 'true', 'perm_write' => false, 'perm_delete'=> false));
		
		// Modalità dettaglio Contatto loggato o no
		$profile = $_REQUEST['profile'];
		if($profile != 'yes'){
			$profile = '';
		}
		if($id != $customerid){
			$profile = '';
		}
		$smarty->assign('CONTACTPROFILE',$profile);
		
		
		if ($_REQUEST['update'] == 'yes' && $id == $customerid) {
			include('Contacts/config.php');
			$smarty->assign('SELECTFIELDS',$selectFields);
			$this->detail_template = 'EditProfile.tpl';
		}
		
		return $smarty;
	}
}
