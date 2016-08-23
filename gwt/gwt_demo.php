<!--
This will download and save 8 CSV files to your hard disk:

./TOP_PAGES-www.domain.com-YYYYmmdd-H:i:s.csv
./TOP_QUERIES-www.domain.com-YYYYmmdd-H:i:s.csv
./CRAWL_ERRORS-www.domain.com-YYYYmmdd-H:i:s.csv
./CONTENT_ERRORS-www.domain.com-YYYYmmdd-H:i:s.csv
./CONTENT_KEYWORDS-www.domain.com-YYYYmmdd-H:i:s.csv
./INTERNAL_LINKS-www.domain.com-YYYYmmdd-H:i:s.csv
./EXTERNAL_LINKS-www.domain.com-YYYYmmdd-H:i:s.csv
./SOCIAL_ACTIVITY-www.domain.com-YYYYmmdd-H:i:s.csv
-->

<script type="text/javascript" language="javascript"> 
function showHideDiv(clicked_id) {
	//alert(clicked_id.value);
	var click_id = 'Div_'+clicked_id;
    var val = document.getElementById(click_id).style.display;
	if(val=="none") {
		document.getElementById(click_id).style.display = "block";
	}else{
		document.getElementById(click_id).style.display = "none";
	}
}
</script>

<?php
    include 'gwtdata.php';
	require "fpdf17/fpdf.php";
    try {
       $email = $_POST['email'];
		$password = $_POST['passwd'];
		$website = $_POST['website'];
	   // $website = $_POST['website'];
	   // $email = "superbarmuya@gmail.com";
       // $password = "harvey9i";
        
        $report_folder = 'GWT_report';
        if (!is_dir($report_folder)) {
 		   mkdir($report_folder);
		}

        # If hardcoded, don't forget trailing slash!
        # $website = "http://www.ironpaper.com/";

        $gdata = new GWTdata();
        if($gdata->LogIn($email, $password) === true)
        {
			echo("<div style='width:100%; padding-top:0px' align='center'>");
			echo("<div style='width:100%; padding-bottom:50px' align='left'>");
		    echo("<h2>Click to download your Webmaster reports</h2>");
		    $gdata->DownloadCSV($website);
        }else{
			
		}
        
		echo("<div style='font-size:14px; padding:5; width:75px; background:#EEEEEE; margin-top:30px' align='center'><a href='/mainGrab.php'>Go back</a></div>");
		echo("</div>");
		echo("</div>");

    } catch (Exception $e) {
        die($e->getMessage());
    }
?>