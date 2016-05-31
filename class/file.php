<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/common.php';

// File status
define('_SPARTNER_STATUS_FILE_NOTSET', -1);
define('_SPARTNER_STATUS_FILE_ACTIVE', 1);
define('_SPARTNER_STATUS_FILE_INACTIVE', 2);

/**
 * Class SmartpartnerFile
 */
class SmartpartnerFile extends XoopsObject
{
    /**
     * constructor
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('fileid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('filename', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('mimetype', XOBJ_DTYPE_TXTBOX, null, true, 64);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('datesub', XOBJ_DTYPE_INT, null, false);
        $this->initVar('status', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('notifypub', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('counter', XOBJ_DTYPE_INT, null, false);

        if (isset($id)) {
            global $smartPartnerFileHandler;
            $file = $smartPartnerFileHandler->get($id);
            foreach ($file->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @param $post_field
     * @param $allowed_mimetypes
     * @param $errors
     * @return bool
     */
    public function checkUpload($post_field, &$allowed_mimetypes, &$errors)
    {
        include_once(SMARTPARTNER_ROOT_PATH . 'class/uploader.php');
        $config = smartpartner_getModuleConfig();

        $maxfilesize   = $config['maximum_filesize'];
        $maxfilewidth  = 100000; //$config['maximum_image_width'];
        $maxfileheight = 100000; //$config['maximum_image_height'];

        $errors = array();

        if (!isset($allowed_mimetypes)) {
            $hMime             = xoops_getModuleHandler('mimetype');
            $allowed_mimetypes = $hMime->checkMimeTypes($post_field);
            if (!$allowed_mimetypes) {
                $errors[] = _SMARTPARTNER_MESSAGE_WRONG_MIMETYPE;

                return false;
            }
        }
        $uploader = new XoopsMediaUploader(smartpartner_getUploadDir(), $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

        if ($uploader->fetchMedia($post_field)) {
            return true;
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));

            return false;
        }
    }

    /**
     * @param $text
     * @return mixed
     */
    public function purifyText($text)
    {
        global $myts;
        $text = str_replace('&nbsp;', ' ', $text);
        $text = str_replace('<br />', ' ', $text);
        $text = str_replace('. ', ' ', $text);
        $text = str_replace(', ', ' ', $text);
        $text = str_replace(')', '', $text);
        $text = str_replace('(', '', $text);
        $text = str_replace(':', '', $text);
        $text = str_replace('&euro', '', $text);
        $text = str_replace(';', '', $text);
        $text = str_replace('!', ' ', $text);
        $text = str_replace('?', ' ', $text);
        $text = str_replace('é', 'e', $text);
        $text = str_replace('è', 'e', $text);
        $text = str_replace('ê', 'e', $text);
        $text = str_replace('â', 'a', $text);
        $text = str_replace('à', 'a', $text);
        $text = str_replace('ù', 'u', $text);
        $text = str_replace('û', 'u', $text);
        $text = str_replace('ô', 'o', $text);
        $text = str_replace('ñ', 'n', $text);
        $text = str_replace('É', 'e', $text);
        $text = str_replace('È', 'e', $text);
        $text = str_replace('Ê', 'e', $text);
        $text = str_replace('Â', 'A', $text);
        $text = str_replace('À', 'A', $text);
        $text = str_replace('Ù', 'U', $text);
        $text = str_replace('Û', 'U', $text);
        $text = str_replace('Ô', 'O', $text);
        $text = str_replace('Ñ', 'N', $text);
        $text = str_replace("'", '', $text);
        $text = str_replace("\\", '', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text);
        $text = $myts->undoHtmlSpecialChars($text);

        return $text;
    }

    /**
     * @param       $post_field
     * @param  null $allowed_mimetypes
     * @param       $errors
     * @return bool
     * @throws
     */
    public function storeUpload($post_field, $allowed_mimetypes = null, &$errors)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        include_once(SMARTPARTNER_ROOT_PATH . 'class/uploader.php');

        $config = smartpartner_getModuleConfig();

        $id = $this->getVar('id');

        if (!isset($allowed_mimetypes)) {
            $hMime             = xoops_getModuleHandler('mimetype');
            $allowed_mimetypes = $hMime->checkMimeTypes($post_field);
            if (!$allowed_mimetypes) {
                return false;
            }
        }

        /*$maxfilesize = $config['xhelp_uploadSize'];
        $maxfilewidth = $config['xhelp_uploadWidth'];
        $maxfileheight = $config['xhelp_uploadHeight'];*/

        $maxfilesize   = $config['maximum_filesize'];
        $maxfilewidth  = 100000; //$config['maximum_image_width'];
        $maxfileheight = 100000; //$config['maximum_image_height'];

        if (!is_dir(smartpartner_getUploadDir())) {
            //            mkdir(smartpartner_getUploadDir(), 0757);
            if (!@mkdir(smartpartner_getUploadDir(), 0757) && !is_dir(smartpartner_getUploadDir())) {
                throw Exception("Couldn't create this directory: " . smartpartner_getUploadDir());
            }
        }

        $uploader = new XoopsMediaUploader(smartpartner_getUploadDir() . '/', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($post_field)) {
            $file_title = $this->purifyText($uploader->getMediaName());
            $uploader->setTargetFileName($id . '_' . $file_title);
            if ($uploader->upload()) {
                $this->setVar('filename', $uploader->getSavedFileName());
                if ($this->getVar('name') == '') {
                    $this->setVar('name', $this->getNameFromFilename());
                }
                $this->setVar('mimetype', $uploader->getMediaType());

                return true;
            } else {
                $errors = array_merge($errors, $uploader->getErrors(false));

                return false;
            }
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));

            return false;
        }
    }

    /**
     * @param       $allowed_mimetypes
     * @param  bool $force
     * @param  bool $doupload
     * @return bool
     */
    public function store(&$allowed_mimetypes, $force = true, $doupload = true)
    {
        if ($this->isNew()) {
            $errors = array();
            if ($doupload) {
                $ret = $this->storeUpload('userfile', $allowed_mimetypes, $errors);
            } else {
                $ret = true;
            }
            if (!$ret) {
                foreach ($errors as $error) {
                    $this->setErrors($error);
                }

                return false;
            }
        }

        global $smartPartnerFileHandler;

        return $smartPartnerFileHandler->insert($this, $force);
    }

    /**
     * @return mixed
     */
    public function fileid()
    {
        return $this->getVar('fileid');
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->getVar('id');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function name($format = 'S')
    {
        return $this->getVar('name', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function description($format = 'S')
    {
        return $this->getVar('description', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function filename($format = 'S')
    {
        return $this->getVar('filename', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function mimetype($format = 'S')
    {
        return $this->getVar('mimetype', $format);
    }

    /**
     * @return mixed
     */
    public function uid()
    {
        return $this->getVar('uid');
    }

    /**
     * @param  string $dateFormat
     * @param  string $format
     * @return string
     */
    public function datesub($dateFormat = 's', $format = 'S')
    {
        return formatTimestamp($this->getVar('datesub', $format), $dateFormat);
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->getVar('status');
    }

    /**
     * @return mixed
     */
    public function notifypub()
    {
        return $this->getVar('notifypub');
    }

    /**
     * @return mixed
     */
    public function counter()
    {
        return $this->getVar('counter');
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return ($this->getVar('id') == 0);
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        $hModule            = xoops_getHandler('module');
        $hModConfig         = xoops_getHandler('config');
        $smartPartnerModule =& $hModule->getByDirname('smartpartner');
        $smartPartnerConfig = &$hModConfig->getConfigsByCat(0, $smartPartnerModule->getVar('mid'));

        return smartpartner_getUploadDir(false) . $this->filename();
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        $hModule            = xoops_getHandler('module');
        $hModConfig         = xoops_getHandler('config');
        $smartPartnerModule =& $hModule->getByDirname('smartpartner');
        $smartPartnerConfig = &$hModConfig->getConfigsByCat(0, $smartPartnerModule->getVar('mid'));

        return smartpartner_getUploadDir() . $this->filename();
    }

    /**
     * @return string
     */
    public function getFileLink()
    {
        return "<a href='" . XOOPS_URL . '/modules/smartpartner/visit.php?fileid=' . $this->fileid() . "'>" . $this->name() . '</a>';
    }

    /**
     * @return string
     */
    public function getItemLink()
    {
        return "<a href='" . XOOPS_URL . '/modules/smartpartner/partner.php?id=' . $this->id() . "'>" . $this->name() . '</a>';
    }

    public function updateCounter()
    {
        $this->setVar('counter', $this->counter() + 1);
        $this->store();
    }

    /**
     * @return mixed
     */
    public function displayFlash()
    {
        if (!defined('MYTEXTSANITIZER_EXTENDED_MEDIA')) {
            include_once(SMARTPARTNER_ROOT_PATH . 'include/media.textsanitizer.php');
        }
        $media_ts = MyTextSanitizerExtension::getInstance();

        return $media_ts->_displayFlash($this->getFileUrl());
    }

    /**
     * @return mixed|string
     */
    public function getNameFromFilename()
    {
        $ret     = $this->filename();
        $sep_pos = strpos($ret, '_');
        $ret     = substr($ret, $sep_pos + 1, -$sep_pos);

        return $ret;
    }
}

/**
 * Files handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of File class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package SmartPartner
 */
class SmartpartnerFileHandler extends XoopsObjectHandler
{
    /**
     * create a new file
     *
     * @param  bool $isNew flag the new objects as "new"?
     * @return object SmartpartnerFile
     */
    public function create($isNew = true)
    {
        $file = new SmartpartnerFile();
        if ($isNew) {
            $file->setNew();
        }

        return $file;
    }

    /**
     * retrieve an file
     *
     * @param  int $id fileid of the file
     * @return mixed reference to the {@link SmartpartnerFile} object, FALSE if failed
     */
    public function get($id)
    {
        if ((int)$id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('smartpartner_files') . ' WHERE fileid=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $file = new SmartpartnerFile();
                $file->assignVars($this->db->fetchArray($result));

                return $file;
            }
        }

        return false;
    }

    /**
     * insert a new file in the database
     *
     * @param  XoopsObject $fileObj
     * @param  bool        $force
     * @return bool        FALSE if failed, TRUE if already present and unchanged or successful
     * @internal param object $file reference to the <a href='psi_element://SmartpartnerFile'>SmartpartnerFile</a> object object
     */
    public function insert(XoopsObject $fileObj, $force = false)
    {
        if (strtolower(get_class($fileObj)) !== 'smartpartnerfile') {
            return false;
        }
        if (!$fileObj->isDirty()) {
            return true;
        }
        if (!$fileObj->cleanVars()) {
            return false;
        }

        foreach ($fileObj->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($fileObj->isNew()) {
            $sql = sprintf('INSERT INTO %s (fileid, id, name, description, filename, mimetype, uid, datesub, `status`, notifypub, counter) VALUES (NULL, %u, %s, %s, %s, %s, %u, %u, %u, %u, %u)',
                           $this->db->prefix('smartpartner_files'), $id, $this->db->quoteString($name), $this->db->quoteString($description), $this->db->quoteString($filename),
                           $this->db->quoteString($mimetype), $uid, time(), $status, $notifypub, $counter);
        } else {
            $sql = sprintf('UPDATE %s SET id = %u, name = %s, description = %s, filename = %s, mimetype = %s, uid = %u, datesub = %u, status = %u, notifypub = %u, counter = %u WHERE fileid = %u',
                           $this->db->prefix('smartpartner_files'), $id, $this->db->quoteString($name), $this->db->quoteString($description), $this->db->quoteString($filename),
                           $this->db->quoteString($mimetype), $uid, $datesub, $status, $notifypub, $counter, $fileid);
        }

        //echo "<br>$sql<br>";

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            $fileObj->setErrors('The query returned an error. ' . $this->db->error());

            return false;
        }

        if ($fileObj->isNew()) {
            $fileObj->assignVar('fileid', $this->db->getInsertId());
        }

        $fileObj->assignVar('fileid', $fileid);

        return true;
    }

    /**
     * delete a file from the database
     *
     * @param  XoopsObject $file reference to the file to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete(XoopsObject $file, $force = false)
    {
        if (strtolower(get_class($file)) !== 'smartpartnerfile') {
            return false;
        }
        // Delete the actual file
        if (!smartpartner_deleteFile($file->getFilePath())) {
            return false;
        }
        // Delete the record in the table
        $sql = sprintf('DELETE FROM %s WHERE fileid = %u', $this->db->prefix('smartpartner_files'), $file->getVar('fileid'));

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * delete files related to an item from the database
     *
     * @param  object $itemObj reference to the item which files to delete
     * @return bool
     */
    public function deleteItemFiles(&$itemObj)
    {
        if (strtolower(get_class($itemObj)) !== 'smartpartneritem') {
            return false;
        }
        $files  = $this->getAllFiles($itemObj->id());
        $result = true;
        foreach ($files as $file) {
            if (!$this->delete($file)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * retrieve files from the database
     *
     * @param  object $criteria  {@link CriteriaElement} conditions to be met
     * @param  bool   $id_as_key use the fileid as key for the array?
     * @return array  array of {@link SmartpartnerFile} objects
     */
    public function getObjects($criteria = null, $id_as_key = false)
    {
        $ret   = array();
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('smartpartner_files');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $file = new SmartpartnerFile();
            $file->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $file;
            } else {
                $ret[$myrow['fileid']] =& $file;
            }
            unset($file);
        }

        return $ret;
    }

    /**
     * retrieve all files
     *
     * @param  int    $id
     * @param  int    $status
     * @param  int    $limit
     * @param  int    $start
     * @param  string $sort
     * @param  string $order
     * @return array  array of <a href='psi_element://SmartpartnerFile'>SmartpartnerFile</a> objects
     *                       objects
     * @internal param object $criteria <a href='psi_element://CriteriaElement'>CriteriaElement</a> conditions to be met conditions to be met
     */
    public function getAllFiles($id = 0, $status = -1, $limit = 0, $start = 0, $sort = 'datesub', $order = 'DESC')
    {
        $hasStatusCriteria = false;
        $criteriaStatus    = new CriteriaCompo();
        if (is_array($status)) {
            $hasStatusCriteria = true;
            foreach ($status as $v) {
                $criteriaStatus->add(new Criteria('status', $v), 'OR');
            }
        } elseif ($status != -1) {
            $hasStatusCriteria = true;
            $criteriaStatus->add(new Criteria('status', $status), 'OR');
        }
        $criteriaItemid = new Criteria('id', $id);

        $criteria = new CriteriaCompo();

        if ($id != 0) {
            $criteria->add($criteriaItemid);
        }

        if ($hasStatusCriteria) {
            $criteria->add($criteriaStatus);
        }

        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $files = $this->getObjects($criteria);

        return $files;
    }

    /**
     * count files matching a condition
     *
     * @param  object $criteria {@link CriteriaElement} to match
     * @return int    count of files
     */
    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('smartpartner_files');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * delete files matching a set of conditions
     *
     * @param  object $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll($criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('smartpartner_files');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Change a value for files with a certain criteria
     *
     * @param string $fieldname  Name of the field
     * @param string $fieldvalue Value to write
     * @param object $criteria   {@link CriteriaElement}
     *
     * @return bool
     **/
    public function updateAll($fieldname, $fieldvalue, $criteria = null)
    {
        $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
        $sql        = 'UPDATE ' . $this->db->prefix('smartpartner_files') . ' SET ' . $set_clause;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        //echo "<br>" . $sql . "<br>";
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }
}
