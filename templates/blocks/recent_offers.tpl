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
            <div class="offers">
                <{foreach item=offer from=$block.offers}>
                    <div>
                        <a href="<{$xoops_url}>/modules/smartpartner/partner.php?id=<{$offer.partnerid}>">
                            <!--offer images turned off by ampersand      <{if $offer.image != ""}>
                        <img src="<{$xoops_url}>/uploads/smartpartner/offer/<{$offer.image}>"  border="0" alt="<{$offer.title}>" <{$block.fadeImage}> /><br />
                        <{/if}>--><{$offer.title}> </a><br/><br/ > <{if $block.insertBr != ""}>
                            <br/>
                            <br/>
                        <{/if}>
                    </div>
                <{/foreach}>
            </div>
        </td>
    </tr>

    <tr align="center">
        <td>
            <a href="<{$block.smartpartner_url}>offer.php"><{$block.lang_see_all}></a>
        </td>
    </tr>

</table>
