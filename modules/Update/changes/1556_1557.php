<?php

// crmv@127567 - remove test dir (only if there are no custom files)

// remove common files
@unlink('test/contact/a.txt');
@unlink('test/product/vtigercrm.txt');
for ($i = 1; $i<=10; ++$i) {
	@rmdir("test/product/product{$i}.jpeg");
}
@unlink('test/upload/vtigercrm.txt');
@unlink('test/user/a.txt');
@unlink('test/vtlib/HTML/3.3.0,aacfe2e21b552364077576cd0a636b92,1.ser');
@unlink('test/vtlib/HTML/README.txt');
@unlink('test/vtlib/README.txt');
@unlink('test/wordtemplatedownload/todel.txt');

// move some files
$dest = 'cache/vtlib/';
$list = glob('test/vtlib/*');
if ($list) {
	foreach ($list as $vtfile) {
		if (is_file($vtfile)) {
			@rename($vtfile, $dest.basename($vtfile));
		}
	}
}
if (is_dir('test/logo') && is_writable('storage')) {
	// move away the standard one
	if (is_dir('storage/logo')) {
		@rename('storage/logo', 'storage/logo.vte');
	}
	// and copy the old one!
	@rename('test/logo', 'storage/logo');
}

// remove dirs (if not empty)
@rmdir('test/contact');
@rmdir('test/product');
@rmdir('test/upload');
@rmdir('test/user');
@rmdir('test/vtlib/HTML');
@rmdir('test/vtlib');
@rmdir('test/wordtemplatedownload');
@rmdir('test');


$trans = array(
	'APP_STRINGS' => array(
		'it_it' => array(
			'STARTING_IN' => 'Inizierà tra',
		),
		'en_us' => array(
			'STARTING_IN' => 'Starting in',
		),
	),
);
$languages = vtlib_getToggleLanguageInfo();
foreach ($trans as $module=>$modlang) {
	foreach ($modlang as $lang=>$translist) {
		if (array_key_exists($lang,$languages)) {
			foreach ($translist as $label=>$translabel) {
				SDK::setLanguageEntry($module, $lang, $label, $translabel);
			}
		}
	}
}
