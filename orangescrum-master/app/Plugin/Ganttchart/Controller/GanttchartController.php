<?php

App::uses('AppController', 'Controller');

class GanttchartController extends GanttchartAppController {

    var $name = 'Ganttchart';
    public $components = array('Format', 'Postcase', 'Ganttchart.Format');
    public $helpers = array('Format', 'Html');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    function ganttv2() {
        if(SES_TYPE < 3 || $GLOBALS['gantt_access_type'] > 0){
        $this->loadModel('Milestone');
        $milestones = $this->Milestone->find('all', array('conditions' => array('Milestone.project_id' => PROJ_ID)));
        $this->set('milestones', $milestones);
        //$this->layout = false;
        } else { $this->redirect(HTTP_ROOT."dashboard");}
    }

    function ganttv2_ajax() {
        ob_clean();
        $this->layout = 'ajax';

        $view = new View($this);
        $this->tz = $view->loadHelper('Tmzone');

        $this->loadModel('Project');
        $this->loadModel('Milestone');
        $this->loadModel('EasycaseMilestone');
        $this->loadModel('Easycase');

        $json_arr = array();
        $colors = array(0 => '#73BCDE', 1 => '#8BC2B9', 2 => '#F8B363', 3 => '#EA7373', 4 => '#9ECC61');

        #pr($this->request->data);exit;
        $prjuid = $this->request->data['prjid'];
        if (!empty($prjuid)) {
            $projects = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prjuid, 'Project.isactive' => '1'), 'recursive' => -1));
            $milestones = $this->Milestone->find('all', array('conditions' => array('Milestone.project_id' => $projects['Project']['id'])));
        } else {
            $projects = $this->Project->find('first', array('conditions' => array('Project.user_id' => SES_ID, 'Project.company_id' => SES_COMP, 'Project.isactive' => '1'), 'recursive' => -1));
            $milestones = $this->Milestone->find('all', array('conditions' => array('Milestone.project_id' => $projects['Project']['id'])));
            #$prjuid = $projects['Project']['id'];
            $prjuid = $projects['Project']['uniq_id'];
        }
        if (is_array($projects) && count($projects) > 0) {
            $project_id = $projects['Project']['id'];
            $workflow_id = intval($projects['Project']['workflow_id']) > 0 ? $projects['Project']['workflow_id'] : 0;
            $this->loadModel('Status');
            $status_list = $this->Status->find('all', array("conditions" => array("Status.workflow_id" => $workflow_id), "fields" => array("Status.id", "Status.name", 'Status.color'), "order" => array("seq_order ASC")));
            $status = $this->Status->find('first', array("conditions" => array("Status.workflow_id" => $workflow_id), "order" => array("seq_order DESC")));
            $status_id = $status['Status']['id'];
        } else {
            $status_id = 3;
            $status_list = array();
        }

        $pid = urldecode($prjuid);

        /* get current project id */
        #$project = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => '' . $pid . ''), 'fields' => 'id,name', 'recursive' => -1));
        $project = $projects;
        $project_id = $project['Project']['id'];
        $project_name = $project['Project']['name'];
        #pr($project);exit;

        $ml_params = array(
            'fields' => array('Milestone.id', 'Milestone.title', 'COUNT(Easycase.id) AS total_tasks',
                'IF(Milestone.title IS NULL,2,1) AS milestonesort',
                "IF(MIN(Easycase.gantt_start_date)!='' AND MIN(Easycase.gantt_start_date)!='0000-00-00 00:00:00',MIN(Easycase.gantt_start_date),CURDATE()) AS milestone_start",
                "IF(MAX(Easycase.due_date)!='' AND MAX(Easycase.due_date) != '0000-00-00 00:00:00',MAX(Easycase.due_date),CURDATE()) AS milestone_end"
            ),
            'order' => "milestonesort ASC, Milestone.id_seq ASC,Milestone.title ASC, Easycase.seq_id ASC, Easycase.title ASC",
            'group' => "Milestone.id",
        );

        $ml_params['conditions'] = array("Project.uniq_id='" . $pid . "'");
        $ml_params['joins'] = array(
            array('table' => 'projects', 'alias' => 'Project', 'type' => 'LEFT', 'conditions' => array('Milestone.project_id=Project.id')),
            array('table' => 'easycase_milestones', 'alias' => 'EasycaseMilestone', 'type' => 'LEFT', 'conditions' => array('Milestone.id=EasycaseMilestone.milestone_id')),
            array('table' => 'easycases', 'alias' => 'Easycase', 'type' => 'LEFT', 'conditions' => array('Easycase.id=EasycaseMilestone.easycase_id', 'Easycase.project_id=Project.id')),
        );
        $tasks1 = $this->Milestone->find('all', $ml_params);

        $ml_params['conditions'] = array("Project.uniq_id='" . $pid . "'", 'Easycase.isactive' => '1', 'Easycase.istype' => '1', 'Easycase.legend !=' => $status_id);
        $ml_params['joins'] = array(
            array('table' => 'projects', 'alias' => 'Project', 'type' => 'LEFT', 'conditions' => array('Easycase.project_id=Project.id')),
            array('table' => 'easycase_milestones', 'alias' => 'EasycaseMilestone', 'type' => 'LEFT', 'conditions' => array('Easycase.id=EasycaseMilestone.easycase_id')),
            array('table' => 'milestones', 'alias' => 'Milestone', 'type' => 'LEFT', 'conditions' => array('Milestone.id=EasycaseMilestone.milestone_id', 'Milestone.project_id=Project.id')),
        );

        $tasks2 = $this->Easycase->find('all', $ml_params);

        #pr($tasks1);pr($tasks2);exit;
        $final_arr1 = Hash::combine($tasks1, '{n}.Milestone.title', '{n}');
        $final_arr2 = Hash::combine($tasks2, '{n}.Milestone.title', '{n}');
        $final_arr = array_merge($final_arr1, $final_arr2);
        $tasks = Hash::combine($final_arr, '{s}.Milestone.id', '{s}');
        #pr($tasks);exit;

        /* paging start */
        $page = ($this->request->data['page'] > 1) ? intval($this->request->data['page']) : 1;
        $rec_limit = 100;
        $limit = $page > 1 ? ($rec_limit * $page) : 100;
        $offset = $page > 1 ? ($rec_limit * $page) - $rec_limit : 0;
        $next = 'No';
        $prev = 'No';
        $total = 0;
        $total_rec = 0;
        $page_milestone_ids = '';
        $cntr = 0;
        if (is_array($tasks) && count($tasks) > 0) {
            foreach ($tasks as $key => $val) {
                $task_count = $val[0]['total_tasks'];
                if ($total < $limit) {
                    $cntr++;
                    if ($total >= $offset) {
                        $final_milestone[intval($val['Milestone']['id'])] = $val[0]['total_tasks'];
                    }
                    $total += $task_count;
                    #echo $total . " >> " . $task_count . "\n";
                }
                $total_rec += $task_count;
            }
            #echo "limit=".$limit." || offset=".$offset." cntr = ".$cntr." ".($cntr-count($final_milestone))."\n";
            #echo $total_rec . " || " . $total . "\n";
            if ($total_rec > $total) {
                $next = 'Yes';
            }
            $page_milestone_ids = implode(',', array_keys($final_milestone));
            $last_count = $cntr - count($final_milestone);
        }
        #exit;
        if ($page > 1) {
            $prev = "Yes";
        }

        #$ml_arr = Hash::combine($tasks, '{n}.Milestone.id', '{n}');
        /* pagging end */

        if ($page_milestone_ids == '') {
            $where = "1=1";
        } else {
            $where = "Milestone.id IN (" . $page_milestone_ids . ")";
        }
        #echo $where;exit;
        /* get all milestones of current project */
        $milestone = $this->Milestone->find('all', array(
            'conditions' => array('Milestone.project_id' => '' . $project_id . '', $where),
            'order' => 'Milestone.id_seq ASC, Milestone.title ASC'));
        #pr($milestone);exit;

        $milestone_ids = "''";
        if (is_array($milestone) && count($milestone) > 0) {
            $milestone_data = Hash::combine($milestone, '{n}.Milestone.id', '{n}.Milestone');
            $milestone_id = array_keys($milestone_data);
            $milestone_ids = implode(',', $milestone_id);
        }
        #pr($milestone_data);exit;

        /* fetch all tasks related to milestones of project */
        $params = array(
            'fields' => array('EasycaseMilestone.easycase_id', 'EasycaseMilestone.milestone_id', 'Easycase.*',
                "(SELECT completed_task  FROM easycases AS ec WHERE project_id=Easycase.project_id AND case_no=Easycase.case_no AND completed_task != 0 ORDER BY id DESC LIMIT 1) AS task_progress",
                "(SELECT CONCAT_WS(' ',name,last_name,(IF(short_name!='',CONCAT(' (',short_name,')'),''))) FROM users WHERE id=Easycase.assign_to) AS assigned_to"
            ),
            'conditions' => array("EasycaseMilestone.milestone_id IN (" . $milestone_ids . ")", 'Easycase.isactive' => '1', 'Easycase.legend !=' => $status_id, "Easycase.project_id" => $project_id),
            #'order' => "Milestone.id_seq ASC, Milestone.title ASC, Easycase.seq_id ASC",
            'order' => "Milestone.id_seq ASC, Milestone.title ASC, EasycaseMilestone.id_seq ASC",
        );
        $params['joins'][] = array('table' => 'easycases', 'alias' => 'Easycase', 'type' => 'LEFT', 'conditions' => array('EasycaseMilestone.easycase_id = Easycase.id'));
        $params['joins'][] = array('table' => 'milestones', 'alias' => 'Milestone', 'type' => 'LEFT', 'conditions' => array('EasycaseMilestone.milestone_id = Milestone.id'));
        $easycasemilestone = $this->EasycaseMilestone->find('all', $params);
        #pr($easycasemilestone);exit;

        /* fetch task details */
        $datesArr = array();
        foreach ($easycasemilestone as $value) {
            $task_id[] = $value['EasycaseMilestone']['easycase_id'];
            $easycase = $value;
            $milestone_id = $value['EasycaseMilestone']['milestone_id'];

            $arr = array();
            $arr['milestone_id'] = $value['EasycaseMilestone']['milestone_id'];
            $arr['id'] = $easycase['Easycase']['id'];
            $arr['name'] = $milestone_data[$value['EasycaseMilestone']['milestone_id']]['title'];
            $arr['title'] = '#' . $easycase['Easycase']['case_no'] . ': ' . $easycase['Easycase']['title'];
            $arr['message'] = $easycase['Easycase']['message'];

            $curr_date = $this->convert_date_timezone();
            $gantt_start_date = $this->convert_date_timezone($easycase['Easycase']['gantt_start_date']);
            $due_date = $this->convert_date_timezone($easycase['Easycase']['due_date']);
            #echo $curr_date." >> ".$gantt_start_date." >> ".$due_date."<br/>";
            $arr['gantt_start_date'] = $gantt_start_date > $due_date ? ($due_date > $curr_date ? $due_date : $curr_date) : $gantt_start_date;
            $arr['due_date'] = $gantt_start_date > $due_date ? ($gantt_start_date > $curr_date ? $gantt_start_date : $curr_date) : $due_date;
            $arr['o_gantt_start_date'] = $easycase['Easycase']['gantt_start_date'];
            $arr['o_due_date'] = $easycase['Easycase']['due_date'];

            $arr['actual_dt_created'] = $this->convert_date_timezone($easycase['Easycase']['actual_dt_created']);

            $arr['legend'] = $easycase['Easycase']['legend'];
            $arr['depends'] = $easycase['Easycase']['depends'];
            $arr['type_id'] = trim($easycase['Easycase']['type_id']);
            $arr['priority'] = trim($easycase['Easycase']['priority']);
            $arr['case_no'] = trim($easycase['Easycase']['case_no']);
            $arr['assign_to'] = trim($easycase['Easycase']['assign_to']);

            $arr['progress'] = floatval($easycase[0]['task_progress']);
            $arr['assigned_to'] = trim($easycase[0]['assigned_to']) != '' ? trim($easycase[0]['assigned_to']) : "Nobody";
            array_push($json_arr, $arr);

            /* milestone start and end dates */
            $datesArr[$milestone_id]['start_date'] = isset($datesArr[$milestone_id]['start_date']) ? $datesArr[$milestone_id]['start_date'] : "0000-00-00";
            $datesArr[$milestone_id]['end_date'] = isset($datesArr[$milestone_id]['end_date']) ? $datesArr[$milestone_id]['end_date'] : "0000-00-00";
            $datesArr[$milestone_id]['created'] = isset($datesArr[$milestone_id]['created']) ? $datesArr[$milestone_id]['created'] : "0000-00-00";

            $datesArr[$milestone_id]['start_date'] = $arr['gantt_start_date'] < $datesArr[$milestone_id]['start_date'] || $datesArr[$milestone_id]['start_date'] == '0000-00-00' ? $arr['gantt_start_date'] : $datesArr[$milestone_id]['start_date'];
            $datesArr[$milestone_id]['end_date'] = $arr['due_date'] > $datesArr[$milestone_id]['end_date'] || $datesArr[$milestone_id]['end_date'] == '0000-00-00' ? $arr['due_date'] : $datesArr[$milestone_id]['end_date'];
            $datesArr[$milestone_id]['created'] = $arr['actual_dt_created'] < $datesArr[$milestone_id]['created'] || $datesArr[$milestone_id]['created'] == '0000-00-00' ? $arr['actual_dt_created'] : $datesArr[$milestone_id]['created'];

            #$final_dates[$milestone_id] = $datesArr;
        }
        #$datesArr = $final_dates;
        #pr($final_dates);exit;
        #pr(($json_arr));exit;

        if ($page_milestone_ids == '' || in_array(0, array_keys($final_milestone))) {
            $options = array(
                'conditions' => array('Easycase.project_id' => $project_id, 'Easycase.istype' => '1', 'Easycase.isactive' => '1', 'Easycase.legend !=' => $status_id, "EasycaseMilestone.id IS NULL"
                #"Easycase.id NOT IN (" . (is_array($task_id) && count($task_id) > 0 ? implode(',', $task_id) : "''") . ")",
                ),
                'order' => "Easycase.seq_id ASC",
                'fields' => "Easycase.*,"
                . "(SELECT completed_task  FROM easycases AS ec WHERE project_id=Easycase.project_id AND case_no=Easycase.case_no AND completed_task != 0 ORDER BY id DESC LIMIT 1) AS task_progress,"
                . "(SELECT CONCAT_WS(' ',name,last_name,(IF(short_name!='',CONCAT(' (',short_name,')'),''))) FROM users WHERE id=Easycase.assign_to) AS assigned_to"
            );
            $options['joins'] = array(array('table' => 'easycase_milestones', 'alias' => 'EasycaseMilestone', 'type' => 'LEFT', 'conditions' => array('EasycaseMilestone.easycase_id = Easycase.id')));
            $easycases = $this->Easycase->find('all', $options);
            #pr(($easycases));exit;
        } else {
            $easycases = array();
        }

        #echo $project_id;
        #pr(count($easycases));exit;
        #pr(($easycases));exit;
        /* default Milestone */
        $start_date = '0000-00-00';
        $end_date = '0000-00-00';
        $created = '0000-00-00';
        if (is_array($easycases) && count($easycases) > 0) {
            foreach ($easycases as $easycase) {
                $arr = array();
                $arr['milestone_id'] = 0;
                $arr['id'] = $easycase['Easycase']['id'];
                $arr['name'] = __("Default Milestone", true);
                $arr['title'] = '#' . $easycase['Easycase']['case_no'] . ': ' . $easycase['Easycase']['title'];

                $arr['message'] = $easycase['Easycase']['message'];
                $arr['legend'] = $easycase['Easycase']['legend'];
                $arr['depends'] = $easycase['Easycase']['depends'];
                $arr['type_id'] = trim($easycase['Easycase']['type_id']);
                $arr['priority'] = trim($easycase['Easycase']['priority']);
                $arr['case_no'] = trim($easycase['Easycase']['case_no']);
                $arr['assign_to'] = trim($easycase['Easycase']['assign_to']);

                $arr['progress'] = floatval($easycase[0]['task_progress']);
                $arr['assigned_to'] = trim($easycase[0]['assigned_to']) != '' ? trim($easycase[0]['assigned_to']) : "Nobody";


                /* $arr['gantt_start_date'] = $easycase['Easycase']['gantt_start_date'];
                  $arr['due_date'] = $easycase['Easycase']['due_date'];
                  $arr['actual_dt_created'] = $easycase['Easycase']['actual_dt_created']; */

                $curr_date = $this->convert_date_timezone();
                $gantt_start_date = $this->convert_date_timezone($easycase['Easycase']['gantt_start_date']);
                $due_date = $this->convert_date_timezone($easycase['Easycase']['due_date']);
                $arr['gantt_start_date'] = $gantt_start_date > $due_date ? ($due_date > $curr_date ? $due_date : $curr_date) : $gantt_start_date;
                $arr['due_date'] = $gantt_start_date > $due_date ? ($gantt_start_date > $curr_date ? $gantt_start_date : $curr_date) : $due_date;
                $arr['o_gantt_start_date'] = $easycase['Easycase']['gantt_start_date'];
                $arr['o_due_date'] = $easycase['Easycase']['due_date'];
                $arr['actual_dt_created'] = $this->convert_date_timezone($easycase['Easycase']['actual_dt_created']);

                array_push($json_arr, $arr);

                /* milestone start and end dates */
                $start_date = $arr['gantt_start_date'] < $start_date || $start_date == '0000-00-00' ? $arr['gantt_start_date'] : $start_date;
                $end_date = $arr['due_date'] > $end_date ? $arr['due_date'] : $end_date;
                $created = date('Y-m-d', strtotime($arr['actual_dt_created'])) < $created || $created == '0000-00-00' ? date('Y-m-d', strtotime($arr['actual_dt_created'])) : $created;
            }
        }
        $dates = array('start_date' => $start_date, 'end_date' => $end_date, 'created' => $created);
        $defult_dates = $this->Format->get_formated_date($dates);
        #pr($defult_dates);exit;
        #pr($json_arr);exit;
        $status_arr = Hash::combine($status_list,"{n}.Status.id","{n}.Status");
        
        if ($json_arr != "") {
            $json_arr = $this->Format->changeGanttDataV2($json_arr,$status_arr);
        }
        #pr($json_arr);exit;
        if (is_array($json_arr) && count($json_arr) > 0) {
            foreach ($json_arr as $key => $val) {
                $milestone_id = ($val['milestone_id']);
                //unset($val['milestone_id']);
                $fial_array[$milestone_id][] = $val;
            }
            $json_arr = ($fial_array);
        }
        #pr($json_arr);exit;
        $final_arr = array();
        #STATUS_ACTIVE, STATUS_DONE, STATUS_FAILED, STATUS_SUSPENDED, STATUS_UNDEFINED

        foreach ($milestone as $key => $val) {
            $milestone_id = $val['Milestone']['id'];
            //if (is_array($json_arr[$milestone_id]) && count($json_arr[$milestone_id]) > 0) {
            #pr($val);exit;
            #pr($datesArr[$milestone_id]);exit;
            $dates = $this->Format->get_formated_date($datesArr[$milestone_id]);
            #pr($dates);exit;
            $final_arr[] = array(
                'id' => "m" . $val['Milestone']['id'],
                'name' => h($val['Milestone']['title']),
                'status' => 'STATUS_ACTIVE',
                'canWrite' => true,
                'duration' => $dates['duration'],
                'startIsMilestone' => false,
                'endIsMilestone' => false,
                'collapsed' => false,
                'assigs' => array(),
                'hasChild' => 1,
                'level' => 0,
                'depends' => '',
                'start' => $dates['start'],
                'end' => $dates['end'],
                'o_start' => $dates['0_start'],
                'o_end' => $dates['o_end'],
            );
            #pr($final_arr);
            if (is_array($json_arr[$milestone_id]) && count($json_arr[$milestone_id]) > 0) {
                $final_arr = array_merge($final_arr, $json_arr[$milestone_id]);
            }
        }
        #pr($final_arr);exit;
        #echo count($json_arr[0]);exit;
        if (is_array($json_arr[0]) && count($json_arr[0]) > 0) {
            $final_arr[] = array(
                'id' => 0,
                'name' => __("Default Milestone", true),
                'status' => 'STATUS_ACTIVE',
                'canWrite' => true,
                'duration' => $defult_dates['duration'],
                'startIsMilestone' => false,
                'endIsMilestone' => false,
                'collapsed' => false,
                'assigs' => array(),
                'hasChild' => 1,
                'level' => 0,
                'depends' => '',
                'start' => $defult_dates['start'],
                'end' => $defult_dates['end'],
            );
            $final_arr = array_merge($final_arr, $json_arr[0]);
        }
        $ids = array();
        if (is_array($final_arr) && count($final_arr) > 0) {
            #$ids = array_values(Hash::combine($final_arr, '{n}.id', '{n}.id'));
            $ids = Hash::extract($final_arr, '{n}.id');
            foreach ($final_arr as $key => $val) {
                $dependsArr = trim($val['depends']) != '' ? explode(',', $val['depends']) : array();
                $depends = array();
                if (is_array($dependsArr) && count($dependsArr) > 0) {
                    foreach ($dependsArr as $k => $v) {
                        if ($chk_dependant = array_search($v, $ids)) {
                            $depends[] = $chk_dependant + 1;
                        }
                    }
                }
                $final_arr[$key]['name'] = $this->Format->formatTitle($final_arr[$key]['name']);
                $final_arr[$key]['description'] = $this->Format->formatTitle($final_arr[$key]['description']);
                unset($final_arr[$key]['message']);

                $final_arr[$key]['depends'] = (is_array($depends) && count($depends) > 0) ? implode(',', $depends) : '';
            }
        }
        echo json_encode(
                array('ok' => true,
                    'project' => array('tasks' => $final_arr, "selectedRow" => 0, "canWrite" => true, "canWriteOnParent" => true, 'project_name' => $project_name),
                    'next' => $next,
                    'prev' => $prev,
                    'last_count' => $last_count,
                    'task_status_list' => Hash::extract($status_list, "{n}.Status")
                )
        );
        exit;
        $this->set('tasks', json_encode(array('tasks' => $final_arr, "selectedRow" => 0, "canWrite" => true, "canWriteOnParent" => true)));

        $this->set('next', $next);
        $this->set('prev', $prev);

        $this->set('milestones', $milestones);
        $this->set('puniq', $prjuid);
        $this->layout = false;
    }

    function ganttv2_save() {
        $this->loadModel('Easycase');
        $this->loadModel('Project');
        $this->loadModel('EasycaseMilestone');
        $this->loadModel('Milestone');

        $updates = array();
        $postdata = $this->request->data;
        #pr($postdata);exit;
        $prjid = $postdata['prjid'];
        $mode = $postdata['CM'];
        $last_count = isset($postdata['lsct']) ? $postdata['lsct'] : 0;
        $data = trim($postdata['prj']) != '' ? json_decode($postdata['prj'], true) : '';
        #pr($data);exit;
        $deletedTaskIds = $data['deletedTaskIds'];
        $timezone = $postdata['timezone'] * ($postdata['timezone'] < 0 ? -1 : 1);
        $dt = $postdata['dt'];
        $tz = ($postdata['timezone'] < 0 ? "-" : "+") . str_pad(intval($timezone / 60), 2, 0, STR_PAD_LEFT) . ":" . str_pad(intval($timezone % 60), 2, 0, STR_PAD_LEFT);
        $user_time = $this->format_date($dt, $tz, 'H:i:s');

        $milestone = '';
        $ids = array();
        #pr($deletedTaskIds);exit;
        #pr($data);exit;
        #pr($data['tasks']);exit;
        $status_id = 3;
		$default_milestone_msg = '';
        $projects = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prjid, 'Project.isactive' => '1'), 'recursive' => -1));
        if (is_array($projects) && count($projects) > 0) {
            $project_id = $projects['Project']['id'];
            $workflow_id = intval($projects['Project']['workflow_id']) > 0 ? $projects['Project']['workflow_id'] : 0;
            $this->loadModel('Status');
            $status_list = $this->Status->find('all', array("conditions" => array("workflow_id" => $workflow_id), "order" => array("seq_order DESC")));
            $status = $this->Status->find('first', array("conditions" => array("workflow_id" => $workflow_id), "order" => array("seq_order DESC")));
            $status_id = $status['Status']['id'];
            $status_perc = Hash::combine($status_list,"{n}.Status.id","{n}.Status.percentage");
        }

        $current_tasks = array();
        if ($mode == 'SVTASK') {
            $updates[0]['tasks'][$data['tasks']['id']] = $data['tasks'];
            $current_tasks[] = $data['tasks']['id'];
        } else {
            if (is_array($data['tasks']) && count($data['tasks']) > 0) {
                $counter = 0;
                foreach ($data['tasks'] as $key => $val) {
                    $counter++;
                    #$ids[] = preg_replace('/\D/', '', $val['id']);
                    $ids[] = $val['id'];
                    $current_tasks[] = $val['id'];
                    if ($val['level'] == '0') {
                        #$milestone = preg_replace('/\D/', '', $val['id']);
                        $milestone = $val['id'];
                        $updates[$milestone] = $val;
                        $updates[$milestone]['tasks'] = array();
                    } else {
                        $updates[$milestone]['tasks'][$val['id']] = $val;
                    }
                }
            }
        }
        #pr($ids);exit;
        #pr($updates);exit;
        $child_arr = array();
        if (is_array($updates) && count($updates) > 0) {
            $seq_id = 0;
            $m_seq_id = intval($last_count);
            foreach ($updates as $key_milestone => $update) {
                $key_milestone_id = preg_replace('/\D/', '', $key_milestone);
                $key_milestone_name = h($update['name']);
                $seq_id++;
                if (is_array($update['tasks']) && count($update) > 0) {
                    foreach ($update['tasks'] as $key => $val) {
                        $seq_id++;
                        #pr($val);#exit;
                        $easycase_id = $key;
                        $depend = $this->task_dependency($easycase_id);
                        if (is_int($easycase_id) && $easycase_id > 0) {
                            $depends = array();
                            $children = array();

                            if (trim($val['depends']) != '') {
                                $dependsArr = explode(',', $val['depends']);
                                foreach ($dependsArr as $k => $v) {
                                    $depends[] = $ids[$v - 1];
                                    $child_arr[$easycase_id][] = $ids[$v - 1];
                                }
                            }

                            /* retail old depend value */
                            $dependancy_params = array(
                                'conditions' => array('Easycase.id' => $easycase_id),
                                'fields' => array('Easycase.id', 'Easycase.depends', 'Easycase.children')
                            );
                            $depend_task = $this->Easycase->find('first', $dependancy_params);
                            #pr($depend_task);exit;
                            if (is_array($depend_task) && count($depend_task) > 0) {
                                $prev_depend = trim($depend_task['Easycase']['depends']) != '' ? explode(',', $depend_task['Easycase']['depends']) : '';
                                $prev_child = trim($depend_task['Easycase']['children']) != '' ? explode(',', $depend_task['Easycase']['children']) : '';

                                /* check for closed tasks. If closed then only save the ids to retain dependency */
                                $closedtask_params_depend = array(
                                    'conditions' => array('Easycase.id' => $prev_depend, "Easycase.legend" => $status_id),
                                    'fields' => array('Easycase.id')
                                );

                                $closed_tasks_depend = $this->Easycase->find('list', $closedtask_params_depend);
                                /* child */
                                $closedtask_params_child = array(
                                    'conditions' => array('Easycase.id' => $prev_child, "Easycase.legend" => $status_id),
                                    'fields' => array('Easycase.id')
                                );
                                $closed_tasks_child = $this->Easycase->find('list', $closedtask_params_child);
                                #pr($closed_tasks_depend);pr($closed_tasks_child);exit;
                                if (is_array($closed_tasks_depend) && count($closed_tasks_depend) > 0) {
                                    $depends = array_merge($depends, array_values($closed_tasks_depend));
                                }
                                if (is_array($closed_tasks_child) && count($closed_tasks_child) > 0) {
                                    foreach ($closed_tasks_child as $key => $taskid) {
                                        if (empty($child_arr[$taskid]))
                                            $child_arr[$taskid] = array();
                                        if (!in_array($easycase_id, $child_arr[$taskid])) {
                                            $child_arr[$taskid][] = $easycase_id;
                                        }
                                    }
                                }
                            }
                            /* end */

                            $update_data = array();
                            $tz = '';
                            $title = strpos($val['name'], ':') != false ? trim(substr($val['name'], strpos($val['name'], ':') + 1)) : trim($val['name']);
                            $update_data = array(
                                'title' => html_entity_decode(addslashes($title)),
                                'message' => addslashes($val['description']),
                                #'gantt_start_date' => "'" . $this->format_date($val['start'], $tz, "Y-m-d") . " " . $user_time . "'",
                                #'due_date' => "'" . $this->format_date($val['end'], $tz, "Y-m-d") . " " . $user_time . "'",
                                'gantt_start_date' => $this->format_date($val['start'], $tz, "Y-m-d H:i:s"),
                                'due_date' => $this->format_date($val['end'], $tz, "Y-m-d H:i:s"),
                                'assign_to' => trim($val['assign_to']),
                                'priority' => intval($val['priority']),
                                'type_id' => trim($val['type_id']),
                                'seq_id' => $seq_id,
                                'id' => $easycase_id,
                                'depends' => !empty($depends) ? implode(',', $depends) : '',
                                'children' => '', //reset child field
                            );

                            if ($mode == 'SVTASK') {
								if($depend != 'No'){
                                $update_data['legend'] = trim($val['legend']);
								}
                                unset($update_data['depends']);
                                unset($update_data['seq_id']);
                            }
                            #echo "\n"; #pr(array('Easycase.id' => $easycase_id));echo "<hr>";
                            $this->Easycase->save($update_data);
                            #$this->Easycase->updateAll($update_data, array('Easycase.id' => $easycase_id));

                            /* update easycase milestone */
                            if ($mode != 'SVTASK') {
                                if (intval($project_id) > 0 && intval($key_milestone_id) > 0 && intval($easycase_id) > 0) {
                                    $EasycaseMilestones = $this->EasycaseMilestone->find('first', array('conditions' => array('EasycaseMilestone.easycase_id' => $easycase_id, 'EasycaseMilestone.project_id' => $project_id, 'EasycaseMilestone.milestone_id' => $key_milestone_id), 'recursive' => -1));
                                    /* will check if task is in current milestone */
                                    if (is_array($EasycaseMilestones) && count($EasycaseMilestones) > 0) {
                                        $m_data = array('id' => $EasycaseMilestones['EasycaseMilestone']['id'], 'id_seq' => $seq_id);
                                        $this->EasycaseMilestone->save($m_data);
                                    } else {
                                        $existEasycaseMilestones = $this->EasycaseMilestone->find('first', array('conditions' => array('EasycaseMilestone.easycase_id' => $easycase_id, 'EasycaseMilestone.project_id' => $project_id), 'recursive' => -1));
                                        /* update milestone id */
                                        if (is_array($existEasycaseMilestones) && count($existEasycaseMilestones) > 0) {
                                            $m_data = array('milestone_id' => $key_milestone_id, 'id_seq' => $seq_id, 'id' => $existEasycaseMilestones['EasycaseMilestone']['id']);
                                            $this->EasycaseMilestone->save($m_data);
                                        } else {
                                            /* insert new easycase milestone */
                                            $mdata = array();
                                            $mdata = array('EasycaseMilestone' => array('easycase_id' => $easycase_id, 'project_id' => $project_id, 'milestone_id' => $key_milestone_id, 'user_id' => SES_ID, 'created' => GMT_DATETIME, 'id_seq' => $seq_id));

                                            #$mdata['EasycaseMilestone']['id_seq'] = $seq_id;
                                            #$insert_arr = array_merge($insert_arr, $mdata);
                                            $this->EasycaseMilestone->create();
                                            $this->EasycaseMilestone->save($mdata);
                                        }
                                    }
                                } elseif (intval($key_milestone_id) == '0') {
									if ($mode != 'SVTASK' && trim($key_milestone_name) != 'Default Milestone') {
										$default_milestone_msg = 'Default Milestone can not be editable.';
									}
                                    $existEasycaseMilestones = $this->EasycaseMilestone->find('first', array('conditions' => array('EasycaseMilestone.easycase_id' => $easycase_id, 'EasycaseMilestone.project_id' => $project_id), 'recursive' => -1));
                                    if ($existEasycaseMilestones) {
                                        $this->EasycaseMilestone->deleteAll(array('EasycaseMilestone.easycase_id' => $easycase_id, 'EasycaseMilestone.project_id' => $project_id));
                                    }
                                }
                            }
                        }
                    }
                    #continue;
                    if (intval($key_milestone_id) > 0) {
                        $this->Milestone->save(array('id' => $key_milestone_id, 'title' => $key_milestone_name, 'id_seq' => ++$m_seq_id));
                    }
                }
            }
        }

        /* reset dependant child field before updating */
        #pr($child_arr);#exit;
        if (is_array($child_arr) && count($child_arr) > 0) {
            $parents_arr = array();
            foreach ($child_arr as $childkey => $child) {
                $child = array_unique($child);
                foreach ($child as $key => $parentid) {
                    $parents_arr[$parentid][] = $childkey;
                }
            }
            #pr($parents_arr);#exit;
            foreach ($parents_arr as $parentkey => $children) {
                if ($parentkey > 0) {
                    $update_data = array('id' => $parentkey, 'children' => implode(',', $children));
                    #$this->Easycase->create();
                    $this->Easycase->save($update_data);
                }
            }
        }

        #exit;
        if ($mode == 'SVTASK') {
            if (is_int($easycase_id) && intval($easycase_id) > 0) {
                #$dataeasycase = $this->Easycase->find('first', array('conditions' => array('id' => "'" . $easycase_id . "'")));
                $sqldata = "SELECT * FROM `easycases` AS Easycase WHERE `id`='" . $easycase_id . "' ";
                $dataeasycase = $this->Easycase->query($sqldata);
                #pr($dataeasycase);exit;
                $milestone_id = $updates[0]['tasks'][$easycase_id]['milestone_id'];
                
                #$progress = $depend == 'Yes' ? $updates[0]['tasks'][$easycase_id]['progress'] : 0;
                $progress = $depend == 'Yes' ? $status_perc[$updates[0]['tasks'][$easycase_id]['legend']] : 0;
                $description = $updates[0]['tasks'][$easycase_id]['description'];
                if ($milestone_id > 0) {
                    $this->loadModel('EasycaseMilestone');
                    $res_milestones = $this->EasycaseMilestone->findByEasycaseId($easycase_id);
                    $count_milestone = $this->EasycaseMilestone->find("first", array('conditions' => array("EasycaseMilestone.milestone_id" => $milestone_id), 'order' => array('id_seq DESC')));
                    $count_milestone = isset($count_milestone['EasycaseMilestone']) ? intval($count_milestone['EasycaseMilestone']['id_seq']) : 1;
                    if (is_array($res_milestones) && count($res_milestones) > 0) {
                        if ($res_milestones['EasycaseMilestone']['milestone_id'] != $milestone_id) {
                            $update_easycase_milestone = array('id' => $res_milestones['EasycaseMilestone']['id'], 'easycase_id' => $easycase_id,
                                'milestone_id' => $milestone_id, 'id_seq' => $count_milestone + 1);
                            $this->EasycaseMilestone->save($update_easycase_milestone);
                        }
                    } else {
                        $new_easycase_milestone = array('easycase_id' => $easycase_id, 'milestone_id' => $milestone_id,
                            'project_id' => $dataeasycase[0]['Easycase']['project_id'],
                            'user_id' => SES_ID,
                            'id_seq' => $count_milestone + 1);
                        $this->EasycaseMilestone->save($new_easycase_milestone);
                    }
                }
                $getCase = $this->Easycase->find('first', array('conditions' => array('id' => $easycase_id, 'isactive' => 1), 'fields' => array('id', 'completed_task')));
                #echo $getCase['Easycase']['completed_task']." != ".$progress;
                #pr($getCase);exit;
                if ($getCase && $getCase['Easycase']['completed_task'] != $progress) {
                    $sql = "UPDATE `easycases` SET `completed_task`='" . $progress . "',dt_created = '" . GMT_DATETIME . "',"
                            . "case_count=case_count+1, updated_by='" . SES_ID . "' "
                            . "WHERE `id`='" . $easycase_id . "' AND isactive='1'";
                    #echo $sql;
                    $upd = $this->Easycase->query($sql);


                    $caseuniqid = $this->Format->generateUniqNumber();
                    $insert_sql = "INSERT INTO easycases SET uniq_id='" . $caseuniqid . "',completed_task='" . $progress . "', "
                            . "case_no = '" . $dataeasycase[0]['Easycase']['case_no'] . "', 	case_count=0, "
                            . "project_id='" . $dataeasycase[0]['Easycase']['project_id'] . "', user_id='" . SES_ID . "', "
                            . "updated_by='" . SES_ID . "', type_id='" . $dataeasycase[0]['Easycase']['type_id'] . "', "
                            . "priority='" . $dataeasycase[0]['Easycase']['priority'] . "', title='', message='$description', "
                            . "estimated_hours='" . $dataeasycase[0]['Easycase']['estimated_hours'] . "', hours='0', "
                            . "assign_to='" . $dataeasycase[0]['Easycase']['assign_to'] . "', istype='2',format='2', "
                            . "status='" . $dataeasycase[0]['Easycase']['status'] . "', legend='2', isactive=1, "
                            . "dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type='6'";
                    #echo $insert_sql; #exit;
                    $this->Easycase->query($insert_sql);
                }
            }
        }
        //progress
        #pr($updates);
        if (is_array($deletedTaskIds) && count($deletedTaskIds) > 0) {
            foreach ($deletedTaskIds as $key => $easycase_id) {
                if (intval($easycase_id) > 0) {
                    $sqldata = "SELECT case_no FROM `easycases` AS Easycase WHERE `id`='" . $easycase_id . "' ";
                    $dataeasycase = $this->Easycase->query($sqldata);
                    #pr($dataeasycase);
                    $case_no = $dataeasycase[0]['Easycase']['case_no'];
                    #echo "$case_no >> $project_id >> $easycase_id";exit;
                    if (intval($case_no) > 0 && intval($project_id) > 0) {
                        $this->ganttv2_delete_case($easycase_id, $case_no, $project_id);
                    }
                }
            }
        }
		if($default_milestone_msg != ''){
			$ret_arr = array('success' => 2, 'message' => $default_milestone_msg, 'ok' => true);
		}else{
			$ret_arr = array('success' => 1, 'message' => 'Tasks updated successfully.' . ($depend != 'Yes' ? "\nTask progress not updated." : ""), 'ok' => true);
		}
        echo json_encode($ret_arr);
        exit;
    }

    function ganttv2_delete_case($id, $cno, $pid) {
        $this->layout = 'ajax';
        /* $id = $this->params['data']['id'];
          $cno = $this->params['data']['cno'];
          $pid = $this->params['data']['pid']; */
        $this->Easycase->recursive = -1;
        $case_list = $this->Easycase->query('SELECT id FROM easycases WHERE case_no=' . $cno . " AND project_id = " . $pid);
        if ($case_list) {
            foreach ($case_list AS $key => $val) {
                $arr[] = $val['easycases']['id'];
            }
        }
        $delCsTitle = $this->Easycase->getCaseTitle($pid, $cno);

        $this->Easycase->query("DELETE FROM easycases WHERE case_no = $cno AND project_id = $pid ");

        $CaseActivity = ClassRegistry::init('CaseActivity');
        $CaseActivity->recursive = -1;
        $CaseActivity->query("DELETE FROM case_activities WHERE project_id=" . $pid . " AND case_no=" . $cno);
        $CaseRecent = ClassRegistry::init('CaseRecent');
        $CaseRecent->recursive = -1;
        $CaseRecent->query("DELETE FROM case_recents WHERE easycase_id IN (" . implode(',', $arr) . ") AND project_id= $pid");

        $CaseUserView = ClassRegistry::init('CaseUserView');
        $CaseUserView->recursive = -1;
        $CaseUserView->query("DELETE FROM case_user_views WHERE easycase_id IN (" . implode(',', $arr) . ") AND project_id= $pid");

        $easycs_mlst_cls = ClassRegistry::init('EasycaseMilestone');
        $easycs_mlst_cls->recursive = -1;
        $easycs_mlst_cls->query("DELETE FROM easycase_milestones WHERE easycase_id=" . $id . " AND project_id=" . $pid);

        $casefiles = ClassRegistry::init('CaseFile');
        $casefiles = ClassRegistry::init('CaseFile');
        $casefiles->recursive = -1;
        $cfiles = $casefiles->query("SELECT * FROM case_files WHERE easycase_id IN (" . implode(',', $arr) . ")");
        if ($cfiles) {
            foreach ($cfiles AS $k => $v) {
                @unlink(DIR_FILES . "case_files/" . $v['case_files']['file']);
            }
        }
        $casefiles->query("DELETE FROM case_files WHERE easycase_id IN (" . implode(',', $arr) . ")");

        //By Sunil
        //Delete records from case file drive table.
        $this->loadModel('CaseFileDrive');
        $condition = array('CaseFileDrive.easycase_id' => $arr);
        $deleteGoogle = $this->CaseFileDrive->deleteRows($condition);


        //socket.io implement start
        $Project = ClassRegistry::init('Project');
        $ProjectUser = ClassRegistry::init('ProjectUser');
        $ProjectUser->recursive = -1;

        $getUser = $ProjectUser->query("SELECT user_id FROM project_users WHERE project_id='" . $pid . "'");
        $prjuniq = $Project->query("SELECT uniq_id, short_name FROM projects WHERE id='" . $pid . "'");
        $prjuniqid = $prjuniq[0]['projects']['uniq_id'];
        $projShName = strtoupper($prjuniq[0]['projects']['short_name']);
        //$channel_name = 'my_channel_delete_case';
        $channel_name = $prjuniqid;

        $this->Postcase->iotoserver(array('channel' => $channel_name, 'message' => 'Updated.~~' . SES_ID . '~~' . $cno . '~~' . 'DEL' . '~~' . $delCsTitle . '~~' . $projShName));
        //socket.io implement end
        return true;
    }

    function convert_date_timezone($date = '') {
        $date = trim($date);
        if ($date == '' || $date == '0000-00-00 00:00:00' || $date == '0000-00-00') {
            $date = date('Y-m-d H:i:s');
        }
        return $date;
        return $this->tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $date, "datetime");
    }

    function format_date($time = '', $tz = '', $format = '') {
        $time = trim($time);

        $format = trim($format) != "" ? trim($format) : "Y-m-d H:i:s";
        if ($time > 0) {
            return date($format, strtotime(date('Y-m-d H:i:s', $time / 1000) . $tz . ' +0 second'));
        }
        return '';
    }

    function temp_ganttv2_ajax() {
        $this->loadModel('Project');
        $this->loadModel('Milestone');
        $this->loadModel('EasycaseMilestone');
        $this->loadModel('Easycase');

        $json_arr = array();
        $colors = array(0 => '#73BCDE', 1 => '#8BC2B9', 2 => '#F8B363', 3 => '#EA7373', 4 => '#9ECC61');

        #pr($this->request->data);exit;
        $prjuid = $this->request->data['prjid'];
        if (!empty($prjuid)) {
            $projects = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prjuid, 'Project.isactive' => '1'), 'recursive' => -1));
            $milestones = $this->Milestone->find('all', array('conditions' => array('Milestone.project_id' => $projects['Project']['id'])));
        } else {
            $projects = $this->Project->find('first', array('conditions' => array('Project.user_id' => SES_ID, 'Project.company_id' => SES_COMP, 'Project.isactive' => '1'), 'recursive' => -1));
            $milestones = $this->Milestone->find('all', array('conditions' => array('Milestone.project_id' => $projects['Project']['id'])));
            #$prjuid = $projects['Project']['id'];
            $prjuid = $projects['Project']['uniq_id'];
        }
        $pid = urldecode($prjuid);
        /* get current project id */
        $project = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => '' . $pid . ''),
            'fields' => 'id,name', 'recursive' => -1));
        $project_id = $project['Project']['id'];
        $project_name = $project['Project']['name'];
        #pr($project);exit;


        $sql = "select count(easycases.id) AS total_tasks,milestones.title,milestones.id, if(milestones.title is null,2,1) as milestonesort
            from easycases 
                    left join projects on easycases.project_id=projects.id
                    left join easycase_milestones on easycases.id=easycase_milestones.easycase_id
                    left join milestones on milestones.id=easycase_milestones.milestone_id and milestones.project_id=projects.id

            where  projects.uniq_id='$pid'  and easycases.isactive=1 and easycases.istype=1
            group by milestones.id
            order by milestonesort ASC,milestones.title ASC, easycases.title ASC;
            ";
        $tasks = $this->Easycase->query($sql);
        $page = 3;
        $rec_limit = 100;
        $limit = $page > 1 ? ($rec_limit * $page) : 100;
        $offset = $page > 1 ? ($rec_limit * $page) - $rec_limit : 0;
        echo $limit . " || " . $offset . "\n";
        /* $offset = 201;
          $limit = 300; */
        $total = 0;
        if (is_array($tasks) && count($tasks) > 0) {
            foreach ($tasks as $key => $val) {
                $task_count = $val[0]['total_tasks'];
                if ($total < $limit) {
                    if ($total >= $offset) {
                        $final_counts[intval($val['milestones']['id'])] = $val[0]['total_tasks'];
                    }
                    $total += $task_count;
                    echo $total . " >> " . $task_count . "\n";
                }
            }
        }

        pr($final_counts);
        exit;
        $sql = "select easycases.*,milestones.title,milestones.id,coalesce(milestones.id,0), if(milestones.title is null,2,1) as milestonesort
            from easycases 
                    left join projects on easycases.project_id=projects.id
                    left join easycase_milestones on easycases.id=easycase_milestones.easycase_id
                    left join milestones on milestones.id=easycase_milestones.milestone_id and milestones.project_id=projects.id

            where  projects.uniq_id='$pid'  and easycases.isactive=1 and easycases.istype=1
            order by milestonesort ASC,milestones.title ASC, easycases.title ASC;
            ";
        $tasks = $this->Easycase->query($sql);
        pr($tasks);
        exit;
    }
