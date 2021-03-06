<table cellspacing="0" style="padding-bottom: 5px;">
    <tr>
        <td>
            <{if $block.fadeImage != ""}> <{literal}>
                <script type="text/javascript">
                    <!--
                    nereidFadeObjects = new Object();
                    nereidFadeTimers = new Object();
                    function nereidFade(object, destOp, rate, delta) {
                        if (!document.all) {
                            return;
                        }
                        if (object != '[object]') {
                            setTimeout('nereidFade(' + object + ',' + destOp + ',' + rate + ',' + delta + ')', 0);
                            return;
                        }
                        clearTimeout(nereidFadeTimers[object.sourceIndex]);
                        diff = destOp - object.filters.alpha.opacity;
                        direction = 1;
                        if (object.filters.alpha.opacity > destOp) {
                            direction = -1;
                        }
                        delta = Math.min(direction * diff, delta);
                        object.filters.alpha.opacity += direction * delta;

                        if (object.filters.alpha.opacity != destOp) {
                            nereidFadeObjects[object.sourceIndex] = object;
                            nereidFadeTimers[object.sourceIndex] = setTimeout('nereidFade(nereidFadeObjects[' + object.sourceIndex + '],' + destOp + ',' + rate + ',' + delta + ')', rate);
                        }
                    }
                    //-->
                </script>
            <{/literal}> <{/if}>
            <div align='center'>
                <{foreach item=category from=$block.categories}> <{if $category.link}>
                    <div style="border: 1px solid grey; padding: 3px; margin-bottom: 10px;"><{$category.link}></div>
                <{/if}> <{foreach item=partner from=$category.partners}> <{$partner.urllink}> <{if $partner.image != ""}>
                    <img src="<{$partner.image}>" <{$partner.img_attr}> border="0" alt="<{$partner.title}>" <{$block.fadeImage}> />
                    <br>
                <{/if}> <{$partner.title}>        </a>        <{if $block.insertBr != ""}>
                    <br>
                    <br>
                <{/if}> <{/foreach}> <{/foreach}>


            </div>
        </td>
    </tr>
    <{if $block.see_all}>
        <tr align="center">
            <td><a href="<{$block.smartpartner_url}>"><{$block.lang_see_all}></a>
            </td>
        </tr>
    <{/if}>
</table>
