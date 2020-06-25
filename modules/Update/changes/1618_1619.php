<?php
global $enterprise_current_version, $enterprise_mode, $enterprise_website;

SDK::setLanguageEntries('APP_STRINGS', 'LBL_BROWSER_TITLE', array(
	'it_it'=>"$enterprise_mode $enterprise_current_version",
	'en_us'=>"$enterprise_mode $enterprise_current_version",
	'de_de'=>"$enterprise_mode $enterprise_current_version",
	'nl_nl'=>"$enterprise_mode $enterprise_current_version",
	'pt_br'=>"$enterprise_mode $enterprise_current_version")
);
SDK::setLanguageEntries('Settings', 'LBL_PRIVACY_DESC', array(
	'it_it'=>"Per migliorare la versione community di VTE, memorizziamo alcune informazioni riguardo la tua installazione come l'utente di {$enterprise_website[1]} che ha attivato il sistema, la cartella in cui è stato installato e il numero di utenti che lo usa, che sono le informazioni necessarie per l'installazione. Non salviamo nessun'altra informazione riguardo dati che hai inserito o server mail che usi. E' solo un modo per conoscere quante persone utilizzano VTE nel mondo. Questa informazione è aggiornata ad ogni login dell'utente amministratore, quindi il numero di utenti attivi viene sempre aggiornato.",
	'en_us'=>'To improve the VTE application, we collect a minimum of information from your installation. E.g. the '.$enterprise_website[1].' user that activates the system, the folder it has been installed in and the number of users. This is the same information needed to activate the application. We do not collect (information on ) your data, or the mail server you use. It helps us to understand how many people are enjoying VTE worldwide. The information is only updated when the administrator logs in.',
));