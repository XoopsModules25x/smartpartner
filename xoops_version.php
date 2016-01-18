<?php

/**
 * $Id: xoops_version.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// This must contain the name of the folder in which reside SmartPartner
if (!defined("SMARTPARTNER_DIRNAME")) {
    define("SMARTPARTNER_DIRNAME", 'smartpartner');
}

$modversion['name'] = _MI_SPARTNER_PARTNERS_NAME;
$modversion['version'] = 2.0;
$modversion['author'] = "The SmartFactory [www.smartfactory.ca]";
$modversion['description'] = _MI_SPARTNER_PARTNERS_DESC;
$modversion['credits'] = "Based on Raul Recio (AKA UNFOR)'s XoopsPartners module";
$modversion['license'] = "GNU General Public License (GPL)";
$modversion['help'] = "";
$modversion['official'] = 0;
$modversion['image'] = "images/module_logo.gif";
$modversion['dirname'] = SMARTPARTNER_DIRNAME;

// Added by marcan for the About page in admin section
$modversion['adminMenu'] = "smartpartner_adminMenu";
$modversion['modFooter'] = "smartpartner_modFooter";
$modversion['developer_lead'] = "marcan [Marc-André Lanciault]";
$modversion['developer_contributor'] = "Andy Cleff, Raul Recio, chapi, Marco, Mariuss, outch, M0nty, Christian, Philou, M4d3L, solo, GiJOE, Predator, phppp, AmiCalmant, hsalazar, Aidan Lister, fx2024, Zabou";
$modversion['developer_website_url'] = "http://www.smartfactory.ca";
$modversion['developer_website_name'] = "SmartFactory.ca";
$modversion['developer_email'] = "marcan@smartfactory";
$modversion['status_version'] = "RC 1";
$modversion['status'] = "Release Candidate";
$modversion['date'] = "2007-09-19";

$modversion['warning'] = _MI_SPARTNER_WARNING_RC;

$modversion['people']['developers'][] = "marcan (Marc-André Lanciault)";
$modversion['people']['developers'][] = "felix (Felix Tousignant)";

$modversion['people']['testers'][] = "Andy Cleff";

//$modversion['people']['translators'][] = "translator 1";

//$modversion['people']['documenters'][] = "documenter 1";

//$modversion['people']['other'][] = "other 1";

$modversion['demo_site_url'] = "http://www.smartfactory.ca/modules/smartpartner/";
$modversion['demo_site_name'] = "SmartFactory.ca";
$modversion['support_site_url'] = "http://dev.xoops.org/modules/xfmod/project/?group_id=1109";
$modversion['support_site_name'] = "SmartPartner on XOOPS Developpers Forge";
$modversion['submit_bug'] = "http://dev.xoops.org/modules/xfmod/tracker/?func=add&group_id=1109&atid=581";
$modversion['submit_feature'] = "http://dev.xoops.org/modules/xfmod/tracker/?func=add&group_id=1109&atid=584";

$modversion['author_word'] = "
<b>The SmartFactory</b> would like to thank everyone involved in this project.<br />
<br />
A big thanks to chapi, Marco, Mariuss, oucth, Christian, Philou, solo, yoyo2021 and AmiCalmant for their feedback on the module.<br />
<br />
Thanks to hsalazar who originally created the admin interface the SmartModules are using. A lot of modules are now using it and it's because of your work. Thank you Horacio !<br />
<br />
Thanks to GiJoe for the blocks and groups management pages. Your code is very usefull to a lot of developers !<br />
<br />
Thanks to Zabou for supporting me ;-)<br />
<br />
Enjoy <b>SmartPartner</b> !
";

// Tables
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "smartpartner_partner";
$modversion['tables'][1] = "smartpartner_categories";
$modversion['tables'][2] = "smartpartner_meta";
$modversion['tables'][3] = "smartpartner_partner_cat_link";
$modversion['tables'][4] = "smartpartner_offer";
$modversion['tables'][5] = "smartpartner_files";
$modversion['tables'][6] = "smartpartner_mimetypes";
// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

// Blocks
$modversion['blocks'][1]['file'] = "partners_list.php";
$modversion['blocks'][1]['name'] = _MI_SPARTNER_BLOCK_PARTNERS_LIST;
$modversion['blocks'][1]['description'] = _MI_SPARTNER_BLOCK_PARTNERS_LIST_DESC;
$modversion['blocks'][1]['show_func'] = "b_partners_list_show";
$modversion['blocks'][1]['edit_func'] = "b_partners_list_edit";
$modversion['blocks'][1]['options'] = "1|1|5|1|hits|DESC|1";
$modversion['blocks'][1]['template'] = 'partners_block_list.html';

$modversion['blocks'][2]['file'] = "scrolling_partner.php";
$modversion['blocks'][2]['name'] = _MI_SPARTNER_BLOCK_SCROLLING_PARTNER;
$modversion['blocks'][2]['description'] = _MI_SPARTNER_BLOCK_SCROLLING_PARTNER_DESC;
$modversion['blocks'][2]['show_func'] = "b_scrolling_partner_show";
$modversion['blocks'][2]['edit_func'] = "b_scrolling_partner_edit";
$modversion['blocks'][2]['options'] = "0|300|150|2|5";
$modversion['blocks'][2]['template'] = 'scrolling_partner.html';

$modversion['blocks'][3]['file'] = "categories_list.php";
$modversion['blocks'][3]['name'] = _MI_SPARTNER_BLOCK_CATEGORIES_LIST;
$modversion['blocks'][3]['description'] = _MI_SPARTNER_BLOCK_CATEGORIES_LIST_DESC;
$modversion['blocks'][3]['show_func'] = "b_categories_list_show";
$modversion['blocks'][3]['edit_func'] = "b_categories_list_edit";
$modversion['blocks'][3]['options'] = "name|ASC|1";
$modversion['blocks'][3]['template'] = 'categories_list.html';

$modversion['blocks'][4]['file'] = "recent_partners.php";
$modversion['blocks'][4]['name'] = _MI_SPARTNER_BLOCK_PARTNERS_RECENT;
$modversion['blocks'][4]['description'] = _MI_SPARTNER_BLOCK_PARTNERS_RECENT_DESC;
$modversion['blocks'][4]['show_func'] = "b_recent_partners_show";
$modversion['blocks'][4]['edit_func'] = "b_recent_partners_edit";
$modversion['blocks'][4]['options'] = "1|1|5";
$modversion['blocks'][4]['template'] = 'recent_partners.html';

$modversion['blocks'][5]['file'] = "recent_offers.php";
$modversion['blocks'][5]['name'] = _MI_SPARTNER_BLOCK_OFFERS_RECENT;
$modversion['blocks'][5]['description'] = _MI_SPARTNER_BLOCK_OFFERS_RECENT_DESC;
$modversion['blocks'][5]['show_func'] = "b_recent_offers_show";
$modversion['blocks'][5]['edit_func'] = "b_recent_offers_edit";
$modversion['blocks'][5]['options'] = "1|1|5";
$modversion['blocks'][5]['template'] = 'recent_offers.html';

$modversion['blocks'][6]['file'] = "random_offer.php";
$modversion['blocks'][6]['name'] = _MI_SPARTNER_BLOCK_OFFERS_RANDOM;
$modversion['blocks'][6]['description'] = _MI_SPARTNER_BLOCK_OFFERS_RANDOM_DESC;
$modversion['blocks'][6]['show_func'] = "b_random_offer_show";
$modversion['blocks'][6]['edit_func'] = "b_random_offer_edit";
$modversion['blocks'][6]['options'] = "1|1|5";
$modversion['blocks'][6]['template'] = 'random_offer.html';
/*
$modversion['blocks'][2]['file'] = "random_partner.php";
$modversion['blocks'][2]['name'] = _MI_SPARTNER_BLOCK_RANDOM_PARTNER;
$modversion['blocks'][2]['description']	= _MI_SPARTNER_BLOCK_RANDOM_PARTNER_DESC;
$modversion['blocks'][2]['show_func'] = "b_random_partner_show";
$modversion['blocks'][2]['edit_func'] = "b_random_partner_edit";
$modversion['blocks'][2]['options']	= "1|1|1";
$modversion['blocks'][2]['template'] = 'random_partner.html';*/

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "smartpartner_search";

