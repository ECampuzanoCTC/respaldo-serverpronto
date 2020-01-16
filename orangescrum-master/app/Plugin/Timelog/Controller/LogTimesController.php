<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 's3', array('file' => 's3' . DS . 'S3.php'));

App::import('Vendor', 'ElephantIO', array('file' => 'ElephantIO' . DS . 'Client.php'));

use ElephantIO\Client as ElephantIOClient;

class LogTimesController extends TimelogAppController {

    public $name = 'LogTimes';
    public $components = array('Format', 'Postcase', 'Sendgrid', 'Tmzone');
    public $helpers = array('Format');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function add_tasklog($data = Null, $log_id = '') {

        $this->loadModel('Project');
        $this->loadModel('LogTime');
        $this->loadModel('Easycase');
        $this->loadModel('EventDetail');
        if (!empty($this->request->data['start_time'])) {
            foreach ($this->request->data['start_time'] as $key => $val) {
                $this->request->data['start_time'][$key] = date("h:i a", strtotime($val));
            }
        }
        if (!empty($this->request->data['end_time'])) {
            foreach ($this->request->data['end_time'] as $key => $val) {
                $this->request->data['end_time'][$key] = date("h:i a", strtotime($val));
            }
        }
        $logdata = isset($data) && !empty($data) ? $data : $this->data;
        $task_id = isset($logdata['task_id']) ? trim($logdata['task_id']) : intval($logdata['hidden_task_id']);
        $this->Format = $this->Components->load('Format');
//        print_r($this->request->data); exit;
        if (defined('GNC') && GNC == 1) {
            $allowed = $this->task_dependency($task_id);
        } else {
            $allowed = 'Yes';
        }
        if ($allowed == 'No') {
            $resCaseProj = null;
            $resCaseProj['success'] = 'No';
            $resCaseProj['dependerr'] = __('Dependant tasks are not closed.', true);
            if (!empty($data)) {
                $resCaseProj['success'] = 'depend';
                return json_encode($resCaseProj);
            } else {
                echo json_encode($resCaseProj);
                exit;
            }
        } else {
            $log_id = !empty(trim($this->data['log_id'])) ? trim($this->data['log_id']) : $log_id;
            $this->loadModel('Project');
            $this->loadModel('LogTime');
            $this->loadModel('Easycase');
            $log_id = trim($this->data['log_id']);
            $mode = $log_id > 0 ? "edit" : "add";
            $slashes = $log_id > 0 ? '"' : '';
            $this->Project->recursive = -1;
            $projid = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $logdata['project_id']), 'fields' => array('Project.id')));
            $project_id = $projid['Project']['id'];

            $users = $logdata['user_id'];
            $task_dates = $logdata['task_date'];
            $start_time = $logdata['start_time'];
            $end_time = $logdata['end_time'];
            $totalbreak = $logdata['totalbreak'];
            $totalduration = $logdata['totalduration'];
            $description = $this->Format->convert_ascii(trim($logdata['description']));
            $easycase = array();

            $task_details = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $task_id), 'fields' => array('Easycase.*')));
            $easycase_uniq_id = $task_details['Easycase']['uniq_id'];
            $this->Format = $this->Components->load('Format');
            $caseuniqid = $this->Format->generateUniqNumber();
            $reply_type = isset($logdata['task_id']) ? 10 : 11;
            $easycase['Easycase']['uniq_id'] = $caseuniqid;
            $easycase['Easycase']['case_no'] = $task_details['Easycase']['case_no'];
            $easycase['Easycase']['case_count'] = 0;
            $easycase['Easycase']['project_id'] = $task_details['Easycase']['project_id'];
            $easycase['Easycase']['user_id'] = SES_ID;
            $easycase['Easycase']['updated_by'] = 0;
            $easycase['Easycase']['type_id'] = $task_details['Easycase']['type_id'];
            $easycase['Easycase']['priority'] = $task_details['Easycase']['priority'];
            $easycase['Easycase']['title'] = '';
            $easycase['Easycase']['message'] = $description;
            $easycase['Easycase']['hours'] = 0;
            $easycase['Easycase']['assign_to'] = $task_details['Easycase']['assign_to'];
            $easycase['Easycase']['istype'] = 2;
            $easycase['Easycase']['status'] = $task_details['Easycase']['status'];
            $easycase['Easycase']['legend'] = $task_details['Easycase']['legend'];
            $easycase['Easycase']['dt_created'] = GMT_DATETIME;
            $easycase['Easycase']['actual_dt_created'] = GMT_DATETIME;
            $easycase['Easycase']['reply_type'] = $reply_type;
            $easycase['Easycase']['previous_legend'] = $task_details['Easycase']['legend'];

            //  $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', case_no = '" . $task_details['Easycase']['case_no'] . "', 	case_count=0, project_id='" . $task_details['Easycase']['project_id'] . "', user_id='" . SES_ID . "', updated_by=0, type_id='" . $task_details['Easycase']['type_id'] . "', priority='" . $task_details['Easycase']['priority'] . "', title='', message='" . $description . "', hours='0', assign_to='" . $task_details['Easycase']['assign_to'] . "', istype='2',format='2', status='" . $task_details['Easycase']['status'] . "', legend='" . $task_details['Easycase']['legend'] . "', isactive=1, dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type=" . $reply_type . "");
            $this->Easycase->save($easycase);
            // $this->Easycase->query("INSERT INTO easycases SET uniq_id='" . $caseuniqid . "', case_no = '" . $task_details['Easycase']['case_no'] . "', 	case_count=0, project_id='" . $task_details['Easycase']['project_id'] . "', user_id='" . SES_ID . "', updated_by=0, type_id='" . $task_details['Easycase']['type_id'] . "', priority='" . $task_details['Easycase']['priority'] . "', title='', message='" . $description . "', hours='0',estimated_hours='0', assign_to='" . $task_details['Easycase']['assign_to'] . "', istype='2',format='2', status='" . $task_details['Easycase']['status'] . "', legend='" . $task_details['Easycase']['legend'] . "', previous_legend='" . $task_details['Easycase']['legend'] . "', isactive=1, dt_created='" . GMT_DATETIME . "',actual_dt_created='" . GMT_DATETIME . "',reply_type=" . $reply_type . "");
            $task_status = 0;
            $cntr = count($logdata['totalduration']);
            $chkids = isset($data) && !empty($data) ? $logdata['chked_ids'] : @array_flip(explode(",", rtrim($logdata['chked_ids'], ",")));
            if (defined('GINV') && GINV == 1) {
                $chkautos = isset($data) && !empty($data) ? $logdata['chked_autos'] : @array_flip(explode(",", rtrim($logdata['chked_autos'], ",")));
            }
            $LogTime = array();
            for ($i = 0; $i < $cntr; $i++) {
                $task_date = date('Y-m-d', strtotime($task_dates[$i]));
                if ($mode != 'edit') {
                    $LogTime[$i]['project_id'] = $project_id;
                    $LogTime[$i]['task_id'] = $task_id;
                    if ($users[$i] != '') {
                        $LogTime[$i]['user_id'] = $users[$i];
                    }
                    $LogTime[$i]['task_status'] = $task_status;
                    $LogTime[$i]['ip'] = $_SERVER['REMOTE_ADDR'];
                }

                /* start time set start */
                $spdts = explode(':', $start_time[$i]);
                #converted to min
                if (strpos($start_time[$i], 'am') === false) {
                    $nwdtshr = ($spdts[0] != 12) ? ($spdts[0] + 12) : $spdts[0];
                    $dt_start = strstr($nwdtshr . ":" . $spdts[1], 'pm', true) . ":00";
                } else {
                    $nwdtshr = ($spdts[0] != 12) ? ($spdts[0] ) : '00';
                    $dt_start = strstr($nwdtshr . ":" . $spdts[1], 'am', true) . ":00";
                }
                $minute_start = ($nwdtshr * 60) + $spdts[1];
                /* start time set end */

                /* end time set start */
                $spdte = explode(':', $end_time[$i]);
                #converted to min
                if (strpos($end_time[$i], 'am') === false) {
                    $nwdtehr = ($spdte[0] != 12) ? ($spdte[0] + 12) : $spdte[0];
                    $dt_end = strstr($nwdtehr . ":" . $spdte[1], 'pm', true) . ":00";
                } else {
                    $nwdtehr = ($spdte[0] != 12) ? ($spdte[0]) : '00';
                    $dt_end = strstr($nwdtehr . ":" . $spdte[1], 'am', true) . ":00";
                }
                $minute_end = ($nwdtehr * 60) + $spdte[1];
                /* end time set end */

                /* checking if start is greater than end then add 24 hr in end i.e. 1440 min */
                $duration = $minute_end >= $minute_start ? ($minute_end - $minute_start) : (($minute_end + 1440) - $minute_start);
                $task_end_date = $minute_end >= $minute_start ? $task_date : date('Y-m-d', strtotime($task_date . ' +1 day'));

                /* total working */
                $break_time = trim($totalbreak[$i]);
                if (strpos($break_time, '.')) {
                    $split_break = ($break_time * 60);
                    $break_hour = (intval($split_break / 60) < 10 ? "0" : "") . intval($split_break / 60);
                    $break_min = (intval($split_break % 60) < 10 ? "0" : "") . intval($split_break % 60);
                    $break_time = $break_hour . ":" . $break_min;
                    $minute_break = $split_break;
                } elseif (strpos($break_time, ':')) {
                    $split_break = explode(':', $break_time);
                    #converted to min
                    $minute_break = ($split_break[0] * 60) + $split_break[1];
                } else {
                    $break_time = $break_time . ":00";
                    $minute_break = $break_time;
                }
                $minute_break = $duration < $minute_break ? 0 : $minute_break;
                /* break ends */

                /* total hrs start */
                $total_duration = $duration - $minute_break;
                $total_hours = $total_duration * 60;
                /* total hrs end */

                $LogTime[$i]['task_date'] = $slashes . $task_date . $slashes;
                $LogTime[$i]['start_time'] = $slashes . $dt_start . $slashes;
                $LogTime[$i]['end_time'] = $slashes . $dt_end . $slashes;

                /* here we are convering time to UTC as the date has been selected by user to in local time */
                #converted to UTC
                $this->Tmzone = $this->Components->load('Tmzone');
                $LogTime[$i]['start_datetime'] = $slashes . $this->Tmzone->convert_to_utc(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $task_date . " " . $dt_start, "datetime") . $slashes;
                $LogTime[$i]['end_datetime'] = $slashes . $this->Tmzone->convert_to_utc(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $task_end_date . " " . $dt_end, "datetime") . $slashes;

                #stored in sec
                $LogTime[$i]['break_time'] = $minute_break * 60;
                #stored in sec
                $LogTime[$i]['total_hours'] = $total_hours;
                $LogTime[$i]['is_billable'] = $logdata['is_billable'][$i];
                if (isset($data) && !empty($data)) {
                    $LogTime[$i]['is_billable'] = isset($logdata['is_billable'][$i]) && !empty($logdata['is_billable'][$i]) ? 1 : 0;
                    if (defined('GINV') && GINV) {
                        #$LogTime[$i]['auto_generate_invoice'] = $chkautos[$i];
                        $LogTime[$i]['auto_generate_invoice'] = isset($chkautos[$i]) ? 1 : 0;
                    }
                } else {
                    $LogTime[$i]['is_billable'] = isset($chkids[$i]) ? 1 : 0;
                    if (defined('GINV') && GINV) {
                        $LogTime[$i]['auto_generate_invoice'] = isset($chkautos[$i]) ? 1 : 0;
                    }
                }
                $LogTime[$i]['description'] = $slashes . addslashes(trim($logdata['description'])) . $slashes;
            }
            $valid = $this->validate_time_log($logdata, $project_id);
            if (is_array($valid) && $valid['success'] == 'No') {
                if (isset($data) && !empty($data)) {
                    return json_encode($valid);
                } else {
                    echo json_encode($valid);
                    exit;
                }
            }
            if ($log_id > 0) {
                $this->LogTime->updateAll($LogTime[0], array('LogTime.log_id' => $log_id));
            } else {
                $this->LogTime->saveAll($LogTime);
            }
            /* $log = $this->LogTime->getDataSource()->showLog(false);debug($log); */

            /* update easycases task dt_created */
            if (intval($task_id) > 0) {
                $this->Easycase->id = $task_id;
                $this->Easycase->save(array('dt_created' => date('Y-m-d H:i:s')));
            }

            /* update last project user visited */
            $this->loadModel('ProjectUser');
            $this->ProjectUser->recursive = -1;
            $this->ProjectUser->updateAll(array('ProjectUser.dt_visited' => "'" . GMT_DATETIME . "'"), array('ProjectUser.project_id' => $projid['Project']['id'], 'ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP));
            /* $ProjectUser['id'] = $projid['Project']['id'];
              $ProjectUser['dt_visited'] = GMT_DATETIME;
              $this->ProjectUser->save($ProjectUser); */
            if (defined('GTLG') && GTLG == 1) {
                $this->Format = $this->Components->load('Format');
                $this->Postcase = $this->Components->load('Postcase');
                $postParam = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $task_id)));
                $this->Format->delete_booked_hours(array('easycase_id' => $postParam['Easycase']['id'], 'project_id' => $postParam['Easycase']['project_id']));
                if ($postParam['Easycase']['estimated_hours'] != '' && $postParam['Easycase']['gantt_start_date'] != '' && $postParam['Easycase']['assign_to'] != 0) {
                    $isAssignedUserFree = $this->Postcase->setBookedData($postParam, $postParam['Easycase']['estimated_hours'], $task_id, SES_COMP);
                }
            }
            if ($logdata['page_type'] == 'details') {
                echo json_encode(array('success' => true, 'task_id' => $easycase_uniq_id));
            } else {
                if (isset($data)) {
                    return json_encode(array('status' => 'success'));
                } else {
                    $this->redirect(HTTP_ROOT . 'easycases/timelog');
                }
            }
        }
        exit;
    }

    /* Author GKM
     *  this method is not used anymore
     */
    /* function timelog() {
      $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
      $prjuniqueid = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
      $usr_id = "";
      $usid = "";
      $stdt = "";
      $st_dt = "";
      if ($this->data['projuniqid'] || $this->data['usrid'] || $this->data['strddt'] || $this->data['enddt']) {
      $this->layout = 'ajax';
      if ($prjuniqueid != $this->data['projuniqid']) {
      $this->loadModel('Project');
      $projid = $this->Project->find('first', array('fields' => array('Project.id'), 'conditions' => array('Project.uniq_id' => $this->data['projuniqid'])));
      $prjid = $projid['Project']['id'];
      }
      } else {
      $prjusr = $GLOBALS['projUser'][$prjuniqueid];
      foreach ($prjusr as $p => $k) {
      $rsrclist[$k['User']['id']] = $k['User']['name'] . " " . $k['User']['last_name'];
      }
      $this->set('rsrclist', $rsrclist);
      }
      if ($this->data['usrid']) {
      $usrid = $this->data['usrid'];
      $usr_id = " AND `LogTime`.`user_id` = $usrid";
      $usid = " AND user_id = '" . $usrid . "'";
      }
      if ($this->data['strddt'] && $this->data['enddt']) {
      $stdt = " AND `LogTime`.`task_date` BETWEEN '" . date('Y-m-d', strtotime($this->data['strddt'])) . "' AND '" . date('Y-m-d', strtotime($this->data['enddt'])) . "'";
      $st_dt = " AND task_date BETWEEN '" . date('Y-m-d', strtotime($this->data['strddt'])) . "' AND '" . date('Y-m-d', strtotime($this->data['enddt'])) . "'";
      }
      if ($this->data['projuniqid'] && !(isset($this->data['usrid']) && isset($this->data['strddt']) && isset($this->data['enddt']))) {
      $this->layout = 'ajax';
      if ($prjuniqueid != $this->data['projuniqid']) {
      $this->loadModel('Project');
      $projid = $this->Project->find('first', array('fields' => array('Project.id'), 'conditions' => array('Project.uniq_id' => $this->data['projuniqid'])));
      $prjid = $projid['Project']['id'];
      }
      $this->loadModel('ProjectUser');
      $this->ProjectUser->recursive = -1;
      $this->ProjectUser->updateAll(array('ProjectUser.dt_visited' => "'" . GMT_DATETIME . "'"), array('ProjectUser.project_id' => $prjid, 'ProjectUser.user_id' => $_SESSION['Auth']['User']['id']));
      }
      // print $usr_id;
      $this->loadModel('LogTime');
      $logtimes = $this->LogTime->find('all', array('conditions' => array("LogTime.project_id = $prjid" . $usr_id . $stdt), 'order' => 'created DESC'));
      $this->set('logtimes', $logtimes);
      $cntlog = $this->LogTime->query('SELECT sum(total_hours) as secds,is_billable FROM log_times WHERE is_billable = 1 and project_id = "' . $prjid . '" ' . $usid . $st_dt . ' GROUP BY project_id  UNION SELECT sum(total_hours) as secds, is_billable FROM log_times WHERE is_billable = 0 and project_id ="' . $prjid . '" ' . $usid . $st_dt . ' GROUP BY project_id ');

      $thoursbillable = floor($cntlog[0][0]['secds'] / 3600) . " hrs " . round((($cntlog[0][0]['secds'] % 3600) / 60), 2) . "mins";
      $thours = ($cntlog[0][0]['secds'] + $cntlog[1][0]['secds']);
      $thrs = floor($thours / 3600) . " hrs " . round((($thours % 3600) / 60), 2) . " mins";
      $this->set('thoursbillable', $thoursbillable);
      $this->set('thrs', $thrs);

      $this->loadModel('Easycase');
      $cntestmhrs = $this->Easycase->query('SELECT sum(estimated_hours) as hrs FROM easycases WHERE project_id = "' . $prjid . '"');
      $this->set('cntestmhrs', $cntestmhrs[0][0]['hrs']);
      if ($this->data['projuniqid'] && $this->data['usrid']) {
      echo $this->render('/Elements/timelog');
      exit;
      } else if ($this->data['strddt'] && $this->data['enddt']) {
      echo $this->render('/Elements/timelog');
      exit;
      } else if ($this->data['projuniqid']) {
      $projUser = $this->Easycase->getMemebers($this->data['projuniqid']);
      $this->set('resCaseProj', $projUser);
      echo $this->render('/Elements/timelog');
      exit;
      }
      } */

    function existing_task() {
        $this->layout = 'ajax';

        $this->loadModel('Project');
        $page = $this->request->data['page'] ? $this->request->data['page'] : '';
        $this->Project->recursive = -1;
        $projid = $this->Project->find('first', array('fields' => array('Project.id'), 'conditions' => array('Project.uniq_id' => $this->data['projuniqid'])));

        $this->loadModel('Easycase');
        $tsktitles = $this->Easycase->find('list', array('fields' => array("Easycase.id", "Easycase.title"), 'conditions' => array('Easycase.project_id' => $projid['Project']['id'], 'Easycase.title != ""', 'Easycase.isactive=1', 'istype=1')));

        $this->set('tsklist', $tsktitles);
        $page != '' ? $this->set('page', $page) : '';
    }

    function deleteInvoiceTimeLog() {
        $id = $this->request['data']['v'];
        $this->loadModel('InvoiceLog');
        $this->InvoiceLog->id = $id;
        $log_id = $this->InvoiceLog->field('log_id');

        if ($log_id > 0) {
            $this->loadModel('LogTime');
            $this->LogTime->query('update log_times set task_status=0 where log_id=' . $log_id);
        }

        if ($this->InvoiceLog->delete($id))
            echo 1;
        else
            echo 0;
        exit;
    }

    function ajaxTimeList() {
        //$prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $this->layout = 'ajax';
        $this->loadModel('ProjectUser');
        $projid = $this->ProjectUser->find('first', array('conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('ProjectUser.project_id'), 'order' => 'dt_visited DESC'));
        $prjid = $projid['ProjectUser']['project_id'];
        /* Start Andola Pageination */
        $page_limit = CASE_PAGE_LIMIT;
        $page = 1;
        if (isset($_GET['page']) && $_GET['page']) {
            $page = $_GET['page'];
        }
        $limit1 = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;
        $this->loadModel('LogTime');
        $logtimes = $this->LogTime->find('all', array('conditions' => array('LogTime.project_id' => $prjid, 'LogTime.is_billable' => 1, 'LogTime.task_status' => 0), 'order' => 'created DESC', 'limit' => $limit2, 'offset' => $limit1));
        $tot = $this->LogTime->find('count', array('conditions' => array('LogTime.project_id' => $prjid, 'LogTime.is_billable' => 1, 'LogTime.task_status' => 0), 'order' => 'created DESC'));
        $this->set('caseCount', $tot);
        $this->set('page_limit', $page_limit);
        $this->set('page', $page);
        $this->set('casePage', $page);
        $this->set('logtimes', $logtimes);
        /* End Andola Pageination */

        $cntlog = $this->LogTime->query('SELECT sum(total_hours) as secds,is_billable FROM log_times WHERE is_billable = 1 and project_id = "' . $prjid . '" GROUP BY project_id  UNION SELECT sum(total_hours) as secds, is_billable FROM log_times WHERE is_billable = 0 and project_id ="' . $prjid . '" GROUP BY project_id ');

        $thoursbillable = floor($cntlog[0][0]['secds'] / 3600) . " hours " . (($cntlog[0][0]['secds'] % 3600) / 60) . "minutes";
        $thours = ($cntlog[0][0]['secds'] + $cntlog[1][0]['secds']);
        $thrs = floor($thours / 3600) . " hours " . (($thours % 3600) / 60) . " minutes";
        $this->set('thoursbillable', $thoursbillable);
        $this->set('thrs', $thrs);
        $this->loadModel('Easycase');
        $cntestmhrs = $this->Easycase->query('SELECT sum(estimated_hours) as hrs FROM easycases WHERE project_id = "' . $prjid . '"');
        $this->set('cntestmhrs', $cntestmhrs[0][0]['hrs']);

        if (defined('INV') && INV == 1) {
            $this->loadModel('Invoice');
            $invoice = $this->Invoice->find('list', array('fields' => array('Invoice.id', 'Invoice.invoice_no'), 'recursive' => 0));
            $this->set('invoice', $invoice);

            $invoicecount = $this->Invoice->find('count', array('conditions' => array('Invoice.user_id' => SES_ID)));
            $this->set('invoiceCount', $invoicecount);
        }
    }

    /* Girish: for timelog paging */

    function time_log() {
        if (isset($this->request->data['page']) && $this->request->data['page'] == 'log') {
            $this->layout = '';
        }
        $this->loadModel('Easycase');
        $this->loadModel('Payment');

        $prjuniqueid = isset($this->request->data['projFil']) && !empty($this->request->data['projFil']) ? $this->request->data['projFil'] : $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        $projUser = $this->Easycase->getMemebers($prjuniqueid);
        $this->set('resCaseProj', $projUser);


        /* get company details */
        $Company = ClassRegistry::init('Company');
        $Company->recursive = -1;
        $getCompany = $Company->find('first', array('conditions' => array('Company.id' => SES_COMP), 'fields' => 'name'));
        $this->set('from', $getCompany['Company']['name']);
        /* Get user email */
        $fromEmail = $this->User->findById(SES_ID);
        $this->set('email', $fromEmail['User']['email']);
    }

    /* time log listing */

    function ajax_timelog_listing() {
        $this->layout = 'ajax';
        $this->loadModel('Easycase');
        $this->loadModel('LogTime');
        $this->loadModel('Project');
        $this->loadModel('ProjectUser');
        $project_id = array();
        $projFil = $this->request->data['params']['projFil'] ? $this->request->data['params']['projFil'] : $this->request->data['projFil'];
        if ($projFil == "0") {
            $projFil = "all";
        }
        if ($_COOKIE['All_Project'] && ($_COOKIE['All_Project'] == 'all')) {
            $projFil = "all";
        } else {
            $project_id[] = isset($GLOBALS['curProjId']) && !empty($GLOBALS['curProjId']) ? $GLOBALS['curProjId'] : $GLOBALS['getallproj'][0]['Project']['id'];
            $prjuniqueid = isset($GLOBALS['projUniq']) && !empty($GLOBALS['projUniq']) ? $GLOBALS['projUniq'] : $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        }

        $usid = '';
        $st_dt = '';
        $where = '';
        $filter_text = "";
        $current_projroleId = $last_projroleId = 0;
        if (defined('ROLE') && ROLE == 1) {
            $lastprjsql = "SELECT ProjectUser.*,CompanyUser.role_id as role_id,Project.id from project_users as ProjectUser LEFT JOIN company_users as CompanyUser ON ProjectUser.user_id = CompanyUser.user_id LEFT JOIN projects as Project ON ProjectUser.project_id = Project.id WHERE ProjectUser.user_id = " . SES_ID . " AND ProjectUser.company_id =" . SES_COMP . " ORDER BY ProjectUser.dt_visited DESC LIMIT 1";
            $projlastupdate = $this->ProjectUser->query($lastprjsql);
            $last_projroleId = !empty($projlastupdate[0]['ProjectUser']['role_id']) ? $projlastupdate[0]['ProjectUser']['role_id'] : $projlastupdate[0]['CompanyUser']['role_id'];
            $current_projroleId = $last_projroleId;
        }
        if ($this->request->data['params']['projFil'] && !(isset($this->request->data['params']['usrid']) && isset($this->request->data['strddt']) && isset($this->request->data['enddt']))) {
            if ($prjuniqueid != $projFil && $projFil != "all") {
                $this->loadModel('Project');
                $projid = $this->Project->find('first', array('fields' => array('Project.id'), 'conditions' => array('Project.uniq_id' => $projFil)));
                $prjid = $projid['Project']['id'];
                $this->loadModel('ProjectUser');
                $this->ProjectUser->recursive = -1;
                $this->ProjectUser->updateAll(array('ProjectUser.dt_visited' => "'" . GMT_DATETIME . "'"), array('ProjectUser.project_id' => $prjid, 'ProjectUser.user_id' => $_SESSION['Auth']['User']['id']));
            }
        }
        if (defined('ROLE') && ROLE == 1) {
            $curentprjsql = "SELECT ProjectUser.*,CompanyUser.role_id,Project.id as role_id from project_users as ProjectUser LEFT JOIN company_users as CompanyUser ON ProjectUser.user_id = CompanyUser.user_id LEFT JOIN projects as Project ON ProjectUser.project_id = Project.id WHERE ProjectUser.user_id = " . SES_ID . " AND ProjectUser.company_id =" . SES_COMP . " ORDER BY ProjectUser.dt_visited DESC LIMIT 1";
            $projcurntupdate = $this->ProjectUser->query($curentprjsql);
            $current_projroleId = !empty($projcurntupdate[0]['ProjectUser']['role_id']) ? $projcurntupdate[0]['ProjectUser']['role_id'] : $projcurntupdate[0]['CompanyUser']['role_id'];
            // print_r($projcurntupdate);
        }

        $page_limit = (isset($this->request->data['page_limit']) && !empty($this->request->data['page_limit'])) ? $this->request->data['page_limit'] : 30;
        #$page_limit = 10;
        if ($this->request->data['casePage']) {
            $casePage = $this->request->data['casePage']; // Pagination
        } else {
            $casePage = 1;
        }
        $pageprev = 1;
        $limit1 = $casePage * $page_limit - $page_limit;
        $limit2 = $page_limit;

        $this->set('page_limit', $page_limit);
        /* company name */
        $Company = ClassRegistry::init('Company');
        $Company->recursive = -1;
        $getCompany = $Company->find('first', array('conditions' => array('Company.id' => SES_COMP), 'fields' => 'name'));
        $this->set('from', $getCompany['Company']['name']);

        /* project details */
        if (isset($projFil) && !empty($projFil)) {
            $this->Project->recursive = -1;
            if ($projFil != 'all') {
                $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $projFil, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
                unset($project_id);
                $project_id[] = $projArr['Project']['id'];
            } else {
                $projArr = $this->Project->find('all', array('conditions' => array('Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
                unset($project_id);
                foreach ($projArr as $k => $pro) {
                    $project_id[] = $pro['Project']['id'];
                }
            }
        }
        if ($this->request->data['strddt']) {
            $startdate = $this->request->data['strddt'];
        } else {
            if ($_COOKIE['flt_typ'] == 'date') {
                $startdate = $_COOKIE['flt_val'];
            } else {
                $startdate = $_COOKIE['logstrtdt'];
            }
        }
        if ($this->request->data['enddt']) {
            $enddate = $this->request->data['enddt'];
        } else {
            $enddate = $_COOKIE['logenddt'];
        }
        if ($_COOKIE['datelog']) {
            $date_flt = $_COOKIE['datelog'];
        } else {
            if ($_COOKIE['flt_typ'] == 'date') {
                $date_flt = $_COOKIE['flt_val'] . ":" . $_COOKIE['flt_val'];
            }
        }
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTime = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $dateFilter = isset($this->request->data['datelog']) && $this->request->data['datelog'] != '' ? $this->request->data['datelog'] : $date_flt;
        if (strpos($dateFilter, ':')) {
            $dt = explode(':', $dateFilter);
            $date['strddt'] = $dt[0];
            $date['enddt'] = $dt[1];
            $startdate = $dt[0];
            $enddate = $dt[1];
        } else {
            $date = $this->Format->date_filter($dateFilter, $curDateTime);
            $startdate = $date['strddt'];
            $enddate = $date['enddt'];
        }
        if ($this->request->data['usrid']) {
            $usrid = $this->request->data['usrid'];
            $user_name = $this->request->data['user_name'];
            setcookie("user_name", $user_name, time() + 3600 * 24 * 365);
        } else {
            if ($_COOKIE['flt_typ'] == 'user') {
                $usrid = $_COOKIE['flt_val'];
            } else {
                $usrid = $_COOKIE['rsrclog'];
            }
            if (strpos($usrid, '-')) {
                $usrid = explode('-', $usrid);
            }
            $user_name = $_COOKIE["user_name"];
        }
        if ($this->request->data['date']) {
            $startdate = $this->request->data['date'];
            $enddate = $this->request->data['date'];
            $date['strddt'] = $this->request->data['date'];
            $date['enddt'] = $this->request->data['date'];
        }

        if ($date['strddt'] && $date['enddt']) {
            $where .= " AND `LogTime`.`task_date` BETWEEN '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND '" . date('Y-m-d', strtotime($date['enddt'])) . "'";
            $st_dt = " AND task_date BETWEEN '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND '" . date('Y-m-d', strtotime($date['enddt'])) . "'";
            if ($date['strddt'] != $date['enddt']) {
                $filter_text .= __("from", true) . " " . $this->Format->dateFormat($date['strddt']) . " " . __("to", true) . " " . $this->Format->dateFormat($date['enddt']);
            } else {
                $filter_text .= "for " . $date['strddt'];
            }
        } elseif ($date['strddt']) {
            $where .= " AND `LogTime`.`task_date` >= '" . date('Y-m-d', strtotime($date['strddt'])) . "'";
            $st_dt = " AND task_date >= '" . date('Y-m-d', strtotime($date['strddt'])) . "'";
            $filter_text .= __("from", true) . " " . $this->Format->dateFormat($date['strddt']);
        } elseif ($date['enddt']) {
            $where .= " AND `LogTime`.`task_date` <= '" . date('Y-m-d', strtotime($date['enddt'])) . "'";
            $st_dt = " AND task_date <= '" . date('Y-m-d', strtotime($date['enddt'])) . "'";
            $filter_text .= __("up to", true) . " " . $this->Format->dateFormat($date['enddt']);
        }
        if ($usrid) {
            if (is_array($usrid)) {
                $usrin = rtrim(implode(',', $usrid), ',');
                $where .= " AND `LogTime`.`user_id` IN (" . $usrin . ") ";
                $usid = " AND user_id = '" . $usrid . "'";
                $count_usid = " AND LogTime.user_id IN (" . $usrin . ") ";
            } else {
                $where .= " AND LogTime.user_id = '" . $usrid . "'";
                $usid = " AND user_id = '" . $usrid . "'";
                $count_usid = " AND LogTime.user_id = '" . $usrid . "'";
            }
            $filter_text .= " " . __("of user", true) . " ";
            if (strstr($usrid, "-")) {
                $expst4 = explode("-", $usrid);
                $cbymems = $this->Format->caseMemsList($expst4);
                foreach ($cbymems as $key => $st4) {
                    $filter_text .= $st4 . " and";
                }
                $filter_text = rtrim($filter_text, ' and');
            } else {
                $filter_text .= $this->Format->caseMemsList($usrid);
            }
        }
        if (SES_TYPE == 3) {
            $where .= " AND `LogTime`.`user_id`=" . SES_ID;
        }
        //for users
        if ($this->request->data['csid']) {
            $where .= " AND `LogTime`.`task_id` =" . $this->request->data['csid'];
            $filter_text .= " " . __("for task", true) . " '" . $this->request->data['cstitle'] . "'";
        }
        $curCaseId = "0";
        $extra_condition = "";
        $sort_cond = " ORDER BY `LogTime`.`created` DESC";
        $reset = 0;
        if (isset($this->request->data['type']) && $this->request->data['type'] == 'Date' || $_COOKIE['timelogsort'] == 'Date') {
            $sort_cond = " ORDER BY `task_date`";
            $reset = 1;
            $filter_text .= "order by date";
        } else if (isset($this->request->data['type']) && $this->request->data['type'] == 'Name' || $_COOKIE['timelogsort'] == 'Name') {
            $sort_cond = " ORDER BY `user_id`";
            $reset = 1;
            $filter_text .= "order by Name";
        } else if (isset($this->request->data['type']) && $this->request->data['type'] == 'Task' || $_COOKIE['timelogsort'] == 'Task') {
            $sort_cond = " ORDER BY `task_id`";
            $reset = 1;
            $filter_text .= "order by Task";
        } else if (isset($this->request->data['type']) && $this->request->data['type'] == 'Project' || $_COOKIE['timelogsort'] == 'Project') {
            $sort_cond = " ORDER BY `project_id`";
            $reset = 1;
            $filter_text .= "order by Project";
        }
        if ($_COOKIE['datelog'] || $_COOKIE['rsrclog']) {
            $reset = 1;
        }
        if (isset($this->request->data['sort']) && $this->request->data['sort'] == 'ASC') {
            $sort_cond = $sort_cond . ' ASC';
        } else if ($this->request->data['sort'] == 'DESC') {
            $sort_cond = $sort_cond . ' DESC';
        }
        $usrCndn = '';
        if (SES_TYPE == 3) {
            $usrCndn = ' AND LogTime.user_id=' . SES_ID;
        }
        $paid_condition = "";
        if (isset($this->request->data['ispaid']) && !empty($this->request->data['ispaid'])) {
            if ($this->request->data['ispaid'] == "paid") {
                $paid_condition = "AND LogTime.log_id  IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN (" . implode(',', $project_id) . ") AND payments.is_paid >0 AND payment_logs.log_id>0)";
            } else if ($this->request->data['ispaid'] == "unpaid") {
                $paid_condition = "AND LogTime.log_id  NOT IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN (" . implode(',', $project_id) . ") AND payments.is_paid >0 AND payment_logs.log_id>0)";
            } else {
                $paid_condition = "";
            }
        } else {
            $ispaid = $_COOKIE["ispaid"];
            if ($ispaid == "paid") {
                $paid_condition = "AND LogTime.log_id  IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN (" . implode(',', $project_id) . ") AND payments.is_paid >0 AND payment_logs.log_id>0)";
            } else if ($ispaid == "unpaid") {
                $paid_condition = "AND LogTime.log_id  NOT IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN (" . implode(',', $project_id) . ") AND payments.is_paid >0 AND payment_logs.log_id>0)";
            } else {
                $paid_condition = "";
            }
        }
        $logsql = "SELECT SQL_CALC_FOUND_ROWS LogTime.*,Project.name AS project_name,"
                . " DATE_FORMAT(LogTime.start_datetime,'%M %d %Y %H:%i:%s') AS start_datetime_v1,"
                . "(SELECT CONCAT_WS(' ',User.name,User.last_name) FROM users AS `User` WHERE `User`.id=LogTime.user_id) AS user_name, "
                . "(SELECT CONCAT_WS('||',title,uniq_id) FROM easycases AS `Easycase` WHERE `Easycase`.id=LogTime.task_id LIMIT 1) AS task_name "
                . "FROM `log_times` AS `LogTime` "
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id LEFT JOIN projects AS Project ON  LogTime.project_id=Project.id "
                . "WHERE LogTime.project_id IN (" . implode(',', $project_id) . ") AND Easycase.isactive=1" . $where . " " . " " . $paid_condition . $sort_cond . " LIMIT $limit1, $limit2";
        #echo $logsql;exit;
        $logtimes = $this->LogTime->query($logsql);
        #pr($logtimes);exit;
        if (is_array($logtimes) && count($logtimes) > 0) {
            foreach ($logtimes as $key => $val) {#May 05 2015 11:05:00
                $logtimes[$key]["LogTime"]['start_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logtimes[$key]["LogTime"]['start_datetime'], "datetime");
                $logtimes[$key]["LogTime"]['end_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logtimes[$key]["LogTime"]['end_datetime'], "datetime");
                $logtimes[$key][0]['start_datetime_v1'] = date('M d Y H:i:s', strtotime($logtimes[$key]["LogTime"]['start_datetime']));

                $logtimes[$key]['LogTime']['start_time'] = date('H:i:s', strtotime($logtimes[$key]['LogTime']['start_datetime']));
                $logtimes[$key]['LogTime']['end_time'] = date('H:i:s', strtotime($logtimes[$key]['LogTime']['end_datetime']));
            }
        }
        $tot = $this->LogTime->query("SELECT FOUND_ROWS() as total");
        $caseCount = $tot[0][0]['total'];
        $count_sql = 'SELECT SUM(hrs.secds) as seconds,hrs.is_billable from (SELECT sum(total_hours) as secds,is_billable '
                . 'FROM log_times AS `LogTime` '
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . 'WHERE is_billable = 1 AND Easycase.isactive =1 AND LogTime.project_id IN (' . implode(",", $project_id) . ') ' . $usrCndn . ' ' . $count_usid . $st_dt . ' '
                . 'GROUP BY LogTime.project_id  '
                . 'UNION '
                . 'SELECT sum(total_hours) as secds, is_billable '
                . 'FROM log_times AS LogTime '
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . 'WHERE is_billable = 0 AND Easycase.isactive =1 AND LogTime.project_id IN (' . implode(",", $project_id) . ') ' . $usrCndn . ' ' . $count_usid . $st_dt . ' '
                . 'GROUP BY LogTime.project_id ) as hrs GROUP BY hrs.is_billable ';
        $cntlog = $this->LogTime->query($count_sql);
        if ($cntlog[0]['hrs']['is_billable'] == 1) {
            $billablehours = $cntlog[0][0]['seconds'];
        }
        if (isset($cntlog[1]['hrs']['is_billable']) && $cntlog[1]['hrs']['is_billable'] == 1) {
            $billablehours = $cntlog[1][0]['seconds'];
        }

        $thoursbillable = ($billablehours);
        $thours = ($cntlog[0][0]['seconds'] + ((isset($cntlog[1][0]['seconds']) && !empty($cntlog[1][0]['seconds'])) ? $cntlog[1][0]['seconds'] : 0 ));
        $thrs = ($thours);

        /* find the paid time and unpaid time */
        $ispaid_times = 'SELECT sum(total_hours) as seconds '
                . 'FROM log_times AS `LogTime` '
                . 'WHERE LogTime.log_id IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN(' . implode(",", $project_id) . ') AND payments.is_paid >0 AND payment_logs.log_id>0) '
                . ' AND LogTime.project_id IN(' . implode(",", $project_id) . ')' . $usrCndn . ' ' . $count_usid . $st_dt . ' '
                . 'GROUP BY LogTime.project_id ';
        $ispaid_time = $this->LogTime->query($ispaid_times);

        $isunpaid_times = ($thrs - $ispaid_time[0][0]['seconds']);

        $nonBillableHrs = ($thours - $billablehours);
        $tasks = (trim($usid) != '' || trim($st_dt) != '') ? ' AND id IN (SELECT task_id FROM log_times As LogTime WHERE project_id IN(' . implode(",", $project_id) . ')' . $usrCndn . ' ' . $usid . $st_dt . ')' : '';
        $estsql = "SELECT SUM(estimated_hours) AS hrs FROM easycases WHERE isactive=1 AND project_id IN(" . implode(',', $project_id) . ") AND istype=1 " . $tasks;
        $cntestmhrs = $this->Easycase->query($estsql);
        $caseTitleRep = '';
        $pgShLbl = '';
        $logtimesArr = array('logs' => $logtimes,
            'task_id' => $curCaseId,
            'task_title' => $caseTitleRep,
            'pgShLbl' => $pgShLbl,
            'csPage' => $casePage,
            'page_limit' => $page_limit,
            'caseCount' => $caseCount,
            'showTitle' => 'Yes',
            'page' => 'timelog',
            'calltype' => $this->request->data['page'],
            'check_sort' => $this->request->data['sort'],
            'reset' => $reset,
            'details' => array(
                'totalHrs' => $thrs,
                'billableHrs' => $thoursbillable,
                'nonbillableHrs' => $nonBillableHrs,
                'estimatedHrs' => trim($cntestmhrs[0][0]['hrs']),
        ));
        $projUser = array();
        if ($projFil) {
            $projUser = $this->Easycase->getMemebers($projFil);
        } else {
            $projUser = $this->Easycase->getMemebers($prjuniqueid);
        }
        #pr($projUser);exit;
        $caseDetail['projUser'] = $projUser;

        //pr($logtimesArr);exit;
        $caseDetail['logtimes'] = $logtimesArr;

        if (defined('INV') && INV == 1) {
            /* to find the time log whose invoice are done */
            $invce_conditions = array('LogTime.project_id' => $project_id,
                'LogTime.is_billable' => 1,
                "LogTime.log_id  IN (SELECT invoice_logs.log_id FROM invoice_logs LEFT JOIN invoices ON invoices.id=invoice_logs.invoice_id WHERE invoices.project_id IN(" . implode(',', $project_id) . ") AND invoice_logs.log_id>0)",
            );
            $invce_timelog = $this->LogTime->find('all', array('conditions' => $invce_conditions, 'fields' => 'LogTime.log_id'));
            $invc_tlgid = array();
            foreach ($invce_timelog as $ik => $iv) {
                $invc_tlgid[] = $iv['LogTime']['log_id'];
            }
        }
        /* to find the timelog whose payment has been made */
        $payment_conditions = array('LogTime.project_id' => $project_id,
            "LogTime.log_id  IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN(" . implode(',', $project_id) . ") AND payment_logs.log_id > 0)",
        );
        $payment_timelog = $this->LogTime->find('all', array('conditions' => $payment_conditions, 'fields' => 'LogTime.log_id'));
        $payment_tlgid = array();
        foreach ($payment_timelog as $ik => $iv) {
            $payment_tlgid[] = $iv['LogTime']['log_id'];
        }
        /* user details */
        $fromEmail = $this->User->findById(SES_ID);
        $this->set('email', $fromEmail['User']['email']);
        // end of to find the time log whose invoice are done //
        $this->set('invc_tlgid', $invc_tlgid);
        $this->set('payment_tlgid', $payment_tlgid);
        //   if(defined('ROLE') && ROLE == 1){
        $this->set("last_projroleId", $last_projroleId);
        $this->set("current_projroleId", $current_projroleId);
        //    }
        $this->set("filter_text", $filter_text);
        $this->set('resCaseProj', $projUser);
        $this->set('startdate', $startdate);
        $this->set('enddate', $enddate);
        $this->set('usrid', $usrid);
        $this->set('prjctId', $projFil);
        $logtimesArr['details']['billableHrs'] = $this->Format->format_time_hr_min($logtimesArr['details']['billableHrs']);
        $logtimesArr['details']['nonbillableHrs'] = $this->Format->format_time_hr_min($logtimesArr['details']['nonbillableHrs']);
        $logtimesArr['details']['totalHrs'] = $this->Format->format_time_hr_min($logtimesArr['details']['totalHrs']);
        $logtimesArr['details']['estimatedHrs'] = $this->Format->format_time_hr_min($logtimesArr['details']['estimatedHrs']);
        $logtimesArr['details']['break_time'] = $this->Format->format_time_hr_min($logtimesArr['details']['break_time']);
        $logtimesArr['details']['paid_time'] = $this->Format->format_time_hr_min($ispaid_time[0][0]['seconds']);
        $logtimesArr['details']['unpaid_time'] = $this->Format->format_time_hr_min($isunpaid_times);
        $this->set('logtimesArr', $logtimesArr);
    }

    /* Author: CHP
      use: to fetch payment created for a particular user
     */

    function unpaidPaymentList() {
        /* get payment details */
        $this->loadModel('Payment');
        $this->loadModel('Project');
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $payee_id = 0;
        if ($this->request->data['usr_id']) {
            $payee_id = $this->request->data['usr_id'];
        }
        if ($this->request->data['prjct_id']) {
            $prjct_id = $this->request->data['prjct_id'];
            if ($prjct_id == 'all') {
                $prjid = $this->Project->find('list', array('conditions' => array('Project.company_id' => SES_COMP, 'Project.isactive' => 1), 'fields' => 'Project.id'));
            } else {
                $prjid = $this->Project->find('list', array('conditions' => array('Project.uniq_id' => $prjct_id, 'Project.company_id' => SES_COMP, 'Project.isactive' => 1), 'fields' => 'Project.id'));
            }
        } else {
            $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        }

        $options = array();
        $options['fields'] = array('Payment.id', 'Payment.payment_no');
        $options['conditions'] = array('Payment.project_id' => $prjid, 'Payment.is_paid' => 0, 'Payment.payee_id' => $payee_id);
        $options['recursive'] = false;
        $options['joins'] = array(array('table' => 'users', 'alias' => 'User', 'type' => 'LEFT', 'conditions' => array('User.id = Payment.payee_id')));

        $payment = $this->Payment->find('list', $options);
        echo json_encode($payment);
        exit;
        // $this->set('payment', $payment);
    }

    /* Author: GKM 
      use: to fetch timelog details and populate in edit mode
     */

    function timelog_details() {
        if (isset($this->data['logid'])) {
            $this->loadModel('LogTime');
            $log_id = intval($this->data['logid']);
            $logtimes = $this->LogTime->find('all', array('fields' => array("LogTime.*,LogTime.log_id as id" . ", DATE_FORMAT(LogTime.start_datetime,'%M %d %Y %H:%i:%s') AS start_datetime_v1"), 'conditions' => array('LogTime.log_id' => $log_id)));
            #$logtimes[0]['LogTime']['start_datetime_v1']=$logtimes[0][0]['start_datetime_v1'];
            $logtimes[0]['LogTime']['srt_datetime_v1'] = $logtimes[0]['LogTime']['start_datetime'];
            $logtimes[0]['LogTime']['end_datetime_v1'] = $logtimes[0]['LogTime']['end_datetime'];
            $logtimes[0]['LogTime']['start_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logtimes[0]['LogTime']['start_datetime'], "datetime");
            $logtimes[0]['LogTime']['end_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logtimes[0]['LogTime']['end_datetime'], "datetime");

            $logtimes[0]['LogTime']['start_datetime_v1'] = date('M d Y H:i:s', strtotime($logtimes[0]['LogTime']['start_datetime']));
            $logtimes[0]['LogTime']['start_time'] = date('H:i:s', strtotime($logtimes[0]['LogTime']['start_datetime']));
            $logtimes[0]['LogTime']['end_time'] = date('H:i:s', strtotime($logtimes[0]['LogTime']['end_datetime']));
            //pr($logtimes);exit;
            unset($logtimes[0]['LogTime']['ip']);
            unset($logtimes[0]['LogTime']['project_id']);

            echo json_encode($logtimes[0]['LogTime']);
        }
        exit;
    }

    /* Author: GKM 
      use: to delete timelog details
     */

    function delete_timelog($logid = Null) {
        $log_id = !empty($this->data['logid']) ? intval($this->data['logid']) : $logid;
        if (!empty($log_id)) {
            $this->loadModel('Timelog.LogTime');
            /* $this->LogTime->log_id = $log_id;
              $res = $this->LogTime->delete(); */
            $log_time = $this->LogTime->find('first', array('conditions' => array("LogTime.log_id" => $log_id)));
            $task_details = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $log_time['LogTime']['task_id'])));
            $res = $this->LogTime->query("DELETE FROM log_times WHERE log_id='" . $log_id . "'");
            #$retArr = array('success' => ($res ? 1 : 0));
            if (defined('GTLG') && GTLG == 1) {
                $this->Format = $this->Components->load('Format');
                $this->Postcase = $this->Components->load('Postcase');
                $postParam = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $task_details['Easycase']['id'])));
                $this->Format->delete_booked_hours(array('easycase_id' => $postParam['Easycase']['id'], 'project_id' => $postParam['Easycase']['project_id']));
                if ($postParam['Easycase']['estimated_hours'] != '' && $postParam['Easycase']['gantt_start_date'] != '' && $postParam['Easycase']['assign_to'] != 0) {
                    $isAssignedUserFree = $this->Postcase->setBookedData($postParam, $postParam['Easycase']['estimated_hours'], $task_details['Easycase']['id'], SES_COMP);
                }
            }
            $retArr = array('success' => 1);
        } else {
            $retArr = array('success' => 0);
        }
        if (!empty($logid)) {
            return $retArr;
        } else {
            echo json_encode($retArr);
        }
        exit;
    }

    /* Author: GKM
     * use: to check overlaping log times 
     */

    function validate_time_log($data, $project_id) {
        $this->loadModel('LogTime');
        $this->loadModel('Project');
        #pr($data);echo $project_id;exit;
        $return_val = true;
        if (!is_array($data) && trim($project_id) == '') {
            $data = $this->data;
            $this->Project->recursive = -1;
            $projid = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $data['project_id']), 'fields' => array('Project.id')));
            $project_id = $projid['Project']['id'];
            $return_val = false;
        }
        $log_id = trim($data['log_id']);
        $mode = $log_id > 0 ? "edit" : "add";
        if ($mode == 'edit') {
            $logtimes = $this->LogTime->find('all', array('fields' => array("LogTime.*,LogTime.log_id as id"), 'conditions' => array('LogTime.log_id' => $log_id)));
            $task_id = $logtimes[0]['LogTime']['task_id'];
            $users[0] = $logtimes[0]['LogTime']['user_id'];
        } else {
            #$task_id = trim($data['task_id']);
            $task_id = isset($data['task_id']) ? trim($data['task_id']) : intval($data['hidden_task_id']);
            $users = $data['user_id'];
        }
        $task_dates = $data['task_date'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $totalbreak = $data['totalbreak'];
        $totalduration = $data['totalduration'];
        $cntr = count($data['totalduration']);
        $user_id_arr = array();

        /* formating logtime array */
        $LogTime = array();
        for ($i = 0; $i < $cntr; $i++) {
            $task_date = date('Y-m-d', strtotime($task_dates[$i]));
            $user_id_arr[] = $users[$i];
            $LogTime[$i]['user_id'] = $users[$i];
            $LogTime[$i]['project_id'] = $project_id;
            $LogTime[$i]['task_id'] = $task_id;

            /* start time set start */
            $spdts = explode(':', $start_time[$i]);
            #converted to min

            if (strpos($start_time[$i], 'am') === false) {
                $nwdtshr = ($spdts[0] != 12) ? ($spdts[0] + 12) : trim($spdts[0]);
                $dt_start = strstr($nwdtshr . ":" . $spdts[1], 'pm', true) . ":00";
            } else {
                $nwdtshr = ($spdts[0] != 12) ? ($spdts[0] < 10 ? "0" : "") . $spdts[0] : '00';
                $dt_start = strstr($nwdtshr . ":" . $spdts[1], 'am', true) . ":00";
            }
            $minute_start = ($nwdtshr * 60) + $spdts[1];
            /* start time set end */

            /* end time set start */
            $spdte = explode(':', $end_time[$i]);
            #converted to min

            if (strpos($end_time[$i], 'am') === false) {
                $nwdtehr = ($spdte[0] != 12) ? ($spdte[0] + 12) : $spdte[0];
                $dt_end = strstr($nwdtehr . ":" . $spdte[1], 'pm', true) . ":00";
            } else {
                $nwdtehr = ($spdte[0] != 12) ? ($spdte[0] < 10 ? "0" : "") . $spdte[0] : '00';
                $dt_end = strstr($nwdtehr . ":" . $spdte[1], 'am', true) . ":00";
            }
            $minute_end = ($nwdtehr * 60) + $spdte[1];
            /* end time set end */

            /* checking if start is greater than end then add 24 hr in end i.e. 1440 min */
            $duration = $minute_end >= $minute_start ? ($minute_end - $minute_start) : (($minute_end + 1440) - $minute_start);
            $task_end_date = $minute_end >= $minute_start ? $task_date : date('Y-m-d', strtotime($task_date . ' +1 day'));

            /* total working */
            $break_time = trim($totalbreak[$i]);
            if (strpos($break_time, '.')) {
                $split_break = ($break_time * 60);
                $break_hour = (intval($split_break / 60) < 10 ? "0" : "") . intval($split_break / 60);
                $break_min = (intval($split_break % 60) < 10 ? "0" : "") . intval($split_break % 60);
                $break_time = $break_hour . ":" . $break_min;
                $minute_break = $split_break;
            } elseif (strpos($break_time, ':')) {
                $split_break = explode(':', $break_time);
                #converted to min
                $minute_break = ($split_break[0] * 60) + $split_break[1];
            } else {
                $break_time = $break_time . ":00";
                $minute_break = $break_time * 60;
            }
            $minute_break = $duration < $minute_break ? 0 : $minute_break;
            /* break ends */

            /* total hrs start */
            $total_duration = $duration - $minute_break;
            /* $total_hrs = floor($total_duration / 60);
              $total_mins = (intval($total_duration % 60) < 10 ? "0" : "") . intval($total_duration % 60);
              $total_hours = $total_hrs . ":" . $total_mins; */
            $total_hours = $total_duration;
            /* total hrs end */

            $LogTime[$i]['task_date'] = $task_date;
            $LogTime[$i]['start_time'] = $dt_start;
            $LogTime[$i]['end_time'] = $dt_end;

            /* not converted to utc as we are validating with current times only */
            $LogTime[$i]['start_datetime'] = $task_date . " " . $dt_start;
            $LogTime[$i]['end_datetime'] = $task_end_date . " " . $dt_end;
            #converted to UTC
            #$LogTime[$i]['start_datetime'] = $this->Tmzone->convert_to_utc(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $task_date . " " . $dt_start, "datetime");
            #$LogTime[$i]['end_datetime'] = $this->Tmzone->convert_to_utc(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $task_end_date . " " . $dt_end, "datetime");
            #stored in sec
            $LogTime[$i]['break_time'] = $minute_break * 60;
            #stored in sec
            $LogTime[$i]['total_hours'] = $total_hours * 60;
            $log_final_arr[$LogTime[$i]['user_id']][] = $LogTime[$i];
        }
        $LogTime = $log_final_arr;

        /* fetching all old records of the task from log_times table */
        $user_ids = array_unique($user_id_arr);
        $condition = array("LogTime.user_id IN (" . (is_array($user_ids) && count($user_ids) > 0 ? implode(',', $user_ids) : "''") . ")", "LogTime.task_id" => $task_id);
        $existing_data = $this->LogTime->find('all', array('fields' => array("LogTime.*,LogTime.log_id as id"), 'conditions' => $condition));
        #$log = $this->LogTime->getDataSource()->showLog(false);debug($log);
        $existing_dates = array();
        $existing_logtime = array();
        if (is_array($existing_data) && count($existing_data) > 0) {
            foreach ($existing_data as $key => $val) {
                $existing_logtime[$val['LogTime']['user_id']][] = array(
                    'id' => $val['LogTime']['id'],
                    'user_id' => $val['LogTime']['user_id'],
                    'task_id' => $val['LogTime']['task_id'],
                    'task_date' => $val['LogTime']['task_date'],
                    'start_time' => $val['LogTime']['start_time'],
                    'end_time' => $val['LogTime']['end_time'],
                    'start_datetime' => $val['LogTime']['start_datetime'], #these from db, so are in UTC
                    'end_datetime' => $val['LogTime']['end_datetime'], #these from db, so are in UTC
                );
            }
        }
        $overrlap = false;
        $overlap_msg = array();
        if (is_array($LogTime) && count($LogTime) > 0) {
            /* loop of users */
            foreach ($LogTime as $userkey => $plog) {
                /* loop of user logs */
                foreach ($plog as $pKey => $pVal) {
                    /* compare with new time log data */
                    if (isset($LogTime[$userkey])) {
                        foreach ($LogTime[$userkey] as $cKey => $cVal) {
                            #pr($cVal);pr($pVal);
                            if ($pKey != $cKey) {
                                $start_datetime = $cVal['start_datetime']; #converted time in UTC
                                $end_datetime = $cVal['end_datetime']; #converted time in UTC
                                if (
                                        ($start_datetime < $pVal['start_datetime'] && $end_datetime > $pVal['start_datetime']) || ($start_datetime < $pVal['end_datetime'] && $end_datetime > $pVal['end_datetime']) || ($start_datetime == $pVal['start_datetime'] && $end_datetime == $pVal['end_datetime']) || ($start_datetime > $pVal['start_datetime'] && $end_datetime < $pVal['end_datetime']) || ($start_datetime > $pVal['start_datetime'] && $end_datetime == $pVal['end_datetime']) || ($start_datetime == $pVal['start_datetime'] && $end_datetime < $pVal['end_datetime'])
                                ) {
                                    $overrlap = true;
                                    $overlap_msg[$userkey][$pVal['start_datetime'] . '||' . $pVal['end_datetime']] = array(
                                        'user_id' => $userkey,
                                        'task_date' => date('M d, Y', strtotime($pVal['start_datetime'])),
                                        'start_time' => trim(date('h:ia', strtotime($pVal['start_datetime'])), '0'),
                                        'end_time' => trim(date('h:ia', strtotime($pVal['end_datetime'])), '0')
                                    );
                                }
                            }
                        }
                    }
                    /* end */
                    /* compare with db records */
                    if (isset($existing_logtime[$userkey])) {
                        foreach ($existing_logtime[$userkey] as $cKey => $cVal) {
                            #pr($cVal);pr($pVal);
                            #echo $log_id." != ".$cVal['id']."<br>";
                            if ($mode != 'edit' || ($mode == 'edit' && $log_id != $cVal['id'])) {
                                $start_datetime = trim($cVal['start_datetime']) > 0 ? $cVal['start_datetime'] : $cVal['task_date'] . " " . $cVal['start_time']; #this is from db, so is in UTC
                                $end_datetime = trim($cVal['end_datetime']) > 0 ? $cVal['end_datetime'] : $cVal['task_date'] . " " . $cVal['end_time']; #this is from db, so is in UTC
                                /* converting date time got from db to user's local time to check overlaping time */
                                $start_datetime = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $start_datetime, "datetime");
                                $end_datetime = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $end_datetime, "datetime");
                                #echo $start_datetime." < ".$pVal['start_datetime']." && ".$end_datetime." > ".$pVal['end_datetime']."<br>";
                                if (
                                        ($start_datetime < $pVal['start_datetime'] && $end_datetime > $pVal['start_datetime']) || ($start_datetime < $pVal['end_datetime'] && $end_datetime > $pVal['end_datetime']) || ($start_datetime == $pVal['start_datetime'] && $end_datetime == $pVal['end_datetime']) || ($start_datetime > $pVal['start_datetime'] && $end_datetime < $pVal['end_datetime']) || ($start_datetime > $pVal['start_datetime'] && $end_datetime == $pVal['end_datetime']) || ($start_datetime == $pVal['start_datetime'] && $end_datetime < $pVal['end_datetime'])
                                ) {
                                    $overrlap = true;
                                    $overlap_msg[$userkey][$pVal['start_datetime'] . '||' . $pVal['end_datetime']] = array(
                                        'log_id' => $cVal['id'],
                                        'user_id' => $userkey,
                                        'task_date' => date('M d, Y', strtotime($pVal['start_datetime'])),
                                        'start_time' => trim(date('h:ia', strtotime($pVal['start_datetime'])), '0'),
                                        'end_time' => trim(date('h:ia', strtotime($pVal['end_datetime'])), '0'),
                                        'db_task_date' => date('Y-m-d', strtotime($start_datetime)),
                                        'db_start_time' => trim(date('h:ia', strtotime($start_datetime)), '0'),
                                        'db_end_time' => trim(date('h:ia', strtotime($end_datetime)), '0')
                                    );
                                }
                            }
                        }
                    }
                    /* end */
                }
            }
        }
        if (is_array($overlap_msg) && count($overlap_msg) > 0) {
            foreach ($overlap_msg as $key => $val) {
                $overlap_msg[$key] = array_values($val);
            }
        }
        $ret_arr = array(
            'success' => $overrlap ? 'No' : 'Yes',
            'data' => $overlap_msg
        );
        #pr($overlap_msg);exit;
        #pr($existing_logtime);pr($LogTime);exit;
        if ($return_val) {
            return $ret_arr;
        } else {
            echo json_encode($ret_arr);
            exit;
        }
    }

    /* Author: GKM
     * it is used to prepare logtime data while add, edit and reply of tasks
     */

    function prepare_log_time_from_reply($arr, $task_details = array()) {
        $LogTime = array();
        $logdata = $arr['timelog'];

        #$task_date = date('Y-m-d',strtotime($logdata['taskdate']));
        #$task_date = date('Y-m-d');
        /* utc has been converted to users time zone */
        $task_date = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, date('Y-m-d H:i:s'), "date");

        $task_id = $arr['CS_id'] > 0 ? $arr['CS_id'] : ($arr['taskid'] > 0 ? $arr['taskid'] : intval($task_details['caseid']));
        $LogTime['task_id'] = $task_id;

        $LogTime['project_id'] = $arr['pid'];
        $LogTime['task_status'] = $arr['CS_legend'];

        $LogTime['user_id'][] = $arr['CS_assign_to'];
        $LogTime['task_date'][] = $task_date;
        $LogTime['start_time'][] = $logdata['start_time'];
        $LogTime['end_time'][] = $logdata['end_time'];

        $LogTime['totalbreak'][] = $logdata['break_time'];
        $LogTime['totalduration'][] = $logdata['hours'];

        $LogTime['is_billable'][] = isset($logdata['is_bilable']) && trim($logdata['is_bilable']) == 'Yes' ? 1 : 0;
        $LogTime['description'] = addslashes(trim($arr['CS_message']));

        return $LogTime;
    }

    /* author: GKM
     * to update time spent for project or task selected
     */

    function project_time_details() {
        $this->loadModel('Easycase');
        $this->loadModel('LogTime');
        $this->loadModel('Project');

        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $prjuniqueid = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        $project_id = $this->data['proid'];
        $task_id = intval($this->data['tskid']) > '0' ? trim($this->data['tskid']) : '';
        $prjunid = $this->data['prjunid'];

        /* project details */
        $this->Project->recursive = -1;
        $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $prjunid, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
        $project_id = $projArr['Project']['id'];

        $task_condition = trim($task_id) != '' ? " AND task_id = $task_id" : "";
        $es_task_condition = trim($task_id) != '' ? " AND id = $task_id" : "";
        /* $cntlog = $this->LogTime->query('SELECT SUM(total_hours) as secds,is_billable FROM log_times WHERE is_billable = 1 and project_id = "' . $project_id . '" ' . $task_condition . ' GROUP BY project_id  '
          . 'UNION '
          . 'SELECT SUM(total_hours) as secds, is_billable FROM log_times WHERE is_billable = 0 and project_id ="' . $project_id . '" ' . $task_condition . ' GROUP BY project_id ');

          #pr($cntlog); */
        $count_sql = 'SELECT SUM(total_hours) as secds,is_billable '
                . 'FROM log_times AS `LogTime` '
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . 'WHERE is_billable = 1 AND Easycase.isactive =1 AND LogTime.project_id = "' . $project_id . '" ' . $task_condition . ' '
                . 'GROUP BY LogTime.project_id  '
                . 'UNION '
                . 'SELECT sum(total_hours) as secds, is_billable '
                . 'FROM log_times AS LogTime '
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . 'WHERE is_billable = 0 AND Easycase.isactive =1 AND LogTime.project_id ="' . $project_id . '" ' . $task_condition . ' '
                . 'GROUP BY LogTime.project_id ';
        #echo $count_sql;exit;
        $cntlog = $this->LogTime->query($count_sql);
        #print_r($cntlog);exit;
        $billable_hours = $cntlog[0][0]['is_billable'] > 0 ? $cntlog[0][0]['secds'] : 0;
        $nonbillable_hours = $cntlog[1][0]['is_billable'] == 0 ? $cntlog[1][0]['secds'] : 0;
        $total_spent = ($cntlog[0][0]['secds'] + $cntlog[1][0]['secds']);

        /* /estimated hours/ */
        $est_sql = "SELECT SUM(estimated_hours) AS hrs "
                . "FROM easycases AS Easycase "
                . "WHERE project_id = '" . $project_id . "' AND istype=1 AND Easycase.isactive=1 " . $es_task_condition;
        $estimated = $this->Easycase->query($est_sql);
        $total_estimated = $estimated[0][0]['hrs'];
        #pr($total_estimated);exit;
        echo json_encode(array('billable_hours' => $billable_hours, 'total_spent' => $total_spent, 'nonBillableHrs' => $nonbillable_hours, 'total_estimated' => $total_estimated));
        exit;
    }

    function export_csv_timelog() {
        $this->loadModel('Easycase');
        $this->loadModel('LogTime');
        $this->LogTime = ClassRegistry::init('LogTime');
        $this->loadModel('Project');
        $this->loadModel('User');
        $view = new View($this);
        $frmt = $view->loadHelper('Format');
        $data = $this->params->query;
        $from_date = trim($data['strddt']);
        $to_date = trim($data['enddt']);
        $user_id = (isset($data['usrid']) && !empty($data['usrid'])) ? intval($data['usrid']) : SES_ID;
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $prjuniqueid = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        $projFil = trim($data['projuniqid']);
        $usid = '';
        $st_dt = '';
        $where = '';
        $project_id = array();
        /* project details */
        if ($projFil != '') {
            $this->Project->recursive = -1;
            if ($projFil != 'all') {
                $params = array(
                    'conditions' => array('Project.uniq_id' => $projFil, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP),
                    'fields' => array('Project.id'));

                $projArr = $this->Project->find('first', $params);
                $project_id[] = $projArr['Project']['id'];
            } else {
                $params = array(
                    'conditions' => array('Project.isactive' => 1, 'Project.company_id' => SES_COMP),
                    'fields' => array('Project.id'));
                $projArr = $this->Project->find('all', $params);
                foreach ($projArr as $ky => $vp) {
                    $project_id[] = $vp['Project'][id];
                }
            }
        } else {
            $project_id = $prjid;
            $projFil = $prjuniqueid;
        }
        if (!empty($user_id) && is_numeric($user_id)) {
            $usrid = $user_id;
            $where .= " AND `LogTime`.`user_id` = $usrid";
            $usid = " AND user_id = '" . $usrid . "'";
            $count_usid = " AND LogTime.user_id = '" . $usrid . "'";
        } else if (!empty($user_id) && !is_numeric($user_id)) {
            $usrid = $user_id;
            $where .= " AND `LogTime`.`user_id` IN (" . $usrid . ")";
            $usid = " AND user_id IN( '" . $usrid . "')";
            $count_usid = " AND LogTime.user_id IN (" . $usrid . ")";
        }
        if ($from_date != '' && $to_date != '') {
            $where .= " AND DATE(`LogTime`.`start_datetime`) BETWEEN '" . date('Y-m-d', strtotime($from_date)) . "' AND '" . date('Y-m-d', strtotime($to_date)) . "'";
            $st_dt = " AND DATE(start_datetime) BETWEEN '" . date('Y-m-d', strtotime($from_date)) . "' AND '" . date('Y-m-d', strtotime($to_date)) . "'";
        } elseif ($from_date != '') {
            $where .= " AND DATE(`LogTime`.`start_datetime`) >= '" . date('Y-m-d', strtotime($from_date)) . "'";
            $st_dt = " AND DATE(start_datetime) >= '" . date('Y-m-d', strtotime($from_date)) . "'";
        } elseif ($to_date != '') {
            $where .= " AND DATE(`LogTime`.`start_datetime`) <= '" . date('Y-m-d', strtotime($to_date)) . "'";
            $st_dt = " AND DATE(start_datetime) <= '" . date('Y-m-d', strtotime($to_date)) . "'";
        }
        $paid_condition = "";
        if (isset($data['ispaid']) && !empty($data['ispaid'])) {
            if ($data['ispaid'] == "paid") {
                $paid_condition = " AND LogTime.log_id  IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN (" . implode(',', $project_id) . ") AND payments.is_paid >0 AND payment_logs.log_id>0)";
            } else if ($data['ispaid'] == "unpaid") {
                $paid_condition = " AND LogTime.log_id  NOT IN (SELECT payment_logs.log_id FROM payment_logs LEFT JOIN payments ON payments.id=payment_logs.payment_id WHERE payments.project_id IN (" . implode(',', $project_id) . ") AND payments.is_paid >0 AND payment_logs.log_id>0)";
            } else {
                $paid_condition = "";
            }
        }
        $where .= $paid_condition;

        $this->loadModel('User');
        $options = array();
        $options['fields'] = array("LogTime.*", "DATE_FORMAT(LogTime.start_datetime,'%M %d %Y %H:%i:%s') AS start_datetime_v1",
            "(SELECT CONCAT_WS(' ',User.name,User.last_name) FROM users AS `User` WHERE `User`.id=LogTime.user_id) AS user_name",
            "(SELECT title FROM easycases AS `Easycase` WHERE Easycase.id=LogTime.task_id LIMIT 1) AS task_name");
        $options['joins'] = array(array('table' => 'easycases', 'alias' => 'Easycase', 'type' => 'LEFT', 'conditions' => array('Easycase.id=LogTime.task_id', 'LogTime.project_id=Easycase.project_id')));
        $options['conditions'] = array("LogTime.project_id" => $project_id, "Easycase.isactive" => 1, trim(trim($where), 'AND'));
        $options['order'] = 'created DESC';
        $logtimes = $this->LogTime->find('all', $options);
        $caseCount = $this->LogTime->find('count', $options);
        if (is_array($logtimes) && count($logtimes) > 0) {
            foreach ($logtimes as $key => $val) {
                $logtimes[$key]["LogTime"]['start_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logtimes[$key]["LogTime"]['start_datetime'], "datetime");
                $logtimes[$key]["LogTime"]['end_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logtimes[$key]["LogTime"]['end_datetime'], "datetime");
                $logtimes[$key][0]['start_datetime_v1'] = date('M d Y H:i:s', strtotime($logtimes[$key]["LogTime"]['start_datetime']));

                $logtimes[$key]['LogTime']['start_time'] = date('H:i:s', strtotime($logtimes[$key]['LogTime']['start_datetime']));
                $logtimes[$key]['LogTime']['end_time'] = date('H:i:s', strtotime($logtimes[$key]['LogTime']['end_datetime']));
            }
        }
        // pr($logtimes);exit;
        $view = new View();
        $project = '';
        $frmt = $view->loadHelper('Format');
        if (count($project_id) > 1) {
            $project = 'Project,';
        }
        $content = __($project . 'Date,Name,Task,Note,Start,End,Break,Billable,Hours', true);
        $content .= "\n";
        $total_billable_hours = 0;
        $total_non_billable_hours = 0;
        if (is_array($logtimes) && count($logtimes) > 0) {
            foreach ($logtimes as $key => $val) {
                if (count($project_id) > 1) {
                    $project_name = $frmt->getprjctUnqid($val['LogTime']['project_id']);
                    $content .= trim($project_name['Project']['name']);
                }
                if (count($project_id) > 1) {
                    $content .= "," . '"' . str_replace('"', '""', $this->Format->dateFormat($val[0]['start_datetime_v1'])) . '"';
                } else {
                    $content .= '"'.$this->Format->dateFormat($val[0]['start_datetime_v1']). '"';
                }
                $content .= "," . '"' . str_replace('"', '""', trim($val[0]['user_name'])) . '"';
                $content .= "," . '"' . str_replace('"', '""', trim($val[0]['task_name'])) . '"';
                $content .= "," . '"' . stripslashes(str_replace('"', '""', trim($val['LogTime']['description']))) . '"';
                $content .= "," . '"' . $this->Format->new_chngdttime($val['LogTime']['task_date'], $val['LogTime']['start_time']) . '"';
                $content .= "," . '"' . $this->Format->new_chngdttime($val['LogTime']['task_date'], $val['LogTime']['end_time']) . '"';
                $content .= "," . '"' . $this->Format->format_time_hr_min($val['LogTime']['break_time']) . '"';
                $content .= "," . '"' . ($val['LogTime']['is_billable'] == '1' ? 'Yes' : 'No') . '"';
                $content .= "," . '"' . $this->Format->format_time_hr_min($val['LogTime']['total_hours']) . '"';
                $content .= "\n";
                ($val['LogTime']['is_billable'] == '1' ? $total_billable_hours += $val['LogTime']['total_hours'] : $total_non_billable_hours += $val['LogTime']['total_hours']);
            }
        }
        $content .= "\n" . __("Export Date,", true). '"' . $this->Format->dateFormat(GMT_DATETIME). '"';
        $content .= "\n" . __("Total,", true) . $caseCount . " records";
        $content .= "\n" . __("Total Billable Hours,", true) . $this->Format->format_time_hr_min($total_billable_hours) . " ";
        $content .= "\n" . __("Total Non-Billable Hours,", true) . $this->Format->format_time_hr_min($total_non_billable_hours) . " ";
        $content .= "\n" . __("Total Hours,", true) . $this->Format->format_time_hr_min($total_billable_hours + $total_non_billable_hours) . " ";
        if (!is_dir(LOGTIME_CSV_PATH)) {
            @mkdir(LOGTIME_CSV_PATH, 0777, true);
        }

        $name = $projFil;
        if (trim($name) != '' && strlen($name) > 25) {
            $name = substr($name, 0, 24) . "_" . date('m-d-Y', strtotime(GMT_DATE)) . "_timelog.csv";
        } else {
            $name .= (trim($name) != '' ? "_" : '') . date('m-d-Y', strtotime(GMT_DATE)) . "_timelog.csv";
        }
        $download_name = date('m-d-Y', strtotime(GMT_DATE)) . "_timelog.csv";

        $file_path = LOGTIME_CSV_PATH . $name;
        $fp = @fopen($file_path, 'w+');
        fwrite($fp, $content);
        fclose($fp);

        $this->response->file($file_path, array('download' => true, 'name' => urlencode($download_name)));
        return $this->response;
    }

    /* to Save Timer data as log time */

    function saveTimer() {
        $data = $this->request->data['params'];
        $data1 = array();
        $data1['project_id'] = $data['project_id'];
        $data1['task_id'] = $data['task_id'];
        $data1['description'] = $data['description'];
        $data1['task_date'][0] = date('Y-m-d', $data['start_time'] / 1000);
        $start_time = date('Y-m-d H:ia', $data['start_time'] / 1000);
        $start_time = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $start_time, "datetime");
        $start_time = explode(' ', $start_time);
        $data1['start_time'][0] = $this->Tmzone->convert12hourformat($start_time[1]);
        $end_time = date('Y-m-d H:ia', $data['end_time'] / 1000);
        $end_time = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $end_time, "datetime");
        $end_time = explode(' ', $end_time);
        $data1['end_time'][0] = $this->Tmzone->convert12hourformat($end_time[1]);
        $data1['totalduration'][0] = (int) ($data['totalduration'] / 1000);
        $duration = (int) (($data['end_time'] - $data['start_time']) / 1000);
        $data1['totalbreak'][0] = (int) (($duration - $data1['totalduration'][0]) / 60);
        $data1['user_id'][0] = SES_ID;
        $data1['chked_ids'][0] = $data['chked_ids'];
        $Easycases = ClassRegistry::init('EasycasesController');
        $result = $this->add_tasklog($data1);
        echo $result;
        exit;
    }

    /*
     * @method resource_utilization
     * Author Satyajeet
     */

    function resource_utilization() {
        if (SES_TYPE > 2) {
            $this->redirect(HTTP_ROOT . 'timelog');
        }
        //$this->layout = 'ajax';
    }

    function ajax_resource_utilization() {
        $this->layout = 'ajax';
        $this->loadModel('Easycase');
        $this->loadModel('Project');
        $this->loadModel('User');
        $this->loadModel('CompanyUser');
        $this->loadModel('LogTime');
        $cond = "";
        $qry = '';
        $arr = array();
        $log_condition = '';
        $filter_msg = array();
        $dt_arr = array();
        $curDate = date('Y-m-d H:i:s');
        if (isset($_COOKIE['utilization_date_filter']) && $_COOKIE['utilization_date_filter'] != '' && $_COOKIE['utilization_date_filter'] != 'all') {
            $filter = $_COOKIE['utilization_date_filter'];
        }
        $sts_filter = $_COOKIE['utilization_status_filter'];
        $prj_filter = $_COOKIE['utilization_project_filter'];
        $usr_filter = $_COOKIE['utilization_resource_filter'];
        if (isset($sts_filter) && $sts_filter != '' && $sts_filter != 'all') {
            $qry .= $this->Format->statusFilter($sts_filter);
            if (substr($sts_filter, -1) == '-') {
                $sts = explode('-', $sts_filter);
                foreach ($sts as $k => $val) {
                    if ($val != '') {
                        $msg = '<div class="fl filter_opn"><span>' . $this->formatstsname($val) . '</span><span class="ico-close" rel="tooltip" title="Reset Filter" style="margin-left:3px;cursor:pointer;" onclick="removeStatus(' . $val . ')">X</span></div>';
                        $filter_msg['status'][] = $msg;
                    }
                }
                $filter_msg['status'] = array_unique($filter_msg['status']);
            }
        }
        if (isset($prj_filter) && $prj_filter != '' && $prj_filter != 'all') {
            $qry .= $this->projectFilter($prj_filter, 'utilization');
            $prj = explode('-', $prj_filter);
            foreach ($prj as $k => $val) {
                $msg = '<div class="fl filter_opn"><span>' . $this->formatprjnm($val) . '</span><span class="ico-close" rel="tooltip" title="Reset Filter" style="margin-left:3px;cursor:pointer;" onclick="removeProject(' . $val . ')">X</span></div>';
                $filter_msg['project'][] = $msg;
            }
            $filter_msg['project'] = array_unique($filter_msg['project']);
        }
        if (isset($usr_filter) && $usr_filter != '' && $usr_filter != 'all') {
            $qry .= $this->arcUserFilter($usr_filter, 'utilization');
            $usr = explode('-', $usr_filter);
            foreach ($usr as $k => $val) {
                $msg = '<div class="fl filter_opn"><span>' . $this->Format->caseMemsList($val) . '</span><span class="ico-close" rel="tooltip" title="Reset Filter" style="margin-left:3px;cursor:pointer;" onclick="removeResource(' . $val . ')">X</span></div>';
                $filter_msg['resource'][] = $msg;
            }
            $filter_msg['resource'] = array_unique($filter_msg['resource']);
        }
        if (!isset($filter)) {
            $date = $this->Format->date_filter('thismonth', $curDate);
        } else {
            if (strpos($filter, ':') == false) {
                $date = $this->Format->date_filter($filter, $curDate);
            } else {
                $arr = explode(':', $filter);
                $date['strddt'] = $arr[0];
                $date['enddt'] = $arr[1];
            }
        }
        #pr($date);exit;
        $limit = $this->data['rowCount'] ? $this->data['rowCount'] : 50;
        $offset = ($this->data['current'] > 1 ? $this->data['current'] - 1 : 0) * $limit;
        $current = $this->data['current'] > 1 ? $this->data['current'] : 1;
        $searchPhrase = $this->data['searchPhrase'];
        $search_cond = '';
        $sort_cond = ' order by LogTime.user_id ASC';
        $sort_cond1 = ' order by Result.user_id ASC';
        #echo $this->data['sort']['resource'];exit;
        if (isset($this->data['sort']['resource'])) {
            if ($this->data['sort']['resource'] == 'asc') {
                $sort_cond = " order by User.name ASC";
                $sort_cond1 = ' order by Result.name ASC';
            } else {
                $sort_cond = " order by User.name DESC";
                $sort_cond1 = " order by Result.name DESC";
            }
        } elseif (isset($this->data['sort']['project'])) {
            if ($this->data['sort']['project'] == 'asc') {
                $sort_cond = " order by Project.name ASC";
                $sort_cond1 = " order by Result.pname ASC";
            } else {
                $sort_cond = " order by Project.name DESC";
                $sort_cond1 = " order by Result.pname DESC";
            }
        } elseif (isset($this->data['sort']['date'])) {
            if ($this->data['sort']['date'] == 'asc') {
                $sort_cond = " order by LogTime.start_datetime ASC";
                $sort_cond1 = " order by Result.start_datetime ASC";
            } else {
                $sort_cond = " order by LogTime.start_datetime DESC";
                $sort_cond1 = " order by Result.start_datetime DESC";
            }
        } elseif (isset($this->data['sort']['task_title'])) {
            if ($this->data['sort']['task_title'] == 'asc') {
                $sort_cond = " order by Easycase.title ASC";
                $sort_cond1 = " order by Result.title ASC";
            } else {
                $sort_cond = " order by Easycase.title DESC";
            }
        } elseif (isset($this->data['sort']['hours'])) {
            if ($this->data['sort']['hours'] == 'asc') {
                $sort_cond = " order by hours ASC";
                $sort_cond1 = " order by Result.hours ASC";
            } else {
                $sort_cond = " order by hours DESC";
                $sort_cond1 = " order by Result.hours DESC";
            }
        } elseif (isset($this->data['sort']['esthrs'])) {
            if ($this->data['sort']['esthrs'] == 'asc') {
                $sort_cond1 = " order by est_hrs ASC";
            } else {
                $sort_cond1 = " order by est_hrs DESC";
            }
        } elseif (isset($this->data['sort']['task_group'])) {
            if ($this->data['sort']['task_group'] == 'asc') {
                $sort_cond1 = " order by Result.mlstn_name ASC";
            } else {
                $sort_cond1 = " order by Result.mlstn_name DESC";
            }
        }
        if (isset($searchPhrase) && trim($searchPhrase) != '') {
            $search_cond = " AND (User.name LIKE '%" . $searchPhrase . "%'";
            if (in_array('project', $this->data['check'])) {
                $search_cond .= " OR Project.name LIKE '%" . $searchPhrase . "%'";
            }
            if (in_array('task_title', $this->data['check'])) {
                $search_cond .= " OR Easycase.title LIKE '%" . $searchPhrase . "%'";
            }
            $search_cond .= ") ";
        }
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dtm = $view->loadHelper('Datetime');
        $fmt = $view->loadHelper('Format');
        if (!empty($date['strddt']) && !empty($date['enddt'])) {
            $cond .= " AND DATE(Easycase.actual_dt_created) >= '" . $dt . "' ";
            $log_condition .= " AND DATE(start_datetime) >= '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND DATE(start_datetime) <= '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
            $filter_msg['date'] = '<div class="fl filter_opn"><span>' . $fmt->chngdate($date['strddt']) . " to " . $fmt->chngdate($date['enddt']) . '</span><span class="ico-close" rel="tooltip" title="Reset Filter" style="margin-left:5px;;cursor:pointer;" onclick="removeDate()">X</span></div>';
            $days = (strtotime($date['enddt']) - strtotime($date['strddt'])) / (60 * 60 * 24);
        } else if (!empty($date['strddt'])) {
            $cond .= " AND DATE(Easycase.actual_dt_created) >= '" . $dt . "' ";
            $log_condition .= " AND DATE(start_datetime) = '" . date('Y-m-d', strtotime($date['strddt'])) . "' ";
            $filter_msg['date'] = '<div class="fl filter_opn"><span>' . $fmt->chngdate($date['strddt']) . '</span><span class="ico-close" rel="tooltip" title="Reset Filter" style="margin-left:5px;;cursor:pointer;" onclick="removeDate()">X</span></div>';
        } else if (isset($date['enddt']) && !empty($date['enddt'])) {
            $dt = date('Y-m-d', strtotime($date['enddt']));
            $cond .= " AND DATE(Easycase.actual_dt_created) <= '" . $dt . "' ";
            $log_condition .= " AND DATE(start_datetime) = '" . $dt . "' ";
            $filter_msg['date'] = '<div class="fl filter_opn"><span>' . $fmt->chngdate($date['enddt']) . '</span><span class="ico-close" rel="tooltip" title="Reset Filter" style="margin-left:5px;;cursor:pointer;" onclick="removeDate()">X</span></div>';
        }


        $grpby = $grpby1 = '';
        $groupbyarr = array('date' => 'DATE(LogTime.start_datetime)', 'resource' => 'LogTime.user_id',
            'project' => 'LogTime.project_id', 'task_title' => 'Easycase.id', 'hours' => 'hours', 'is_billable' => 'billable');
        $groupbyarr1 = array('date' => 'DATE(Result.start_datetime)', 'resource' => 'Result.user_id',
            'project' => 'Result.project_id', 'task_title' => 'Result.id', 'hours' => 'Result.hours', 'is_billable' => 'Result.billable');
        $grpby1 = $grpby = 'GROUP BY ';
        $str1 = $str = '';
        foreach ($this->data['check'] as $k => $val) {
            if ($val != 'task_status' && $val != 'hours' && $val != 'task_type' && $val != 'task_group' && $val != 'esthrs') {
                $str = $str . $groupbyarr['' . $val . ''] . ',';
                $str1 = $str1 . $groupbyarr1['' . $val . ''] . ',';
            }
        }
        $str = rtrim($str, ',');
        $str1 = rtrim($str1, ',');
        $grpby = (!empty($str)) ? $grpby . $str : '';
        $grpby1 = (!empty($str1)) ? $grpby1 . $str1 : '';
        $usr_cond = '';
        if (SES_TYPE < 3) {
            $usr_cond = "LogTime.user_id >0";
        } elseif (SES_TYPE == 3) {
            $usr_cond = "LogTime.user_id = " . SES_ID;
        }
        $log_sql_inner = "SELECT LogTime.user_id, SUM(LogTime.total_hours) AS hours, GROUP_CONCAT(Distinct LogTime.task_id)  AS esthrs, "
                . "if(LogTime.is_billable=1, 'Yes', 'No') AS billable, User.name, User.last_name, Project.name as pname, Easycase.id, Easycase.title, Easycase.legend, Easycase.type_id, LogTime.start_datetime,LogTime.project_id, Milestone.title AS mlstn_name "
                . "FROM log_times AS LogTime "
                . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                . "LEFT JOIN easycase_milestones AS EasycaseMilestone ON LogTime.task_id = EasycaseMilestone.easycase_id "
                . "LEFT JOIN milestones AS Milestone ON EasycaseMilestone.milestone_id=Milestone.id "
                . "LEFT JOIN users AS User ON LogTime.user_id = User.id "
                . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id "
                . "WHERE Easycase.isactive=1 AND " . $usr_cond . " " . $log_condition . " " . $qry . " " . $search_cond . " AND Project.company_id=" . SES_COMP . " AND Easycase.id IS NOT NULL "
                . "$grpby $sort_cond  LIMIT $offset, $limit";

        $log_sql = "SELECT Result.*,sum(p.estimated_hours) as est_hrs FROM ($log_sql_inner) AS Result LEFT JOIN easycases AS p ON find_in_set(p.id,Result.esthrs) LEFT JOIN projects pr ON p.project_id = pr.id WHERE pr.company_id =" . SES_COMP . " AND p.id IS NOT NULL AND Result.id IS NOT NULL $grpby1 $sort_cond1 ";
        //print $log_sql_inner;exit; 
        $logtime = $this->LogTime->query($log_sql);
        $count_sql = "SELECT SQL_CALC_FOUND_ROWS if(LogTime.is_billable=1, 'Yes', 'No') AS billable "
                . "FROM log_times AS LogTime "
                . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                . "LEFT JOIN easycase_milestones AS EasycaseMilestone ON LogTime.task_id = EasycaseMilestone.easycase_id "
                . "LEFT JOIN milestones AS Milestone ON EasycaseMilestone.milestone_id=Milestone.id "
                . "LEFT JOIN users AS User ON LogTime.user_id = User.id "
                . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id "
                . "WHERE Easycase.isactive=1 AND " . $usr_cond . " " . $log_condition . " " . $qry . " " . $search_cond . " AND Project.company_id=" . SES_COMP . " AND Easycase.id IS NOT NULL "
                . "$grpby $sort_cond";
        $total_count = $this->LogTime->query($count_sql);
        $tot_od = $this->LogTime->query("SELECT FOUND_ROWS() as tot_od");
        #$esthrsdata = $this->LogTime->query($log_sql);
        #echo "<pre>";print_r($logtime);exit;
        $data = array("current" => $current,
            "rowCount" => $limit,
            "rows" => array(),
            "total" => $tot_od[0][0]['tot_od'],
            "filter_msg" => $filter_msg);
        if ($logtime) {
            $legnds = Hash::extract($logtime, '{n}.Result.legend');
            $this->loadModel('Status');
            $legendDetails = $this->Status->find('list', array('conditions' => array('Status.id' => $legnds), 'fields' => array('Status.id', 'Status.name')));
            $legendDetails[4] = 'In Progress';
        }
        foreach ($logtime as $key => $value) {
            $hour = $this->Format->format_time_hr_min($value['Result']['hours']);
            $esthrs = $this->Format->format_time_hr_min($value['0']['est_hrs']);
            $name = $value['Result']['name'] . " " . $value['Result']['last_name'];
            $caseDtUploaded = $value['Result']['start_datetime'];
            $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtUploaded, "datetime");
            $updatedCur = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "date");
            $displayTime = $dtm->dateFormatOutputdateTime_day($updated, $updatedCur); //Nov 25, Thu at 1:25 pm
            $typeNm = $this->Format->getRequireTypeName($value['Result']['type_id']);
            $legend = $value['Result']['legend'];
            $legendname = $legendDetails[$legend];
            $data['rows'][] = array('date' => $fmt->chngdate($updated), 'resource' => $name,
                'project' => $fmt->formatTitle($value['Result']['pname']),
                'task_title' => $fmt->formatTitle($value['Result']['title']),
                'task_group' => $value['Result']['mlstn_name'] != '' ? $fmt->formatTitle($value['Result']['mlstn_name']) : 'Default Task Group',
                'task_status' => $legendname, 'task_type' => $typeNm, 'hours' => $hour,
                'esthrs' => $esthrs, 'is_billable' => $value['Result']['billable']);
        }
        echo json_encode($data);
        exit;
    }

    function ajax_resource_utilization_export_csv() {
        $this->layout = 'ajax';
        $this->loadModel('Easycase');
        $this->loadModel('Project');
        $this->loadModel('User');
        $this->loadModel('CompanyUser');
        $this->loadModel('LogTime');
        $cond = "";
        $arr = array();
        $log_condition = '';
        $curDate = date('Y-m-d H:i:s');
        if (isset($_COOKIE['utilization_date_filter']) && $_COOKIE['utilization_date_filter'] != '' && $_COOKIE['utilization_date_filter'] != 'all') {
            $filter = $_COOKIE['utilization_date_filter'];
        }

        $sts_filter = isset($_COOKIE['utilization_status_filter']) ? $_COOKIE['utilization_status_filter'] : '';
        $prj_filter = isset($_COOKIE['utilization_project_filter']) ? $_COOKIE['utilization_project_filter'] : '';
        $usr_filter = isset($_COOKIE['utilization_resource_filter']) ? $_COOKIE['utilization_resource_filter'] : '';

        if (isset($sts_filter) && $sts_filter != '' && $sts_filter != 'all') {
            $qry .= $this->Format->statusFilter($sts_filter);
        }
        if (isset($prj_filter) && $prj_filter != '' && $prj_filter != 'all') {
            $qry .= $this->projectFilter($prj_filter, 'utilization');
        }
        if (isset($usr_filter) && $usr_filter != '' && $usr_filter != 'all') {
            $qry .= $this->arcUserFilter($usr_filter, 'utilization');
        }
        if (!isset($filter)) {
            $date = $this->Format->date_filter('thismonth', $curDate);
        } else {
            if (strpos($filter, ':') == false) {
                $date = $this->Format->date_filter($filter, $curDate);
            } else {
                $arr = explode(':', $filter);
                $date['strddt'] = $arr[0];
                $date['enddt'] = $arr[1];
            }
        }

        $check = explode(',', $this->params->query['check']);
        $searchPhrase = $this->params->query['search'];
        $search_cond = '';
        $sort_cond = ' order by LogTime.user_id ASC';
        if (isset($searchPhrase) && trim($searchPhrase) != '') {
            $search_cond = " AND (User.name LIKE '%" . $searchPhrase . "%'";
            if (in_array('project', $check)) {
                $search_cond .= " OR Project.name LIKE '%" . $searchPhrase . "%'";
            }
            if (in_array('task_title', $check)) {
                $search_cond .= " OR Easycase.title LIKE '%" . $searchPhrase . "%'";
            }
            $search_cond .= ") ";
        }

        if (!empty($date['strddt']) && !empty($date['enddt'])) {
            //$log_condition .= " AND DATE(start_datetime) >= '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND DATE(start_datetime) <= '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
            $log_condition .= " AND DATE(start_datetime) BETWEEN '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
        } else if (!empty($date['strddt'])) {
            $log_condition .= " AND DATE(start_datetime) = '" . date('Y-m-d', strtotime($date['strddt'])) . "' ";
        } else if (!empty($date['enddt'])) {
            $dt = date('Y-m-d', strtotime($date['enddt']));
            $log_condition .= " AND DATE(start_datetime) = '" . $dt . "' ";
        }

        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $dtm = $view->loadHelper('Datetime');
        $fmt = $view->loadHelper('Format');
        $grpby1 = $grpby = '';

        $groupbyarr = array('date' => 'DATE(LogTime.start_datetime)', 'resource' => 'LogTime.user_id',
            'project' => 'LogTime.project_id', 'task_title' => 'Easycase.id', 'hours' => 'hours', 'is_billable' => 'billable');
        $groupbyarr1 = array('date' => 'DATE(Result.start_datetime)', 'resource' => 'Result.user_id',
            'project' => 'Result.project_id', 'task_title' => 'Result.id', 'hours' => 'Result.hours', 'is_billable' => 'Result.billable');
        $grpby1 = $grpby = 'GROUP BY ';
        $str1 = $str = '';
        foreach ($check as $k => $val) {
            if ($val != 'task_status' && $val != 'hours' && $val != 'task_type' && $val != 'task_group' && $val != 'esthrs') {
                $str .= $groupbyarr[$val] . ',';
                $str1 .= $groupbyarr1[$val] . ',';
            }
        }
        $str = rtrim($str, ',');
        $str1 = rtrim($str1, ',');

        $grpby = (!empty($str)) ? $grpby . $str : '';
        $grpby1 = (!empty($str1)) ? $grpby1 . $str1 : '';

        $usr_cond = '';
        if (SES_TYPE < 3) {
            $usr_cond = "LogTime.user_id >0";
        } elseif (SES_TYPE == 3) {
            $usr_cond = "LogTime.user_id = " . SES_ID;
        }

        $log_sql_inner = "SELECT LogTime.user_id, SUM(LogTime.total_hours) AS hours, GROUP_CONCAT(Distinct LogTime.task_id)  AS esthrs, "
                . "if(LogTime.is_billable=1, 'Yes', 'No') AS billable, User.name, User.last_name, Project.name as pname, Easycase.id, Easycase.title, Easycase.legend, Easycase.type_id, LogTime.start_datetime,LogTime.project_id, Milestone.title AS mlstn_name "
                . "FROM log_times AS LogTime "
                . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                . "LEFT JOIN easycase_milestones AS EasycaseMilestone ON LogTime.task_id = EasycaseMilestone.easycase_id "
                . "LEFT JOIN milestones AS Milestone ON EasycaseMilestone.milestone_id=Milestone.id "
                . "LEFT JOIN users AS User ON LogTime.user_id = User.id "
                . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id "
                . "WHERE Easycase.isactive=1 AND " . $usr_cond . " " . $log_condition . " " . $qry . " " . $search_cond . " AND Project.company_id=" . SES_COMP . " AND Easycase.id IS NOT NULL "
                . "$grpby $sort_cond ";

        $log_sql = "SELECT SQL_CALC_FOUND_ROWS Result.*,sum(p.estimated_hours) as est_hrs FROM ($log_sql_inner) AS Result LEFT JOIN easycases AS p ON find_in_set(p.id,Result.esthrs) "
                . " LEFT JOIN projects pr ON p.project_id = pr.id "
                . " WHERE p.id IS NOT NULL AND Result.id IS NOT NULL AND pr.company_id=" . SES_COMP . " " . $grpby1;

        $logtime = $this->LogTime->query($log_sql);
        $tot_od = $this->LogTime->query("SELECT FOUND_ROWS() as tot_od");
        #pr($logtime);exit;
        $data = array();
        if (is_array($logtime) && count($logtime) > 0) {
            if ($logtime) {
                $legnds = Hash::extract($logtime, '{n}.Result.legend');
                $this->loadModel('Status');
                $legendDetails = $this->Status->find('list', array('conditions' => array('Status.id' => $legnds), 'fields' => array('Status.id', 'Status.name')));
                $legendDetails[4] = 'In Progress';
            }
            foreach ($logtime as $key => $val) {
                //$hour = $this->Format->format_time_hr_min_new($val['0']['hours']);				
                $hour = round(($val['Result']['hours'] / 3600), 2);
                $estimatedHour = round(($val[0]['est_hrs'] / 3600), 2);
                $name = $val['Result']['name'] . " " . $val['Result']['last_name'];
                $caseDtUploaded = $val['Result']['start_datetime'];
                $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtUploaded, "datetime");
                $updatedCur = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "date");
                $displayTime = $dtm->dateFormatOutputdateTime_day($updated, $updatedCur); //Nov 25, Thu at 1:25 pm
                $typeNm = $this->Format->getRequireTypeName($val['Result']['type_id']);
                $legend = $val['Result']['legend'];
                $legendname = $legendDetails[$legend];
                $data[$key]['resource'] = $name;
                $data[$key]['date'] = date('M d, Y', strtotime($updated));
                $data[$key]['hours'] = $hour;
                if (in_array('esthrs', $check)) {
                    $data[$key]['esthrs'] = $estimatedHour;
                }
                $data[$key]['is_billable'] = $val['Result']['billable'];
                if (in_array('project', $check)) {
                    $data[$key]['project'] = addslashes(html_entity_decode($val['Result']['pname']));
                }
                if (in_array('task_title', $check)) {
                    $data[$key]['task_title'] = addslashes(html_entity_decode($val['Result']['title']));
                    $data[$key]['task_group'] = $val['Result']['mlstn_name'] != '' ? html_entity_decode($val['Result']['mlstn_name'], ENT_QUOTES) : 'Without Milestone';
                    $data[$key]['task_status'] = $legendname;
                    $data[$key]['task_type'] = $typeNm;
                }
            }
        }
        #pr($data);exit; 

        $content = '';
        if (in_array('date', $check)) {
            $content = 'Date';
        }
        if (in_array('resource', $check)) {
            if ($content == '')
                $content .= 'Resource';
            else
                $content .= ',Resource';
        }
        if (in_array('project', $check)) {
            if ($content == '')
                $content .= 'Project';
            else
                $content .= ',Project';
        }
        if (in_array('task_title', $check)) {
            if ($content == '')
                $content .= 'Task';
            else
                $content .= ',Task';

            if (in_array('task_group', $check)) {
                $content == '' ? $content .= 'Milestone' : $content .= ',Milestone';
            }
            if (in_array('task_status', $check)) {
                $content == '' ? $content .= 'Status' : $content .= ',Status';
            }
            if (in_array('task_type', $check)) {
                $content == '' ? $content .= 'Task Type' : $content .= ',Task Type';
            }
        }

        if (in_array('hours', $check)) {
            if ($content == '')
                $content .= 'Hour(s) Spent';
            else
                $content .= ',Hour(s) Spent';
        }

        if (in_array('esthrs', $check)) {
            if ($content == '')
                $content .= 'Estimated Hour(s)';
            else
                $content .= ',Estimated Hour(s)';
        }

        if (in_array('is_billable', $check)) {
            if ($content == '')
                $content .= 'Billable';
            else
                $content .= ',Billable';
        }

        $content .= "\n";
        $view = new View($this);
        $fmt = $view->loadHelper('Format');
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $val) {

                if (in_array('date', $check)) {
                    $content .= '"' . str_replace('"', '""', trim($fmt->chngdate($val['date']))) . '",';
                }
                if (in_array('resource', $check)) {
                    $content .= '"' . str_replace('"', '""', trim($val['resource'])) . '",';
                }
                if (in_array('project', $check)) {
                    $content .= '"' . str_replace('"', '""', trim($val['project'])) . '",';
                }
                if (in_array('task_title', $check)) {
                    $content .= '"' . str_replace('"', '""', trim($val['task_title'])) . '",';
                    if (in_array('task_group', $check)) {
                        $content .= '"' . str_replace('"', '""', trim($val['task_group'])) . '",';
                    }
                    if (in_array('task_status', $check)) {
                        $content .= '"' . str_replace('"', '""', trim($val['task_status'])) . '",';
                    }
                    if (in_array('task_type', $check)) {
                        $content .= '"' . str_replace('"', '""', trim($val['task_type'])) . '",';
                    }
                }
                if (in_array('hours', $check)) {
                    $content .= '"' . $val['hours'] . '",';
                }
                if (in_array('esthrs', $check)) {
                    $content .= '"' . $val['esthrs'] . '",';
                }
                if (in_array('is_billable', $check)) {
                    $content .= '"' . $val['is_billable'] . '",';
                }
                $content = trim($content, ',');
                $content .= "\n";
            }
        }
        if (!is_dir(RESOURCE_UTILIZATION_CSV_PATH)) {
            @mkdir(RESOURCE_UTILIZATION_CSV_PATH, 0777, true);
        }

        $name = $this->params->query['projuniqid'];
        if (trim($name) != '' && strlen($name) > 25) {
            $name = substr($name, 0, 24) . "_" . date('m-d-Y', strtotime(GMT_DATE)) . "_resource_utilization.csv";
        } else {
            $name .= (trim($name) != '' ? "_" : '') . date('m-d-Y', strtotime(GMT_DATE)) . "_resource_utilization.csv";
        }
        $download_name = date('m-d-Y', strtotime(GMT_DATE)) . "_resource_utilization.csv";

        $file_path = RESOURCE_UTILIZATION_CSV_PATH . $name;
        $fp = @fopen($file_path, 'w+');
        fwrite($fp, $content);
        fclose($fp);

        $this->response->file($file_path, array('download' => true, 'name' => urlencode($download_name)));
        return $this->response;
    }

    function arcUserFilter($usrid, $type = NULL) {
        $qry = "";
        $qryTyp = "";
        if ($usrid != "all") {
            if (strstr($usrid, "-")) {
                $typArr = explode("-", $usrid);
                foreach ($typArr as $typChk) {
                    if ($type == 'utilization') {
                        $qryTyp .= "LogTime.user_id=" . $typChk . " OR ";
                    } elseif ($type == 'invoice') {
                        $qryTyp .= "LogTime.user_id=" . $typChk . " OR ";
                    } else {
                        $qryTyp .= "Archive.user_id=" . $typChk . " OR ";
                    }
                }
                $qryTyp = substr($qryTyp, 0, -3);
                if ($type != 'invoice') {
                    $qry .= " AND (" . $qryTyp . ")";
                } else {
                    $qry .= " (" . $qryTyp . ")";
                }
            } else {

                if ($type == 'utilization') {
                    $qry .= " AND LogTime.user_id=" . $usrid;
                } elseif ($type == 'invoice') {
                    $qry .= "LogTime.user_id=" . $usrid;
                } else {
                    $qry .= " AND Archive.user_id=" . $usrid;
                }
            }
        }
        return $qry;
    }

    function projectFilter($prjid) {
        $qry = "";
        $qryTyp = "";
        if ($prjid != "all") {
            if (strstr($prjid, "-")) {
                $typArr = explode("-", $prjid);
                //foreach ($typArr as $typChk) {
                if (!empty($typArr)) {
                    $typ = implode(",", $typArr);
                    $qry .= "AND Easycase.project_id IN (" . $typ . ")";
                }
                //}
                //$qryTyp = substr($qryTyp, 0, -3);
                //$qry.=" AND (" . $qryTyp . ")";
            } else {
                $qry .= " AND Easycase.project_id=" . $prjid;
            }
        }
        return $qry;
    }

    function formatprjnm($prjid) {
        $prj = ClassRegistry::init('Project');
        //$prjsname = $prj->find('fisrt', array('conditions'=>array('Project.id'=>$prjid, 'Project.company_id'=>SES_COMP), 'fields'=>array('Project.short_name')));
        $prjsname = $prj->query("SELECT Project.short_name FROM projects AS Project WHERE Project.id=" . $prjid . " AND Project.company_id=" . SES_COMP . "");
        return $prjsname['0']['Project']['short_name'];
    }

    function formatstsname($stsid) {
        $sts = ClassRegistry::init('Status');
        $statusname = $sts->find('first', array('conditions' => array('Status.id' => $stsid))); //("SELECT Status.name FROM statuses AS Status WHERE Status.id=" . $stsid);
        return $statusname['Status']['name'];
    }

    /*
     * Author: Orangescrum
     * For Dashboard Time log graph
     *     */

    function timelog_graph() {
        $this->layout = 'ajax';
        $this->loadModel('Project');
        $this->Project->recursive = -1;
        if (SES_TYPE < 3) {
            $user_id = 'ProjectUser.user_id > 0';
        } else {
            $user_id = 'ProjectUser.user_id=' . SES_ID;
        }
        $projId = $this->data['projid'];
        $projQry = '';
        if ($projId != 'all') {
            $projid = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $projId), 'fields' => array('Project.id')));
            $project_id = $projid['Project']['id'];
            $projQry = " AND LogTime.project_id = $project_id";
        } else {
            $projQry = " AND LogTime.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $user_id . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1') ";
        }
        $dates = $this->Format->date_filter('last30days');
        $days = (strtotime($dates['enddt']) - strtotime($dates['strddt'])) / (60 * 60 * 24);
        $x = floor($days);
        if ($x < 7) {
            $interval = 1;
        } elseif ($x > 80) {
            $interval = ceil($x / 10);
        } else {
            $interval = 7;
        }
        $this->set('tinterval', $interval);
        $dt_arr = array();
        $dts_arr = array();
        for ($i = 0; $i <= $x; $i++) {
            $m = " +" . $i . "day";
            $dt = date('Y-m-d', strtotime(date("Y-m-d", strtotime($dates['strddt'])) . $m));
            $dts = date('M d, Y', strtotime(date("Y-m-d H:i:s", strtotime($dates['strddt'])) . $m));
            $times = explode(" ", GMT_DATETIME);
            array_push($dt_arr, $dt);
            array_push($dts_arr, $dts);
        }
        $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";


        $this->loadModel('LogTime');
        /* find total billable and non-billable time */
        $count_sql = 'SELECT sum(total_hours) as secds,is_billable,DATE(LogTime.start_datetime) AS date '
                . 'FROM log_times AS `LogTime` '
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . 'WHERE is_billable = 1 AND Easycase.isactive =1 ' . $projQry . ' ' . $dateCond . '  '
                . 'GROUP BY DATE(LogTime.start_datetime)  '
                . 'UNION '
                . 'SELECT sum(total_hours) as secds, is_billable,DATE(LogTime.start_datetime) AS date '
                . 'FROM log_times AS LogTime '
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . 'WHERE is_billable = 0 AND Easycase.isactive =1 ' . $projQry . ' ' . $dateCond . '  '
                . 'GROUP BY DATE(LogTime.start_datetime) ';
        #echo $count_sql;exit;
        $cntlog = $this->LogTime->query($count_sql);

        if (is_array($cntlog) && count($cntlog) > 0) {
            $billablearr = array();
            $nonbillablearr = array();
            foreach ($cntlog as $k => $val) {
                if ($val[0]['is_billable'] == 1) {
                    $billablearr[$val[0]['date']] = $val[0];
                } else {
                    $nonbillablearr[$val[0]['date']] = $val[0];
                }
            }
            foreach ($dt_arr as $key => $dt) {
                $nonbillable_series['name'] = 'Non-billable';
                $nonbillable_series['color'] = '#C5C5C5';
                if (array_key_exists($dt, $nonbillablearr)) {
                    $nonbillable_series['data'][] = ($nonbillablearr[$dt]['secds'] / 3600);
                } else {
                    $nonbillable_series['data'][] = 0;
                }
                $billable_series['name'] = 'Billable';
                $billable_series['color'] = '#00A2FF';
                if (array_key_exists($dt, $billablearr)) {
                    $billable_series['data'][] = ($billablearr[$dt]['secds'] / 3600);
                } else {
                    $billable_series['data'][] = 0;
                }
            }
            $series[0] = $nonbillable_series;
            $series[1] = $billable_series;

            $this->set('dt_arr', json_encode($dts_arr));
            $this->set('series', json_encode($series));
        } else {
            echo "<img src='" . HTTP_ROOT . "img/dbord_timelog.jpg' height='285px' width='98%'>";
            exit;
        }
    }

    /* BY:CHP
     * invoice page for user
     */

    function ajaxUserPaymentPage() {
        $this->layout = 'ajax';
        $this->loadModel('Payment');
        $this->loadModel('User');

        $project_id = $GLOBALS['getallproj'][0]['Project']['id'];
        $id = $this->request['data']['v'];
        $log_id = $this->request['data']['log'];
        $payment_user_id = $this->request['data']['usr_id'];
        $payment = array();
        $Company = ClassRegistry::init('Company');
        $Company->recursive = -1;
        $getCompany = $Company->find('first', array('conditions' => array('Company.id' => SES_COMP), 'fields' => array('name', 'logo', 'id')));
        if ($id > 0) {
            /* invoice details */
            $this->Payment->bindModel(
                    array('hasMany' => array(
                            'PaymentLog' => array(
                                'className' => 'PaymentLog',
                                'dependent' => true,
                                'order' => 'created ASC'
                            )
                        )
            ));
            $payment = $this->Payment->findById($id);
            $this->Payment->unbindModel(array('hasMany' => array('PaymentLog')));
        } else {
            /* company name */
            $payment['Payment']['company_name'] = $getCompany['Company']['name'];
            $payment['Payment']['payment_from'] = $getCompany['Company']['name'];
            $this->set('company_name', $getCompany['Company']['name']);
        }
        /*
          $this->Payment->recursive = false;
          $lastpayment = $this->Payment->find('first', array('conditions' => array('company_id' => SES_COMP, "payment_from!=''"), 'order' => 'id desc'));
          $payment['Payment']['payment_from'] = $lastpayment['Payment']['payment_from'];
          } */
        /* company name */

        /* user details */
        $fromEmail = $this->User->findById(SES_ID);
        $this->set('email', $fromEmail['User']['email']);


        /* payment user detail */
        $payment_user = $this->User->findById($payment_user_id);
        $this->set('userdata', $payment_user['User']);
        /* selected timelogs details */
        $logdata = array();
        if (trim($log_id) != '') {
            $payment['Payment']['issue_date'] = date('Y-m-d H:i:s');
            $payment['Payment']['due_date'] = date('Y-m-d H:i:s');
            if (trim($log_id) != 'new') {
                $this->loadModel('LogTime');
                $logdata = $this->LogTime->find('all', array(
                    'conditions' => array("log_id IN ($log_id)"),
                    'fields' => array("LogTime.*", "Easycase.title"),
                    'joins' => array(array('table' => 'easycases', 'alias' => 'Easycase', 'type' => 'LEFT', 'conditions' => array('Easycase.id = LogTime.task_id')))
                        )
                );
                if (is_array($logdata) && count($logdata) > 0) {
                    foreach ($logdata as $key => $val) {
                        $logdata[$key]["LogTime"]['start_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logdata[$key]["LogTime"]['start_datetime'], "datetime");
                        $logdata[$key]["LogTime"]['end_datetime'] = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $logdata[$key]["LogTime"]['end_datetime'], "datetime");
                        $logdata[$key][0]['start_datetime_v1'] = date('M d Y H:i:s', strtotime($logdata[$key]["LogTime"]['start_datetime']));

                        $logdata[$key]['LogTime']['start_time'] = date('H:i:s', strtotime($logdata[$key]['LogTime']['start_datetime']));
                        $logdata[$key]['LogTime']['end_time'] = date('H:i:s', strtotime($logdata[$key]['LogTime']['end_datetime']));
                        $description = strip_tags($logdata[$key]['LogTime']['description']);
                        $logdata[$key]['LogTime']['description'] = trim($description) != '' ? $description : $logdata[$key]['Easycase']['title'];
                    }
                }
                $payment['log'] = $logdata;
            } else {
                $payment['log'] = array(array());
            }
        }

        $this->set('company', $getCompany);
        $this->set('i', $payment);
        //echo "<pre>";print_r($payment);exit;
        if (SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) {
            $this->loadModel('PaymentActivity');
            $this->PaymentActivity->bindModel(
                    array('belongsTo' => array(
                            'User' => array(
                                'className' => 'User',
                                'foreignKey' => 'user_id',
                            )
                        )
                    )
            );
            $params = array();
            $params['conditions'] = array('payment_id' => $id);
            $params['fields'] = array('PaymentActivity.*', 'User.name', 'User.last_name');
            $log = $this->PaymentActivity->find('all', $params);
            $this->set('activity', $log);
        } else {
            //   $this->render('ajax_invoice_page_view');
        }
    }

    /* save payments */

    function save_payment() {
        $this->loadModel('Payment');
        $this->loadModel('PaymentLog');
        $this->loadModel('LogTime');
        // echo "<pre>";print_r($this->data);exit;
        if (intval(SES_COMP) == 0) {
            exit;
        }
        $view = new View($this);
        $format_helper = $view->loadHelper('Format');

        $data = $this->data;
        $is_modified = trim($data['ismodified']);
        $payment_id = intval($data['payment_id']);

        if ($payment_id > 0) {

            $this->Payment->recursive = false;
            $invoice_old_data = $this->Payment->findById($payment_id);
        }
        $payment_no = trim($data['edit_paymentNo']);
        $mode = $payment_id > 0 ? "edit" : "add";
        $error = false;
        $exist_condition = array();

        if ($payment_no == '') {# && $mode == 'edit'
            $msg = 'Please enter payment number.';
            $error = true;
        } elseif ($payment_no != '') {
            /* checking if invoice number already exist */
            $this->Payment->recursive = false;
            $exist_condition[] = "payment_no='" . $payment_no . "'";
            $exist_condition[] = "company_id='" . SES_COMP . "'";
            if ($payment_id > 0) {
                $exist_condition[] = "id!=$payment_id";
            }
            $exists = $this->Payment->find('all', array('conditions' => $exist_condition));
            if (is_array($exists) && count($exists)) {
                $msg = 'Payment number exist. Please enter another payment number.';
                $error = true;
            }
        }
        if ($error == true) {
            echo json_encode(array('success' => 'No', 'msg' => $msg));
            exit;
        }
        $payment = array(
            'id' => $payment_id,
            'user_id' => SES_ID,
            'project_id' => $GLOBALS['getallproj'][0]['Project']['id'],
            'company_id' => SES_COMP,
            'payment_no' => $payment_no,
            'issue_date' => trim($data['edit_issue_date']) != '' ? date('Y-m-d H:i:s', strtotime($data['edit_issue_date'])) : '',
            // 'due_date' => trim($data['edit_due_date']) != '' ? date('Y-m-d H:i:s', strtotime($data['edit_due_date'])) : '',
            'note' => trim($data['edit_notes']),
            'terms' => trim($data['edit_terms']),
            'payment_from' => trim($data['edit_payment_from']),
            'payment_to' => trim($data['edit_payment_to']),
            'tax' => trim($data['edit_tax']),
            'discount' => trim($data['edit_discount']),
            'discount_type' => trim($data['payment_discount_type']),
            'payee_id' => intval($data['payment_customer_id']),
            'currency' => trim($data['payment_customer_currency']) ? $data['payment_customer_currency'] : 'USD',
            'logo' => trim($data['logophoto']),
            'hourly_rate' => $data['hourly_rate'],
            'modified' => date('Y-m-d H:i:s'),
        );
        if ($mode == 'edit') {
            
        } else {
            $payment['uniq_id'] = $this->Format->generateUniqNumber();
            $payment['ip'] = $this->request->clientIp();
            $payment['created'] = date('Y-m-d H:i:s');
        }
        $this->Payment->save($payment);
        $payment_id = $this->Payment->id;
        $subTotal = 0;

        if (is_array($data['edit_log_date']) && count($data['edit_log_date']) > 0 && $payment_id > 0) {
            $payment_log_ids = $mode == 'edit' && isset($data['payment_log_id']) ? $data['payment_log_id'] : array();

            $i = 0;
            foreach ($data['edit_log_date'] as $key => $val) {
                $index = $key;
                $log_id = intval($payment_log_ids[$index]) > 0 ? $payment_log_ids[$index] : '';

                if (trim($data['rate_edit_total_hours'][$index]) != '' || trim($data['edit_total_hours'][$index]) != '' || trim($data['edit_description'][$index]) != '' || trim($data['edit_log_date'][$index]) != '') {
                    $logs[$i] = array(
                        'id' => $log_id,
                        'payment_id' => $payment_id,
                        'user_id' => SES_ID,
                        'rate' => trim(preg_replace('/[^0-9.]/', '', $data['rate_edit_total_hours'][$index])),
                        'total_hours' => trim($data['edit_total_hours'][$index]),
                        'description' => trim($data['edit_description'][$index]),
                        'task_date' => trim($data['edit_log_date'][$index]) != '' ? date('Y-m-d H:i:s', strtotime(trim($data['edit_log_date'][$index]))) : '',
                    );
                    $timelog_id = isset($data['edit_log_id'][$index]) ? $data['edit_log_id'][$index] : 0;
                    if ($timelog_id > 0) {
                        $timelog_data = $this->LogTime->findByLogId($timelog_id);
                        $logs[$i] = array_merge($logs[$i], array(
                            'log_id' => $timelog_data['LogTime']['log_id'],
                            'start_time' => $timelog_data['LogTime']['start_time'],
                            'end_time' => $timelog_data['LogTime']['end_time'],
                            'task_status' => $timelog_data['LogTime']['task_status'],
                            'start_datetime' => $timelog_data['LogTime']['start_datetime'],
                            'end_datetime' => $timelog_data['LogTime']['end_datetime'],
                        ));
                    }

                    if ($log_id > 0) {
                        $logs[$i]['modified'] = date('Y-m-d H:i:s');
                    } else {
                        $logs[$i]['created'] = date('Y-m-d H:i:s');
                        $logs[$i]['ip'] = $this->request->clientIp();
                    }
                    $subTotal += floatval($logs[$i]['rate']) * floatval($logs[$i]['total_hours']);
                    $i++;
                }
            }
            if (is_array($logs) && count($logs) > 0) {
                $this->PaymentLog->saveAll($logs);
            }
        }
        $paymentDiscount = $this->Format->format_price($payment['discount_type'] != 'Flat' ? (($subTotal * floatval($payment['discount'])) / 100) : floatval($payment['discount']));
        $paymentTax = $this->Format->format_price((($subTotal - $paymentDiscount) * floatval($payment['tax'])) / 100);
        $totalPrice = $this->Format->format_price(($subTotal - $paymentDiscount + $paymentTax));

        $update_data = array('price' => floatval($totalPrice));
        if ($mode == 'add' && $payment_no == '') {
            
        }
        /* update invoice price */
        $this->Payment->id = $payment_id;
        $this->Payment->save($update_data);
        /* end */
        if ($mode == 'add') {
            $this->activity_log('create', $payment_id, true);
        } elseif ($mode == 'edit' && $is_modified == 'Yes') {
            $this->activity_log('modify', $payment_id, true);
        }

        /* save invoice image */
        $filename = trim($data['logophoto']);
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $s3->putBucket(BUCKET_NAME, S3::ACL_PRIVATE);
        $source = DIR_PHOTOS_S3_TEMP_FOLDER;

        if ($invoice_old_data['Payment']['logo'] != $filename) {
            $destination = DIR_INVOICE_PHOTOS_S3_FOLDER . SES_COMP . '/';
            $ret_res = $s3->copyObject(BUCKET_NAME, $source . $filename, BUCKET_NAME, $destination . $filename, S3::ACL_PRIVATE);
        }
        /* save image to company table if company table is empty */
        $this->loadModel('Company');
        $company = $this->Company->find('all', array('conditions' => array('id' => SES_COMP), 'fields' => array('id', 'logo')));
        $logo = trim($company[0]['Company']['logo']);
        if ($logo == '' || ($logo != '' && !$format_helper->pub_file_exists(DIR_COMPANY_PHOTOS_S3_FOLDER . SES_COMP . '/', $logo))) {
            /* saving to s3 company logo */
            $destination = DIR_COMPANY_PHOTOS_S3_FOLDER . SES_COMP . '/';
            $ret_res = $s3->copyObject(BUCKET_NAME, $source . $filename, BUCKET_NAME, $destination . $filename, S3::ACL_PRIVATE);
            $this->Company->save(array('id' => SES_COMP, 'logo' => $filename));
        }
        /* end */

        echo json_encode(array('success' => 'Yes', 'id' => $payment_id));
        exit;
    }

    function activity_log($act = '', $payment_id = '', $flag = false) {
        $this->loadModel('PaymentActivity');
        $this->loadModel('Payment');
        #$this->loadModel('InvoiceLog');
        if ($flag == true) {
            $lastpayment = $this->Payment->find('first', array('conditions' => array('company_id' => SES_COMP, "id" => $payment_id)));
        }

        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $prjuniqueid = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        $data = array(
            "id" => "",
            "company_id" => SES_COMP,
            "project_id" => $prjid,
            "payment_id" => $payment_id,
            "user_id" => SES_ID,
            "created" => date('Y-m-d H:i:s'),
            "ip" => $this->request->clientIp(),
            "activity" => $act,
            "payment" => $flag == true ? base64_encode(json_encode($lastinvoice)) : '',
        );
        $this->PaymentActivity->save($data);
        return $this->PaymentActivity->id;
    }

    /* sending invoice email */

    function sendInvoiceEmail() {
        $this->loadModel('Payment');
        parse_str($this->request['data']['v'], $d);

        $this->loadModel('Payment');
        $id = $d['data']['paymentId'];
        /* generate pdf */
        $this->createInvoicePdf($id);

        /* inovoice details */
        $i = $this->Payment->findById($id);

        /* add activity log */
        $this->activity_log('email', $id);

        $subject = $d['data']['subject'];

        $this->Email->delivery = 'smtp';
        $this->Email->to = $d['data']['to'];

        $this->Email->subject = $subject;
        $this->Email->from = $d['data']['from'];
        $this->Email->template = 'payment_email';
        $this->Email->sendAs = 'html';

        $id = $i["Payment"]["id"];
        $payment_no = $this->Format->seo_url($i["Payment"]["payment_no"], '_');
        $f = HTTP_PAYMENT . 'payment_' . $payment_no . '.pdf';
        $f_path = HTTP_PAYMENT_PATH . 'payment_' . $payment_no . '.pdf';
        $this->Email->attachments = array($f_path);

        $this->set('f', $f);
        $this->set('i', $i);
        $this->set('message', $d['data']);
        if ($this->Sendgrid->sendgridsmtp($this->Email)) {
            echo "success";
            @unlink($f_path);
        } else {
            echo "unsuccess";
        }
        exit;
    }

    /* Adding other timelog to payment or update of payment
     */

    function addToPayment() {
        $this->layout = 'ajax';
        $this->loadModel('PaymentLog');
        $this->loadModel('Payment');
        $this->loadModel('LogTime');

        $paymentId = $this->request['data']['payment'];
        $price = $this->Payment->findById($paymentId);

        $arr = explode(',', $this->request['data']['log']);

        foreach ($arr as $k => $v) {
            $log = $this->LogTime->findByLogId($v);
            $paymentLog['user_id'] = SES_ID;
            $paymentLog['payment_id'] = $this->request['data']['payment'];
            $paymentLog['log_id'] = $log['LogTime']['log_id'];
            $paymentLog['task_date'] = $log['LogTime']['task_date'];
            $paymentLog['start_time'] = $log['LogTime']['start_time'];
            $paymentLog['end_time'] = $log['LogTime']['end_time'];
            $paymentLog['total_hours'] = round($log['LogTime']['total_hours'] / 3600, 2);
            $paymentLog['description'] = $log['LogTime']['description'];
            $paymentLog['task_status'] = $log['LogTime']['task_status'];
            $paymentLog['rate'] = NULL;
            $paymentLog['ip'] = $this->request->clientIp();
            $paymentLog['start_datetime'] = $log['LogTime']['start_datetime'];
            $paymentLog['end_datetime'] = $log['LogTime']['end_datetime'];

            $this->PaymentLog->save($paymentLog);
            $this->PaymentLog->id = '';
            $this->LogTime->query('update log_times set task_status=1 where log_id=' . $log['LogTime']['log_id']);
        }
        exit;
    }

    /*
     * Delete the time logs of saved payment
     */

    function deletePaymentTimelog() {
        $this->loadModel('PaymentLog');
        $id = $this->request['data']['v'];
        $this->PaymentLog->id = $id;
        $log_id = $this->PaymentLog->field('log_id');
        if ($log_id > 0) {
            $this->loadModel('LogTime');
            $this->LogTime->query('update log_times set task_status=0 where log_id=' . $log_id);
        }
        if ($this->PaymentLog->delete($id))
            echo 1;
        else
            echo 0;
        exit;
    }

    /* creating pdf of payment */

    function createPaymentPdf($payment_id = '0') {
        $id = $payment_id > 0 ? $payment_id : $this->request['data']['v'];
        $this->loadModel('Payment');

        $i = $this->Payment->findById($id);
        //   $settings = $this->InvoiceSetting->find('first', array('conditions' => array('company_id' => SES_COMP)));

        $payment_no = $this->Format->seo_url($i["Payment"]["payment_no"], '_');

        $payment_dir = WWW_ROOT . 'user_payment';
        $filename = WWW_ROOT . 'user_payment/payment_' . $payment_no . '.pdf';
        if (!is_dir($payment_dir)) {
            mkdir($payment_dir);
            chown($payment_dir, 'apache');
            $this->Format->recursiveChmod($payment_dir);
        }
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $orientation = '';
        $layout = 'portrait';
        /*  if (is_array($settings) && count($settings) > 0 && $settings['InvoiceSetting']['layout'] == 'landscape') {
          $layout = $settings['InvoiceSetting']['layout'];
          $orientation = " -O landscape ";
          } */
        //echo PDF_LIB_PATH . $orientation . " " . HTTP_ROOT_INVOICE . "easycases/invoicePdf/" . $id . '/' . SES_COMP . '/' . $layout . " " . $filename;
        //  echo PDF_LIB_PATH . $orientation . " " . HTTP_ROOT_INVOICE . "easycases/paymentPdf/" . $id . '/' . SES_COMP . '/' . $layout . " " . $filename ;exit;
        if (defined('USE_WKHTMLTOPDF') && USE_WKHTMLTOPDF == 1) {
            if (exec(PDF_LIB_PATH . $orientation . " " . HTTP_ROOT_INVOICE . "easycases/paymentPdf/" . $id . '/' . SES_COMP . '/' . $layout . " " . $filename)) {
                $this->Format->recursiveChmod($payment_dir);
            } else {
                
            }
        } else {
            /*             * *Create pdf by using tcpdf** */
            $this->Mpdf = $this->Components->load('Mpdf');
            $this->Mpdf->init();
            $this->Mpdf->setFilename($filename);
            $this->Mpdf->customOutput('F', $this->requestAction('easycases/paymentPdf/' . $id . '/' . SES_COMP . '/' . $layout, array('return')));
            /*             * *End* */
        }

        if ($payment_id > 0) {
            return true;
        } else {
            print $id;
            exit;
        }
        exit;
    }

    function downloadPdf() {
        $this->loadModel('Payment');

        $id = $this->request->params['pass'][0];
        $mode = $this->request->params['pass'][1];
        $i = $this->Payment->findById($id);
        $payment_no = $this->Format->seo_url($i['Payment']['payment_no'], '_');

        /* insert activity log */
        if ($mode != 'preview') {
            $this->activity_log('download', $id);
        }

        $file = WWW_ROOT . 'user_payment' . DS . 'payment_' . $payment_no . '.pdf';
        if (file_exists($file)) {
            $this->response->file($file, array('download' => $mode == 'preview' ? false : true, 'name' => urlencode(basename($file))));
            return $this->response;
        } else {
            echo __("file not found: ") . $file;
        }
        exit;
    }

    function ajaxPaymentList() {
        $this->layout = 'ajax';
        $prjid = array();
        $this->loadModel('Payment');
        $this->loadModel('Project');
        if ($_COOKIE['All_Project'] && ($_COOKIE['All_Project'] == 'all')) {
            $projArr = $this->Project->find('all', array('conditions' => array('Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
            foreach ($projArr as $k => $pro) {
                $prjid[] = $pro['Project']['id'];
            }
        } else {
            $prjid[] = $GLOBALS['getallproj'][0]['Project']['id'];
        }
        $order_by = 'created';
        $order_sort = 'DESC';
        if (isset($this->data['params'])) {
            $sort_by = (isset($this->data['params']['sortby'])) ? trim($this->data['params']['sortby']) : '';
            $order_sort = (isset($this->data['params']['order'])) ? trim($this->data['params']['order']) : ' ASC';
            switch ($sort_by) {
                case"payment":$order_by = 'payment_no';
                    break;
                case"payment_date":$order_by = 'issue_date';
                    break;
                case"amount":$order_by = 'price';
                    break;
            }
        }
        $page_limit = CASE_PAGE_LIMIT;
        $page = 1;

        if (isset($this->data['casePage']) && $this->data['casePage']) {
            $page = $this->data['casePage'];
        }
        $limit1 = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;
        $conditions = array('Payment.project_id' => $prjid, 'Payment.is_active' => 1);
        if ((SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) && ($this->Auth->user('is_client') != 1)) {
            
        } else {
            $conditions[] = "User.id='" . SES_ID . "'";
        }
        $options = array();
        $options['conditions'] = $conditions;
        $options['recursive'] = false;
        $options['joins'] = array(
            array('table' => 'users', 'alias' => 'User', 'type' => 'LEFT', 'conditions' => array('User.id = Payment.payee_id'))
        );
        //echo "<pre>";print_r($options);exit;
        $caseCount = $this->Payment->find('count', $options);


        $options['fields'] = array('Payment.*', "User.name AS payee_name");
        $options['order'] = $order_by . ' ' . $order_sort;
        $options['limit'] = $limit2;
        $options['offset'] = $limit1;

        $inv = $this->Payment->find('all', $options);
        $this->set('inv', $inv);
        $this->set('caseCount', $caseCount);
        $this->set('page_limit', $page_limit);
        $this->set('page', $page);
        $this->set('casePage', $page);
        $this->set('order_by', $sort_by);
        $this->set('order_sort', $order_sort);
    }

    /* By CP
     * to delete invoices
     */

    function deleteInvoice() {
        $this->loadModel('Payment');
        $this->loadModel('PaymentLog');
        $this->loadModel('LogTime');

        $id = $this->request['data']['v'];
        $this->Payment->id = $id;
        $org_Img = $this->Payment->field('logo');
        @unlink(WWW_ROOT . "invoice-logo/" . $org_Img);

        $PaymentLog = $this->$PaymentLog->find('all', array('fields' => array('log_id'), 'conditions' => array('payment_id' => $id)));
        if (is_array($PaymentLog) && count($PaymentLog) > 0) {
            foreach ($PaymentLog as $k => $v) {
                $this->LogTime->query("update log_times set task_status=0 where log_id='" . $v['PaymentLog']['log_id'] . "'");
            }
            $this->PaymentLog->delete("payment_id='" . $id . "'");
        }
        echo ($this->Payment->delete($id)) ? 1 : 0;
        exit;
    }

    /* to get the customer details */

    function customer_details() {
        $this->loadModel('User');
        $id = $this->data['id'];
        $customers = $this->User->findById($id);
        echo json_encode($customers);
        exit;
    }

    /* to mark the payment paid */

    function markPaymentPaid() {
        $this->loadModel('Payment');
        $paymentId = $this->request->data['id'];

        $payment['Payment']['id'] = $this->request->data['id'];
        $payment['Payment']['is_paid'] = 1;
        $payment['Payment']['receive_date'] = GMT_DATETIME;
        if ($this->Payment->save($payment)) {
            if ($this->activity_log('paid', $paymentId, true)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    function markPaymentUnpaid() {
        $this->loadModel('Payment');
        $paymentId = $this->request->data['id'];
        $payment['Payment']['id'] = $this->request->data['id'];
        $payment['Payment']['is_paid'] = 0;
        $payment['Payment']['receive_date'] = "";
        if ($this->Payment->save($payment)) {
            if ($this->activity_log('paid', $paymentId, true)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    /* create payment Invoice */

    function createInvoicePdf($payment_id = '0') {

        $id = $payment_id > 0 ? $payment_id : $this->request['data']['v'];
        $this->loadModel('Payment');
        // $this->loadModel('InvoiceSetting');

        $i = $this->Payment->findById($id);
        // $settings = $this->InvoiceSetting->find('first', array('conditions' => array('company_id' => SES_COMP)));

        $payment_no = $this->Format->seo_url($i["Payment"]["payment_no"], '_');

        $invoice_dir = WWW_ROOT . 'user_payment';
        $filename = WWW_ROOT . 'user_payment/payment_' . $payment_no . '.pdf';
        if (!is_dir($invoice_dir)) {
            mkdir($invoice_dir);
            chown($invoice_dir, 'apache');
            $this->Format->recursiveChmod($invoice_dir);
        }
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $orientation = '';
        $layout = 'portrait';
        /*  if (is_array($settings) && count($settings) > 0 && $settings['InvoiceSetting']['layout'] == 'landscape') {
          $layout = $settings['InvoiceSetting']['layout'];
          $orientation = " -O landscape ";
          } */
#echo PDF_LIB_PATH . $orientation . " " . HTTP_ROOT_INVOICE . "easycases/invoicePdf/" . $id . '/' . SES_COMP . '/' . $layout . " " . $filename;
        if (defined('USE_WKHTMLTOPDF') && USE_WKHTMLTOPDF == 1) {
            if (exec(PDF_LIB_PATH . $orientation . " " . HTTP_ROOT_INVOICE . "easycases/paymentPdf/" . $id . '/' . SES_COMP . '/' . $layout . " " . $filename)) {
                $this->Format->recursiveChmod($invoice_dir);
            } else {
                
            }
        } else {

            /*             * *Create pdf by using tcpdf** */
            $this->Mpdf = $this->Components->load('Mpdf');
            $this->Mpdf->init();
            $this->Mpdf->setFilename($filename);
            $this->Mpdf->customOutput('F', $this->requestAction('easycases/paymentPdf/' . $id . '/' . SES_COMP . '/' . $layout, array('return')));
            /*             * *End* */
        }

        if ($payment_id > 0) {
            return true;
        } else {
            print $id;
            exit;
        }
        exit;
    }

    /* delete payments */

    function deletepayment() {
        $this->loadModel('Payment');
        $this->loadModel('PaymentLog');
        $this->loadModel('LogTime');

        $id = $this->request['data']['v'];
        $this->Payment->id = $id;
        $org_Img = $this->Payment->field('logo');
        @unlink(WWW_ROOT . "invoice-logo/" . $org_Img);

        $paymentLog = $this->PaymentLog->find('all', array('fields' => array('log_id'), 'conditions' => array('payment_id' => $id)));
        //echo "<pre>";print_r($paymentLog);exit;
        if (is_array($paymentLog) && count($paymentLog) > 0) {
            foreach ($paymentLog as $k => $v) {
                $this->LogTime->query("update log_times set task_status=0 where log_id='" . $v['PaymentLog']['log_id'] . "'");
            }
            $this->PaymentLog->delete("payment_id='" . $id . "'");
        }
        echo ($this->Payment->delete($id)) ? 1 : 0;
        exit;
    }

    function getPaymentCount() {
        if (isset($this->data['prjId'])) {
            $this->loadModel('Project');
            if (trim($this->data['prjId']) == 'all') {
                $allprj = $this->Project->getCompAllPrjIds();
                if (!$allprj) {
                    $prjid = 0;
                } else {
                    $prjid = $allprj;
                }
            } else {
                $prjid = $this->Project->getPrjIdFromUniqid(trim($this->data['prjId']));
            }
        } else {
            $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        }
        if ($prjid) {
            //print_r($prjid);exit;
            $this->loadModel('Payment');
            $this->loadModel('LogTime');
            /* payment count */
            $options = array();
            $pymntconditions = array('Payment.project_id' => $prjid, 'Payment.is_active' => 1);
            if ((SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) && ($this->Auth->user('is_client') != 1)) {
                
            } else {
                $pymntconditions[] = "User.id='" . SES_ID . "'";
            }
            $options['conditions'] = $pymntconditions;
            $options['recursive'] = false;
            $options['joins'] = array(array('table' => 'users', 'alias' => 'User', 'type' => 'LEFT', 'conditions' => array('User.id = Payment.payee_id')));
            $payment = $this->Payment->find('count', $options);
            /* unbilled logtime count */
            if ($_COOKIE['datelog']) {
                $date_flt = $_COOKIE['datelog'];
            } else {
                if ($_COOKIE['flt_typ'] == 'date') {
                    $date_flt = $_COOKIE['flt_val'] . ":" . $_COOKIE['flt_val'];
                }
            }
            $view = new View($this);
            $tz = $view->loadHelper('Tmzone');
            $curDateTime = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            $dateFilter = isset($this->request->data['datelog']) && $this->request->data['datelog'] != '' ? $this->request->data['datelog'] : $date_flt;
            if (strpos($dateFilter, ':')) {
                $dt = explode(':', $dateFilter);
                $date['strddt'] = $dt[0];
                $date['enddt'] = $dt[1];
            } else {
                $date = $this->Format->date_filter($dateFilter, $curDateTime);
            }
            //echo "<pre>";print_r($date);exit;
            if ($this->request->data['usrid']) {
                $usrid = $this->request->data['usrid'];
            } else {
                if ($_COOKIE['flt_typ'] == 'user') {
                    $usrid = $_COOKIE['flt_val'];
                } else {
                    $usrid = $_COOKIE['rsrclog'];
                }
                if (strpos($usrid, '-')) {
                    $usrid = explode('-', $usrid);
                }
            }
            $where = "`LogTime`.`project_id` IN(" . implode(',', $prjid) . ")";
            if ($date['strddt'] && $date['enddt']) {
                $where .= " AND `LogTime`.`task_date` BETWEEN '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND '" . date('Y-m-d', strtotime($date['enddt'])) . "'";
            } elseif ($date['strddt']) {
                $where .= " AND `LogTime`.`task_date` >= '" . date('Y-m-d', strtotime($date['strddt'])) . "'";
            } elseif ($date['enddt']) {
                $where .= " AND `LogTime`.`task_date` <= '" . date('Y-m-d', strtotime($date['enddt'])) . "'";
            }
            if ($usrid) {
                if (is_array($usrid)) {
                    $usrin = rtrim(implode(',', $usrid), ',');
                    $where .= " AND `LogTime`.`user_id` IN (" . $usrin . ") ";
                } else {
                    $where .= " AND LogTime.user_id = '" . $usrid . "'";
                }
            }
            if (SES_TYPE == 3) {
                $where .= " AND `LogTime`.`user_id`=" . SES_ID;
            }
            //for users
            if ($_COOKIE['flt_typ'] == 'task') {
                $where .= " AND `LogTime`.`task_id` =" . $_COOKIE['flt_val'];
            }
            $logtimes = $this->LogTime->query("SELECT COUNT(LogTime.log_id) as count FROM log_times as LogTime WHERE " . $where);
            //print_r($logtimes);exit;
            $count = null;
            $count['payment'] = $payment;
            $count['logtime'] = $logtimes[0][0]['count'];
            print json_encode($count);
            exit;
        } else {
            print json_encode(array('payment' => 0, 'logtime' => 0));
            exit;
        }
    }

    /*
     * Author Satyajeet
     * This function is used to generate a chart view of time log
     */

    function showChartView() {
        $this->layout = 'ajax';
        $this->loadModel('Easycase');
        $this->loadModel('LogTime');
        $this->loadModel('Project');
        /* Set the date */
        if (isset($this->data['currentdate']) && !empty($this->data['currentdate'])) {
            $date = strtotime($this->data['currentdate']);
        } else {
            $date = strtotime(date("Y-m-d"));
        }
        $day = date('d', $date);
        $month = date('m', $date);
        $year = date('Y', $date);
        $nextMonth = date('Y-m-d', strtotime('+1 month', $date));
        $prevMonth = date('Y-m-d', strtotime('-1 month', $date));
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $title = strftime('%B', $firstDay);
        $dayOfWeek = date('D', $firstDay);
        $daysInMonth = cal_days_in_month(0, $month, $year);
        $this->set(compact('day', 'month', 'year', 'title', 'daysInMonth', 'nextMonth', 'prevMonth'));
        /*         * *End *** */
        $project_id = array();
        $projFil = $this->request->data['params']['projFil'] ? $this->request->data['params']['projFil'] : $this->request->data['projFil'];
        if ($projFil == "0") {
            $projFil = "all";
        }
        if ($_COOKIE['All_Project'] && ($_COOKIE['All_Project'] == 'all')) {
            $projFil = "all";
        } else {
            $project_id[] = isset($GLOBALS['curProjId']) && !empty($GLOBALS['curProjId']) ? $GLOBALS['curProjId'] : $GLOBALS['getallproj'][0]['Project']['id'];
            $prjuniqueid = isset($GLOBALS['projUniq']) && !empty($GLOBALS['projUniq']) ? $GLOBALS['projUniq'] : $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        }
        #pr($this->data);exit;
        $projFil = $this->data['projFil'];
        $filter = $this->data['filter'];
        $data = $this->data;
        $usid = '';
        $st_dt = '';
        $where = '';
        /* updating latest project id for user */
        if ($data['projFil'] && !(isset($data['usrid']) || isset($data['strddt']) || isset($data['enddt']) || isset($data['filter']))) {
            if ($prjuniqueid != $projFil) {
                $this->loadModel('Project');
                $projid = $this->Project->find('first', array('fields' => array('Project.id'), 'conditions' => array('Project.uniq_id' => $projFil)));
                $prjid = $projid['Project']['id'];
            }
            $this->loadModel('ProjectUser');
            $this->ProjectUser->recursive = -1;
            $this->ProjectUser->updateAll(array('ProjectUser.dt_visited' => "'" . GMT_DATETIME . "'"), array('ProjectUser.project_id' => $prjid, 'ProjectUser.user_id' => $_SESSION['Auth']['User']['id']));
            $timelog_filter_msg = "";
        } else {
            if (trim($data['usrid']) != '' || trim($data['strddt']) != '' || trim($data['enddt']) != '' || (trim($data['filter']) != '' && trim($data['filter']) != 'alldates')) {
                $timelog_filter_msg = "Showing data ";
            }
        }

        /* page limit set */
        $page_limit = 30;
        /* current page */
        $casePage = $this->params['data']['casePage'] > 0 ? $this->params['data']['casePage'] : 1; // Pagination
        $page = $casePage;
        $limit1 = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;

        /* project details */
        if (isset($projFil) && !empty($projFil)) {
            $this->Project->recursive = -1;
            if ($projFil != 'all') {
                $projArr = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $projFil, 'Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
                unset($project_id);
                $project_id[] = $projArr['Project']['id'];
            } else {
                $projArr = $this->Project->find('all', array('conditions' => array('Project.isactive' => 1, 'Project.company_id' => SES_COMP), 'fields' => array('Project.id')));
                foreach ($projArr as $k => $pro) {
                    $project_id[] = $pro['Project']['id'];
                }
            }
        }

        $curDateTime = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        if (isset($this->data['currentdate']) && !empty($this->data['currentdate'])) {
            $usrCndtn = " USERS.id IS NOT NULL AND LogTime.start_datetime >= (LAST_DAY('" . $this->data['currentdate'] . "') + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND LogTime.start_datetime <  (LAST_DAY('" . $this->data['currentdate'] . "') + INTERVAL 1 DAY) AND ";
            $tskCndtn = " Easycase.id IS NOT NULL AND LogTime.start_datetime >= (LAST_DAY('" . $this->data['currentdate'] . "') + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND LogTime.start_datetime <  (LAST_DAY('" . $this->data['currentdate'] . "') + INTERVAL 1 DAY) AND ";
        } else {
            $usrCndtn = " USERS.id IS NOT NULL AND LogTime.start_datetime >= (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND LogTime.start_datetime <  (LAST_DAY(CURDATE()) + INTERVAL 1 DAY) AND ";
            $tskCndtn = " Easycase.id IS NOT NULL AND LogTime.start_datetime >= (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND LogTime.start_datetime <  (LAST_DAY(CURDATE()) + INTERVAL 1 DAY) AND ";
        }
        if (SES_TYPE == 3) {
            $usrCndtn1 = " AND  `LogTime`.user_id= " . SES_ID . " ";
        } else {
            $usrCndtn1 = '';
        }
        if (isset($this->data['task_id']) && $this->data['task_id'] != '') {
            $tskCndtn = "`LogTime`.task_id= " . $this->data['task_id'] . " AND LogTime.start_datetime >= (LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) AND LogTime.start_datetime <  (LAST_DAY(CURDATE()) + INTERVAL 1 DAY) AND ";
            $curCaseId = $this->data['task_id'];
            $taskUid = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $curCaseId), 'fields' => array('Easycase.uniq_id', 'Easycase.title', 'Easycase.isactive')));
            $caseTitleRep = $taskUid['Easycase']['title'];
            $isactive = $taskUid['Easycase']['isactive'];
            $prjDtls = $this->Project->find('first', array('conditions' => array('Project.uniq_id' => $projFil), 'fields' => array('Project.name')));
        }
        $logsql = "SELECT SQL_CALC_FOUND_ROWS DAYS.*,TASK_TYPE.*,TASKS.* FROM "
                . "(SELECT DATE_FORMAT(LogTime.start_datetime,'%m %d %Y') AS start_datetime_v1,LogTime.start_datetime,Easycase.title, "
                . "SUM(LogTime.total_hours) AS agrigate_hours "
                . "FROM `log_times` AS `LogTime` "
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . "WHERE " . $tskCndtn . "`LogTime`.`project_id` IN (" . implode(',', $project_id) . ") AND Easycase.isactive=1 $usrCndtn1 $where "
                . "GROUP BY  start_datetime_v1 ORDER BY LogTime.start_datetime DESC ) AS DAYS "
                . "LEFT JOIN "
                . "(SELECT DATE_FORMAT(LogTime.start_datetime,'%m %d %Y') AS start_datetime_v2, "
                . "SUM(LogTime.total_hours) AS agrigate_hours ,USERS.name,USERS.email,USERS.photo ,LogTime.user_id "//TYPES.name, TYPES.short_name
                . "FROM `log_times` AS `LogTime` "
                . "LEFT JOIN users as USERS ON LogTime.user_id=USERS.id "
                . "WHERE " . $usrCndtn . "`LogTime`.`project_id` IN (" . implode(',', $project_id) . ") $usrCndtn1 $where "
                . "GROUP BY LogTime.user_id,start_datetime_v2 ) AS TASK_TYPE ON (DAYS.start_datetime_v1=TASK_TYPE.start_datetime_v2)"
                . "LEFT JOIN "
                . "(SELECT DATE_FORMAT(LogTime.start_datetime,'%m %d %Y') AS start_datetime_v3,"
                . "SUM(LogTime.total_hours) AS agrigate_hours ,Easycase.title,Easycase.case_no ,LogTime.task_id ,"
                . "GROUP_CONCAT(DISTINCT LogTime.user_id) as userids "
                . "FROM `log_times` AS `LogTime` "
                . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                . "WHERE " . $tskCndtn . "`LogTime`.`project_id` IN (" . implode(',', $project_id) . ") AND Easycase.isactive=1 $usrCndtn1 $where "
                . "GROUP BY  LogTime.task_id,start_datetime_v3) AS TASKS ON  (DAYS.start_datetime_v1=TASKS.start_datetime_v3) ORDER BY TASKS.agrigate_hours DESC ";

        #print $logsql;exit;
        $logtimes = $this->LogTime->query($logsql);
        $dates = array_unique(Hash::extract($logtimes, '{n}.DAYS.start_datetime_v1'));
        $results = array();
        $i = 0;
        foreach ($dates as $key => $val) {
            foreach ($logtimes as $k => $v) {
                $i = date('d', strtotime($v['DAYS']['start_datetime']));
                if ($v['DAYS']['start_datetime_v1'] == $val) {
                    $results[$i]['DAYS'] = $v['DAYS'];
                }
                if ($v['TASK_TYPE']['start_datetime_v2'] == $val) {
                    $results[$i]['TASK_TYPE'][$v['TASK_TYPE']['user_id']] = $v['TASK_TYPE'];
                }
                if ($v['TASKS']['start_datetime_v3'] == $val) {
                    $results[$i]['TASKS'][$v['TASKS']['task_id']] = $v['TASKS'];
                }
            }
        }
        /** get the max hours ****** */
        $max = 0;
        foreach ($results as $k => $v) {
            $max = max($max, $v['DAYS']['agrigate_hours']);
        }
        $this->set('max', $max);
        /*         * *End** */
        /* set the json value for creating the chart** */
        $arr = array();
        $chart = array();
        foreach ($results as $k => $v) {
            $v['DAYS']['title'] = h($v['DAYS']['title'], true, 'UTF-8');
            $arr[$k]['name'] = $v['DAYS']['start_datetime_v1'];
            $arr[$k]['colorByPoint'] = 'true';
            $arr[$k]['agrigate_hours'] = round((($v['DAYS']['agrigate_hours'] / $max) * 100) / 2); // This is calculate the size of the piechnart max height is 50px so after calculate the % value devide by 2 
            $arr[$k]['agrigate_hours'] = ($arr[$k]['agrigate_hours'] < 20) ? 20 : $arr[$k]['agrigate_hours']; // Set the minimum size as 10 of the piechnart
            $arr1 = array();
            $j = 0;
            foreach ($v['TASK_TYPE'] as $k1 => $v1) {
                $arr1[$j]['name'] = $v1['name'];
                $arr1[$j]['y'] = (floatval($v1['agrigate_hours']) * 100) / floatval($v['DAYS']['agrigate_hours']);
                $arr1[$j]['hours'] = $this->Format->seconds2human($v1['agrigate_hours']);
                $j++;
            }
            $arr[$k]['data'] = $arr1;
            $chart[$k]['start_datetime'] = $v['DAYS']['start_datetime'];
            $chart[$k]['actual_hours'] = $this->Format->seconds2human($v['DAYS']['agrigate_hours']);
            $chart[$k]['users'] = $v['TASK_TYPE'];
            $chart[$k]['tasks'] = $v['TASKS'];
            foreach ($v['TASKS'] as $tk => $tv) {
                $uids = explode(',', $tv['userids']);
                foreach ($uids as $uk => $uv) {
                    $u = $this->LogTime->find('all', array('fields' => array('SUM(LogTime.total_hours) as hrs'), 'conditions' => array('LogTime.task_id' => $tk, 'LogTime.user_id' => $uv, 'DATE(start_datetime)' => date('Y-m-d', strtotime($v['DAYS']['start_datetime'])))));
                    $chart[$k]['tasks'][$tk]['uid'][$uv] = $this->Format->seconds2fraction($u[0][0]['hrs']);
                }
                $chart[$k]['tasks'][$tk]['title'] = h($chart[$k]['tasks'][$tk]['title'], true, 'UTF-8');
                $chart[$k]['tasks'][$tk]['agrigate_hours'] = $this->Format->seconds2fraction($chart[$k]['tasks'][$tk]['agrigate_hours']);
            }
        }
        $this->set('datas', json_encode($arr));
        /* end* */
        $this->set('chart', $chart);
    }

    /*
     * Author: Satyajeet
     * This function is used to get the resource availability view
     */

    function resource_availability() {
        if (SES_TYPE > 2) {
            $this->redirect(HTTP_ROOT . 'timelog');
        }
    }

    function ajax_resource_availability() {
        $this->layout = 'ajax';
        $this->loadModel('ProjectBookedResource');
        $cond = "";
        $qry = '';
        $arr = array();
        $log_condition = '';
        $filter_msg = array();
        $dt_arr = array();
        $curDate = date('Y-m-d H:i:s');
        if (isset($this->request->data['start_date']) && $this->request->data['start_date'] != '') {
            $date = $this->Format->date_filter('next30days', date('Y-m-d', strtotime($this->request->data['start_date'])));
        } else if (isset($this->request->data['last_date']) && $this->request->data['last_date'] != '') {
            $date = $this->Format->date_filter('last30days', date('Y-m-d', strtotime($this->request->data['last_date'])));
        } else {
            $date = $this->Format->date_filter('next30days', $curDate);
        }
        $dts_arr = $dt_arr = array();
        $days = floor((strtotime($date['enddt']) - strtotime($date['strddt'])) / (60 * 60 * 24));
        for ($i = 0; $i <= $days; $i++) {
            $m = " +" . $i . "day";
            $dt = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date['strddt'])) . $m));
            $dts = date('d M', strtotime(date("Y-m-d H:i:s", strtotime($date['strddt'])) . $m));
            $times = explode(" ", GMT_DATETIME);
            array_push($dts_arr, $dts);
            array_push($dt_arr, $dt);
        }
        $sort_cond = ' order by ProjectBookedResource.user_id ASC';
        $grpby = '';
        $this->loadModel('User');
        $this->User->bindModel(array(
            'hasMany' => array(
                'ProjectBookedResource' => array(
                    'className' => 'ProjectBookedResource',
                    'belongsTo' => array(
                        'Easycase' => array(
                            'className' => 'Easycase',
                        )
                    )
                ),
                'Overload' => array(
                    'className' => 'Overload',
                ),
                'UserLeave' => array(
                    'className' => 'UserLeave'
                )
            )
        ));
        $user_conditions_array = array('User.isactive' => 1);
        if (is_array($this->request->data['user_id']) && !empty($this->request->data['user_id'])) {
            if (count($this->request->data['user_id']) == 1) {
                $user_conditions_array['User.id'] = $this->request->data['user_id'][0];
            } else {
                $user_conditions_array['User.id IN'] = $this->request->data['user_id'];
            }
        }

        $this->User->Behaviors->attach('Containable');
        $bookedData = $this->User->find(
                'all', array(
            'contain' => array(
                'ProjectBookedResource' => array(
                    'conditions' => array(
                        'ProjectBookedResource.company_id' => SES_COMP,
                        'ProjectBookedResource.date >=' => date('Y-m-d', strtotime($date['strddt']))
                    )
                ),
                'Overload' => array(
                    'conditions' => array(
                        'Overload.company_id' => SES_COMP,
                        'Overload.date >=' => date('Y-m-d', strtotime($date['strddt']))
                    )
                ),
                'UserLeave' => array(
                    'conditions' => array(
                        'UserLeave.company_id' => SES_COMP,
                        'or' => array(
                            'UserLeave.start_date >=' => date('Y-m-d', strtotime($date['strddt'])),
                            'UserLeave.end_date >=' => date('Y-m-d', strtotime($date['strddt']))
                        )
                    )
                )
            ),
            'joins' => array(
                array(
                    'table' => 'company_users',
                    'alias' => 'CompanyUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'CompanyUser.user_id=User.id',
                        'CompanyUser.company_id' => SES_COMP
                    )
                )
            ),
            'conditions' => $user_conditions_array,
            'recursive' => 2
                )
        );
        $data = array();
        foreach ($bookedData as $k => $val) {
            $data[$val['User']['id']]['resource'] = $val['User']['name'] . ' ' . $val['User']['last_name'];
            if (!isset($data[$val['User'][id]]['data'])) {
                $data[$val['User']['id']]['data'] = array();
                $data[$val['User']['id']]['overload'] = array();
            }
            foreach ($val['ProjectBookedResource'] as $key => $timeData) {
                if ($this->Format->taskIsActive($timeData['easycase_id'])) {
                    $data[$val['User']['id']]['data'][$timeData['date']] += $timeData['booked_hours'];
                    #$data[$val['User']['id']]['overload'][$timeData['date']] = $timeData['overload'];
                }
            }
            foreach ($val['Overload'] as $k => $overloadData) {
                if ($this->Format->taskIsActive($overloadData['easycase_id'])) {
                    $data[$val['User']['id']]['overload'][$overloadData['date']] = $overloadData['overload'];
                }
            }
            $data[$val['User']['id']]['leave'] = $data[$val['User']['id']]['leave_reason'] = array();
            foreach ($val['UserLeave'] as $k1 => $leave) {
                $leaveDates = $this->Format->getLeaveDates($leave['start_date'], $leave['end_date'], $leave['id']);
                $data[$val['User']['id']]['leave'] = array_merge($data[$val['User']['id']]['leave'], $leaveDates);
                $leaveDateList = $this->Format->getLeaveDates($leave['start_date'], $leave['end_date'], $leave['id'], 'list');
                $leaveDateLists = array_fill_keys($leaveDateList, $leave['reason']);
                $data[$val['User']['id']]['leave_reason'] = array_merge($data[$val['User']['id']]['leave_reason'], $leaveDateLists);
                //  $data[$val['User']['id']]['leave_reason'] = array_merge($data[$val['User']['id']]['leave'], $leaveDates);
            }
        }
        // echo "<pre>";print_r($data);exit;
        $this->set('dates', $dts_arr);
        $this->set('date', $dt_arr);
        $this->set('data', $data);
    }

    public function getProjs() {
        $this->loadModel('Project');
        $data_res = Hash::combine($this->Project->find('all', array('conditions' => array('Project.company_id' => SES_COMP, 'Project.isactive' => 1), 'order' => 'Project.name ASC', 'recursive' => -1)), '{n}.Project.uniq_id', '{n}.Project.name');
        echo json_encode($data_res);
        exit;
    }

    function checkAvailableUsers($callee = array()) {
        $this->layout = 'ajax';
        $data = !empty($callee) ? $callee : $this->request->data;
        $assigned_Resource_id = !empty($callee) ? $callee['assignTo'] : $this->request->data['assignedId'];
        $assigned_Resource_date = !empty($callee) ? $callee['str_date'] : date('Y-m-d', strtotime($this->request->data['gantt_start_date']));
        $estimated_hrs = !empty($callee) ? $callee['est_hr'] : (!empty($data['estimated_hours']) ? $data['estimated_hours'] : 0);
        $assigned_Resource_project = !empty($callee) ? $callee['projectId'] : $this->request->data['project_id'];
        $caseId = !empty($callee) ? $callee['caseId'] : $this->request->data['caseid'];
        $perDayWorkSec = $GLOBALS['company_work_hour'] * 3600;
        $caseUniqId = !empty($callee) ? $callee['caseUniqId'] : $this->request->data['caseuniqid'];
        $this->loadModel('Timelog.ProjectBookedResource');
        $this->loadModel('CompanyUser');
        $this->User->bindModel(array('hasMany' => array('ProjectBookedResource' => array('className' => 'ProjectBookedResource'))));
        $this->User->Behaviors->attach('Containable');
        $conditions_array = array('ProjectBookedResource.company_id' => SES_COMP, 'DATE(ProjectBookedResource.date) >=' => date('Y-m-d', strtotime($assigned_Resource_date)));
        $users_conditions = array('User.isactive' => 1);
        if (!empty($callee)) {
            $conditions_array['ProjectBookedResource.user_id'] = $callee['assignTo'];
            $users_conditions['User.id'] = $callee['assignTo'];
        }
        $data = $this->User->find('all', array('joins' => array(array('table' => 'company_users', 'alias' => 'CompanyUser', 'type' => 'INNER', 'conditions' => array('CompanyUser.user_id=User.id', 'CompanyUser.company_id' => SES_COMP)), array('table' => 'project_users', 'alias' => 'ProjectUser', 'type' => 'INNER', 'conditions' => array('ProjectUser.user_id=User.id', 'ProjectUser.project_id' => $assigned_Resource_project))), 'conditions' => $users_conditions, 'recursive' => -1));
        $ResourceNextAvailableDate = array();
        foreach ($data as $k => $usrdata) {
            //Find Last Assigned Date 
            $last_res_arr = $this->ProjectBookedResource->find('all', array(
                'conditions' => array(
                    'ProjectBookedResource.company_id' => SES_COMP,
                    'ProjectBookedResource.user_id' => !empty($callee) ? $callee['assignTo'] : $usrdata['User']['id'],
                    'DATE(ProjectBookedResource.date) >=' => date('Y-m-d', strtotime($assigned_Resource_date))
                ),
                'joins' => array(
                    array(
                        'table' => 'easycases',
                        'alias' => 'Easycase',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Easycase.id=ProjectBookedResource.easycase_id',
                            'Easycase.isactive' => 1
                        )
                    )
                ),
                'order' => array('ProjectBookedResource.date' => 'DESC'),
                'limit' => 1
            ));
            $ResourceNextAvailableDate[$usrdata['User']['id']]['name'] = $usrdata['User']['name'] . ' ' . $usrdata['User']['last_name'];
            $user_id = !empty($callee) ? $callee['assignTo'] : $usrdata['User']['id'];
            $view = new View($this);
            $frmt = $view->loadHelper('Format');
            if (!empty($last_res_arr)) {
                $all_working_days = $this->checkLeavestats($user_id, $assigned_Resource_date, $last_res_arr[0]['ProjectBookedResource']['date']);
                $total_consumed_hours = 0;
                $AssignedResourceNextAvailableDataAll = array();
                $needed = $estimated_hrs;
                foreach ($all_working_days as $key => $value) {
                    $Avlhrs = ($perDayWorkSec - $value[0]['booked_hours']) / 3600;
                    if ($needed > 0) {
                        $total_consumed_hours += $Avlhrs;
                        if ($total_consumed_hours > $estimated_hrs) {
                            $AssignedResourceNextAvailableDataAll['next_available_dates'][] = array("date" => $frmt->chngdate($value['ProjectBookedResource']['date']), 'Avlhrs' => $needed);
                            break;
                        } else {
                            $AssignedResourceNextAvailableDataAll['next_available_dates'][] = array("date" => $frmt->chngdate($value['ProjectBookedResource']['date']), 'Avlhrs' => $Avlhrs);
                        }
                        $needed -= $Avlhrs;
                    }
                }
                $ResourceNextAvailableDate[$usrdata['User']['id']]['AvailableHours'] = ($total_consumed_hours < $estimated_hrs) ? $this->assignWork($last_res_arr[0]['ProjectBookedResource']['date'], $total_consumed_hours, $estimated_hrs, $AssignedResourceNextAvailableDataAll, date('Y-m-d', strtotime($assigned_Resource_date))) : $AssignedResourceNextAvailableDataAll;
            } else {
                $curr_date = date('Y-m-d');
                $last_date = date("Y-m-d", strtotime($curr_date));
                // echo $assigned_Resource_date."<br/>".$last_date;
                $total_no_estimate_dys = round($estimated_hrs / $GLOBALS['company_work_hour']);
                $lastDate = date("Y-m-d", strtotime($assigned_Resource_date . " + " . $total_no_estimate_dys . " days"));
                // echo $total_no_estimate_dys."<br/>".$lastDate;exit;
                $all_working_days = $this->checkLeavestats($user_id, $assigned_Resource_date, $lastDate);
                $total_consumed_hours = 0;
                $needed = $estimated_hrs;
//                print_r($all_working_days); exit;
                $AssignedResourceNextAvailableDataAll = array();
                foreach ($all_working_days as $key => $value) {
                    $Avlhrs = ($perDayWorkSec - $value[0]['booked_hours']) / 3600;
                    if ($needed > 0) {
                        if (($needed * 3600) < $perDayWorkSec) {
                            $AssignedResourceNextAvailableDataAll['next_available_dates'][] = array("date" => $frmt->chngdate($value['ProjectBookedResource']['date']), 'Avlhrs' => $needed);
                            $needed -= $needed;
                            break;
                        } else {
                            if (($needed * 3600) > $perDayWorkSec) {
                                $AssignedResourceNextAvailableDataAll['next_available_dates'][] = array("date" => $frmt->chngdate($value['ProjectBookedResource']['date']), 'Avlhrs' => $perDayWorkSec / 3600);
                                $needed = $needed - ($perDayWorkSec / 3600);
                            } else {
                                $AssignedResourceNextAvailableDataAll['next_available_dates'][] = array("date" => $frmt->chngdate($value['ProjectBookedResource']['date']), 'Avlhrs' => $needed);
                                $needed = $needed - $needed;
                            }
                        }
                    }
                }
                $ResourceNextAvailableDate[$usrdata['User']['id']]['AvailableHours'] = $AssignedResourceNextAvailableDataAll;
            }
        }
        if (!empty($callee)) {
            return $ResourceNextAvailableDate;
            exit;
        } else {
            $view = new View($this);
            $frmt = $view->loadHelper('Format');
            foreach ($ResourceNextAvailableDate as $key => $value) {
                foreach ($value['AvailableHours']['next_available_dates'] as $k => $v) {
                    $ResourceNextAvailableDate[$key]['AvailableHours']['next_available_dates'][$k]['date'] = $frmt->chngdate($v['date']);
                }
            }
            $this->set('assignedResourceNextAvailabelData', $ResourceNextAvailableDate[$assigned_Resource_id]);
            $this->set('ResourceNextAvailableDate', $ResourceNextAvailableDate);
            $this->set('caseId', $caseId);
            $this->set('caseUniqId', $caseUniqId);
            $this->set('project_id', $assigned_Resource_project);
            $this->set('estimated_hours', $estimated_hrs);
            $this->set('gantt_start_date', date('Y-m-d', strtotime($this->request->data['gantt_start_date'])));
            $this->set('assigned_Resource_id', $assigned_Resource_id);
        }
    }

    public function checkLeavestats($user_id, $start, $end) {
        $start = date('Y-m-d', strtotime($start));
        $end = date('Y-m-d', strtotime($end));
        $dates_array = $this->createDateRangeArray($start, $end);
        $perDayWorkSec = $GLOBALS['company_work_hour'] * 3600;
        $UserLeaves = ClassRegistry::init('Timelog.UserLeave');
        $leaves = $UserLeaves->find('all', array('conditions' => array('UserLeave.company_id' => SES_COMP, 'UserLeave.user_id' => $user_id)));
        $query = "SELECT SUM(`ProjectBookedResource`.`booked_hours`) AS booked_hours, `ProjectBookedResource`.`id`, `ProjectBookedResource`.`company_id`, `ProjectBookedResource`.`project_id`, `ProjectBookedResource`.`easycase_id`, `ProjectBookedResource`.`user_id`, `ProjectBookedResource`.`date` FROM `project_booked_resources` AS `ProjectBookedResource` INNER JOIN easycases AS Easycase ON Easycase.id=ProjectBookedResource.easycase_id  AND Easycase.isactive=1  WHERE `ProjectBookedResource`.`company_id` = " . SES_COMP . " AND `ProjectBookedResource`.`user_id` = " . $user_id . " AND DATE(`ProjectBookedResource`.`date`) >= '" . $start . "' AND DATE(`ProjectBookedResource`.`date`) <= '" . $end . "'  GROUP BY `ProjectBookedResource`.`date`";
        $all_working_days = $this->ProjectBookedResource->query($query);
        $bookedDates = Hash::extract($all_working_days, '{n}.ProjectBookedResource.date');
        $asg_arr = array();
        foreach ($dates_array as $key => $value) {
            //Check If User is on leave
            $inleave = $this->Postcase->checkDateInLeave($value, $leaves);
            if (!$inleave) {
                if (!in_array($value, $bookedDates)) {
                    $asg_arr[] = array(0 => array('booked_hours' => 0), 'ProjectBookedResource' => array('date' => $value));
                } else {
                    //for every working day
                    foreach ($all_working_days as $k => $v) {
                        //IF date not available
                        if (strtotime($v['ProjectBookedResource']['date']) === strtotime($value) && $v[0]['booked_hours'] < $perDayWorkSec) {
                            $asg_arr[] = array(0 => array('booked_hours' => $v[0]['booked_hours']), 'ProjectBookedResource' => array('date' => $v['ProjectBookedResource']['date']));
                        }
                    }
                }
            }
        }
        return $asg_arr;
    }

    function createDateRangeArray($strDateFrom, $strDateTo) {
        $aryRange = array();
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom));
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400;
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function assignWork($users_last_date, $total_consumed_hours, $estimated_hrs, $AssignedResourceNextAvailableData, $asgn_date, $user_id) {
        $perDayWorkSec = $GLOBALS['company_work_hour'] * 3600;
        $total_left_hrs = $next_left_hrs = ($estimated_hrs - $total_consumed_hours) * 3600;
        $more_days_needed = ceil($total_left_hrs / $perDayWorkSec);
        if (!empty($users_last_date)) {
            for ($i = 0; $i <= $more_days_needed; $i++) {
                $newDate = date('Y-m-d', strtotime($users_last_date . " +" . $i . "days"));
                if ($users_last_date !== $newDate) {
                    $assgign_hour = ($next_left_hrs > $perDayWorkSec) ? $perDayWorkSec / 3600 : $next_left_hrs / 3600;
                    $AssignedResourceNextAvailableData['next_available_dates'][] = array("date" => date('M d, Y', strtotime($newDate)), 'Avlhrs' => $assgign_hour);
                    $next_left_hrs -= $perDayWorkSec;
                }
            }
        } else {
            $users_last_date = date('Y-m-d');
            for ($i = 0; $i < $more_days_needed; $i++) {
                $newDate = date('Y-m-d', strtotime($users_last_date . " +" . $i . "days"));
                $assgign_hour = ($next_left_hrs > $perDayWorkSec) ? $perDayWorkSec / 3600 : $next_left_hrs / 3600;
                $AssignedResourceNextAvailableData['next_available_dates'][] = array("date" => date('M d, Y', strtotime($newDate)), 'Avlhrs' => $assgign_hour);
                $next_left_hrs -= $perDayWorkSec;
            }
        }
        return $AssignedResourceNextAvailableData;
    }

    function changeresource() {
        $res = $this->checkAvailableUsers($this->request->data);
        $caseId = $this->request->data['caseId'];
        $caseUniqId = $this->request->data['caseUniqId'];
        $assignTo = $this->request->data['assignTo'];
        $projectId = $this->request->data['projectId'];
        $start_date = date('Y-m-d H:i:s', strtotime($this->request->data['str_date']));
        $perDayWorkSec = $GLOBALS['company_work_hour'] * 3600;
        $this->loadModel('Easycase');
        $caseDetails = $this->Easycase->find('first', array('conditions' => array('Easycase.id' => $caseId), 'fields' => array('Easycase.estimated_hours')));
        $estimated_hours = $caseDetails['Easycase']['estimated_hours'];
        if ($estimated_hours == '') {
            $due_date = date('Y-m-d', strtotime($start_date));
        } else {
            $BookedResources = ClassRegistry::init('Timelog.ProjectBookedResource');
            $bookedData = $BookedResources->find('all', array('conditions' => array('ProjectBookedResource.company_id' => SES_COMP, 'ProjectBookedResource.user_id' => $assignTo, 'ProjectBookedResource.date >=' => date('Y-m-d', strtotime($start_date)))));
            $bookedDates = Hash::combine($bookedData, '{n}.ProjectBookedResource.date', '{n}.ProjectBookedResource.booked_hours');
            $data = array();
            $j = 0;
            foreach ($res[$assignTo]['AvailableHours']['next_available_dates'] as $key => $value) {
                $newbookedhrs = $value['Avlhrs'] * 3600;
                $data[$j]['ProjectBookedResource']['company_id'] = SES_COMP;
                $data[$j]['ProjectBookedResource']['user_id'] = $assignTo;
                $data[$j]['ProjectBookedResource']['project_id'] = $projectId;
                $data[$j]['ProjectBookedResource']['easycase_id'] = $caseId;
                $data[$j]['ProjectBookedResource']['date'] = date('Y-m-d', strtotime($value['date']));
                $data[$j]['ProjectBookedResource']['booked_hours'] = $newbookedhrs;
                $estimated_hours -= $newbookedhrs;
                $j++;
            }
            $due_date = $data[$j - 1]['ProjectBookedResource']['date'];
            $start_date = $data[0]['ProjectBookedResource']['date'];
            // echo "<pre>";echo $j;print_r($data);exit;
            $BookedResources->saveMany($data);
        }
        $this->Easycase->updateAll(array('Easycase.assign_to' => $assignTo, 'Easycase.gantt_start_date' => '"' . $start_date . '"', 'Easycase.due_date' => '"' . $due_date . '"'), array('Easycase.id' => $caseId));
        echo 1;
        exit;
    }

    public function overloadUsers() {
        if ($this->request->is('ajax')) {
            $callee = $this->request->data;
            $assigned_Resource_id = $callee['assignTo'];
            $estimated_hrs = $callee['est_hr'];
            $assigned_Resource_project = $callee['projectId'];
            $caseId = $callee['caseId'];
            $perDayWorkSec = $GLOBALS['company_work_hour'] * 3600;
            $caseUniqId = $callee['caseUniqId'];
            $no_of_days = $nof_of_days_lv = ceil(($estimated_hrs * 3600) / $perDayWorkSec);
            $startdate = $assigned_Resource_date = date('Y-m-d', strtotime($callee['str_date']));
            $lastdate = date('Y-m-d', strtotime($assigned_Resource_date . " +" . ($no_of_days - 1) . "days"));
            $this->loadModel('Overload');
            $this->loadModel('Timelog.ProjectBookedResource');
            $this->loadModel('UserLeave');
            $leaves = $this->UserLeave->find('all', array('conditions' => array('UserLeave.company_id' => SES_COMP, 'UserLeave.user_id' => $assigned_Resource_id)));

            $working_dates = array();
            $do = $no_of_days;
            while ($do > 0) {
                $inleave = $this->Postcase->checkDateInLeave($assigned_Resource_date, $leaves);
                if (!$inleave) {
                    $working_dates[] = $assigned_Resource_date;
                    $do--;
                }
                $assigned_Resource_date = date('Y-m-d', strtotime($assigned_Resource_date . " +" . 1 . " days"));
            }
            $partial_days = array();
            foreach ($working_dates as $key => $value) {
                $query = "SELECT SUM(`ProjectBookedResource`.`booked_hours`) AS booked_hrs, `ProjectBookedResource`.`id`, `ProjectBookedResource`.`company_id`, `ProjectBookedResource`.`project_id`, `ProjectBookedResource`.`easycase_id`, `ProjectBookedResource`.`user_id`, `ProjectBookedResource`.`date` FROM `project_booked_resources` AS `ProjectBookedResource` WHERE `ProjectBookedResource`.`company_id` = " . SES_COMP . " AND `ProjectBookedResource`.`user_id` = " . $assigned_Resource_id . " AND DATE(`ProjectBookedResource`.`date`) = '" . date('Y-m-d', strtotime($value)) . "' GROUP BY `ProjectBookedResource`.`date`";
                $hours_booked = $this->ProjectBookedResource->query($query);
                if (!empty($hours_booked)) {
                    $booked_hours = $hours_booked[0][0]['booked_hrs'];
                    $all_hours_taken = ($booked_hours == $perDayWorkSec) ? true : false;
                    if (!$all_hours_taken) {
                        $partial_days[$value] = $booked_hours;
                    }
                } else {
                    $partial_days[$value] = $perDayWorkSec;
                }
            }

            $due_date = date('Y-m-d', strtotime($startdate));
            if (empty($partial_days)) {
                $overload_hours = $ovhrs = ($estimated_hrs / $no_of_days) * 3600;
                $overload = array();
                foreach ($working_dates as $key => $value) {
                    $newDate = $due_date = date('Y-m-d', strtotime($value));
                    $load_data = $this->ProjectBookedResource->find('all', array('conditions' => array('ProjectBookedResource.user_id' => $assigned_Resource_id, 'ProjectBookedResource.company_id' => SES_COMP, 'DATE(ProjectBookedResource.date) =' => $newDate)));
                    /* $this->ProjectBookedResource->updateAll(
                      array('ProjectBookedResource.overload' => (!empty($load_data) && !empty($load_data[0]['ProjectBookedResource']['overload'])) ? ($overload_hours + $load_data[0]['ProjectBookedResource']['overload']) : $overload_hours), array('ProjectBookedResource.company_id' => SES_COMP, 'ProjectBookedResource.user_id' => $assigned_Resource_id, 'DATE(ProjectBookedResource.date) =' => $newDate)
                      ); */
                    $overload[] = array('date' => $newDate, 'easycase_id' => $caseId, 'project_id' => $assigned_Resource_project, 'user_id' => $assigned_Resource_id, 'company_id' => SES_COMP, 'overload' => $overload_hours);
                }
                $this->Overload->saveAll($overload);
            } else {
                $estimated_hrss = $estimated_hrs;
                foreach ($partial_days as $key => $value) {
                    $pr_bk_hrs['ProjectBookedResource'] = array();
                    $newDate = date('Y-m-d', strtotime($key));
                    $a = $perDayWorkSec - $value;
                    $rest_to_assign = (empty($a)) ? ($perDayWorkSec / 3600) : ($a) / 3600;
                    $bookde_val = (empty($a)) ? $perDayWorkSec : ($a);
                    $pr_bk_hrs['ProjectBookedResource'] = array('user_id' => $assigned_Resource_id, 'date' => $newDate, 'project_id' => $assigned_Resource_project, 'easycase_id' => $caseId, 'company_id' => SES_COMP, 'booked_hours' => $bookde_val, 'overload' => 0);
                    $this->ProjectBookedResource->create();
                    $this->ProjectBookedResource->save($pr_bk_hrs);
                    $estimated_hrss -= $rest_to_assign;
                }
                $overload_hours = ($estimated_hrss / $no_of_days) * 3600;
                $last_date = $working_dates[count($working_dates) - 1];
                $overload = array();
                foreach ($working_dates as $key => $value) {
                    $newDate = $due_date = date('Y-m-d', strtotime($value));
                    $load_data = $this->ProjectBookedResource->find('all', array('conditions' => array('ProjectBookedResource.user_id' => $assigned_Resource_id, 'ProjectBookedResource.company_id' => SES_COMP, 'DATE(ProjectBookedResource.date) =' => $newDate)));
                    /* $this->ProjectBookedResource->updateAll(
                      array('ProjectBookedResource.overload' => (!empty($load_data) && !empty($load_data[0]['ProjectBookedResource']['overload'])) ? ($overload_hours + $load_data[0]['ProjectBookedResource']['overload']) : $overload_hours), array('ProjectBookedResource.company_id' => SES_COMP, 'ProjectBookedResource.user_id' => $assigned_Resource_id, 'DATE(ProjectBookedResource.date) =' => $newDate)
                      ); */
                    $overload[] = array('date' => $newDate, 'easycase_id' => $caseId, 'project_id' => $assigned_Resource_project, 'user_id' => $assigned_Resource_id, 'company_id' => SES_COMP, 'overload' => $overload_hours);
                }
                $this->Overload->saveAll($overload);
            }
            // echo "<pre>";print_r($working_dates);exit;
            $this->loadModel('Easycase');
            $this->Easycase->updateAll(array('Easycase.assign_to' => $assigned_Resource_id, 'Easycase.gantt_start_date' => '"' . date('Y-m-d H:i:s', strtotime($working_dates[0])) . '"', 'Easycase.due_date' => '"' . date('Y-m-d H:i:s', strtotime($working_dates[count($working_dates) - 1])) . '"'), array('Easycase.id' => $caseId));
            echo 1;
            exit;
        }
    }

    public function getProjusers() {
        $proj_id = $this->request->query['projUniq'];
        $clt_sql = 1;
        $mlstnQ2 = '';
        $mlstnQ2 = '';
        $qry = '';
        $options = array();
        $options['group'] = array('ProjectUser.user_id');
        $options['fields'] = array('DISTINCT ProjectUser.user_id AS user_id', 'User.*');
        $options['conditions'] = array("User.isactive" => 1);
        $options['order'] = array('User.name ASC');
        if (trim($proj_id) !== 'all') {
            $proj_options = array('conditions' => array('Project.uniq_id' => $proj_id));
            $project_data = $this->Project->find('first', $proj_options);
            $options['conditions'] = array('ProjectUser.project_id' => $project_data['Project']['id'], "User.isactive" => 1);
        }

        $this->ProjectUser->bindModel(array('belongsTo' => array('User' => array('className' => 'User', 'foreignKey' => 'user_id'))));
        $user_data = $this->ProjectUser->find('all', $options);
        echo json_encode($user_data);
        exit;
    }

    public function getBookedhourreports() {
        $this->layout = 'ajax';
        $this->loadModel('ProjectBookedResource');
        $data = $this->request->data;
        $options['conditions'] = array('ProjectBookedResource.user_id' => $data['user_id'], 'DATE(ProjectBookedResource.date)' => $data['date']);
        $options['fields'] = array("ProjectBookedResource.*", "Company.*", "Project.*", "Easycase.*", "User.*");
        $options['joins'] = array(
            array('table' => 'companies', 'alias' => 'Company', 'type' => 'INNER', 'conditions' => array('Company.id = ProjectBookedResource.company_id')),
            array('table' => 'projects', 'alias' => 'Project', 'type' => 'INNER', 'conditions' => array('Project.id = ProjectBookedResource.project_id')),
            array('table' => 'easycases', 'alias' => 'Easycase', 'type' => 'INNER', 'conditions' => array('Easycase.id = ProjectBookedResource.easycase_id')),
            array('table' => 'users', 'alias' => 'User', 'type' => 'INNER', 'conditions' => array('User.id = ProjectBookedResource.user_id')),
        );
        $booked_data = $this->ProjectBookedResource->find('all', $options);
        $data_arr['date'] = date('M d, Y', strtotime($data['date']));
        foreach ($booked_data as $key => $value) {
            $data_arr['booked_rsrs'][] = array('project' => $value['Project']['name'], 'case_no' => $value['Easycase']['case_no'], 'case_title' => $value['Easycase']['title'], 'hours_booked' => $value['ProjectBookedResource']['booked_hours'] / 3600);
            /* if (isset($value['ProjectBookedResource']['overload']) && !empty($value['ProjectBookedResource']['overload'])) {
              $data_arr['overload'] = $value['ProjectBookedResource']['overload'];
              } */
            $data_arr['user'] = $value['User']['name'];
            $data_arr['userId'] = $value['User']['id'];
        }
        $this->loadModel('Overload');
        $totalOverloadHours = $this->Overload->find('all', array('conditions' => array('Overload.user_id' => $data['user_id'], 'DATE(Overload.date)' => $data['date']), 'fields' => array('SUM(Overload.overload) as total_overload'), 'group' => array('Overload.user_id', 'Overload.date')));
        $this->set('data_arr', $data_arr);
        $this->set('total_overload', $totalOverloadHours[0][0]['total_overload']);
    }

    public function getOverloads() {
        $this->layout = 'ajax';
        $this->loadModel('Overload');
        $data = $this->request->data;
        $date = date('Y-m-d', strtotime($data['date']));
        $options['conditions'] = array('Overload.user_id' => $data['user_id'], 'DATE(Overload.date)' => $data['date']);
        $over_data = $this->Overload->find('all', $options);
        $data_arr['date'] = date('M d, Y', strtotime($date));
        foreach ($over_data as $key => $value) {
            $data_arr['overload_rsrs'][] = array('project' => $value['Project']['name'], 'case_no' => $value['Easycase']['case_no'], 'case_title' => $value['Easycase']['title'], 'hours_overload' => $value['Overload']['overload'] / 3600);
            $data_arr['user'] = $value['User']['name'];
            $data_arr['userId'] = $value['User']['id'];
        }
        $this->set('over_data', $data_arr);
        $this->set('date', $data['date']);
    }

    /*
     * Author: Satyajeet
     * To add/update leave/vacation data
     */

    function save_vacation() {
        $data = $this->request->data;
        $this->loadModel('UserLeave');
        $arr = array();
        if (isset($data['id'])) {
            $this->UserLeave->id = $data['id'];
        }
        $data['company_id'] = SES_COMP;
        $data['dt_created'] = GMT_DATETIME;
        if ($this->UserLeave->save($data)) {
            $arr['success'] = 1;
        } else {
            $arr['success'] = 0;
        }
        echo json_encode($arr);
        exit;
    }

    function update_vacation() {
        $this->layout = 'ajax';
        $id = $this->request->data['id'];
        $this->loadModel('UserLeave');
        $leavearr = $this->UserLeave->find('first', array('conditions' => array('UserLeave.id' => $id, 'UserLeave.company_id' => SES_COMP)));
        $this->set(compact('leavearr'));
        $this->render('Elements/popup_user_leave_form');
    }

    function cancel_vacation() {
        $this->layout = 'ajax';
        $id = $this->request->data['leave_id'];
        $this->loadModel('UserLeave');
        if ($this->UserLeave->delete($id)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    function getlastLog($projUniq = '', $taskid = '') {
        $this->layout = 'ajax';
        $proj_uniq_id = !empty($this->data['projUniq']) ? $this->data['projUniq'] : $projUniq;
        $taskid = !empty($this->data['taskid']) ? $this->data['taskid'] : $taskid;
        if ($proj_uniq_id != 'all') {
            $this->loadModel('Timelog.LogTime');
            $this->LogTime->bindModel(array('belongsTo' => array('Project')));
            $cond = array('Project.uniq_id' => $proj_uniq_id, 'Project.isactive' => 1, 'LogTime.created >' => date('Y-m-d 00:00:00'));
            $cond1 = array('Project.uniq_id' => $proj_uniq_id, 'Project.isactive' => 1);
            if (!empty($taskid)) {
                $cond['LogTime.task_id'] = $taskid;
                $cond1['LogTime.task_id'] = $taskid;
            }
            if (SES_TYPE == 3) {
                $cond['LogTime.user_id'] = SES_ID;
                $cond1['LogTime.user_id'] = SES_ID;
            }
            $this->loadModel('Timelog.LogTime');
            $projArr = $this->LogTime->find('all', array('conditions' => $cond, 'fields' => array('LogTime.created', 'LogTime.total_hours'), 'order' => array('LogTime.created DESC')));
            $this->LogTime->create();
            $this->LogTime->bindModel(array('belongsTo' => array('Project')));
            $latedittime = $this->LogTime->find('first', array('conditions' => $cond1, 'fields' => array('LogTime.created'), 'order' => array('LogTime.created DESC')));
            $total_hour = 0;
            $total_hour_format = '0 hr(s)';
            $created_on = '';
            if (count($projArr) > 0) {
                foreach ($projArr as $k => $v) {
                    $total_hour += intval($v['LogTime']['total_hours']);
                }
            }
            $total_hour_format = floor($total_hour / 3600) . ' hr(s) ';
            $mins = round(($total_hour % 3600) / 60);
            if ($mins > 0) {
                $total_hour_format .= $mins . " min(s) ";
            }
            $view = new View($this);
            $dt = $view->loadHelper('Datetime');
            $tz = $view->loadHelper('Tmzone');
            if (isset($latedittime['LogTime']['created'])) {
                $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
                $locDT1 = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $latedittime['LogTime']['created'], "datetime");
                $created_on = $dt->facebook_style_date_time($locDT1, $curDateTz);
                if (!empty($projUniq)) {
                    $log_time['logged'] = $total_hour_format;
                    $log_time['last_entry'] = $created_on;
                    return $log_time;
//                    return "Logged: <b>{$total_hour_format} today</b>. Last entry: <b>{$created_on}</b>";
                } else {
                    echo "Logged: <b>{$total_hour_format} today</b>. Last entry: <b>{$created_on}</b>";
                }
            } else {
                if (!empty($projUniq)) {
                    $log_time['logged'] = $total_hour_format;
                    $log_time['last_entry'] = $created_on;
                    return $log_time;
//                    return "Logged: <b>{$total_hour_format} today</b>. Last entry: <b>none</b>";
                } else {
                    echo "Logged: <b>{$total_hour_format} today</b>. Last entry: <b>none</b>";
                }
            }
        }
        if (!empty($projUniq)) {
            return true;
        } else {
            exit;
        }
    }

}
