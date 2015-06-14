<?php

/**
 * $Id: category.php,v 1.3 2007/09/19 20:09:35 marcan Exp $
 * Module: SmarttPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

function displayCategory($categoryObj, $level = 0)
{
    Global $xoopsModule, $smartpartner_category_handler;
    $description = $categoryObj->description();
    if (!XOOPS_USE_MULTIBYTES) {
        if (strlen($description) >= 100) {
            $description = substr($description, 0, (100 - 1)) . "...";
        }
    }
    $modify = "<a href='category.php?op=mod&categoryid=" . $categoryObj->categoryid() . "&parentid=" . $categoryObj->parentid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif' title='" . _AM_SPARTNER_CATEGORY_EDIT . "' alt='" . _AM_SPARTNER_CATEGORY_EDIT . "' /></a>";
    $delete = "<a href='category.php?op=del&categoryid=" . $categoryObj->categoryid() . "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif' title='" . _AM_SPARTNER_CATEGORY_DELETE . "' alt='" . _AM_SPARTNER_CATEGORY_DELETE . "' /></a>";

    $spaces = '';
    for ($j = 0; $j < $level; $j++) {
        $spaces .= '&nbsp;&nbsp;&nbsp;';
    }

    echo "<tr>";
    echo "<td class='even' align='lefet'>" . $spaces . "<a href='" . $categoryObj->getCategoryUrl() . "'><img src='" . XOOPS_URL . "/modules/smartpartner/images/icon/subcat.gif' alt='' />&nbsp;" . $categoryObj->name() . "</a></td>";
    echo "<td class='even' align='center'>" . $categoryObj->weight() . "</td>";
    echo "<td class='even' align='center'> $modify $delete </td>";
    echo "</tr>";
    $subCategoriesObj = $smartpartner_category_handler->getCategories(0, 0, $categoryObj->categoryid());
    if (count($subCategoriesObj) > 0) {
        $level++;
        foreach ($subCategoriesObj as $key => $thiscat) {
            displayCategory($thiscat, $level);
        }
    }
    unset($categoryObj);
}

function editcat($showmenu = false, $categoryid = 0, $nb_subcats = 4, $categoryObj = null)
{
    Global $xoopsDB, $smartpartner_category_handler, $xoopsUser, $myts, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    // If there is a parameter, and the id exists, retrieve data: we're editing a category
    if ($categoryid != 0) {

        // Creating the category object for the selected category
        //$categoryObj = new SmartpartnerCategory($categoryid);
        $categoryObj = $smartpartner_category_handler->get($categoryid);

        if ($showmenu) {
            smartpartner_adminMenu(1, _AM_SPARTNER_CATEGORIES . " > " . _AM_SPARTNER_EDITING);
        }
        echo "<br />\n";
        if ($categoryObj->notLoaded()) {
            redirect_header("category.php", 1, _AM_SPARTNER_NOCOLTOEDIT);
            exit();
        }
        smartpartner_collapsableBar('edittable', 'edittableicon', _AM_SPARTNER_CATEGORY_EDIT, _AM_SPARTNER_CATEGORY_EDIT_INFO);
    } else {

        if (!$categoryObj) {
            $categoryObj = $smartpartner_category_handler->create();
        }

        if ($showmenu) {
            smartpartner_adminMenu(1, _AM_SPARTNER_CATEGORIES . " > " . _AM_SPARTNER_CATEGORY_CREATING);
        }

        //echo "<br />\n";
        smartpartner_collapsableBar('createtable', 'createtableicon', _AM_SPARTNER_CATEGORY_CREATE, _AM_SPARTNER_CATEGORY_CREATE_INFO);
    }
    // Start category form
    $mytree = new XoopsTree($xoopsDB -> prefix("smartpartner_categories"), "categoryid", "parentid");
    $sform = new XoopsThemeForm(_AM_SPARTNER_CATEGORY, "op", xoops_getenv('PHP_SELF'));
    $sform->setExtra('enctype="multipart/form-data"');

    // Name
    $sform->addElement(new XoopsFormText(_AM_SPARTNER_CATEGORY, 'name', 50, 255, $categoryObj->name('e')), true);

    // Description
    $sform->addElement(new XoopsFormTextArea(_AM_SPARTNER_CATEGORY_DSC, 'description', $categoryObj->description('e'), 7, 60));

    // IMAGE
    $image_array = & XoopsLists :: getImgListAsArray(smartpartner_getImageDir('category'));
    $image_select = new XoopsFormSelect('', 'image', $categoryObj->image());
    $image_select -> addOption('-1', '---------------');
    $image_select -> addOptionArray($image_array);
    $image_select -> setExtra("onchange='showImgSelected(\"image3\", \"image\", \"" . 'uploads/smartpartner/images/category/' . "\", \"\", \"" . XOOPS_URL . "\")'");
    $image_tray = new XoopsFormElementTray(_AM_SPARTNER_CATEGORY_IMAGE, '&nbsp;');
    $image_tray -> addElement($image_select);
    $image_tray -> addElement(new XoopsFormLabel('', "<br /><br /><img src='" . smartpartner_getImageDir('category', false) . $categoryObj->image() . "' name='image3' id='image3' alt='' />"));
    $image_tray->setDescription(_AM_SPARTNER_CATEGORY_IMAGE_DSC);
    $sform -> addElement($image_tray);

    // IMAGE UPLOAD
    $max_size = 5000000;
    $file_box = new XoopsFormFile(_AM_SPARTNER_CATEGORY_IMAGE_UPLOAD, "image_file", $max_size);
    $file_box->setExtra("size ='45'");
    $file_box->setDescription(_AM_SPARTNER_CATEGORY_IMAGE_UPLOAD_DSC);
    $sform->addElement($file_box);

    // Weight
    $sform->addElement(new XoopsFormText(_AM_SPARTNER_CATEGORY_WEIGHT, 'weight', 4, 4, $categoryObj->weight()));

    $member_handler = &xoops_gethandler('member');
    $group_list = &$member_handler->getGroupList();

    $module_id = $xoopsModule->getVar('mid');

    // Parent Category
    ob_start();
    $mytree -> makeMySelBox("name", "weight", $categoryObj->parentid(), 1, 'parentid');
    //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
    $parent_cat_select = new XoopsFormLabel(_AM_SPARTNER_CATEGORY_PARENT, ob_get_contents());
    $parent_cat_select->setDescription(_AM_SPARTNER_CATEGORY_PARENT_DSC);
    $sform -> addElement($parent_cat_select);
    ob_end_clean();

    // Added by fx2024
    // sub Categories

    $cat_tray = new XoopsFormElementTray(_AM_SPARTNER_CATEGORY_SUBCATS_CREATE, '<br /><br />');
    $cat_tray->setDescription(_AM_SPARTNER_CATEGORY_SUBCATS_CREATE_DSC);
    for ($i = 0; $i < $nb_subcats; $i++) {
        if ($i < (isset($_POST['scname']) ? sizeof($_POST['scname']) : 0)) {
            $subname = isset($_POST['scname']) ? $_POST['scname'][$i] : '';
        }
        else {
            $subname = '';
        }
        $cat_tray->addElement(new XoopsFormText('', 'scname[' . $i . ']', 50, 255, $subname), true);

    }

    $t = new XoopsFormText('', 'nb_subcats', 3, 2);
    $l = new XoopsFormLabel('', sprintf(_AM_SPARTNER_ADD_OPT, $t->render()));
    $b = new XoopsFormButton('', 'submit', _AM_SPARTNER_ADD_OPT_SUBMIT, 'submit');
    if ($categoryid == 0) {
        $b->setExtra('onclick="this.form.elements.op.value=\'addsubcats\'"');
    }
    else {
        $b->setExtra('onclick="this.form.elements.op.value=\'mod\'"');
    }
    $r = new XoopsFormElementTray('');
    $r->addElement($l);
    $r->addElement($b);
    $cat_tray->addElement($r);

    $sform->addElement($cat_tray);
    //End of fx2024 code


    /*$gperm_handler = &xoops_gethandler('groupperm');
     $mod_perms = $gperm_handler->getGroupIds('category_moderation', $categoryid, $module_id);

     $moderators_select = new XoopsFormSelect('', 'moderators', $moderators, 5, true);
     $moderators_tray->addElement($moderators_select);

     $butt_mngmods = new XoopsFormButton('', '', 'Manage mods', 'button');
     $butt_mngmods->setExtra('onclick="javascript:small_window(\'pop.php\', 370, 350);"');
     $moderators_tray->addElement($butt_mngmods);

     $butt_delmod = new XoopsFormButton('', '', 'Delete mod', 'button');
     $butt_delmod->setExtra('onclick="javascript:deleteSelectedItemsFromList(this.form.elements[\'moderators[]\']);"');
     $moderators_tray->addElement($butt_delmod);

     $sform->addElement($moderators_tray);
     */
    $sform -> addElement(new XoopsFormHidden('categoryid', $categoryid));

    //$parentid = $categoryObj->parentid('s');

    //$sform -> addElement( new XoopsFormHidden( 'parentid', $parentid ) );

    $sform -> addElement(new XoopsFormHidden('nb_sub_yet', $nb_subcats));

    // Action buttons tray
    $button_tray = new XoopsFormElementTray('', '');

    /*for ($i = 0; $i < sizeof($moderators); $i++) {
     $allmods[] = $moderators[$i];
     }

     $hiddenmods = new XoopsFormHidden('allmods', $allmods);
     $button_tray->addElement($hiddenmods);
     */
    $hidden = new XoopsFormHidden('op', 'addcategory');
    $button_tray->addElement($hidden);

    // No ID for category -- then it's new category, button says 'Create'
    if (!$categoryid) {
        $butt_create = new XoopsFormButton('', '', _AM_SPARTNER_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new XoopsFormButton('', '', _AM_SPARTNER_CLEAR, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);

        $sform->addElement($button_tray);
        $sform->display();
        smartpartner_close_collapsable('createtable', 'createtableicon');
    } else {
        // button says 'Update'
        $butt_create = new XoopsFormButton('', '', _AM_SPARTNER_MODIFY, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
        $button_tray->addElement($butt_create);

        $butt_cancel = new XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);

        $sform->addElement($button_tray);
        $sform->display();
        smartpartner_close_collapsable('edittable', 'edittableicon');
    }
    /*
     //Added by fx2024
     if ($categoryid) {
         // TODO : displaysubcats comes from smartpartner and need to be adapted for smartpartner
         include_once XOOPS_ROOT_PATH . "/modules/smartpartner/include/displaysubcats.php";

         // TODO : displayitems comes from smartpartner and need to be adapted for smartpartner
         //include_once XOOPS_ROOT_PATH . "/modules/smartpartner/include/displayitems.php";
     }
     //end of fx2024 code
     */
    unset($hidden);
}

