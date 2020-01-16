<?php
echo $this->Form->create('Ganttchart',array('url'=>array('controller'=>'ganttchart','action'=>'upload'),'type'=>'file'));
?>
<table>
	
	<tr>
		<td>
			<label>Upload Gantt Chart</label>
			<?php echo $this->Form->file('chart');?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $this->Form->submit('Save'); ?>
		</td>
	</tr>
</table>	
