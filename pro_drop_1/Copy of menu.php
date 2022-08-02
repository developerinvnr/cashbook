<?php if($type=='A') { ?>											
											
<ul id="nav">
	<li class="top"><a href="admin.php" class="top_link"><span>&nbsp;&nbsp;Home&nbsp;&nbsp;</span></a></li>
	<li class="top"><a href="admin_changepwd.php" class="top_link"><span>&nbsp;&nbsp;Change PassWord&nbsp;&nbsp;</span></a></li>
	<li class="top"><a href="#" id="products" class="top_link"><span class="down">&nbsp;&nbsp;ADD&nbsp;&nbsp;</span></a>
		<ul class="sub">
			<li><a href="addCourse.php" >Course</a></li>
			<li ><a href="addSubjectU.php">Subject</a></li>
			<li><a href="addChapter.php">Chapter</a></li>
			<li><a href="addQuestion.php">Question</a></li>
			<li><a href="addQuestion_gk.php">Question_GK</a></li>
			<li><a href="WebLink.php">WebLink</a></li>
		</ul>
	</li>
	<li class="top"><a href="#" id="products" class="top_link"><span class="down">&nbsp;&nbsp;Update&nbsp;&nbsp;</span></a>
		<ul class="sub">
			<li><a href="UpdateQuestion.php" style="text-decoration:none" onclick="window.open(this.href, 'popupwindow', 'width=1200,height=700,scrollbars=yes,resizable=yes,addressbar=no,toolbar=no,directories=no,menubar=no'); return false;">Question</a></li>
			<li><a href="UpdateQuestion_gk.php" style="text-decoration:none" onclick="window.open(this.href, 'popupwindow', 'width=1200,height=700,scrollbars=yes,resizable=yes,addressbar=no,toolbar=no,directories=no,menubar=no'); return false;">Question_GK</a></li>
			<li ><a href="User_payment.php">User_payment</a></li>
			<li ><a href="Update_AllIndiaExam.php">All India Exam</a></li>
			<li><a href="AaaNewsUpdate.php">News</a></li>
		</ul>
	</li>
	<li class="top"><a href="#" id="services" class="top_link"><span class="down">&nbsp;&nbsp;Setting&nbsp;&nbsp;</span></a>
		<ul class="sub">
			<li><a href="setPayment.php">Sub. payment</a></li>
			<li><a href="settingExam.php">Test paper</a></li>
			<li><a href="settingView.php">Provide</a></li>
			<li><a href="All_India.php">All India Exam</a></li>
			<li><a href="GK_exam.php">G. K. Exam</a></li>
		</ul>
	</li>
	<li class="top"><a href="OurSubjects.php" id="contacts" class="top_link"><span>&nbsp;&nbsp;Upload Books&nbsp;&nbsp;</span></a></li>
	<li class="top"><a href="#nogo53" id="shop" class="top_link"><span class="down">&nbsp;&nbsp;ADD/Update Other&nbsp;&nbsp;</span></a>
		<ul class="sub">
			<li><a href="seminar.php">Seminar</a></li>
			<li><a href="update_seminar.php">update Seminar</a></li>
			<li><a href="vacancy.php">Vacancy</a></li>
		</ul>
	</li>
	<li class="top"><a href="logout.php" id="privacy" class="top_link"><span>&nbsp;&nbsp;LogOut&nbsp;&nbsp;</span></a></li>
</ul>

 <?php } if($type=='U'){ ?> 
 
 <ul id="nav">
	<li class="top"><a href="admin.php" class="top_link"><span>&nbsp;&nbsp;Home&nbsp;&nbsp;</span></a></li>
	<li class="top"><a href="#" id="products" class="top_link"><span class="down">&nbsp;&nbsp;ADD&nbsp;&nbsp;</span></a>
		<ul class="sub">
			<li><a href="addQuestionUser.php">Question</a></li>
			<li><a href="addQuestionUser_gk.php">Question_GK</a></li>
		</ul>
	</li>
	<li class="top"><a href="logout.php" id="privacy" class="top_link"><span>&nbsp;&nbsp;LogOut&nbsp;&nbsp;</span></a></li>
</ul>

 <?php }?>
