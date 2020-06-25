<?php

namespace VteSyncLib\Model;

interface UserInterface {

	public static function fromRawData($data);
	public static function fromCommonUser(CommonUser $cuser);
	public function toCommonUser();
	
}