// Menu
$modversion['hasMain'] = 1;

// Page Awareness
$modversion['pages'][1]['name'] = _MI_SPARTNER_PAGE_PARTNER;
$modversion['pages'][1]['url'] = "partner.php";
$modversion['pages'][2]['name'] = _MI_SPARTNER_PAGE_INDEX;
$modversion['pages'][2]['url'] = "index.php";

// Templates
$i = 1;

$modversion['templates'][$i]['file'] = 'smartpartner_header.html';
$modversion['templates'][$i]['description'] = 'Module header';
$i++;

$modversion['templates'][$i]['file'] = 'smartpartner_footer.html';
$modversion['templates'][$i]['description'] = 'Module footer';
$i++;

$modversion['templates'][$i]['file'] = 'smartpartner_index.html';
$modversion['templates'][$i]['description'] = 'Partners main page';
$i++;

$modversion['templates'][$i]['file'] = 'smartpartner_partner.html';
$modversion['templates'][$i]['description'] = "Full partner's page";
$i++;

$modversion['templates'][$i]['file'] = 'smartpartner_join.html';
$modversion['templates'][$i]['description'] = 'Apply to be a partner form';
$i++;

$modversion['templates'][$i]['file'] = 'smartpartner_pdf.html';
$modversion['templates'][$i]['description'] = 'Create a PDF page';
$i++;

