<?php
// $Id: partner_cat_link.php,v 1.1 2007/09/18 14:00:57 marcan Exp $
// ------------------------------------------------------------------------ //
// 				 XOOPS - PHP Content Management System                      //
//					 Copyright (c) 2000 XOOPS.org                           //
// 						<http://www.xoops.org/>                             //
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
// URL: http://www.xoops.org/												//
// Project: The XOOPS Project                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once XOOPS_ROOT_PATH . "/modules/smartobject/class/smartobject.php";
include_once XOOPS_ROOT_PATH . "/modules/smartobject/class/smartobjecthandler.php";
class SmartpartnerPartner_cat_link extends SmartObject
{

    function SmartpartnerPartner_cat_link()
    {
        $this->initVar('partner_cat_linkid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('partnerid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('categoryid', XOBJ_DTYPE_INT, '', true);
    }
}

class SmartpartnerPartner_cat_linkHandler extends SmartPersistableObjectHandler
{
    function SmartpartnerPartner_cat_linkHandler($db)
    {

        $this->SmartPersistableObjectHandler($db, 'partner_cat_link', array('partnerid', 'categoryid'), '', false, 'smartpartner');
    }

    function getParentIds($partnerid)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('partnerid', $partnerid));
        $links = $this->getObjects($criteria);
        $parent_array = array();
        foreach ($links as $link) {
            $parent_array[] = $link->getVar('categoryid');
        }

        return implode('|', $parent_array);
    }
}
