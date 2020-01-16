<?php

class InvoiceCustomer extends AppModel {
    
    function getCustomerList(){
        $this->virtualFields = array(
    'full_name' => 'CONCAT(first_name, " ", last_name)');
        $custmr_lst = $this->find('list',array('conditions'=>array('status'=>'Active'),'fields'=>array('id','organization')));
        
       // echo "<pre>";print_r($custmr_lst);exit;
        return $custmr_lst ;
    }
}

?>
