<?php
/**
 * $Id: offer.php,v 1.2 2007/09/19 20:09:35 marcan Exp $
 * Module: SmartCourse
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

function editoffer($showmenu = false, $offerid = 0, $fct = '')
{
    global $smartpartner_offer_handler, $smartpartner_category_handler, $smartpartner_partner_handler;

    $offerObj = $smartpartner_offer_handler->get($offerid);

    if ($offerObj->isNew()) {
        $breadcrumb = _AM_SPARTNER_OFFERS . " > " . _AM_SPARTNER_CREATINGNEW;
        $title = _AM_SPARTNER_OFFER_CREATE;
        $info = _AM_SPARTNER_OFFER_CREATE_INFO;
        $collaps_name = 'offercreate';
        $form_name = _AM_SPARTNER_OFFER_CREATE;
        $submit_button_caption = null;
        $offerObj->setVar('date_sub', time());
    }

    else {
        $breadcrumb = _AM_SPARTNER_OFFERS . " > " . _AM_SPARTNER_EDITING;
        $title = _AM_SPARTNER_OFFER_EDIT;
        $info = _AM_SPARTNER_OFFER_EDIT_INFO;
        $collaps_name = 'offeredit';
        $form_name = _AM_SPARTNER_OFFER_EDIT;
        $submit_button_caption = null;
    }
    $partnerObj = $smartpartner_partner_handler->get($offerObj->getVar('partnerid', 'e'));
    $offerObj->hideFieldFromForm('date_sub');
    $menuTab = 3;

    if ($showmenu) {
        smart_adminMenu($menuTab, $breadcrumb);
    }
    echo "<br />\n";
    smart_collapsableBar($collaps_name, $title, $info);

    $sform = $offerObj->getForm($form_name, 'addoffer', false, $submit_button_caption);
    if ($fct == 'app') {
        $sform->addElement(new XoopsFormHidden('fct', 'app'));
    }
    $sform->display();
    smart_close_collapsable($collaps_name);
}

include_once("admin_header.php");

$op = '';
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
    case "mod":
        $offerid = isset($_GET['offerid']) ? intval($_GET['offerid']) : 0;
        $fct = isset($_GET['fct']) ? $_GET['fct'] : '';
        smart_xoops_cp_header();

        editoffer(true, $offerid, $fct);
        break;

    case "addoffer":
        include_once XOOPS_ROOT_PATH . "/modules/smartobject/class/smartobjectcontroller.php";

        $controller = new SmartObjectController($smartpartner_offer_handler);
        $offerObj = $controller->storeSmartObject();
        $fct = isset($_POST['fct']) ? $_POST['fct'] : '';

        if ($offerObj->hasError()) {
            redirect_header($smart_previous_page, 3, _CO_SOBJECT_SAVE_ERROR . $offerObj->getHtmlErrors());
        } else {
            $partnerObj = $smartpartner_partner_handler->get($offerObj->getVar('partnerid', 'e'));
            $partnerObj->setUpdated();
            if ($_POST['offerid'] == '') {
                $offerObj->sendNotifications(array(_SPARTNER_NOT_OFFER_NEW));
            }

            redirect_header(smart_get_page_before_form(), 3, _CO_SOBJECT_SAVE_SUCCESS);
        }
        exit;
        break;

    case "del":
        include_once XOOPS_ROOT_PATH . "/modules/smartobject/class/smartobjectcontroller.php";
        $controller = new SmartObjectController($smartpartner_offer_handler);
        $controller->handleObjectDeletion();
        break;

    case "default":
    default:
        include_once(XOOPS_ROOT_PATH . "/modules/smartobject/include/functions.php");
        smart_xoops_cp_header();

        smart_adminMenu(3, _AM_SPARTNER_OFFERS);

        echo "<br />\n";
        echo "<form><div style=\"margin-bottom: 12px;\">";
        echo "<input type='button' name='button' onclick=\"location='offer.php?op=mod'\" value='" . _AM_SPARTNER_OFFER_CREATE . "'>&nbsp;&nbsp;";
        echo "</div></form>";

        smart_collapsableBar('createdoffers', _AM_SPARTNER_OFFERS, _AM_SPARTNER_OFFERS_DSC);

        include_once XOOPS_ROOT_PATH . "/modules/smartobject/class/smartobjecttable.php";
        $objectTable = new SmartObjectTable($smartpartner_offer_handler);
        $objectTable->addFilter('partnerid', 'getPartnerList');
        $objectTable->addFilter('status', 'getStatusList');
        $objectTable->addColumn(new SmartObjectColumn('title', 'left'));
        $objectTable->addColumn(new SmartObjectColumn('partnerid', 'center', 100));
        $objectTable->addColumn(new SmartObjectColumn('status', 'center', 100));
        $objectTable->render();

        echo "<br />";
        smart_close_collapsable('createdoffers');
        echo "<br>";

        break;
}

smart_modFooter();
xoops_cp_footer();
