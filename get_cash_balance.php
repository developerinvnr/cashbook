<?php session_start();
require_once('config/config.php');
/*---------------------*/
if(isset($_POST['id']) && ($_POST['id']!="" || $_POST['id']!=0)){
	$cid = $_POST['id'];
	$currentDate = $_POST['dt'];
	/*---------------------*/
	$sql=mysql_db_query(DATABASE2,"SELECT Sum(op_amount) AS amount FROM tblopening WHERE cih_id=".$cid) or die(mysql_error());
	$res=mysql_fetch_assoc($sql);
	$opBal = ($res['amount']==null ? 0 : $res['amount']);
	/*---------------------*/
	$sql=mysql_db_query(DATABASE2,"SELECT Sum(vouch_total) AS cur_balance FROM tblcash1 WHERE cih_id=".$cid." AND vouch_date<='".date("Y-m-d",strtotime($currentDate))."'");
	$res=mysql_fetch_assoc($sql);
	$current_balance = $opBal + ($res['cur_balance']==null ? 0 : $res['cur_balance']);
	/*---------------------*/
	$sql=mysql_db_query(DATABASE2,"SELECT Max(op_date) AS max_date FROM tblopening WHERE cih_id=".$cid);
	$res=mysql_fetch_assoc($sql);
	if($res['max_date']!=null){
		$minimum_date1 = date("d-m-Y",strtotime($res['max_date']));
	} else {
		$minimum_date1 = date("d-m-Y",strtotime($_SESSION['cashbook_syr']));
	}
	$sql=mysql_db_query(DATABASE2,"SELECT Max(vouch_date) AS max_date FROM tblcash1 WHERE cih_id=".$cid);
	$res=mysql_fetch_assoc($sql);
	if($res['max_date']!=null){
		$minimum_date2 = date("d-m-Y",strtotime($res['max_date']));
	} else {
		$minimum_date2 = date("d-m-Y",strtotime($_SESSION['cashbook_syr']));
	}
	$minimum_date = date("d-m-Y",max(strtotime($minimum_date1),strtotime($minimum_date2)));
	/*---------------------*/
	if($current_balance==0)
		echo number_format($current_balance,2,".","")."~~".$current_balance."~~".$minimum_date;
	elseif($current_balance<0)
		echo number_format(-1*$current_balance,2,".","")." DR"."~~".$current_balance."~~".$minimum_date;
	elseif($current_balance>0)
		echo number_format($current_balance,2,".","")." CR"."~~".$current_balance."~~".$minimum_date;
}
?>