<?php session_start();
require_once('config/config.php');
include("function.php");
if(check_user()==false){header("Location: login.php");}
/*----------------------------*/
if($_SESSION['cashbook_utype']=="A" || $_SESSION['cashbook_utype']=="S"){
	$sql=mysql_db_query(DATABASE2,"SELECT Sum(op_amount) AS amount FROM tblopening WHERE location_id=0") or die(mysql_error());
	$res=mysql_fetch_assoc($sql);
	$opBal = ($res['amount']==null ? 0 : $res['amount']);
	/*----------------------------*/
	$sql=mysql_db_query(DATABASE2,"SELECT Sum(vouch_total) AS cur_balance FROM tblcash1 WHERE location_id=0 AND vouch_date<='".date("Y-m-d")."'") or die(mysql_error());
} elseif($_SESSION['cashbook_utype']=="U"){
	$sql=mysql_db_query(DATABASE2,"SELECT Sum(op_amount) AS amount FROM tblopening WHERE location_id=".$_SESSION['cashbook_locid']." AND cih_id=".$_SESSION['cashbook_cihid']) or die(mysql_error());
	$res=mysql_fetch_assoc($sql);
	$opBal = ($res['amount']==null ? 0 : $res['amount']);
	/*----------------------------*/
	$sql=mysql_db_query(DATABASE2,"SELECT Sum(vouch_total) AS cur_balance FROM tblcash1 WHERE location_id=".$_SESSION['cashbook_locid']." AND cih_id=".$_SESSION['cashbook_cihid']." AND vouch_date<='".date("Y-m-d")."'") or die(mysql_error());
}
$res=mysql_fetch_assoc($sql);
/*----------------------------*/
$current_balance = $opBal + ($res['cur_balance']==null ? 0 : $res['cur_balance']);
if($current_balance==0)
	$balance = number_format($current_balance,2,".","");
elseif($current_balance<0)
	$balance = number_format(-1*$current_balance,2,".","")." DR";
elseif($current_balance>0)
	$balance = number_format($current_balance,2,".","")." CR";
