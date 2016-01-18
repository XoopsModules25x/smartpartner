<?php

/**
 * $Id: functions.php 9889 2012-07-16 12:08:42Z beckmi $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

function smartpartner_xoops_cp_header()
{
    xoops_cp_header();

    ?>
    <script type='text/javascript' src='funcs.js'></script>
    <script type='text/javascript' src='cookies.js'></script>
    <?php

}

/**
 * Detemines if a table exists in the current db
 *
 * @param string $table the table name (without XOOPS prefix)
 * @return bool True if table exists, false if not
 *
 * @access public
 * @author xhelp development team
 */
function smartpartner_TableExists($table)
{

    $bRetVal = false;
    //Verifies that a MySQL table exists
    $xoopsDB =& Database::getInstance();
    $realname = $xoopsDB->prefix($table);
    $ret = mysql_list_tables(XOOPS_DB_NAME, $xoopsDB->conn);
    while (list($m_table) = $xoopsDB->fetchRow($ret)) {

        if ($m_table == $realname) {
            $bRetVal = true;
            break;
        }
    }
    $xoopsDB->freeRecordSet($ret);

    return ($bRetVal);
}

/**
 * Gets a value from a key in the xhelp_meta table
 *
 * @param string $key
 * @return string $value
 *
 * @access public
 * @author xhelp development team
 */
function smartpartner_GetMeta($key)
{
    $xoopsDB =& Database::getInstance();
    $sql = sprintf("SELECT metavalue FROM %s WHERE metakey=%s", $xoopsDB->prefix('smartpartner_meta'), $xoopsDB->quoteString($key));
    $ret = $xoopsDB->query($sql);
    if (!$ret) {
        $value = false;
    } else {
        list($value) = $xoopsDB->fetchRow($ret);

    }

    return $value;
}

/**
 * Sets a value for a key in the xhelp_meta table
 *
 * @param string $key
 * @param string $value
 * @return bool TRUE if success, FALSE if failure
 *
 * @access public
 * @author xhelp development team
 */
function smartpartner_SetMeta($key, $value)
{
    $xoopsDB =& Database::getInstance();
    if ($ret = smartpartner_GetMeta($key)) {
        $sql = sprintf("UPDATE %s SET metavalue = %s WHERE metakey = %s", $xoopsDB->prefix('smartpartner_meta'), $xoopsDB->quoteString($value), $xoopsDB->quoteString($key));
    } else {
        $sql = sprintf("INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)", $xoopsDB->prefix('smartpartner_meta'), $xoopsDB->quoteString($key), $xoopsDB->quoteString($value));
    }
    $ret = $xoopsDB->queryF($sql);
    if (!$ret) {
        return false;
    }

    return true;
}

function smartpartner_highlighter($matches)
{
    //$color=getmoduleoption('highlightcolor');
    $smartConfig =& smartpartner_getModuleConfig();
    $color = $smartConfig['highlight_color'];
    if (substr($color, 0, 1) != '#') {
        $color = '#' . $color;
    }

    return '<span style="font-weight: bolder; background-color: ' . $color . ';">' . $matches[0] . '</span>';
}

function smartpartner_getAllowedImagesTypes()
{
    return array('jpg/jpeg', 'image/bmp', 'image/gif', 'image/jpeg', 'image/jpg', 'image/x-png', 'image/png', 'image/pjpeg');
}

/**
 * Copy a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.0
 * @param       string   $source    The source
 * @param       string   $dest      The destination
 * @return      bool     Returns true on success, false on failure
 */
function smartpartner_copyr($source, $dest)
{
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) {
            copyr("$source/$entry", "$dest/$entry");
        } else {
            copy("$source/$entry", "$dest/$entry");
        }
    }

    // Clean up
    $dir->close();

    return true;
}

function smartpartner_getHelpPath()
{
    $smartConfig =& smartpartner_getModuleConfig();
    switch ($smartConfig['helppath_select'])
    {
        case 'docs.xoops.org' :
            return 'http://docs.xoops.org/help/spartnerh/index.htm';
            break;

        case 'inside' :
            return SMARTPARTNER_URL . "doc/";
            break;

        case 'custom' :
            return $smartConfig['helppath_custom'];
            break;
    }
}

