<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

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
 * @param  string $table the table name (without XOOPS prefix)
 * @return bool   True if table exists, false if not
 *
 * @access public
 * @author xhelp development team
 */
function smartpartner_TableExists($table)
{
    $bRetVal = false;
    //Verifies that a MySQL table exists
    $xoopsDB  = XoopsDatabaseFactory::getDatabaseConnection();
    $realname = $xoopsDB->prefix($table);
    $sql      = 'SHOW TABLES FROM ' . XOOPS_DB_NAME;
    $ret      = $xoopsDB->queryF($sql);
    while (list($m_table) = $xoopsDB->fetchRow($ret)) {
        if ($m_table == $realname) {
            $bRetVal = true;
            break;
        }
    }
    $xoopsDB->freeRecordSet($ret);

    return $bRetVal;
}

/**
 * Gets a value from a key in the xhelp_meta table
 *
 * @param  string $key
 * @return string $value
 *
 * @access public
 * @author xhelp development team
 */
function smartpartner_GetMeta($key)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
    $sql     = sprintf('SELECT metavalue FROM %s WHERE metakey=%s', $xoopsDB->prefix('smartpartner_meta'), $xoopsDB->quoteString($key));
    $ret     = $xoopsDB->query($sql);
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
 * @param  string $key
 * @param  string $value
 * @return bool   TRUE if success, FALSE if failure
 *
 * @access public
 * @author xhelp development team
 */
function smartpartner_SetMeta($key, $value)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
    if ($ret = smartpartner_GetMeta($key)) {
        $sql = sprintf('UPDATE %s SET metavalue = %s WHERE metakey = %s', $xoopsDB->prefix('smartpartner_meta'), $xoopsDB->quoteString($value), $xoopsDB->quoteString($key));
    } else {
        $sql = sprintf('INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)', $xoopsDB->prefix('smartpartner_meta'), $xoopsDB->quoteString($key), $xoopsDB->quoteString($value));
    }
    $ret = $xoopsDB->queryF($sql);
    if (!$ret) {
        return false;
    }

    return true;
}

/**
 * @param $matches
 * @return string
 */
function smartpartner_highlighter($matches)
{
    //$color=getmoduleoption('highlightcolor');
    $smartConfig =& smartpartner_getModuleConfig();
    $color       = $smartConfig['highlight_color'];
    if (0 !== strpos($color, '#')) {
        $color = '#' . $color;
    }

    return '<span style="font-weight: bolder; background-color: ' . $color . ';">' . $matches[0] . '</span>';
}

/**
 * @return array
 */
function smartpartner_getAllowedImagesTypes()
{
    return array('jpg/jpeg', 'image/bmp', 'image/gif', 'image/jpeg', 'image/jpg', 'image/x-png', 'image/png', 'image/pjpeg');
}

/**
 * Copy a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @param  string $source The source
 * @param  string $dest   The destination
 * @return bool Returns true on success, false on failure
 * @throws
 */
function smartpartner_copyr($source, $dest)
{
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
//        mkdir($dest);
        if (!@mkdir($dest) && !is_dir($dest)) {
            throw Exception("Couldn't create this directory: " . $dest);
        }
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry === '.' || $entry === '..') {
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

/**
 * @return string
 */
function smartpartner_getHelpPath()
{
    $smartConfig =& smartpartner_getModuleConfig();
    switch ($smartConfig['helppath_select']) {
        case 'docs.xoops.org':
            return 'http://docs.xoops.org/help/spartnerh/index.htm';
            break;

        case 'inside':
            return SMARTPARTNER_URL . 'doc/';
            break;

        case 'custom':
            return $smartConfig['helppath_custom'];
            break;
    }
}

/**
 * @return mixed|null
 */
function smartpartner_getModuleInfo()
{
    static $smartModule;
    if (!isset($smartModule)) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == SMARTPARTNER_DIRNAME) {
            $smartModule = $xoopsModule;
        } else {
            $hModule     = xoops_getHandler('module');
            $smartModule = $hModule->getByDirname(SMARTPARTNER_DIRNAME);
        }
    }

    return $smartModule;
}

/**
 * @return mixed
 */
function smartpartner_getModuleConfig()
{
    static $smartConfig;
    if (!$smartConfig) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == SMARTPARTNER_DIRNAME) {
            global $xoopsModuleConfig;
            $smartConfig = $xoopsModuleConfig;
        } else {
            $smartModule =& smartpartner_getModuleInfo();
            $hModConfig  = xoops_getHandler('config');
            $smartConfig = $hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));
        }
    }

    return $smartConfig;
}

/**
 * @param $src
 * @param $maxWidth
 * @param $maxHeight
 * @return array
 */