/*----------------------------*/
if($_SESSION['cashbook_utype']=="A" || $_SESSION['cashbook_utype']=="S"){
	$minimum_date = date("d-m-Y",strtotime($_SESSION['cashbook_syr']));
} elseif($_SESSION['cashbook_utype']=="U"){
	$sql=mysql_db_query(DATABASE2,"SELECT Max(op_date) AS max_date FROM tblopening WHERE cih_id=".$_SESSION['cashbook_cihid']) or die(mysql_error());
	$res=mysql_fetch_assoc($sql);
	$minimum_date1 = ($res['max_date']==null ? date("d-m-Y",strtotime($_SESSION['cashbook_syr'])) : date("d-m-Y",strtotime($res['max_date'])));
	/*----------------------------*/
	$sql=mysql_db_query(DATABASE2,"SELECT Max(vouch_date) AS max_date FROM tblcash1 WHERE cih_id=".$_SESSION['cashbook_cihid']);
	$res=mysql_fetch_assoc($sql);
	if($res['max_date']!=null){
		$minimum_date2 = date("d-m-Y",strtotime($res['max_date']));
	} else {
		$minimum_date2 = date("d-m-Y",strtotime($_SESSION['cashbook_syr']));
	}
	$minimum_date = date("d-m-Y",max(strtotime($minimum_date1),strtotime($minimum_date2)));
}
/*----------------------------*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>CashBook</title>
<link rel="stylesheet" type="text/css" href="pro_drop_1/pro_drop_1.css" />
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" language="javascript">
function show_row(val){
	var prev_val = 0
	for(var i=val-1; i>0; i--){
		if(document.getElementById('hideT'+i).value=="P"){ prev_val = i; break;}
	}
	if(document.getElementById('exp_head_'+prev_val).value==0 && (document.getElementById('Amt'+prev_val).value==0 || document.getElementById('Amt'+prev_val).value=="") && document.getElementById('hideT'+prev_val).value=="P"){
		alert("Selection of Debit A/c and Amount is mandatory !!");
	} else if(document.getElementById('exp_head_'+prev_val).value==0 && (document.getElementById('Amt'+prev_val).value!=0 || document.getElementById('Amt'+prev_val).value!="") && document.getElementById('hideT'+prev_val).value=="P"){
		alert("Selection of Debit A/c is mandatory !!");
	} else if(document.getElementById('exp_head_'+prev_val).value!=0 && (document.getElementById('Amt'+prev_val).value==0 || document.getElementById('Amt'+prev_val).value=="") && document.getElementById('hideT'+prev_val).value=="P"){
		alert("Amount of selected Debit A/c is mandatory !!");
	} else {
		document.getElementById('tdColB['+val+']').style.display="";
		document.getElementById('tdColC['+val+']').style.display="";
		document.getElementById('tdColD['+val+']').style.display="";
		document.getElementById('tdColE['+val+']').style.display="";
		document.getElementById('tdColF['+val+']').style.display="";
		document.getElementById('tdColA['+val+']').innerHTML='<a href="#" onclick="hide_row('+val+')"><img src="images/shrink.gif" style="display:inline;cursor:hand;" border="0"/></a>';
		document.getElementById('hideT'+val).value="P";
		document.getElementById('trRow['+(val+1)+']').style.display="";
		document.getElementById('tdColA['+(val+1)+']').style.display="";
		document.getElementById('tdColB['+(val+1)+']').style.display="none";
		document.getElementById('tdColC['+(val+1)+']').style.display="none";
		document.getElementById('tdColD['+(val+1)+']').style.display="none";
		document.getElementById('tdColE['+(val+1)+']').style.display="none";
		document.getElementById('tdColF['+(val+1)+']').style.display="none";
		var ctr = document.getElementById('exp_head_0').length;
		var strg = '<select name="exp_head_'+val+'" id="exp_head_'+val+'" style="width:230px; height:20px;">';
		for(var i=0; i<ctr; i++){
			strg = strg + '<option value="'+document.getElementById("exp_head_0").options[i].value+'">'+document.getElementById("exp_head_0").options[i].text+'</option>';
		}
		strg = strg+'</select>';
		document.getElementById('tdColC['+val+']').innerHTML=strg;
	}
}

function hide_row(val){
	document.getElementById('trRow['+val+']').style.display="none";
	document.getElementById('exp_head_'+val).value="0";
	document.getElementById('Amt'+val).value="";
	document.getElementById('hideT'+val).value="D";
	document.getElementById('tdColC['+val+']').innerHTML='<select name="exp_head_'+val+'" id="exp_head_'+val+'" style="width:230px; height:20px;"><option value="0">--Select--</option></select>';
	show_total_N_balance();
}

function check_value(value1)
{
	var Amount = parseFloat(value1);
	var Numfilter=/^[0-9.]+$/;
	var test_num = Numfilter.test(Amount);
	if(!test_num){
		alert("Please Enter Only numeric data in the Amount !");
		return false;
	} else {
		show_total_N_balance();
		return true;
	}
}

function show_total_N_balance()
{
	var Total = 0;
	for(var i=0; i<=99; i++){
		if(document.getElementById('hideT'+i).value=="P")
			Total = Total + parseFloat(document.getElementById('Amt'+i).value);
	}
	document.getElementById("TotalAmt").value = Total;
	var availBalance = parseFloat(document.getElementById("currentBalance").value) - Total;
	if(availBalance<0)
		document.getElementById("availBalance").value = (-1*availBalance)+' DR';
	else
		document.getElementById("availBalance").value = availBalance+' CR';
}

function ValidateForm()
{
	if(document.getElementById("locationId").value==0){
		alert("Location not selected. Please select and submit again !");
		return false;
	}
	if(document.getElementById("cihName").value==0){
		alert("Debit Account not selected. Please select and submit again !");
		return false;
	}
	if(document.getElementById("DeptId").value==0){
		alert("Department not selected. Please select and submit again !");
		return false;
	}
	
	if(document.getElementById("Paydate").value!=""){
		if(!checkdate(document.receipt.Paydate)){
			return false;
		} else {
			var no_of_days1 = getDaysbetween2Dates(document.receipt.Paydate,document.receipt.endYear);
			if(no_of_days1 < 0){
				alert("Voucher date wrongly selected. Please correct and submit again !");
				return false;
			} else {
				var no_of_days2 = getDaysbetween2Dates(document.receipt.startYear,document.receipt.Paydate);
				if(no_of_days2 < 0){
					alert("Voucher date wrongly selected. Please correct and submit again !");
					return false;
				} else {
					var no_of_days3 = getDaysbetween2Dates(document.receipt.minDate,document.receipt.Paydate);
					if(no_of_days3 < 0){
						alert("Voucher date wrongly selected. Please correct and submit again.\n"+
						"Last Entry date was "+document.getElementById("minDate").value+", so lower date is not acceptable !");
						return false;
					} else {
						var no_of_days4 = getDaysbetween2Dates(document.payment.Paydate,document.payment.maxDate);
						if(no_of_days4 < 0){
							alert("Voucher date wrongly selected. Please correct and submit again.\n"+
							"Current date is "+document.getElementById("maxDate").value+", so higher date is not acceptable !");
							return false;
						}
					}
				}
			}
		}
	} else if(document.getElementById("Paydate").value==""){
		alert("Please input/select Voucher date !");
		return false;
	}
	for(var i=0; i<99; i++){
		if(document.getElementById('hideT'+i).value=="P"){
			if(document.getElementById('exp_head_'+i).value==0){
				alert("Credit Account not selected. Please select and submit again !");
				return false;
			}
			if(document.getElementById('Amt'+i).value=="" || document.getElementById('Amt'+i).value==0){
				alert("Please input Credit Amount!");
				return false;
			}
		}
	}
	if(parseFloat(document.getElementById("TotalAmt").value)==0){
		alert("Internal Error! Voucher getting Totaling mistake.\n Please submit again this voucher.");
		show_total_N_balance();
		return false;
	}
	document.getElementById("submit").style.display='none';
}

function get_cash_balance(value1)
{
	var url = 'get_cash_balance.php';
	var value2 = document.getElementById('Paydate').value;
	var pars = 'id='+value1+"&dt="+value2;
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'post', 
		parameters: pars, 
		onComplete: show_cash_balance
	});
}
function show_cash_balance(originalRequest)
{	
	var res=originalRequest.responseText.split('~~',3);
	document.getElementById('Balance').value=res[0];
	document.getElementById('currentBalance').value=res[1];
	document.getElementById('minDate').value=res[2];
	document.getElementById('availBalance').value=res[0];
	if(res[1]>=0){
		document.getElementById('tdBal').innerHTML='<input name="Balance" id="Balance" readonly="true" value="'+res[0]+'" class="styleInput1" style="background-color:#C5F8B8; color:#FF0000;">';
		document.getElementById('avlBalSpan').innerHTML='<input name="availBalance" id="availBalance" readonly="true" value="'+res[0]+'" class="styleInput1" style="background-color:#C5F8B8; color:#FF0000;">';
	} else {
		document.getElementById('tdBal').innerHTML='<input name="Balance" id="Balance" readonly="true" value="'+res[0]+'" class="styleInput1" style="background-color:#C5F8B8; color:#000000;">';
		document.getElementById('avlBalSpan').innerHTML='<input name="availBalance" id="availBalance" readonly="true" value="'+res[0]+'" class="styleInput1" style="background-color:#C5F8B8; color:#000000;">';
	}
}

function get_cash_in_hand(value1,value2)
{
	var url = 'get_cash_in_hand.php';
	var pars = 'id='+value1+'&vt='+value2;
	var myAjax = new Ajax.Request(
	url, 
	{
		method: 'post', 
		parameters: pars, 
		onComplete: show_cash_in_hand
	});
}
function show_cash_in_hand(originalRequest)
{
	document.getElementById('tdCIH').innerHTML=originalRequest.responseText;
	get_cash_balance(document.getElementById('cihName').value);
}
</script>
<style type="text/css">
.styleInput {
width:225px;
height:13px;}
.styleInput1 {
width:100px;
height:13px;}
</style>
</head>

<body>
<form name="receipt" id="receipt" method="POST" action="receiptQ.php" onSubmit="return ValidateForm()">		
<table style="width:1000px;" align="center" border="0">
<tr>
	<td>
		<table style="width:1000px; height:380px;" bgcolor="#8ADEF7" align="center">
		<tr>
			<td valign="top">
				<table style="margin-top:0px;" align="center" border="0">
				<tr height="50px"><td valign="top"><?php include("menu.php"); ?></td></tr>
				<tr height="20px"><td align="center"><b>Receipt Voucher</b></td></tr>
				<?php if($_SESSION['cashbook_utype']=="A" || $_SESSION['cashbook_utype']=="S"){?>
				<tr height="20px"><td align="center"><b style="font-size:14px;">Location&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</b>
				<select name="locationId" id="locationId" style="width:194px;height:20px;" onchange="get_cash_in_hand(this.value,'R')">
				<option value="0">--Select--</option>
				<?php $sql=mysql_db_query(DATABASE1,"SELECT * FROM location ORDER BY location_name");
				while($res=mysql_fetch_array($sql)){
					echo '<option value="'.$res['location_id'].'">'.$res['location_name'].'</option>';
				}?>
				</select></td></tr>
				<?php } elseif($_SESSION['cashbook_utype']=="U"){?>
				<tr height="20px"><td align="center"><b style="font-size:14px;">Location&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<font color="#A2377A"><input name="locationName" id="locationName" value="<?php echo $_SESSION['cashbook_lname']; ?>" readonly="true" style="background-color:#E7F0F8; color:#0000FF"></font></b><input type="hidden" name="locationId" id="locationId" value="<?php echo $_SESSION['cashbook_locid']; ?>"></td></tr>
				<?php } ?>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table background="images/backBody.gif" border="5" style="border-color:#2D7CBD;" align="center" width="780px">
				<tr>
					<td>
						<table border="0" align="center" width="780px" style="margin-top:6px; margin-bottom:6px;margin-left:2px;margin-right:2px;">
						<tr>
							<td width="770px">
								<table align="center" border="0" width="770px" cellpadding="2" cellspacing="4" style="margin-top:0px;">
								<tr height="10px" width="770px">
									<td width="12px"></td>
									<td width="90px"><b style="font-size:14px; color:#FFFFFF;">Voucher No.&nbsp;:&nbsp;</b></td>
									<td width="230px"><input name="VaoucherNo" readonly="true" class="styleInput" style="background-color:#C5F8B8; "></td>
									<td width="150px">&nbsp;<input type="hidden" name="startYear" id="startYear" value="<?php echo date("d-m-Y",strtotime($_SESSION['cashbook_syr']));?>"/><input type="hidden" name="endYear" id="endYear" value="<?php echo date("d-m-Y",strtotime($_SESSION['cashbook_eyr']));?>"/><input type="hidden" name="minDate" id="minDate" value="<?php echo $minimum_date; ?>"/><input type="hidden" name="maxDate" id="maxDate" value="<?php echo date("d-m-Y"); ?>"/></td>
									<td width="50px"><b style="font-size:14px;color:#FFFFFF;">Date&nbsp;:&nbsp;</b></td>
									<td width="120px"><input name="Paydate" id="Paydate" maxlength="10" class="styleInput1" value="<?php echo date("d-m-Y"); ?>" onchange="get_cash_balance(document.getElementById('cihName').value)"/></td>
								</tr>
								<tr height="10px" width="770px">
									<td width="12px"></td>
									<td width="90px"><b style="font-size:14px;color:#FFFFFF;">Dr. A/C&nbsp;:&nbsp;</b></td>
									<?php if($_SESSION['cashbook_utype']=="A" || $_SESSION['cashbook_utype']=="S"){?>
									<td width="230px" id="tdCIH"><input name="cih" id="cih" size="34" readonly="true" value="Cash in hand" style="background-color:#C5F8B8;"><input type="hidden" name="cihName" id="cihName" value="0"/></td>
									<?php } elseif($_SESSION['cashbook_utype']=="U"){?>
									<td width="230px"><input name="cih" id="cih" size="34" readonly="true" value="<?php echo $_SESSION['cashbook_cihname'];?>" style="background-color:#C5F8B8;"><input type="hidden" name="cihName" id="cihName" value="<?php echo $_SESSION['cashbook_cihid'];?>"/></td>
									<?php } ?>
									<td width="150px">&nbsp;<input type="hidden" name="currentBalance" id="currentBalance" value="<?php echo $current_balance; ?>"></td>
									<td width="50px"><b style="font-size:14px;color:#FFFFFF;">Balance&nbsp;:&nbsp;</b></td>
									<td width="120px" id="tdBal"><?php if($current_balance>=0){
									echo '<input name="Balance" id="Balance" readonly="true" value="'.$balance.'" class="styleInput1" style="background-color:#C5F8B8; color:#FF0000;">';
									} else {
									echo '<input name="Balance" id="Balance" readonly="true" value="'.$balance.'" class="styleInput1" style="background-color:#C5F8B8; color:#000000;">';
									} ?>
									</td>
								</tr>
								<?php $i=0;
								echo '<tr id="trRow['.$i.']" height="10px" width="770px">
									<td width="12px" id="tdColA['.$i.']">&nbsp;</td>
									<td width="90px" id="tdColB['.$i.']"><b style="font-size:14px;color:#FFFFFF;">Cr. A/C&nbsp;:&nbsp;</b></td>
									<td width="230px" id="tdColC['.$i.']">
									<select name="exp_head_'.$i.'" id="exp_head_'.$i.'" style="width:230px; height:20px;">
									<option value="0">--Select--</option>';
									$sql=mysql_db_query(DATABASE2,"SELECT * FROM ledger ORDER BY ledger_name");
									while($res=mysql_fetch_array($sql)){
										echo '<option value="'.$res['ledger_id'].'">'.$res['ledger_name'].'</option>';
									}
								echo '</select></td>
									<td width="150px" id="tdColD['.$i.']">&nbsp;<input type="hidden" name="hideT'.$i.'" id="hideT'.$i.'" value="P"/></td>
									<td width="50px" id="tdColE['.$i.']"><b style="font-size:14px;color:#FFFFFF;">Amount&nbsp;:&nbsp;</b></td>
									<td width="120px" id="tdColF['.$i.']"><input name="Amt'.$i.'" id="Amt'.$i.'" value="0" class="styleInput1" onkeyup="return check_value(this.value);"></td>
								</tr>';
								for($i=1; $i<=99; $i++){
									if($i==1){
										echo '<tr id="trRow['.$i.']" height="10px" width="770px">';
										echo '<td width="12px" id="tdColA['.$i.']" width="8px"><a onclick="show_row(1)"><img src="images/expand.gif" style="display:inline; cursor:hand;" border="0" /></a></td>';
									} else {
										echo '<tr id="trRow['.$i.']" height="10px" width="770px" style="display:none;">';
										echo '<td width="12px" id="tdColA['.$i.']" style="display:none;"><a onclick="show_row('.$i.')"><img src="images/expand.gif" style="display:inline; cursor:hand;" border="0" /></a></td>';
									}
									
								echo '<td width="90px" id="tdColB['.$i.']" style="display:none;"><b style="font-size:14px;color:#FFFFFF;">Cr. A/C&nbsp;:&nbsp;</b></td>
									<td width="230px" id="tdColC['.$i.']" style="display:none;">
									<select name="exp_head_'.$i.'" id="exp_head_'.$i.'" style="width:230px; height:20px;">
									<option value="0">--Select--</option>';
								echo '</select></td>
								<td width="150px" id="tdColD['.$i.']" style="display:none;">&nbsp;<input type="hidden" name="hideT'.$i.'" id="hideT'.$i.'" value="D"/></td>
								<td width="50px" id="tdColE['.$i.']" style="display:none;"><b style="font-size:14px;color:#FFFFFF;">Amount&nbsp;:&nbsp;</b></td>
								<td width="120px" id="tdColF['.$i.']" style="display:none;"><input name="Amt'.$i.'" id="Amt'.$i.'" value="0" class="styleInput1" onkeyup="return check_value(this.value);"></td>
								</tr>';
								}?>
								
								
								<tr height="10px" width="770px">
								<td width="12px"></td>
								<td width="90px"><b style="font-size:14px;color:#FFFFFF;">Department&nbsp;:&nbsp;</b></td>
							    <td width="230px"><select name="DeptId" id="DeptId" style="width:230px;height:20px;">
				<option value="0">--Select--</option>
		<?php $sqld=mysql_db_query(DATABASE2,"SELECT * FROM department where DeptStatus='A' ORDER BY DepartmentCode ASC");
			  while($resd=mysql_fetch_array($sqld)){
			  echo '<option value="'.$resd['DepartmentId'].'">'.$resd['DepartmentName'].'</option>';
			  } ?>
				</select></td>
								<td colspan="3">&nbsp;</td>
							   </tr>
								
								
								<tr height="10px" width="770px">
									<td width="12px"></td>
									<td width="90px"><b style="font-size:14px;color:#FFFFFF;">Narration&nbsp;:&nbsp;</b></td>
									<td width="230px"><textarea name="remark" cols="26" rows="4"></textarea></td>
									<td width="150px">&nbsp;</td>
									<td width="50px"><b style="font-size:14px;color:#FFFFFF;">Total&nbsp;:&nbsp;</b></td>
									<td width="120px"><input name="TotalAmt" id="TotalAmt" value="0" readonly="true" class="styleInput1" style="background-color:#C5F8B8;"></td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" height="35px"><b style="font-size:14px;color:#FFFFFF;">Available Balance&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><span id="avlBalSpan"><?php if($current_balance>=0){echo '<input name="availBalance" id="availBalance" readonly="true" value="'.$balance.'" class="styleInput1" style="background-color:#C5F8B8; color:#FF0000;">';} else {echo '<input name="availBalance" id="availBalance" readonly="true" value="'.$balance.'" class="styleInput1" style="background-color:#C5F8B8; color:#000000;">';}?></span><?php echo str_repeat("&nbsp;",62); ?><input type="submit" name="submit" id="submit" value="Submit" style="width:80px;">&nbsp;&nbsp;<input type="button" value="Refresh" style="width:80px;" onclick="reset()">&nbsp;&nbsp;<input type="button" value="Exit" style="width:80px;" onclick="window.location='CashbookMenu.php'">
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
</body>
</html>