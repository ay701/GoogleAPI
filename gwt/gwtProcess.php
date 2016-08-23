<?php
    include 'gwtdata.php';
    
    $email = $_POST['email'];
	$passwd = $_POST['passwd'];
	$site_arr = array();
	$gdata = new GWTdata();
    if($gdata->LogIn($email, $passwd) === true)
    {
		$site_arr = $gdata->GetSites();

		echo("
		<div id='loadSiteDiv' class='loadSiteDiv' style='margin:5 0 5 0'>
		<table width='550px' style='margin-top:5px'>
		<tr>
		<td align='left' width='175px'>
		Select Your Site
		</td>
		<td align='left'>
		<select name='website'>");
		
		foreach($site_arr as &$val){
			echo("<option value='".$val."'>".$val."</option>");
		}
		
		echo("
		</select>
		</span>
		</td>
		</tr>
		</table>
		</div>
		");
    }else{
		echo("<br><font color=red style='font-weight:bold'>Login failed. Refresh & Try again.</font>");
	}
?>