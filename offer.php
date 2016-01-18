<?php

include_once("header.php");
$xoopsOption['template_main'] = 'smartpartner_offer.html';
include XOOPS_ROOT_PATH . "/header.php";

$offers = $smartpartner_offer_handler->getObjectsForUserSide();

$xoopsTpl->assign('offers', $offers);
$xoopsTpl->assign('lang_offer_click_here', _CO_SPARTNER_OFFER_CLICKHERE);
$xoopsTpl->assign('lang_offer_intro', _MD_SPARTNER_OFFER_INTRO);
include "footer.php";
include_once(XOOPS_ROOT_PATH . "/footer.php");
