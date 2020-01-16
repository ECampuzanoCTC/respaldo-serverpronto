<?php

class Payment extends TimelogAppModel {

    var $name = 'Payment';
    var $primaryKey = 'id';
    public $hasMany = array(
                   'PaymentLog' => array(                      
                      'dependent' => true,
                       'order' => 'created ASC',  
                    )
                 );	
}

?>
