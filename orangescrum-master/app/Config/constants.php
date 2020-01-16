<?php

########################################################################
##################### Email Sending Options ############################
########################################################################
//Make sure to enable "php_openssl" in PHP. In WAMP, you need to enable extension=php_openssl.dll on php.ini file 
define("EMAIL_DELIVERY", "mail");
//Gmail SMTP
define("SMTP_HOST", "ssl://smtp.gmail.com");
define("SMTP_PORT", "465");
define("SMTP_UNAME", "admin@ctconsulting.com.mx");
define("SMTP_PWORD", "ctconsulting$2018");
//https://www.arclab.com/en/amlc/list-of-smtp-and-imap-servers-mailserver-list.html (Get the list of Host names)
### OR ###

/*
  //Mandrill smtp
  define("SMTP_HOST", "smtp.mandrillapp.com");
  define("SMTP_PORT", "58");
  define("SMTP_UNAME", "youremail@domain.com");
  define("SMTP_PWORD", "******"); //Mandrill API Key
  //https://www.mandrill.com/signup/ (free signup to mandrill)
 */

### OR ###

/*
  //Sendgrid smtp
  define("SMTP_HOST", "smtp.sendgrid.net");
  define("SMTP_PORT", "587");
  define("SMTP_UNAME", "youremail@domain.com");
  define("SMTP_PWORD", "******");
  //https://sendgrid.com/user/signup (free signup to sendgrid)
 */

########################################################################
define("WEB_DOMAIN", "Ctconsulting.com.mx"); //ex. demo.orangescrum.com
define('FROM_EMAIL_NOTIFY', 'malavez@ctconsulting.com.mx'); //(REQUIRED)
define('SUPPORT_EMAIL', 'rjimenez@ctconsulting.com.mx'); //(REQUIRED) From Email
define('FROM_EMAIL', 'Orangescrum<' . SUPPORT_EMAIL . '>');

define("DEV_EMAIL", 'developer@mycompany.com'); // Developer Email ID to report the application error
define('EMAIL_SUBJ', '[Orangescrum]');

// If you have not yet set up the Nohup cronjob, leave it blank
define('EMAIL_REPLY', "<div style='font-family:Arial;font-size:14px;color:#787878;margin-bottom:5px;'>Just REPLY to this Email the same will be added under the Task. <br/><span style='font-size:11px;'><b>NOTE:</b> Do not remove this original message.</span></div>");

define('RELEASE', 1); //Increase the release version on every CSS/JS changes to remove the browser cache
##################### Domain and URL Constants ############################
define('SUB_FOLDER', ''); //If your application URL followed by a folder name like: http://your-site.com/folder_name, put your folder name as 'folder_name/'

