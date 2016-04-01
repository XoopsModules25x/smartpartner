<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $category
 * @param $item_id
 * @return mixed
 */

function smartpartner_notify_iteminfo($category, $item_id)
{
    // This must contain the name of the folder in which reside SmartPartner
    if (!defined('SMARTPARTNER_DIRNAME')) {
        define('SMARTPARTNER_DIRNAME', 'smartpartner');
    }

    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;

    if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != SMARTPARTNER_DIRNAME) {
        $moduleHandler = xoops_getHandler('module');
        $module        = &$moduleHandler->getByDirname(SMARTPARTNER_DIRNAME);
        $configHandler = xoops_getHandler('config');
        $config        = &$configHandler->getConfigsByCat(0, $module->getVar('mid'));
    } else {
        $module = &$xoopsModule;
        $config = &$xoopsModuleConfig;
    }

    if ($category === 'global') {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsDB;

    if ($category === 'item') {
        // Assume we have a valid partner id
        $sql          = 'SELECT question FROM ' . $xoopsDB->prefix('smartpartner_partner') . ' WHERE id = ' . $item_id;
        $result       = $xoopsDB->query($sql); // TODO: error check
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/partner.php?id=' . $item_id;

        return $item;
    }
}
