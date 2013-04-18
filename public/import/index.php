<?php
require_once('Importer.class.php');
require_once('DB.class.php');
require_once('DataHelper.class.php');

// No warnings please
error_reporting(E_ERROR);
// Start looking for files from root dir
chdir(dirname(dirname(__DIR__)));

// Take database credentials from ZF2 local config
$zf2LocalConf = include('config/autoload/local.php');

try {
	$flush = true;
	$importer = new \Libadmin\Importer($zf2LocalConf[ 'db' ], $flush);

	$xmlFile = '/module/Libadmin/data/tpgreen-libraries.xml';
	echo $importer->import('module/Libadmin/data/tpgreen-libraries.xml');

} catch (Exception $e) {
	die('IMPORT FAILED - ' . $e->getMessage());
}