function smartpartner_imageResize($src, $maxWidth, $maxHeight)
{
    $width  = '';
    $height = '';
    $type   = '';
    $attr   = '';

    if (file_exists($src)) {
        list($width, $height, $type, $attr) = getimagesize($src);
        if ($width > $maxWidth) {
            $originalWidth = $width;
            $width         = $maxWidth;
            $height        = $width * $height / $originalWidth;
        }

        if ($height > $maxHeight) {
            $originalHeight = $height;
            $height         = $maxHeight;
            $width          = $height * $width / $originalHeight;
        }

        $attr = " width='$width' height='$height'";
    }

    return array($width, $height, $type, $attr);
}

/**
 * @param       $name
 * @param  bool $optional
 * @return bool
 */
function smartpartner_gethandler($name, $optional = false)
{
    static $handlers;
    $name = strtolower(trim($name));
    if (!isset($handlers[$name])) {
        if (file_exists($hnd_file = SMARTPARTNER_ROOT_PATH . 'class/' . $name . '.php')) {
            require_once $hnd_file;
        }
        $class = 'Smartpartner' . ucfirst($name) . 'Handler';
        if (class_exists($class)) {
            $handlers[$name] = new $class($GLOBALS['xoopsDB']);
        }
    }
    if (!isset($handlers[$name]) && !$optional) {
        trigger_error('Class <b>' . $class . '</b> does not exist<br />Handler Name: ' . $name . ' | Module path: ' . SMARTPARTNER_ROOT_PATH, E_USER_ERROR);
    }
    $ret = isset($handlers[$name]) ? $handlers[$name] : false;

    return $ret;
}

/**
 * Checks if a user is admin of SmartPartner
 *
 * smartpartner_userIsAdmin()
 *
 * @return boolean: array with userids and uname
 */
function smartpartner_userIsAdmin()
{
    global $xoopsUser;

    $result      = false;
    $smartModule = smartpartner_getModuleInfo();
    $module_id   = $smartModule->getVar('mid');

    if (!empty($xoopsUser)) {
        $groups = $xoopsUser->getGroups();
        $result = in_array(XOOPS_GROUP_ADMIN, $groups) || $xoopsUser->isAdmin($module_id);
    }

    return $result;
}

/**
 * @param string $tablename
 * @param string $iconname
 * @param string $tabletitle
 * @param string $tabledsc
 */
function smartpartner_collapsableBar($tablename = '', $iconname = '', $tabletitle = '', $tabledsc = '')
{
    global $xoopsModule;
    echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "')\";>";
    echo "<img id='$iconname' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/close12.gif alt='' /></a>&nbsp;" . $tabletitle . '</h3>';
    echo "<div id='$tablename'>";
    if ($tabledsc != '') {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $tabledsc . '</span>';
    }
}

/**
 * @param $name
 * @param $icon
 */
function smartpartner_openclose_collapsable($name, $icon)
{
    $urls = smartpartner_getCurrentUrls();
    $path = $urls['phpself'];

    $cookie_name = $path . '_smartpartner_collaps_' . $name;
    $cookie_name = str_replace('.', '_', $cookie_name);
    $cookie      = smartpartner_getCookieVar($cookie_name, '');

    if ($cookie === 'none') {
        echo '
        <script type="text/javascript"><!--
        toggle("' . $name . '"); toggleIcon("' . $icon . '");
            //-->
        </script>
        ';
    }
}

/**
 * @param $name
 * @param $icon
 */
function smartpartner_close_collapsable($name, $icon)
{
    echo '</div>';
    smartpartner_openclose_collapsable($name, $icon);
}

/**
 * @param     $name
 * @param     $value
 * @param int $time
 */
function smartpartner_setCookieVar($name, $value, $time = 0)
{
    if ($time == 0) {
        $time = time() + 3600 * 24 * 365;
        //$time = '';
    }
    setcookie($name, $value, $time, '/');
}

/**
 * @param         $name
 * @param  string $default
 * @return string
 */
function smartpartner_getCookieVar($name, $default = '')
{
    if (isset($_COOKIE[$name]) && ($_COOKIE[$name] > '')) {
        return $_COOKIE[$name];
    } else {
        return $default;
    }
}

/**
 * @return array
 */
function smartpartner_getCurrentUrls()
{
    $http        = (strpos(XOOPS_URL, 'https://') === false) ? 'http://' : 'https://';
    $phpself     = $_SERVER['PHP_SELF'];
    $httphost    = $_SERVER['HTTP_HOST'];
    $querystring = $_SERVER['QUERY_STRING'];

    if ($querystring != '') {
        $querystring = '?' . $querystring;
    }

    $currenturl = $http . $httphost . $phpself . $querystring;

    $urls                = array();
    $urls['http']        = $http;
    $urls['httphost']    = $httphost;
    $urls['phpself']     = $phpself;
    $urls['querystring'] = $querystring;
    $urls['full']        = $currenturl;

    return $urls;
}

