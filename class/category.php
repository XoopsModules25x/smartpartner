<?php
// 
// ------------------------------------------------------------------------ //
//               XOOPS - PHP Content Management System                      //
//                   Copyright (c) 2000-2016 XOOPS.org                           //
//                      <http://xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: http://xoops.org/                                               //
// Project: XOOPS Project                                               //
// -------------------------------------------------------------------------//
if (!class_exists('smartpartner_PersistableObjectHandler')) {
    include_once XOOPS_ROOT_PATH . '/modules/smartpartner/class/object.php';
}

/**
 * Class SmartpartnerCategory
 */
class SmartpartnerCategory extends XoopsObject
{
    /**
     * SmartpartnerCategory constructor.
     */
    public function __construct()
    {
        $this->initVar('categoryid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('parentid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, null, false, 255);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('total', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false);
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
    public function parentid()
    {
        return $this->getVar('parentid');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function name($format = 'S')
    {
        $ret = $this->getVar('name', $format);
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
    public function description($format = 'S')
    {
        return $this->getVar('description', $format);
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
     * @param  bool $falseIfNoImage
     * @return bool|mixed|string
     */
    public function getImageUrl($falseIfNoImage = false)
    {
        if (($this->getVar('image') !== '') && ($this->getVar('image') !== 'blank.png') && ($this->getVar('image') !== '-1')) {
            return smartpartner_getImageDir('category', false) . $this->image();
        } elseif ($falseIfNoImage) {
            return false;
        } elseif (!$this->getVar('image_url')) {
            return smartpartner_getImageDir('category', false) . 'blank.png';
        } else {
            return $this->getVar('image_url');
        }
    }

    /**
     * @return mixed
     */
    public function weight()
    {
        return $this->getVar('weight');
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return ($this->getVar('categoryid') == -1);
    }

    /**
     * @param  bool $withAllLink
     * @return mixed|string
     */
    public function getCategoryPath($withAllLink = true)
    {
        $filename = 'category.php';
        if ($withAllLink) {
            $ret = $this->getCategoryLink();
        } else {
            $ret = $this->name();
        }
        $parentid = $this->parentid();
        global $smartPartnerCategoryHandler;
        if ($parentid != 0) {
            $parentObj =& $smartPartnerCategoryHandler->get($parentid);
            if ($parentObj->notLoaded()) {
                exit;
            }
            $parentid = $parentObj->parentid();
            $ret      = $parentObj->getCategoryPath($withAllLink) . ' > ' . $ret;
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getCategoryUrl()
    {
        return smartpartner_seo_genUrl('category', $this->categoryid(), $this->name());
    }

    /**
     * @param  bool $class
     * @return string
     */
    public function getCategoryLink($class = false)
    {
        if ($class) {
            return "<a class='$class' href='" . $this->getCategoryUrl() . "'>" . $this->name() . '</a>';
        } else {
            return "<a href='" . $this->getCategoryUrl() . "'>" . $this->name() . '</a>';
        }
    }

    /**
     * @param  bool $sendNotifications
     * @param  bool $force
     * @return mixed
     */
    public function store($sendNotifications = true, $force = true)
    {
        global $smartPartnerCategoryHandler;
        $ret = $smartPartnerCategoryHandler->insert($this, $force);
        if ($sendNotifications && $ret && $this->isNew()) {
            $this->sendNotifications();
        }
        $this->unsetNew();

        return $ret;
    }

    public function sendNotifications()
    {
        $hModule     = xoops_getHandler('module');
        $smartModule =& $hModule->getByDirname('smartpartner');
        $module_id   = $smartModule->getVar('mid');

        $myts                = MyTextSanitizer::getInstance();
        $notificationHandler = xoops_getHandler('notification');

        $tags                  = array();
        $tags['MODULE_NAME']   = $myts->displayTarea($smartModule->getVar('name'));
        $tags['CATEGORY_NAME'] = $this->name();
        $tags['CATEGORY_URL']  = $this->getCategoryUrl();

        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global_item', 0, 'category_created', $tags);
    }

    /**
     * @param  array $category
     * @return array
     */
    public function toArray($category = array())
    {
        $category['categoryid']   = $this->categoryid();
        $category['name']         = $this->name();
        $category['categorylink'] = $this->getCategoryLink();
        $category['total']        = $this->getVar('itemcount');
        $category['description']  = $this->description();

        if ($this->image() !== 'blank.png') {
            $category['image_path'] = smartpartner_getImageDir('category', false) . $this->image();
        } else {
            $category['image_path'] = '';
        }

        return $category;
    }
}

/**
 * Class SmartpartnerCategoryHandler
 */
class SmartpartnerCategoryHandler extends smartpartner_PersistableObjectHandler
{
    /**
     * SmartpartnerCategoryHandler constructor.
     * @param object|XoopsDatabase $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'smartpartner_categories', 'SmartpartnerCategory', 'categoryid', 'name');
    }

    /**
     * @param  XoopsObject $category
     * @param  bool        $force
     * @return bool
     */
    public function delete(XoopsObject $category, $force = false)
    {
        /*if (parent::delete($object, $force)) {
            global $xoopsModule;

            // TODO: Delete partners in this category
            return true;
        }

        return false;*/

        if (strtolower(get_class($category)) !== 'smartpartnercategory') {
            return false;
        }

        // Deleting the partners
        global $smartPartnerPartnerHandler;
        if (!isset($smartPartnerPartnerHandler)) {
            $smartPartnerPartnerHandler = smartpartner_gethandler('partner');
        }
        $criteria = new Criteria('category', $category->categoryid());
        $partners =& $smartPartnerPartnerHandler->getObjects($criteria);
        if ($partners) {
            foreach ($partners as $partner) {
                $smartPartnerPartnerHandler->delete($partner);
            }
        }

        // Deleteing the sub categories
        $subcats = $this->getCategories(0, 0, $category->categoryid());
        foreach ($subcats as $subcat) {
            $this->delete($subcat);
        }

        $sql = sprintf('DELETE FROM %s WHERE categoryid = %u ', $this->db->prefix('smartpartner_categories'), $category->getVar('categoryid'));

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
     * @param  int    $limit
     * @param  int    $start
     * @param  int    $parentid
     * @param  string $sort
     * @param  string $order
     * @param  bool   $id_as_key
     * @return array
     */
    public function getCategories($limit = 0, $start = 0, $parentid = 0, $sort = 'weight', $order = 'ASC', $id_as_key = true)
    {
        $criteria = new CriteriaCompo();

        $criteria->setSort($sort);
        $criteria->setOrder($order);

        if ($parentid != -1) {
            $criteria->add(new Criteria('parentid', $parentid));
        }

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $ret = $this->getObjects($criteria, $id_as_key);

        return $ret;
    }

    /**
     * @param  int $parentid
     * @return int
     */
    public function getCategoriesCount($parentid = 0)
    {
        if ($parentid == -1) {
            return $this->getCount();
        }
        $criteria = new CriteriaCompo();
        if (isset($parentid) && ($parentid != -1)) {
            $criteria->add(new criteria('parentid', $parentid));
        }

        return $this->getCount($criteria);
    }
}
