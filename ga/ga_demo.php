<LINK REL="StyleSheet" HREF="/style.css" TYPE="text/css">
<?php
	// Include the Google Analytics data import class
	include_once 'GAnalytics.php';
	require "gapi.class.php";
	require "../gwt/fpdf17/fpdf.php";
	
	$profileID = "68645509";
	$report_folder = 'GA_report';
	// Set up a period of time to get data for
	//$fromDate = date('Y-m-d', time() - 8 * 24 * 60 *60); //one week ahead
	//$endDate  = date('Y-m-d', time() - 1 * 24 * 60 *60); //yesterday
	//$grabNum  = 25;   // default 25 results
	
	$fromDate = $_POST["fromDate"];
	$endDate = $_POST["endDate"];
	$grabNum = $_POST["grabNum"]; 

	// Set up your Google Analytics credentials
	$gaEmail        = 'superbarmuya@gmail.com';
	$gaPassword     = 'harvey9i';

	// Get and store the query data code from Google Analytics Data Feed Query Explorer
	// http://code.google.com/apis/analytics/docs/gdata/gdataExplorer.html
	//
	// You will set here your own query data url
	$gaUrl = "https://www.googleapis.com/analytics/v2.4/data?" .
			  "ids=ga:$profileID&" .
			  "dimensions=ga%3ApagePath,ga%3AvisitorType,ga%3Asource,ga%3AoperatingSystem,ga%3Abrowser,ga%3AbrowserVersion,ga%3Akeyword" .
			  "&metrics=ga%3Avisits,ga%3Apageviews,ga%3Aexits,ga%3Avisitors,ga%3AnewVisits,ga%3AtimeOnSite,ga%3AavgTimeOnSite&" .
			 // "filters=ga%3ApagePath%3D~anunt%5C%3Fid%3D*&" .
			  "sort=-ga%3Avisits&" .
			  "start-date={$fromDate}&" .
			  "end-date={$endDate}&" .
		      "max-results=$grabNum";

	// Keep your connection data into a config array
	$config = array('email'      => $gaEmail,
					'password'   => $gaPassword,
					'requestUrl' => $gaUrl,
	);

	// Create a new GAnalytics object
	$ga = new GAnalytics($config);

	try {

		// Call the Google Analytics API request in here
		$gaResult = $ga->call();

		// Check if report folder exists
		if (!is_dir($report_folder)) {
 		   mkdir($report_folder);
		}
		
		// Write output to XML file
		$myFile = "GA_report_".$profileID."_".date('Y-m-d', time()).".xml";
		$fname = $report_folder."/".$myFile;
		$fname_pdf = substr($fname,0,strlen($fname)-3)."pdf";
		$fh = fopen($fname, (file_exists($report_folder."/".$myFile)) ? 'a' : 'w');
		fwrite($fh, $gaResult."\n");
		fclose($fh);

		// If the call was successful - do your magic in here
		// You have to parse the Atom Feed XML response and gather you stats
		// This can be achieved with a SimpleXML tree traversing
		// or with a preg_match_call() to make your life easier
		// preg_match_all("@<dxp:dimension name='ga:pagePath' value='/anunt\?id=([0-9]{1,})'/>@", $gaResult, $matches);
		// preg_match("@<dxp:dimension name='ga:pagePath' value='/[0-9a-zA-Z-/]{1,}'/>@", $gaResult, $matches);
		$pattern_pagePath = "@<dxp:dimension name=\"ga:pagePath\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_visitorType = "@<dxp:dimension name=\"ga:visitorType\" value=\"([0-9a-zA-Z\s-/]{1,})\"/>@";
		$pattern_source = "@<dxp:dimension name=\"ga:source\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_operatingSystem = "@<dxp:dimension name=\"ga:operatingSystem\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_browser = "@<dxp:dimension name=\"ga:browser\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";		
		$pattern_browserVersion = "@<dxp:dimension name=\"ga:browserVersion\" value=\"([0-9a-zA-Z-./]{1,})\"/>@";
		$pattern_keyword = "@<dxp:dimension name=\"ga:keyword\" value=\"([0-9a-zA-Z\s()-/]{1,})\"/>@";
		$pattern_visits = "@<dxp:metric name=\"ga:visits\" type=\"integer\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_pageviews = "@<dxp:metric name=\"ga:pageviews\" type=\"integer\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_exits = "@<dxp:metric name=\"ga:exits\" type=\"integer\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_visitors = "@<dxp:metric name=\"ga:visitors\" type=\"integer\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_newVisits = "@<dxp:metric name=\"ga:newVisits\" type=\"integer\" value=\"([0-9a-zA-Z-/]{1,})\"/>@";
		$pattern_timeOnSite = "@<dxp:metric name=\"ga:timeOnSite\" type=\"time\" value=\"([0-9a-zA-Z.-/]{1,})\"/>@";
		$pattern_avgTimeOnSite = "@<dxp:metric name=\"ga:avgTimeOnSite\" type=\"time\" value=\"([0-9a-zA-Z.-/]{1,})\"/>@";
		
		preg_match_all($pattern_pagePath, $gaResult, $matches_pagePath);
		preg_match_all($pattern_visitorType, $gaResult, $matches_visitorType);
		preg_match_all($pattern_source, $gaResult, $matches_source);
		preg_match_all($pattern_operatingSystem, $gaResult, $matches_operatingSystem);
		preg_match_all($pattern_browser, $gaResult, $matches_browser);
		preg_match_all($pattern_browserVersion, $gaResult, $matches_browserVersion);
		preg_match_all($pattern_keyword, $gaResult, $matches_keyword);
		preg_match_all($pattern_visits, $gaResult, $matches_visits);
		preg_match_all($pattern_pageviews, $gaResult, $matches_pageviews);
		preg_match_all($pattern_exits, $gaResult, $matches_exits);
		preg_match_all($pattern_visitors, $gaResult, $matches_visitors);
		preg_match_all($pattern_newVisits, $gaResult, $matches_newVisits);
		preg_match_all($pattern_timeOnSite, $gaResult, $matches_timeOnSite);
		preg_match_all($pattern_avgTimeOnSite, $gaResult, $matches_avgTimeOnSite);
//		echo($matches[1]."---count:".count($matches[1]));

		$arr_pagePath = $matches_pagePath[1];
		$arr_visitorType = $matches_visitorType[1];
		$arr_source = $matches_source[1];
		$arr_operatingSystem = $matches_operatingSystem[1];
		$arr_browser = $matches_browser[1];
		$arr_browserVersion = $matches_browserVersion[1];
		$arr_keyword = $matches_keyword[1];
		$arr_visits = $matches_visits[1];
		$arr_pageviews = $matches_pageviews[1];
		$arr_exits = $matches_exits[1];
		$arr_visitors = $matches_visitors[1];
		$arr_newVisits = $matches_newVisits[1];
		$arr_timeOnSite = $matches_timeOnSite[1];
		$arr_avgTimeOnSite = $matches_avgTimeOnSite[1];

		// Generate PDF report
		$pdf = new FPDF( );
		$pdf->AddPage();
		$pdf->SetFont('Times','B',14);
		$pdf->Cell(10,10,$fname_pdf, 0,1,'L');
		$pdf->SetFont('Times','',8);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(10,5,"Profile ID: $profileID", 0,1,'L');	
		$pdf->Cell(10,5,"Account: $gaEmail", 0,1,'L');	
		$pdf->Cell(10,5,"Page Number: $grabNum", 0,1,'L');	
		$pdf->Cell(10,5,"Analytics report for: http://www.ironpaper.com", 0,1,'L');	
		$pdf->Cell(10,5,"Total visits: ".$arr_visits[0], 0,1,'L');	
		$pdf->Cell(10,5,"Page views: ".$arr_pageviews[0], 0,1,'L');	
		$pdf->Cell(10,5,"Exits: ".$matches_exits[0], 0,1,'L');	
		$pdf->Cell(10,5,"Visitors: ".$arr_visitors[0], 0,1,'L');	
		$pdf->Cell(10,5,"New Visits: ".$arr_newVisits[0], 0,1,'L');	
		$pdf->Cell(10,5,"Time on Site: ".$arr_timeOnSite[0], 0,1,'L');	
		$pdf->Cell(10,5,"Average time Onsite: ".$arr_avgTimeOnSite[0], 0,1,'L');							

		echo("<div style='-moz-border-radius: 5px; border-radius: 5px; line-height:125%; width:1024px; margin-bottom:15px; border:1px solid #DDDDDD'>");
		echo("
		<table border='0' cellpadding='1' cellspacing='0'>
		<tr>
		<td valign='top' width='424' style='background: #F5F5F5; font-weight:bold; font-size:14px; padding:10px; color:#333333'>Profile ID: $profileID 
		<br> Account: $gaEmail
		<br> Page Number: $grabNum
		<br>Analytics report for:
		<br><br> <a href='http://www.ironpaper.com'><font style='font-size:28px; color:#01AEF0'>http://www.ironpaper.com</font></a></td>
		<td style='padding:10px; background: #EEEEEE; width:400' valign='top'>");
		echo("<img src='/img/blackArrow.png' width='15'>&nbsp; Total visits: &nbsp;&nbsp;".$arr_visits[0]);
		echo("<br><img src='/img/blackArrow.png' width='15'>&nbsp; Page views: &nbsp;&nbsp;".$arr_pageviews[0]);
		echo("<br><img src='/img/blackArrow.png' width='15'>&nbsp; Exits: &nbsp;&nbsp;".$matches_exits[0]);
		echo("<br><img src='/img/blackArrow.png' width='15'>&nbsp; Visitors: &nbsp;&nbsp;".$arr_visitors[0]);
		echo("<br><img src='/img/blackArrow.png' width='15'>&nbsp; New Visits: &nbsp;&nbsp;".$arr_newVisits[0]);
		echo("<br><img src='/img/blackArrow.png' width='15'>&nbsp; Time on Site: &nbsp;&nbsp;".$arr_timeOnSite[0]);
		echo("<br><img src='/img/blackArrow.png' width='15'>&nbsp; Average time Onsite: &nbsp;&nbsp;".$arr_avgTimeOnSite[0]);
		echo("</td>
		<td style='padding:10; background: #F5F5F5; width:200' valign='top' align='center'>
		<a href='/mainGrab.php'><div style='margin-top:5; -moz-border-radius: 5px; border-radius: 5px; width:150px; height:25px; padding:5 0 5 0; background:#01AEF0; font-weight:bold; border:1px solid #666666; color:#FFFFFF; font-size:18px; cursor:pointer' align='center' onmouseover='this.style.border=\"2px #666666 solid\"' onmouseout='this.style.border=\"1px #666666 solid\"'>
		Back Home</div>
		</a>
		<a href='$fname_pdf' target='_blank'>
		<div style='margin-top:20; -moz-border-radius: 5px; border-radius: 5px; width:150px; height:25px; padding:5 0 5 0; background:#4CC552; font-weight:bold; border:1px solid #666666; color:#FFFFFF; font-size:18px; cursor:pointer' align='center' onmouseover='this.style.border=\"2px #666666 solid\"' onmouseout='this.style.border=\"1px #666666 solid\"'>
		Download &nbsp;<img src='/img/pdf.png' width='20'></div>
		</a>
		<div style='margin-top:10px; color:#999999; font-size:14px; width:150px;' align='center'>(Currently only PDF)</div>
		</td>
		<tr></table></div>");
	
		echo("<table style='border-top:1px solid #DDDDDD' border='0' cellpadding='1' cellspacing='0'>
		<tr style='background:#F5F5F5'>
		<td width='400' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Page Path</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Visitor Type</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Source</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Operating System</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Browser</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Browser Version</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Keyword</td>
		<td width='50' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Visits</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Page views</td>
		<td width='50' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Exits</td>
		<td width='60' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Visitors</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>New Visits</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Time on Site</td>
		<td width='100' style='border-bottom:1px dashed #DDDDDD; font-size:12px; font-weight:bold'>Average time on Site</td>
		</tr>
		");

		$pdf->SetTextColor(0,0,255);
		$pdf->Cell(10,5,"Page Path, Visitor Type, Source, Operating System, Browser, Browser Version, Keyword, Visits, Page Views, Exits, Visitors, New Visits, Time on Site, Average time on Site", 0,1,'L');	
		$pdf->SetTextColor(0,0,0);

		for($i=0;$i<$grabNum;$i++){
			$j = $i + 1;
			echo "<tr>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_pagePath[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_visitorType[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_source[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_operatingSystem[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_browser[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_browserVersion[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_keyword[$i]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_visits[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_pageviews[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_exits[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_visitors[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_newVisits[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_timeOnSite[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-bottom:1px dashed #DDDDDD; font-size:12px'>". $arr_avgTimeOnSite[$j]."&nbsp;&nbsp;&nbsp;</td>";
			echo "</tr>";
			
			$pdf->SetFont('Times','',8);
			$pdf->Cell(10,5, $arr_pagePath[$i].", ".$arr_visitorType[$i].", ".$arr_source[$i].", ".$arr_operatingSystem[$i].", ".$arr_browser[$i].", ".$arr_browserVersion[$i].", ".$arr_keyword[$i].", ".$arr_visits[$j].", ".$arr_pageviews[$j].", ".$arr_exits[$j].", ".$arr_visitors[$j].", ".$arr_newVisits[$j].", ".$arr_timeOnSite[$j].", ".$arr_avgTimeOnSite[$j], 0,1,'L');	
		}
		echo("</table>");

		$pdf->Output(substr($fname,0,strlen($fname)-3)."pdf","F");

		// echo("count matches: ". count($matches));
		// A dummy data rendering here...
		// var_dump($matches, $matches[1], $gaResult);

		
		//$xml = simplexml_load_file($report_folder."/".$myFile);
		//echo($xml."----");
		//header("Location: ".$report_folder."/".$myFile);

		/*
		$results = array();
		$xml = simplexml_load_string($gaResult);
		$namespaces = $xml->getNamespaces(true);
		foreach($xml->entry as $entry)
    	{
      	  $metrics = array();
		  foreach($entry->children('http://schemas.google.com/analytics/2009')->metric as $metric)
		  {
			$metric_value = strval($metric->attributes()->value);
			
			//Check for float, or value with scientific notation
			if(preg_match('/^(\d+\.\d+)|(\d+E\d+)|(\d+.\d+E\d+)$/',$metric_value))
			{
			  $metrics[str_replace('ga:','',$metric->attributes()->name)] = floatval($metric_value);
			}
			else
			{
			  $metrics[str_replace('ga:','',$metric->attributes()->name)] = intval($metric_value);
			}
		  }
		  
		  $dimensions = array();
		  foreach($entry->children('http://schemas.google.com/analytics/2009')->dimension as $dimension)
		  {
			$dimensions[str_replace('ga:','',$dimension->attributes()->name)] = strval($dimension->attributes()->value);
		  }
		  
		  $results[] = new gapiReportEntry($metrics,$dimensions);
		
		  foreach($results as $result)
		  {
		    echo '<strong>'.$result.'</strong><br />';
		    echo 'Pageviews: ' . $result->getPageviews() . ' ';
//		    echo 'Visitors: ' . $result->getVisitors() . ' ';
		    echo 'TimeOnSite: ' . $result->getTimeOnSite() . ' ';
		    echo 'AvgTimeOnSite: ' . $result->getAvgTimeOnSite() . ' ';
			echo 'Visits: ' . $result->getVisits() . '<br />';
		  }
		}
		*/
		
		/**
		echo (string)$xml->feed->entry->dxp:dimension['value'].'<br />';

		simplexml_load_file($myFile);
		echo $xml->getName() . "<br>";

		foreach($xml->children() as $child)
  		{
  			echo $child->getName() . ": " . $child . "<br>";
  		}
		**/
		
		/**
		$ga = new gapi('superbarmuya@gmail.com','harvey9i');
		$ga->requestReportData(68645509,array('pagePath','browser','browserVersion','operatingSystem','keyword'),array('uniquePageViews','pageviews','visits','visitors','timeOnSite','avgTimeOnSite'));

		foreach($ga->getResults() as $result)
		{
			echo '<strong>'.$result.'</strong><br />';
			echo 'Pageviews: ' . $result->getPageviews() . ' ';
			echo 'UniquePageviews: ' . $result->getUniquePageViews() . ' ';
			echo 'Visits: ' . $result->getVisits() . ' ';
			echo 'Visitors: ' . $result->getVisitors() . ' ';
			echo 'timeOnSite: ' . $result->getTimeOnSite() . ' ';
			echo 'avgTimeOnSite: ' . $result->getAvgTimeOnSite() . '<br />';
		}

		echo '<p>Total pageviews: ' . $ga->getPageviews() . ' total visits: ' . $ga->getVisits() . '</p>';
		**/
		
		//echo("<div style='margin-bottom:10px'></div><h2>Report has been generated under GA_report folder.</h2>");
		//echo($gaResult);
		//echo("<div style='font-size:14px; padding:5; width:75px; background:#EEEEEE' align='center'><a href='/mainGrab.php'>Go back</a></div>");

	} catch (Exception $e) {

		// Log your error here
		echo "GAnalytics Connection error ({$e->getCode()}): {$e->getMessage()}";
	}