$modversion['templates'][$i]['file'] = 'smartpartner_offer.html';
$modversion['templates'][$i]['description'] = 'Displays offers';
$i++;

//config categoories
$modversion['configcat'][1]['nameid'] = 'format_options';
$modversion['configcat'][1]['name'] = '_MI_SPARTNER_CAT_FOR_OPT';
$modversion['configcat'][1]['description'] = '_MI_SPARTNER_CAT_FOR_OPT_DSC';

$modversion['configcat'][2]['nameid'] = 'permissions';
$modversion['configcat'][2]['name'] = '_MI_SPARTNER_CAT_PERM';
$modversion['configcat'][2]['description'] = '_MI_SPARTNER_CAT_PERM_DSC';

$modversion['configcat'][3]['nameid'] = 'other';
$modversion['configcat'][3]['name'] = '_MI_SPARTNER_CAT_OTHER';
$modversion['configcat'][3]['description'] = '_MI_SPARTNER_CAT_OTHER_DSC';

// Config options
$i = 0;
$i++;
$modversion['config'][$i]['name'] = 'allowsubmit';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_ALLOWSUBMIT';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_ALLOWSUBMITDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'permissions';

$i++;
$modversion['config'][$i]['name'] = 'anonpost';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_ANONPOST';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_ANONPOSTDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'permissions';

$i++;
$modversion['config'][$i]['name'] = 'autoapprove_submitted';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_AUTOAPPROVE';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_AUTOAPPROVE_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'permissions';

$i++;
$modversion['config'][$i]['name'] = 'useimagenavpage';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_USEIMAGENAVPAGE';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_USEIMAGENAVPAGEDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'maximum_imagesize';
$modversion['config'][$i]['title'] = '_MI_SSPARTNER_MAX_SIZE';
$modversion['config'][$i]['description'] = '_MI_SSPARTNER_MAX_SIZEDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '1000000';

