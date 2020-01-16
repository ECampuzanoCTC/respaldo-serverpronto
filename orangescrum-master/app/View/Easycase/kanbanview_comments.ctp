<style>
    .popup_bg{width:auto !important;max-width:700px}
    .status-report table th{border-bottom: 2px solid #ccc;border-top: 1px solid #ccc;padding: 10px;}
    .popup_form .status-report table tr td { padding: 10px;text-align: left;font-size: 14px}
</style>
<div class="data-scroll status-report">
    <table cellpadding="0" cellspacing="0" class="col-lg-12 ">
        <tr>
            <th style ="text-align: left">#</th>
            <th style ="text-align: left" >User Name</th>
            <th style ="text-align: left">Comment</th>
            <th style ="text-align: left">Date / Time</th>
        </tr>
        <?php
        $i = 1;
        foreach ($comment_arr as $skey => $sval) {
            ?>
            <tr>
                <td style ="text-align: left"><?php echo $i; ?> </td>
                <td style ="text-align: left"><?php echo $sval['username']; ?> </td>
                <td style ="text-align: left" onmouseover="boldAdvise(this)" data-value="<?php echo $sval['comment']; ?>" style = width: 20em; overflow: hidden;"><?php echo strlen($sval['comment']) > 100 ? substr(wordwrap($sval['comment'], 50, "\n"), 0, 100) . "...." : substr(wordwrap($sval['comment'], 50, "\n"), 0, 100); ?> </td>
                <td style ="text-align: left"><?php echo $sval['date_time']; ?> </td>
            </tr>
            <?php
            $i++;
        }
        ?>
<?php if (count($comment_arr) > 5) { ?>
            <tr>
                <td></td>
                <td>
                    <div class="fl">
                        <span id="ldr" style="display:none;">
                            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." />
                        </span>
                    </div>
                </td>
                <td></td>
                <td><span class="or_cancel" ><a href="javascript:void(0)" id="btn_edit_project_template" class=" btn_cmn_efect cmn_bg cmn_size" onclick='redirectMore("<?php echo $uid1; ?>")'><?php echo __("View More"); ?></a></span></td>
            </tr>
<?php } ?>
    </table>
</div>
<script type="text/javascript">
    function redirectMore(caseUniqId) {
        var url1 = HTTP_ROOT + 'dashboard#details/' + caseUniqId;
        closePopup();
        window.location.href = url1;
    }
    function boldAdvise(src) {
        var comment = $(src).attr('data-value');
        src.title = comment;
        return;
    }
</script>