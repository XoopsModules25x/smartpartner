<!-- Thank you for keeping this line in the template:-) //-->
<div style="display: none;"><{$ref_smartpartner}></div>

<div class="smartpartner_infotitle"><{$lang_intro_title}></div>
<div class="smartpartner_infotext"><{$lang_intro_text}></div>

<{$joinform.javascript}>
<form name="<{$joinform.name}>" action="<{$joinform.action}>" method="<{$joinform.method}>" <{$joinform.extra}>>
    <table class="outer" cellspacing="1">
        <tr>
            <th colspan="2"><{$joinform.title}></th>
        </tr>
        <!-- start of form elements loop --><{foreach item=element from=$joinform.elements}> <{if $element.hidden != true}>
            <tr>
                <td class="head"><{$element.caption}> <{if $element.description}>
                        <div style="font-weight: normal;"><{$element.description}></div>
                    <{/if}>
                </td>
                <td class="<{cycle values=" even,odd"}>"><{$element.body}></td>
            </tr>
        <{else}> <{$element.body}> <{/if}> <{/foreach}><!-- end of form elements loop -->
    </table>
</form>
