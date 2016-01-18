<?php

/**
 * $Id: menu.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

$adminmenu[0]['title'] = _MI_SPARTNER_ADMENU1;
$adminmenu[0]['link'] = "admin/index.php";

$adminmenu[1]['title'] = _MI_SPARTNER_CATEGORIES;
$adminmenu[1]['link'] = "admin/category.php";

$adminmenu[2]['title'] = _MI_SPARTNER_ADMENU2;
$adminmenu[2]['link'] = "admin/partner.php";

$adminmenu[3]['title'] = _MI_SPARTNER_ADMENU3;
$adminmenu[3]['link'] = "admin/offer.php";

if (isset($xoopsModule)) {
    $headermenu[0]['title'] = _PREFERENCES;
    $headermenu[0]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

    $headermenu[1]['title'] = _AM_SPARTNER_GOMOD;
    $headermenu[1]['link'] = SMARTPARTNER_URL;

    $headermenu[2]['title'] = _AM_SPARTNER_UPDATE_MODULE;
    $headermenu[2]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');

    $headermenu[3]['title'] = _AM_SPARTNER_IMPORT;
    $headermenu[3]['link'] = SMARTPARTNER_URL . "admin/import.php";

    $headermenu[4]['title'] = _AM_SPARTNER_ABOUT;
    $headermenu[4]['link'] = SMARTPARTNER_URL . "admin/about.php";
}
