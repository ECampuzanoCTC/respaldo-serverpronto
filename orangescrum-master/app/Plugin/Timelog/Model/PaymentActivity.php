<?php

class PaymentActivity extends TimelogAppModel {

    public $name = 'PaymentActivity';
    public $belongsTo = array(
        'Payment' => array(
            'className' => 'Payment',
            'foreignKey' => 'payment_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
    );

}
