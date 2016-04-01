<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

/* THIS FILE COMES FROM SMARTSECTION AND NEEDS TO EDITED TO WORK... */

global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $smartPartnerItemHandler;
$startitem = isset($_GET['startitem']) ? (int)$_GET['startitem'] : 0;

$items_title = _AM_SPARTNER_CAT_ITEMS;
$items_info  = _AM_SPARTNER_CAT_ITEMS_DSC;
$sel_cat     = $categoryid;

smartpartner_collapsableBar('bottomtable', 'bottomtableicon', $items_title, $items_info);

// Get the total number of published ITEMS
$totalitems = $smartPartnerItemHandler->getItemsCount($sel_cat, array(_SPARTNER_STATUS_PUBLISHED));

// creating the items objects that are published
$itemsObj = $smartPartnerItemHandler->getAllPublished($xoopsModuleConfig['perpage'], $startitem, $sel_cat);

$totalitemsOnPage = count($itemsObj);

$allcats = $smartPartnerCategoryHandler->getObjects(null, true);
echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
echo '<tr>';
echo "<td width='40' class='bg3' align='center'><b>" . _AM_SPARTNER_ITEMID . '</b></td>';
echo "<td width='20%' class='bg3' align='left'><b>" . _AM_SPARTNER_ITEMCOLNAME . '</b></td>';
echo "<td class='bg3' align='left'><b>" . _AM_SPARTNER_ITEMDESC . '</b></td>';
echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_CREATED . '</b></td>';
echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . '</b></td>';
echo '</tr>';

if ($totalitems > 0) {
    global $pathIcon16;
    for ($i = 0; $i < $totalitemsOnPage; ++$i) {
        $categoryObj =& $allcats[$itemsObj[$i]->categoryid()];
        $modify      = "<a href='item.php?op=mod&amp;itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . $pathIcon16 . '/edit.png' . "'  title='" . _AM_SPARTNER_EDITITEM . "' alt='" . _AM_SPARTNER_EDITITEM . "' /></a>";
        $delete      = "<a href='item.php?op=del&amp;itemid=" . $itemsObj[$i]->itemid() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_DELETEITEM . "' alt='" . _AM_SPARTNER_DELETEITEM . "'/></a>";

        echo '<tr>';
        echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . '</td>';
        echo "<td class='even' align='left'>" . $categoryObj->name() . '</td>';
        echo "<td class='even' align='left'>" . $itemsObj[$i]->getitemLink() . '</td>';

        echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub('s') . '</td>';
        echo "<td class='even' align='center'> $modify $delete </td>";
        echo '</tr>';
    }
} else {
    $itemid = -1;
    echo '<tr>';
    echo "<td class='head' align='center' colspan= '7'>" . _AM_SPARTNER_NOITEMS . '</td>';
    echo '</tr>';
}
echo "</table>\n";
echo "<br />\n";
$pagenav_extra_args = "op=mod&categoryid=$sel_cat&parentid=$parentid";
$pagenav            = new XoopsPageNav($totalitems, $xoopsModuleConfig['perpage'], $startitem, 'startitem', $pagenav_extra_args);
echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
echo "<input type='button' name='button' onclick=\"location='item.php?op=mod&categoryid=" . $sel_cat . "'\" value='" . _AM_SPARTNER_CREATEITEM . "'>&nbsp;&nbsp;";
echo '</div>';
