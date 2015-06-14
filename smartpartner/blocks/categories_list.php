<?php

/**
 * $Id: categories_list.php,v 1.1 2007/09/18 14:00:52 marcan Exp $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
function get_content($cat_id, $catsObj, $displaysubs)
{
    $content = array();
    $i = 0;
    foreach ($catsObj as $catObj) {
        if ($catObj->getVar('parentid') == $cat_id) {
            $content[$catObj->getVar('categoryid')]['link'] = "<a href='" . XOOPS_URL . "/modules/smartpartner/index.php?view_category_id=" . $catObj->getVar('categoryid') . "'>" . $catObj->getVar('name') . "</a>";
            $content[$catObj->getVar('categoryid')]['categories'] = get_content($catObj->getVar('categoryid'), $catsObj, $displaysubs);
            $content[$catObj->getVar('categoryid')]['displaysubs'] = $displaysubs;
        }
        $i++;
    }

    return $content;

}

function b_categories_list_show($options)
{
    include_once (XOOPS_ROOT_PATH . "/modules/smartpartner/include/common.php");

    $smartpartner_category_handler =& smartpartner_gethandler('category');
    $criteria = new CriteriaCompo();

    $criteria->setSort(isset($options[0]) ? $options[0] : 'name');
    $criteria->setOrder(isset($options[1]) ? $options[1] : 'ASC');

    $catsObj =& $smartpartner_category_handler->getobjects($criteria, true);
    $catArray = get_content(0, $catsObj, $options[2]);

    $block = array();
    $block['categories'] = $catArray;
    $block['displaysubs'] = $options[2];
    if (isset($_GET['view_category_id'])) {
        $current_id = $_GET['view_category_id'];
        $block['current'] = $catsObj[$current_id]->getVar('parentid') == 0 ? $current_id : $catsObj[$current_id]->getVar('parentid');
    } elseif (isset($_GET['id'])) {
        $smartpartner_partner_handler =& smartpartner_gethandler('partner');
        $partnerObj = $smartpartner_partner_handler->get($_GET['id']);
        if (is_object($partnerObj)) {
            $parent = $partnerObj->getVar('categoryid');
            $block['current'] = $catsObj[$parent]->getVar('parentid') == 0 ? $parent : $catsObj[$parent]->getVar('parentid');
        }
    }

    return $block;
}

function b_categories_list_edit($options)
{
    $form = "<table border='0'>";

    /*$form .= "<tr><td>"._MB_SPARTNER_BLIMIT."</td><td>";
     $form .= "<input type='text' name='options[0]' size='16' value='".$options[0]."' /></td></tr>";*/
    //sort
    $form .= "<tr><td>" . _MB_SPARTNER_SORT . "</td><td>";
    $form .= "<select size='1' name='options[0]'>";
    $sel = "";
    if ($options[0] == 'title') {
        $sel = " selected='selected'";
    }
    $form .= "<option value='name' " . $sel . ">" . _MB_SPARTNER_TITLE . "</option>";
    $sel = "";
    if ($options[0] == 'weight') {
        $sel = " selected='selected'";
    }
    $form .= "<option value='weight' " . $sel . ">" . _MB_SPARTNER_WEIGHT . "</option>";
    $sel = "";
    if ($options[0] == 'categoryid') {
        $sel = " selected='selected'";
    }
    $form .= "<option value='categoryid' " . $sel . ">" . _MB_SPARTNER_ID . "</option>";
    $form .= "</select></td></tr>";

    //order
    $form .= "<tr><td>" . _MB_SPARTNER_ORDER . "</td><td>";
    $form .= "<select size='1' name='options[2]'>";
    $sel = "";
    if ($options[1] == 'ASC') {
        $sel = " selected='selected'";
    }
    $form .= "<option value='ASC' " . $sel . ">" . _MB_SPARTNER_ASC . "</option>";
    $sel = "";
    if ($options[1] == 'DESC') {
        $sel = " selected='selected'";
    }
    $form .= "<option value='DESC' " . $sel . ">" . _MB_SPARTNER_DESC . "</option>";

    $form .= "</select></td></tr>";

    //displaysubs
    $form .= "<tr><td>" . _MB_SPARTNER_SHOW_CURR_SUBS . "</td><td>";
    $form .= "<select size='1' name='options[3]'>";
    $sel = "";
    if ($options[2] == 1) {
        $sel = " selected='selected'";
    }
    $form .= "<option value='1' " . $sel . ">" . _MB_SPARTNER_YES . "</option>";
    $sel = "";
    if ($options[2] == 0) {
        $sel = " selected='selected'";
    }
    $form .= "<option value='0' " . $sel . ">" . _MB_SPARTNER_NO . "</option>";

    $form .= "</select></td></tr>";
    $form .= "</table>";

    return $form;
}
