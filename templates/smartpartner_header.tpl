<!-- Thank you for keeping this line in the template:-) //-->
<div style="display: none;"><{$ref_smartpartner}></div>

<div class="smartpartner_headertable">
    <{if $module_home}>
        <span class="smartpartner_modulename"><{$module_home}></span>
    <{/if}> <{if $categoryPath}>
        <span class="smartpartner_breadcrumb">
        <{if $module_home}>
            &gt;
        <{/if}>
            <{$categoryPath}></span>
    <{/if}>
</div>
