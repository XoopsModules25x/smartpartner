<?php

/**
 * $Id: xoopspartners.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: Marius Scurtescu <mariuss@romanians.bc.ca>
 * Licence: GNU
 *
 * Import script from XoopsPartners to SmartPartner.
 *
 * It was tested with XoosPartners version 1.1 and SmartPartner version 1.0 beta
 *
 */

include_once("admin_header.php");

$importFromModuleName = 'XoopsPartners';
$scriptname = 'xoopspartners.php';

$op = 'start';

if (isset($_POST['op']) && ($_POST['op'] == 'go')) {
    $op = $_POST['op'];
}

if ($op == 'start') {

    smartpartner_xoops_cp_header();
    smartpartner_adminMenu(-1, _AM_SPARTNER_IMPORT);
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $result = $xoopsDB->query("select count(*) from " . $xoopsDB->prefix("partners"));
    list ($totalpartners) = $xoopsDB->fetchRow($result);
    smartpartner_collapsableBar('bottomtable', 'bottomtableicon', sprintf(_AM_SPARTNER_IMPORT_FROM, $importFromModuleName), sprintf(_AM_SPARTNER_IMPORT_MODULE_FOUND, $importFromModuleName, $totalpartners));

    $form = new XoopsThemeForm (_AM_SPARTNER_IMPORT_SETTINGS, 'import_form', XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/admin/" . $scriptname);

    // Auto-Approve
    $form->addElement(new XoopsFormLabel(_AM_SPARTNER_SMARTPARTNER_IMPORT_SETTINGS, _AM_SPARTNER_SMARTPARTNER_IMPORT_SETTINGS_VALUE));

    $form->addElement(new XoopsFormHidden('op', 'go'));
    $form->addElement(new XoopsFormButton ('', 'import', _AM_SPARTNER_IMPORT, 'submit'));
    $form->display();

    //exit ();
}

if ($op == 'go') {
    include_once("admin_header.php");

    smartpartner_xoops_cp_header();
    smartpartner_adminMenu(-1, _AM_SPARTNER_IMPORT);

    smartpartner_collapsableBar('bottomtable', 'bottomtableicon', sprintf(_AM_SPARTNER_IMPORT_FROM, $importFromModuleName), _AM_SPARTNER_IMPORT_RESULT);
    $cnt_imported_partner = 0;

    $smartpartner_partner_handler =& smartpartner_gethandler('partner');

    $resultPartners = $xoopsDB->query("select * from " . $xoopsDB->prefix("partners") . " ");
    while ($arrPartners = $xoopsDB->fetchArray($resultPartners))
    {
        extract($arrPartners, EXTR_PREFIX_ALL, 'xpartner');

        // insert partner into SmartPartner
        $partnerObj =& $smartpartner_partner_handler->create();

        if ($xpartner_status == 0) {
            $xpartner_status = _SPARTNER_STATUS_INACTIVE;
        } elseif ($xpartner_status == 1) {
            $xpartner_status = _SPARTNER_STATUS_ACTIVE;
        }

        $partnerObj->setVar('weight', $xpartner_weight);
        $partnerObj->setVar('hits', $xpartner_hits);
        $partnerObj->setVar('url', $xpartner_url);
        $partnerObj->setVar('image_url', $xpartner_image);
        $partnerObj->setVar('title', $xpartner_title);
        $partnerObj->setVar('summary', $xpartner_description);
        $partnerObj->setVar('status', $xpartner_status);

        if (!$partnerObj->store(false)) {
            echo sprintf("  " . _AM_SPARTNER_IMPORT_PARTNER_ERROR, $xpartner_title) . "<br/>";
            continue;
        } else {
            echo "&nbsp;&nbsp;" . sprintf(_AM_SPARTNER_IMPORTED_PARTNER, $partnerObj->title()) . "<br />";
            $cnt_imported_partner++;
        }

        echo "<br/>";
    }

    echo "Done.<br/>";
    echo sprintf(_AM_SPARTNER_IMPORTED_PARTNERS, $cnt_imported_partner) . "<br/>";

    //exit ();
}
echo '</div>';
smart_modFooter();
xoops_cp_footer();
exit ();
