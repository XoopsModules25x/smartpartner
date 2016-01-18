<?php

/**
 * $Id: partner.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
function showfiles($partnerObj)
{
    // UPLOAD FILES
    //include_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/functions.php';
    global $xoopsModule, $smartpartner_file_handler;

    smartpartner_collapsableBar('filetable', 'filetableicon', _AM_SPARTNER_FILES_LINKED);
    $filesObj =& $smartpartner_file_handler->getAllFiles($partnerObj->id());
    if (count($filesObj) > 0) {
        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='50' class='bg3' align='center'><b>ID</b></td>";
        echo "<td width='150' class='bg3' align='left'><b>" . _AM_SPARTNER_FILENAME . "</b></td>";
        echo "<td class='bg3' align='left'><b>" . _AM_SPARTNER_DESCRIPTION . "</b></td>";
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_HITS . "</b></td>";
        echo "<td width='100' class='bg3' align='center'><b>" . _AM_SPARTNER_UPLOADED_DATE . "</b></td>";
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . "</b></td>";
        echo "</tr>";

        for ($i = 0; $i < count($filesObj); $i++) {
            $modify = "<a href='file.php?op=mod&fileid=" . $filesObj[$i]->fileid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_SPARTNER_EDITFILE . "' alt='" . _AM_SPARTNER_EDITFILE . "' /></a>";
            $delete = "<a href='file.php?op=del&fileid=" . $filesObj[$i]->fileid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_SPARTNER_DELETEFILE . "' alt='" . _AM_SPARTNER_DELETEFILE . "'/></a>";
            if ($filesObj[$i]->status() == 0) {
                $not_visible = "<img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/no.gif'/>";
            }
            else {
                $not_visible = '';
            }
            echo "<tr>";
            echo "<td class='head' align='center'>" . $filesObj[$i]->getVar('fileid') . "</td>";
            echo "<td class='odd' align='left'>" . $not_visible . $filesObj[$i]->getFileLink() . "</td>";
            echo "<td class='even' align='left'>" . $filesObj[$i]->description() . "</td>";
            echo "<td class='even' align='center'>" . $filesObj[$i]->counter() . "";
            echo "<td class='even' align='center'>" . $filesObj[$i]->datesub() . "</td>";
            echo "<td class='even' align='center'> $modify $delete </td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br >";
    } else {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . _AM_SPARTNER_NOFILE . "</span>";
    }

    echo "<form><div style=\"margin-bottom: 24px;\">";
    echo "<input type='button' name='button' onclick=\"location='file.php?op=mod&id=" . $partnerObj->id() . "'\" value='" . _AM_SPARTNER_UPLOAD_FILE_NEW . "'>&nbsp;&nbsp;";
    echo "</div></form>";

    smartpartner_close_collapsable('filetable', 'filetableicon');
}

function editpartner($showmenu = false, $id = 0)
{
    global $xoopsDB, $smartpartner_partner_handler, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;
    if (!isset($smartpartner_partner_handler)) {
        $smartpartner_partner_handler =& smartpartner_gethandler('partner');
    }
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    // If there is a parameter, and the id exists, retrieve data: we're editing a partner
    if ($id != 0) {
        // Creating the partner object
        $partnerObj = new SmartpartnerPartner($id);

        if ($partnerObj->notLoaded()) {
            redirect_header("partner.php", 1, _AM_SPARTNER_NOPARTNERSELECTED);
            exit();
        }

        switch ($partnerObj->status()) {

            case _SPARTNER_STATUS_SUBMITTED :
                $breadcrumb_action1 = _AM_SPARTNER_SUBMITTED_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_APPROVING;
                $page_title = _AM_SPARTNER_SUBMITTED_TITLE;
                $page_info = _AM_SPARTNER_SUBMITTED_INFO;
                $button_caption = _AM_SPARTNER_APPROVE;
                $new_status = _SPARTNER_STATUS_ACTIVE;
                break;

            case _SPARTNER_STATUS_ACTIVE :
                $breadcrumb_action1 = _AM_SPARTNER_ACTIVE_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_EDITING;
                $page_title = _AM_SPARTNER_ACTIVE_EDITING;
                $page_info = _AM_SPARTNER_ACTIVE_EDITING_INFO;
                $button_caption = _AM_SPARTNER_MODIFY;
                $new_status = _SPARTNER_STATUS_ACTIVE;
                break;

            case _SPARTNER_STATUS_INACTIVE :
                $breadcrumb_action1 = _AM_SPARTNER_INACTIVE_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_EDITING;
                $page_title = _AM_SPARTNER_INACTIVE_EDITING;
                $page_info = _AM_SPARTNER_INACTIVE_EDITING_INFO;
                $button_caption = _AM_SPARTNER_MODIFY;
                $new_status = _SPARTNER_STATUS_INACTIVE;
                break;

            case _SPARTNER_STATUS_REJECTED :
                $breadcrumb_action1 = _AM_SPARTNER_REJECTED_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_EDITING;
                $page_title = _AM_SPARTNER_REJECTED_EDITING;
                $page_info = _AM_SPARTNER_REJECTED_EDITING_INFO;
                $button_caption = _AM_SPARTNER_MODIFY;
                $new_status = _SPARTNER_STATUS_REJECTED;
                break;

            case "default" :
            default :
                break;
        }

        If ($showmenu) {
            smartpartner_adminMenu(2, $breadcrumb_action1 . " > " . $breadcrumb_action2);
        }

        echo "<br />\n";
        smartpartner_collapsableBar('editpartner', 'editpartmericon', $page_title, $page_info);
    } else {
        // there's no parameter, so we're adding a partner
        $partnerObj =& $smartpartner_partner_handler->create();
        $breadcrumb_action1 = _AM_SPARTNER_PARTNERS;
        $breadcrumb_action2 = _AM_SPARTNER_CREATE;
        $button_caption = _AM_SPARTNER_CREATE;
        $new_status = _SPARTNER_STATUS_ACTIVE;
        If ($showmenu) {
            smartpartner_adminMenu(2, $breadcrumb_action1 . " > " . $breadcrumb_action2);
        }

        smartpartner_collapsableBar('addpartner', 'addpartmericon', _AM_SPARTNER_PARTNER_CREATING, _AM_SPARTNER_PARTNER_CREATING_DSC);
    }

    // PARTNER FORM
    $sform = new XoopsThemeForm(_AM_SPARTNER_PARTNERS, "op", xoops_getenv('PHP_SELF'));
    $sform->setExtra('enctype="multipart/form-data"');

    // TITLE
    $title_text = new XoopsFormText(_AM_SPARTNER_TITLE, 'title', 50, 255, $partnerObj->title('e'));
    $sform->addElement($title_text, true);

    // Parent Category
    $mytree = new SmartTree($xoopsDB -> prefix("smartpartner_categories"), "categoryid", "parentid");
    ob_start();
    $mytree -> makeMySelBox("name", "weight", explode('|', $partnerObj->categoryid()), 0, 'categoryid', '', true);
    //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
    $parent_cat_select = new XoopsFormLabel(_AM_SPARTNER_CATEGORY_BELONG, ob_get_contents());
    $parent_cat_select->setDescription(_AM_SPARTNER_BELONG_CATEGORY_DSC);
    $sform -> addElement($parent_cat_select);
    ob_end_clean();

    // LOGO
    $logo_array = & XoopsLists :: getImgListAsArray(smartpartner_getImageDir());
    $logo_select = new XoopsFormSelect('', 'image', $partnerObj->image());
    $logo_select -> addOption('-1', '---------------');
    $logo_select -> addOptionArray($logo_array);
    $logo_select -> setExtra("onchange='showImgSelected(\"image3\", \"image\", \"" . 'uploads/' . SMARTPARTNER_DIRNAME . '/images' . "\", \"\", \"" . XOOPS_URL . "\")'");
    $logo_tray = new XoopsFormElementTray(_AM_SPARTNER_LOGO, '&nbsp;');
    $logo_tray -> addElement($logo_select);
    $logo_tray -> addElement(new XoopsFormLabel('', "<br /><br /><img src='" . smartpartner_getImageDir('', false) . $partnerObj->image() . "' name='image3' id='image3' alt='' />"));
    $logo_tray->setDescription(_AM_SPARTNER_LOGO_DSC);
    $sform -> addElement($logo_tray);

    // LOGO UPLOAD
    $max_size = 5000000;
    $file_box = new XoopsFormFile(_AM_SPARTNER_LOGO_UPLOAD, "logo_file", $max_size);
    $file_box->setExtra("size ='45'");
    $file_box->setDescription(sprintf(_AM_SPARTNER_LOGO_UPLOAD_DSC, $xoopsModuleConfig['img_max_width'], $xoopsModuleConfig['img_max_height']));
    $sform->addElement($file_box);

    // IMAGE_URL
    $image_url_text = new XoopsFormText(_CO_SPARTNER_IMAGE_URL, 'image_url', 50, 255, $partnerObj->image_url());
    $image_url_text->setDescription(_CO_SPARTNER_IMAGE_URL_DSC);
    $sform->addElement($image_url_text, false);

    // URL
    $url_text = new XoopsFormText(_AM_SPARTNER_URL, 'url', 50, 255, $partnerObj->url());
    $url_text->setDescription(_AM_SPARTNER_URL_DSC);
    $sform->addElement($url_text, false);

    // SUMMARY
    $summary_text = new XoopsFormTextArea(_AM_SPARTNER_SUMMARY, 'summary', $partnerObj->summary(0, 'e'), 7, 60);
    $summary_text->setDescription(_AM_SPARTNER_SUMMARY_DSC);
    $sform->addElement($summary_text, true);

    // SHOW summary on partner page
    $showsum_radio = new XoopsFormRadioYN(_AM_SPARTNER_SHOW_SUMMARY, 'showsummary', $partnerObj->getVar('showsummary'));
    $showsum_radio->setDescription(_AM_SPARTNER_SHOW_SUMMARY_DSC);
    $sform -> addElement($showsum_radio);

    // DESCRIPTION
    $description_text = new XoopsFormDhtmlTextArea(_AM_SPARTNER_DESCRIPTION, 'description', $partnerObj->description(0, 'e'), 15, 60);
    $description_text->setDescription(_AM_SPARTNER_DESCRIPTION_DSC);
    $sform->addElement($description_text, false);

    // CONTACT_NAME
    $contact_name_text = new XoopsFormText(_CO_SPARTNER_CONTACT_NAME, 'contact_name', 50, 255, $partnerObj->contact_name('e'));
    $contact_name_text->setDescription(_CO_SPARTNER_CONTACT_NAME_DSC);
    $sform->addElement($contact_name_text, false);

    // CONTACT_EMAIL
    $contact_email_text = new XoopsFormText(_CO_SPARTNER_CONTACT_EMAIL, 'contact_email', 50, 255, $partnerObj->contact_email('e'));
    $contact_email_text->setDescription(_CO_SPARTNER_CONTACT_EMAIL_DSC);
    $sform->addElement($contact_email_text, false);

    // EMAIL_PRIV
    $email_priv_radio = new XoopsFormRadioYN(_CO_SPARTNER_CONTACT_EMAILPRIV, 'email_priv', $partnerObj->email_priv('e'));
    $email_priv_radio->setDescription(_CO_SPARTNER_CONTACT_EMAILPRIV_DSC);
    $sform -> addElement($email_priv_radio);

    // CONTACT_PHONE
    $contact_phone_text = new XoopsFormText(_CO_SPARTNER_CONTACT_PHONE, 'contact_phone', 50, 255, $partnerObj->contact_phone('e'));
    $contact_phone_text->setDescription(_CO_SPARTNER_CONTACT_PHONE_DSC);
    $sform->addElement($contact_phone_text, false);

    // PHONE_PRIV
    $phone_priv_radio = new XoopsFormRadioYN(_CO_SPARTNER_CONTACT_PHONEPRIV, 'phone_priv', $partnerObj->phone_priv('e'));
    $phone_priv_radio->setDescription(_CO_SPARTNER_CONTACT_PHONEPRIV_DSC);
    $sform -> addElement($phone_priv_radio);

    // ADRESS
    //$adress_text = new XoopsFormText(_CO_SPARTNER_ADRESS, 'adress', 50, 255, $partnerObj->adress('e'));
    $adress_text = new XoopsFormTextArea(_CO_SPARTNER_ADRESS, 'adress', $partnerObj->adress('e'));
    $adress_text->setDescription(_CO_SPARTNER_ADRESS_DSC);
    $sform->addElement($adress_text, false);

    // ADRESS_PRIV
    $adress_priv_radio = new XoopsFormRadioYN(_CO_SPARTNER_CONTACT_ADRESSPRIV, 'adress_priv', $partnerObj->adress_priv('e'));
    $adress_priv_radio->setDescription(_CO_SPARTNER_CONTACT_ADRESSPRIV_DSC);
    $sform -> addElement($adress_priv_radio);

    // STATUS
    $options = $partnerObj->getAvailableStatus();
    $status_select = new XoopsFormSelect(_AM_SPARTNER_STATUS, 'status', $new_status);
    $status_select->addOptionArray($options);
    $status_select->setDescription(_AM_SPARTNER_STATUS_DSC);
    $sform -> addElement($status_select);

    // WEIGHT
    $weight_text = new XoopsFormText(_AM_SPARTNER_WEIGHT, 'weight', 4, 4, $partnerObj->weight());
    $weight_text->setDescription(_AM_SPARTNER_WEIGHT_DSC);
    $sform->addElement($weight_text);

    //perms
    global $smartpermissions_handler;
    include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectpermission.php';
    $smartpermissions_handler = new SmartobjectPermissionHandler($smartpartner_partner_handler);

    if ($partnerObj->id() != 0) {
        $grantedGroups = $smartpermissions_handler->getGrantedGroups('full_view', $partnerObj->id());
    } else {
        $grantedGroups = $xoopsModuleConfig['default_full_view'];
    }
    $full_view_select = new XoopsFormSelectGroup(_CO_SPARTNER_FULL_PERM_READ, 'full_view', true, $grantedGroups, 5, true);
    $full_view_select->setDescription(_CO_SPARTNER_FULL_PERM_READ_DSC);
    $sform->addElement($full_view_select);

    if ($partnerObj->id() != 0) {
        $partGrantedGroups = $smartpermissions_handler->getGrantedGroups('partial_view', $partnerObj->id());
    } else {
        $partGrantedGroups = $xoopsModuleConfig['default_part_view'];
    }
    $part_view_select = new XoopsFormSelectGroup(_CO_SPARTNER_PART_PERM_READ, 'partial_view', true, $partGrantedGroups, 5, true);
    $part_view_select->setDescription(_CO_SPARTNER_PART_PERM_READ_DSC);
    $sform->addElement($part_view_select);

    // Partner id
    $sform->addElement(new XoopsFormHidden('id', $partnerObj->id()));

    $button_tray = new XoopsFormElementTray('', '');
    $hidden = new XoopsFormHidden('op', 'addpartner');
    $button_tray->addElement($hidden);

    $sform->addElement(new XoopsFormHidden('original_status', $partnerObj->status()));

    if (!$id) {
        // there's no id? Then it's a new partner
        // $button_tray -> addElement( new XoopsFormButton( '', 'mod', _AM_SPARTNER_CREATE, 'submit' ) );
        $butt_create = new XoopsFormButton('', '', _AM_SPARTNER_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addpartner\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new XoopsFormButton('', '', _AM_SPARTNER_CLEAR, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    } else {
        // else, we're editing an existing partner
        // $button_tray -> addElement( new XoopsFormButton( '', 'mod', _AM_SPARTNER_MODIFY, 'submit' ) );
        $butt_create = new XoopsFormButton('', '', $button_caption, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addpartner\'"');
        $button_tray->addElement($butt_create);

        $butt_cancel = new XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    }

    $sform->addElement($button_tray);
    $sform->display();
    unset($hidden);
    if (!$id) {
        smartpartner_close_collapsable('addpartner', 'addpartnericon');
    } else {
        smartpartner_close_collapsable('editpartner', 'editpartnericon');
    }
    if ($id != 0) {
        showfiles($partnerObj);
    }
}

include("admin_header.php");
include(XOOPS_ROOT_PATH . "/class/xoopstree.php");

$op = '';
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

// Where shall we start ?
$startpartner = isset($_GET['startpartner']) ? intval($_GET['startpartner']) : 0;

if (!isset($smartpartner_partner_handler)) {
    $smartpartner_partner_handler =& smartpartner_gethandler('partner');
}
/* -- Available operations -- */
switch ($op) {
    case "add":

        xoops_cp_header();
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

        editpartner(true, 0);
        break;

    case "mod":

        Global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;
        $id = (isset($_GET['id'])) ? $_GET['id'] : 0;

        xoops_cp_header();
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

        editpartner(true, $id);
        break;

    case "addpartner":
        global $xoopsUser;

        if (!$xoopsUser) {
            if ($xoopsModuleConfig['anonpost'] == 1) {
                $uid = 0;
            } else {
                redirect_header("index.php", 3, _NOPERM);
                exit();
            }
        } else {
            $uid = $xoopsUser->uid();
        }

        $id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;

        // Creating the partner object
        If ($id != 0) {
            $partnerObj = new SmartpartnerPartner($id);
        } else {
            $partnerObj = $smartpartner_partner_handler->create();
        }

        // Uploading the logo, if any
        // Retreive the filename to be uploaded
        if ($_FILES['logo_file']['name'] != "") {
            $filename = $_POST["xoops_upload_file"][0];
            if (!empty($filename) || $filename != "") {
                global $xoopsModuleConfig;

                $max_size = 10000000;
                $max_imgwidth = $xoopsModuleConfig['img_max_width'];
                $max_imgheight = $xoopsModuleConfig['img_max_height'];
                $allowed_mimetypes = null; //smartpartner_getAllowedMimeTypes();

                include_once(XOOPS_ROOT_PATH . "/class/uploader.php");

                if ($_FILES[$filename]['tmp_name'] == "" || !is_readable($_FILES[$filename]['tmp_name'])) {
                    redirect_header('javascript:history.go(-1)', 2, _CO_SPARTNER_FILE_UPLOAD_ERROR);
                    exit;
                }

                $uploader = new XoopsMediaUploader(smartpartner_getImageDir(), $allowed_mimetypes, $max_size, $max_imgwidth, $max_imgheight);

                // TODO : prefix the image file with the partnerid, but for that we need to first save the partner to get partnerid...
                // $uploader->setTargetFileName($partnerObj->partnerid() . "_" . $_FILES['logo_file']['name']);

                if ($uploader->fetchMedia($filename) && $uploader->upload()) {

                    $partnerObj->setVar('image', $uploader->getSavedFileName());

                } else {
                    redirect_header('javascript:history.go(-1)', 2, _CO_SPARTNER_FILE_UPLOAD_ERROR . $uploader->getErrors());
                    exit;
                }
            }
        } else {
            $partnerObj->setVar('image', $_POST['image']);
        }

        // Putting the values in the partner object
        $partnerObj->setVar('id', (isset($_POST['id'])) ? intval($_POST['id']) : 0);
        $partnerObj->setVar('categoryid', (isset($_POST['categoryid'])) ? implode('|', $_POST['categoryid']) : array(0));
        $partnerObj->setVar('status', isset($_POST['status']) ? intval($_POST['status']) : 0);
        $partnerObj->setVar('title', $_POST['title']);
        $partnerObj->setVar('summary', $_POST['summary']);
        $partnerObj->setVar('image_url', $_POST['image_url']);
        $partnerObj->setVar('description', $_POST['description']);
        $partnerObj->setVar('contact_name', $_POST['contact_name']);
        $partnerObj->setVar('contact_email', $_POST['contact_email']);
        $partnerObj->setVar('contact_phone', $_POST['contact_phone']);
        $partnerObj->setVar('adress', $_POST['adress']);
        $partnerObj->setVar('url', $_POST['url']);
        $partnerObj->setVar('weight', (isset($_POST['weight'])) ? intval($_POST['weight']) : 0);
        $partnerObj->setVar('email_priv', (isset($_POST['email_priv'])) ? intval($_POST['email_priv']) : 0);
        $partnerObj->setVar('phone_priv', (isset($_POST['phone_priv'])) ? intval($_POST['phone_priv']) : 0);
        $partnerObj->setVar('adress_priv', (isset($_POST['adress_priv'])) ? intval($_POST['adress_priv']) : 0);
        $partnerObj->setVar('showsummary', (isset($_POST['showsummary'])) ? intval($_POST['showsummary']) : 0);

        $redirect_msgs = $partnerObj->getRedirectMsg($_POST['original_status'], $_POST['status']);

        // Storing the partner
        If (!$partnerObj->store()) {
            redirect_header("javascript:history.go(-1)", 3, $redirect_msgs['error'] . smartpartner_formatErrors($partnerObj->getErrors()));
            exit;
        }

        If (($_POST['original_status'] == _SPARTNER_STATUS_SUBMITTED) || ($_POST['status'] == _SPARTNER_STATUS_ACTIVE)) {
            $partnerObj->sendNotifications(array(_SPARTNER_NOT_PARTNER_APPROVED));
        }
        if ($partnerObj->isNew()) {
            $partnerObj->sendNotifications(array(_SPARTNER_NOT_PARTNER_NEW));
        }
        redirect_header("partner.php", 2, $redirect_msgs['success']);

        exit();
        break;

    case "del":
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $_GET;

        $module_id = $xoopsModule->getVar('mid');
        $gperm_handler = &xoops_gethandler('groupperm');

        $id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
        $id = (isset($_GET['id'])) ? intval($_GET['id']) : $id;

        $partnerObj = new SmartpartnerPartner($id);

        $confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
        $title = (isset($_POST['title'])) ? $_POST['title'] : '';

        if ($confirm) {
            If (!$smartpartner_partner_handler->delete($partnerObj)) {
                redirect_header("partner.php", 2, _AM_SPARTNER_PARTNER_DELETE_ERROR);
                exit;
            }

            redirect_header("partner.php", 2, sprintf(_AM_SPARTNER_PARTNER_DELETE_SUCCESS, $partnerObj->title()));
            exit();
        } else {
            // no confirm: show deletion condition
            $id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
            xoops_cp_header();
            xoops_confirm(array('op' => 'del', 'id' => $partnerObj->id(), 'confirm' => 1, 'name' => $partnerObj->title()), 'partner.php', _AM_SPARTNER_DELETETHISP . " <br />'" . $partnerObj->title() . "' <br /> <br />", _AM_SPARTNER_DELETE);
            xoops_cp_footer();
        }

        exit();
        break;

    case "default":
    default:
        smartpartner_xoops_cp_header();

        smartpartner_adminMenu(2, _AM_SPARTNER_PARTNERS);

        echo "<br />\n";
        echo "<form><div style=\"margin-bottom: 12px;\">";
        echo "<input type='button' name='button' onclick=\"location='partner.php?op=mod'\" value='" . _AM_SPARTNER_PARTNER_CREATE . "'>&nbsp;&nbsp;";
        echo "</div></form>";

        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule;

        smartpartner_collapsableBar('partners', 'partnersicon', _AM_SPARTNER_ACTIVE_PARTNERS, _AM_SPARTNER_ACTIVE_PARTNERS_DSC);

        // Get the total number of published PARTNER
        $totalpartners = $smartpartner_partner_handler->getPartnerCount(_SPARTNER_STATUS_ACTIVE);
        // creating the partner objects that are published
        $partnersObj = $smartpartner_partner_handler->getPartners($xoopsModuleConfig['perpage_admin'], $startpartner);
        $totalPartnersOnPage = count($partnersObj);

        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
        echo "<tr>";
        echo "<td class='bg3' width='200px' align='left'><b>" . _AM_SPARTNER_NAME . "</b></td>";
        echo "<td width='' class='bg3' align='left'><b>" . _AM_SPARTNER_INTRO . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_HITS . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_STATUS . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . "</b></td>";
        echo "</tr>";
        if ($totalpartners > 0) {
            for ($i = 0; $i < $totalPartnersOnPage; $i++) {

                $modify = "<a href='partner.php?op=mod&id=" . $partnersObj[$i]->id() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_SPARTNER_EDITPARTNER . "' alt='" . _AM_SPARTNER_EDITPARTNER . "' /></a>&nbsp;";
                $delete = "<a href='partner.php?op=del&id=" . $partnersObj[$i]->id() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_SPARTNER_DELETEPARTNER . "' alt='" . _AM_SPARTNER_DELETEPARTNER . "'/></a>&nbsp;";

                echo "<tr>";
                echo "<td class='head' align='left'><a href='" . SMARTPARTNER_URL . "partner.php?id=" . $partnersObj[$i]->id() . "'><img src='" . SMARTPARTNER_URL . "images/links/partner.gif' alt=''/>&nbsp;" . $partnersObj[$i]->title() . "</a></td>";
                echo "<td class='even' align='left'>" . $partnersObj[$i]->summary(100) . "</td>";
                echo "<td class='even' align='center'>" . $partnersObj[$i]->hits() . "</td>";
                echo "<td class='even' align='center'>" . $partnersObj[$i]->getStatusName() . "</td>";
                echo "<td class='even' align='center'> " . $modify . $delete . "</td>";
                echo "</tr>";
            }
        } else {
            $id = 0;
            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_SPARTNER_NOPARTNERS . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        echo "<br />\n";

        $pagenav = new XoopsPageNav($totalpartners, $xoopsModuleConfig['perpage_admin'], $startpartner, 'startpartner');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        smartpartner_close_collapsable('partners', 'partnersicon');

        break;
}
smart_modFooter();
xoops_cp_footer();