$i++;
$modversion['config'][$i]['name'] = 'img_max_width';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_IMG_MAX_WIDTH';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_IMG_MAX_WIDTH_DSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '150';
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'img_max_height';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_IMG_MAX_HEIGHT';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_IMG_MAX_HEIGHT_DSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '150';
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'reclick_time';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_RECLICK';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 86400;
$modversion['config'][$i]['options'] = array('_MI_SPARTNER_HOUR' => '3600', '_MI_SPARTNER_3HOURS' => '10800', '_MI_SPARTNER_5HOURS' => '18000', '_MI_SPARTNER_10HOURS' => '36000', '_MI_SPARTNER_DAY' => '86400');
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'percat_user';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_PERCAT_USER';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_PERCAT_USER_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;
$modversion['config'][$i]['options'] = array('2' => 2, '3' => 3, '5' => 5, '10' => 10, '20' => 20);
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'offer_sort';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_SORT';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_SORT_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['options'] = array(_MI_SPARTNER_SORT_WEIGHT => 'weight',
                                             _MI_SPARTNER_SORT_DATE_PUB => 'date_pub',
                                             _MI_SPARTNER_SORT_DATE_END => 'date_end',
                                             _MI_SPARTNER_SORT_ALPHA => 'title'
);
$modversion['config'][$i]['default'] = 'date_pub';
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'offer_order';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_ORDER';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_ORDER_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['options'] = array(_MI_SPARTNER_ORDER_ASC => 'ASC',
                                             _MI_SPARTNER_ORDER_DESC => 'DESC'
);
$modversion['config'][$i]['default'] = 'DESC';
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'updated_period';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_UPDATE_PERIOD';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_UPDATE_PERIODDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '7';
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'partview_msg';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_PARTVIEW_MSG';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_PARTVIEW_MSG_DSC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_SPARTNER_PARTVIEW_MSG_DEF;
$modversion['config'][$i]['category'] = 'other';
/*
//temporarly disabled. To make this preference work,
// index.php have to use the page navigator
$i++;
$modversion['config'][$i]['name'] = 'perpage_user';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_PERPAGE_USER';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_PERPAGE_USER_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 5;
$modversion['config'][$i]['options'] = array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50);
$modversion['config'][$i]['category'] = 'format_options';
*/

$i++;
$modversion['config'][$i]['name'] = 'perpage_admin';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_PERPAGE_ADMIN';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_PERPAGE_ADMIN_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 5;
$modversion['config'][$i]['options'] = array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50);
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'orphan_first';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_ORPHAN_FIRST';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_ORPHAN_FIRST_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'index_sortby';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_INDEX_SORTBY';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_INDEX_SORTBY_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'weight';
$modversion['config'][$i]['options'] = array('_MI_SPARTNER_ID' => 'id', '_MI_SPARTNER_HITS' => 'hits', '_MI_SPARTNER_TITLE' => 'title', '_MI_SPARTNER_WEIGHT' => 'weight');
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'welcomemsg';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_WELCOMEMSG';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_WELCOMEMSG_DSC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_SPARTNER_WELCOMEMSG_DEF;
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'allowed_ext';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_ALLOWED_EXT';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_ALLOWED_EXT_DSC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'doc|pdf|xls|ppt';
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'maximum_filesize';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_MAX_SIZE';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_MAX_SIZEDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '1000000';
$member_handler = &xoops_gethandler('member');
$group_list = &$member_handler->getGroupList();
foreach ($group_list as $key => $group) {
    $groups[$group] = $key;
}
$i++;
$modversion['config'][$i]['name'] = 'default_full_view';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_DEF_FULL';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_DEF_FULL_DSC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['options'] = $groups;
$modversion['config'][$i]['default'] = array(1, 2);
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'default_part_view';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_DEF_PART';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_DEF_PART_DSC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['options'] = $groups;
$modversion['config'][$i]['default'] = array(3);
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'stats_group';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_STATS_GROUP';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_STATS_GROUP_DSC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['options'] = $groups;
$modversion['config'][$i]['default'] = $groups;
$modversion['config'][$i]['category'] = 'other';

