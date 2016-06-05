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

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
include_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecthandler.php';

/**
 * Class SmartpartnerPartnerCatLink
 */
class SmartpartnerPartner_cat_link extends SmartObject
{
    /**
     * SmartpartnerPartnerCatLink constructor.
     */
    public function __construct()
    {
        $this->initVar('partner_cat_linkid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('partnerid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('categoryid', XOBJ_DTYPE_INT, '', true);
    }
}

/**
 * Class SmartpartnerPartnerCatLinkHandler
 */
class SmartpartnerPartner_cat_linkHandler extends SmartPersistableObjectHandler
{
    /**
     * SmartpartnerPartnerCatLinkHandler constructor.
     * @param object|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'partner_cat_link', array('partnerid', 'categoryid'), '', false, 'smartpartner');
    }

    /**
     * @param $partnerid
     * @return string
     */
    public function getParentIds($partnerid)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('partnerid', $partnerid));
        $links        = $this->getObjects($criteria);
        $parent_array = array();
        foreach ($links as $link) {
            $parent_array[] = $link->getVar('categoryid');
        }

        return implode('|', $parent_array);
    }
}
