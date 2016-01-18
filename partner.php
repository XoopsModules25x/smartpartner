<?php

/**
 * $Id: partner.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

include_once("header.php");
$xoopsOption['template_main'] = 'smartpartner_partner.html';
include_once(XOOPS_ROOT_PATH . "/header.php");
include_once("footer.php");

global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

If ($id == 0) {
    redirect_header("javascript:history.go(-1)", 2, _MD_SPARTNER_NOPARTNERSELECTED);
    exit();
}

// Creating the Partner object for the selected FAQ
$partnerObj = new SmartpartnerPartner($id);

// If the selected partner was not found, exit
If ($partnerObj->notLoaded()) {
    redirect_header("javascript:history.go(-1)", 2, _MD_SPARTNER_NOPARTNERSELECTED);
    exit();
}
include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectpermission.php';
$smartpermissions_handler = new SmartobjectPermissionHandler($smartpartner_partner_handler);
$grantedItems = $smartpermissions_handler->getGrantedItems('full_view');
$grantedItems = array_merge($grantedItems, $smartpermissions_handler->getGrantedItems('partial_view'));

// Chech the status
If ($partnerObj->status() != _SPARTNER_STATUS_ACTIVE || (!in_array($id, $grantedItems))) {
    redirect_header("javascript:history.go(-1)", 2, _NOPERM);
    exit();
}

// Updating the counter
$partnerObj->updateHits_page();

// Populating the smarty variables with informations related to the selected Partner
$partner = $partnerObj->toArray();
// Creating the files object associated with this item
$filesObj = $partnerObj->getFiles();

$files = array();
$embeded_files = array();

foreach ($filesObj as $fileObj)
{
    if ($fileObj->mimetype() == 'application/x-shockwave-flash') {
        $file['content'] = $fileObj->displayFlash();

        if (strpos($partner['maintext'], '[flash-' . $fileObj->getVar('fileid') . ']')) {
            $partner['maintext'] = str_replace('[flash-' . $fileObj->getVar('fileid') . ']', $file['content'], $partner['maintext']);
        } else {
            $embeded_files[] = $file;
        }
        unset($file);
    } else {
        $file['fileid'] = $fileObj->fileid();
        $file['name'] = $fileObj->name();
        $file['description'] = $fileObj->description();
        $file['name'] = $fileObj->name();
        $file['type'] = $fileObj->mimetype();
        $file['datesub'] = $fileObj->datesub();
        $file['hits'] = $fileObj->counter();
        $files[] = $file;
        unset($file);
    }

}
$partner['files'] = $files;
$partner['embeded_files'] = $embeded_files;
$xoopsTpl->assign('partner', $partner);

//Get offers of this partner
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('partnerid', $id));
$criteria->add(new Criteria('date_pub', time(), '<'));
$criteria->add(new Criteria('date_end', time(), '>'));
$criteria->add(new Criteria('status', _SPARTNER_STATUS_ONLINE));

$offersObj =& $smartpartner_offer_handler->getObjects($criteria);
$offers = array();
foreach ($offersObj as $offerObj) {
    $offers[] = $offerObj->toArray();
}
$xoopsTpl->assign('offers', $offers);
$categoryPath = '';
if (isset($_GET['cid'])) {
    $categoryObj = $smartpartner_category_handler->get($_GET['cid']);
} else {
    $categoryObj = $smartpartner_category_handler->get($partnerObj->categoryid());
}

if (!$categoryObj->isNew()) {
    $categoryPath = $categoryObj->getCategoryPath() . " > ";
}
$categoryPath .= $partnerObj->title();
$xoopsTpl->assign('categoryPath', $categoryPath);
$xoopsTpl->assign('module_home', '<a href="' . SMARTPARTNER_URL . '">' . $smartpartner_moduleName . '</a>');

// Lanugage constants
$xoopsTpl->assign('lang_offers', _CO_SPARTNER_OFFERS);
$xoopsTpl->assign('lang_offer_click_here', _CO_SPARTNER_OFFER_CLICKHERE);
$xoopsTpl->assign('lang_contact', _CO_SPARTNER_CONTACT);
$xoopsTpl->assign('lang_email', _CO_SPARTNER_EMAIL);
$xoopsTpl->assign('lang_adress', _CO_SPARTNER_ADRESS);
$xoopsTpl->assign('lang_phone', _CO_SPARTNER_PHONE);
$xoopsTpl->assign('lang_website', _CO_SPARTNER_WEBSITE);
$xoopsTpl->assign('lang_times', _CO_SPARTNER_TIMES);
$xoopsTpl->assign('lang_stats', _CO_SPARTNER_STATS);
$xoopsTpl->assign('lang_partner_informations', _CO_SPARTNER_PARTNER_INFORMATIONS);
$xoopsTpl->assign('lang_page_been_seen', _CO_SPARTNER_PAGE_BEEN_SEEN);
$xoopsTpl->assign('lang_url_been_visited', _CO_SPARTNER_URL_BEEN_VISITED);
$xoopsTpl->assign('lang_backtoindex', _MD_SPARTNER_BACKTOINDEX);
$xoopsTpl->assign('modulepath', SMARTPARTNER_URL);
$xoopsTpl->assign('lang_private', _CO_SPARTNER_PRIVATE);
$xoopsTpl->assign('partview_msg', $myts->xoopsCodeDecode($myts->displayTarea($xoopsModuleConfig['partview_msg'], 1)));

$show_stats_block = false;
if ($xoopsUser) {
    foreach ($xoopsModuleConfig['stats_group'] as $group) {
        if (in_array($group, $xoopsUser->getGroups())) {
            $show_stats_block = true;
        }
    }
} else {
    $show_stats_block = in_array(XOOPS_GROUP_ANONYMOUS, $xoopsModuleConfig['stats_group']);
}

$xoopsTpl->assign('show_stats_block', $show_stats_block);

// MetaTag Generator
smartpartner_createMetaTags($partnerObj->title(), '', $partnerObj->summary());

//code to include smartie
/*if (file_exists(XOOPS_ROOT_PATH . '/modules/smarttie/smarttie_links.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/smarttie/smarttie_links.php';
    $xoopsTpl->assign('smarttie',1);
}*/
//end code for smarttie

include_once XOOPS_ROOT_PATH . '/footer.php';
