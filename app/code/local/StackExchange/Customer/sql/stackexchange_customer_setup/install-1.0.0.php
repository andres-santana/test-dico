<?php
$this->addAttribute('customer', 'numero_interior', array(
    'type'      => 'varchar',
    'label'     => 'Número Interior',
    'input'     => 'text',
    'position'  => 120,
    'required'  => false,//or true
    'is_system' => 0,
));

$this->addAttribute('customer', 'referencia', array(
    'type'      => 'varchar',
    'label'     => 'Referencia',
    'input'     => 'text',
    'position'  => 121,
    'required'  => false,//or true
    'is_system' => 0,
));

$this->addAttribute('customer', 'numero_exterior', array(
    'type'      => 'varchar',
    'label'     => 'Número',
    'input'     => 'text',
    'position'  => 122,
    'required'  => false,//or true
    'is_system' => 0,
));
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'numero_interior');
$attribute->setData('used_in_forms', array(
    'adminhtml_customer',
    'checkout_register',
    'customer_account_create',
    'customer_account_edit',
));
$attribute->setData('is_user_defined', 0);
$attribute->save();

$attribute2 = Mage::getSingleton('eav/config')->getAttribute('customer', 'referencia');
$attribute2->setData('used_in_forms', array(
    'adminhtml_customer',
    'checkout_register',
    'customer_account_create',
    'customer_account_edit',
));
$attribute2->setData('is_user_defined', 0);
$attribute2->save();

$attribute3 = Mage::getSingleton('eav/config')->getAttribute('customer', 'numero_exterior');
$attribute3->setData('used_in_forms', array(
    'adminhtml_customer',
    'checkout_register',
    'customer_account_create',
    'customer_account_edit',
));
$attribute3->setData('is_user_defined', 0);
$attribute3->save();