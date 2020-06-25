<?php

namespace VteSyncLib\Model;

interface MetaInterface {

	public static function fromRawData($data);
	public static function fromCommonMeta(CommonMeta $cmeta);
	public function toCommonMeta();

}