include("admin_header.php");
include(XOOPS_ROOT_PATH . "/class/xoopstree.php");

global $smartpartner_category_handler;

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

// Where do we start ?
$startcategory = isset($_GET['startcategory']) ? intval($_GET['startcategory']) : 0;

switch ($op) {
    case "mod":

        $categoryid = isset($_GET['categoryid']) ? intval($_GET['categoryid']) : 0;

        //Added by fx2024

        $nb_subcats = isset($_POST['nb_subcats']) ? intval($_POST['nb_subcats']) : 0;
        $nb_subcats = $nb_subcats + (isset($_POST['nb_sub_yet']) ? intval($_POST['nb_sub_yet']) : 4);
        if ($categoryid == 0) {
            $categoryid = isset($_POST['categoryid']) ? intval($_POST['categoryid']) : 0;
        }
        //end of fx2024 code

        smartpartner_xoops_cp_header();

        editcat(true, $categoryid, $nb_subcats);
        break;

    case "addcategory":
        global $_POST, $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $modify, $myts, $categoryid;

        $categoryid = (isset($_POST['categoryid'])) ? intval($_POST['categoryid']) : 0;
        $parentid = (isset($_POST['parentid'])) ? intval($_POST['parentid']) : 0;

        If ($categoryid != 0) {
            $categoryObj = $smartpartner_category_handler->get($categoryid);
        } else {
            $categoryObj = $smartpartner_category_handler->create();
        }

        // Uploading the image, if any
        // Retreive the filename to be uploaded
        if (isset ($_FILES['image_file']['name']) && $_FILES['image_file']['name'] != "") {
            $filename = $_POST["xoops_upload_file"][0];
            if (!empty($filename) || $filename != "") {
                global $xoopsModuleConfig;

                // TODO : implement smartpartner mimetype management

                $max_size = $xoopsModuleConfig['maximum_imagesize'];
                $max_imgwidth = $xoopsModuleConfig['img_max_width'];
                $max_imgheight = $xoopsModuleConfig['img_max_height'];
                $allowed_mimetypes = smartpartner_getAllowedImagesTypes();

                include_once(XOOPS_ROOT_PATH . "/class/uploader.php");

                if ($_FILES[$filename]['tmp_name'] == "" || !is_readable($_FILES[$filename]['tmp_name'])) {
                    redirect_header('javascript:history.go(-1)', 2, _AM_SPARTNER_FILEUPLOAD_ERROR);
                    exit;
                }

                $uploader = new XoopsMediaUploader(smartpartner_getImageDir('category'), $allowed_mimetypes, $max_size, $max_imgwidth, $max_imgheight);

                if ($uploader->fetchMedia($filename) && $uploader->upload()) {

                    $categoryObj->setVar('image', $uploader->getSavedFileName());

                } else {
                    redirect_header('javascript:history.go(-1)', 2, _AM_SPARTNER_FILEUPLOAD_ERROR . $uploader->getErrors());
                    exit;
                }
            }
        } else {
            if (isset($_POST['image'])) {
                $categoryObj->setVar('image', $_POST['image']);
            }
        }
        $categoryObj->setVar('parentid', (isset($_POST['parentid'])) ? intval($_POST['parentid']) : 0);

        $applyall = (isset($_POST['applyall'])) ? intval($_POST['applyall']) : 0;
        $categoryObj->setVar('weight', (isset($_POST['weight'])) ? intval($_POST['weight']) : 1);

        $categoryObj->setVar('name', $_POST['name']);

        $categoryObj->setVar('description', $_POST['description']);

        if ($categoryObj->isNew()) {
            $redirect_msg = _AM_SPARTNER_CATEGORY_CREATED;
            $redirect_to = 'category.php?op=mod';
        } else {
            $redirect_msg = _AM_SPARTNER_CATEGORY_MODIFIED;
            $redirect_to = 'category.php';
        }

        If (!$categoryObj->store()) {
            redirect_header("javascript:history.go(-1)", 3, _AM_SPARTNER_CATEGORY_SAVE_ERROR . smartpartner_formatErrors($categoryObj->getErrors()));
            exit;
        }
//Added by fx2024
        $parentCat = $categoryObj->categoryid();

        for ($i = 0; $i < sizeof($_POST['scname']); $i++) {

            if ($_POST['scname'][$i] != '') {
                $categoryObj = $smartpartner_category_handler->create();
                $categoryObj->setVar('name', $_POST['scname'][$i]);
                $categoryObj->setVar('parentid', $parentCat);

                If (!$categoryObj->store()) {
                    redirect_header("javascript:history.go(-1)", 3, _AM_SPARTNER_CATEGORY_SUBCAT_SAVE_ERROR . smartpartner_formatErrors($categoryObj->getErrors()));
                    exit;
                }

            }
        }

//end of fx2024 code
        redirect_header($redirect_to, 2, $redirect_msg);

        exit();
        break;

    //Added by fx2024

    case "addsubcats":

        $categoryid = 0;
        $nb_subcats = intval($_POST['nb_subcats']) + $_POST['nb_sub_yet'];

        smartpartner_xoops_cp_header();

        $categoryObj =& $smartpartner_category_handler->create();
        $categoryObj->setVar('name', $_POST['name']);
        $categoryObj->setVar('description', $_POST['description']);
        $categoryObj->setVar('weight', $_POST['weight']);
        if (isset($parentCat)) {
            $categoryObj->setVar('parentid', $parentCat);
        }

        editcat(true, $categoryid, $nb_subcats, $categoryObj);
        exit();

        break;
    //end of fx2024 code


    case "del":
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $_GET;

        $module_id = $xoopsModule->getVar('mid');
        $gperm_handler = &xoops_gethandler('groupperm');

        $categoryid = (isset($_POST['categoryid'])) ? intval($_POST['categoryid']) : 0;
        $categoryid = (isset($_GET['categoryid'])) ? intval($_GET['categoryid']) : $categoryid;

        //$categoryObj = new SmartpartnerCategory($categoryid);
        $categoryObj = $smartpartner_category_handler->get($categoryid);

        $confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';

        if ($confirm) {
            If (!$smartpartner_category_handler->delete($categoryObj)) {
                redirect_header("category.php", 1, _AM_SPARTNER_CATEGORY_DELETE_ERROR);
                exit;
            }

            redirect_header("category.php", 1, sprintf(_AM_SPARTNER_CATEGORY_DELETED, $name));
            exit();
        } else {
            // no confirm: show deletion condition
            $categoryid = (isset($_GET['categoryid'])) ? intval($_GET['categoryid']) : 0;
            xoops_cp_header();
            xoops_confirm(array('op' => 'del', 'categoryid' => $categoryObj->categoryid(), 'confirm' => 1, 'name' => $categoryObj->name()), 'category.php', _AM_SPARTNER_CATEGORY_DELETE . " '" . $categoryObj->name() . "'. <br /> <br />" . _AM_SPARTNER_CATEGORY_DELETE_CONFIRM, _AM_SPARTNER_DELETE);
            xoops_cp_footer();
        }
        exit();
        break;

    case "cancel":
        redirect_header("category.php", 1, sprintf(_AM_SPARTNER_BACK2IDX, ''));
        exit();

    case "default":
    default:

        smartpartner_xoops_cp_header();

        smartpartner_adminMenu(1, _AM_SPARTNER_CATEGORIES);

        echo "<br />\n";
        echo "<form><div style=\"margin-bottom: 12px;\">";
        echo "<input type='button' name='button' onclick=\"location='category.php?op=mod'\" value='" . _AM_SPARTNER_CATEGORY_CREATE . "'>&nbsp;&nbsp;";
        //echo "<input type='button' name='button' onclick=\"location='partner.php?op=mod'\" value='" . _AM_SPARTNER_CREATEITEM . "'>&nbsp;&nbsp;";
        echo "</div></form>";

        // Creating the objects for top categories
        $categoriesObj = $smartpartner_category_handler->getCategories($xoopsModuleConfig['perpage_admin'], $startcategory, 0);

        smartpartner_collapsableBar('createdcategories', 'createdcategoriesicon', _AM_SPARTNER_CATEGORIES_TITLE, _AM_SPARTNER_CATEGORIES_DSC);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td class='bg3' align='left'><b>" . _AM_SPARTNER_CATEGORY . "</b></td>";
        echo "<td class='bg3' width='65' align='center'><b>" . _AM_SPARTNER_WEIGHT . "</b></td>";
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . "</b></td>";
        echo "</tr>";
        $totalCategories = $smartpartner_category_handler->getCategoriesCount(0);
        if (count($categoriesObj) > 0) {
            foreach ($categoriesObj as $key => $thiscat) {
                displayCategory($thiscat);
            }
        } else {
            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_SPARTNER_CATEGORY_NONE . "</td>";
            echo "</tr>";
            $categoryid = '0';
        }
        echo "</table>\n";
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new XoopsPageNav($totalCategories, $xoopsModuleConfig['perpage_admin'], $startcategory, 'startcategory');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo "<br />";
        smartpartner_close_collapsable('createdcategories', 'createdcategoriesicon');
        echo "<br>";
        //editcat(false);
        break;
}

smart_modFooter();
xoops_cp_footer();
