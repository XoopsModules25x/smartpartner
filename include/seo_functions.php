<?php
/*
 * $Id
 * Module: SmartPartner
 * Author: Sudhaker Raj <http://xoops.biz>
 * Licence: GNU
 */

// define SEO_ENABLED in mainfile.php, possible values
//   are "rewrite" & "path-info"
/**
 * @param  string $title
 * @return string
 */
function smartpartner_seo_title($title = '')
{
    $words = preg_split('/[^0-9a-z.]+/', strtolower($title), -1, PREG_SPLIT_NO_EMPTY);
    if (count($words) > 0) {
        return implode($words, '-') . '.html';
    } else {
        return '';
    }
}

// TODO: The SEO feature is not fully implemented in the module...
/**
 * @param         $op
 * @param         $id
 * @param  string $title
 * @return string
 */
function smartpartner_seo_genUrl($op, $id, $title = '')
{
    if (defined('SEO_ENABLED')) {
        if (SEO_ENABLED === 'rewrite') {
            // generate SEO url using htaccess
            return XOOPS_URL . "/smartpartner.${op}.${id}/" . smartpartner_seo_title($title);
        } elseif (SEO_ENABLED === 'path-info') {
            // generate SEO url using path-info
            return XOOPS_URL . "/modules/smartpartner/seo.php/${op}.${id}/" . smartpartner_seo_title($title);
        } else {
            die('Unknown SEO method.');
        }
    } else {
        // generate classic url
        switch ($op) {
            case 'category':
                return XOOPS_URL . "/modules/smartpartner/index.php?view_category_id=${id}";
            case 'item':
            case 'print':
                return XOOPS_URL . "/modules/smartpartner/${op}.php?itemid=${id}";
            default:
                die('Unknown SEO operation.');
        }
    }
}