/**
 * @return mixed
 */
function smartpartner_getCurrentPage()
{
    $urls = smartpartner_getCurrentUrls();

    return $urls['full'];
}

function smartpartner_modFooter()
{
    global $xoopsUser, $xoopsDB, $xoopsConfig;

    $hModule    = xoops_getHandler('module');
    $hModConfig = xoops_getHandler('config');

    $smartModule = &$hModule->getByDirname('smartpartner');
    $module_id   = $smartModule->getVar('mid');

    $module_name = $smartModule->getVar('dirname');
    $smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

    $module_id = $smartModule->getVar('mid');

    $versioninfo  = &$hModule->get($smartModule->getVar('mid'));
    $modfootertxt = 'Module ' . $versioninfo->getInfo('name') . ' - Version ' . $versioninfo->getInfo('version') . '';
    if (!defined('_AM_SPARTNER_XOOPS_PRO')) {
        define('_AM_SPARTNER_XOOPS_PRO', 'Do you need help with this module ?<br />Do you need new features not yet availale?');
    }

    echo "<div style='padding-top: 8px; padding-bottom: 10px; text-align: center;'><a href='" . $versioninfo->getInfo('support_site_url') . "' target='_blank'><img src='" . XOOPS_URL . "/modules/smartpartner/assets/images/spcssbutton.gif' title='" . $modfootertxt . "' alt='" . $modfootertxt . "'/></a></div>";
    echo '<div style="border: 2px solid #C2CDD6;">';
    echo '<div style="font-weight:bold; padding-top: 5px; text-align: center;">' . _AM_SPARTNER_XOOPS_PRO . '<br /><a href="http://inboxinternational.com/modules/smartcontent/page.php?pageid=10"><img src="http://inboxinternational.com/images/INBOXsign150_noslogan.gif" alt="Need XOOPS Professional Services?" title="Need XOOPS Professional Services?"></a>
<a href="http://inboxinternational.com/modules/smartcontent/page.php?pageid=10"><img src="http://inboxinternational.com/images/xoops_services_pro_english.gif" alt="Need XOOPS Professional Services?" title="Need XOOPS Professional Services?"></a>
</div>';
    echo '</div>';
}

/**
 * Thanks to the NewBB2 Development Team
 * @param             $item
 * @param  bool       $getStatus
 * @return int|string
 */
function smartpartner_admin_getPathStatus($item, $getStatus = false)
{
    if ($item === 'root') {
        $path = '';
    } else {
        $path = $item;
    }

    $thePath = smartpartner_getUploadDir(true, $path);

    if (empty($thePath)) {
        return false;
    }
    if (@is_writable($thePath)) {
        $pathCheckResult = 1;
        $path_status     = _AM_SPARTNER_AVAILABLE;
    } elseif (!@is_dir($thePath)) {
        $pathCheckResult = -1;
        $path_status     = _AM_SPARTNER_NOTAVAILABLE . " <a href=index.php?op=createdir&amp;path=$item>" . _AM_SPARTNER_CREATETHEDIR . '</a>';
    } else {
        $pathCheckResult = -2;
        $path_status     = _AM_SPARTNER_NOTWRITABLE . " <a href=index.php?op=setperm&amp;path=$item>" . _AM_SCS_SETMPERM . '</a>';
    }
    if (!$getStatus) {
        return $path_status;
    } else {
        return $pathCheckResult;
    }
}

/**
 * Thanks to the NewBB2 Development Team
 * @param $target
 * @return bool
 */
function smartpartner_admin_mkdir($target)
{
    // http://www.php.net/manual/en/function.mkdir.php
    // saint at corenova.com
    // bart at cdasites dot com
    if (is_dir($target) || empty($target)) {
        return true;
    } // best case check first
    if (file_exists($target) && !is_dir($target)) {
        return false;
    }
    if (smartpartner_admin_mkdir(substr($target, 0, strrpos($target, '/')))) {
        if (!file_exists($target)) {
            return mkdir($target);
        }
    } // crawl back up & create dir tree

    return true;
}

/**
 * Thanks to the NewBB2 Development Team
 * @param       $target
 * @param  int  $mode
 * @return bool
 */
function smartpartner_admin_chmod($target, $mode = 0777)
{
    return @chmod($target, $mode);
}

/**
 * @param  bool $local
 * @param  bool $item
 * @return string
 */
