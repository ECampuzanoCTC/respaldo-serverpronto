<?php

class Invoice extends InvoiceAppModel {
	public $name = 'Invoice';
        public $hasMany = array(
                   'InvoiceLog' => array(                      
                      'dependent' => true,
                       'order' => 'created ASC',  
                    )
                 );	
}

?>
