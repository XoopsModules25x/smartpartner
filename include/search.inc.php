<?php

/**
 *
 * Module: SmartMedia
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

function smartpartner_search($queryarray, $andor, $limit, $offset, $userid)
{
    // This must contain the name of the folder in which reside SmartPartner
    if (!defined('SMARTPARTNER_DIRNAME')) {
        define('SMARTPARTNER_DIRNAME', 'smartpartner');
    }
    include_once XOOPS_ROOT_PATH . '/modules/' . SMARTPARTNER_DIRNAME . '/include/common.php';

    $ret = array();

    if (!isset($smartPartnerPartnerHandler)) {
        $smartPartnerPartnerHandler = smartpartner_gethandler('partner');
    }

    // Searching the partners
    $partners_result = $smartPartnerPartnerHandler->getObjectsForSearch($queryarray, $andor, $limit, $offset, $userid);

    if ($queryarray == '') {
        $keywords       = '';
        $hightlight_key = '';
    } else {
        $keywords       = implode('+', $queryarray);
        $hightlight_key = '&amp;keywords=' . $keywords;
    }

    foreach ($partners_result as $result) {
        $item['image'] = 'assets/images/links/partner.gif';
        $item['link']  = 'partner.php?id=' . $result['id'] . $hightlight_key;
        $item['title'] = '' . $result['title'];
        $item['time']  = '';
        $item['uid']   = '';
        $ret[]         = $item;
        unset($item);
    }

    return $ret;
}
