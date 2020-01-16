<div class= "milestonediv noprint">
	<div id="milestonediv" class="fl">
	<span class="fl">Milestone: </span>
	<?php if(count($milestones) == 0){?>
	<span class="fl" style="margin-top:10px;color:red;"><?php echo "No Milestone in this Project";?></span>
	<div class="mlsbtn fl">
	<button id="mlist_crt_mlstbtn" class="btn btn_blue" value="Create Milestone" type="button" onclick="addEditMilestone('','','','','','');" style=" display: inline-block;"> Create Milestone </button>
	</div>
	</div></div>
	<div><img src="../img/sample/gantt_chart.jpg" style="width:98%;"></div>
	<script>
	$(document).ready(function(){
		$("#caseLoader").css("display", "none");
	});
	</script>
	<?php exit;}?>
	<select id="milestone_dropdown" class= "form-control dailyUpdate_sel fl">
	<?php foreach($milestones as $milestone){?>
		<option value="<?php echo $milestone['Milestone']['uniq_id']; ?>"><?php echo $milestone['Milestone']['title']; ?></option>
	<?php } ?>
	</select>
	</div>
	<div class="mlsbtn fl">
	<button id="mlist_crt_mlstbtn" class="btn btn_blue" value="Create Milestone" type="button" onclick="addEditMilestone('','','','','','');" style=" display: inline-block;"> Create Milestone </button>
	</div>
</div>
<div id="ganttChart"></div>
<br/><br/>
<div id="eventMessage" style="font-size:12px"></div>
<?php
	echo $this->Html->script(array('jquery.ganttView', 'jquery-ui-1.10.3', 'date'));
	echo $this->Html->css(array('jquery.ganttView', 'reset', 'style_new_v5'));
?>
<script>
$(document).ready(function(){
	$("#caseLoader").css("display", "none");
	var mluniq=$('#milestone_dropdown').val();
	var cookievalue = getCookie('mlstnid');
	var puniq = '<?php echo $puniq;?>';
	if(cookievalue){
		$('#milestone_dropdown').val(cookievalue);
		showGanttchart(cookievalue,puniq);
	}else{
		showGanttchart(mluniq,puniq);
	}
	$("#milestone_dropdown").change(function(){
		$("#caseLoader").css("display", "block");
		mluniq=$('#milestone_dropdown').val();
		createCookie('mlstnid', mluniq, 365, DOMAIN_COOKIE);
		showGanttchart(mluniq,puniq);
	});
});

function showGanttchart(mid,puniq){
		$("#eventMessage").text("");
		$("#ganttChart").ganttView({ 
			dataUrl: "/Ganttchart/ganttdata/"+mid+"/"+puniq, 
			slideWidth: 870,
			behavior: {
				clickable: true,
				draggable: true,
				resizable: true,
				onSuccess: true,
				onClick: function (data) {
					var start = new Date(data.start).toString().split(" ");
					var end = new Date(data.end).toString().split(" ");
					var msg = "Your task <b>'"+data.name+"'</b> duraion is now <b>" + start[2]+" "+start[1] + "</b> to <b>" + end[2]+" "+end[1] + "</b>";
					$("#eventMessage").html(msg);
				},
				onResize: function (data) { 
					var start = new Date(data.start).toString().split(" ");
					var end = new Date(data.end).toString().split(" ");
					//console.log(data);
					var msg = "Your task <b>'"+data.name+"'</b> duration is now <b> "+ start[2]+" "+start[1] + "</b> to <b>" + end[2]+" "+end[1] + "</b>";
					$("#eventMessage").html(msg);
					$.post(
						"/Ganttchart/changeStartEndDate",
						{data:data}
					);
				},
				onDrag: function (data) {
					var start = new Date(data.start).toString().split(" ");
					var end = new Date(data.end).toString().split(" ");
					//console.log(data);
					var msg = "Your task <b>'"+data.name+"'</b> duration is now <b> " + start[2]+" "+start[1] + "</b> to <b>" + end[2]+" "+end[1] + "</b>";
					$("#eventMessage").html(msg);
					$.post(
						"/Ganttchart/changeStartEndDate",
						{data:data}
					);
				}
			}
		});
		$("#ganttChart").html($(".ganttview").data());
	}
	function hideloader(){
		$("#caseLoader").css("display", "none");
	}
</script>