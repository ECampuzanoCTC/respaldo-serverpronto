<?php
//echo "<pre>"; print_r($milestones);exit;
?>
<div id="milestonelist" style="min-height:400px"> </div>
<div id="caseLoader" style="display: none;">
<div class="loadingdata" >
	<img title="loading..." alt="loading..." src="<?php echo HTTP_ROOT?>/img/ajax-loader.gif">
Loading...
</div>
<script type="text/javascript">
$(document).ready(function(){
	var projid = $("#projFil").val();
	var ganttprjcookie = getCookie('prjid');
	var pname = getCookie('pjname');;
	if(ganttprjcookie){
		projid= ganttprjcookie;
	}
	if(pname){
		$('#pname_dashboard').html(pname);
	}
	$("#caseLoader").css("display", "block");
	$.post(
			HTTP_ROOT + "Ganttchart/get_milestones",
			{prjid : projid},
			function(res){
				$("#milestonelist").html(res);
			}
		);
});
</script>