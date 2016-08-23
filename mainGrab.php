<LINK REL="StyleSheet" HREF="/style.css" TYPE="text/css">

<?php
$outPut = "";
$website = "http://www.ironpaper.com";
$GA_login_success_Tag==0;
$GWT_login_success_Tag==0;
$site_arr = array();
$login_outPut = "";
$actionPage = "";

if(isset($_POST['loginSubmit'])){
	// Set up your Google Webmaster/Analytics credentials
	$email = $_POST['email'];
	$passwd = $_POST['passwd'];
	$apiType = $_POST['apiType'];

	if($apiType=="Analytics"){
		include_once 'ga/GAnalytics.php';
		require "ga/gapi.class.php";
		
		$profileID = "68645509";
		$gaUrl = "";

		// Keep your connection data into a config array
		$config = array('email'      => $email,
						'password'   => $passwd,
						'requestUrl' => $gaUrl,
		);

		// Create a new GAnalytics object
		$ga = new GAnalytics($config);

		try {
			// Call the Google Analytics API check login validation
			if(!$ga->login_AZ()) 
				$login_outPut = 
				"<br><font color=red style='font-weight:bold; font-size:14px'>Analytics login failed, try again.</font>
				<br><font style='font-weight:; font-size:14px'>[Username: $email]</font><br>";
			else{ 
				$GA_login_success_Tag = 1;
				$actionPage = "ga/ga_demo.php";
			}
		} catch (Exception $e) {
			// Log error here
			$login_outPut = "GAnalytics Connection error ({$e->getCode()}): {$e->getMessage()}";
		}
	}else if($apiType=="Webmaster"){
		include 'gwt/gwtdata.php';

		$gdata = new GWTdata();
		if($gdata->LogIn($email, $passwd) === true)
		{
			$site_arr = $gdata->GetSites();
			$GWT_login_success_Tag = 1;
			$actionPage = "gwt/gwt_demo.php";
			if(count($site_arr)>0){
				$login_outPut =
				"
				<div id='loadSiteDiv' class='loadSiteDiv' style='margin:5 0 5 0'>
				<table width='550px' style='margin-top:5px'>
				<tr>
				<td align='left' width='175px'>
				Select Your Site
				</td>
				<td align='left'>
				<select name='website'>"
				;
				
				foreach($site_arr as &$val){
					$login_outPut .= "<option value='".$val."'>".$val."</option>";
				}
				
				$login_outPut .= "
				</select>
				</span>
				</td>
				</tr>
				</table>
				</div>
				";
			}else
				$login_outPut = "You have no site for tracking.";
		}else{
			$login_outPut = "
			<br><font color=red style='font-weight:bold; font-size:14px'>Webmaster login failed, try again.</font>
			<br><font style='font-weight:; font-size:14px'>[Username: $email]</font><br>";
		}
	}
}

if(strlen($outPut)>0){
	$outPut = "<div style='padding:5 10 5 10; width:550px; padding:10px; margin-bottom:10; color:red; font-weight: bold'>".$outPut."</div>";
}

