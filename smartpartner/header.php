<?php

/**
 * $Id: header.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

include "../../mainfile.php";

// This must contain the name of the folder in which reside SmartPartner
if (!defined("SMARTPARTNER_DIRNAME")) {
    define("SMARTPARTNER_DIRNAME", 'smartpartner');
}

include XOOPS_ROOT_PATH . '/modules/' . SMARTPARTNER_DIRNAME . '/include/common.php';
include XOOPS_ROOT_PATH . "/class/pagenav.php";
