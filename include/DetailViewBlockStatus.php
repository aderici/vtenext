<?php
/**
* File created by SAKTI PRASAD MISHRA on 31 oct, 2007.
* This file is included within "DetailView.tpl" to provide SESSION value to smarty template
*/
VteSession::start();
$aAllBlockStatus = VteSession::get('BLOCKINITIALSTATUS');
$this->assign("BLOCKINITIALSTATUS",$aAllBlockStatus);
