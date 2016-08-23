<div style="line-height:175%; width:100%; " align="left">
<div class="title_ga" id="title_ga"><img src="/img/down_arrow_red.png" width="11">&nbsp; Google Analytics Grabber</div>

<div style="width:450px; padding-top:40px" align="left">

<div id="spanSiteTitle" class="spanSiteTitle">
<font color="#000">Current Grabbing -></font>&nbsp; Ironpaper.com &nbsp; <font color="#999999">(Profile ID: 68645509)</font>
</div>
<form action="ga/ga_demo.php" method="post" name="ga_form" id="ga_form" onsubmit="return ray.ajax()" onClick="addText('apiName','Analytics');">
From Date: &nbsp; &nbsp;<input type="text" size="20" name="fromDate" maxlength="10">&nbsp; &nbsp; <br>
End Date: &nbsp; &nbsp;&nbsp; <input type="text" size="20" name="endDate" maxlength="10" value=<?php echo(date('Y-m-d', time() - 1 * 24 * 60 *60)); ?>><br>
How Many &nbsp; &nbsp;<input type="text" size="20" name="grabNum" value="25">&nbsp; &nbsp; <font style="font-size:12px; color:#999999">(Pick from 1 to 500)</font><br><br>
 <input type="submit" value="Go Grab!" class="submitBtn" id="submitBtn" name="submitGABtn" onClick="return validate_ga_form(this)">
</form>

</div>

<div id="hintBox" class="hintBox" align="left">
<strong>Note:</strong><br>
The following data will be retreived based on each page path:<br>
Visitor's Type<br>Source<br>Operating System<br>Browser Type<br>Browser Version<br>Keyword Used<br>Visits<br>Page Views<br>Page Visits<br>Exits<br>Visitors<br>NewVisits<br>Time On Site<br>Average Time On Site
</div>

</div>