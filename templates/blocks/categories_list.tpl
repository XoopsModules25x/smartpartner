<table cellspacing="0" cellpadding="0">
    <tr>
        <td id="mainmenu" width="100%">
            <{foreach key = key item=cat from=$block.categories}>
                <div class="maincatlisting"><{$cat.link}></div>
                <{if $block.displaysubs && $key == $block.current}> <{foreach key = key item=subcat from=$cat.categories}>
                    <div class="subcatlisting"><{$subcat.link}></div>
                <{/foreach}>

                <{/if}> <{/foreach}>
        </td>
    </tr>
</table>
