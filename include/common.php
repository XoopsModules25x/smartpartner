<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

// This must contain the name of the folder in which reside SmartPartner
if (!defined('SMARTPARTNER_DIRNAME')) {
    define('SMARTPARTNER_DIRNAME', 'smartpartner');
}

if (!defined('SMARTPARTNER_URL')) {
    define('SMARTPARTNER_URL', XOOPS_URL . '/modules/' . SMARTPARTNER_DIRNAME . '/');
}
if (!defined('SMARTPARTNER_ROOT_PATH')) {
    define('SMARTPARTNER_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . SMARTPARTNER_DIRNAME . '/');
}

include_once(SMARTPARTNER_ROOT_PATH . 'include/functions.php');
include_once(SMARTPARTNER_ROOT_PATH . 'include/seo_functions.php');
include_once(SMARTPARTNER_ROOT_PATH . 'include/metagen.php');
include_once(SMARTPARTNER_ROOT_PATH . 'class/keyhighlighter.class.php');
include_once(SMARTPARTNER_ROOT_PATH . 'class/session.php');

/** Include SmartObject framework **/
include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartloader.php';
include_once(SMARTOBJECT_ROOT_PATH . 'class/smartobjectcategory.php');

// Creating the SmartModule object
$smartModule = smartpartner_getModuleInfo();

// Find if the user is admin of the module
$smartPartnerIsAdmin = smartpartner_userIsAdmin();

$myts                   = MyTextSanitizer::getInstance();
$smartPartnerModuleName = $smartModule->getVar('name');

// Creating the SmartModule config Object
$smartConfig = smartpartner_getModuleConfig();

// Creating the partner handler object
$smartPartnerPartnerHandler = smartpartner_gethandler('partner');

// Creating the category handler object
$smartPartnerCategoryHandler = smartpartner_gethandler('category');

// Creating the category link handler object
$smartpartnerPartnerCatLinkHandler = smartpartner_gethandler('partnercatlink');

// Creating the offer handler object
$smartPartnerOfferHandler = smartpartner_gethandler('offer');

// Creating the file handler object
$smartPartnerFileHandler = smartpartner_gethandler('file');

define('_SPARTNER_STATUS_OFFLINE', 0);
define('_SPARTNER_STATUS_ONLINE', 1);
$statusArray = array(
    _SPARTNER_STATUS_OFFLINE => _CO_SPARTNER_STATUS_OFFLINE,
    _SPARTNER_STATUS_ONLINE  => _CO_SPARTNER_STATUS_ONLINE
);
include_once(SMARTPARTNER_ROOT_PATH . 'class/smarttree.php');
