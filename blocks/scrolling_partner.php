<?php

/**
 * $Id: scrolling_partner.php,v 1.1 2007/09/18 14:00:53 marcan Exp $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

function b_scrolling_partner_show($options)
{
    include_once (XOOPS_ROOT_PATH . "/modules/smartpartner/include/common.php");

    // Creating the partner handler object
    $smartpartner_partner_handler =& smartpartner_gethandler('partner');
    //$smartpartner_category_handler =& smartpartner_gethandler('category');

    // Randomize
    $partnersObj =& $smartpartner_partner_handler->getPartners(0, 0, _SPARTNER_STATUS_ACTIVE);
    if (count($partnersObj) > 1) {
        $key_arr = array_keys($partnersObj);
        $key_rand = array_rand($key_arr, count($key_arr));
        for ($i = 0; (($i < count($partnersObj)) && (($options[0] == 0) || ($i < $options[0]))); $i++) {
            $newObjs[$i] = $partnersObj[$key_rand[$i]];
        }
        $partnersObj = $newObjs;
    }
    /*	$cat_id = array();
            foreach($partnersObj as $partnerObj){
                $p_cats = $partnerObj->categoryid();
                $p_cat_rand = array_rand($p_cats);

                if(!in_array($p_cats[$p_cat_rand],$cat_id)){
                    $cat_id[] = $p_cats[$p_cat_rand];
                }
            }
    */
    $block = array();
    if ($partnersObj) {
        for ($i = 0; $i < count($partnersObj); $i++) {
            if ($partnersObj[$i]->image() != "" && $partnersObj[$i]->image() != "blank.png") {
                //$partner['id'] = $partnersObj[$i]->id();
                $partner['urllink'] = $partnersObj[$i]->getUrlLink('block');
                $partner['image'] = $partnersObj[$i]->getImageUrl();
                $partner['title'] = $partnersObj[$i]->title();
                $smartConfig =& smartpartner_getModuleConfig();
                $image_info = smartpartner_imageResize($partnersObj[$i]->getImagePath(), $smartConfig['img_max_width'], $smartConfig['img_max_height']);
                $block['partners'][] = $partner;
            }
        }
    }
    $block['width'] = $options[1];
    $block['height'] = $options[2];
    $block['speed'] = $options[3];
    $block['space'] = $options[4];
    $block['background'] = isset($options[5]) && $options[5] != '' ? $options[5] : 'FFFFFF';

    return $block;
}

function b_scrolling_partner_edit($options)
{
    $form = "<table border='0'>";
    $form .= "<tr><td>" . _MB_SPARTNER_BLIMIT . "</td><td>";
    $form .= "<input type='text' name='options[0]' size='16' value='" . $options[0] . "' /></td></tr>";
    $form .= "<tr><td>" . _MB_SPARTNER_BWIDTH . "</td><td>";
    $form .= "<input type='text' name='options[1]' size='16' value='" . $options[1] . "' /></td></tr>";
    $form .= "<tr><td>" . _MB_SPARTNER_BHEIGHT . "</td><td>";
    $form .= "<input type='text' name='options[2]' size='16' value='" . $options[2] . "' /></td></tr>";
    $form .= "<tr><td>" . _MB_SPARTNER_BSPEED . "</td><td>";
    $form .= "<input type='text' name='options[3]' size='16' value='" . $options[3] . "' /></td></tr>";
    $form .= "<tr><td>" . _MB_SPARTNER_BSPACE . "</td><td>";
    $form .= "<input type='text' name='options[4]' size='16' value='" . $options[4] . "' /></td></tr>";
    $form .= "<tr><td>" . _MB_SPARTNER_BBG . "</td><td>";
    $form .= "<input type='text' name='options[5]' size='16' value='" . $options[5] . "' /></td></tr>";

    $form .= "</table>";

    return $form;
}
