<?php

/**
 * $Id: index.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

/**
 *Ceci nous produira un tableau de forme:
 *
 *PartnersArray[] =
 *     PartnersArray[TopCat1][info] = (nom, description)
 *     PartnersArray[TopCat1][partners] = array de partner (via fct get_partners_array())
 *      PartnersArray[TopCat1][subcats][0][info] = (nom, description)
 *      PartnersArray[TopCat1][subcats][0][partners] = array de partner
 *      PartnersArray[TopCat1][subcats][0][subcats]....
 *    Ainsi de suite
 *
 *ex: PartnersArray[TopCat1][partners][0][nom] contiendra le nom du 1er partenaire de TopCat1
 *
 */

/**
 *Loop inside the array of all partners to match with current category
 *
 *param $categoryid - id of the current category
 *return array of partners for the current category
 */
function get_partners_array($categoryid)
{
    global $every_partners_array, $count, $xoopsModuleConfig, $view_category_id;
    $partners = array();
    foreach ($every_partners_array as $partnerObj) {
        if (in_array($categoryid, explode('|', $partnerObj->categoryid())) && ($view_category_id || (!$view_category_id && sizeof($partners) < $xoopsModuleConfig['percat_user']))) {
            $partner = $partnerObj->toArray('index');
            $partners[] = $partner;
        }
    }

    return $partners;
}

/**
 *Loop inside the array of all categories to find subcats for current category
 *recusrive function: for each subcat found, call to function getcontent to
 *get partners and subcats within it
 *
 *param $categoryid - id of the current category
 *return array of subcats for the current category
 */
function get_subcats($every_categories_array, $categoryid, $level)
{

    //global $every_categories_array;
    $subcatArray = array();
    $level++;

    foreach ($every_categories_array as $subcatObj) {

        if ($subcatObj->parentid() == $categoryid) {
            $subcatArray[] = get_cat_content($every_categories_array, $subcatObj, $level);
        }
    }

    return $subcatArray;
}

/**
 *Retrieve content for the current category
 *
 *param $categoryid - id of the current category
 *return array of content for the current category
 */
function get_cat_content($every_categories_array, $categoryObj, $level)
{
    $category = array();
    $decalage = '';
    /*for($i=0;$i<$level;$i++){
         $decalage .= '--';
     }*/
    $decalage .= ' ';
    $category['title'] = $decalage . '' . $categoryObj->name();
    $category['categoryid'] = $categoryObj->categoryid();
    $category['description'] = $categoryObj->description();
    $category['link_view'] = $categoryObj->getCategoryUrl();
    $category['partners'] = get_partners_array($categoryObj->categoryid());
    $category['image_url'] = $categoryObj->getImageUrl(true);
    $category['subcats'] = get_subcats($every_categories_array, $categoryObj->categoryid(), $level);

    return $category;
}

include "header.php";
$xoopsOption['template_main'] = 'smartpartner_index.html';
include XOOPS_ROOT_PATH . "/header.php";
include "footer.php";

// At which record shall we start
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

$view_category_id = isset($_GET['view_category_id']) ? intval($_GET['view_category_id']) : 0;

$partners_total = $smartpartner_partner_handler->getPartnerCount();

if ($xoopsModuleConfig['index_sortby'] == 'title' || $xoopsModuleConfig['index_sortby'] == 'weight') {
    $order = 'ASC';
}
else {
    $order = 'DESC';
}
//Retreive all records from database
$every_categories_array = $smartpartner_category_handler->getCategories(0, 0, -1, 'weight', 'ASC', true);
$every_partners_array = $smartpartner_partner_handler->getPartnersForIndex(-1, _SPARTNER_STATUS_ACTIVE, $xoopsModuleConfig['index_sortby'], $order);

$partnersArray = array();

//display All categories and partners
if (!$view_category_id) {
    //get orphan first if preference says so
    if ($xoopsModuleConfig['orphan_first']) {
        $partnersArray['orphan']['partners'] = get_partners_array(0);
    }

    //get all categories and content
    foreach ($every_categories_array as $categoryObj) {
        if ($categoryObj->parentid() == 0) {
            $partnersArray[] = get_cat_content($every_categories_array, $categoryObj, 0);
        }
    }

    //get orphan last if preference says so
    if (!$xoopsModuleConfig['orphan_first']) {
        $partnersArray['orphan']['partners'] = get_partners_array(0);
    }

    $categoryPath = '';
}

    //viewing a specific category
else {
    $currentCategoryObj = $every_categories_array[$view_category_id];
    $partnersArray[] = get_cat_content($every_categories_array, $currentCategoryObj, 0);

    if (!$partnersArray[0]['partners'] && !$partnersArray[0]['subcats']) {
        redirect_header(SMARTPARTNER_URL, 3, _MD_SPARTNER_CATEGORY_EMPTY);
    }
    // Retreiving the category path
    $categoryPath = $currentCategoryObj->getCategoryPath();
}

//$partners_total_onpage = $count;.partners
$xoopsTpl->assign('partners', $partnersArray);

//end new code to implement categories

// Partners Navigation Bar
//$pagenav = new XoopsPageNav($partners_total_onpage, $xoopsModuleConfig['perpage_user'], $start, 'start', '');
//$xoopsTpl->assign('pagenav', '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>');
$xoopsTpl->assign('view_deteils_cat', _MD_SPARTNER_DETAIL_CAT);
$xoopsTpl->assign('on_index_page', $view_category_id == 0);
$xoopsTpl->assign('sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign("displayjoin", $xoopsModuleConfig['allowsubmit'] && (is_object($xoopsUser) || $xoopsModuleConfig['anonpost']));
$xoopsTpl->assign("img_max_width", $xoopsModuleConfig['img_max_width']);
$xoopsTpl->assign('module_home', '<a href="' . SMARTPARTNER_URL . '">' . $smartpartner_moduleName . '</a>');
$xoopsTpl->assign('categoryPath', $categoryPath);
$xoopsTpl->assign('lang_intro_text', $myts->displayTarea($xoopsModuleConfig['welcomemsg']));
$xoopsTpl->assign('lang_partner', _MD_SPARTNER_PARTNER);
$xoopsTpl->assign('lang_desc', _MD_SPARTNER_DESCRIPTION);
$xoopsTpl->assign('lang_edit', _MD_SPARTNER_EDIT);
$xoopsTpl->assign('lang_delete', _MD_SPARTNER_DELETE);
$xoopsTpl->assign('lang_hits', _MD_SPARTNER_HITS);
$xoopsTpl->assign('lang_join', _MD_SPARTNER_JOIN);
$xoopsTpl->assign('lang_no_partners', _MD_SPARTNER_NOPART);
$xoopsTpl->assign('lang_main_partner', _MD_SPARTNER_PARTNERS);
$xoopsTpl->assign('lang_readmore', _MD_SPARTNER_READMORE);
$xoopsTpl->assign('partview_msg', $xoopsModuleConfig['partview_msg']);
if (!$xoopsModuleConfig['hide_module_name']) {
    $xoopsTpl->assign('lang_partnerstitle', $myts->displayTarea($xoopsModule->getVar('name')));
}
include_once XOOPS_ROOT_PATH . '/footer.php';
