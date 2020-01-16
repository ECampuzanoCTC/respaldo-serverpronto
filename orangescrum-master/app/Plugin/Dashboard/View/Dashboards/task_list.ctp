<?php
$cnt = 0; $od_label = $td_label = 0;
$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date"); ?>
<div id="exTab2">	
<ul class="nav nav-tabs">
			<li class="active">
        <a  href="#ovrdue" data-toggle="tab"><?php echo __("Overdue Tasks"); ?></a>
			</li>
			<li><a href="#upcmng_tsks" data-toggle="tab"><?php echo __("Upcoming Tasks"); ?> </a>
			</li>
		</ul>

			<div class="tab-content ">
			  <div class="tab-pane active" id="ovrdue">
                              <?php if(isset($ovr_duetsk) && !empty($ovr_duetsk)) {
           foreach ($ovr_duetsk as $key => $value) {
	 $cnt++;
	 $due_date = '';
	 
	 $actual_dt_created = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Easycase']['actual_dt_created'], "date");
	 
	if($value['Easycase']['due_date'] != "NULL" && $value['Easycase']['due_date'] != "0000-00-00" && $value['Easycase']['due_date'] != "" && $value['Easycase']['due_date'] != "1970-01-01") {
	    $locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Easycase']['due_date'], "date");

			$due_date = $this->Datetime->facebook_datestyle($value['Easycase']['due_date']);
	    
	}
	$actual_dt_created = $this->Datetime->dateFormatOutputdateTime_day($actual_dt_created, $gmdate,"date");
    ?>
	
	
	<div class="listdv">
		<div class="fl task_title_db">
		<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>" title="<?php echo ucfirst($value['Project']['name']); ?>" style="color:#5191BD"><?php echo $this->Format->shortLength(strtoupper($value['Project']['short_name']),4); ?></a> - 
		<a href="<?php echo HTTP_ROOT; ?>dashboard#details/<?php echo $value['Easycase']['uniq_id']; ?>" title="<?php echo htmlentities($this->Format->convert_ascii($value['Easycase']['title']),ENT_QUOTES); ?>">#<?php echo $value['Easycase']['case_no'];?>: <?php echo htmlentities($this->Format->shortLength($this->Format->convert_ascii($value['Easycase']['title']),50),ENT_QUOTES); ?></a>
		</div>
	    <div class="cb"></div>
		<div class="fl" style="font-size:12px;">
                    <span style="color: #8f8f8f;"><?php echo __("Created on");?> <span class=""><?php echo $actual_dt_created; ?></span> &nbsp;&nbsp;
		   
                        <?php echo __("Overdue By"); ?>:
			<span class="tsklst_label ovrdueby" title="<?php echo $due_date;?>"><?php echo $this->Datetime->datediffernce($value['Easycase']['due_date'],'overdue');?> </span>
		    
                        </span>
		</div>
	    
	    <?php if($project == 'all' && 0){ ?>
		<div class="fr">
		    <div class="fl"><img class="prj-db" src="<?php echo HTTP_IMAGES; ?>images/u_det_proj.png"></div>
		    <div class="fl">
			<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>">
			    <div class="prj_title_db" title="<?php echo ucfirst($value['Project']['name']); ?>"><?php echo ucfirst($value['Project']['name']); ?></div>
			</a>
		    </div>
		</div>
		<?php } ?>
	    <div class="cb"></div>
	    <?php if(count($gettodos) != $cnt) { ?>
	    <div class="lstbtndv"></div>
	    <?php } ?>
	</div>
	<div class="cb"></div>
     <?php } ?>
	<div id="to_dos_more" data-value="<?php echo $total;?>" style="display: none;"></div>
     <?php } else { ?>
	<div class="mytask"></div>
	<div class="mytask_txt">
        <?php if(isset($username)){ ?>
        No tasks assigned to <?php echo $username['User']['name']; ?>
        <?php } ?>
    </div>
    <div id="to_dos_more" data-value="0" style="display: none;"></div>
<?php } ?>

				</div>
				<div class="tab-pane" id="upcmng_tsks">
                                    <?php if(isset($upcmng_tsks) && !empty($upcmng_tsks)) {
           foreach ($upcmng_tsks as $key => $value) {
	 $cnt++;
	 $due_date = '';
	 
	 $actual_dt_created = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Easycase']['actual_dt_created'], "datetime");
	 
	if($value['Easycase']['gantt_start_date'] != "NULL" && $value['Easycase']['gantt_start_date'] != "0000-00-00" && $value['Easycase']['gantt_start_date'] != "" && $value['Easycase']['gantt_start_date'] != "1970-01-01") {
	    $locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Easycase']['gantt_start_date'], "datetime");

	    
			$due_date = $this->Datetime->dateFormatOutputdateTime_day($locDT, $gmdate,"time");
                       // echo $value['Easycase']['gantt_start_date'] ."<br/>".$locDT."<br/>".$due_date;exit;
	   
	}
	$actual_dt_created = $this->Datetime->dateFormatOutputdateTime_day($actual_dt_created, $gmdate,"time");
    ?>
	
	
	<div class="listdv">
		<div class="fl task_title_db">
		<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>" title="<?php echo ucfirst($value['Project']['name']); ?>" style="color:#5191BD"><?php echo $this->Format->shortLength(strtoupper($value['Project']['short_name']),4); ?></a> - 
		<a href="<?php echo HTTP_ROOT; ?>dashboard#details/<?php echo $value['Easycase']['uniq_id']; ?>" title="<?php echo htmlentities($this->Format->convert_ascii($value['Easycase']['title']),ENT_QUOTES); ?>">#<?php echo $value['Easycase']['case_no'];?>: <?php echo htmlentities($this->Format->shortLength($this->Format->convert_ascii($value['Easycase']['title']),50),ENT_QUOTES); ?></a>
		</div>
	    <div class="cb"></div>
		<div class="fl" style="font-size:12px;">
                    <span style="color: #8f8f8f;"><?php echo __("Created on"); ?> <span class=""><?php echo $actual_dt_created; ?></span> &nbsp;&nbsp;
		    
                        <?php echo __("Upcoming In"); ?>:
			<span class="tsklst_label upcomngin" title="<?php echo $due_date;?>"><?php echo $this->Datetime->datediffernce($locDT,'upcoming');?> </span>
		   
                        </span>
		</div>
	    
	    <?php if($project == 'all' && 0){ ?>
		<div class="fr">
		    <div class="fl"><img class="prj-db" src="<?php echo HTTP_IMAGES; ?>images/u_det_proj.png"></div>
		    <div class="fl">
			<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>">
			    <div class="prj_title_db" title="<?php echo ucfirst($value['Project']['name']); ?>"><?php echo ucfirst($value['Project']['name']); ?></div>
			</a>
		    </div>
		</div>
		<?php } ?>
	    <div class="cb"></div>
	    <?php if(count($gettodos) != $cnt) { ?>
	    <div class="lstbtndv"></div>
	    <?php } ?>
	</div>
	<div class="cb"></div>
     <?php } ?>
	<div id="to_dos_more" data-value="<?php echo $total;?>" style="display: none;"></div>
     <?php } else { ?>
	<div class="mytask"></div>
	<div class="mytask_txt">
        <?php if(isset($username)){ ?>
        No tasks assigned to <?php echo $username['User']['name']; ?>
        <?php } ?>
    </div>
    <div id="to_dos_more" data-value="0" style="display: none;"></div>
<?php } ?> 
				</div>
        
			</div>
  </div>

