<?php

namespace VteSyncLib\Model;

interface RecordInterface {

	public static function fromRawData($data);
	public static function fromCommonRecord(CommonRecord $crecord);
	public function toCommonRecord();

}