function &smartpartner_getModuleInfo()
{
    static $smartModule;
    if (!isset($smartModule)) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == SMARTPARTNER_DIRNAME) {
            $smartModule =& $xoopsModule;
        }
        else {
            $hModule = &xoops_gethandler('module');
            $smartModule = $hModule->getByDirname(SMARTPARTNER_DIRNAME);
        }
    }

    return $smartModule;
}

function &smartpartner_getModuleConfig()
{
    static $smartConfig;
    if (!$smartConfig) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == SMARTPARTNER_DIRNAME) {
            global $xoopsModuleConfig;
            $smartConfig =& $xoopsModuleConfig;
        }
        else {
            $smartModule =& smartpartner_getModuleInfo();
            $hModConfig = &xoops_gethandler('config');
            $smartConfig = $hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));
        }
    }

    return $smartConfig;
}

function smartpartner_imageResize($src, $maxWidth, $maxHeight)
{
    $width = '';
    $height = '';
    $type = '';
    $attr = '';

    if (file_exists($src)) {
        list($width, $height, $type, $attr) = getimagesize($src);
        If ($width > $maxWidth) {
            $originalWidth = $width;
            $width = $maxWidth;
            $height = $width * $height / $originalWidth;
        }

        If ($height > $maxHeight) {
            $originalHeight = $height;
            $height = $maxHeight;
            $width = $height * $width / $originalHeight;
        }

        $attr = " width='$width' height='$height'";
    }

    return array($width, $height, $type, $attr);
}

function &smartpartner_gethandler($name, $optional = false)
{
    static $handlers;
    $name = strtolower(trim($name));
    if (!isset($handlers[$name])) {
        if (file_exists($hnd_file = SMARTPARTNER_ROOT_PATH . 'class/' . $name . '.php')) {
            require_once $hnd_file;
        }
        $class = "Smartpartner" . ucfirst($name) . 'Handler';
        if (class_exists($class)) {
            $handlers[$name] = new $class($GLOBALS['xoopsDB']);
        }
    }
    if (!isset($handlers[$name]) && !$optional) {
        trigger_error('Class <b>' . $class . '</b> does not exist<br />Handler Name: ' . $name . ' | Module path : ' . SMARTPARTNER_ROOT_PATH, E_USER_ERROR);
    }
    $ret = isset($handlers[$name]) ? $handlers[$name] : false;

    return $ret;
}

/**
 * Checks if a user is admin of SmartPartner
 *
 * smartpartner_userIsAdmin()
 *
 * @return boolean : array with userids and uname
 */
function smartpartner_userIsAdmin()
{
    global $xoopsUser;

    $result = false;
    $smartModule = smartpartner_getModuleInfo();
    $module_id = $smartModule->getVar('mid');

    if (!empty($xoopsUser)) {
        $groups = $xoopsUser->getGroups();
        $result = (in_array(XOOPS_GROUP_ADMIN, $groups)) || ($xoopsUser->isAdmin($module_id));
    }

    return $result;
}