function smartpartner_getUploadDir($local = true, $item = false)
{
    if ($item) {
        if ($item === 'root') {
            $item = '';
        } else {
            $item .= '/';
        }
    } else {
        $item = '';
    }

    if ($local) {
        return XOOPS_ROOT_PATH . "/uploads/smartpartner/$item";
    } else {
        return XOOPS_URL . "/uploads/smartpartner/$item";
    }
}

/**
 * @param  string $item
 * @param  bool   $local
 * @return string
 */
function smartpartner_getImageDir($item = '', $local = true)
{
    if ($item) {
        $item = "images/$item";
    } else {
        $item = 'images';
    }

    return smartpartner_getUploadDir($local, $item);
}

/**
 * @param  array $errors
 * @return string
 */
function smartpartner_formatErrors($errors = array())
{
    $ret = '';
    foreach ($errors as $key => $value) {
        $ret .= '<br /> - ' . $value;
    }

    return $ret;
}

/**
 * @param  bool   $another
 * @param  bool   $withRedirect
 * @param  object $itemObj
 * @return bool|string
 */
function smartpartner_upload_file($another = false, $withRedirect = true, $itemObj)
{
    include_once(SMARTPARTNER_ROOT_PATH . 'class/uploader.php');

    global $smartPartnerIsAdmin, $xoopsModuleConfig, $smartPartnerPartnerHandler, $smartPartnerFileHandler, $xoopsUser;

    $id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $uid     = is_object($xoopsUser) ? $xoopsUser->uid() : 0;
    $session = SmartpartnerSession::singleton();
    $session->set('smartpartner_file_filename', isset($_POST['name']) ? $_POST['name'] : '');
    $session->set('smartpartner_file_description', isset($_POST['description']) ? $_POST['description'] : '');
    $session->set('smartpartner_file_status', $_POST['file_status']);
    $session->set('smartpartner_file_uid', $uid);
    $session->set('smartpartner_file_id', $id);

    if (!is_object($itemObj)) {
        $itemObj = $smartPartnerPartnerHandler->get($id);
    }

    $max_size = $xoopsModuleConfig['maximum_filesize'];

    $fileObj = $smartPartnerFileHandler->create();
    $fileObj->setVar('name', isset($_POST['name']) ? $_POST['name'] : '');
    $fileObj->setVar('description', isset($_POST['description']) ? $_POST['description'] : '');
    $fileObj->setVar('status', isset($_POST['file_status']) ? (int)$_POST['file_status'] : 1);
    $fileObj->setVar('uid', $uid);
    $fileObj->setVar('id', $itemObj->getVar('id'));
    $allowed_mimetypes = '';
    $errors            = '';
    // Get available mimetypes for file uploading
    /*    $hMime = xoops_getModuleHandler('mimetype');
        if ($smartPartnerIsAdmin) {
            $crit = new Criteria('mime_admin', 1);
        } else {
            $crit = new Criteria('mime_user', 1);
        }
        $mimetypes =& $hMime->getObjects($crit);
        // TODO: display the available mimetypes to the user
        */
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        if (!$ret = $fileObj->checkUpload('userfile', $allowed_mimetypes, $errors)) {
            $errorstxt = implode('<br />', $errors);

            $message = sprintf(_SMARTPARTNER_MESSAGE_FILE_ERROR, $errorstxt);
            if ($withRedirect) {
                redirect_header('file.php?op=mod&id=' . $id, 5, $message);
            } else {
                return $message;
            }
        }
    }

    // Storing the file
    if (!$fileObj->store($allowed_mimetypes)) {
        if ($withRedirect) {
            redirect_header('file.php?op=mod&id=' . $fileObj->id(), 3, _AM_SPARTNER_FILEUPLOAD_ERROR . smartpartner_formatErrors($fileObj->getErrors()));
            exit;
        } else {
            return _AM_SPARTNER_FILEUPLOAD_ERROR . smartpartner_formatErrors($fileObj->getErrors());
        }
    }
    if ($withRedirect) {
        $redirect_page = $another ? 'file.php' : 'partner.php';
        redirect_header($redirect_page . '?op=mod&id=' . $fileObj->id(), 2, _AM_SPARTNER_FILEUPLOAD_SUCCESS);
    } else {
        return true;
    }
}

/**
 * @param $dirname
 * @return bool
 */
function smartpartner_deleteFile($dirname)
{
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
}

function smartpartner_create_upload_folders()
{
    $handler = xoops_getModuleHandler('offer', 'smartpartner');
    smart_admin_mkdir($handler->getImagePath());

    smart_admin_mkdir(XOOPS_ROOT_PATH . '/uploads/smartpartner/images/category');
}
