<?php

// crmv@136499

// alter the table to be longtext
Vtiger_Utils::AlterTable($table_prefix.'_notes','notecontent XL');
