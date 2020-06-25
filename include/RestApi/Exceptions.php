<?php
/* crmv@170283 */
class InvalidLoginException extends \Exception {
	var $pHttpCode = '401';
}
class MethodNotFoundException extends \Exception {
	var $pHttpCode = '500';
}