<?php

//require_once("Maintenance.php");

require_once(dirname(dirname(__FILE__)).'/wpi.php');

$dbr =& wfGetDB(DB_SLAVE);
//$dbr =& wfGetDB("127.0.0.1");

$res = $dbr->select("page", array("page_title"), array("page_namespace"=> NS_PATHWAY));
while($row = $dbr->fetchRow($res)) {
	try {
		$pathway = Pathway::newFromTitle($row[0]);
		echo($pathway->getTitleObject()->getFullText() . "\n<BR>");
		if($doit) {
					$pathway->updateCache();
		}
	} catch(Exception $e) {
		echo "Exception: {$e->getMessage()}<BR>\n";
	}
}
