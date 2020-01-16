<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 's3', array('file' => 's3' . DS . 'S3.php'));

App::import('Vendor', 'ElephantIO', array('file' => 'ElephantIO' . DS . 'Client.php'));

use ElephantIO\Client as ElephantIOClient;

class DashboardsController extends DashboardAppController {

    public $name = 'Dashboards';
    public $components = array('Format', 'Postcase', 'Sendgrid', 'Tmzone');
    public $helpers = array('Format');

    function project_rag() {
        $this->layout = 'ajax';
        $usr_cndn = $logUsr_cndn = '';
        $status_lists = array();
        $this->loadModel('Project');
        $this->Project->recursive = -1;
        $conditions = array('Project.company_id' => SES_COMP, 'Project.isactive' => 1, 'Project.estimated_hours NOT IN ' => array("0.0", 'NULL'), 'Project.start_date !=' => "NULL");
        #echo"<pre>";print_r($this->data);exit;
        if (SES_TYPE == 3) {
            $this->loadModel('ProjectUser');
            $usr_prjct = $this->ProjectUser->find('all', array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'ProjectUser.user_id' => SES_ID), 'fields' => array('ProjectUser.project_id')));
            $prjId = Hash::extract($usr_prjct, '{n}.ProjectUser.project_id');
            $conditions = array_merge($conditions, array('Project.id' => $prjId,));
        }
        $ordr = "Project.name ASC ";
        if ($this->data) {
            if ($this->data['type'] == 'start_date') {
                $ordr = "Project.start_date " . $this->data['sort'];
            } else {
                $ordr = "Project.end_date " . $this->data['sort'];
            }
        } else {
            $ordr = 'Project.name ASC';
        }

        $projects = $this->Project->find('all', array('joins' => array(
                array('table' => 'invoice_customers',
                    'alias' => 'InvoiceCustomer',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Project.invoice_customer_id = InvoiceCustomer.id'
                    ))), 'conditions' => $conditions, 'fields' => array('Project.id', 'Project.name', 'Project.estimated_hours', 'Project.budget', 'Project.cost_approved', 'Project.hourly_rate', 'Project.min_range_percent', 'Project.max_range_percent', 'Project.start_date', 'Project.end_date', 'Project.dt_created', 'Project.currency', 'InvoiceCustomer.organization AS Company_name'), 'order' => $ordr));