if (php_sapi_name() === "cli") {
    define('PROTOCOL', "http://");
    define('DOMAIN', "www.my-orangescrum.com/"); // Please set your application domain (REQUIRED)
} else {
    $ht = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
    define('PROTOCOL', $ht);
    if ($_SERVER['SERVER_PORT'] != 80)
        define('DOMAIN', $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/");
    else
        define('DOMAIN', $_SERVER['SERVER_NAME'] . "/");
}
define('HTTP_SERVER', PROTOCOL . DOMAIN);
define('HTTP_ROOT', HTTP_SERVER . SUB_FOLDER);
define('HTTP_ROOT_INVOICE', "http://" . $_SERVER['SERVER_NAME'] . "/" . SUB_FOLDER);
define('DOMAIN_COOKIE', $_SERVER['SERVER_NAME']);
define('HTTP_APP', PROTOCOL . DOMAIN . SUB_FOLDER);
define('HTTPS_HOME', PROTOCOL . DOMAIN . SUB_FOLDER);
define('HTTP_HOME', "http://" . DOMAIN . SUB_FOLDER);

##################### Google Keys (Login, Drive, Contacts) ############################
define("CLIENT_ID", "XXXXXXXXXXXX.apps.googleusercontent.com");
define("CLIENT_ID_NUM", "XXXXXXXXXXXX");
define("CLIENT_SECRET", "xXxXXxxxx_xXxXXxxxx");
define("API_KEY", "xXxXXxxxxxXXXXXXXXXXXXXxXXxxxx");
define("REDIRECT_URI", HTTP_ROOT . "users/googleConnect");

define("USE_GOOGLE", 0); //Set this parameter to 1, to use Google Login, Drive and Contacts
##################### Dropbox Key ############################
define("DROPBOX_KEY", "xXxxXxxxXx");

define("USE_DROPBOX", 0); //Set this parameter to 1, to use Dropbox file sharing
##################### AWS S3 Bucket ############################
define('USE_S3', 0); //Set this parameter to 1 to use AWS S3 Bucket
define('BUCKET_NAME', 'Bucket Name');
define('DOWNLOAD_BUCKET_NAME', 'Download Bucket Name');
define('awsAccessKey', 'XXXXXXXXXXXXXX');
define('awsSecretKey', 'XXXX/XXXXXXXXXXXXXX/+XXXXXXXXXXXXXX');
define('DOWNLOAD_S3_TASK_PATH', 'DownloadTask/zipTask/');

define('DIR_CASE_FILES_S3', 'https://s3.amazonaws.com/' . BUCKET_NAME . '/files/case_files/');
define('DIR_USER_PHOTOS_S3', 'https://s3.amazonaws.com/' . BUCKET_NAME . '/files/photos/');
define('DIR_USER_PHOTOS_S3_TEMP', 'https://s3.amazonaws.com/' . BUCKET_NAME . '/files/temp/');
define('DIR_USER_COMPANY_S3', 'https://s3.amazonaws.com/' . BUCKET_NAME . '/files/company/');
define('DIR_USER_PHOTOS_S3_FOLDER', 'files/photos/');
define('DIR_USER_COMPANY_S3_FOLDER', 'files/company/');
define('DIR_CASE_FILES_S3_FOLDER', 'files/case_files/');
define('DIR_CASE_FILES_S3_FOLDER_TEMP', 'files/case_files/temp/');

define('CHAT_PATH', WWW_ROOT.'chat-imgs'.DS);
define('CHAT_FILE', HTTP_ROOT.'chat-imgs/');

define('MAX_FILE_SIZE', 200); //In Mb
define('CASE_PAGE_LIMIT', 30); // Task Listing Page
define('INVOICE_PAGE_LIMIT', 10);
define('MILESTONE_PAGE_LIMIT', 5);
define('PROJECT_PAGE_LIMIT', 30);
define('MILE_PAGE_LIMIT', 30);
define('USER_PAGE_LIMIT', 30);
define('ARC_PAGE_LIMIT', 30);
define('MAX_SPACE_USAGE', 1024);
define('MILESTONE_PER_PAGE', 3);
define('ARC_CASE_PAGE_LIMIT', 10);
define('ARC_FILE_PAGE_LIMIT', 10);
define('TEMP_PROJECT_PAGE_LIMIT', 10);
define('TEMP_TASK_PAGE_LIMIT', 10);
define('FILTER_PAGE_LIMIT', 30);

define('TIMELIMIT', 3000);

define('ONBORDING', 1);
define('ONBORDING_DAILY_UPDATE', 1);
define('ONBORDING_DATE', '2014-01-01');

define('GMT_DATETIME', gmdate('Y-m-d H:i:s'));
define('GMT_DATE', gmdate('Y-m-d'));
define('GMT_TIME', gmdate('H:i:s'));

define('USE_LOCAL', 1);

########## Cookie Settings ############
if (isset($_COOKIE['REMEMBER']) && $_COOKIE['REMEMBER']) {
    define('COOKIE_TIME', time() + 3600 * 24 * 7);
} else {
    define('COOKIE_TIME', time() + 3600 * 2);
}
define('COOKIE_REM', time() + 3600 * 24 * 30);

define('CSS_PATH', HTTP_ROOT . 'css/');
define('JS_PATH', HTTP_ROOT . 'js/');
if (!defined('WWW_ROOT')) {
    $dir = dirname(__FILE__);
    define('WWW_ROOT', substr($dir, 0, stripos($dir, 'webroot') + strlen('webroot')) . DS);
}
if (WWW_ROOT == 'WWW_ROOT') {
    $w_root = $_SERVER['DOCUMENT_ROOT'] . '/' . SUB_FOLDER . 'app/webroot/';
    define('DIR_IMAGES', $w_root . 'img/');
    define('CSV_PATH', $w_root . 'csv/');
    define('DOWNLOAD_TASK_PATH', $w_root . 'DownloadTask/');
    define('DIR_FILES', $w_root . 'files/');
    define('DIR_CASE_FILES', DIR_FILES . 'case_files/');
    define('DIR_USER_PHOTOS', DIR_FILES . 'photos/');
} else {
    define('DIR_IMAGES', WWW_ROOT . 'img/');
    define('CSV_PATH', WWW_ROOT . 'csv/');
    define('DOWNLOAD_TASK_PATH', WWW_ROOT . 'DownloadTask/');
    define('DIR_FILES', WWW_ROOT . 'files/');
    define('DIR_CASE_FILES', DIR_FILES . 'case_files/');
    define('DIR_USER_PHOTOS', DIR_FILES . 'photos/');
}
define('DIR_USER_PHOTOS_TEMP', 'files/temp/');
define('DIR_USER_PHOTOS_THUMB', 'files/thumb/');

define('HTTP_IMAGES', HTTP_ROOT . 'img/');
define('HTTP_FILES', HTTP_ROOT . 'files/');
define('HTTP_CASE_FILES', HTTP_FILES . 'case_files/');
define('HTTP_USER_PHOTOS', HTTP_FILES . 'photos/');
define('RESOURCE_UTILIZATION_CSV_PATH', WWW_ROOT . 'files' . DS . 'resource_utilization' . DS);
define('LOGTIME_CSV_PATH', WWW_ROOT . 'files' . DS . 'log_time' . DS);

define('PDF_LIB_PATH', '');
# For Example
#define('PDF_LIB_PATH', '/home/ec2-user/wkhtmltox/bin/wkhtmltopdf');
define('HTTP_INVOICE', HTTP_ROOT . 'company_invoice/');
define('HTTP_INVOICE_PATH', WWW_ROOT . 'company_invoice' . DS);

define('HTTP_PAYMENT', HTTP_ROOT . 'user_payment/');
define('HTTP_PAYMENT_PATH', WWW_ROOT . 'user_payment' . DS);
define('USE_WKHTMLTOPDF', 0);
define('WEB_ROOT', ROOT . DS . APP_DIR . DS . 'webroot');
define('PLUGIN', ROOT . DS . APP_DIR . DS . 'Plugin');

/**Require socket.io and node.js for instance messaging.**/
define("NODEJS_HOST",''); //enable this, if you have Node.js setup in the server
// If you are enabling NODEJS_HOST, make sure you have PHP version >5.3.0
// Also, remove comment on "use ElephantIO\Client as ElephantIOClient" from the following files
// app/Controller/EasycasesController.php, app/Controller/Component/PostcaseComponent.php, app/webroot/EmailReply.php


define('TWELVE', 1);
define('TWENTYFOUR', 2);
define('TIMEFORMAT', json_encode(array(TWELVE => '12-Hour', TWENTYFOUR => '24-Hour')));
define('MDATE', 1);
define('DDATE', 2);
define('DATEFORMAT', json_encode(array(MDATE => 'mmm dd, yyyy', DDATE => 'dd mmm, yyyy')));