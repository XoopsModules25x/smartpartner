<?php
/**
 *
 * Module: SmartSection
 * Author: marcan <marcan@notrevie.ca>
 * Licence: GNU
 */

include_once __DIR__ . '/header.php';

$fileid = isset($_GET['fileid']) ? (int)$_GET['fileid'] : 0;

// Creating the item object for the selected item
$fileObj = $smartPartnerFileHandler->get($fileid);
$fileObj->updateCounter();

if (!preg_match("/^ed2k*:\/\//i", $fileObj->getFileUrl())) {
    header('Location: ' . $fileObj->getFileUrl());
}

echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $myts->oopsHtmlSpecialChars($fileObj->getFileUrl()) . "\"></meta></head><body></body></html>";
exit();
