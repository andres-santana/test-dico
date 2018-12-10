<?php
$mageconf = '../app/etc/local.xml';  // Mage local.xml config
$mageapp  = '../app/Mage.php';       // Mage app location
require_once '../app/Mage.php';
require_once '../lib/rc4crypt.php';
umask(0);
Mage::app();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
}