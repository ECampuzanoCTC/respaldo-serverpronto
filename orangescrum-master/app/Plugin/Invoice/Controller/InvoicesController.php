<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 's3', array('file' => 's3' . DS . 'S3.php'));

App::import('Vendor', 'ElephantIO', array('file' => 'ElephantIO' . DS . 'Client.php'));

use RRule\RRule;

class InvoicesController extends InvoiceAppController {

    public $name = 'Invoices';
    public $components = array('Format', 'Postcase', 'Sendgrid', 'Tmzone');
    public $helpers = array('Format');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('invoicePdf');
    }

    function invoice() {
        $this->loadModel('Invoice');
        /* $this->loadModel('ProjectUser');
          $projid = $this->ProjectUser->find('first', array('conditions' => array('ProjectUser.user_id' => SES_ID, 'ProjectUser.company_id' => SES_COMP), 'fields' => array('ProjectUser.project_id'), 'order' => 'dt_visited DESC'));
          $prjid = $projid['ProjectUser']['project_id']; */
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];

        /* user details */
        $fromEmail = $this->User->findById(SES_ID);
        $this->set('email', $fromEmail['User']['email']);

        $options = array();
        $options['fields'] = array('Invoice.id', 'Invoice.invoice_no');
        $options['conditions'] = array('Invoice.project_id' => $prjid, "IF(Invoice.customer_id>0,InvoiceCustomer.status='Active',1=1)");
        $options['recursive'] = false;
        $options['joins'] = array(array('table' => 'invoice_customers', 'alias' => 'InvoiceCustomer', 'type' => 'LEFT', 'conditions' => array('InvoiceCustomer.id = Invoice.customer_id')));

        $invoice = $this->Invoice->find('list', $options);
        $this->set('invoice', $invoice);
        /* company name */
        $Company = ClassRegistry::init('Company');
        $Company->recursive = -1;
        $getCompany = $Company->find('first', array('conditions' => array('Company.id' => SES_COMP), 'fields' => 'name'));
        $this->set('from', $getCompany['Company']['name']);

        /* fetch invoice and log time counts */
        // $this->getCountInvoice(true); 
    }

    function ajaxTimeList() {
        $this->layout = 'ajax';
        $this->loadModel('ProjectUser');
        $this->loadModel('Easycase');
        $this->loadModel('LogTime');
        $this->loadModel('Invoice');
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];

        $order_by = 'created';
        $order_sort = 'DESC';
        if (isset($this->data['params'])) {
            $sort_by = (isset($this->data['params']['sortby'])) ? trim($this->data['params']['sortby']) : '';
            $order_sort = (isset($this->data['params']['order'])) ? trim($this->data['params']['order']) : ' ASC';
            switch ($sort_by) {
                case"date":$order_by = 'start_datetime';
                    break;
                case"resource":$order_by = 'name';
                    break;
                case"task":$order_by = 'Easycase.title';
                    break;
                case"type":$order_by = 'Type.name';
                    break;
            }
        }

        /* Start Pagination */
        $page_limit = INVOICE_PAGE_LIMIT;
        $page = 1;

        if (isset($this->data['page']) && $this->data['page']) {
            $page = $this->data['page'];
        }
        $offset = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;
        $conditions = array('LogTime.project_id' => $prjid,
            'LogTime.is_billable' => 1,
            "LogTime.log_id NOT IN (SELECT invoice_logs.log_id FROM invoice_logs LEFT JOIN invoices ON invoices.id=invoice_logs.invoice_id WHERE invoices.project_id='$prjid' AND invoice_logs.log_id>0)",
        );
        $params = array();
        $params['conditions'] = $conditions;
        $params['order'] = $order_by . ' ' . $order_sort;
        $params['limit'] = $limit2;
        $params['offset'] = $offset;
        $params['joins'] = array(
            array('table' => 'easycases', 'alias' => 'Easycase', 'type' => 'LEFT', 'conditions' => array('Easycase.id = LogTime.task_id')),
            array('table' => 'users', 'alias' => 'User', 'type' => 'LEFT', 'conditions' => array('User.id = LogTime.user_id')),
            array('table' => 'types', 'alias' => 'Type', 'type' => 'LEFT', 'conditions' => array('Type.id = Easycase.type_id')),
        );
        $params['fields'] = array('LogTime.*', 'Easycase.title', 'Type.name', 'CONCAT_WS(" ",User.name,User.last_name) AS name');
        $logtimes = $this->LogTime->find('all', $params);
        $tot = $this->LogTime->find('count', array('conditions' => $conditions, 'order' => 'created DESC'));
        $tot_hours = $this->LogTime->find('all', array('conditions' => $conditions, 'fields' => 'SUM(total_hours) AS total_hours'));

        $this->set('total_hours', $tot_hours[0][0]['total_hours']);
        $this->set('caseCount', $tot);
        $this->set('page_limit', $page_limit);
        $this->set('page', $page);
        $this->set('casePage', $page);
        $this->set('logtimes', $logtimes);
        $this->set('order_by', $sort_by);
        $this->set('order_sort', $order_sort);
        /* End Andola Pageination */
    }

    /* By CP
     * to show listing
     */

    function ajaxCustomerList() {
        $this->layout = 'ajax';
        $project_id = $GLOBALS['getallproj'][0]['Project']['id'];
        $this->loadModel('InvoiceCustomer');

        $order_by = 'first_name';
        $order_sort = 'ASC';
        if (isset($this->data['params'])) {
            $sort_by = (isset($this->data['params']['sortby'])) ? trim($this->data['params']['sortby']) : '';
            $order_sort = (isset($this->data['params']['order'])) ? trim($this->data['params']['order']) : ' ASC';
            switch ($sort_by) {
                case"name":$order_by = 'first_name';
                    break;
                case"currency":$order_by = 'currency';
                    break;
                case"status":$order_by = 'status';
                    break;
            }
        }
        $page_limit = CASE_PAGE_LIMIT;
        $page = (isset($this->data['page']) && $this->data['page'] > 0) ? $this->data['page'] : 1;

        $offset = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;
        $params = array();
        $params['fields'] = array('id', 'uniq_id', 'status', 'currency', 'organization', "CONCAT_WS(' ',first_name,last_name) AS name", "CONCAT_WS(', ',street,city,state,country,zipcode) AS details");
        $params['conditions'] = array('company_id' => SES_COMP);
        $params['order'] = $order_by . ' ' . $order_sort;
        $params['limit'] = $limit2;
        $params['offset'] = $offset;

        $customers = $this->InvoiceCustomer->find('all', $params);
        $caseCount = $this->InvoiceCustomer->find('count', array('fields' => array('id'), 'conditions' => $params['conditions']));

        $this->set('customers', $customers);
        $this->set('caseCount', $caseCount);
        $this->set('page_limit', $page_limit);
        $this->set('page', $page);
        $this->set('casePage', $page);
        $this->set('order_by', $sort_by);
        $this->set('order_sort', $order_sort);
    }

    function getCountInvoice($return = false) {
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $this->loadModel('Invoice');
        if (defined('TLG') && TLG == 1) {
            $this->loadModel('LogTime');
        }
        /* invoice count */

        $options = array();
        $invconditions = array('Invoice.project_id' => $prjid, 'Invoice.is_active' => 1, "IF(Invoice.customer_id>0,InvoiceCustomer.status='Active',1=1)");
        $arcconditions = array('Invoice.project_id' => $prjid, 'Invoice.is_active' => 0, "IF(Invoice.customer_id>0,InvoiceCustomer.status='Active',1=1)");
        if ((SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) && ($this->Auth->user('is_client') != 1)) {
            
        } else {
            $invconditions[] = "InvoiceCustomer.user_id='" . SES_ID . "'";
            $arcconditions[] = "InvoiceCustomer.user_id='" . SES_ID . "'";
        }
        $options['conditions'] = $invconditions;
        $options['recursive'] = false;
        $options['joins'] = array(array('table' => 'invoice_customers', 'alias' => 'InvoiceCustomer', 'type' => 'LEFT', 'conditions' => array('InvoiceCustomer.id = Invoice.customer_id')));

        $invoice = $this->Invoice->find('count', $options);
        $options['conditions'] = $arcconditions;
        $archived = $this->Invoice->find('count', $options);

        $logtimes = 0;
        if (defined('TLG') && TLG == 1) {
            /* unbilled logtime count */
            $logtimes = $this->LogTime->find('count', array(
                'conditions' => array('LogTime.project_id' => $prjid, 'LogTime.is_billable' => 1, "LogTime.log_id NOT IN (SELECT invoice_logs.log_id FROM invoice_logs LEFT JOIN invoices ON invoices.id=invoice_logs.invoice_id WHERE invoices.project_id='$prjid' AND invoice_logs.log_id>0)",),
                'order' => 'created DESC')
            );
        }

        $count['invoice'] = $invoice;
        $count['logtime'] = $logtimes;
        $count['archived'] = $archived;
        if ($return == false) {
            print json_encode($count);
            exit;
        } else {
            $this->set('caseCount', $logtimes);
            $this->set('invoiceCount', $invoice);
            $this->set('archivedCount', $archived);
        }
    }

    function updateInvoicedropdown() {
        $this->layout = 'ajax';
        $this->loadModel('Invoice');
        $this->loadModel('InvoiceCustomer');
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];

        $options = array();
        $options['fields'] = array('Invoice.id', 'Invoice.invoice_no');
        $options['conditions'] = array('Invoice.project_id' => $prjid, "IF(Invoice.customer_id>0,InvoiceCustomer.status='Active',1=1)");
        $options['recursive'] = false;
        $options['joins'] = array(array('table' => 'invoice_customers', 'alias' => 'InvoiceCustomer', 'type' => 'LEFT', 'conditions' => array('InvoiceCustomer.id = Invoice.customer_id')));
        $invoice = $this->Invoice->find('list', $options);

        $str = '';
        $str.='<option value="">' . __('Select') . '</option>';
        $str.='<option value="0">' . __('Add New Invoice') . '...</option>';
        if (!empty($invoice)) {
            foreach ($invoice as $k => $v) {
                $str.='<option value="' . $k . '">' . $v . '</option>';
            }
        } else {
            
        }

        print $str;
        exit;
    }

    function add_customer() {
        $this->loadModel('InvoiceCustomer');
        $data = array();
        $project_id = isset($this->data['Customer']['project_id']) ? $this->data['Customer']['project_id'] : $GLOBALS['getallproj'][0]['Project']['id'];
        $id = $this->data['Customer']['customer_id'];
        $error = false;
        if (trim($this->data['Customer']['cust_fname']) == '') {
            $msg = __("Please enter customer name.", true);
            $error = true;
        } elseif (trim($this->data['Customer']['cust_email']) == '') {
            $msg = __("Please enter email address.", true);
            $error = true;
        } elseif (trim($this->data['Customer']['cust_currency']) == '') {
            $msg = __("Please select currency.", true);
            $error = true;
        } elseif (trim($this->data['Customer']['cust_email']) != '') {
            $conditions = array('email' => trim($this->data['Customer']['cust_email']));
            $conditions[] = "company_id=" . SES_COMP;
            if ($id > 0) {
                $conditions[] = "id!=$id";
            }
            $exist = $this->InvoiceCustomer->find('all', array('conditions' => $conditions));
            if (is_array($exist) && count($exist) > 0) {
                $msg = __("Email already exist. Please enter another email", true);
                $error = true;
            }
        } else if (trim($this->data['Customer']['organization']) == '') {
            $msg = __("Please enter organisation name.", true);
            $error = true;
        } else if (trim($this->data['Customer']['organization']) != '') {
            $conditionss = array('organization' => trim($this->data['Customer']['organization']));
            $conditionss[] = "company_id=" . SES_COMP;
            if ($id > 0) {
                $conditions[] = "id!=$id";
        }
            $exist = $this->InvoiceCustomer->find('all', array('conditions' => $conditionss));
            if (is_array($exist) && count($exist) > 0) {
                $msg = __("Organization already exits.Please enter another Organization name", true);
                $error = true;
            }
        }
        if ($error == true) {
            echo json_encode(array('success' => "No", 'msg' => $msg));
            exit;
        }

        /* assign customer id */
        $user_id = 0;
        $email = trim($this->data['Customer']['cust_email']);
        if ($email != "") {
            $this->loadModel('User');
            $userdetails = $this->User->findByEmail($email);

            if (is_array($userdetails) && count($userdetails) > 0) {
                $user_id = $userdetails['User']['id'];
                $this->User->id = $user_id;
                $this->User->saveField('is_client', true);
                $client_user = $client_user = array(
                    'email' => trim($this->data['Customer']['cust_email']),
                    'password' => '',
                    'name' => $this->data['Customer']['cust_fname'] . ' ' . $this->data['Customer']['cust_lname'],
                    'short_name' => trim($this->Format->makeShortName($this->data['Customer']['cust_fname'], $this->data['Customer']['cust_lname'])),
                    'istype' => 3,
                );

                $client_user['isactive'] = 2;
                $client_user['dt_created'] = date('Y-m-d H:i:s');
                $client_user['is_email'] = true;
                $client_user['is_client'] = 1;
                $client_user['uniq_id'] = $this->Format->generateUniqNumber();
            } else {
                $client_user = array(
                    'email' => trim($this->data['Customer']['cust_email']),
                    'password' => '',
                    'name' => $this->data['Customer']['cust_fname'] . ' ' . $this->data['Customer']['cust_lname'],
                    'short_name' => trim($this->Format->makeShortName($this->data['Customer']['cust_fname'], $this->data['Customer']['cust_lname'])),
                    'istype' => 3,
                );
                $client_user['isactive'] = 2;
                $client_user['dt_created'] = date('Y-m-d H:i:s');
                $client_user['is_email'] = true;
                $client_user['is_client'] = 1;
                $client_user['uniq_id'] = $this->Format->generateUniqNumber();
            }
        }
        if (trim($this->data['Customer']['cust_fname']) != '') {
            $customer = array(
                'first_name' => trim($this->data['Customer']['cust_fname']),
                'last_name' => trim($this->data['Customer']['cust_lname']) != '' ? trim($this->data['Customer']['cust_lname']) : NULL,
                'street' => trim($this->data['Customer']['cust_street']) != '' ? trim($this->data['Customer']['cust_street']) : NULL,
                'city' => trim($this->data['Customer']['cust_city']) != '' ? trim($this->data['Customer']['cust_city']) : NULL,
                'state' => trim($this->data['Customer']['cust_state']) != '' ? trim($this->data['Customer']['cust_state']) : NULL,
                'country' => trim($this->data['Customer']['cust_country']) != '' ? trim($this->data['Customer']['cust_country']) : NULL,
                'zipcode' => trim($this->data['Customer']['cust_zipcode']) != "" ? trim($this->data['Customer']['cust_zipcode']) : NULL,
                'currency' => trim($this->data['Customer']['cust_currency']) != "" ? trim($this->data['Customer']['cust_currency']) : NULL,
                'phone' => trim($this->data['Customer']['cust_phone']) != "" ? trim($this->data['Customer']['cust_phone']) : NULL,
                'email' => trim($this->data['Customer']['cust_email']) != "" ? trim($this->data['Customer']['cust_email']) : NULL,
                'title' => trim($this->data['Customer']['cust_title']) != "" ? trim($this->data['Customer']['cust_title']) : NULL,
                'organization' => trim($this->data['Customer']['cust_organization']) != "" ? trim($this->data['Customer']['cust_organization']) : NULL,
                'status' => trim($this->data['Customer']['cust_status']) != "" ? trim($this->data['Customer']['cust_status']) : 'Active',
                'modified' => date("Y-m-d H:i:s")
            );
            $customer['user_id'] = $user_id;
            if ($id > 0) {
                $this->InvoiceCustomer->id = $id;
                $mode = 'Edit';
            } else {
                $mode = 'Add';
                $customer['uniq_id'] = $this->Format->generateUniqNumber();
                $customer['project_id'] = $project_id;
                $customer['company_id'] = SES_COMP;
                $customer['created'] = date("Y-m-d H:i:s");
            }
            if ($this->InvoiceCustomer->save($customer)) {
                if ($id > 0) {
                    $this->User->read(null, $user_id);
                    $this->User->set($client_user);
                    $this->User->save();
                } else {
                    $this->User->save($client_user);
                    $lastId = $this->User->id;
                    $this->InvoiceCustomer->read(null, $this->InvoiceCustomer->id);
                    $this->InvoiceCustomer->set(array('user_id' => $lastId));
                    $this->InvoiceCustomer->save();
                }
            }
            $id = $this->InvoiceCustomer->id;


            $customer_name = $customer['title'] . " " . $customer['first_name'] . " " . $customer['last_name'];
            $customer_details = $customer['title'] . " " . $customer['first_name'] . " " . $customer['last_name'];
            $customer_details .= "\n";
            $customer_details .= trim($customer['street']) != '' ? trim($customer['street']) . "," : '';
            $customer_details .= trim($customer['city']) != '' ? trim($customer['city']) . "," : '';
            $customer_details .= trim($customer['state']) != '' ? trim($customer['state']) . "," : '';
            $customer_details .= trim($customer['country']) != '' ? trim($customer['country']) . "," : '';
            $customer_details .= trim($customer['zipcode']) != '' ? trim($customer['zipcode']) . "" : '';
            $html = "<li><a class='anchor customer_opts' data-name='" . $customer['first_name'] . "' "
                    . " data-id='" . addslashes($customer_details) . "' "
                    . " data-cid='" . $id . "'>" . $customer['first_name'] . "</a></li>";
        } else {
            $id = 0;
            $html = '';
            $customer_details = '';
            $customer_name = '';
        }
        echo json_encode(array('success' => ($id > 0 ? "Yes" : "No"),
            'id' => $id,
            'currency' => $customer['currency'],
            'status' => $customer['status'],
            'email' => !empty($customer['email']) ? $customer['email'] : '',
            'name' => trim($customer['first_name'] . " " . $customer['last_name']),
            'details' => addslashes(trim($customer_details)),
            'name' => trim($customer_name),
            'mode' => $mode,
            'html' => $html,));
        exit;
    }

    /* By CP
     * for customer details
     */

    function customer_details() {
        $this->loadModel('InvoiceCustomer');
        $id = $this->data['id'];
        $customers = $this->InvoiceCustomer->findById($id);
        echo json_encode($customers);
        exit;
    }

    function ajaxInvoicePage() {
        $this->layout = 'ajax';
        $this->loadModel('Invoice');
        $this->loadModel('User');
        $this->loadModel('InvoiceCustomer');
        $project_id = $GLOBALS['getallproj'][0]['Project']['id'];
        $id = $this->request['data']['v'];
        $log_id = $this->request['data']['log'];
        $invoice = array();
        if ($id > 0) {
            /* invoice details */
            $this->Invoice->bindModel(
                    array('hasMany' => array(
                            'InvoiceLog' => array(
                                'className' => 'InvoiceLog',
                                'dependent' => true,
                                'order' => 'created ASC'
                            )
                        )
            ));
            $invoice = $this->Invoice->findById($id);
            $this->Invoice->unbindModel(array('hasMany' => array('InvoiceLog')));
        } else {
            $this->Invoice->recursive = false;
            $lastinvoice = $this->Invoice->find('first', array('conditions' => array('company_id' => SES_COMP, "invoice_from!=''"), 'order' => 'id desc'));
            $invoice['Invoice']['invoice_from'] = $lastinvoice['Invoice']['invoice_from'];
        }
        /* company name */
        $Company = ClassRegistry::init('Company');
        $Company->recursive = -1;
        $getCompany = $Company->find('first', array('conditions' => array('Company.id' => SES_COMP), 'fields' => array('name', 'logo', 'id')));
        $invoice['Invoice']['company_name'] = $getCompany['Company']['name'];

        /* user details */
        $fromEmail = $this->User->findById(SES_ID);
        $this->set('email', $fromEmail['User']['email']);

        $options = array();
        $options['fields'] = array('id', "currency", "email", "CONCAT_WS(' ',title,first_name,last_name) AS name", "CONCAT_WS(', ',CONCAT_WS(' ',title,first_name,last_name),street,city,state,country,zipcode) AS details");
        $options['conditions'] = array('company_id' => SES_COMP, 'status' => 'Active');
        $options['order'] = 'first_name ASC';
        $customers = $this->InvoiceCustomer->find('all', $options);

        /* selected timelogs details */
        $logdata = array();
        if (trim($log_id) != '') {
            $invoice['Invoice']['issue_date'] = date('Y-m-d H:i:s');
            $invoice['Invoice']['due_date'] = date('Y-m-d H:i:s');
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
                $invoice['log'] = $logdata;
            } else {
                $invoice['log'] = array(array());
            }
        }
        $this->set('customers', $customers);
        $this->set('company', $getCompany);
        // echo "<pre>";print_r($getCompany);exit;
        $this->set('i', $invoice);
        if (SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) {
            $this->loadModel('InvoiceActivity');
            $this->InvoiceActivity->bindModel(
                    array('belongsTo' => array(
                            'User' => array(
                                'className' => 'User',
                                'foreignKey' => 'user_id',
                            )
                        )
                    )
            );
            $params = array();
            $params['conditions'] = array('invoice_id' => $id);
            $params['fields'] = array('InvoiceActivity.*', 'User.name', 'User.last_name');
            $log = $this->InvoiceActivity->find('all', $params);
            $this->set('activity', $log);
        } else {
            $this->render('ajax_invoice_page_view');
        }
    }

    function sendInvoiceEmail() {
        $this->loadModel('Invoice');
        parse_str($this->request['data']['v'], $d);
        $this->loadModel('Invoice');
        $id = $d['data']['invoiceId'];
        /* generate pdf */
        $this->createInvoicePdf($id);

        /* inovoice details */
        $i = $this->Invoice->findById($id);

        /* add activity log */
        $this->activity_log('email', $id);

        $subject = $d['data']['subject'];

        $this->Email->delivery = 'smtp';
        $this->Email->to = $d['data']['to'];

        $this->Email->subject = $subject;
        $this->Email->from = $d['data']['from'];
        $this->Email->template = 'invoice_email';
        $this->Email->sendAs = 'html';

        $id = $i["Invoice"]["id"];
        $invoice_no = $this->Format->seo_url($i["Invoice"]["invoice_no"], '_');
        $f = HTTP_INVOICE . 'invoice_' . $invoice_no . '.pdf';
        $f_path = HTTP_INVOICE_PATH . 'invoice_' . $invoice_no . '.pdf';
        $this->Email->attachments = array($f_path);

        $this->set('f', $f);
        $this->set('i', $i);
        $this->set('message', $d['message']);
        if ($this->Sendgrid->sendgridsmtp($this->Email)) {
            echo "success";
            @unlink($f_path);
        } else {
            echo "unsuccess";
        }
        exit;
    }

    function createInvoicePdf($invoice_id = '0') {
        $id = $invoice_id > 0 ? $invoice_id : $this->request['data']['v'];
        $this->loadModel('Invoice');
        $this->loadModel('InvoiceSetting');

        $i = $this->Invoice->findById($id);
        $settings = $this->InvoiceSetting->find('first', array('conditions' => array('company_id' => SES_COMP)));

        $invoice_no = $this->Format->seo_url($i["Invoice"]["invoice_no"], '_');

        $invoice_dir = WWW_ROOT . 'company_invoice';
        $filename = WWW_ROOT . 'company_invoice/invoice_' . $invoice_no . '.pdf';
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
        if (defined('USE_WKHTMLTOPDF') && USE_WKHTMLTOPDF == 1) {
            if (is_array($settings) && count($settings) > 0 && $settings['InvoiceSetting']['layout'] == 'landscape') {
                $layout = $settings['InvoiceSetting']['layout'];
                $orientation = " -O landscape ";
            }
            if (exec(PDF_LIB_PATH . $orientation . " " . HTTP_ROOT_INVOICE . "easycases/invoicePdf/" . $id . '/' . SES_COMP . '/' . $layout . " " . $filename)) {
                $this->Format->recursiveChmod($invoice_dir);
            } else {
                
            }
        } else {
            /*             * *Create pdf by using tcpdf** */
            $this->Mpdf = $this->Components->load('Mpdf');
            $this->Mpdf->init();
            $this->Mpdf->setFilename($filename);
            $this->Mpdf->customOutput('F', $this->requestAction('easycases/invoicePdf/' . $id . '/' . SES_COMP . '/' . $layout, array('return')));
            /*             * *End* */
        }
        if ($invoice_id > 0) {
            return true;
        } else {
            print $id;
            exit;
        }
    }

    function downloadPdf() {
        $this->loadModel('Invoice');

        $id = $this->request->params['pass'][0];
        $mode = $this->request->params['pass'][1];
        $i = $this->Invoice->findById($id);
        $invoice_no = $this->Format->seo_url($i['Invoice']['invoice_no'], '_');

        /* insert activity log */
        if ($mode != 'preview') {
            $this->activity_log('download', $id);
        }

        $file = WWW_ROOT . 'invoice' . DS . 'invoice_' . $invoice_no . '.pdf';
        if (file_exists($file)) {
            $this->response->file($file, array('download' => $mode == 'preview' ? false : true, 'name' => urlencode(basename($file))));
            return $this->response;
        } else {
            echo "file not found: " . $file;
        }
        exit;
    }

    function invoiceLogo() {
        // echo "<pre>";print_r($this->request->params);exit;
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $s3->putBucket(BUCKET_NAME, S3::ACL_PRIVATE);
        $ext = array('gif' => 'image/gif', // IMAGETYPE_GIF
            'jpg' => 'image/jpeg', // IMAGETYPE_JPEG
            'jpeg' => 'image/jpeg', // IMAGETYPE_JPEG
            'png' => 'image/png', // IMAGETYPE_PNG
            'bmp' => 'image/bmp',);
        $rep = array("'", " ", "?", "%", "&", "$", ",", "@");
        $repw = array("", "-", "", "", "", "", "", "");

        $p = $this->request->params['form']['photo'];
        $id = key($p['name']);
        $type = $p['type'][$id];
        if (!in_array($type, $ext)) {
            $ret_arr = array('msg' => "Please select an image.", 'success' => "no");
        } else {

            if ($p['name'][$id] != "") {
                if ($p['size'][$id] <= 2000000) {
                    $path2 = WWW_ROOT . "invoice-logo";
                    $s1 = time();
                    $realname = $p['name'][$id];
                    $realname = str_replace($rep, $repw, $realname);
                    $realname = $s1 . "_" . $realname;
                    $dest = INVOICE_LOGO_PATH . SES_COMP . DS . $realname;
                    $newname = $realname;
                    $tmp_name = $p['tmp_name'][$id];
                    $file_dimension = (getimagesize($tmp_name));
                    echo "<pre>";
                    print_r($file_dimension);
                    exit;
                    $sizeX = 200;
                    $sizeY = 200;

                    $width = $file_dimension[0];
                    $height = $file_dimension[1];
                    if ($width > 200 || $height > 200) {
                        echo json_encode(array('msg' => "Please select an image of height 200px and width 200px.", 'success' => "no"));
                        exit;
                    }

                    if ($width > $height) {
                        exec("convert " . $tmp_name . " -resize x" . $sizeX . " -quality 100 " . $tmp_name);
                    } else {
                        exec("convert " . $tmp_name . " -resize " . $sizeY . " -quality 100 " . $tmp_name);
                    }

                    /* saving to s3 invoice */
                    $folder_orig_Name = DIR_PHOTOS_S3_TEMP_FOLDER . trim($newname);
                    $s3->putObjectFile($tmp_name, BUCKET_NAME, $folder_orig_Name, S3::ACL_PRIVATE);
                    $info = $s3->getObjectInfo(BUCKET_NAME, $folder_orig_Name);

                    $img_name = trim($realname);
                    /* save the image name to db while editing invoice */
                    $view = new View($this);
                    $format_helper = $view->loadHelper('Format');
                    $url = $format_helper->generateTemporaryURL(DIR_PHOTOS_S3_TEMP . $img_name);

                    $ret_arr = array('msg' => $img_name, 'success' => "yes", 'url' => $url);
                } else {
                    $ret_arr = array('msg' => "exceeds", 'success' => "no");
                }
            } else {
                $ret_arr = array('msg' => "Please select an image.", 'success' => "no");
            }
        }
        echo json_encode($ret_arr);
        exit;
    }

    function save_invoice() {
        #pr($this->data);exit;
        $this->loadModel('Invoice');
        $this->loadModel('InvoiceLog');
        if (defined('TLG') && TLG == 1) {
            $this->loadModel('LogTime');
        }
        if (intval(SES_COMP) == 0) {
            exit;
        }
        $view = new View($this);
        $format_helper = $view->loadHelper('Format');

        $data = $this->data;
        $is_modified = trim($data['ismodified']);
        $invoice_id = intval($data['invoice_id']);
        if ($invoice_id > 0) {

            $this->Invoice->recursive = false;
            $invoice_old_data = $this->Invoice->findById($invoice_id);
        }
        $invoice_no = trim($data['edit_invoiceNo']);
        $mode = $invoice_id > 0 ? "edit" : "add";
        $error = false;
        $exist_condition = array();

        if ($invoice_no == '') {# && $mode == 'edit'
            $msg = 'Please enter invoice number.';
            $error = true;
        } elseif ($invoice_no != '') {
            /* checking if invoice number already exist */
            $this->Invoice->recursive = false;
            $exist_condition[] = "invoice_no='" . $invoice_no . "'";
            $exist_condition[] = "company_id='" . SES_COMP . "'";
            if ($invoice_id > 0) {
                $exist_condition[] = "id!=$invoice_id";
            }
            $exists = $this->Invoice->find('all', array('conditions' => $exist_condition));
            if (is_array($exists) && count($exists)) {
                $msg = 'Invoice number exist. Please enter another invoice number.';
                $error = true;
            }
        }
        if ($error == true) {
            echo json_encode(array('success' => 'No', 'msg' => $msg));
            exit;
        }
        $invoice = array(
            'id' => $invoice_id,
            'user_id' => SES_ID,
            'project_id' => $GLOBALS['getallproj'][0]['Project']['id'],
            'company_id' => SES_COMP,
            'invoice_no' => $invoice_no,
            'issue_date' => trim($data['edit_issue_date']) != '' ? date('Y-m-d H:i:s', strtotime($data['edit_issue_date'])) : '',
            'due_date' => trim($data['edit_due_date']) != '' ? date('Y-m-d H:i:s', strtotime($data['edit_due_date'])) : '',
            'notes' => trim($data['edit_notes']),
            'terms' => trim($data['edit_terms']),
            'invoice_from' => trim($data['edit_invoice_from']),
            'invoice_to' => trim($data['edit_invoice_to']),
            'tax' => trim($data['edit_tax']),
            'discount' => trim($data['edit_discount']),
            'discount_type' => trim($data['invoice_discount_type']),
            'customer_id' => intval($data['invoice_customer_id']),
            'currency' => trim($data['invoice_customer_currency']),
            'logo' => trim($data['logophoto']),
            'invoice_term' => trim($data['invoice_terms']),
            'modified' => date('Y-m-d H:i:s'),
        );
        if ($mode == 'edit') {
            
        } else {
            $invoice['uniq_id'] = $this->Format->generateUniqNumber();
            $invoice['ip'] = $this->request->clientIp();
            $invoice['created'] = date('Y-m-d H:i:s');
        }
        $this->Invoice->save($invoice);
        $invoice_id = $this->Invoice->id;
        $subTotal = 0;

        if (is_array($data['edit_log_date']) && count($data['edit_log_date']) > 0 && $invoice_id > 0) {
            $invoice_log_ids = $mode == 'edit' && isset($data['invoice_log_id']) ? $data['invoice_log_id'] : array();

            $i = 0;
            foreach ($data['edit_log_date'] as $key => $val) {
                $index = $key;
                $log_id = intval($invoice_log_ids[$index]) > 0 ? $invoice_log_ids[$index] : '';

                if (trim($data['rate_edit_total_hours'][$index]) != '' || trim($data['edit_total_hours'][$index]) != '' || trim($data['edit_description'][$index]) != '' || trim($data['edit_log_date'][$index]) != '') {
                    $logs[$i] = array(
                        'id' => $log_id,
                        'invoice_id' => $invoice_id,
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
                $this->InvoiceLog->saveAll($logs);
            }
        }
        $invoiceDiscount = $this->Format->format_price($invoice['discount_type'] != 'Flat' ? (($subTotal * floatval($invoice['discount'])) / 100) : floatval($invoice['discount']));
        $invoiceTax = $this->Format->format_price((($subTotal - $invoiceDiscount) * floatval($invoice['tax'])) / 100);
        $totalPrice = $this->Format->format_price(($subTotal - $invoiceDiscount + $invoiceTax));

        $update_data = array('price' => floatval($totalPrice));
        if ($mode == 'add' && $invoice_no == '') {
            
        }
        /* update invoice price */
        $this->Invoice->id = $invoice_id;
        $this->Invoice->save($update_data);
        /* end */
        if ($mode == 'add') {
            $this->activity_log('create', $invoice_id, true);
        } elseif ($mode == 'edit' && $is_modified == 'Yes') {
            $this->activity_log('modify', $invoice_id, true);
        }

        /* save invoice image */
        $filename = trim($data['logophoto']);
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $s3->putBucket(BUCKET_NAME, S3::ACL_PRIVATE);
        $source = DIR_PHOTOS_S3_TEMP_FOLDER;

        if ($invoice_old_data['Invoice']['logo'] != $filename) {
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

        echo json_encode(array('success' => 'Yes', 'id' => $invoice_id));
        exit;
    }

    function ajaxInvoiceList() {
        $this->layout = 'ajax';
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $this->loadModel('Invoice');

        $order_by = 'created';
        $order_sort = 'DESC';
        if (isset($this->data['params'])) {
            $sort_by = (isset($this->data['params']['sortby'])) ? trim($this->data['params']['sortby']) : '';
            $order_sort = (isset($this->data['params']['order'])) ? trim($this->data['params']['order']) : ' ASC';
            switch ($sort_by) {
                case"invoice":$order_by = 'invoice_no';
                    break;
                case"invoice_date":$order_by = 'issue_date';
                    break;
                case"customer":$order_by = 'customer_name';
                    break;
                case"due_date":$order_by = 'due_date';
                    break;
                case"amount":$order_by = 'price';
                    break;
            }
        }
        $page_limit = INVOICE_PAGE_LIMIT;
        $page = 1;

        if (isset($this->data['page']) && $this->data['page']) {
            $page = $this->data['page'];
        }
        $limit1 = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;
        $conditions = array('Invoice.project_id' => $prjid, 'Invoice.is_active' => 1, "IF(Invoice.customer_id>0,InvoiceCustomer.status='Active',1=1)");
        if ((SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) && ($this->Auth->user('is_client') != 1)) {
            
        } else {
            $conditions[] = "InvoiceCustomer.user_id='" . SES_ID . "'";
        }
        $options = array();
        $options['conditions'] = $conditions;
        $options['recursive'] = false;
        $options['joins'] = array(
            array('table' => 'invoice_customers', 'alias' => 'InvoiceCustomer', 'type' => 'LEFT', 'conditions' => array('InvoiceCustomer.id = Invoice.customer_id')),
        );

        $caseCount = $this->Invoice->find('count', $options);

        $options['fields'] = array('Invoice.*', "CONCAT_WS(' ',InvoiceCustomer.title,InvoiceCustomer.first_name,InvoiceCustomer.last_name) AS customer_name");
        $options['order'] = $order_by . ' ' . $order_sort;
        $options['limit'] = $limit2;
        $options['offset'] = $limit1;

        $inv = $this->Invoice->find('all', $options);
        $this->set('inv', $inv);
        $this->set('caseCount', $caseCount);
        $this->set('page_limit', $page_limit);
        $this->set('page', $page);
        $this->set('casePage', $page);
        $this->set('order_by', $sort_by);
        $this->set('order_sort', $order_sort);
    }

    /* Author: STJ
     * for listing of archived invoices
     */

    function ajaxArchivedInvoiceList() {
        $this->layout = 'ajax';
        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $this->loadModel('Invoice');

        $order_by = 'created';
        $order_sort = 'DESC';
        if (isset($this->data['params'])) {
            $sort_by = (isset($this->data['params']['sortby'])) ? trim($this->data['params']['sortby']) : '';
            $order_sort = (isset($this->data['params']['order'])) ? trim($this->data['params']['order']) : ' ASC';
            switch ($sort_by) {
                case"invoice":$order_by = 'invoice_no';
                    break;
                case"invoice_date":$order_by = 'issue_date';
                    break;
                case"customer":$order_by = 'customer_name';
                    break;
                case"due_date":$order_by = 'due_date';
                    break;
                case"amount":$order_by = 'price';
                    break;
            }
        }
        $page_limit = INVOICE_PAGE_LIMIT;
        $page = 1;

        if (isset($this->data['page']) && $this->data['page']) {
            $page = $this->data['page'];
        }
        $limit1 = $page * $page_limit - $page_limit;
        $limit2 = $page_limit;
        $conditions = array('Invoice.project_id' => $prjid, 'Invoice.is_active' => 0, "IF(Invoice.customer_id>0,InvoiceCustomer.status='Active',1=1)");
        if ((SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) && ($this->Auth->user('is_client') != 1)) {
            
        } else {
            $conditions[] = "InvoiceCustomer.user_id='" . SES_ID . "'";
        }
        $options = array();
        $options['conditions'] = $conditions;
        $options['recursive'] = false;
        $options['joins'] = array(
            array('table' => 'invoice_customers', 'alias' => 'InvoiceCustomer', 'type' => 'LEFT', 'conditions' => array('InvoiceCustomer.id = Invoice.customer_id')),
        );

        $caseCount = $this->Invoice->find('count', $options);

        $options['fields'] = array('Invoice.*', "CONCAT_WS(' ',InvoiceCustomer.title,InvoiceCustomer.first_name,InvoiceCustomer.last_name) AS customer_name");
        $options['order'] = $order_by . ' ' . $order_sort;
        $options['limit'] = $limit2;
        $options['offset'] = $limit1;

        $inv = $this->Invoice->find('all', $options);
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
        $this->loadModel('Invoice');
        $this->loadModel('InvoiceLog');
        if (defined('TLG') && TLG == 1) {
            $this->loadModel('LogTime');
        }

        $id = $this->request['data']['v'];
        $this->Invoice->id = $id;
        $org_Img = $this->Invoice->field('logo');
        @unlink(WWW_ROOT . "invoice-logo/" . $org_Img);

        $InvoiceLog = $this->InvoiceLog->find('all', array('fields' => array('log_id'), 'conditions' => array('invoice_id' => $id)));
        if (is_array($InvoiceLog) && count($InvoiceLog) > 0) {
            if (defined('TLG') && TLG == 1) {
                foreach ($InvoiceLog as $k => $v) {
                    $this->LogTime->query("update log_times set task_status=0 where log_id='" . $v['InvoiceLog']['log_id'] . "'");
                }
            }
            $this->InvoiceLog->delete("invoice_id='" . $id . "'");
        }
        echo ($this->Invoice->delete($id)) ? 1 : 0;
        exit;
    }

    /* By CP
     * to delete invoice logs
     */

    function deleteInvoiceTimeLog() {
        $this->loadModel('InvoiceLog');
        $id = $this->request['data']['v'];
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

    /*
     * Used to save individual fields after change in invoice details page
     */

    function editInvoice() {
        $this->layout = 'ajax';
        $this->loadModel('Invoice');
        $this->loadModel('InvoiceLog');

        $id = trim($this->request['data']['pk']);
        $val = trim($this->request['data']['value']);

        if ($this->request['data']['name'] == 'edit_invoiceNo') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('invoice_no', $val);
        } elseif (strpos($this->request['data']['name'], 'edit_description') !== false) {
            $this->InvoiceLog->id = $id;
            $this->InvoiceLog->saveField('description', $val);
        } elseif (strpos($this->request['data']['name'], 'rate_edit_total_hours') !== false) {
            $this->InvoiceLog->id = $id;
            $this->InvoiceLog->saveField('rate', $val);
        } elseif (strpos($this->request['data']['name'], 'edit_total_hours') !== false) {
            $this->InvoiceLog->id = $id;
            $this->InvoiceLog->saveField('total_hours', $val);
        } elseif ($this->request['data']['name'] == 'edit_notes') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('notes', $val);
        } elseif ($this->request['data']['name'] == 'edit_terms') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('terms', $val);
        } elseif ($this->request['data']['name'] == 'edit_issue_date') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('issue_date', trim($val) != '' ? date('Y-m-d', strtotime($val)) : '');
        } elseif ($this->request['data']['name'] == 'edit_due_date') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('due_date', trim($val) != '' ? date('Y-m-d', strtotime($val)) : '');
        } elseif ($this->request['data']['name'] == 'edit_log_date') {
            $this->InvoiceLog->id = $id;
            $this->InvoiceLog->saveField('task_date', trim($val) != '' ? date('Y-m-d', strtotime($val)) : '');
        } elseif ($this->request['data']['name'] == 'edit_invoice_from') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('invoice_from', $val);
        } elseif ($this->request['data']['name'] == 'edit_invoice_to') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('invoice_to', $val);
        } elseif ($this->request['data']['name'] == 'edit_price') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('price', $val);
        } elseif ($this->request['data']['name'] == 'edit_tax') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('tax', $val);
        } elseif ($this->request['data']['name'] == 'edit_discount') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('discount', $val);
        } elseif ($this->request['data']['name'] == 'edit_discount_type') {
            $this->Invoice->id = $id;
            $this->Invoice->saveField('discount_type', $val);
        }
        echo "Success";
        exit;
    }

    function invoicePdf($id, $company_id, $layout) {
        pr($layout);
        exit;
        $this->layout = false;
        $this->loadModel('Invoice');
        $i = $this->Invoice->findById($id);
        $this->set('i', $i);
        $this->set('company_id', $company_id);
        $this->set('layout', $layout);
    }

    function add2Invoice() {
        $this->layout = 'ajax';
        $this->loadModel('InvoiceLog');
        $this->loadModel('Invoice');
        $this->loadModel('LogTime');

        $invoiceId = $this->request['data']['invoice'];
        $price = $this->Invoice->findById($invoiceId);

        $arr = explode(',', $this->request['data']['log']);

        foreach ($arr as $k => $v) {
            $log = $this->LogTime->findByLogId($v);
            $InvoiceLog['user_id'] = SES_ID;
            $InvoiceLog['invoice_id'] = $this->request['data']['invoice'];
            $InvoiceLog['log_id'] = $log['LogTime']['log_id'];
            $InvoiceLog['task_date'] = $log['LogTime']['task_date'];
            $InvoiceLog['start_time'] = $log['LogTime']['start_time'];
            $InvoiceLog['end_time'] = $log['LogTime']['end_time'];
            $InvoiceLog['total_hours'] = round($log['LogTime']['total_hours'] / 3600, 2);
            $InvoiceLog['description'] = $log['LogTime']['description'];
            $InvoiceLog['task_status'] = $log['LogTime']['task_status'];
            $InvoiceLog['rate'] = NULL;
            $InvoiceLog['ip'] = $this->request->clientIp();
            $InvoiceLog['start_datetime'] = $log['LogTime']['start_datetime'];
            $InvoiceLog['end_datetime'] = $log['LogTime']['end_datetime'];

            $this->InvoiceLog->save($InvoiceLog);
            $this->InvoiceLog->id = '';
            $this->LogTime->query('update log_times set task_status=1 where log_id=' . $log['LogTime']['log_id']);
        }
        exit;
    }

    function settings() {
        $this->loadModel('InvoiceSetting');
        $result = $this->InvoiceSetting->find('first', array('conditions' => array('company_id' => SES_COMP)));
        if (is_array($result) && count($result) > 0) {
            $layout = $result['InvoiceSetting']['layout'];
        } else {
            $layout = 'portrait';
        }
        $this->set('layout', $layout);
    }

    function save_settings() {
        $this->loadModel('InvoiceSetting');

        if (SES_COMP == '') {
            $this->Session->write("ERROR", "Company not found");
            $this->redirect(HTTP_ROOT . 'invoice-settings');
        }
        $layout = isset($this->data['Settings']['layout']) && trim($this->data['Settings']['layout']) != '' ? trim($this->data['Settings']['layout']) : "portrait";

        $result = $this->InvoiceSetting->find('first', array('conditions' => array('company_id' => SES_COMP)));
        if (is_array($result) && count($result) > 0) {
            $data = array('company_id' => SES_COMP, 'layout' => $layout, 'id' => $result['InvoiceSetting']['id']);
        } else {
            $data = array('company_id' => SES_COMP, 'layout' => $layout);
        }
        $this->InvoiceSetting->save($data);
        $this->Session->write("SUCCESS", "Invoice setting updated successfully.");
        $this->redirect(HTTP_ROOT . 'invoice-settings');
    }

    function activity_log($act = '', $invoice_id = '', $flag = false) {
        $this->loadModel('InvoiceActivity');
        $this->loadModel('Invoice');
        #$this->loadModel('InvoiceLog');
        if ($flag == true) {
            $lastinvoice = $this->Invoice->find('first', array('conditions' => array('company_id' => SES_COMP, "id" => $invoice_id)));
        }

        $prjid = $GLOBALS['getallproj'][0]['Project']['id'];
        $prjuniqueid = $GLOBALS['getallproj'][0]['Project']['uniq_id'];
        $data = array(
            "id" => "",
            "company_id" => SES_COMP,
            "project_id" => $prjid,
            "invoice_id" => $invoice_id,
            "user_id" => SES_ID,
            "created" => date('Y-m-d H:i:s'),
            "ip" => $this->request->clientIp(),
            "activity" => $act,
            "invoice" => $flag == true ? base64_encode(json_encode($lastinvoice)) : '',
        );
        $this->InvoiceActivity->save($data);
        return $this->InvoiceActivity->id;
    }

    /* Author: STJ
     * for marking Invoice as paid
     */

    function markInvoicePaid() {
        $this->loadModel('Invoice');
        $invoiceId = $this->request->data['id'];
        $this->Invoice->id = $invoiceId;
        if ($this->Invoice->saveField('is_paid', 1)) {
            if ($this->activity_log('paid', $invoiceId, true)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for marking Invoice as unpaid
     */

    function markUnpaidInvoice() {
        $this->loadModel('Invoice');
        $invoiceId = $this->request->data['id'];
        $this->Invoice->id = $invoiceId;
        if ($this->Invoice->saveField('is_paid', 0)) {
            if ($this->activity_log('paid', $invoiceId, true)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for marking Invoices as paid
     */

    function markGroupInvoicePaid() {
        $this->loadModel('Invoice');
        $c = 0;
        $invoiceIds = $this->request->data['ids'];
        foreach ($invoiceIds as $id) {
            $this->Invoice->id = $id;
            if ($this->Invoice->saveField('is_paid', 1)) {
                if ($this->activity_log('unpaid', $id, true)) {
                    $c++;
                }
            }
        }
        if ($c == count($invoiceIds)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for marking Invoices as unpaid
     */

    function markGroupInvoiceUnpaid() {
        $this->loadModel('Invoice');
        $c = 0;
        $invoiceIds = $this->request->data['ids'];
        foreach ($invoiceIds as $id) {
            $this->Invoice->id = $id;
            if ($this->Invoice->saveField('is_paid', 0)) {
                if ($this->activity_log('unpaid', $id, true)) {
                    $c++;
                }
            }
        }
        if ($c == count($invoiceIds)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for archiving Invoice
     */

    function archiveInvoice() {
        $this->loadModel('Invoice');
        $invoiceId = $this->request->data['id'];
        $this->Invoice->id = $invoiceId;
        if ($this->Invoice->saveField('is_active', 0)) {
            if ($this->activity_log('archive', $invoiceId, true)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for restoring Invoice
     */

    function restoreInvoice() {
        $this->loadModel('Invoice');
        $invoiceId = $this->request->data['id'];
        $this->Invoice->id = $invoiceId;
        if ($this->Invoice->saveField('is_active', 1)) {
            if ($this->activity_log('restore', $invoiceId, true)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for restoring Invoices
     */

    function groupRestoreInvoice() {
        $this->loadModel('Invoice');
        $c = 0;
        $invoiceIds = $this->request->data['ids'];
        foreach ($invoiceIds as $id) {
            $this->Invoice->id = $id;
            if ($this->Invoice->saveField('is_active', 1)) {
                if ($this->activity_log('restore', $id, true)) {
                    $c++;
                }
            }
        }
        if ($c == count($invoiceIds)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    /* Author: STJ
     * for archiving Invoices
     */

    function groupArchiveInvoice() {
        $this->loadModel('Invoice');
        $c = 0;
        $invoiceIds = $this->request->data['ids'];
        foreach ($invoiceIds as $id) {
            $this->Invoice->id = $id;
            if ($this->Invoice->saveField('is_active', 0)) {
                if ($this->activity_log('archive', $id, true)) {
                    $c++;
                }
            }
        }
        if ($c == count($invoiceIds)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

}