//get user listing for gantt setting
    function get_user_list(){
        $this->layout = 'ajax' ;
        $this->loadModel('User');
        $this->loadModel('GanttSetting');
		$clnt_sql = '';
		$clnt_fld = '';
		if(defined('CR') && CR==1){
			//$clnt_sql = 'AND CompanyUser.is_client = 0';
			$clnt_fld = ',CompanyUser.is_client';
		}
        $query = "SELECT CONCAT(User.name,User.last_name) as Name".$clnt_fld.",User.id AS user_id ,GanttSetting.id,GanttSetting.access_type AS access_type FROM users as User LEFT JOIN company_users as CompanyUser ON User.id = CompanyUser.user_id" 
           ." LEFT JOIN gantt_settings as GanttSetting ON User.id = GanttSetting.user_id WHERE CompanyUser.user_type > 2 ".$clnt_sql." AND User.isactive = 1 ORDER BY User.name ASC";
        $user_list = $this->User->query($query);
        $this->set('user_list',$user_list);       
    }
     // add the gantt settings
    function add_gantt_setting(){
        $this->layout = 'ajax';
        $this->loadModel('GanttSetting');
        #echo "<pre>";print_r($this->request->data);exit;
        if($this->request->data){
            $data = $this->request->data['gantt_settings'];
            $add_data = array();
            foreach($data['user_id'] as $ku => $vu){
                $add_data['GanttSetting']['id'] = $data['id'][$ku];
                $add_data['GanttSetting']['user_id'] = $vu;
                $add_data['GanttSetting']['access_type'] = isset($data['access_type'][$ku]) ? $data['access_type'][$ku] : 0 ;

                $this->GanttSetting->save($add_data);
}
            $this->Session->write("SUCCESS",__("Settings Saved Successfully"));
            $this->redirect(HTTP_ROOT . "gantt-chart");
        }
    }
}

?>