<?php
	echo $this->Html->script(array('jquery.ganttView', 'jquery-ui-1.10.3', 'date'));
	echo $this->Html->css(array('jquery-ui-1.8.4', 'jquery.ganttView', 'reset'));
?>
<div id="ganttChart"></div>
<br/><br/>
<div id="eventMessage" style="font-size:12px"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("div.ganttview-block-text[rel=tooltip], div.ganttview-vtheader-series-name[rel=tooltip], div.ganttview-vtheader-item-name[rel=tooltip] ,img").tipsy({ live:true, gravity:'s', trigger: 'hover', opacity: 0.8, title: 'title'});
});
	
	function showGanttchart(mid){
		//alert(pid);return false;
		//ajaxcall();
		//$("div").attr("rel","tooltip");
		$("#eventMessage").text("");
		$("#caseLoader").css("display", "block");
		$("#ganttChart").ganttView({ 
			dataUrl: "/Ganttchart/ganttdata/"+mid, 
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
	/*function ajaxcall(){
		$.ajax({
					url:"/Ganttchart/ganttdata",
					data:{},
					success:function(data) {
						alert(data);
					}
					});
	}*/
</script>