<?php
// 
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://xoops.org/, http://jp.xoops.org/ //
// Project: XOOPS Project                                                    //
// ------------------------------------------------------------------------- //

/**
 * Class SmartTree
 */
class smarttree
{
    public $table; //table with parent-child structure
    public $id; //name of unique id for records in table $table
    public $pid; // name of parent id used in table $table
    public $order; //specifies the order of query results
    public $title; // name of a field in table $table which will be used when  selection box and paths are generated
    public $db;

    //constructor of class SmartTree
    //sets the names of table, unique id, and parend id
    /**
     * SmartTree constructor.
     * @param $table_name
     * @param $id_name
     * @param $pid_name
     */
    public function __construct($table_name, $id_name, $pid_name)
    {
        $this->db    = XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $table_name;
        $this->id    = $id_name;
        $this->pid   = $pid_name;
    }

    // returns an array of first child objects for a given id($sel_id)
    /**
     * @param         $sel_id
     * @param  string $order
     * @return array
     */
    public function getFirstChild($sel_id, $order = '')
    {
        $arr = array();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $arr;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            array_push($arr, $myrow);
        }

        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($sel_id)
    /**
     * @param $sel_id
     * @return array
     */
    public function getFirstChildId($sel_id)
    {
        $idarray = array();
        $result  = $this->db->query('SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '');
        $count   = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list($id) = $this->db->fetchRow($result)) {
            array_push($idarray, $id);
        }

        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)
    /**
     * @param         $sel_id
     * @param  string $order
     * @param  array  $idarray
     * @return array
     */
    public function getAllChildId($sel_id, $order = '', $idarray = array())
    {
        $sql = 'SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list($r_id) = $this->db->fetchRow($result)) {
            array_push($idarray, $r_id);
            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }

        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)
    /**
     * @param         $sel_id
     * @param  string $order
     * @param  array  $idarray
     * @return array
     */
    public function getAllParentId($sel_id, $order = '', $idarray = array())
    {
        $sql = 'SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        list($r_id) = $this->db->fetchRow($result);
        if ($r_id == 0) {
            return $idarray;
        }
        array_push($idarray, $r_id);
        $idarray = $this->getAllParentId($r_id, $order, $idarray);

        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)
    // the path is delimetered with "/"
    /**
     * @param         $sel_id
     * @param         $title
     * @param  string $path
     * @return string
     */
    public function getPathFromId($sel_id, $title, $path = '')
    {
        $result = $this->db->query('SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list($parentid, $name) = $this->db->fetchRow($result);
        $myts = MyTextSanitizer::getInstance();
        $name = $myts->htmlSpecialChars($name);
        $path = '/' . $name . $path . '';
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getPathFromId($parentid, $title, $path);

        return $path;
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    /**
     * @param        $title
     * @param string $order
     * @param int    $preset_id
     * @param int    $none
     * @param string $sel_name
     * @param string $onchange
     * @param bool   $multiple
     */
    public function makeMySelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '', $multiple = false)
    {
        global $myts;
        if ($sel_name == '') {
            $sel_name = $this->id;
        }
        $myts = MyTextSanitizer::getInstance();
        /*
           * Hack by felix<INBOX> for ampersand
           * allow multiple select
           */
        //echo "<select name='".$sel_name."'";
        if ($multiple) {
            echo "<select name='" . $sel_name . "[]'  multiple='multiple'";
        } else {
            echo "<select name='" . $sel_name . "'";
        }
        /*
           * End of Hack by felix<INBOX> for ampersand
           * allow multiple select
           */
        if ($onchange != '') {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = 'SELECT ' . $this->id . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=0';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>----</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = '';
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            /*
                * Hack by felix<INBOX> for ampersand
                * allow multiple select
                */
            if (is_array($preset_id) && in_array($catid, $preset_id)) {
                $sel = " selected='selected'";
            }
            /*
                * End of Hack by felix<INBOX> for ampersand
                * allow multiple select
                */
            // MT hack added by hsalazar //
            //$name = $myts->formatForML($name);
            // MT hack added by hsalazar //
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = '';
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);
                $catpath          = $option['prefix'] . '&nbsp;' . $myts->htmlSpecialChars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                /*
                     * Hack by felix<INBOX> for ampersand
                     * allow multiple select
                     */
                if (is_array($preset_id) && in_array($option[$this->id], $preset_id)) {
                    $sel = " selected='selected'";
                }
                /*
                     * End of Hack by felix<INBOX> for ampersand
                     * allow multiple select
                     */

                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = '';
            }
        }
        echo "</select>\n";
    }

    //generates nicely formatted linked path from the root id to a given id
    /**
     * @param         $sel_id
     * @param         $title
     * @param         $funcURL
     * @param  string $path
     * @return string
     */
    public function getNicePathFromId($sel_id, $title, $funcURL, $path = '')
    {
        $sql    = 'SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id";
        $result = $this->db->query($sql);
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list($parentid, $name) = $this->db->fetchRow($result);
        $myts = MyTextSanitizer::getInstance();
        $name = $myts->htmlSpecialChars($name);
        $path = "<a href='" . $funcURL . '&amp;' . $this->id . '=' . $sel_id . "'>" . $name . '</a>&nbsp;:&nbsp;' . $path . '';
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }

    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    /**
     * @param         $sel_id
     * @param  string $path
     * @return string
     */
    public function getIdPathFromId($sel_id, $path = '')
    {
        $result = $this->db->query('SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list($parentid) = $this->db->fetchRow($result);
        $path = '/' . $sel_id . $path . '';
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    /**
     * @param  int    $sel_id
     * @param  string $order
     * @param  array  $parray
     * @return array
     */
    public function getAllChild($sel_id = 0, $order = '', $parray = array())
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            array_push($parray, $row);
            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }

        return $parray;
    }

    /**
     * @param  int    $sel_id
     * @param  string $order
     * @param  array  $parray
     * @param  string $r_prefix
     * @return array
     */
    public function getChildTreeArray($sel_id = 0, $order = '', $parray = array(), $r_prefix = '')
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';
        if ($order != '') {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            $row['prefix'] = $r_prefix . '.';
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }
}
