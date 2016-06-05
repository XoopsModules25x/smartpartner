<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecthandler.php';

// Partners status
define('_SPARTNER_STATUS_NOTSET', -1);
define('_SPARTNER_STATUS_ALL', 0);
define('_SPARTNER_STATUS_SUBMITTED', 1);
define('_SPARTNER_STATUS_ACTIVE', 2);
define('_SPARTNER_STATUS_REJECTED', 3);
define('_SPARTNER_STATUS_INACTIVE', 4);

define('_SPARTNER_NOT_PARTNER_SUBMITTED', 1);
define('_SPARTNER_NOT_PARTNER_APPROVED', 2);
define('_SPARTNER_NOT_PARTNER_NEW', 3);
define('_SPARTNER_NOT_OFFER_NEW', 4);

/**
 * Class SmartpartnerPartner
 */
class SmartpartnerPartner extends SmartObject
{
    public $_extendedInfo = null;

    /**
     * SmartpartnerPartner constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('categoryid', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('datesub', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '', true);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('contact_name', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('contact_email', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('contact_phone', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('adress', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, '', true);
        $this->initVar('image_url', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0, true, 10);
        $this->initVar('hits_page', XOBJ_DTYPE_INT, 0, true, 10);
        $this->initVar('status', XOBJ_DTYPE_INT, _SPARTNER_STATUS_NOTSET, false, 10);
        $this->initVar('last_update', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('email_priv', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('phone_priv', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('adress_priv', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('showsummary', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);

        if (isset($id)) {
            $smartPartnerPartnerHandler = new SmartpartnerPartnerHandler($this->db);
            $partner                    = $smartPartnerPartnerHandler->get($id);
            foreach ($partner->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->getVar('id');
    }

    /**
     * @return mixed
     */
    public function categoryid()
    {
        return $this->getVar('categoryid');
    }

    /**
     * @return mixed
     */
    public function weight()
    {
        return $this->getVar('weight');
    }

    /**
     * @return mixed
     */
    public function email_priv()
    {
        return $this->getVar('email_priv');
    }

    /**
     * @return mixed
     */
    public function phone_priv()
    {
        return $this->getVar('phone_priv');
    }

    /**
     * @return mixed
     */
    public function adress_priv()
    {
        return $this->getVar('adress_priv');
    }

    /**
     * @return mixed
     */
    public function hits()
    {
        return $this->getVar('hits');
    }