//        echo"<pre>";print_r($conditions);exit;
        if ($projects) {
            foreach ($projects as $kp => $vp) {
                $projects[$kp]['Project']['client_company_name'] = $vp['InvoiceCustomer']['Company_name'];
            }
            $project_ids = Hash::extract($projects, '{n}.Project.id');
            $project_dets = Hash::combine($projects, '{n}.Project.id', '{n}.Project');
            if (defined('TSG') && TSG == 1) {
                $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
                foreach ($statuses_list as $k => $v) {
                    $status_lists[] = $v['s']['status_id'];
                }
                array_push($status_lists, '3');
            } else {
                $status_lists = array('3');
            }


            if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
                $this->loadModel('LogTime');
                if (SES_TYPE < 3) {
                    $usr_cond = "LogTime.user_id >0";
                    $usr_rte_cond = "Rate.user_id >0";
                } elseif (SES_TYPE == 3) {
                    $usr_cond = "LogTime.user_id = " . SES_ID;
                    $usr_rte_cond = "Rate.user_id = " . SES_ID;
                }
                $projQry = " AND LogTime.project_id IN (" . implode(',', $project_ids) . ") ";
                // $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
                $log_sql = "SELECT SUM(LogTime.total_hours) AS spent_hours, Project.name, Project.id "
                        . "FROM log_times AS LogTime "
                        . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                        . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id AND DATE(LogTime.start_datetime) >= DATE(Project.start_date) AND DATE(LogTime.start_datetime) <= DATE(Project.end_date)"
                        . "WHERE Easycase.isactive=1 AND " . $usr_cond . " " . $projQry . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                        . "GROUP BY LogTime.project_id,hours ORDER BY LogTime.project_id ASC";

                $logtime = $this->Easycase->query($log_sql);

                foreach ($logtime as $k => $val) {
                    if (array_key_exists($val['Project']['id'], $project_dets)) {
                        $project_dets[$val['Project']['id']]['spent_hours'] = (int) ($val[0]['spent_hours'] / 3600);
                    }
                }
                $projRateQry = " AND Rate.project_id IN (" . implode(',', $project_ids) . ") ";
                $ind_log_sql = "SELECT SUM(LogTime.total_hours) AS spent_hours,LogTime.user_id ,Project.id "
                        . "FROM log_times AS LogTime "
                        . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id AND DATE(LogTime.start_datetime) >= DATE(Project.start_date) AND DATE(LogTime.start_datetime) <= DATE(Project.end_date)"
                        . "WHERE " . $usr_cond . " " . $projQry . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 AND LogTime.is_billable =1 "
                        . "GROUP BY LogTime.user_id,LogTime.project_id ORDER BY LogTime.project_id ASC";
                $ind_logtime = $this->LogTime->query($ind_log_sql);

                $prjct_tme_ary = array();
                foreach ($ind_logtime as $kl => $kv) {
                    $prjct_tme_ary[$kv['Project']['id']][$kv['LogTime']['user_id']]['user_id'] = $kv['LogTime']['user_id'];
                    $prjct_tme_ary[$kv['Project']['id']][$kv['LogTime']['user_id']]['total_hr'] = (int) ($kv[0]['spent_hours'] / 3600);
                }
                #echo "<pre>";print_r($prjct_tme_ary);print_r($prjct_tme_ary);exit;

                $this->loadModel('RoleRate');
                $rate_sql = "SELECT Rate.rate,Rate.actual_rate,Rate.user_id,Project.id "
                        . "From role_rates AS Rate "
                        . "LEFT JOIN projects AS Project ON Rate.project_id= Project.id "
                        . "WHERE " . $usr_rte_cond . " " . $projRateQry . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                        . "GROUP BY Rate.user_id,Rate.project_id ORDER BY Rate.project_id ASC";

                $rates = $this->RoleRate->query($rate_sql);

                $prjct_rate_ary = array();
                foreach ($rates as $kr => $vr) {
                    /* $prjct_rate_ary[$vr['Project']['id']][$vr['Rate']['user_id']]['user_id'] = $vr['Rate']['user_id'] ;
                      $prjct_rate_ary[$vr['Project']['id']][$vr['Rate']['user_id']]['rate'] = $vr['Rate']['rate']; */
                    $prjct_rate_ary[$vr['Project']['id']][$vr['Rate']['user_id']]['actual_rate'] = $vr['Rate']['actual_rate'];
                }
                $final_rates = array();
                foreach ($prjct_tme_ary as $kp => $pv) {
                    foreach ($pv as $ka => $vra) {
                        if (array_key_exists($kp, $prjct_rate_ary)) {
                            if (array_key_exists($ka, $prjct_rate_ary[$kp])) {
                                // $final_rates[$kp][$ka]['rates'] = (int)($vra['total_hr'] * $prjct_rate_ary[$kp][$ka]['rate']) ;
                                // $actl_rate = (isset() && !empty)
                                $final_rates[$kp][$ka]['actual_cost'] = (int) ($vra['total_hr'] * $prjct_rate_ary[$kp][$ka]['actual_rate']);
                            } else {
                                $final_rates[$kp][$ka]['actual_cost'] = !empty($project_dets[$kp]['hourly_rate']) ? (int) ($vra['total_hr'] * $project_dets[$kp]['hourly_rate']) : 0;
                            }
                        } else {
                            $final_rates[$kp][$ka]['actual_cost'] = !empty($project_dets[$kp]['hourly_rate']) ? (int) ($vra['total_hr'] * $project_dets[$kp]['hourly_rate']) : 0;
                        }
                    }
                }
                #echo "<pre>";print_r($prjct_rate_ary);print_r($prjct_tme_ary);exit;
                foreach ($final_rates as $kf => $vf) {
                    foreach ($vf as $kvf => $vvf) {
                        if (array_key_exists($kf, $project_dets)) {
                            // $project_dets[$kf]['rates'] += $vvf['rates'];
                            $project_dets[$kf]['actual_cost'] += $vvf['actual_cost'];
                        }
                    }
                }
                #echo "<pre>";print_r($project_dets);print_r($ind_logtime);exit;
                $this->Easycase->bindModel(
                        array('hasMany' => array(
                                'LogTime' => array(
                                    'className' => 'LogTime',
                                    'foreignKey' => 'task_id'
                                )
                            )
                        )
                );
                $this->Easycase->Behaviors->attach('Containable');
                $closed_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $project_ids, 'Easycase.isactive' => 1, 'Easycase.istype' => 1, 'Easycase.legend' => $status_lists), 'fields' => array('COUNT(Easycase.id) as completed_tasks', 'Easycase.project_id'), 'group' => array('Easycase.project_id')));
            } else {
                $this->loadModel('Easycase');
                if (SES_TYPE == 3) {
                    $logUsr_cndn = array('Easycase.user_id' => SES_ID);
                } else {
                    $logUsr_cndn = array('Easycase.user_id >' => 0);
                }
                $spent_times = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $project_ids, 'Easycase.isactive' => 1, $logUsr_cndn), 'fields' => array('SUM(Easycase.hours) as spent_hours', 'Easycase.project_id'), 'group' => 'Easycase.project_id'));
                foreach ($spent_times as $k => $val) {
                    if (array_key_exists($val['Easycase']['project_id'], $project_dets)) {
                        $project_dets[$val['Easycase']['project_id']]['spent_hours'] = $val[0]['spent_hours'];
                    }
                }
            }
            $total_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $project_ids, 'Easycase.isactive' => 1, 'Easycase.istype' => 1), 'fields' => array('COUNT(Easycase.id) as total_tasks', 'Easycase.project_id'), 'group' => array('Easycase.project_id')));
            #$log = $this->Easycase->getDataSource()->getLog(false, false);debug(end($log['log']));exit;
            foreach ($total_tasks as $k => $val) {
                if (array_key_exists($val['Easycase']['project_id'], $project_dets)) {
                    $project_dets[$val['Easycase']['project_id']]['total_tasks'] = $val[0]['total_tasks'];
                }
            }
            foreach ($closed_tasks as $k => $val) {
                if (array_key_exists($val['Easycase']['project_id'], $project_dets)) {
                    $project_dets[$val['Easycase']['project_id']]['closed_tasks'] = $val[0]['completed_tasks'];
                    if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
                        $project_dets[$val['Easycase']['project_id']]['completed_hour'] = $this->LogTime->find('all', array('conditions' => array('LogTime.task_id' => $val['Easycase']['id']), 'fields' => array('SUM(LogTime.total_hours) AS completed_hr'), 'group' => 'LogTime.task_id'));
                    }
                    //$project_dets[$val['Easycase']['project_id']]['completed_hour'] = $val['LogTime'][]
                }
            }
            $view = new View($this);
            $tz = $view->loadHelper('Tmzone');
            $frmt = $view->loadHelper('Format');
            $today = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
            $project_dets['current_date'] = $today;
            #echo "<pre>";print_r($project_dets);exit;
            $project_dets = $this->Format->getRAGStatus($project_dets);
            #echo "<pre>";print_r($project_dets);exit;
            if ($this->params->query['type'] == 'export') {
                $print_to_csv = "Project Name,Client Name,Start Date,End Date,Estimated Hour(s)/Hour(s) Spent,Closed Task(s) v/s Total Task(s),Budget,Cost Approved,Cost Incurred,Cost Remainig,RAG Time Status,RAG Cost Status\n";
                foreach ($project_dets as $k => $project) {
                    $strt_date = (isset($project['start_date']) && !empty($project['start_date'])) ? $frmt->chngdate_csv($project['start_date']) : $frmt->chngdate_csv($project['dt_created']);
                    $end_date = (isset($project['end_date']) && !empty($project['end_date'])) ? $frmt->chngdate_csv($project['end_date']) : 'No end date';
                    $budgets = isset($project['budget']) && $project['budget'] != '0.00' ? $project['budget'] : 0;
                    $cost_approveds = isset($project['cost_approved']) && $project['cost_approved'] != '0.00' && !empty($project['cost_approved']) ? $project['cost_approved'] : 0;
                    $actual_cost = isset($project['actual_cost']) && $project['actual_cost'] != '0.00' ? $project['actual_cost'] : 0;

                    $cost_approveds = $cost_approveds != 0 ? $cost_approveds : $budgets;
                    $cost_balance = ($cost_approveds - $actual_cost);
                    $estimated_hr = isset($project['estimated_hours']) && $project['estimated_hours'] != '0.0' ? $project['estimated_hours'] : '0.0';
                    $hour_spent = isset($project['spent_hours']) ? $project['spent_hours'] : '0.0';
                    $tot_tsks = isset($project['total_tasks']) ? $project['total_tasks'] : '0';
                    $closed_tsks = isset($project['closed_tasks']) ? $project['closed_tasks'] : '0';
                    $budget = isset($project['budget']) && $project['budget'] != '0.00' ? $project['budget'] : '0.00';
                    $cost_approved = isset($project['cost_approved']) && $project['cost_approved'] != '0.00' ? $project['cost_approved'] : '0.00';
                    $cost_incurred = isset($project['actual_cost']) && $project['actual_cost'] != '0.00' ? $project['actual_cost'] : '0.00';
                    $clnt_cmpny_name = !empty($project['client_company_name']) ? $project['client_company_name'] : 'None';
                    $print_to_csv .= $project['name'] . "," . $clnt_cmpny_name . "," . '"' . $strt_date . '"' . "," . '"' . $end_date . '"' . "," . $estimated_hr . "/" . $hour_spent . "," . $closed_tsks . " out of " . $tot_tsks . "," . $budget . "," . $cost_approved . "," . $cost_incurred . "," . $cost_balance . "," . $project['rag_title'] . "," . $project['rag_title2'] . "\n";
                }

                $filename = "Dashboard_RAG_Report_" . date("m-d-Y_H-i-s", time());

                header("Content-type: application/vnd.ms-excel");
                header("Content-disposition: csv" . date("Y-m-d") . ".csv");
                header("Content-disposition: filename=" . $filename . ".csv");

                print $print_to_csv;
                exit;
            } else {
//                print_r($project_dets); exit;
                $this->set('projects', $project_dets);
            }
        } else {
            echo "<div class='mytask_txt'>" . __('Oops! No projects are having an estimated hour or a Start date ! RAG report cannot be found out') . "?</div>";
            exit;
        }
    }

    public function rag_cost_report() {
        $this->layout = "ajax";
        $this->loadModel('RoleRate');
        $this->loadModel('Project');
        $this->Project->recursive = -1;
        $conditions = array('Project.company_id' => SES_COMP, 'Project.isactive' => 1, 'OR' => array("Project.budget NOT IN " => array("0.0", 'NULL'), "Project.cost_approved NOT IN " => array("0.0", 'NULL')));
        if (SES_TYPE == 3) {
            $this->loadModel('ProjectUser');
            $usr_prjct = $this->ProjectUser->find('all', array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'ProjectUser.user_id' => SES_ID), 'fields' => array('ProjectUser.project_id')));
            $prjId = Hash::extract($usr_prjct, '{n}.ProjectUser.project_id');
            $conditions = array_merge($conditions, array('Project.id' => $prjId,));
        }
        // $this->Project->bindModel(array('belongsTo' => array('InvoiceCustomer' => array('className' => 'InvoiceCustomer', 'foreignKey' => 'invoice_customer_id'))));
        // $this->Project->bindModel(array('belongsTo'=>array('InvoiceCustomer'=>array('className'=>'InvoiceCustomer','foreignKey'=>'invoice_customer_id','conditions'=>array('Project.invoice_customer_id'=>'InvoiceCustomer.id')))));
        $ordr = "Project.name ASC";
        $projects = $this->Project->find('all', array('joins' => array(
                array('table' => 'invoice_customers',
                    'alias' => 'InvoiceCustomer',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Project.invoice_customer_id = InvoiceCustomer.id'
                    ))), 'conditions' => $conditions, 'fields' => array('Project.id', 'Project.name', 'Project.estimated_hours', 'Project.budget', 'Project.cost_approved', 'Project.hourly_rate', 'Project.min_range_percent', 'Project.max_range_percent', 'Project.start_date', 'Project.end_date', 'Project.dt_created', 'Project.currency', 'InvoiceCustomer.organization AS Company_name'), 'order' => $ordr));
        // debug($this->Project->find('all', array('conditions' => $conditions, 'order'=>$ordr)));exit;
        // echo "<pre>";print_r($projects);exit;
        if ($projects) {
            foreach ($projects as $kp => $vp) {
                $projects[$kp]['Project']['client_company_name'] = $vp['InvoiceCustomer']['Company_name'];
            }
            $project_ids = Hash::extract($projects, '{n}.Project.id');
            $project_dets = Hash::combine($projects, '{n}.Project.id', '{n}.Project');
            // $project_dets = Hash::combine($project_dets, '{n}.InvoiceCustomer');

            if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
                $this->loadModel('LogTime');
                if (SES_TYPE < 3) {
                    $usr_cond = "LogTime.user_id >0";
                    $usr_rte_cond = "Rate.user_id >0";
                } elseif (SES_TYPE == 3) {
                    $usr_cond = "LogTime.user_id = " . SES_ID;
                    $usr_rte_cond = "Rate.user_id = " . SES_ID;
                }
                $projQry = " AND LogTime.project_id IN (" . implode(',', $project_ids) . ") ";
                $projRateQry = " AND Rate.project_id IN (" . implode(',', $project_ids) . ") ";
                $log_sql = "SELECT SUM(LogTime.total_hours) AS spent_hours,LogTime.user_id ,Project.id "
                        . "FROM log_times AS LogTime "
                        . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id "
                        . "WHERE " . $usr_cond . " " . $projQry . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                        . "GROUP BY LogTime.user_id,LogTime.project_id ORDER BY LogTime.project_id ASC";
                $logtime = $this->LogTime->query($log_sql);
                $prjct_tme_ary = array();
                foreach ($logtime as $kl => $kv) {
                    $prjct_tme_ary[$kv['Project']['id']][$kv['LogTime']['user_id']]['user_id'] = $kv['LogTime']['user_id'];
                    $prjct_tme_ary[$kv['Project']['id']][$kv['LogTime']['user_id']]['total_hr'] = (int) ($kv[0]['spent_hours'] / 3600);
                }
                //echo "<pre>";print_r($prjct_tme_ary);print_r($logtime);exit;
                $rate_sql = "SELECT Rate.rate,Rate.actual_rate,Rate.user_id,Project.id "
                        . "From role_rates AS Rate "
                        . "LEFT JOIN projects AS Project ON Rate.project_id= Project.id "
                        . "WHERE " . $usr_rte_cond . " " . $projRateQry . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                        . "GROUP BY Rate.user_id,Rate.project_id ORDER BY Rate.project_id ASC";

                $rates = $this->RoleRate->query($rate_sql);
                $prjct_rate_ary = array();
                foreach ($rates as $kr => $vr) {
                    $prjct_rate_ary[$vr['Project']['id']][$vr['Rate']['user_id']]['user_id'] = $vr['Rate']['user_id'];
                    $prjct_rate_ary[$vr['Project']['id']][$vr['Rate']['user_id']]['rate'] = $vr['Rate']['rate'];
                    $prjct_rate_ary[$vr['Project']['id']][$vr['Rate']['user_id']]['actual_rate'] = $vr['Rate']['actual_rate'];
                }
                $final_rates = array();
                foreach ($prjct_tme_ary as $kp => $pv) {
                    foreach ($pv as $ka => $vra) {
                        if (array_key_exists($kp, $prjct_rate_ary)) {
                            if (array_key_exists($ka, $prjct_rate_ary[$kp])) {
                                $hrly_rate = ($prjct_rate_ary[$kp][$ka]['rate'] == 0 || $prjct_rate_ary[$kp][$ka]['rate'] == "" ) ? $prjct_rate_ary[$kp][$ka]['actual_rate'] : $prjct_rate_ary[$kp][$ka]['rate'];
                                $final_rates[$kp][$ka]['rates'] = (int) ($vra['total_hr'] * $hrly_rate);
                                $final_rates[$kp][$ka]['actual_cost'] = (int) ($vra['total_hr'] * $prjct_rate_ary[$kp][$ka]['actual_rate']);
                            }
                        }
                    }
                }
                foreach ($final_rates as $kf => $vf) {
                    foreach ($vf as $kvf => $vvf) {
                        if (array_key_exists($kf, $project_dets)) {
                            $project_dets[$kf]['rates'] += $vvf['rates'];
                            $project_dets[$kf]['actual_cost'] += $vvf['actual_cost'];
                        }
                    }
                }
                #echo "<pre>";print_r($project_dets);exit;
                if ($this->params->query['type'] == "export") {
                    $print_csv = "Project Name,'Client Name',Cost approved,Rate/Revenue,Cost to customer,Profit \n";
                    foreach ($project_dets as $k => $val) {
                        $cost_approved = empty($val['cost_approved']) ? $val['budger'] : $val['cost_approved'];
                        $rate = isset($val['rates']) && !empty($val['rates']) ? $val['rates'] : 0;
                        $actual_cost = isset($val['actual_cost']) && !empty($val['actual_cost']) ? $val['actual_cost'] : 0;
                        $profit = (int) ($val['rates'] - $val['actual_cost']);
                        $clnt_cmnpy_name = !empty($val['client_company_name']) ? $val['client_company_name'] : 'None';
                        $print_csv .= $val['name'] . "," . $clnt_cmnpy_name . "," . $cost_approved . "," . $rate . "," . $actual_cost . "," . $profit . "\n";
                    }
                    $filename = "Dashboard_Rag_cost_report" . date("m-d-Y_H-i-s", time());
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
                    header("Content-disposition: filename=" . $filename . ".csv");
                    print $print_csv;
                    exit;
                } else {
                    $this->set("projects", $project_dets);
                }

                #echo "<pre>";print_r($project_dets);print_r($final_rates);exit;print_r($prjct_rate_ary);print_r($project_dets);exit;
            }
        } else {
            echo "<div class='mytask_txt'>" . __('Oops! No projects are having a budget  ! Cost report cannot be found out') . "</div>";
            exit;
        }
    }

    public function timelog_chart1() {
        $this->layout = 'ajax';
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        if (isset($this->params->query['type']) && !empty($this->params->query['type'])) {
            $time_fltr = $this->params->query['time_flt'] ? $this->params->query['time_flt'] : 'last30days';
            $users_id = $this->params->query['user_id'];
            $project_id = $this->params->query['project_id'];
        } else {
            $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
            $users_id = $this->data['user_id'];
            $project_id = $this->data['prjct_id'];
        }
        // echo "<pre>";print_r($this->params); print_r($this->params->query);exit;
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        // $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
        $dates = $this->Format->date_filter($time_fltr, $curDateTz);
        $this->set('time_flt', $this->data['time_flt']);
        $this->set('user_id', $this->data['user_id']);
        $this->set('project_id', $this->data['prjct_id']);

        $days = (strtotime($dates['enddt']) - strtotime($dates['strddt'])) / (60 * 60 * 24);
        //echo "<pre>";print_r($dates);exit;
        $x = floor($days);
        //echo $x;exit;
        if ($x < 7) {
            $interval = 1;
        } elseif ($x > 7 && $x <= 30) {
            $interval = 8;
        } elseif ($x > 30 && $x < 90) {
            $interval = 16;
        } else {
            $interval = 24 * 3600 * 1000 * 31;
        }
        $this->set('tinterval', $interval);
        $dt_arr = array();
        $dts_arr = array();
        $view = new View($this);
        $frmt = $view->loadHelper('Format');
        for ($i = 0; $i <= $x; $i++) {
            $m = " +" . $i . "day";
            $dt = date('Y-m-d', strtotime(date("Y-m-d", strtotime($dates['strddt'])) . $m));
            $dts = $frmt->chngdate(date("Y-m-d H:i:s", strtotime($dates['strddt'])) . $m);
            $times = explode(" ", GMT_DATETIME);
            array_push($dt_arr, $dt);
            array_push($dts_arr, $dts);
        }
        $this->set('dt_arr', json_encode($dts_arr));
        //echo "<pre>";print_r($dts_arr);exit;
        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            $this->loadModel('LogTime');
            $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
            // if($this->data){
            if ($users_id != 'all') {
                $user_id = 'ProjectUser.user_id=' . $users_id;
                $user_cond = "AND LogTime.user_id =" . $users_id;
            } else {
                $user_id = 'ProjectUser.user_id > 0';
                $user_cond = "AND LogTime.user_id > 0";
            }
            if ($project_id == 'all') {
                $projQry = " AND LogTime.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $user_id . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1') ";
            } else {
                $projQry = " AND LogTime.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $user_id . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id= " . $project_id . " AND Project.isactive='1') ";
            }
            /* find total billable and non-billable time */
            $count_sql = 'SELECT sum(total_hours) as secds,is_billable,DATE(LogTime.start_datetime) AS date,Project.name AS project_name,Project.id,User.name AS user_name '
                    . 'FROM log_times AS `LogTime` '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . 'LEFT JOIN projects AS Project ON Project.id=LogTime.project_id '
                    . 'LEFT JOIN users AS User ON User.id=LogTime.user_id '
                    . 'WHERE LogTime.is_billable = 1 AND Easycase.isactive =1 ' . $projQry . ' ' . $dateCond . $user_cond . '  '
                    . 'GROUP BY DATE(LogTime.start_datetime)  '
                    . 'UNION '
                    . 'SELECT sum(total_hours) as secds, is_billable,DATE(LogTime.start_datetime) AS date,Project.name AS project_name,Project.id,User.name AS user_name '
                    . 'FROM log_times AS LogTime '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . 'LEFT JOIN projects AS Project ON Project.id=LogTime.project_id '
                    . 'LEFT JOIN users AS User ON User.id=LogTime.user_id '
                    . 'WHERE LogTime.is_billable = 0 AND Easycase.isactive =1 ' . $projQry . ' ' . $dateCond . $user_cond . '  '
                    . 'GROUP BY DATE(LogTime.start_datetime) ';
            $cntlog = $this->LogTime->query($count_sql);
            $logtimes = array();

            if (is_array($cntlog) && count($cntlog) > 0) {
                $billablearr = array();
                $nonbillablearr = array();
                foreach ($cntlog as $k => $val) {
                    if ($val[0]['is_billable'] == 1) {
                        $billablearr[$val[0]['date']] = $val[0];
                        $logtimes[$val[0]['date']]['project_name'] = $val[0]['project_name'];
                        $logtimes[$val[0]['date']]['user_name'] = $val[0]['user_name'];
                        $logtimes[$val[0]['date']]['billablehr'] = round($val[0]['secds'] / 3600, 2);
                    } else {
                        $nonbillablearr[$val[0]['date']] = $val[0];
                        $logtimes[$val[0]['date']]['project_name'] = $val[0]['project_name'];
                        $logtimes[$val[0]['date']]['user_name'] = $val[0]['user_name'];
                        $logtimes[$val[0]['date']]['non_billablehr'] = round($val[0]['secds'] / 3600, 2);
                    }
                }
                foreach ($dt_arr as $key => $dt) {
                    $nonbillable_series['name'] = __('Non-billable',true);
                    $nonbillable_series['color'] = '#90C42C';  //'#8dc63f';
                    if (array_key_exists($dt, $nonbillablearr)) {
                        $nonbillable_series['data'][] = round($nonbillablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $nonbillable_series['data'][] = 0;
                    }
                    $billable_series['name'] = __('Billable',true);
                    $billable_series['color'] = '#00A2FF'; //'#0071bd';
                    if (array_key_exists($dt, $billablearr)) {
                        $billable_series['data'][] = round($billablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $billable_series['data'][] = 0;
                    }
                }
                $series[0] = $nonbillable_series;
                $series[1] = $billable_series;
                if ($this->params->query['type'] == "export") {
                    $print_csv = "Project Name,Resource Name,Date,Billable Hour,Non Billable Hour,Total Hour \n";
                    foreach ($logtimes as $k => $val) {
                        $billbale_hr = isset($val['billablehr']) && !empty($val['billablehr']) ? $val['billablehr'] : '0';
                        $unbillable_hr = isset($val['non_billablehr']) && !empty($val['non_billablehr']) ? $val['non_billablehr'] : '0';
                        $total_hr = (int) ($billbale_hr + $unbillable_hr) . ' hours';
                        $print_csv .= $val['project_name'] . "," . $val['user_name'] . ",".'"' . $frmt->chngdate_csv($k) . '"'."," . $billbale_hr . "," . $unbillable_hr . "," . $total_hr . "\n";
                    }

                    $filename = "Dashboard_timelog_" . date("m-d-Y_H-i-s", time());
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
                    header("Content-disposition: filename=" . $filename . ".csv");
                    print $print_csv;
                    exit;
                } else {
                    $this->set('series', json_encode($series));
                    $this->set('log_times', $logtimes);
                }
            } else {
                $user_id = $this->request->data['user_id'];
                $project_id = $this->request->data['prjct_id'];
                $msg = "<div class='mytask_txt'><p>";
                $msg .= __("Oops!", true);
                if ($user_id != 'all') {
                    $usrname = $this->Format->getUserShortName($user_id);
                    $msg .= __($usrname['User']['name'] . " has not entered time log for his tasks ", true);
                } else {
                    $msg .= __("No user has logged time", true);
                }
                if ($project_id != 'all') {
                    $prjname = $this->Format->getProjectName($project_id);
                    $msg .= __(" on Project " . $prjname, true);
                }
                $msg .= __(" !");
                $msg .= "<br />";
                $msg .= __("What's happening ?", true);
                $msg .= "</p></div>";
                echo $msg;
                exit;
            }
        } else {
            if ($this->data) {
                $this->loadModel('Easycase');
                if ($this->data['user_id'] != 'all') {
                    $user_id = 'ProjectUser.user_id=' . $this->data['user_id'];
                } else {
                    $user_id = 'ProjectUser.user_id > 0';
                }
                $dateCond = " AND DATE(Easycase.dt_created) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
                if ($this->data['prjct_id'] == 'all') {
                    $count_sql = 'SELECT SUM(Easycase.hours) as secds, DATE(Easycase.dt_created) AS date '
                            . 'FROM easycases AS Easycase '
                            . 'WHERE Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser, projects As Project WHERE ' . $user_id . ' AND ProjectUser.company_id=' . SES_COMP . ' AND ProjectUser.user_id=' . SES_ID . ' AND ProjectUser.project_id=Project.id AND Project.isactive=1) '
                            . 'AND Easycase.isactive=1 ' . $dateCond . ' GROUP BY Easycase.dt_created';
                } else {
                    $count_sql = 'SELECT SUM(Easycase.hours) as secds, DATE(Easycase.dt_created) AS date '
                            . 'FROM easycases AS Easycase '
                            . 'WHERE Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser, projects As Project WHERE ' . $user_id . ' AND ProjectUser.company_id=' . SES_COMP . ' AND ProjectUser.user_id=' . SES_ID . ' AND ProjectUser.project_id=' . $this->data["prjct_id"] . ' AND Project.isactive=1) '
                            . 'AND Easycase.isactive=1 ' . $dateCond . ' GROUP BY Easycase.dt_created';
                }
                $cntlog = $this->Easycase->query($count_sql);
                if (!empty($cntlog)) {
                    $billablearr = array();
                    foreach ($cntlog as $k => $val) {
                        if ($val[0]['secds'] != '') {
                            $billablearr[$val[0]['date']] = $val[0]['secds'];
                        }
                    }
                    foreach ($dt_arr as $key => $dt) {
                        $billable_series['name'] = __('Billable',true);
                        $billable_series['color'] = '#00A2FF';
                        if (array_key_exists($dt, $billablearr)) {
                            $billable_series['data'][] = (int) $billablearr[$dt];
                        } else {
                            $billable_series['data'][] = 0;
                        }
                    }
                    $series[0] = $billable_series;
                    $this->set('series', json_encode($series));
                } else {
                    $user_id = $this->request->data['user_id'];
                    $project_id = $this->request->data['prjct_id'];
                    $msg = "<div class='mytask_txt'><p>";
                    $msg .= __("Oops!", true);
                    if ($user_id != 'all') {
                        $usrname = $this->Format->getUserShortName($user_id);
                        $msg .= __($usrname['User']['name'] . " has not entered time log for tasks ", true);
                    } else {
                        $msg .= __("No user has logged time", true);
                    }
                    if ($project_id != 'all') {
                        $prjname = $this->Format->getProjectName($project_id);
                        $msg .= __(" on Project " . $prjname, true);
                    }
                    $msg .= __(" !");
                    $msg .= "<br />";
                    $msg .= __("What's happening ?", true);
                    $msg .= "</p></div>";
                    echo $msg;
                    exit;
                }
            }
        }
    }

    public function timelog_resource_chart() {
        $this->layout = 'ajax';
        $this->loadModel('User');
        $this->loadModel('ProjectUser');
        $this->User->recursive = -1;

        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
        $dates = $this->Format->date_filter($time_fltr, $curDateTz);
        $project_id = $this->data['prjct_id'];

        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            $this->loadModel('LogTime');

            if ($this->data) {
                if ($this->data['prjct_id'] == 'all') {
                    $projQry = " AND LogTime.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1') ";
                    $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1), 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
                } else {
                    $projQry = " AND LogTime.project_id = " . $this->data['prjct_id'];
                    $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1, 'ProjectUser.project_id' => $this->data['prjct_id']), 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
                }
            } else {
                $projQry = "AND LogTime.project_id = " . PROJ_ID;
                $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1, 'ProjectUser.project_id' => $this->data['prjct_id']), 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
            }
            $this->ProjectUser->bindModel(array('belongsTo' => array('User')));
            if (SES_TYPE < 3) {
                $user_list = $this->ProjectUser->find('all', $allcond);
            } else {
                $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1, 'ProjectUser.user_id' => SES_ID), 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
                $user_list = $this->ProjectUser->find('all', $allcond);
            }
            //echo "<pre>";print_r($user_list);exit;
            $user_ids = array();
            $user_name = array();
            foreach ($user_list as $ku => $vu) {
                $user_ids[] = $vu['User']['id'];
                $user_name[] = $vu[0]['Uname'];
            }
            $usr = implode(',', $user_ids);
            $userCond = "AND LogTime.user_id IN (" . $usr . ")";
            $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
            $this->set('user_arr', json_encode($user_name));
            $count_sql = 'SELECT sum(total_hours) as secds,is_billable,DATE(LogTime.start_datetime) AS date ,LogTime.user_id as user_id '
                    . 'FROM log_times AS `LogTime` '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . 'WHERE is_billable = 1 AND Easycase.isactive =1 ' . $projQry . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY LogTime.user_id  '
                    . 'UNION '
                    . 'SELECT sum(total_hours) as secds, is_billable,DATE(LogTime.start_datetime) AS date,LogTime.user_id as user_id '
                    . 'FROM log_times AS LogTime '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . 'WHERE is_billable = 0 AND Easycase.isactive =1 ' . $projQry . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY LogTime.user_id';
            // echo $count_sql;exit;
            $cntlog = $this->LogTime->query($count_sql);
            if (is_array($cntlog) && count($cntlog) > 0) {
                $billablearr = array();
                $nonbillablearr = array();
                foreach ($cntlog as $k => $val) {
                    if ($val[0]['is_billable'] == 1) {
                        $billablearr[$val[0]['user_id']] = $val[0];
                    } else {
                        $nonbillablearr[$val[0]['user_id']] = $val[0];
                    }
                }
                foreach ($user_ids as $key => $dt) {
                    $nonbillable_series['name'] = __('Non-billable',true);
                    $nonbillable_series['color'] = '#90C42C';
                    if (array_key_exists($dt, $nonbillablearr)) {
                        $nonbillable_series['data'][] = round($nonbillablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $nonbillable_series['data'][] = 0;
                    }
                    $billable_series['name'] = __('Billable',true);
                    $billable_series['color'] = '#00A2FF'; //'#4897F1';
                    if (array_key_exists($dt, $billablearr)) {
                        $billable_series['data'][] = round($billablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $billable_series['data'][] = 0;
                    }
                }
                $series[0] = $nonbillable_series;
                $series[1] = $billable_series;
                // echo "<pre>";print_r($series);print_r($user_ids);exit;
                $this->set('series', json_encode($series));
            } else {
                $project_id = $this->request->data['prjct_id'];
                $msg = "<div class='mytask_txt'><p>";
                $msg .= __("Oops!", true);
                if ($project_id != 'all') {
                    $prjname = $this->Format->getProjectName($project_id);
                    $msg .= __("No user has entered time log for tasks on Project " . $prjname, true);
                } else {
                    $msg .= __("No user has entered time log for tasks!", true);
                }
                $msg .= "<br />";
                $msg .= __("What's going on ?");
                $msg .= "</p></div>";
                echo $msg;
                exit;
            }
        } else {
            if ($this->data) {
                $this->loadModel('Easycase');
                $userCond = "AND Easycase.user_id IN (" . $usr . ")";
                $dateCond = " AND DATE(Easycase.dt_created) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
                if ($this->data['prjct_id'] == 'all') {
                    $projQry = " AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1') ";
                } else {
                    $projQry = " AND Easycase.project_id = " . $this->data['prjct_id'];
                }
                $count_sql = 'SELECT SUM(Easycase.hours) as secds,DATE(Easycase.dt_created) AS date ,Easycase.user_id as user_id '
                        . 'FROM easycases AS `Easycase` '
                        . 'WHERE Easycase.isactive =1 ' . $projQry . ' ' . $userCond . ' ' . $dateCond . ' '
                        . 'GROUP BY Easycase.user_id';
                $cntlog = $this->Easycase->query($count_sql);
                if (is_array($cntlog) && count($cntlog) > 0) {
                    $billablearr = array();
                    foreach ($cntlog as $k => $val) {
                        if ($val[0]['secds'] != '') {
                            $billablearr[$val['Easycase']['user_id']] = $val[0]['secds'];
                        }
                    }
                    foreach ($user_ids as $key => $dt) {
                        $billable_series['name'] = 'Billable';
                        $billable_series['color'] = '#00A2FF';
                        if (array_key_exists($dt, $billablearr)) {
                            $billable_series['data'][] = (int) $billablearr[$dt];
                        } else {
                            $billable_series['data'][] = 0;
                        }
                    }
                    $series[0] = $billable_series;
                    $this->set('series', json_encode($series));
                } else {
                    $project_id = $this->request->data['prjct_id'];
                    $msg = "<div class='mytask_txt'><p>";
                    $msg .= __("Oops!", true);
                    if ($project_id != 'all') {
                        $prjname = $this->Format->getProjectName($project_id);
                        $msg .= __("No user has entered time log for tasks on Project " . $prjname, true);
                    } else {
                        $msg .= __("No user has entered time log for tasks!", true);
                    }
                    $msg .= "<br />";
                    $msg .= __("What's going on ?", true);
                    $msg .= "</p></div>";
                    echo $msg;
                    exit;
                }
            }
        }
    }

    public function timelog_project_chart() {
        $this->layout = 'ajax';
        $this->loadModel('ProjectUser');
        $this->loadModel('Project');
        $this->ProjectUser->recursive = -1;
        $this->Project->recursive = -1;

        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
        $dates = $this->Format->date_filter($time_fltr, $curDateTz);
        $user_id = $this->data['user_id'];
        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            $this->loadModel('LogTime');

            $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
            if ($this->data) {
                if ($this->data['user_id'] == 'all') {
                    $userCond = "AND LogTime.user_id > 0";
                    $conditions = array('Project.isactive' => 1);
                } else {
                    $userCond = "AND LogTime.user_id =" . $this->data['user_id'];
                    $prjct_conditions = array('ProjectUser.company_id' => SES_COMP, 'ProjectUser.user_id ' => $this->data['user_id']);
                    $usr_prjct = $this->ProjectUser->find('all', array('conditions' => $prjct_conditions, 'fields' => array('ProjectUser.project_id')));
                    $usr_prjcts = array();
                    foreach ($usr_prjct as $ku => $kv) {
                        $usr_prjcts[] = $kv['ProjectUser']['project_id'];
                    }
                    $conditions = array('Project.id' => $usr_prjcts, 'Project.isactive' => 1);
                }
            } else {
                $userCond = "AND LogTime.user_id =" . SES_ID;
            }
            // echo "<pre>";print_r($usr_prjcts);exit;
            $prjct_list = $this->Project->find('all', array('conditions' => $conditions, 'fields' => array('Project.id', 'Project.name')));
            $prjct_id = array();
            $prjct_name = array();
            $prjct_shrtname = array();
            //echo "<pre>";print_r($prjct_list);exit;
            foreach ($prjct_list as $kp => $kv) {
                $prjct_id[] = $kv['Project']['id'];
                $prjct_name[] = $kv['Project']['name'];
                $prjct_shrtname[] = $kv['Project']['short_name'];
            }
            $prjct = implode(',', $prjct_id);
            $this->set('project_name', json_encode($prjct_name));
            $this->set('project_shrtname', json_encode($prjct_shrtname));
            $prjCond = "AND LogTime.project_id IN (" . $prjct . ")";
            $count_sql = 'SELECT sum(total_hours) as secds,is_billable,DATE(LogTime.start_datetime) AS date ,LogTime.project_id as project_id '
                    . 'FROM log_times AS `LogTime` '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . 'WHERE is_billable = 1 AND Easycase.isactive =1 ' . $prjCond . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY LogTime.project_id  '
                    . 'UNION '
                    . 'SELECT sum(total_hours) as secds, is_billable,DATE(LogTime.start_datetime) AS date,LogTime.project_id as project_id '
                    . 'FROM log_times AS LogTime '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . 'WHERE is_billable = 0 AND Easycase.isactive =1 ' . $prjCond . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY LogTime.project_id';
            $cntlog = $this->LogTime->query($count_sql);
            if (is_array($cntlog) && count($cntlog) > 0) {
                $billablearr = array();
                $nonbillablearr = array();
                foreach ($cntlog as $k => $val) {
                    if ($val[0]['is_billable'] == 1) {
                        $billablearr[$val[0]['project_id']] = $val[0];
                    } else {
                        $nonbillablearr[$val[0]['project_id']] = $val[0];
                    }
                }foreach ($prjct_id as $key => $dt) {
                    $nonbillable_series['name'] = __('Non-billable',true);
                    $nonbillable_series['color'] = '#90C42C';
                    if (array_key_exists($dt, $nonbillablearr)) {
                        $nonbillable_series['data'][$key] = round($nonbillablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $nonbillable_series['data'][$key] = 0;
                    }
                    $billable_series['name'] = __('Billable',true);
                    $billable_series['color'] = '#00A2FF';
                    if (array_key_exists($dt, $billablearr)) {
                        $billable_series['data'][$key] = round($billablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $billable_series['data'][$key] = 0;
                    }
                }
                // arsort($billable_series['data']);
                // arsort($nonbillable_series['data']);
                $series[0] = $nonbillable_series;
                $series[1] = $billable_series;
                #echo "<pre>";print_r($prjct_id);print_r($series);exit;
                $this->set('series', json_encode($series));
            } else {
                $user_id = $this->request->data['user_id'];
                $msg = "<div class='mytask_txt'><p>";
                $msg .= __("Oops!", true);
                if ($user_id != 'all') {
                    $username = $this->Format->getUserShortName($user_id);
                    $msg .= __($username['User']['name'] . " has not entered time log for tasks", true);
                } else {
                    $msg .= __("No user has entered time log for tasks!", true);
                }
                $msg .= "<br />";
                $msg .= __("What's going on ?", true);
                $msg .= "</p></div>";
                echo $msg;
                exit;
            }
        } else {
            $this->loadModel('Easycase');
            $prjCond = "AND Easycase.project_id IN (" . $prjct . ")";
            $dateCond = " AND DATE(Easycase.dt_Created) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
            if ($this->data) {
                if ($this->data['user_id'] == 'all') {
                    $userCond = "AND Easycase.user_id > 0";
                } else {
                    $userCond = "AND Easycase.user_id =" . $this->data['user_id'];
                }
            } else {
                $userCond = "AND Easycase.user_id =" . SES_ID;
            }
            $count_sql = 'SELECT SUM(Easycase.hours) as secds,DATE(Easycase.dt_created) AS date ,Easycase.project_id as project_id '
                    . 'FROM Easycases AS `Easycase` '
                    . 'WHERE Easycase.isactive =1 ' . $prjCond . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY Easycase.project_id';
            $cntlog = $this->Easycase->query($count_sql);
            if (is_array($cntlog) && count($cntlog) > 0) {
                $billablearr = array();
                foreach ($cntlog as $k => $val) {
                    if ($val[0]['secds'] != '') {
                        $billablearr[$val['Easycase']['project_id']] = $val[0]['secds'];
                    }
                }foreach ($prjct_id as $key => $dt) {
                    $billable_series['name'] = 'Billable';
                    $billable_series['color'] = '#00A2FF';
                    if (array_key_exists($dt, $billablearr)) {
                        $billable_series['data'][] = (int) $billablearr[$dt];
                    } else {
                        $billable_series['data'][] = 0;
                    }
                }
                $series[0] = $billable_series;
                $this->set('series', json_encode($series));
            } else {
                $user_id = $this->request->data['user_id'];
                $msg = "<div class='mytask_txt'><p>";
                $msg .= __("Oops!", true);
                if ($user_id != 'all') {
                    $username = $this->Format->getUserShortName($user_id);
                    $msg .= __($username['User']['name'] . " has not entered time log for tasks", true);
                } else {
                    $msg .= __("No user has entered time log for tasks!", true);
                }
                $msg .= "<br />";
                $msg .= __("What's going on ?", true);
                $msg .= "</p></div>";
                echo $msg;
                exit;
            }
        }
    }

    public function star_project_chart() {
        $this->layout = 'ajax';
        $this->loadModel('Status');
        $this->loadModel('Easycase');
        $this->loadModel('Project');
        $this->loadModel('ProjectUser');
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");

        $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'lastweek';
        $dates = $this->Format->date_filter($time_fltr, $curDateTz);
        //print_r($dates);exit;
        /*  if(SES_TYPE < 3){
          $user_list = $this->User->find('list',array('conditions'=>array('User.isactive' => 1),'fileds'=>array('User.id','User.name')));
          }else{
          $user_list = $this->User->find('list',array('conditions'=>array('User.id'=> SES_ID,'User.isactive' => 1),'fileds'=>array('User.id','User.name')));
          } */
        $user_ids = array();
        $user_name = array();
        /* foreach($user_list as $ku => $vu){
          $user_ids[] = $ku;
          $user_name[] = $vu ;
          } */
        if ($this->request->data['prjct_id']) {
            $project_id = $this->request->data['prjct_id'];
        } else {
            $project_id = PROJ_ID;
        }
        $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1, 'ProjectUser.project_id' => $project_id), 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
        $this->ProjectUser->bindModel(array('belongsTo' => array('User')));
        if (SES_TYPE < 3) {
            $user_list = $this->ProjectUser->find('all', $allcond);
        } else {
            $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1, 'ProjectUser.user_id' => SES_ID, 'ProjectUser.project_id' => $project_id), 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
            $user_list = $this->ProjectUser->find('all', $allcond);
        }
        foreach ($user_list as $ku => $vu) {
            $user_ids[] = $vu['User']['id'];
            $user_name[] = $vu[0]['Uname'];
        }
        $this->Project->recursive = -1;
        $all_projects = $this->Project->find('all', array('conditions' => array('Project.id' => $project_id, 'Project.isactive' => 1), 'fields' => array('Project.id', 'Project.workflow_id')));
        $workflow_ids = Hash::extract($all_projects, "{n}.Project.workflow_id");
        $project_ids = Hash::extract($all_projects, "{n}.Project.id");
        //echo "<pre>";print_r($workflow_ids);
        $status_lists = array();
        $statuses = $this->Status->find('all', array('conditions' => array('Status.workflow_id' => array_unique($workflow_ids)), 'order' => array('Status.workflow_id ASC', 'Status.seq_order DESC'), 'limit' => 1));
        $status_lists = Hash::extract($statuses, "{n}.Status.id");
        //echo "<pre>";print_r($status_lists);
        /* if(defined('TSG') && TSG == 1){
          $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
          foreach ($statuses_list as $k => $v) {
          $status_lists[] = $v['s']['status_id'];
          }
          } */
        // echo "<pre>";print_r($status_lists);exit;
        $this->Easycase->recursive = -1;
        $task_list = $this->Easycase->find('all', array('conditions' => array("Easycase.istype" => 1, "Easycase.project_id" => $project_id, "Easycase.isactive" => 1, "Easycase.assign_to" => $user_ids, "DATE(Easycase.dt_created) BETWEEN '" . $dates['strddt'] . "' AND '" . $dates['enddt'] . "' "), 'fields' => array('Easycase.id,Easycase.assign_to,Easycase.legend')));
        if (is_array($task_list) && count($task_list) > 0) {
            $total_tasksarr = array();
            $cmplt_tasksarr = array();
            $count = 0;
            $tcount = 0;
            foreach ($task_list as $ktl => $vtl) {
                if (in_array($vtl['Easycase']['legend'], $status_lists)) {
                    $cmplt_tasksarr[$vtl['Easycase']['assign_to']][] = $vtl['Easycase'];
                } else {
                    $total_tasksarr[$vtl['Easycase']['assign_to']][] = $vtl['Easycase'];
                }
            }
            foreach ($user_ids as $key => $dt) {
                $total_task_series['name'] = __('In Progress',true);
                $total_task_series['color'] = '#8dc2f8';
                if (array_key_exists($dt, $total_tasksarr)) {
                    $total_task_series['data'][] = (int) (count($total_tasksarr[$dt]));
                } else {
                    $total_task_series['data'][] = 0;
                }
                $cmplt_task_series['name'] = __('Completed',true);
                $cmplt_task_series['color'] = '#8ad6a3';
                if (array_key_exists($dt, $cmplt_tasksarr)) {
                    $cmplt_task_series['data'][] = (int) (count($cmplt_tasksarr[$dt]));
                } else {
                    $cmplt_task_series['data'][] = 0;
                }
            }
            $series[0] = $total_task_series;
            $series[1] = $cmplt_task_series;
            # echo "<pre>";print_r( $series);echo "<pre>";print_r($user_name);exit;
            $this->set('user_arr', json_encode($user_name));
            $this->set('series', json_encode($series));
        } else {
            echo "<div class='mytask_txt'>" . __('Oops! No task has been assigned to any user yet ! Whats cooking') . "?</div>";
            exit;
        }
    }

    /* Function:Task list
     * 
     */

    function task_list() {
        $this->layout = 'ajax';
        // echo "<pre>";print_r($this->params['data']);exit;
        $project_id = (isset($this->params['data']['prj_id']) && !empty($this->params['data']['prj_id'])) ? $this->params['data']['prj_id'] : 'all';

        $cond = '';
        $usr_id = $this->params['data']['user_fltid'] ? $this->params['data']['user_fltid'] : SES_ID;
        $prjct_id = array();
        if ($project_id != 'all') {
            $this->set('prjname', $this->Format->getProjectName($project_id));
        }
        if ($usr_id) {
            $this->set('username', $this->Format->getUserShortName($usr_id));
        }
        if ($project_id != 'all') {
            $cond = "Project.id = '" . $project_id . "' AND";
            $prjct_id[] = $project_id;

            $this->loadModel('ProjectUser');
            $this->ProjectUser->unbindModel(array('belongsTo' => array('User')));
            $this->ProjectUser->bindModel(array('belongsTo' => array('Project')));

            $projArr = $this->ProjectUser->find('first', array('conditions' => array('Project.id' => $project_id, 'ProjectUser.user_id' => $usr_id, 'Project.isactive' => 1, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('ProjectUser.id', 'Project.workflow_id')));

            if (count($projArr)) {
                if (isset($projArr['Project']['workflow_id']) && !empty($projArr['Project']['workflow_id'])) {
                    $this->loadModel('Status');
                    $workflow_id = intval($projArr['Project']['workflow_id']);
                    $status_list = $this->Status->find('first', array('conditions' => array('Status.workflow_id' => $workflow_id), 'order' => 'seq_order DESC', 'limit' => 1));
                    $lgnd_val = $status_list['Status']['id'];
                    $status_name = $status_list['Status']['name'];
                }
            }
        }
        $lgnd_qry = '';
        if ($project_id == 'all') {
            $this->loadModel('Project');
            $this->Project->recursive = -1;
            $prjct_id = $this->Project->find('list', array('conditions' => array('isactive' => 1), 'fields' => 'id'));

            if (defined(TSG) && TSG == 1) {
                $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
                if (empty($statuses_list)) {
                    $lgnd_qry = "";
                } else {
                    foreach ($statuses_list as $k => $v) {
                        $status_list[] = $v['s']['status_id'];
                    }
                    $lgnd_qry = "AND Easycase.legend NOT IN(" . implode(',', $status_list) . ")";
                }
            } else {
                $lgnd_qry = "";
            }
        }
        $clt_sql = 1;
        if ($this->Auth->user('is_client') == 1) {
            $clt_sql = "((Easycase.client_status = " . $this->Auth->user('is_client') . " AND Easycase.user_id = " . $this->Auth->user('id') . ") OR Easycase.client_status != " . $this->Auth->user('is_client') . ")";
        }

        // $dt_cond = " AND Easycase.due_date<'" . GMT_DATE . "'";
        // $lgnd_qry = '';
        if ($lgnd_val > 0) {
            $lgnd_qry = "AND Easycase.legend != '" . $lgnd_val . "'";
        }
        $dt_cond = " AND Easycase.due_date<'" . GMT_DATETIME . "' AND (Easycase.due_date !='0000-00-00' AND Easycase.due_date !='1970-01-01' AND Easycase.due_date !='')";
        $sql_od = "SELECT SQL_CALC_FOUND_ROWS Easycase.case_no,Easycase.actual_dt_created,Easycase.dt_created,Easycase.uniq_id,Easycase.project_id,Easycase.due_date,
		Easycase.title,Project.name,Project.short_name, Project.uniq_id, 'od' as todos_type FROM (SELECT * FROM easycases as Easycase WHERE Easycase.istype='1' 
        AND Easycase.legend!=3 AND Easycase.legend!=5 " . $lgnd_qry . " 
                AND Easycase.isactive=1 AND " . $clt_sql . " AND Easycase.project_id!=0 " . $dt_cond . " AND Easycase.project_id IN (" . implode(',', $prjct_id) . ")  AND ((Easycase.assign_to='" . $usr_id . "') OR 
		(Easycase.assign_to=0 AND Easycase.user_id='" . $usr_id . "')) ORDER BY  Easycase.project_id DESC) AS Easycase LEFT JOIN projects AS Project
		ON (Easycase.project_id=Project.id)  ORDER BY Easycase.due_date ASC,Easycase.dt_created DESC LIMIT 0,5";
        $get_od_todos = $this->Easycase->query($sql_od);

        $tot_od = $this->Easycase->query("SELECT FOUND_ROWS() as tot_od");
        $qry_limit = 10 - count($get_od_todos);
        //    $dt_cond = " AND (Easycase.due_date>='" . GMT_DATE . "' OR Easycase.due_date IS NULL OR Easycase.due_date='0000-00-00' OR Easycase.due_date='1970-01-01' OR Easycase.due_date='')";
        $dt_cond = " AND Easycase.gantt_start_date >='" . GMT_DATETIME . "'";
        $sql = "SELECT SQL_CALC_FOUND_ROWS Easycase.case_no,Easycase.actual_dt_created,Easycase.dt_created,Easycase.gantt_start_date,Easycase.uniq_id,Easycase.project_id,Easycase.due_date,
		Easycase.title,Project.name,Project.short_name, Project.uniq_id, 'td' as todos_type FROM (SELECT * FROM easycases as Easycase WHERE Easycase.istype='1' AND " . $clt_sql . " 
        AND Easycase.legend!=3 AND Easycase.legend!=5 " . $lgnd_qry . " 
                AND Easycase.isactive=1 AND Easycase.project_id!=0 " . $dt_cond . " AND Easycase.project_id IN (" . implode(',', $prjct_id) . ")  AND ((Easycase.assign_to='" . $usr_id . "') OR 
		(Easycase.assign_to=0 AND Easycase.user_id='" . $usr_id . "')) ORDER BY  Easycase.project_id DESC) AS Easycase LEFT JOIN projects AS Project
		ON (Easycase.project_id=Project.id)  ORDER BY gantt_start_date ASC, Easycase.dt_created ASC LIMIT 0,$qry_limit";
        $gettodos = $this->Easycase->query($sql);
        $tot = $this->Easycase->query("SELECT FOUND_ROWS() as total");
        #echo "<pre>";print_r($get_od_todos);print_r($gettodos);exit;
        $this->set('ovr_duetsk', $get_od_todos);
        $this->set('upcmng_tsks', $gettodos);
        $this->set('gettodos', array_merge($get_od_todos, $gettodos));
        // echo "<pre>";print_r(array_merge($get_od_todos, $gettodos));exit;
        $this->set('project', $project_uid);
        $this->set('total', $tot[0][0]['total'] + $tot_od[0][0]['tot_od']);
    }

    /* Function to display the recent activities of users */

    function dash_recent_activities() {
        $this->layout = 'ajax';
        $this->loadModel('Easycase');
        $project_uid = (isset($this->params['data']['prj_id']) && !empty($this->params['data']['prj_id'])) ? $this->params['data']['prj_id'] : 'all';
        $clt_sql = 1;
        if (defined('CR') && CR == 1 && $this->Auth->user('is_client') == 1) {
            $clt_sql = "((Easycase.client_status = " . $this->Auth->user('is_client') . " AND Easycase.user_id = " . $this->Auth->user('id') . ") OR Easycase.client_status != " . $this->Auth->user('is_client') . ")";
        }
        $cond = '';
        $usr_id = $this->params['data']['user_fltid'] ? $this->params['data']['user_fltid'] : SES_ID;
        if ($project_uid != 'all') {
            $cond = "AND Project.id = '" . $project_uid . "'";
        }

        if ($project_uid != 'all') {
            $this->set('projectname', $this->Format->getProjectName($project_uid));
        }
        if ($usr_id != 'all') {
            $this->set('username', $this->Format->getUserShortName($usr_id));
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS Easycase.*,User.id,User.name,User.short_name,User.photo,Project.id,Project.uniq_id,Project.name
			FROM easycases AS Easycase INNER JOIN users AS User ON (Easycase.user_id = User.id) INNER JOIN 
			projects AS Project ON (Easycase.project_id = Project.id) inner JOIN project_users AS ProjectUser ON 
			(Easycase.project_id = ProjectUser.project_id AND ProjectUser.user_id = '" . $usr_id . "' AND ProjectUser.company_id = '" . SES_COMP . "') 
			WHERE Project.isactive='1' AND Easycase.isactive='1' AND Easycase.user_id = '" . $usr_id . "' " . $cond . " AND " . $clt_sql . " ORDER BY Easycase.actual_dt_created DESC LIMIT 0,10";

        $recent_activities = $this->Easycase->query($sql);
        $tot = $this->Easycase->query("SELECT FOUND_ROWS() as total");
        $total = $tot[0][0]['total'];
        if ($total != 0) {
            $view = new View($this);
            $fmt = $view->loadHelper('Format');
            $dt = $view->loadHelper('Datetime');
            $tz = $view->loadHelper('Tmzone');
            $csq = $view->loadHelper('Casequery');
            $this->loadModel('User');
            foreach ($recent_activities as $k => $v) {
                $recent_activities[$k]['User']['color'] = $fmt->getProfileBgColr($v['User']['id']);
                $recent_activities[$k]['User']['sht_name'] = mb_substr(trim(ucfirst($v['User']['name'])), 0, 1, "utf-8");
            }

            $frmtActivity = $this->User->formatActivities($recent_activities, $total, $fmt, $dt, $tz, $csq);
        }
//        print_r($frmtActivity); exit;
        $this->set('recent_activities', $frmtActivity['activity']);
        $this->set('project', $project_uid);
        $this->set('total', $total);
    }

    /* Function to display the number of task of different task types */

    function dash_task_type() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : 'all';
        $usr_id = $this->params['data']['user_fltid'] ? $this->params['data']['user_fltid'] : SES_ID;
        if ($project_uid == 'all') {
            $projQry = "AND Easycase.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.user_id=" . $usr_id . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')";
        } else {
            $projQry = "AND Easycase.project_id='" . $project_uid . "'";
        }

        if ($project_uid != 'all') {
            $this->set('projectname', $this->Format->getProjectName($project_uid));
        }
        if ($usr_id != 'all') {
            $this->set('username', $this->Format->getUserShortName($usr_id));
        }

        $usrQry = "AND Easycase.user_id='" . $usr_id . "'";
        $cond = '';
        $this->loadModel('Project');
        $this->loadModel('Easycase');
        $this->loadModel('Type');

        $query_All = 0;
        $query_Close = 0;
        $query_Resolve = 0;
        $stsMsg = '';
        $stsMsgTtl = '';
        $taskProg = "";
        $clt_sql = 1;
        if (defined('CR') && CR == 1 && $this->Auth->user('is_client') == 1) {
            $clt_sql = "((Easycase.client_status = " . $this->Auth->user('is_client') . " AND Easycase.user_id = " . $this->Auth->user('id') . ") OR Easycase.client_status != " . $this->Auth->user('is_client') . ")";
        }
        $types_sql = "select DISTINCT t.name,t.id,t.short_name,t.company_id,(select count(Easycase.id) from easycases as Easycase where Easycase.istype='1' AND " . $clt_sql . " AND Easycase.type_id=t.id AND Easycase.isactive='1' " . $projQry . " " . $usrQry . ") as count from types as t 
			WHERE CASE WHEN (SELECT COUNT(*) AS total FROM type_companies WHERE company_id = " . SES_COMP . " HAVING total >=1) THEN id IN (SELECT type_id FROM type_companies WHERE company_id = " . SES_COMP . ") ELSE company_id = 0 End 
			ORDER BY count DESC";
        //echo $types_sql;exit;
        $typeArr = $this->Easycase->query($types_sql);
        //  echo "<pre>";print_r($typeArr);exit;

        $arr_ouput = array();
        $total_count = 0;
        foreach ($typeArr as $k => $v) {
            $arr_ouput[$k][] = trim($v['t']['name']);
            $arr_ouput[$k][] = intval($v[0]['count']);
            $total_count += intval($v[0]['count']);
        }
        $arr_ouput_t['data'] = array();
        $arr_ouput_t['data'] = $arr_ouput;
        $arr_ouput_t['status'] = 'success';
        $arr_ouput_t['total_cnt'] = $total_count;
        // echo "<pre>";print_r($arr_ouput_t);exit;
        $this->set('task_report', json_encode($arr_ouput_t));
    }

    /* Function to display the progress of task according to the task statuses */

    function dash_task_status() {
        $this->layout = 'ajax';
        $project_uid = (isset($this->params['data']['projid']) && !empty($this->params['data']['projid'])) ? $this->params['data']['projid'] : PROJ_ID;
        $usr_id = $this->params['data']['user_fltid'] ? $this->params['data']['user_fltid'] : SES_ID;
        if ($project_uid != 'all') {
            $this->set('projectname', $this->Format->getProjectName($project_uid));
        }
        if ($usr_id != 'all') {
            $this->set('username', $this->Format->getUserShortName($usr_id));
        }
        $usrQry = " Easycase.user_id='" . $usr_id . "' AND ";
        $cond = '';
        $this->loadModel('Project');
        if (defined('TSG') && TSG == 1) {
            $this->loadModel('TaskStatusGroup.Workflow');
        }
        if ($project_uid != 'all') {
            $projArr = $this->Project->find('first', array('conditions' => array('Project.id' => $project_uid), 'fields' => array('Project.id', 'Project.workflow_id')));
            if (defined('TSG') && TSG == 1) {
                $workflow = $this->Workflow->find('first', array('conditions' => array('Workflow.id' => $projArr['Project']['workflow_id'])));
            }
            $cond = "Project.id = '" . $project_uid . "' AND";
        }
        $projQry = "AND Easycase.project_id ='" . $project_uid . "'"; //IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $cond . " ProjectUser.user_id=" . $usr_id . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1')";

        $stsMsg = '';
        $stsMsgTtl = '';
        $clt_sql = " AND 1 AND ";
        if (defined('CR') && CR == 1 && $this->Auth->user('is_client') == 1) {
            $clt_sql = " AND ((Easycase.client_status = " . $this->Auth->user('is_client') . " AND Easycase.user_id = " . $this->Auth->user('id') . ") OR Easycase.client_status != " . $this->Auth->user('is_client') . ") AND ";
        }
        if ($workflow['Status'] && $project_uid != 'all') {
            $stsArr = Hash::combine($workflow['Status'], '{n}.id', '{n}.name');
            $stsColorArr = Hash::combine($workflow['Status'], '{n}.name', '{n}.color');
        } else {
            $stsArr = array(1 => 'New', 2 => 'In Progress', 3 => 'Closed', 4 => 'In Progress', 5 => 'Resolved');
            $stsColorArr = array('New' => '#AE432E', 'In Progress' => '#244F7A', 'Closed' => '#77AB13', 'Resolved' => '#EF6807');
        }
        //echo "SELECT legend,COUNT(Easycase.id) as count, (CASE WHEN legend=4 THEN 2 ELSE status.seq_order END) as seq_order FROM easycases as Easycase LEFT JOIN statuses AS status ON Easycase.legend=status.id WHERE Easycase.istype='1' AND Easycase.isactive='1' " . $clt_sql ." " . $usrQry ." Easycase.project_id!=0 " . $projQry . " GROUP BY legend ORDER BY seq_order ASC" ;exit;
        $query_All1 = $this->Easycase->query("SELECT legend,COUNT(Easycase.id) as count, (CASE WHEN legend=4 THEN 2 ELSE status.seq_order END) as seq_order FROM easycases as Easycase LEFT JOIN statuses AS status ON Easycase.legend=status.id WHERE Easycase.istype='1' AND Easycase.isactive='1' " . $clt_sql . " " . $usrQry . " Easycase.project_id!=0 " . $projQry . " GROUP BY legend ORDER BY seq_order ASC");
        $stsCalc = array();
        foreach ($query_All1 as $k => $v) {
            $stsCalc[$stsArr[$v['Easycase']['legend']]] += $v[0]['count'];
        }
        $statusRate;
        if (array_sum($stsCalc)) {
            foreach ($stsCalc as $k => $sts) {
                $statusRate[] = array(
                    'name' => $k,
                    'color' => $stsColorArr[$k],
                    'y' => (float) number_format(($sts / array_sum($stsCalc)) * 99, 1)
                );
            }
        }
        //echo "<pre>";print_r($query_All1);exit;
        $this->set('status_report', json_encode(array('sts_msg' => $stsMsg, 'sts_msg_ttl' => $stsMsgTtl, 'task_prog' => $statusRate)));
    }

    /*
     * Author Satyajeet
     * Function to get time logged per task type per project for last seven days
     */

    function project_timesheet() {
        $this->layout = 'ajax';
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $date = array();
        $date['strddt'] = date('Y-m-d', strtotime('last monday', strtotime($curDateTime . " -7 days")));
        $date['enddt'] = date('Y-m-d', strtotime($curDateTz));
        $usr_cond = $logUsr_cndn = '';
        $status_lists = array();
        $this->loadModel('Project');
        $this->Project->recursive = -1;
        $conditions = array('Project.company_id' => SES_COMP, 'Project.isactive' => 1);
        if (SES_TYPE == 3) {
            $this->loadModel('ProjectUser');
            $usr_prjct = $this->ProjectUser->find('all', array('conditions' => array('ProjectUser.company_id' => SES_COMP, 'ProjectUser.user_id' => SES_ID), 'fields' => array('ProjectUser.project_id')));
            $prjId = Hash::extract($usr_prjct, '{n}.ProjectUser.project_id');
            $conditions = array_merge($conditions, array('Project.id' => $prjId));
        }
        $projects = $this->Project->find('all', array('conditions' => $conditions, 'fields' => array('Project.id', 'Project.name')));
        $project_ids = Hash::extract($projects, '{n}.Project.id');
        $project_dets = Hash::combine($projects, '{n}.Project.id', '{n}.Project');
        $total_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $project_ids, 'Easycase.isactive' => 1, 'Easycase.istype' => 1), 'fields' => array('COUNT(Easycase.id) as total_tasks', 'Easycase.project_id'), 'group' => array('Easycase.project_id')));
        $prjct_tottask = array();
        foreach ($total_tasks as $kt => $vt) {
            $prjct_tottask[$vt['Easycase']['project_id']]['prjct_total_task'] = $vt['0']['total_tasks'];
        }
        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            $this->loadModel('LogTime');
            if (SES_TYPE < 3) {
                $usr_cond = "LogTime.user_id >0";
            } elseif (SES_TYPE == 3) {
                $usr_cond = "LogTime.user_id = " . SES_ID;
            }
            if (defined('TSG') && TSG == 1) {
                $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
                foreach ($statuses_list as $k => $v) {
                    $status_lists[] = $v['s']['status_id'];
                }
                array_push($status_lists, 3);
            } else {
                $status_lists = array(3);
            }
            $log_condition .= " AND DATE(start_datetime) >= '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND DATE(start_datetime) <= '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
            $log_sql = "SELECT SUM(LogTime.total_hours) AS hours, COUNT(DISTINCT LogTime.task_id) As tot_tsks, Project.name, Project.id, LogTime.start_datetime "
                    . "FROM log_times AS LogTime "
                    . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                    . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id "
                    . "WHERE Easycase.isactive=1 AND " . $usr_cond . " " . $log_condition . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                    . "GROUP BY DATE(LogTime.start_datetime), LogTime.project_id,hours ORDER BY DATE(LogTime.start_datetime) DESC";
            #echo $log_sql;exit;
            $logtime = $this->LogTime->query($log_sql);
            $data = $logtime;

            $closed_tasks = array();
            foreach ($logtime as $key => $value) {
                $hour = $this->Format->format_time_hr_min($value['0']['hours']);
                $caseDtUploaded = $value['LogTime']['start_datetime'];
                $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtUploaded, "datetime");
                // $typeNm = $this->Format->getRequireTypeName($value['Easycase']['type_id']);
                if (array_key_exists($value['Project']['id'], $prjct_tottask)) {
                    // echo "<pre>";print_r($prjct_tottask[$value['Project']['id']]);
                    $data[$key]['0']['prjct_total_task'] = $prjct_tottask[$value['Project']['id']]['prjct_total_task'];
                }
                $closed_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $value['Project']['id'], 'Easycase.isactive' => 1, 'Easycase.istype' => 1, 'Easycase.legend' => $status_lists, 'DATE(Easycase.dt_created)' => date('Y-m-d', strtotime($value['LogTime']['start_datetime']))), 'fields' => array('COUNT(Easycase.id) as completed_tasks')));
                $data[$key]['0']['date'] = $updated;
                $data[$key]['0']['total_tasks'] = $value['0']['tot_tsks'] > $closed_tasks['0']['0']['completed_tasks'] ? (int) ($value['0']['tot_tsks'] - $closed_tasks['0']['0']['completed_tasks']) : (int) ($closed_tasks['0']['0']['completed_tasks'] - $value['0']['tot_tsks']);
                $data[$key]['0']['completed_tasks'] = $closed_tasks['0']['0']['completed_tasks'];
                $data[$key]['0']['hours'] = $hour;
            }
            /// echo "<pre>";print_r($logtime);print_r($data);print_r($closed_tasks);exit;
        } else {
            $this->loadModel('Easycase');
            if (SES_TYPE < 3) {
                $usr_cond = "Easycase.user_id >0";
            } elseif (SES_TYPE == 3) {
                $usr_cond = "Easycase.user_id = " . SES_ID;
            }
            $log_condition .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
            $log_sql = "SELECT SUM(Easycase.hours) AS hours,COUNT(Easycase.id) As tot_tsks, Project.name, Project.id, Easycase.dt_created "
                    . "FROM easycases AS Easycase "
                    . "LEFT JOIN projects AS Project ON Easycase.project_id= Project.id "
                    . "WHERE Easycase.isactive=1 AND " . $usr_cond . " " . $log_condition . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                    . "GROUP BY Easycase.project_id, DATE(Easycase.dt_created) ORDER BY Easycase.project_id ASC";
            #echo $log_sql;exit;
            $logtime = $this->Easycase->query($log_sql);
            $data = $logtime;
            $closed_tasks = array();
            if (defined('TSG') && TSG == 1) {
                $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
                foreach ($statuses_list as $k => $v) {
                    $status_lists[] = $v['s']['status_id'];
                }
                array_push($status_lists, 3);
            } else {
                $status_lists = array(3);
            }
            foreach ($logtime as $key => $value) {
                $caseDtUploaded = $value['Easycase']['dt_created'];
                $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtUploaded, "datetime");
                if (array_key_exists($value['Project']['id'], $prjct_tottask)) {
                    // echo "<pre>";print_r($prjct_tottask[$value['Project']['id']]);
                    $data[$key]['0']['prjct_total_task'] = $prjct_tottask[$value['Project']['id']]['prjct_total_task'];
                }
                $closed_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $value['Project']['id'], 'Easycase.isactive' => 1, 'Easycase.istype' => 1, 'Easycase.legend' => $status_lists, 'DATE(Easycase.dt_created)' => date('Y-m-d', strtotime($value['Easycase']['dt_created']))), 'fields' => array('COUNT(Easycase.id) as completed_tasks')));
                $data[$key]['0']['total_tasks'] = $value['0']['tot_tsks'] > $closed_tasks['0']['0']['completed_tasks'] ? (int) ($value['0']['tot_tsks'] - $closed_tasks['0']['0']['completed_tasks']) : (int) ($closed_tasks['0']['0']['completed_tasks'] - $value['0']['tot_tsks']);
                $data[$key]['0']['completed_tasks'] = $closed_tasks['0']['0']['completed_tasks'];
                $data[$key]['0']['date'] = $updated;
                // $data[$key]['0']['task_type'] = $typeNm;
            }
        }
        $view = new View($this);
        $frmt = $view->loadHelper('Format');
        if ($this->params->query['type'] == 'export') {
            $print_csv = "Project Name,Date,Total Task,In Progress Task,Completed Task,Hour(s) Spent \n";
            foreach ($data as $k => $val) {
                if ($val['0']['hours'] != '0.0' && $val['0']['hours'] != '---') {
                    $cmptd_tsk = !empty($val['0']['completed_tasks']) ? $val['0']['completed_tasks'] : '0';
                    $print_csv .= $val['Project']['name'] . "," . '"' . $frmt->chngdate_csv($val['0']['date']) . '"' . "," . $val['0']['prjct_total_task'] . "," . $val['0']['total_tasks'] . "," . $cmptd_tsk . "," . $val['0']['hours'] . "\n";
                }
            }
            $filename = "Dashboard_Project_timesheet_" . date("m-d-Y_H-i-s", time());

            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: csv" . date("Y-m-d") . ".csv");
            header("Content-disposition: filename=" . $filename . ".csv");

            print $print_csv;
            exit;
        } else {
            $this->set('data', $data);
        }
    }

    /*
     * Author Satyajeet
     * Function to get time logged per task type per resource for last seven days
     */

    function resource_timesheet() {
        $this->layout = 'ajax';
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        $date = array();
        $date['strddt'] = date('Y-m-d', strtotime('last monday', strtotime($curDateTime . " -7 days")));
        $date['enddt'] = date('Y-m-d', strtotime($curDateTz));
        $usr_cond = $logUsr_cndn = '';
        $status_lists = array();
        $this->loadModel('ProjectUser');
        $this->loadModel('Easycase');
        $this->loadModel('Project');
        $conditions = array('ProjectUser.company_id' => SES_COMP, 'User.isactive' => 1);
        if (SES_TYPE == 3) {
            $conditions = array('ProjectUser.user_id' => SES_ID);
        }
        $allcond = array('conditions' => $conditions, 'fields' => array('DISTINCT  User.id', 'CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
        $this->ProjectUser->bindModel(array('belongsTo' => array('User')));
        $UserProjArr = $this->ProjectUser->find('all', $allcond);
        $user_ids = Hash::extract($UserProjArr, '{n}.User.id');

        //$project_dets = Hash::combine($projects, '{n}.Project.id', '{n}.Project');
        $total_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.assign_to' => $user_ids, 'Easycase.isactive' => 1, 'Easycase.istype' => 1), 'fields' => array('COUNT(Easycase.id) as total_tasks', 'Easycase.project_id', 'Easycase.assign_to'), 'group' => array('Easycase.assign_to', 'Easycase.project_id'))); //, 'group' => array('Easycase.assign_to')
        $user_tottask = array();

        foreach ($total_tasks as $kt => $vt) {
            $user_tottask[$kt]['user_total_task'] = $vt['0']['total_tasks'];
            $user_tottask[$kt]['user_id'] = $vt['Easycase']['assign_to'];
            $user_tottask[$kt]['prjct_id'] = $vt['Easycase']['project_id'];
        }
        //echo "<pre>";print_r($user_tottask);print_r($total_tasks);exit;
        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            $this->loadModel('LogTime');
            if (SES_TYPE < 3) {
                $usr_cond = "LogTime.user_id >0";
            } elseif (SES_TYPE == 3) {
                $usr_cond = "LogTime.user_id = " . SES_ID;
            }
            if (defined('TSG') && TSG == 1) {
                $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
                foreach ($statuses_list as $k => $v) {
                    $status_lists[] = $v['s']['status_id'];
                }
                array_push($status_lists, 3);
            } else {
                $status_lists = array(3);
            }
            $log_condition .= " AND DATE(start_datetime) >= '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND DATE(start_datetime) <= '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
            $log_sql = "SELECT SUM(LogTime.total_hours) AS hours, Project.name,Project.id, COUNT( DISTINCT LogTime.task_id) As tot_tasks, LogTime.start_datetime, User.name, User.last_name, User.id "
                    . "FROM log_times AS LogTime "
                    . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                    . "LEFT JOIN users AS User ON LogTime.user_id = User.id "
                    . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id "
                    . "WHERE Easycase.isactive=1 AND  " . $usr_cond . " " . $log_condition . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                    . "GROUP BY LogTime.user_id,DATE(LogTime.start_datetime), LogTime.project_id ORDER BY DATE(LogTime.start_datetime) DESC"; //DATE(LogTime.start_datetime)
            #echo $log_sql;exit;
            $logtime = $this->LogTime->query($log_sql);
            // echo "<pre>";print_r($logtime);
            $closed_tasks = array();
            $data = $logtime;
            foreach ($logtime as $key => $value) {
                $hour = $this->Format->format_time_hr_min($value['0']['hours']);
                $name = $value['User']['name'] . " " . $value['User']['last_name'];
                $caseDtUploaded = $value['LogTime']['start_datetime'];
                $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtUploaded, "datetime");
                foreach ($user_tottask as $ku => $vu) {
                    if ($vu['user_id'] == $value['User']['id'] && $vu['prjct_id'] == $value['Project']['id']) {
                        $data[$key]['0']['user_total_tasks'] = $vu['user_total_task'];
                        break;
                    }
                }
                /* if(array_key_exists($value['Project']['id'], $user_tottask)){
                  if($user_tottask[$value['Project']['id']]['user_id'] == $value['User']['id']){
                  $data[$key]['0']['user_total_tasks'] = $user_tottask[$value['Project']['id']]['user_total_task'];
                  }

                  } */
                $closed_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $value['Project']['id'], 'Easycase.isactive' => 1, 'Easycase.assign_to' => $value['User']['id'], 'Easycase.istype' => 1, 'Easycase.legend' => $status_lists, 'DATE(Easycase.dt_created)' => date('Y-m-d', strtotime($value['LogTime']['start_datetime']))), 'fields' => array('COUNT(Easycase.id) as completed_tasks')));
                $data[$key]['0']['date'] = $updated;
                $data[$key]['0']['resource'] = __($name, true);
                $data[$key]['0']['total_tasks'] = $value['0']['tot_tasks'] > $closed_tasks['0']['0']['completed_tasks'] ? (int) ($value['0']['tot_tasks'] - $closed_tasks['0']['0']['completed_tasks']) : (int) ($closed_tasks['0']['0']['completed_tasks'] - $value['0']['tot_tasks']);
                $data[$key]['0']['hours'] = $hour;
                $data[$key]['0']['completed_task'] = $closed_tasks['0']['0']['completed_tasks'];
            }
            // echo "<pre>";print_r($user_tottask);print_r($logtime);print_r($data);exit;
        } else {
            $this->loadModel('Easycase');
            if (SES_TYPE < 3) {
                $usr_cond = "Easycase.user_id >0";
            } elseif (SES_TYPE == 3) {
                $usr_cond = "Easycase.user_id = " . SES_ID;
            }
            $log_condition .= " AND DATE(Easycase.dt_created) >= '" . date('Y-m-d', strtotime($date['strddt'])) . "' AND DATE(Easycase.dt_created) <= '" . date('Y-m-d', strtotime($date['enddt'])) . "' ";
            $log_sql = "SELECT SUM(Easycase.hours) AS hours, Project.name, Project.id, COUNT(Easycase.id) As tot_tasks, Easycase.dt_created, User.name, User.last_name, User.id "
                    . "FROM easycases AS Easycase "
                    . "LEFT JOIN users AS User ON Easycase.user_id = User.id "
                    . "LEFT JOIN projects AS Project ON Easycase.project_id= Project.id "
                    . "WHERE Easycase.isactive=1 AND Easycase.istype=1 AND " . $usr_cond . " " . $log_condition . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                    . "GROUP BY Easycase.user_id, DATE(Easycase.dt_created) ORDER BY  DATE(Easycase.dt_created) DESC";
            #echo $log_sql;exit;
            $logtime = $this->Easycase->query($log_sql);
            $data = $logtime;
            $closed_tasks = array();
            if (defined('TSG') && TSG == 1) {
                $statuses_list = $this->Project->query("SELECT p.id AS prjct_id,w.id As wrkflow_id,s.id as status_id,s.name  from projects as p LEFT JOIN workflows as w ON p.workflow_id = w.id LEFT JOIN statuses as s ON w.id = s.workflow_id where p.workflow_id !=0 AND w.is_active =1 AND s.id =(Select id from statuses where statuses.workflow_id = w.id ORDER BY statuses.seq_order DESC LIMIT 1 )");
                foreach ($statuses_list as $k => $v) {
                    $status_lists[] = $v['s']['status_id'];
                }
                array_push($status_lists, 3);
            } else {
                $status_lists = array(3);
            }

            foreach ($logtime as $key => $value) {
                $caseDtUploaded = $value['Easycase']['dt_created'];
                $name = $value['User']['name'] . " " . $value['User']['last_name'];
                $updated = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $caseDtUploaded, "datetime");
                foreach ($user_tottask as $ku => $vu) {
                    if ($vu['user_id'] == $value['User']['id'] && $vu['prjct_id'] == $value['Project']['id']) {
                        $data[$key]['0']['user_total_tasks'] = $vu['user_total_task'];
                        break;
                    }
                }
                $closed_tasks = $this->Easycase->find('all', array('conditions' => array('Easycase.project_id' => $value['Project']['id'], 'Easycase.isactive' => 1, 'Easycase.assign_to' => $value['User']['id'], 'Easycase.istype' => 1, 'Easycase.legend' => $status_lists, 'DATE(Easycase.dt_created)' => date('Y-m-d', strtotime($value['LogTime']['start_datetime']))), 'fields' => array('COUNT(Easycase.id) as completed_tasks')));
                $data[$key]['0']['date'] = $updated;
                $data[$key]['0']['resource'] = __($name, true);
                $data[$key]['0']['total_tasks'] = $value['0']['tot_tasks'] > $closed_tasks['0']['0']['completed_tasks'] ? (int) ($value['0']['tot_tasks'] - $closed_tasks['0']['0']['completed_tasks']) : (int) ($closed_tasks['0']['0']['completed_tasks'] - $value['0']['tot_tasks']);
                $data[$key]['0']['hours'] = $hour;
                $data[$key]['0']['completed_task'] = $closed_tasks['0']['0']['completed_tasks'];
            }
        }
        $view = new View($this);
        $frmt = $view->loadHelper('Format');
        if ($this->params->query['type'] == "export") {
            $print_csv = "Resource,Date,Project Name,Total Task,Inprogress Task(s),Completed Task(s),Hour(s) Spent \n";
            foreach ($data as $k => $val) {
                if ($val['0']['hours'] != '0.0' && $val['0']['hours'] != '---') {
                    $cmptd_tsk = !empty($val['0']['completed_task']) ? $val['0']['completed_task'] : '0';
                    $usr_tsks = (isset($val['0']['completed_task']) && !empty($val['0']['user_total_tasks'])) ? $val['0']['user_total_tasks'] : '0';
                    $print_csv .= $val[0]['resource'] . "," . '"' . $frmt->chngdate_csv($val['0']['date']) . '"' ."," .$val['Project']['name'] . "," . $usr_tsks . "," . $val['0']['total_tasks'] . "," . $cmptd_tsk . "," . $val['0']['hours'] . "\n";
                }
            }
            $filename = "Dashboard_Resource_timesheet_" . date("m-d-Y_H-i-s", time());
            header("Content-type: application/vnd.ms-excel");
            header("Content-disposition: csv" . date("Y-m-d") . ".csv");
            header("Content-disposition: filename=" . $filename . ".csv");
            print $print_csv;
            exit;
        } else {
            $this->set('data', $data);
        }
    }

    /* function to display the billable and non billable hour of each task type */

    function dash_timelog_tasktype() {
        $this->layout = 'ajax';
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $this->loadModel('Easycase');
        $this->loadModel('LogTime');
        // echo "<pre>";print_r($this->data);exit;
        if (isset($this->params->query['type']) && !empty($this->params->query['type'])) {
            $time_fltr = $this->params->query['time_flt'] ? $this->params->query['time_flt'] : 'last30days';
            $users_id = $this->params->query['user_id'];
            $project_id = $this->params->query['project_id'];
        } else {
            $time_fltr = $this->data['tme_flt'] ? $this->data['tme_flt'] : 'last30days';
            $users_id = $this->data['user_fltid'];
            $project_id = $this->data['projid'];
        }
        // echo "<pre>";print_r($this->params); print_r($this->params->query);exit;
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        // $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
        $dates = $this->Format->date_filter($time_fltr, $curDateTz);
        $this->set('time_flt', $this->data['time_flt']);
        $this->set('user_id', $this->data['user_id']);
        $this->set('project_id', $this->data['prjct_id']);
        if ($users_id == 'all') {
            $user_tsktyp = "Easycase.assign_to > 0";
            $userCond = "AND LogTime.user_id > 0";
            $user_id_cond = 'ProjectUser.user_id > 0 ';
        } else {
            $user_tsktyp = "Easycase.assign_to =" . $users_id; //." OR Easycase.assign_to=".$users_id;
            $userCond = "AND LogTime.user_id =" . $users_id;

            $user_id_cond = 'ProjectUser.user_id=' . $users_id;
        }
        if ($project_id == "all") {
            $prjct_tsktyp = "Easycase.project_id > 0";
            $projQry = " AND LogTime.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE " . $user_id_cond . " AND ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1') ";
        } else {
            $prjct_tsktyp = "Easycase.project_id = " . $project_id;
            $projQry = " AND LogTime.project_id = " . $project_id;
        }
        $tsk_typs_conditions = "SELECT DISTINCT(Type.id),Type.name,Easycase.assign_to From types As Type LEFT JOIN easycases AS Easycase ON Type.id = Easycase.type_id WHERE " . $user_tsktyp . " AND " . $prjct_tsktyp . " AND Easycase.istype = 1 AND Easycase.isactive =1 GROUP BY Easycase.type_id ORDER BY Type.name";
        $typ_lists = $this->Easycase->query($tsk_typs_conditions);
        $typ_lst_id_arr = Hash::extract($typ_lists, '{n}.Type.id');
        $typ_lst_name_arr = Hash::extract($typ_lists, '{n}.Type.name');
        $typ_lst_assign_userId = Hash::extract($typ_lists, '{n}.Easycase.assign_id');

        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            if (!empty($typ_lists)) {
            $this->loadModel('LogTime');
                if (!empty($typ_lst_assign_userId)) {
                    $userCond = "AND LogTime.user_id IN(" . implode(',', $typ_lst_assign_userId) . ")";
                }
            $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
            $count_sql = 'SELECT sum(total_hours) as secds,is_billable,Easycase.type_id AS type_id,Type.name AS type_name,User.name AS user_name,Project.name AS project_name '
                    . 'FROM log_times AS `LogTime` '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . "LEFT JOIN projects AS Project ON Project.id=LogTime.project_id "
                    . "LEFT JOIN users AS User ON User.id=LogTime.user_id "
                    . "LEFT JOIN types AS Type ON Easycase.type_id = Type.id "
                    . 'WHERE is_billable = 1 AND Easycase.isactive =1 ' . $projQry . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY Easycase.type_id,LogTime.user_id '
                    . 'UNION '
                    . 'SELECT sum(total_hours) as secds,is_billable,Easycase.type_id AS type_id,Type.name AS type_name,User.name AS user_name,Project.name AS project_name '
                    . 'FROM log_times AS LogTime '
                    . "LEFT JOIN easycases AS Easycase ON Easycase.id=LogTime.task_id AND LogTime.project_id=Easycase.project_id "
                    . "LEFT JOIN projects AS Project ON Project.id=LogTime.project_id "
                    . "LEFT JOIN users AS User On User.id = LogTime.user_id "
                    . "LEFT JOIN types AS Type ON Easycase.type_id = Type.id "
                    . 'WHERE is_billable = 0 AND Easycase.isactive =1 ' . $projQry . ' ' . $userCond . ' ' . $dateCond . ' '
                    . 'GROUP BY Easycase.type_id,LogTime.user_id';
            $cntlog = $this->LogTime->query($count_sql);
            //echo $count_sql ;exit;

            if (is_array($cntlog) && count($cntlog) > 0) {
                $billablearr = array();
                $nonbillablearr = array();
                $type_timelog = array();
                foreach ($cntlog as $k => $val) {
                    if ($val[0]['is_billable'] == 1) {
                            $billablearr[$val[0]['type_id']]['secds'] +=$val[0]['secds'];
                        $type_timelog[$val[0]['type_id']]['project_name'][] = $val[0]['project_name'];
                        $type_timelog[$val[0]['type_id']]['user_name'][] = $val[0]['user_name'];
                        $type_timelog[$val[0]['type_id']]['type_name'][] = $val[0]['type_name'];
                        $type_timelog[$val[0]['type_id']]['billable_hr'][] = round($val[0]['secds'] / 3600, 2);
                    } else {
                            $nonbillablearr[$val[0]['type_id']]['secds'] +=$val[0]['secds'];
                        $type_timelog[$val[0]['type_id']]['project_name'][] = $val[0]['project_name'];
                        $type_timelog[$val[0]['type_id']]['user_name'][] = $val[0]['user_name'];
                        $type_timelog[$val[0]['type_id']]['type_name'][] = $val[0]['type_name'];
                        $type_timelog[$val[0]['type_id']]['non_billable_hr'][] = round($val[0]['secds'] / 3600, 2);
                    }
                }
                //echo "<pre>";print_r($billablearr);print_r($nonbillablearr);print_r($tsk_lst_id_arr);exit;
                foreach ($typ_lst_id_arr as $key => $dt) {
                    $nonbillable_series['name'] = __('Non-billable',true);
                    $nonbillable_series['color'] = '#90C42C';
                    if (array_key_exists($dt, $nonbillablearr)) {
                        $nonbillable_series['data'][] = round($nonbillablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $nonbillable_series['data'][] = 0;
                    }
                    $billable_series['name'] = __('Billable',true);
                    $billable_series['color'] = '#00A2FF';
                    if (array_key_exists($dt, $billablearr)) {

                        $billable_series['data'][] = round($billablearr[$dt]['secds'] / 3600, 2);
                    } else {
                        $billable_series['data'][] = 0;
                    }
                }
                $series[0] = $nonbillable_series;
                $series[1] = $billable_series;
                if ($this->params->query['type'] == "export") {
                    $print_csv = "Project Name,Resource Name,Task Type Name,Billable Hour,Non Billable Hour,Total Hour \n";
                    //echo "<pre>";print_r();print_r($logtimes);exit;
                    foreach ($type_timelog as $k => $val) {

                        foreach ($val['project_name'] as $kv => $vv) {
                            $billbale_hr = isset($val['billable_hr'][$kv]) && !empty($val['billable_hr'][$kv]) ? $val['billable_hr'][$kv] : '0';
                            $unbillable_hr = isset($val['non_billable_hr'][$kv]) && !empty($val['non_billable_hr'][$kv]) ? $val['non_billable_hr'][$kv] : '0';
                            if ($billbale_hr != '0' || $unbillable_hr != '0') {
                                $print_csv .= $vv . "," . $val['user_name'][$kv] . "," . $val['type_name'][$kv] . "," . $billbale_hr . "," . $unbillable_hr . "," . (int) ($billbale_hr + $unbillable_hr) . "\n";
                            }
                        }
                    }

                    $filename = "Dashboard_tas_type_" . date("m-d-Y_H-i-s", time());
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
                    header("Content-disposition: filename=" . $filename . ".csv");
                    print $print_csv;
                    exit;
                } else {
                    // echo "<pre>";print_r($series);exit;
                    $this->set('series', json_encode($series));
                    $this->set('type_names', json_encode($typ_lst_name_arr));
                    $this->set('type_timelog', $type_timelog);
                }

                //echo "<pre>";print_r($type_timelog);print_r($typ_lst_name_arr);exit;
            } else {

                $user_id = $this->data['user_fltid'];
                $project_id = $this->data['projid'];
                $msg = "<div class='mytask_txt'><p>";
                $msg .= __("Oops!", true);
                if ($user_id != 'all') {
                    $usrname = $this->Format->getUserShortName($user_id);
                    $msg .= __($usrname['User']['name'] . " has not entered time log for tasks ", true);
                } else {
                    $msg .= __("No user has logged time", true);
                }
                if ($project_id != 'all') {
                    $prjname = $this->Format->getProjectName($project_id);
                    $msg .= __(" on Project " . $prjname, true);
                }
                $msg .= __(" !");
                $msg .= "<br />";
                $msg .= __("What's happening ?", true);
                $msg .= "</p></div>";
                echo $msg;
                exit;
            }
            } else {
                $user_id = $this->data['user_fltid'];
                $project_id = $this->data['projid'];
                $msg = "<div class='mytask_txt'><p>";
                $msg.= __("Oops!", true);
                if ($user_id != 'all') {
                    $usrname = $this->Format->getUserShortName($user_id);
                    $msg.= __($usrname['User']['name'] . " has not been assigned to any task", true);
                } else {
                    $msg.= __("No user has been to any tasks");
        }
                if ($project_id != 'all') {
                    $prjname = $this->Format->getProjectName($project_id);
                    $msg.= __(" on Project " . $prjname, true);
    }
                $msg.= __(" !");
                $msg.= "<br />";
                $msg.= __("What's happening ?");
                $msg.= "</p></div>";
                echo $msg;
                exit;
            }
        }
    }

    /* Resource cost report */

    function resource_cost_report() {

        $this->layout = 'ajax';
        $view = new View($this);
        $tz = $view->loadHelper('Tmzone');
        $curDateTz = $tz->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATETIME, "datetime");
        if (isset($this->params->query['type']) && !empty($this->params->query['type'])) {
            $time_fltr = $this->params->query['time_flt'] ? $this->params->query['time_flt'] : 'last30days';
            $project_id = $this->params->query['project_id'];
        } else {
            $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
            $project_id = $this->data['projid'];
        }
        $time_fltr = $this->data['time_flt'] ? $this->data['time_flt'] : 'last30days';
        $dates = $this->Format->date_filter($time_fltr, $curDateTz);

        $usr_cond = $logUsr_cndn = '';
        $status_lists = array();
        $this->loadModel('ProjectUser');
        $this->loadModel('Easycase');
        $this->loadModel('Project');

        //echo "<pre>";print_r($user_tottask);print_r($total_tasks);exit;
        /*  if($project_id == 'all'){
          $projQry = " AND LogTime.project_id IN (SELECT ProjectUser.project_id FROM project_users AS ProjectUser,projects as Project WHERE ProjectUser.company_id=" . SES_COMP . " AND ProjectUser.project_id=Project.id AND Project.isactive='1') ";
          $allcond = array('conditions' => array('ProjectUser.company_id' => SES_COMP,'User.isactive'=>1), 'fields' => array('DISTINCT  User.id','CONCAT(User.name,User.last_name) AS Uname'), 'order' => array('ProjectUser.dt_visited DESC'));
          }else{ */
        $projQry = " AND LogTime.project_id = " . $project_id;
        //  }

        if ((defined('TLG') && TLG == 1) || (defined('TPAY') && TPAY == 1) || (defined('GTLG') && GTLG == 1)) {
            $this->loadModel('LogTime');
            if (SES_TYPE < 3) {
                $usr_cond = "LogTime.user_id >0";
            } elseif (SES_TYPE == 3) {
                $usr_cond = "LogTime.user_id = " . SES_ID;
            }

            $dateCond = " AND DATE(LogTime.start_datetime) BETWEEN '" . date('Y-m-d', strtotime($dates['strddt'])) . "' AND '" . date('Y-m-d', strtotime($dates['enddt'])) . "'";
            $log_sql = "SELECT SUM(LogTime.total_hours) AS hours,CONCAT(User.name, User.last_name) AS user_name, User.id,Project.currency ,Project.name,RoleRate.rate as billable_rate,RoleRate.actual_rate as actual_rate,InvoiceCustomer.organization as project_company_name "
                    . "FROM log_times AS LogTime "
                    . "LEFT JOIN easycases AS Easycase ON LogTime.task_id=Easycase.id AND LogTime.project_id=Easycase.project_id "
                    . "LEFT JOIN users AS User ON LogTime.user_id = User.id "
                    . "LEFT JOIN projects AS Project ON LogTime.project_id= Project.id  LEFT JOIN invoice_customers as InvoiceCustomer ON Project.invoice_customer_id = InvoiceCustomer.id "
                    . "LEFT JOIN role_rates AS RoleRate ON (LogTime.project_id = RoleRate.project_id AND LogTime.user_id = RoleRate.user_id) "
                    . "WHERE Easycase.isactive=1 AND  " . $usr_cond . " " . $dateCond . " " . $projQry . " AND Project.company_id=" . SES_COMP . " AND Project.isactive=1 "
                    . "GROUP BY LogTime.user_id,LogTime.project_id ORDER BY DATE(LogTime.start_datetime) DESC"; //DATE(LogTime.start_datetime)
            $logtime = $this->LogTime->query($log_sql);
            #echo "<pre>";print_r($logtime);exit;
            if ($this->params->query['type'] == "export") {
                $print_csv = "Resource Name,Client Name,Project Name,Hours Spent,Actual Cost,Total Actual Cost,Billing Rate,Total Billing Amount \n";
                foreach ($logtime as $k => $val) {
                    $actual_cost = (isset($val['RoleRate']['actual_rate']) && !empty($val['RoleRate']['actual_rate'])) ? $val['RoleRate']['actual_rate'] . " " . $val['Project']['currency'] : 0;
                    $billing_cost = (isset($val['RoleRate']['billable_rate']) && !empty($val['RoleRate']['billable_rate'])) ? $val['RoleRate']['billable_rate'] . " " . $val['Project']['currency'] : 0;
                    $total_actual_rate = round(($val['0']['hours'] / 3600) * $val['RoleRate']['actual_rate']) != 0 ? round(($val['0']['hours'] / 3600) * $val['RoleRate']['actual_rate']) . " " . $val['Project']['currency'] : 0;
                    $total_billing_rate = round(($val['0']['hours'] / 3600) * $val['RoleRate']['billable_rate']) != 0 ? round(($val['0']['hours'] / 3600) * $val['RoleRate']['billable_rate']) . " " . $val['Project']['currency'] : 0;
                    $clnt_cmnpy_name = !empty($val['InvoiceCustomer']['project_company_name']) ? $val['InvoiceCustomer']['project_company_name'] : 'None';
                    $spent_hrs = $this->Format->format_time_hr_min($val['0']['hours']);
                    $print_csv .= ucfirst($val[0]['user_name']) . "," . $clnt_cmnpy_name . "," . ucfirst($val['Project']['name']) . "," . $spent_hrs . "," . $actual_cost . "," . $total_actual_rate . "," . $billing_cost . "," . $total_billing_rate . "\n";
                }

                $filename = "Dashboard_resource_cost_report" . date("m-d-Y_H-i-s", time());
                header("Content-type: application/vnd.ms-excel");
                header("Content-disposition: csv" . date("Y-m-d") . ".csv");
                header("Content-disposition: filename=" . $filename . ".csv");
                print $print_csv;
                exit;
            } else {
                $this->set('resource_cost_details', $logtime);
            }

            // echo "<pre>";print_r($logtime);exit;
        }
    }

}
