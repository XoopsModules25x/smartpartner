<?php
/**
 * $Id: visit.php,v 1.1 2007/09/18 14:00:56 marcan Exp $
 * Module: SmartSection
 * Author: marcan <marcan@notrevie.ca>
 * Licence: GNU
 */

include_once "header.php";

$fileid = isset($_GET['fileid']) ? intval($_GET['fileid']) : 0;

// Creating the item object for the selected item
$fileObj = $smartpartner_file_handler->get($fileid);
$fileObj->updateCounter();

if (!preg_match("/^ed2k*:\/\//i", $fileObj->getFileUrl())) {
    Header("Location: " . $fileObj->getFileUrl());
}

echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $myts->oopsHtmlSpecialChars($fileObj->getFileUrl()) . "\"></meta></head><body></body></html>";
exit();