if(!isset($_POST['mainSubmit'])||strlen($outPut)>0){
?>

<html>
<head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
var loginSubmit={
ajax:function(st){
	//document.bgColor = '#666666';
	//this.hide('bodyBg');
	//this.show('wholeBg');
	if(document.main_form_1.email.value.length == 0) { 
		alert("Please enter email correctly."); 
		document.main_form_1.email.focus(); 
		return false; 
	}
    
	if(document.main_form_1.passwd.value.length == 0) { 
		alert("Please enter password correctly."); 
		document.main_form_1.passwd.focus(); 
		return false; 
	}
    
	var api=document.getElementById('apiType'); 
	var apiVal = api.options[api.selectedIndex].value;
	if(apiVal!="Webmaster"&&apiVal!="Analytics") {
		alert("Please select API type"); 
		return false;
	};

	this.hide('loginBtn');
	this.show('grayLoginBtn');
},

show:function(el){
	 this.getID(el).style.display='';
},

hide:function(el){
	 this.getID(el).style.display='none';
},

getID:function(el){
	 return document.getElementById(el);
}
}
</script>

<script type="text/javascript">
var finalSubmit={
ajax:function(st){
	//document.bgColor = '#666666';
	//this.hide('bodyBg');
	//this.show('wholeBg');
	if(document.main_form_2.fromDate.value.length == 0) { 
		alert("Please enter report start date."); 
		document.main_form_2.fromDate.focus(); 
		return false; 
	}
    
	if(document.main_form_2.endDate.value.length == 0) { 
		alert("Please enter report end date."); 
		document.main_form_2.endDate.focus(); 
		return false; 
	}
    
	if(document.main_form_2.grabNum.value.length == 0) { 
		alert("Please enter number of results."); 
		document.main_form_2.grabNum.focus(); 
		return false; 
	}	

	if(apiVal=="Analytics") {
		//if(isValidDate(document.main_form_1.fromDate.value)||isValidDate(document.main_form_1.endDate.value)){
		//	alert("Please input valid date"+document.main_form_1.fromDate.value+"++"+document.main_form_1.endDate.value); 
		//	document.main_form_1.fromDate.focus();
		//	return false;
		//}	
	}
	this.show('load');
},

show:function(el){
	 this.getID(el).style.display='';
},

hide:function(el){
	 this.getID(el).style.display='none';
},

getID:function(el){
	 return document.getElementById(el);
}
}
</script>

<script type="text/javascript" language="javascript"> 
<!--
function addText(target,text) {
    var myTarget = document.getElementById(target);
    myTarget.innerText = text;

} 

function controlDiv() {
    var api=document.getElementById('apiType'); 
	var apiVal = api.options[api.selectedIndex].value;
	if(apiVal=="Analytics") {
		document.getElementById('loginOutPut').style.display = "none";
		document.getElementById('loginDiv').style.display = "block";
		document.getElementById('loginSubmitDiv').style.display = "block";
		document.getElementById('apiTypeTag').style.display = "block";
	}else if(apiVal=="Webmaster"){
		document.getElementById('loginOutPut').style.display = "none";
		document.getElementById('loginDiv').style.display = "block";
		document.getElementById('loginSubmitDiv').style.display = "block";
		document.getElementById('apiTypeTag').style.display = "block";
	}else{
		document.getElementById('loginDiv').style.display = "none";
		document.getElementById('loginSubmitDiv').style.display = "none";
		document.getElementById('apiTypeTag').style.display = "none";
	}
} 
//-->
</script>

<script type="text/javascript">
$(function() {
	$(".getListBtn1").click(function() {
		var apiType = document.main_form_1.apiType.value;
		var email = document.main_form_1.email.value;
		var passwd = document.main_form_1.passwd.value;
		var dataString = 'email='+email+'&&passwd='+passwd; 
		
		$("#getListBtn").hide();
		$("#grayGetListBtn").show();
		
		$.ajax({
			type: "POST",
			url: "gwt/gwtProcess.php",
			data: dataString,
			cache: false,
			success: function(html){
				$("#grayGetListBtn").hide();
				$("#apiTypeDiv").hide();
				$("#loginDiv").hide();
				$("#showAPI").html(apiType+"&nbsp;&nbsp;&nbsp;&nbsp;<a href='mainGrab.php' style='color:#01AEF0'>Home Page</a>");
				$("#showAPIDiv").show();
				$("#showEmail").text(email);
				$("#showEmailDiv").show();
				$("#submitDiv").show();
				$("#loginOutPut").after(html);
			}
 		});
 		return false;
 	});
 });
</script>

<script type="text/javascript">
$(function() {
	$(".GALoginBtn1").click(function() {
		var apiType = document.main_form_1.apiType.value;
		var email = document.main_form_1.email.value;
		var passwd = document.main_form_1.passwd.value;
		var dataString = 'email='+email+'&&passwd='+passwd; 
		
		$("#GALoginBtn").hide();
		$("#grayGALoginBtn").show();
		
		$.ajax({
			type: "POST",
			url: "ga/gaProcess.php",
			data: dataString,
			cache: false,
			success: function(html){
				$("#grayGALoginBtn").hide();
				$("#apiTypeDiv").hide();
				$("#loginDiv").hide();
				$("#showAPI").html(apiType+"&nbsp;&nbsp;&nbsp;&nbsp;<a href='mainGrab.php' style='color:#01AEF0'>Home Page</a>");
				$("#showAPIDiv").show();
				$("#showEmail").text(email);
				$("#showEmailDiv").show();
				$("#datePageDiv").show();
				$("#submitDiv").show();
				$("#GAResult").after(html);
			}
 		});
 		return false;
 	});
 });
</script>

</head>
<body bgcolor="">

<div width="100%" align="center">

<div style="background:#EEEEEE; margin-top:5%; margin-bottom:1%; border-top:0px solid #CCCCCC">
<div align="left" style="width:550px">
<h1>Google Webmaster/Analytics Utility</h1>
</div>
</div>

<div id="load" style="display:none;">
<img src="img/loading.gif" width="40">&nbsp;&nbsp;<span id="apiName"></span> is working...
</div>

<div width="1024px" style="margin-top:65px">

<?php echo($outPut) ?>
<form method="post" name="main_form_1" onsubmit="return loginSubmit.ajax()">

<?php if($GA_login_success_Tag==0&&$GWT_login_success_Tag==0){ ?>
<div id="apiTypeDiv" class="apiTypeDiv">
<table width="550px">
<tr>
<td align="left" width="175px">
<span style="display:none" id="apiTypeTag" class="apiTypeTag">Selected API type</span>
</td>
<td align="left">
<select name="apiType" id="apiType" class="apiType" onChange="controlDiv()">
<!--
onChange="controlDiv();if(this.options[this.selectedIndex].value=='Webmaster')this.form.action='gwt/gwtProcess.php';
else if(this.options[this.selectedIndex].value=='Analytics')this.form.action='gwt/gaProcess.php'">
-->
<option value="blank">Select API type</option>
<option value="Webmaster">Webmaster</option>
<option value="Analytics">Analytics</option>
</option>
</select>
</td>
</tr>
</table>
</div>
<?php } ?>

<div id="loginDiv" class="loginDiv" style="display:none">
<table width="550px">
<tr>
<td align="left" width="175px">
Google Email
</td>
<td align="left">
<input type="text" name="email" id="email" size="30">
</td>
</tr>
</table>
<table width="550px">
<tr>
<td align="left" width="175px">
Password
</td>
<td align="left">
<input type="password" name="passwd" id="passwd" size="30">
</td>
</tr>
</table>
</div>

<div id="loginSubmitDiv" class="loginSubmitDiv" style="display:none">
<table width="550px">
<tr>
<td align="left" width="175px">
</td>
<td align="left" style="padding-top:20px">
<input type="submit" name="loginSubmit" value="Login" class="loginBtn" id="loginBtn" align="center">
<div class="grayLoginBtn" id="grayLoginBtn" align="center">Login... <img src='img/20.gif'></div>
</td>
</tr>
</table>
</div>
</form>

<form action="<?php echo($actionPage) ?>" method="post" name="main_form_2" onsubmit="return finalSubmit.ajax()">
<?php if($GA_login_success_Tag==1||$GWT_login_success_Tag==1){ ?>
<div align="right" style="width:400px; margin-bottom:30px">
<font style="font-size:12px; color:#999999">Login Successful</font>&nbsp;&nbsp;
<a href="/mainGrab.php" style="font-size:12px; color:#01AEF0">Home Menu</a> 
</div>
<div id="showAPIDiv" class="showAPIDiv">
<table width="550px">
<tr>
<td align="left" width="175px">
Selected API
</td>
<td align="left">
<span id="showAPI" class="showAPI"><u><?php echo($apiType) ?></u></span>
</td>
</tr>
</table>
</div>

<div id="showEmailDiv" class="showEmailDiv">
<table width="550px">
<tr>
<td align="left" width="175px">
Email Account
</td>
<td align="left">
<span id="showEmail" class="showEmail"><?php echo($email) ?></span>
<input type="hidden" name="email" value="<?php echo($email) ?>">
<input type="hidden" name="passwd" value="<?php echo($passwd) ?>">
</td>
</tr>
</table>
</div>
<?php } ?>

<div id="loginOutPut" class="loginOutPut">
<?php echo($login_outPut) ?>
</div>

<?php if($GA_login_success_Tag==1){ ?>
<div id="datePageDiv" class="datePageDiv" name="datePageDiv" style="margin-top:-5px" >
<table width="550px">
<tr>
<td align="left" width="175px">
Report start date
</td>
<td align="left" style="padding-top:10px">
<input type="text" name="fromDate" id="fromDate" class="fromDate" size="25">
</td>
</tr>
</table>
<table width="550px" style="margin-top:-10px">
<tr>
<td align="left" width="175px">
End date
</td>
<td align="left" style="padding-top:10px">
<input type="text" name="endDate" id="endDate" class="endDate" size="25" maxlength="10" value=<?php echo(date('Y-m-d', time() - 1 * 24 * 60 *60)); ?>>
</td>
</tr>
</table>
<table width="550px" style="margin-top:-10px">
<tr>
<td align="left" width="175px">
How many Pages
</td>
<td align="left" style="padding-top:10px">
<input type="text" size="25" name="grabNum" id="grabNum" class="grabNum" value="25">&nbsp; <font style="font-size:12px; color:#999999">(Pick from 1 to 500)</font>
</td>
</tr>
</table>
</div>
<?php } ?>

<?php if($GA_login_success_Tag==1||$GWT_login_success_Tag==1){ ?>
<div id="submitDiv" class="submitDiv">
<table width="550px">
<tr>
<td align="left" width="175px">
</td>
<td align="left" style="padding-top:10px">
<input type="submit" name="mainSubmit" value="Submit" class="submitBtn" id="submitBtn" onClick="var obj=document.getElementById('apiType'); var apiVal = obj.options[obj.selectedIndex].value; addText('apiName',apiVal);">
</td>
</tr>
</table>
</div>
<?php } ?>
</form>

<?php if($GWT_login_success_Tag==1){ ?>
<div id="hintGWTBox" class="hintGWTBox" align="left">
<strong>Note:</strong><br>
Google Webmaster will retrieve following ranking data:<p>
Keywords searched by user<br>
Top pages visited<br>
Internal links<br>
External links<br>
Top queries
</div>
<?php } ?>

<?php if($GA_login_success_Tag==1){ ?>
<div id="hintGABox" class="hintGABox" align="left">
<strong>Note:</strong><br>
Google Analytics will retrieve following data per each page<p>
Visitor's Type<br>Source<br>Operating System<br>Browser Type<br>Browser Version<br>Keyword Used<br>Visits<br>Page Views<br>Page Visits<br>Exits<br>Visitors<br>NewVisits<br>Time On Site<br>Average Time On Site
</div>
<?php } ?>

</div>


<div style="width:550px; padding:10px; font-size:14px; margin-top:75px; color:#999999" align="center">
&copy; 2013 Implemented by Ironpaper 
&nbsp;&nbsp;
<a href="http://www.ironpaper.com"><img src="/img/ironpaper-logo.png" width="15"></a>&nbsp;&nbsp;
<a href="http://www.google.com"><img src="/img/google-logo.png" width="13"></a>
</div>

</div>


<script language="JavaScript" type="text/JavaScript">
    function validate_main_form_1() {
        if(document.main_form_1.email.value.length == 0) { alert("Please enter email correctly."); document.main_form_1.email.focus(); return false; }
        if(document.main_form_1.passwd.value.length == 0) { alert("Please enter password correctly."); document.main_form_1.passwd.focus(); return false; }
        var obj=document.getElementById('apiType'); 
		var apiVal = obj.options[obj.selectedIndex].value;
		if(apiVal == "blank") { alert("Please select an API type."); document.main_form_1.apiType.focus(); return false; }
        document.main_form_1.submit();
    }
        
    function validEmail(e) {
        var filter = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
        return String(e).search (filter) != -1;
    }

    function isLetter(s) 
    { 
        return s.match("^[a-zA-Z\(\)]+$");     
    } 

	function isValidDate(date){
		var matches = /^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(date);
		if (matches == null) return false;
		var d = matches[2];
		var m = matches[1] - 1;
		var y = matches[3];
		var composedDate = new Date(y, m, d);
		return composedDate.getDate() == d &&
            composedDate.getMonth() == m &&
            composedDate.getFullYear() == y;
	}
</script>
</body>
</html>

<?php } ?>