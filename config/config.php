<?php 
define('HOST','localhost');
define('USER','cashbook_user');  //vnrseed2_agrimat
define('PASS','cashbook@192');  //ajay_scpms
define('DATABASE1','masters');
define('DATABASE2','cashbook');
//define('DATABASE3','agrimatr_stores');
define('PAGING','30');

$link_cashbook = mysql_connect(HOST,USER,PASS);
$dblink1_cashbook = mysql_select_db(DATABASE1,$link_cashbook);
$dblink2_cashbook = mysql_select_db(DATABASE2,$link_cashbook);
//$dblink3_cashbook = mysql_select_db(DATABASE3,$link_cashbook);

mysql_query("SET SESSION sql_mode = 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
?>