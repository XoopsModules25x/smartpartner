<?php

/**
 * $Id: vpartner.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

include "header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$partnerObj = new SmartpartnerPartner($id);

if ($partnerObj->notLoaded()) {
    redirect_header("javascript:history.go(-1)", 1, _CO_SPARTNER_NOPARTNERSELECTED);
    exit();
}

if ($partnerObj->url()) {
    if (!isset($HTTP_COOKIE_VARS['partners'][$id])) {
        setcookie("partners[$id]", $id, $xoopsModuleConfig['cookietime']);
        $partnerObj->updateHits();
    }
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $partnerObj->url() . " ");
    exit();
    //echo "<html><head><meta http-equiv='Refresh' content='0; URL=".$partnerObj->url()."'></head><body></body></html>";
} else {
    redirect_header("index.php", 1, _XP_NOPART);
}