$i++;
$modversion['config'][$i]['name'] = 'highlight_color';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_HIGHLIGHT_COLOR';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_HIGHLIGHT_COLORDSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '#FFFF80';
$modversion['config'][$i]['category'] = 'format_options';

$i++;
$modversion['config'][$i]['name'] = 'hide_module_name';
$modversion['config'][$i]['title'] = '_MI_SPARTNER_HIDE_MOD_NAME';
$modversion['config'][$i]['description'] = '_MI_SPARTNER_HIDE_MOD_NAMEDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
$modversion['config'][$i]['category'] = 'format_options';

// Notification
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'smartpartner_notify_iteminfo';

$modversion['notification']['category'][1]['name'] = 'global_partner';
$modversion['notification']['category'][1]['title'] = _MI_SPARTNER_PARTNER_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_SPARTNER_PARTNER_NOTIFY_DSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php');

$modversion['notification']['category'][2]['name'] = 'partner';
$modversion['notification']['category'][2]['title'] = _MI_SPARTNER_PARTNER_NOTIFY;
$modversion['notification']['category'][2]['description'] = _MI_SPARTNER_PARTNER_NOTIFY_DSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('partner.php');
$modversion['notification']['category'][2]['item_name'] = 'id';

$modversion['notification']['event'][1]['name'] = 'submitted';
$modversion['notification']['event'][1]['category'] = 'global_partner';
$modversion['notification']['event'][1]['admin_only'] = 1;
$modversion['notification']['event'][1]['title'] = _MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY_CAP;
$modversion['notification']['event'][1]['description'] = _MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY_DSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_partner_submitted';
$modversion['notification']['event'][1]['mail_subject'] = _MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY_SBJ;

$modversion['notification']['event'][2]['name'] = 'approved';
$modversion['notification']['event'][2]['category'] = 'partner';
$modversion['notification']['event'][2]['invisible'] = 1;
$modversion['notification']['event'][2]['title'] = _MI_SPARTNER_PARTNER_APPROVED_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _MI_SPARTNER_PARTNER_APPROVED_NOTIFY_CAP;
$modversion['notification']['event'][2]['description'] = _MI_SPARTNER_PARTNER_APPROVED_NOTIFY_DSC;
$modversion['notification']['event'][2]['mail_template'] = 'partner_approved';
$modversion['notification']['event'][2]['mail_subject'] = _MI_SPARTNER_PARTNER_APPROVED_NOTIFY_SBJ;

$modversion['notification']['event'][3]['name'] = 'new_partner';
$modversion['notification']['event'][3]['category'] = 'global_partner';
$modversion['notification']['event'][3]['title'] = _MI_SPARTNER_GLOBAL_PARTNER_NEW_NOTIFY;
$modversion['notification']['event'][3]['caption'] = _MI_SPARTNER_GLOBAL_PARTNER_NEW_NOTIFY_CAP;
$modversion['notification']['event'][3]['description'] = _MI_SPARTNER_GLOBAL_PARTNER_NEW_NOTIFY_DSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_partner_new';
$modversion['notification']['event'][3]['mail_subject'] = _MI_SPARTNER_GLOBAL_PARTNER_NEW_NOTIFY_SBJ;

$modversion['notification']['event'][4]['name'] = 'new_offer';
$modversion['notification']['event'][4]['category'] = 'global_partner';
$modversion['notification']['event'][4]['title'] = _MI_SPARTNER_GLOBAL_OFFER_NEW_NOTIFY;
$modversion['notification']['event'][4]['caption'] = _MI_SPARTNER_GLOBAL_OFFER_NEW_NOTIFY_CAP;
$modversion['notification']['event'][4]['description'] = _MI_SPARTNER_GLOBAL_OFFER_NEW_NOTIFY_DSC;
$modversion['notification']['event'][4]['mail_template'] = 'global_offer_new';
$modversion['notification']['event'][4]['mail_subject'] = _MI_SPARTNER_GLOBAL_OFFER_NEW_NOTIFY_SBJ;
