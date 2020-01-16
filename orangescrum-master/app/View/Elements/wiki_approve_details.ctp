<style>
.popup_form .new_customer table tr td{padding:5px 20px 5px 0px;}
.tdHeading{
	width:30%;
	text-align:right;
	font-weight:bold;
	color:#000000;
	vertical-align:top;
}
.tdDetails{
	width:70%;
	text-align:left !important;
	vertical-align:top;
}
</style>
<center><div id="wiki_appr_err_msg" style="color:#FF0000;display:none;"></div></center>

<table style="width:100%;border-collapse:inherit;" cellpadding="5">
	<tr>
		<td class="tdHeading"><?php echo __("Wiki Title"); ?> : </td>
		<td class="tdDetails displayWikiTitleCls"></td>
	</tr>
	<tr>
		<td class="tdHeading"><?php echo __("Wiki Description"); ?> : </td>
		<td class="tdDetails displayWikiDescCls"></td>
	</tr>
	<tr>
		<td class="tdHeading"><?php echo __("Link To Project"); ?> : </td>
		<td class="tdDetails displayLinktoPrjCls"></td>
	</tr>
	<tr>
		<td class="tdHeading"><?php echo __("Wiki Category"); ?> : </td>
		<td class="tdDetails displayWikiCategoryCls"></td>
	</tr>
	<tr>
		<td class="tdHeading"><?php echo __("Wiki Sub Category"); ?> : </td>
		<td class="tdDetails displayWikiSubcategoryCls"></td>
	</tr>
	<tr>
		<td class="tdHeading"><?php echo __("File Description"); ?> : </td>
		<td class="tdDetails displayFileDescCls"></td>
	</tr>
	<tr>
		<td class="tdHeading"><?php echo __("Submitted By"); ?> : </td>
		<td class="tdDetails displayCreatedByCls"></td>
	</tr>
	<tr>
		<td class="tdHeading">&nbsp;</td>
		<td class="tdDetails">
			<div class="DisplayApproveButtons">
				<span class="wiki_approve_loader" style="display:none;">
					<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader" style="margin-left:30px;padding-left:30px;"/>
				</span>
				<span class="wiki_btn_approve">
					<input type="hidden" name="hid_approve_wiki_id" id="hid_approve_wiki_id" value=""/>
					<input type="hidden" name="HidApprId" id="HidApprId" value="" />
					<input type="hidden" name="HidProjectId" id="HidProjectId" value="" />
					<input type="hidden" name="HidWikiCreatorId" id="HidWikiCreatorId" value="" />
					
					<input type="button" value="APPROVE" name="approveWiki" id="approveWikiId" class="btn mr0 fl no-border-radius" onclick="ApproveRejectWiki('approve')" style="padding-right: 12px;background-color:#258946;color:#FFF;" />
					<input type="button" value="REJECT" name="rejectWiki" id="rejectWikiId" class="btn mr0 fl no-border-radius" onclick="ApproveRejectWiki('reject')" style="padding-left: 10px;padding-right: 12px;margin-left:20px;background-color:#e21b1b;color:#FFF;" />
				</span>
			</div>
			<div class="DisplayApproveButtonsNot">
				<b style="color:#006633;"><?php echo __("This wiki is already approved."); ?></b>
			</div>
		</td>
	</tr>
</table>
