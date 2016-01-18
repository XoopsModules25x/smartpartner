<?php

/**
 * $Id: main.php 10055 2012-08-11 12:46:10Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

/**
 * This does not belong here. However, since some admin.php constants are refereing to constants
 * defined in common.php, and since admin.php is automatically include when including mainfile.php
 * we need to include the common language file here.
 *
 * For next release : remove all reference to common constants in other language files.
 */
// including the common language file
$fileName = XOOPS_ROOT_PATH . '/modules/smartpartner/language/' . $GLOBALS['xoopsConfig']['language'] . '/common.php';
if (file_exists($fileName)) {
    include_once $fileName;
} else {
    include_once XOOPS_ROOT_PATH . '/modules/smartpartner/language/english/common.php';
}

define("_MD_SPARTNER_ADMIN_PAGE",":: Admin Section ::");
define("_MD_SPARTNER_NOPARTNERSELECTED","You did not select a valid partner.");
define("_MD_SPARTNER_NOTIFY","Notify on approval");
define("_MD_SPARTNER_READMORE","Read more...");
define("_MD_SPARTNER_SUBMIT_ERROR","An error occured while submitting the partner.");
define("_MD_SPARTNER_SUBMIT_SUCCESS","The partner was successfully submitted.");
define("_MD_SPARTNER_DETAIL_CAT","View all partners of this category");
define("_MD_SPARTNER_EDIT","Edit");
define("_MD_SPARTNER_DELETE","Delete");
define("_MD_SPARTNER_JOIN","Become a Partner");
define("_MD_SPARTNER_PARTNERS","Partners");
define("_MD_SPARTNER_PARTNERSTITLE","The partners of ");
define("_MD_SPARTNER_PARTNER","Partner");
define("_MD_SPARTNER_DESCRIPTION","Description");
define("_MD_SPARTNER_HITS","Hits");
define("_MD_SPARTNER_NOPART","There is currently no partner to display.");
//file join.php
define("_MD_SPARTNER_IMAGE","Logo");
define("_MD_SPARTNER_TITLE","Name");
define("_MD_SPARTNER_URL","Web site:");
define("_MD_SPARTNER_SEND","Send the request");
define("_MD_SPARTNER_ERROR1","You did not fill the form correctly. Please try again.");
define("_MD_SPARTNER_ERROR2","<center>Image size is larger than 110x50. <a href='javascript:history.back(1)'>Try with another image</a></center>");
define("_MD_SPARTNER_ERROR3","<center>The image file doesn't exist. <a href='javascript:history.back(1)'>Try with another image</a></center>");
define("_MD_SPARTNER_GOBACK","<a href='javascript:history.back(1)'>Back</a>");
define("_MD_SPARTNER_NEWPARTNER","%s Partners Request");
define("_MD_SPARTNER_SENDMAIL","Request mail sent to the administrator.<br /><a href='index.php'>Return to index</a>");
define("_MD_SPARTNER_CATEGORY_EMPTY","This category is empty.");

// Hack by marcan : More information on the SmartPartner module
define("_MD_SPARTNER_INTRO_TEXT","Here is the list of our prefered partners. You can access their web site by clicking on their logo");
define("_MD_SPARTNER_INTRO_JOIN","You would like to become a partner of %s? Fill out the following form and we will contact you as soon as possible.");
define("_MD_SPARTNER_TITLE_DSC","Your organisation name");
define("_MD_SPARTNER_IMAGE_DSC","Your logo address");
define("_MD_SPARTNER_URL_DSC","Your web site");
define("_MD_SPARTNER_DESCRIPTION_DSC","Description of your organisation");
define("_MD_SPARTNER_BACKTOINDEX","Back to partners index");
define("_MD_SPARTNER_OFFER_INTRO","Offer list");
