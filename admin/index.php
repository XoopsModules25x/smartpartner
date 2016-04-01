<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (http://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$indexAdmin = new ModuleAdmin();

$folder = array(
    XOOPS_ROOT_PATH . '/uploads/smartpartner/images/',
    XOOPS_ROOT_PATH . '/uploads/smartpartner/images/category/'
);
//---------------------

// Creating the Partner handler object
$smartPartnerPartnerHandler = smartpartner_gethandler('partner');

// Total Partners -- includes everything on the table
$totalpartners = $smartPartnerPartnerHandler->getPartnerCount(_SPARTNER_STATUS_ALL);

// Total Submitted Partners
$totalsubmitted = $smartPartnerPartnerHandler->getPartnerCount(_SPARTNER_STATUS_SUBMITTED);

// Total active Partners
$totalactive = $smartPartnerPartnerHandler->getPartnerCount(_SPARTNER_STATUS_ACTIVE);

// Total inactive Partners
$totalinactive = $smartPartnerPartnerHandler->getPartnerCount(_SPARTNER_STATUS_INACTIVE);

// Total rejected Partners
$totalrejected = $smartPartnerPartnerHandler->getPartnerCount(_SPARTNER_STATUS_REJECTED);

//create info block
$indexAdmin->addInfoBox(_AM_SPARTNER_INVENTORY);

if ($totalsubmitted > 0) {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . '<a href="category.php">' . _AM_SPARTNER_TOTAL_SUBMITTED . '</a><b>' . '</infolabel>', $totalsubmitted, 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . _AM_SPARTNER_TOTAL_SUBMITTED . '</infolabel>', $totalsubmitted, 'Green');
}
if ($totalactive > 0) {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . '<a href="partner.php">' . _AM_SPARTNER_TOTAL_ACTIVE . '</a><b>' . '</infolabel>', $totalactive, 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . _AM_SPARTNER_TOTAL_ACTIVE . '</infolabel>', $totalactive, 'Green');
}
if ($totalrejected > 0) {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . '<a href="main.php">' . _AM_SPARTNER_TOTAL_REJECTED . '</a><b>' . '</infolabel>', $totalrejected, 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . _AM_SPARTNER_TOTAL_REJECTED . '</infolabel>', $totalrejected, 'Green');
}
if ($totalinactive > 0) {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . '<a href="main.php">' . _AM_SPARTNER_TOTAL_INACTIVE . '</a><b>' . '</infolabel>', $totalinactive, 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_AM_SPARTNER_INVENTORY, '<infolabel>' . _AM_SPARTNER_TOTAL_INACTIVE . '</infolabel>', $totalinactive, 'Green');
}
//---------------------

foreach (array_keys($folder) as $i) {
    $indexAdmin->addConfigBoxLine($folder[$i], 'folder');
    $indexAdmin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

include_once __DIR__ . '/admin_footer.php';
