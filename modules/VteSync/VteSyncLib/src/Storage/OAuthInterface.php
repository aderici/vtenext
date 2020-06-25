<?php

namespace VteSyncLib\Storage;

interface OAuthInterface {

	public function getOAuthInfo($syncid);
	public function getTokenInfo($syncid);
	public function setTokenInfo($syncid, $tokenInfo);
	//public function setRefreshToken($syncid, $token);
	
}