    /**
     * @return mixed
     */
    public function hits_page()
    {
        return $this->getVar('hits_page');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function url($format = 'S')
    {
        return $this->getVar('url', $format);
    }

    /**
     * @param  string $format
     * @return mixed|string
     */
    public function image($format = 'S')
    {
        if ($this->getVar('image') != '') {
            return $this->getVar('image', $format);
        } else {
            return 'blank.png';
        }
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function image_url($format = 'S')
    {
        return $this->getVar('image_url', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function title($format = 'S')
    {
        $ret = $this->getVar('title', $format);
        if (($format === 's') || ($format === 'S') || ($format === 'show')) {
            $myts = MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed|string
     */
    public function datesub($format = 'S')
    {
        $ret = $this->getVar('datesub', $format);
        if (($format === 's') || ($format === 'S') || ($format === 'show')) {
            $ret = formatTimestamp($ret, 's');
        }

        return $ret;
    }

    /**
     * @param  int    $maxLength
     * @param  string $format
     * @return mixed|string
     */
    public function summary($maxLength = 0, $format = 'S')
    {
        $ret = $this->getVar('summary', $format);

        if ($maxLength != 0) {
            if (!XOOPS_USE_MULTIBYTES) {
                if (strlen($ret) >= $maxLength) {
                    $ret = xoops_substr(smartpartner_metagen_html2text($ret), 0, $maxLength);
                }
            }
        }

        return $ret;
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
    public function contact_name($format = 'S')
    {
        $ret = $this->getVar('contact_name', $format);
        if (($format === 's') || ($format === 'S') || ($format === 'show')) {
            $myts = MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function contact_email($format = 'S')
    {
        $ret = $this->getVar('contact_email', $format);
        if (($format === 's') || ($format === 'S') || ($format === 'show')) {
            $myts = MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function contact_phone($format = 'S')
    {
        $ret = $this->getVar('contact_phone', $format);
        if (($format === 's') || ($format === 'S') || ($format === 'show')) {
            $myts = MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function adress($format = 'S')
    {
        $ret = $this->getVar('adress', $format);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->getVar('status');
    }

    /**
     * @param $forWhere
     * @return string
     */
    public function getUrlLink($forWhere)
    {
        if ($forWhere === 'block') {
            if ($this->extentedInfo()) {
                return '<a href="' . SMARTPARTNER_URL . 'partner.php?id=' . $this->id() . '">';
            } else {
                if ($this->url()) {
                    return '<a href="' . $this->url() . '" target="_blank">';
                } else {
                    return '';
                }
            }
        } elseif ($forWhere === 'index') {
            if ($this->extentedInfo()) {
                return '<a href="' . SMARTPARTNER_URL . 'partner.php?id=' . $this->id() . '">';
            } else {
                if ($this->url()) {
                    return '<a href="' . SMARTPARTNER_URL . 'vpartner.php?id=' . $this->id() . '">';
                } else {
                    return '';
                }
            }
        } elseif ($forWhere === 'partner') {
            if ($this->url()) {
                return '<a href="' . SMARTPARTNER_URL . 'vpartner.php?id=' . $this->id() . '">';
            } else {
                return '';
            }
        }
    }

    /**
     * @return mixed|string
     */
    public function getImageUrl()
    {
        if (($this->getVar('image') !== '') && ($this->getVar('image') !== 'blank.png') && ($this->getVar('image') !== '-1')) {
            return smartpartner_getImageDir('', false) . $this->image();
        } elseif (!$this->getVar('image_url')) {
            return smartpartner_getImageDir('', false) . 'blank.png';
        } else {
            return $this->getVar('image_url');
        }
    }

    /**
     * @return bool|string
     */
    public function getImagePath()
    {
        if (($this->getVar('image') !== '') && ($this->getVar('image') !== 'blank.png')) {
            return smartpartner_getImageDir() . $this->image();
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getImageLink()
    {
        $ret = "<a href='rrvpartner.php?id=" . $this->id() . "' target='_blank'>";
        if ($this->getVar('image') != '') {
            $ret .= "<img src='" . $this->getImageUrl() . "' alt='" . $this->url() . "' border='0' /></a>";
        } else {
            $ret .= "<img src='" . $this->image_url() . "' alt='" . $this->url() . "' border='0' /></a>";
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        switch ($this->status()) {
            case _SPARTNER_STATUS_ACTIVE:
                return _CO_SPARTNER_ACTIVE;
                break;

            case _SPARTNER_STATUS_INACTIVE:
                return _CO_SPARTNER_INACTIVE;
                break;

            case _SPARTNER_STATUS_REJECTED:
                return _CO_SPARTNER_REJECTED;
                break;

            case _SPARTNER_STATUS_SUBMITTED:
                return _CO_SPARTNER_SUBMITTED;
                break;

            case _SPARTNER_STATUS_NOTSET:
            default;

                return _CO_SPARTNER_NOTSET;
                break;
        }
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return ($this->getVar('id') == 0);
    }

    /**
     * @return bool|null
     */
    public function extentedInfo()
    {
        if ($this->_extendedInfo) {
            return $this->_extendedInfo;
        }
        if (!$this->description() && !$this->contact_name() && !$this->contact_email() && !$this->contact_phone() && !$this->adress()) {
            $this->_extendedInfo = false;
        } else {
            $this->_extendedInfo = true;
        }

        return $this->_extendedInfo;
    }

    /**
     * @param  bool $force
     * @return bool
     */
    public function store($force = true)
    {
        $smartPartnerPartnerHandler = new SmartpartnerPartnerHandler($this->db);

        return $smartPartnerPartnerHandler->insert($this, $force);
    }

    /**
     * @return bool
     */
    public function updateHits()
    {
        $sql = 'UPDATE ' . $this->db->prefix('smartpartner_partner') . ' SET hits=hits+1 WHERE id = ' . $this->id();
        if ($this->db->queryF($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function updateHits_page()
    {
        $sql = 'UPDATE ' . $this->db->prefix('smartpartner_partner') . ' SET hits_page=hits_page+1 WHERE id = ' . $this->id();
        if ($this->db->queryF($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $notifications
     */
    public function sendNotifications($notifications = array())
    {
        $smartModule =& smartpartner_getModuleInfo();
        $module_id   = $smartModule->getVar('mid');

        $myts                = MyTextSanitizer::getInstance();
        $notificationHandler = xoops_getHandler('notification');

        $tags                 = array();
        $tags['MODULE_NAME']  = $myts->displayTarea($smartModule->getVar('name'));
        $tags['PARTNER_NAME'] = $this->title(20);
        foreach ($notifications as $notification) {
            switch ($notification) {

                case _SPARTNER_NOT_PARTNER_SUBMITTED:
                    $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/admin/partner.php?op=mod&id=' . $this->id();
                    $notificationHandler->triggerEvent('global_partner', 0, 'submitted', $tags);
                    break;

                case _SPARTNER_NOT_PARTNER_APPROVED:
                    $tags['PARTNER_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/partner.php?id=' . $this->id();
                    $notificationHandler->triggerEvent('partner', $this->id(), 'approved', $tags);
                    break;

                case _SPARTNER_NOT_PARTNER_NEW:
                    $tags['PARTNER_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/partner.php?id=' . $this->id();
                    $notificationHandler->triggerEvent('global_partner', 0, 'new_partner', $tags);
                    break;

                case -1:
                default:
                    break;
            }
        }
    }

    /**
     * @param $original_status
     * @param $new_status
     * @return array
     */
    public function getRedirectMsg($original_status, $new_status)
    {
        $redirect_msgs = array();

        switch ($original_status) {

            case _SPARTNER_STATUS_NOTSET:
                switch ($new_status) {
                    case _SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_NOTSET_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_NOTSET_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;
                }
                break;

            case _SPARTNER_STATUS_SUBMITTED:
                switch ($new_status) {
                    case _SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_SUBMITTED_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_SUBMITTED_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_REJECTED:
                        $redirect_msgs['success'] = _AM_SPARTNER_SUBMITTED_REJECTED_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;
                }
                break;

            case _SPARTNER_STATUS_ACTIVE:
                switch ($new_status) {
                    case _SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_ACTIVE_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_ACTIVE_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                }
                break;

            case _SPARTNER_STATUS_INACTIVE:
                switch ($new_status) {
                    case _SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_INACTIVE_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_INACTIVE_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                }
                break;

            case _SPARTNER_STATUS_REJECTED:
                switch ($new_status) {
                    case _SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_REJECTED_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_REJECTED_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case _SPARTNER_STATUS_REJECTED:
                        $redirect_msgs['success'] = _AM_SPARTNER_REJECTED_REJECTED_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;
                }
                break;
        }

        return $redirect_msgs;
    }

    /**
     * @return array
     */
    public function getAvailableStatus()
    {
        switch ($this->status()) {
            case _SPARTNER_STATUS_NOTSET:
                $ret = array(
                    _SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    _SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                );
                break;
            case _SPARTNER_STATUS_SUBMITTED:
                $ret = array(
                    _SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    _SPARTNER_STATUS_REJECTED => _AM_SPARTNER_REJECTED,
                    _SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                );
                break;

            case _SPARTNER_STATUS_ACTIVE:
                $ret = array(
                    _SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    _SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                );
                break;

            case _SPARTNER_STATUS_INACTIVE:
                $ret = array(
                    _SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    _SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                );
                break;

            case _SPARTNER_STATUS_REJECTED:
                $ret = array(
                    _SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    _SPARTNER_STATUS_REJECTED => _AM_SPARTNER_REJECTED,
                    _SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                );
                break;
        }

        return $ret;
    }

    public function setUpdated()
    {
        $this->setVar('last_update', time());
        $this->store();
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        global $smartPartnerFileHandler;

        return $smartPartnerFileHandler->getAllFiles($this->id(), _SPARTNER_STATUS_FILE_ACTIVE);
    }

    /**
     * @param  string $url_link_type
     * @return mixed
     */
    public function toArray($url_link_type = 'partner')
    {
        $smartConfig = smartpartner_getModuleConfig();

        $partner['id']         = $this->id();
        $partner['categoryid'] = $this->categoryid();
        $partner['hits']       = $this->hits();
        $partner['hits_page']  = $this->hits_page();
        $partner['url']        = $this->url();
        $partner['urllink']    = $this->getUrlLink($url_link_type);
        $partner['image']      = $this->getImageUrl();

        $partner['title']       = $this->title();
        $partner['datesub']     = $this->datesub();
        $partner['clean_title'] = $partner['title'];
        $partner['summary']     = $this->summary();

        $partner['contact_name']  = $this->contact_name();
        $partner['contact_email'] = $this->contact_email();
        $partner['contact_phone'] = $this->contact_phone();
        $partner['adress']        = $this->adress();
        $partner['email_priv']    = $this->email_priv();
        $partner['phone_priv']    = $this->phone_priv();
        $partner['adress_priv']   = $this->adress_priv();

        $image_info          = smartpartner_imageResize($this->getImagePath(), $smartConfig['img_max_width'], $smartConfig['img_max_height']);
        $partner['img_attr'] = $image_info[3];

        $partner['readmore'] = $this->extentedInfo();
        if ((time() - $this->datesub('e')) < ($smartConfig['updated_period'] * 24 * 3600)) {
            $partner['update_status'] = 'new';
        } elseif ((time() - $this->getVar('last_update')) < ($smartConfig['updated_period'] * 24 * 3600)) {
            $partner['update_status'] = 'updated';
        } else {
            $partner['update_status'] = 'none';
        }
        //--------------
        global $smartPermissionsHandler, $smartPartnerPartnerHandler, $xoopsUser;
        include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectpermission.php';
        if (!$smartPartnerPartnerHandler) {
            $smartPartnerPartnerHandler = smartpartner_gethandler('partner');
        }
        $smartPermissionsHandler = new SmartobjectPermissionHandler($smartPartnerPartnerHandler);
        $grantedGroups           = $smartPermissionsHandler->getGrantedGroups('full_view', $this->id());
        $partGrantedGroups       = $smartPermissionsHandler->getGrantedGroups('partial_view', $this->id());

        $userGroups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);

        if (array_intersect($userGroups, $grantedGroups)) {
            $partner['display_type'] = 'full';
        } elseif (array_intersect($userGroups, $partGrantedGroups)) {
            $partner['display_type'] = 'part';
        } else {
            $partner['display_type'] = 'none';
        }
        if ($this->description() != '' && $partner['display_type'] === 'full') {
            $partner['description'] = $this->description();
        } else {
            //$partner['description'] = $this->summary();
        }
        $partner['showsummary'] = $this->getVar('showsummary');

        //--------------

        // Hightlighting searched words
        $highlight = true;
        if ($highlight && isset($_GET['keywords'])) {
            $myts                     = MyTextSanitizer::getInstance();
            $keywords                 = $myts->htmlSpecialChars(trim(urldecode($_GET['keywords'])));
            $h                        = new SmartpartnerKeyhighlighter($keywords, true, 'smartpartner_highlighter');
            $partner['title']         = $h->highlight($partner['title']);
            $partner['summary']       = $h->highlight($partner['summary']);
            $partner['description']   = $h->highlight($partner['description']);
            $partner['contact_name']  = $h->highlight($partner['contact_name']);
            $partner['contact_email'] = $h->highlight($partner['contact_email']);
            $partner['contact_phone'] = $h->highlight($partner['contact_phone']);
            $partner['adress']        = $h->highlight($partner['adress']);
        }

        return $partner;
    }
}

/**
 * Partner handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of Partner class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package SmartPartner
 */
class SmartpartnerPartnerHandler extends SmartPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param XoopsDatabase $db reference to a xoops_db object
     */

    public function __construct(XoopsDatabase $db)
    {
        xoops_loadLanguage('common', 'smartpartner');
        parent::__construct($db, 'partner', 'id', 'title', false, 'smartpartner');
        $this->addPermission('full_view', _CO_SPARTNER_FULL_PERM_READ, _CO_SPARTNER_FULL_PERM_READ_DSC);
        $this->addPermission('partial_view', _CO_SPARTNER_PART_PERM_READ, _CO_SPARTNER_PART_PERM_READ_DSC);
    }

    /**
     * Singleton - prevent multiple instances of this class
     *
     * @param  objecs|XoopsDatabase $db
     * @return object               <a href='psi_element://SmartpartnerCategoryHandler'>SmartpartnerCategoryHandler</a>
     * @access public
     */
    public function getInstance(XoopsDatabase $db)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($db);
        }

        return $instance;
    }

    /**
     * @param  bool $isNew
     * @return SmartpartnerPartner
     */
    public function create($isNew = true)
    {
        $partner = new SmartpartnerPartner();
        if ($isNew) {
            $partner->setNew();
        }

        return $partner;
    }

    /**
     * retrieve a Partner
     *
     * @param  int  $id        partnerid of the user
     * @param  bool $as_object
     * @param  bool $debug
     * @param  bool $criteria
     * @return mixed reference to the <a href='psi_element://SmartpartnerPartner'>SmartpartnerPartner</a> object, FALSE if failed
     *                         object, FALSE if failed
     */
    public function get($id, $as_object = true, $debug = false, $criteria = false)
    {
        if ((int)$id > 0) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $partner = new SmartpartnerPartner();
                $partner->assignVars($this->db->fetchArray($result));
                global $smartpartnerPartnerCatLinkHandler;
                if (!$smartpartnerPartnerCatLinkHandler) {
                    $smartpartnerPartnerCatLinkHandler = smartpartner_gethandler('partner_cat_link');
                }
                $partner->setVar('categoryid', $smartpartnerPartnerCatLinkHandler->getParentIds($partner->getVar('id')));

                return $partner;
            }
        }
        $ret = false;

        return $ret;
    }

    /**
     * insert a new Partner in the database
     *
     * @param  XoopsObject $partner
     * @param  bool        $force
     * @param  bool        $checkObject
     * @param  bool        $debug
     * @return bool        FALSE if failed, TRUE if already present and unchanged or successful
     * @internal param XoopsObject $partner reference to the <a href='psi_element://SmartpartnerPartner'>SmartpartnerPartner</a> object object
     */
    public function insert(XoopsObject $partner, $force = false, $checkObject = true, $debug = false)
    {
        if (strtolower(get_class($partner)) != strtolower($this->className)) {
            return false;
        }

        if (!$partner->isDirty()) {
            return true;
        }

        if (!$partner->cleanVars()) {
            return false;
        }

        foreach ($partner->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($partner->isNew()) {
            $sql =
                sprintf('INSERT INTO %s (id,  weight, hits, hits_page, url, image, image_url, title, datesub, summary, description, contact_name, contact_email, contact_phone, adress, `status`, `last_update`, `email_priv`, `phone_priv`, `adress_priv`, `showsummary`) VALUES (null, %u, %u, %u, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %u, %u, %u, %u, %u, %u)',
                        $this->table, $weight, $hits, $hits_page, $this->db->quoteString($url), $this->db->quoteString($image), $this->db->quoteString($image_url), $this->db->quoteString($title),
                        time(), $this->db->quoteString($summary), $this->db->quoteString($description), $this->db->quoteString($contact_name), $this->db->quoteString($contact_email),
                        $this->db->quoteString($contact_phone), $this->db->quoteString($adress), $status, time(), $email_priv, $phone_priv, $adress_priv, $showsummary);
        } else {
            $sql =
                sprintf('UPDATE %s SET  weight = %u, hits = %u, hits_page = %u, url = %s, image = %s, image_url = %s, title = %s, datesub = %s, summary = %s, description = %s, contact_name = %s, contact_email = %s, contact_phone = %s, adress = %s, `status` = %u, `last_update` = %u, `email_priv` = %u, `phone_priv` = %u, `adress_priv` = %u, `showsummary` = %u WHERE id = %u',
                        $this->table, $weight, $hits, $hits_page, $this->db->quoteString($url), $this->db->quoteString($image), $this->db->quoteString($image_url), $this->db->quoteString($title),
                        $this->db->quoteString($datesub), $this->db->quoteString($summary), $this->db->quoteString($description), $this->db->quoteString($contact_name),
                        $this->db->quoteString($contact_email), $this->db->quoteString($contact_phone), $this->db->quoteString($adress), $status, time(), $email_priv, $phone_priv, $adress_priv,
                        $showsummary, $id);
        }

        //echo "<br>" . $sql . "<br>";exit;

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            return false;
        }
        if ($partner->isNew()) {
            $partner->assignVar('id', $this->db->getInsertId());
        }
        global $smartpartnerPartnerCatLinkHandler;
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('partnerid', $partner->getVar('id')));
        $links        = $smartpartnerPartnerCatLinkHandler->getObjects($criteria);
        $categoryid   = explode('|', $partner->getVar('categoryid'));
        $parent_array = array();
        foreach ($links as $link) {
            if (!in_array($link->getVar('categoryid'), $categoryid)) {
                $smartpartnerPartnerCatLinkHandler->delete($link);
            } else {
                $parent_array[] = $link->getVar('categoryid');
            }
        }
        foreach ($categoryid as $cat) {
            if (!in_array($cat, $parent_array)) {
                $linkObj = $smartpartnerPartnerCatLinkHandler->create();
                $linkObj->setVar('partnerid', $partner->getVar('id'));
                $linkObj->setVar('categoryid', $cat);
                $smartpartnerPartnerCatLinkHandler->insert($linkObj);
            }
        }
        if (isset($_POST['partial_view']) || isset($_POST['full_view'])) {
            $smartPermissionsHandler = new SmartobjectPermissionHandler($this);
            $smartPermissionsHandler->storeAllPermissionsForId($partner->id());
        }

        return true;
    }

    /**
     * delete a Partner from the database
     *
     * @param  XoopsObject $partner reference to the Partner to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete(XoopsObject $partner, $force = false)
    {
        global $smartPartnerOfferHandler, $smartpartnerPartnerCatLinkHandler;
        $partnerModule =& smartpartner_getModuleInfo();
        $module_id     = $partnerModule->getVar('mid');

        if (strtolower(get_class($partner)) != strtolower($this->className)) {
            return false;
        }

        $sql = sprintf('DELETE FROM %s WHERE id = %u', $this->table, $partner->getVar('id'));

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('partnerid', $partner->getVar('id')));
        $offersObj = $smartPartnerOfferHandler->getObjects($criteria);

        foreach ($offersObj as $offerObj) {
            $smartPartnerOfferHandler->delete($offerObj, 1);
        }
        $linksObj = $smartpartnerPartnerCatLinkHandler->getObjects($criteria);
        foreach ($linksObj as $linkObj) {
            $smartpartnerPartnerCatLinkHandler->delete($linkObj, 1);
        }

        return true;
    }

    /**
     * retrieve Partners from the database
     *
     * @param  CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param  bool            $id_as_key use the partnerid as key for the array?
     * @param  bool            $as_object
     * @param  bool            $sql
     * @param  bool            $debug
     * @return array  array of <a href='psi_element://SmartpartnerPartner'>SmartpartnerPartner</a> objects
     *                                    objects
     */
    public function getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true, $sql = false, $debug = false)//&getObjects($criteria = null, $id_as_key = false)
    {
        $ret   = array();
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->table;

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $whereClause = $criteria->renderWhere();

            if ($whereClause !== 'WHERE ()') {
                $sql .= ' ' . $criteria->renderWhere();
                if ($criteria->getSort() != '') {
                    $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
                }
                $limit = $criteria->getLimit();
                $start = $criteria->getStart();
            }
        }

        //echo "<br>" . $sql . "<br>";exit;
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        if (count($result) == 0) {
            return $ret;
        }
        global $smartpartnerPartnerCatLinkHandler;
        if (!isset($smartpartnerPartnerCatLinkHandler)) {
            $smartpartnerPartnerCatLinkHandler = smartpartner_gethandler('partner_cat_link');
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $partner = new SmartpartnerPartner();
            $partner->assignVars($myrow);

            if (!$id_as_key) {
                $ret[] =& $partner;
            } else {
                $ret[$myrow['id']] =& $partner;
            }
            $partner->setVar('categoryid', $smartpartnerPartnerCatLinkHandler->getParentIds($partner->getVar('id')));
            unset($partner);
        }

        return $ret;
    }

    /**
     * count Partners matching a condition
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement} to match
     * @return int    count of partners
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $whereClause = $criteria->renderWhere();
            if ($whereClause !== 'WHERE ()') {
                $sql .= ' ' . $criteria->renderWhere();
            }
        }

        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * @param  int $status
     * @return int
     */
    public function getPartnerCount($status = _SPARTNER_STATUS_ACTIVE)
    {
        if ($status != _SPARTNER_STATUS_ALL) {
            $criteriaStatus = new CriteriaCompo();
            $criteriaStatus->add(new Criteria('status', $status));
        }

        $criteria = new CriteriaCompo();
        if (isset($criteriaStatus)) {
            $criteria->add($criteriaStatus);
        }

        return $this->getCount($criteria);
    }

    /**
     * @param  array  $queryarray
     * @param  string $andor
     * @param  int    $limit
     * @param  int    $offset
     * @param  int    $userid
     * @return array
     */
    public function &getObjectsForSearch($queryarray = array(), $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
    {
        global $xoopsConfig;

        $ret = array();
        $sql = 'SELECT title, id
                   FROM ' . $this->table . '
                   ';
        if ($queryarray) {
            $criteriaKeywords = new CriteriaCompo();
            for ($i = 0, $iMax = count($queryarray); $i < $iMax; ++$i) {
                $criteriaKeyword = new CriteriaCompo();
                $criteriaKeyword->add(new Criteria('title', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('summary', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('description', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('contact_name', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('contact_email', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('contact_phone', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('adress', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeywords->add($criteriaKeyword, $andor);
                unset($criteriaKeyword);
            }
        }

        $criteria = new CriteriaCompo();

        if (!empty($criteriaKeywords)) {
            $criteria->add($criteriaKeywords, 'AND');
        }

        $criteria->add(new Criteria('status', _SPARTNER_STATUS_ACTIVE, '='), 'AND');

        if ($userid != 0) {
            $criteria->add(new Criteria('id', $userid), 'AND');
        }

        $criteria->setSort('datesub');
        $criteria->setOrder('DESC');

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . '
                    ' . $criteria->getOrder();
            }
        }

        //echo "<br>$sql<br>";

        $result = $this->db->query($sql, $limit, $offset);
        // If no records from db, return empty array
        if (!$result) {
            return $ret;
        }

        // Add each returned record to the result array
        while ($myrow = $this->db->fetchArray($result)) {
            $item['id']    = $myrow['id'];
            $item['title'] = $myrow['title'];
            $ret[]         = $item;
            unset($item);
        }

        return $ret;
    }

    /**
     * @param  int    $limit
     * @param  int    $start
     * @param  int    $status
     * @param  string $sort
     * @param  string $order
     * @param  bool   $asobject
     * @return array
     */
    public function getPartners($limit = 0, $start = 0, $status = _SPARTNER_STATUS_ACTIVE, $sort = 'title', $order = 'ASC', $asobject = true)
    {
        global $xoopsUser;
        if ($status != _SPARTNER_STATUS_ALL) {
            $criteriaStatus = new CriteriaCompo();
            $criteriaStatus->add(new Criteria('status', $status));
        }

        $criteria = new CriteriaCompo();
        if (isset($criteriaStatus)) {
            $criteria->add($criteriaStatus);
        }
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $ret = $this->getObjects($criteria);

        return $ret;
    }

    /**
     * @param  int    $categoryid
     * @param  int    $status
     * @param  string $sort
     * @param  string $order
     * @param  bool   $asobject
     * @return array
     */
    public function getPartnersForIndex($categoryid = 0, $status = _SPARTNER_STATUS_ACTIVE, $sort = 'title', $order = 'ASC', $asobject = true)
    {
        global $xoopsUser;
        if ($status != _SPARTNER_STATUS_ALL) {
            $criteriaStatus = new CriteriaCompo();
            $criteriaStatus->add(new Criteria('status', $status));
        }

        $criteria = new CriteriaCompo();
        if (isset($criteriaStatus)) {
            $criteria->add($criteriaStatus);
        }
        if ($categoryid != -1) {
            $criteria->add(new Criteria('categoryid', $categoryid));
        }
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $ret = $this->getObjects($criteria);

        return $ret;
    }

    /**
     * @param  null $status
     * @return bool|mixed
     */
    public function getRandomPartner($status = null)
    {
        $ret = false;

        // Getting the number of partners
        $totalPartners = $this->getPartnerCount($status);

        if ($totalPartners > 0) {
            --$totalPartners;
            mt_srand((double)microtime() * 1000000);
            $entrynumber = mt_rand(0, $totalPartners);
            $partner     = $this->getPartners(1, $entrynumber, $status);
            if ($partner) {
                $ret =& $partner[0];
            }
        }

        return $ret;
    }

    /**
     * delete Partners matching a set of conditions
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('smartpartner_partner');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Change a value for a Partner with a certain criteria
     *
     * @param string          $fieldname  Name of the field
     * @param string          $fieldvalue Value to write
     * @param CriteriaElement $criteria   {@link CriteriaElement}
     *
     * @param  bool           $force
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, CriteriaElement $criteria = null, $force = false)
    {
        $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
        $sql        = 'UPDATE ' . $this->db->prefix('smartpartner_partner') . ' SET ' . $set_clause;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param  int $limit
     * @param  int $status
     * @return bool
     */
    public function getRandomPartners($limit = 0, $status = _SPARTNER_STATUS_ACTIVE)
    {
        $ret = false;
        $sql = 'SELECT id FROM ' . $this->db->prefix('smartpartner_partner') . ' ';
        $sql .= 'WHERE status=' . $status;

        //echo "<br>" . $sql . "<br>";

        $result = $this->db->query($sql);

        if (!$result) {
            return $ret;
        }

        if (count($result) == 0) {
            return $ret;
        }

        $partners_ids = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $partners_ids[] = $myrow['id'];
        }

        if (count($partners_ids) > 1) {
            $key_arr  = array_values($partners_ids);
            $key_rand = array_rand($key_arr, count($key_arr));
            $ids      = implode(', ', $key_rand);
            echo $ids;

            return $ret;
        } else {
            return $ret;
        }
    }

    /*  function getFaqsFromSearch($queryarray = array(), $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
        {

        Global $xoopsUser;

        $ret = array();

        $hModule = xoops_getHandler('module');
        $hModConfig = xoops_getHandler('config');
        $smartModule =& $hModule->getByDirname('smartfaq');
        $module_id = $smartModule->getVar('mid');

        $gpermHandler = xoops_getHandler('groupperm');
        $groups = ($xoopsUser) ? ($xoopsUser->getGroups()): XOOPS_GROUP_ANONYMOUS;
        $userIsAdmin = sf_userIsAdmin();

        if ($userid != 0) {
            $criteriaUser = new CriteriaCompo();
            $criteriaUser->add(new Criteria('faq.uid', $userid), 'OR');
            $criteriaUser->add(new Criteria('answer.uid', $userid), 'OR');
        }

        If ($queryarray) {
            $criteriaKeywords = new CriteriaCompo();
            for ($i = 0, $iMax = count($queryarray); $i < $iMax; ++$i) {
                $criteriaKeyword = new CriteriaCompo();
                $criteriaKeyword->add(new Criteria('faq.question', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new Criteria('answer.answer', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeywords->add($criteriaKeyword, $andor);
            }
        }

        // Categories for which user has access
        if (!$userIsAdmin) {
            $categoriesGranted = $gpermHandler->getItemIds('category_read', $groups, $module_id);
            $grantedCategories = new Criteria('faq.categoryid', "(".implode(',', $categoriesGranted).")", 'IN');
        }
        // FAQs for which user has access
        if (!$userIsAdmin) {
            $faqsGranted = $gpermHandler->getItemIds('item_read', $groups, $module_id);
            $grantedFaq = new Criteria('faq.faqid', "(".implode(',', $faqsGranted).")", 'IN');
        }

        $criteriaPermissions = new CriteriaCompo();
        if (!$userIsAdmin) {
            $criteriaPermissions->add($grantedCategories, 'AND');
            $criteriaPermissions->add($grantedFaq, 'AND');
        }

        $criteriaAnswersStatus = new CriteriaCompo();
        $criteriaAnswersStatus->add(new Criteria('answer.status', _SF_AN_STATUS_APPROVED));

        $criteriaFasStatus = new CriteriaCompo();
        $criteriaFasStatus->add(new Criteria('faq.status', _SF_STATUS_OPENED), 'OR');
        $criteriaFasStatus->add(new Criteria('faq.status', _SF_STATUS_PUBLISHED), 'OR');

        $criteria = new CriteriaCompo();
        If (!empty($criteriaUser)) {
            $criteria->add($criteriaUser, 'AND');
        }

        If (!empty($criteriaKeywords)) {
            $criteria->add($criteriaKeywords, 'AND');
        }

        If (!empty($criteriaPermissions) && (!$userIsAdmin)) {
            $criteria->add($criteriaPermissions);
        }

        If (!empty($criteriaAnswersStatus)) {
            $criteria->add($criteriaAnswersStatus, 'AND');
        }

        If (!empty($criteriaFasStatus)) {
            $criteria->add($criteriaFasStatus, 'AND');
        }

        $criteria->setLimit($limit);
        $criteria->setStart($offset);
        $criteria->setSort('faq.datesub');
        $criteria->setOrder('DESC');

        $sql = 'SELECT faq.faqid FROM '.$this->db->prefix('smartfaq_faq') . ' as faq INNER JOIN '.$this->db->prefix('smartfaq_answers') . ' as answer ON faq.faqid = answer.faqid';

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $whereClause = $criteria->renderWhere();

            If ($whereClause != 'WHERE ()') {
                $sql .= ' '.$criteria->renderWhere();
                if ($criteria->getSort() != '') {
                    $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
                }
                $limit = $criteria->getLimit();
                $start = $criteria->getStart();
            }
        }

        //echo "<br>" . $sql . "<br>";

        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            echo "- query did not work -";

            return $ret;
        }

        If (count($result) == 0) {
            return $ret;
        }

        while ($myrow = $this->db->fetchArray($result)) {
            $faq = new sfFaq($myrow['faqid']);
            $ret[] =& $faq;
            unset($faq);
        }

        return $ret;
        }*/
}
