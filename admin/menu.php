<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(__DIR__));
$moduleHandler   = xoops_getHandler('module');
$module          = $moduleHandler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu              = array();
$i                      = 0;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_ADMENU1;
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/manage.png';

++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_CATEGORIES;
$adminmenu[$i]['link']  = 'admin/category.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_ADMENU2;
$adminmenu[$i]['link']  = 'admin/partner.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/users.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_ADMENU3;
$adminmenu[$i]['link']  = 'admin/offer.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/cash_stack.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_IMPORT;
$adminmenu[$i]['link']  = 'admin/import.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/compfile.png';
++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