function smartpartner_adminMenu($currentoption = 0, $breadcrumb = '')
{

    /* Nice buttons styles */
    echo "
    	<style type='text/css'>
    	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . SMARTPARTNER_URL . "/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		#buttonbar li { display:inline; margin:0; padding:0; }
		#buttonbar a { float:left; background:url('" . SMARTPARTNER_URL . "/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
		#buttonbar a span { float:left; display:block; background:url('" . SMARTPARTNER_URL . "images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
		/* Commented Backslash Hack hides rule from IE5-Mac \*/
		#buttonbar a span {float:none;}
		/* End IE5-Mac hack */
		#buttonbar a:hover span { color:#333; }
		#buttonbar #current a { background-position:0 -150px; border-width:0; }
		#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		#buttonbar a:hover { background-position:0% -150px; }
		#buttonbar a:hover span { background-position:100% -150px; }
		</style>
    ";
    // global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    global $xoopsModule, $xoopsConfig;
    $myts = &MyTextSanitizer::getInstance();

    $tblColors = Array();
    $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
    $tblColors[$currentoption] = 'current';

    //echo SMARTPARTNER_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php';

    if (file_exists(SMARTPARTNER_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        include_once SMARTPARTNER_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        include_once SMARTPARTNER_ROOT_PATH . 'language/english/modinfo.php';
    }

    if (file_exists(SMARTPARTNER_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/admin.php')) {
        include_once SMARTPARTNER_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        include_once SMARTPARTNER_ROOT_PATH . 'language/english/admin.php';
    }

    include 'menu.php';
    echo '<div id="buttontop">';
    echo '<table style="width: 100%; padding: 0;" cellspacing="0"><tr>';
    echo '<td style="font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;">';
    for ($i = 0; $i < count($headermenu); $i++) {
        echo '<a class="nobutton" href="' . $headermenu[$i]['link'] . '">' . $headermenu[$i]['title'] . '</a> ';

        if ($i < count($headermenu) - 1) {
            echo "| ";
        }
    }
    echo '</td>';
    echo '<td style="font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;">' . $breadcrumb . '</td>';
    echo '</tr></table>';
    echo '</div>';

    echo '<div id="buttonbar">';
    echo "<ul>";

    for ($i = 0; $i < count($adminmenu); $i++) {
        echo '<li id="' . $tblColors[$i] . '"><a href="' . SMARTPARTNER_URL . $adminmenu[$i]['link'] . '"><span>' . $adminmenu[$i]['title'] . '</span></a></li>';
    }
    echo '</ul></div>';
}

function smartpartner_collapsableBar($tablename = '', $iconname = '', $tabletitle = '', $tabledsc = '')
{

    global $xoopsModule;
    echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "')\";>";
    echo "<img id='$iconname' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . $tabletitle . "</h3>";
    echo "<div id='$tablename'>";
    if ($tabledsc != '') {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $tabledsc . "</span>";
    }
}

function smartpartner_openclose_collapsable($name, $icon)
{
    $urls = smartpartner_getCurrentUrls();
    $path = $urls['phpself'];

    $cookie_name = $path . '_smartpartner_collaps_' . $name;
    $cookie_name = str_replace('.', '_', $cookie_name);
    $cookie = smartpartner_getCookieVar($cookie_name, '');

    if ($cookie == 'none') {
        echo '
		<script type="text/javascript"><!--
		toggle("' . $name . '"); toggleIcon("' . $icon . '");
			//-->
		</script>
		';
    }
}

function smartpartner_close_collapsable($name, $icon)
{
    echo "</div>";
    smartpartner_openclose_collapsable($name, $icon);
}

function smartpartner_setCookieVar($name, $value, $time = 0)
{
    if ($time == 0) {
        $time = time() + 3600 * 24 * 365;
        //$time = '';
    }
    setcookie($name, $value, $time, '/');
}

function smartpartner_getCookieVar($name, $default = '')
{
    if ((isset($_COOKIE[$name])) && ($_COOKIE[$name] > '')) {
        return $_COOKIE[$name];
    } else {
        return $default;
    }
}

function smartpartner_getCurrentUrls()
{
    $http = ((strpos(XOOPS_URL, "https://")) === false) ? ("http://") : ("https://");
    $phpself = $_SERVER['PHP_SELF'];
    $httphost = $_SERVER['HTTP_HOST'];
    $querystring = $_SERVER['QUERY_STRING'];

    If ($querystring != '') {
        $querystring = '?' . $querystring;
    }

    $currenturl = $http . $httphost . $phpself . $querystring;

    $urls = array();
    $urls['http'] = $http;
    $urls['httphost'] = $httphost;
    $urls['phpself'] = $phpself;
    $urls['querystring'] = $querystring;
    $urls['full'] = $currenturl;

    return $urls;
}

function smartpartner_getCurrentPage()
{
    $urls = smartpartner_getCurrentUrls();

    return $urls['full'];
}

function smartpartner_modFooter()
{
    global $xoopsUser, $xoopsDB, $xoopsConfig;

    $hModule = &xoops_gethandler('module');
    $hModConfig = &xoops_gethandler('config');

    $smartModule = &$hModule->getByDirname('smartpartner');
    $module_id = $smartModule->getVar('mid');

    $module_name = $smartModule->getVar('dirname');
    $smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

    $module_id = $smartModule->getVar('mid');

    $versioninfo = &$hModule->get($smartModule->getVar('mid'));
    $modfootertxt = "Module " . $versioninfo->getInfo('name') . " - Version " . $versioninfo->getInfo('version') . "";
    if (!defined('_AM_SPARTNER_XOOPS_PRO')) {
        define("_AM_SPARTNER_XOOPS_PRO", "Do you need help with this module ?<br />Do you need new features not yet availale ?");
    }

    echo "<div style='padding-top: 8px; padding-bottom: 10px; text-align: center;'><a href='" . $versioninfo->getInfo('support_site_url') . "' target='_blank'><img src='" . XOOPS_URL . "/modules/smartpartner/images/spcssbutton.gif' title='" . $modfootertxt . "' alt='" . $modfootertxt . "'/></a></div>";
    echo '<div style="border: 2px solid #C2CDD6">';
    echo '<div style="font-weight:bold; padding-top: 5px; text-align: center;">' . _AM_SPARTNER_XOOPS_PRO . '<br /><a href="http://inboxinternational.com/modules/smartcontent/page.php?pageid=10"><img src="http://inboxinternational.com/images/INBOXsign150_noslogan.gif" alt="Need XOOPS Professional Services?" title="Need XOOPS Professional Services?"></a>
<a href="http://inboxinternational.com/modules/smartcontent/page.php?pageid=10"><img src="http://inboxinternational.com/images/xoops_services_pro_english.gif" alt="Need XOOPS Professional Services?" title="Need XOOPS Professional Services?"></a>
</div>';
    echo '</div>';

}

/**
 * Thanks to the NewBB2 Development Team
 */
function &smartpartner_admin_getPathStatus($item, $getStatus = false)
{
    if ($item == 'root') {
        $path = '';
    } else {
        $path = $item;
    }

    $thePath = smartpartner_getUploadDir(true, $path);

    if (empty($thePath)) return false;
    if (@is_writable($thePath)) {
        $pathCheckResult = 1;
        $path_status = _AM_SPARTNER_AVAILABLE;
    } elseif (!@is_dir($thePath)) {
        $pathCheckResult = -1;
        $path_status = _AM_SPARTNER_NOTAVAILABLE . " <a href=index.php?op=createdir&amp;path=$item>" . _AM_SPARTNER_CREATETHEDIR . '</a>';
    } else {
        $pathCheckResult = -2;
        $path_status = _AM_SPARTNER_NOTWRITABLE . " <a href=index.php?op=setperm&amp;path=$item>" . _AM_SCS_SETMPERM . '</a>';
    }
    if (!$getStatus) {
        return $path_status;
    } else {
        return $pathCheckResult;
    }
}

/**
 * Thanks to the NewBB2 Development Team
 */
function smartpartner_admin_mkdir($target)
{
    // http://www.php.net/manual/en/function.mkdir.php
    // saint at corenova.com
    // bart at cdasites dot com
    if (is_dir($target) || empty($target)) return true; // best case check first
    if (file_exists($target) && !is_dir($target)) return false;
    if (smartpartner_admin_mkdir(substr($target, 0, strrpos($target, '/')))) {
        if (!file_exists($target)) return mkdir($target);
    } // crawl back up & create dir tree
    return true;
}

/**
 * Thanks to the NewBB2 Development Team
 */
function smartpartner_admin_chmod($target, $mode = 0777)
{
    return @chmod($target, $mode);
}

function smartpartner_getUploadDir($local = true, $item = false)
{
    if ($item) {
        if ($item == 'root') {
            $item = '';
        } else {
            $item = $item . '/';
        }
    } else {
        $item = '';
    }

    If ($local) {
        return XOOPS_ROOT_PATH . "/uploads/smartpartner/$item";
    } else {
        return XOOPS_URL . "/uploads/smartpartner/$item";
    }
}

function smartpartner_getImageDir($item = '', $local = true)
{
    if ($item) {
        $item = "images/$item";
    } else {
        $item = 'images';
    }

    return smartpartner_getUploadDir($local, $item);
}

function smartpartner_formatErrors($errors = array())
{
    $ret = '';
    foreach ($errors as $key => $value)
    {
        $ret .= "<br /> - " . $value;
    }

    return $ret;

}

function smartpartner_upload_file($another = false, $withRedirect = true, &$itemObj)
{
    include_once(SMARTPARTNER_ROOT_PATH . "class/uploader.php");

    global $smartpartner_isAdmin, $xoopsModuleConfig, $smartpartner_partner_handler, $smartpartner_file_handler, $xoopsUser;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $uid = is_object($xoopsUser) ? $xoopsUser->uid() : 0;
    $session = SmartpartnerSession::singleton();
    $session->set('smartpartner_file_filename', isset($_POST['name']) ? $_POST['name'] : '');
    $session->set('smartpartner_file_description', isset($_POST['description']) ? $_POST['description'] : '');
    $session->set('smartpartner_file_status', $_POST['file_status']);
    $session->set('smartpartner_file_uid', $uid);
    $session->set('smartpartner_file_id', $id);

    if (!is_object($itemObj)) {
        $itemObj = $smartpartner_partner_handler->get($id);
    }

    $max_size = $xoopsModuleConfig['maximum_filesize'];

    $fileObj = $smartpartner_file_handler->create();
    $fileObj->setVar('name', isset($_POST['name']) ? $_POST['name'] : '');
    $fileObj->setVar('description', isset($_POST['description']) ? $_POST['description'] : '');
    $fileObj->setVar('status', isset($_POST['file_status']) ? intval($_POST['file_status']) : 1);
    $fileObj->setVar('uid', $uid);
    $fileObj->setVar('id', $itemObj->getVar('id'));
    $allowed_mimetypes = '';
    $errors = '';
    // Get available mimetypes for file uploading
/*    $hMime =& xoops_getmodulehandler('mimetype');
    if ($smartpartner_isAdmin) {
        $crit = new Criteria('mime_admin', 1);
    } else {
        $crit = new Criteria('mime_user', 1);
    }
    $mimetypes =& $hMime->getObjects($crit);
    // TODO : display the available mimetypes to the user
    */
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        if (!$ret = $fileObj->checkUpload('userfile', $allowed_mimetypes, $errors)) {
            $errorstxt = implode('<br />', $errors);

            $message = sprintf(_SMARTPARTNER_MESSAGE_FILE_ERROR, $errorstxt);
            if ($withRedirect) {
                redirect_header("file.php?op=mod&id=" . $id, 5, $message);
            } else {
                return $message;
            }
        }
    }

    // Storing the file
    if (!$fileObj->store($allowed_mimetypes)) {
        if ($withRedirect) {
            redirect_header("file.php?op=mod&id=" . $fileObj->id(), 3, _AM_SPARTNER_FILEUPLOAD_ERROR . smartpartner_formatErrors($fileObj->getErrors()));
            exit;
        } else {
            return _AM_SPARTNER_FILEUPLOAD_ERROR . smartpartner_formatErrors($fileObj->getErrors());
        }
    }
    if ($withRedirect) {
        $redirect_page = $another ? 'file.php' : 'partner.php';
        redirect_header($redirect_page . "?op=mod&id=" . $fileObj->id(), 2, _AM_SPARTNER_FILEUPLOAD_SUCCESS);
    } else {
        return true;
    }
}

function smartpartner_deleteFile($dirname)
{
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
}

function smartpartner_create_upload_folders()
{
    $hanlder =& xoops_getmodulehandler('offer', 'smartpartner');
    smart_admin_mkdir($hanlder->getImagePath());

    smart_admin_mkdir(XOOPS_ROOT_PATH . '/uploads/smartpartner/images/category');
}

?>
