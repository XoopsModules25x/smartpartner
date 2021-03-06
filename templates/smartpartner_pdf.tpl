<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
    <meta http-equiv="content-language" content="fr"/>
    <meta name="robots" content="index,follow"/>
    <meta name="keywords" content="InBox, Solutions"/>
    <meta name="description"
          content="InBox Solutions propose une gamme complète de solutions pour sites web dentreprises. De la simple conception de site au développement dapplications web performantes, InBox Solutions est votre seul arrêt pour tout vos besoins web !"/>
    <meta name="rating" content="general"/>
    <meta name="author" content="XOOPS"/>
    <meta name="copyright" content="Copyright &copy; 2001-2003"/>
    <meta name="generator" content="XOOPS"/>
    <title>InBox Solutions - SmartPartner - Colloque technique et scientifique sur la réseautique avancée</title>
    <link href="http://marcan/smart/favicon.ico" rel="SHORTCUT ICON"/>
    <link rel="stylesheet" type="text/css" media="screen" href="http://marcan/smart/xoops.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="http://marcan/smart/themes/default/styleNN.css"/>

    <!-- RMV: added module header -->
    <meta name="multilanguages" content="XOOPS Multilanguages is developed by The SmartFactory (http://www.smartfactory.ca), a division of InBox Solutions (http://www.inboxsolutions.net)"/>
    <link rel='stylesheet' type='text/css' href='http://marcan/smart/modules/smartpartner//smartpartner.css'/>
    <script type="text/javascript">
        <!--
        //--></script>
    <script type="text/javascript" src="http://marcan/smart/include/xoops.js"></script>
    <script type="text/javascript"><!--
        //-->
    </script>
</head>

<!-- Thank you for keeping this line in the template:-) //-->
<div style="display: none;"><{$ref_smartpartner}></div>

<{if $backtoindex}>
    <div class="smartpartner_backlink">
        <a href="<{$modulepath}>"><{$lang_backtoindex}></a></div>
<{/if}> <span class="smartpartner_infotitle"><{$lang_partnerstitle}></span>
<table class='inner' cellspacing='1' width='98%'>
    <tr>
        <td width="60%" valign="top">
            <div class="smartpartner_partnertitle">
                <{$partner.urllink}><img style='float: right; padding: 10px;' src='<{$partner.image}>' alt='<{$partner.clean_title}>' title='<{$partner.clean_title}>' align='right'
                                         border='5px'/></a><{$partner.title}> <{if $isAdmin}>
                    <a href="<{$xoops_url}>/modules/smartpartner/admin/partner.php?op=mod&id=<{$partner.id}>"><img src="<{xoModuleIcons16 edit.png}>" title="<{$lang_edit}>" alt="<{$lang_edit}>"/></a>
                    <a href="<{$xoops_url}>/modules/smartpartner/admin/partner.php?op=del&id=<{$partner.id}>"><img src="<{xoModuleIcons16 delete.png}>" title="<{$lang_delete}>"
                                                                                                                   alt="<{$lang_delete}>"/></a>
                <{/if}>
            </div>
            <div class="smartpartner_partnersummary">
                <{$partner.description}>
            </div>
        </td>
    </tr>
</table>

<br>

<table width="100%">
    <tr>
        <td>
            <{if $partner.contact_name || $partner.contact_email || $partner.contact_phone || $partner.adress || $partner.url}>
                <table width="49%" style="font-size: 10px;" class='outer' cellspacing='1'>
                    <tr>
                        <td class="itemHead" colspan="2">
                            <span style="font-weight: bold;"><{$lang_partner_informations}></span></td>
                    </tr>
                    <{if $partner.contact_name}>
                        <tr>
                            <td class="even" width="80px">
                                <div style="font-weight: bold; text-align: center;"><{$lang_contact}></div>
                            </td>
                            <td class="odd"><{$partner.contact_name}></td>
                        </tr>
                    <{/if}> <{if $partner.contact_email}>
                        <tr>
                            <td class="even" width="80px">
                                <div style="font-weight: bold; text-align: center;"><{$lang_email}></div>
                            </td>
                            <td class="odd">
                                <a href="mailto:<{$partner.contact_email}>"><{$partner.contact_email}></a>
                            </td>
                        </tr>
                    <{/if}> <{if $partner.contact_phone}>
                        <tr>
                            <td class="even" width="80px">
                                <div style="font-weight: bold; text-align: center;"><{$lang_phone}></div>
                            </td>
                            <td class="odd"><{$partner.contact_phone}></td>
                        </tr>
                    <{/if}> <{if $partner.adress}>
                        <tr>
                            <td class="even" width="80px" valign="top">
                                <div style="font-weight: bold; text-align: center;"><{$lang_adress}></div>
                            </td>
                            <td class="odd"><{$partner.adress}></td>
                        </tr>
                    <{/if}> <{if $partner.url}>
                        <tr>
                            <td class="even" width="80px">
                                <div style="font-weight: bold; text-align: center;"><{$lang_website}></div>
                            </td>
                            <td class="odd">
                                <a href="vpartner.php?id=<{$partner.id}>" target="_blank"><{$partner.url}></a>
                            </td>
                        </tr>
                    <{/if}>
                </table>
            <{/if}>
        </td>
        <{if $show_stats_block}>
        <td width="2%">
            &nbsp;
        </td>
        <td valign="top">
            <table style="text-align: right; font-size: 10px;" class='outer' cellspacing='1'>
                <tr>
                    <td class="itemHead"><span style="font-weight: bold;"><{$lang_stats}></span></td>
                </tr>
                <tr>
                    <td class="odd"><{$lang_page_been_seen}> <{$partner.hits_page}>
                        <span style="font-weight: bold;"><{$lang_times}></span></td>
                </tr>
                <tr>
                    <td class="odd"><{$lang_url_been_visited}> <{$partner.hits}>
                        <span style="font-weight: bold;"><{$lang_times}></span></td>
                </tr>
                <table>
                    <{else}>
                    <table>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <table>
                            </td>    <{/if}></tr>
                        </table>
                        <!-- Technically, a close table tage should not be required, but if I don't put one, the notification table shows in the Stats table... //-->
                    </table>


                    <table class="PageTitle">
                        <tr>
                            <td><h2><{$wiwimod.title}></h2></td>
                            <td style="text-align:right;">
                                <{foreach from=$parentlist item=pg}>[<{$pg}>]&nbsp;>&nbsp;<{/foreach}> [<{$wiwimod.keyword}>]
                            </td>
                        </tr>
                    </table>

                    <{$wiwimod.body}>
                    <div style="text-align: right; padding: 3px; margin: 0; color: #8090B1;"><span style="border-top: 1px solid #8090B1; padding: 3px; font-style:italic; font-size:small;">
<{$_MD_WIWI_MODIFIED_TXT}> <{$wiwimod.lastmodified}> <{$_MD_WIWI_BY}> <span class="itemPoster"><{$wiwimod.author}></span>
</span></div>
