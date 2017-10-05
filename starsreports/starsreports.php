<?php
/*
Plugin Name: Stars Reports
Plugin URI: http://starswebservice.com
Description: Plugin to obtain a multitude of Astrology Reports
Version: 2.5

History
2.1
Additional Report Person Names Capitalized
Break added before (Discounted by)
2.2
Unknown Birth Time disabled for non Natal and Child reports.
2.3
Warnings removed and green styling removed
2.4
Composite functionality added
2.5
Lunar Return functionality added

Author: David Klugmann	
Author URI: http://www.starswebservice.com
*/

session_start();
define("IMPLEMENTATION", "WORDPRESS");
define("WALESID",1000);
define("SCOTLANDID",2000);
define("ENGLANDID",3000);
define("NIRELANDID",4000);

if (IMPLEMENTATION == "STANDARD")
{
    printf("<link rel=\"stylesheet\" type=\"text/css\" href=\"sr_stars.css?id=%d\">",time());
    printf("<script type=\"text/javascript\" src=\"sr_stars.js?id=%d\" language=\"javascript1.2\"></script>",time());
}
if (IMPLEMENTATION == "WORDPRESS")
    add_action('wp_enqueue_scripts','sr_stars_include',999);

function sr_stars_include() 
{
    $url = sprintf("sr_stars.css?id=%d",time());
    wp_enqueue_style( 'sr-stars-style', plugins_url($url, __FILE__) );
    $url = sprintf("sr_stars.js?id=%d",time());
    wp_enqueue_script( 'sr-stars-js', plugins_url( $url, __FILE__ ) );
}

if (IMPLEMENTATION == "WORDPRESS")
    include( plugin_dir_path( __FILE__ ) . 'sr_stars_config.php');
if (IMPLEMENTATION == "STANDARD")
    include ('sr_stars_config.php');

function sr_display_input_form() 
{

    global $apikey;
    global $enginepath;
    global $enginename;

    global $townmatch1;
    global $towncount1;
    global $failednoname1;
    global $failednogender1;
    global $failednotown1;
    global $failednocountry1;
    global $failednodob1;
    global $failednotime1;
    global $failedzerotown1;
    global $foundmultipletown1;
    global $failedmultipletown1;
    global $foundvaguetown1;
    global $townmatch2;
    global $towncount2;
    global $failednoname2;
    global $failednogender2;
    global $failednotown2;
    global $failednocountry2;
    global $failednodob2;
    global $failednotime2;
    global $failedzerotown2;
    global $foundmultipletown2;
    global $failedmultipletown2;
    global $foundvaguetown2;
    global $townmatchreturn1;
    global $towncountreturn1;
    global $failednotownreturn1;
    global $failednocountryreturn1;
    global $failedzerotownreturn1;
    global $foundmultipletownreturn1;
    global $failedmultipletownreturn1;
    global $foundvaguetownreturn1;
    global $townmatchreturn2;
    global $towncountreturn2;
    global $failednotownreturn2;
    global $failednocountryreturn2;
    global $failedzerotownreturn2;
    global $foundmultipletownreturn2;
    global $failedmultipletownreturn2;
    global $foundvaguetownreturn2;
    global $townmatchlreturn1;
    global $towncountlreturn1;
    global $failednotownlreturn1;
    global $failednocountrylreturn1;
    global $failedzerotownlreturn1;
    global $foundmultipletownlreturn1;
    global $failedmultipletownlreturn1;
    global $foundvaguetownlreturn1;
    global $failednoemail;
    global $starspath;
    global $nextpage;

    $lastpage = $_POST['lastpage'];
    $reporttype = $_POST['reporttype'];
    $name1 = $_POST['name1'];
    $gender1 = $_POST['gender1'];
    $dob1 = $_POST['dob1'];
    $unknowntime1 = $_POST['unknowntime1'];
    if (isset($unknowntime1))
       $unknowntime1 = 'Y';
    else
        $unknowntime1 = 'N';
    $time1 = $_POST['time1'];
    $town1 = $_POST['town1'];
    $townselect1 = $_POST['townselect1'];
    $extraforecast1 = $_POST['extraforecast1'];
    $name2 = $_POST['name2'];
    $gender2 = $_POST['gender2'];
    $dob2 = $_POST['dob2'];
    $unknowntime2 = $_POST['unknowntime2'];
    if (isset($unknowntime2))
       $unknowntime2 = 'Y';
    else
        $unknowntime2 = 'N';
    $time2 = $_POST['time2'];
    $town2 = $_POST['town2'];
    $townselect2 = $_POST['townselect2'];
    $extraforecast2 = $_POST['extraforecast2'];
    $email =  $_POST['email'];
    $usebirth1 = $_POST['usebirth1'];
    if (!$usebirth1)
        $usebirth1 = 'N';
    $whichreturn1 = $_POST['whichreturn1'];
    $extrareturn1 = $_POST['extrareturn1'];
    $townreturn1 =  $_POST["townreturn1"];
    $townselectreturn1 = $_POST['townselectreturn1'];
    $usebirth2 = $_POST['usebirth2'];
    if (!$usebirth2)
        $usebirth2 = 'N';
    $whichreturn2 = $_POST['whichreturn2'];
    $townreturn2 =  $_POST["townreturn2"];
    $townselectreturn2 = $_POST['townselectreturn2'];
    $lusebirth1 = $_POST['lusebirth1'];
    if (!$lusebirth1)
        $lusebirth1 = 'N';
    $whichlreturn1 = $_POST['whichlreturn1'];
    $extralreturn1 = $_POST['extralreturn1'];
    $townlreturn1 =  $_POST["townlreturn1"];
    $townselectlreturn1 = $_POST['townselectlreturn1'];
    $timedisplaystyle = 'HH:MI:SS AM/PM';
    $dateinputstyle = 'CHOOSE';
    $nousstates = 52;
    if ( !isset( $_POST['submitted1'] ) && $nextpage == 1 ) 
    {
         $countryid1 = -1;
         $countryid2 = -1;
         $countryidreturn1 = -1;
    }
    else
    {
        $countryid1 = $_POST['countryid1'];
        $countryid2 = $_POST['countryid2'];
        $countryidreturn1 = $_POST['countryidreturn1'];
    }
    if ( !isset( $_POST['submitted2'] ) && $nextpage == 2 && $reporttype != 'SOLAR RETURN') 
    {
         $countryidreturn1 = -1;
         $countryidreturn2 = -1;
    }
    else
    {
        $countryidreturn1 = $_POST['countryidreturn1'];
        $countryidreturn2 = $_POST['countryidreturn2'];
    }
    if ( !isset( $_POST['submitted2'] ) && $nextpage == 2 && $reporttype != 'LUNAR RETURN') 
    {
         $countryidlreturn1 = -1;
         $countryidlreturn2 = -1;
    }
    else
    {
        $countryidlreturn1 = $_POST['countryidlreturn1'];
        $countryidlreturn2 = $_POST['countryidlreturn2'];
    }

    if ( !isset( $_POST['submitted1'] ) && $nextpage == 1) 
    {
        $email = $_SESSION['session_email'];

        $name1 =  $_SESSION['session_name1'];
        $gender1 =  $_SESSION['session_gender1'];
        $dob1 = $_SESSION['session_dob1'];
        $time1 = $_SESSION['session_time1'];
        $unknowntime1 = $_SESSION['session_unknowntime1'];
        $town1 = $_SESSION['session_town1'];
        if ($_SESSION['session_countryid1'])
            $countryid1 = $_SESSION['session_countryid1'];

        $name2 =  $_SESSION['session_name2'];
        $gender2 =  $_SESSION['session_gender2'];
        $dob2 = $_SESSION['session_dob2'];
        $time2 = $_SESSION['session_time2'];
        $unknowntime2 = $_SESSION['session_unknowntime2'];
        $town2 = $_SESSION['session_town2'];
        if ($_SESSION['session_countryid2'])
            $countryid2 = $_SESSION['session_countryid2'];
        $townreturn1 = $_SESSION['session_townreturn1_primary'];
        if ($_SESSION['session_countryidreturn1_primary'])
            $countryidreturn1 = $_SESSION['session_countryidreturn1_primary'];
        $usebirth1 = $_SESSION['session_usebirth1_primary'];
        $whichreturn1 = $_SESSION['session_whichreturn1_primary'];
    }
    if ( !isset( $_POST['submitted2'] ) && $nextpage == 2 ) 
    {
        if ($reporttype != 'SOLAR RETURN')
        {
            $townreturn1 = $_SESSION['session_townreturn1_additional'];
            if ($_SESSION['session_countryidreturn1_additional'])
                $countryidreturn1 = $_SESSION['session_countryidreturn1_additional'];
            $usebirth1 = $_SESSION['session_usebirth1_additional'];
            $whichreturn1 = $_SESSION['session_whichreturn1_additional'];
            $townreturn2 = $_SESSION['session_townreturn2_additional'];
            if ($_SESSION['session_countryidreturn2_additional'])
                $countryidreturn2 = $_SESSION['session_countryidreturn2_additional'];
            $usebirth2 = $_SESSION['session_usebirth2_additional'];
            $whichreturn2 = $_SESSION['session_whichreturn2_additional'];
        }
    }

    echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';

    if ($nextpage == 1)
    {        

        printf ("<input type=\"hidden\" name=\"lastpage\" value=1></input>");
        $paramname = 'REPORT_AMOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $report_amount= $returnxmlstring->paramvalue[0];
        
        $paramname = 'REPORT_CURRENCY';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $report_currency = $returnxmlstring->paramvalue[0];
        if ($report_currency == 'USD')
            $currencysymbol = '$';
        elseif ($report_currency == 'EUR')
            $currencysymbol = 'â‚¬';
        elseif ($report_currency == 'GBP')
            $currencysymbol = 'Â£';

        $paramname = 'EXTRA_FORECASTS_DISCOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $extra_forecasts_discount = (int) $returnxmlstring->paramvalue[0];

        $paramname = 'EXTRA_SOLAR_RETURNS_DISCOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $extra_solar_returns_discount = (int) $returnxmlstring->paramvalue[0];

        $paramname = 'EXTRA_LUNAR_RETURNS_DISCOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $extra_lunar_returns_discount = (int) $returnxmlstring->paramvalue[0];

        $paramname = 'REPORT_TYPE';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $num_reports = count($returnxmlstring->paramvalue);
        $paramname = 'REPORT_DESCRIPTION';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring2 = sr_stars_loadXML($url);
        printf ("<table class=\"entrytable\" style=\"max-width:600px\">");
        printf ("<tr><td colspan=2 class=\"typetitle\">Choose  Report Type</td></tr>");
        printf ("<tr><td class=\"entrytitle\">Report Type</td><td class=\"entrystandard\"><select class=\"entryinput\" name=\"reporttype\" id=\"reporttype\" onchange=\"sr_checkreporttype(this);\" size=1>");
        for ($count = 0; $count < $num_reports; $count++)
        {
            $cur_reporttype = $returnxmlstring->paramvalue[$count];
            $report_description = $returnxmlstring2->paramvalue[$count];
            printf ("<option value=\"%s\"",$cur_reporttype);
            if ($reporttype == $cur_reporttype)
                printf (" selected");
            printf (">%s</option>",$report_description);
        }
        printf ("</td></tr>");
            
        printf ("<tr><td colspan=2 class=\"persontitle\">Enter Person's Details</td></tr>");
        if ($failednoname1)
            printf ("<tr><td colspan=2 class=\"errormessage\">*Please enter a Name</td></tr>");
        printf ("<tr><td class=\"entrytitle\">Name</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" name=\"name1\" value=\"%s\"></input></td></tr>",$name1);
    
        if ($failednogender1)
            printf ("<tr><td colspan=2 class=\"errormessage\">*Please select a Gender</td></tr>");
        printf ("<tr><td class=\"entrytitle\">Gender</td><td class=\"entrystandard\"><select class=\"entryinput\" name=\"gender1\" id=\"gender1\" size=1>");
        printf ("<option value=\"\"></option>");
        printf ("<option");
        if ($gender1 == "M")
            printf (" selected\n");
        printf (" value=\"M\">Male</option>");
        printf ("<option");
        if ($gender1 == "F")
            printf (" selected");
        printf (" value=\"M\">Female</option>");
        printf ("<option");
        printf ("</select>");
        printf ("</td></tr>");
    
        if ($failednodob1)
            printf ("<tr><td colspan=2 class=\"errormessage\">*Please enter a Date of Birth</td></tr>");
        printf ("<tr><td class=\"entrytitle\">Date of Birth</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" onchange=\"sr_checkdate(this,'%s');\" id=\"dob1\" name=\"dob1\" value=\"%s\"></input></td></tr>",$dateinputstyle,$dob1);
        if ($failednotime1)
            printf ("<tr id=\"enter1time\"><td colspan=2 class=\"errormessage\">*Please enter a Time of Birth</td></tr>");
        printf ("<tr id=\"time1line\"><td class=\"entrytitle\">Time of Birth</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" onchange=\"sr_checktime(this,'%s');\" id=\"time1\" name=\"time1\" value=\"%s\"></input></td></tr>",$timedisplaystyle,$time1);
        printf ("<tr id=\"unknowntime1line\"><td class=\"entrytitle\">Unknown Time</td>");
        printf ("<td><input style=\"padding:0px; margin:0px; border:0px;\" name=\"unknowntime1\" id=\"unknowntime1\" type=\"checkbox\" onchange=\"sr_checkdisplaytime(1);\"");
        if ($unknowntime1 == 'Y')
            printf (" checked");
        printf ("></input></td></tr>");
        if ($failednotown1)
            printf ("<tr><td colspan=2 class=\"errormessage\">*Please enter a City</td></tr>");
        elseif ($foundvaguetown1)
        {
            printf ("<tr><td colspan=2 class=\"errormessage\">*Closest Town Listed / Please proceed to Confirm</td></tr>");
            $town1 = $townmatch1[0]->town;
        }
        elseif ($failedzerotown1)
            printf ("<tr><td colspan=2 class=\"errormessage\">*Failed to find that city in the US State / Country</td></tr>");
        if (!$foundmultipletown1)
        {
            printf ("<tr><td class=\"entrytitle\">Enter City Only</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" id=\"town1\" name=\"town1\" value=\"%s\"></input></td></tr>",$town1);
            if ($failednocountry1)
                printf ("<tr><td colspan=2 class=\"errormessage\">*Please select a US State / Country</td></tr>");
            printf ("<tr>");
            printf ("<td class=\"entrytitle\">US State / Country</td>");
            printf ("<td class=\"entrystandard\">");
            $url = $starspath . "/listcountries.php";
    		$returnxmlstring = sr_stars_loadXML($url);
            printf ("<select class=\"entryinput\" name=\"countryid1\" id=\"countryid1\" size=1>");
            printf ("<option value=-1></option>");
            $displaywales1 = FALSE;
            $displayscotland1 = FALSE;
            $displayengland1 = FALSE;
            $displaynireland1 = FALSE;
            for ($count = $nousstates; $count <$returnxmlstring->rowsreturned; $count++)
            {
                if ((strcasecmp($returnxmlstring->country[$count],"Wales") > 0) && $displaywales1 == FALSE)
                {
                    printf ("<option value=%d", WALESID);
                    if ($countryid1 == WALESID)
                        printf (" selected=selected");
                    printf (">%s</option>","Wales");
                    $displaywales1 = TRUE;
                }
                if ((strcasecmp($returnxmlstring->country[$count],"Scotland") > 0) && $displayscotland1 == FALSE)
                {
                    printf ("<option value=%d", SCOTLANDID);
                    if ($countryid1 == SCOTLANDID)
                        printf (" selected=selected");
                    printf (">%s</option>","Scotland");
                    $displayscotland1 = TRUE;
                }
                if ((strcasecmp($returnxmlstring->country[$count],"England") > 0) && $displayengland1 == FALSE)
                {
                    printf ("<option value=%d", ENGLANDID);
                    if ($countryid1 == ENGLANDID)
                        printf (" selected=selected");
                    printf (">%s</option>","England");
                    $displayengland1 = TRUE;
                }
                if ((strcasecmp($returnxmlstring->country[$count],"Northern Ireland") > 0) && $displaynireland1 == FALSE)
                {
                    printf ("<option value=%d", NIRELANDID);
                    if ($countryid1 == NIRELANDID)
                        printf (" selected=selected");
                    printf (">%s</option>","Northern Ireland");
                    $displaynireland1 = TRUE;
                }
                printf ("<option value=%d", $count);
                if ($count == $countryid1)
                    printf (" selected=selected");
                printf (">%s</option>",$returnxmlstring->country[$count]);
            }
            for ($count = 0; $count <$nousstates; $count++)
            {
                printf ("<option value=%d", $count);
                if ($count == $countryid1)
                    printf (" selected=selected");
                printf (">%s</option>",$returnxmlstring->country[$count]);
            }
            printf ("</select>");
            printf ("</td></tr>");
    
        }
        else
        {
            printf ("<input type=\"hidden\" name=town1 value=\"%s\">",$town1);
            printf ("<input type=\"hidden\" name=countryid1 value=\"%d\">",$countryid1);
            printf ("<tr><td colspan=2 class=\"errormessage\">Multiple cities found. Please choose one.</td></tr>");
            printf ("<tr><td colspan=2 class=\"entrylong\"><select class=\"entryinput\" name=\"townselect1\" size=5>");
            for ($count = 0; $count <$towncount1; $count++)
            {
                $townidentifier = sprintf("%s#%s#%s#%d#%d#%d#%d", $townmatch1[$count]->town,$townmatch1[$count]->county,$townmatch1[$count]->country,$townmatch1[$count]->latitude,$townmatch1[$count]->longitude,$townmatch1[$count]->typetable,$townmatch1[$count]->zonetable);
                printf ("<option");
                if ($townidentifier == $townselect1)
                     printf (" selected");
                printf (" value=\"%s\">%s %s %s Lat (%.2lf) Long (%.2lf)</option>",$townidentifier,ucwords($townmatch1[$count]->town), ucwords($townmatch1[$count]->county), ucwords($townmatch1[$count]->country),($townmatch1[$count]->latitude/3600), ($townmatch1[$count]->longitude/3600)*-1);
            }
            printf ("</select></td></tr></select>");
        }
        printf ("<tr id=\"firstforecastentry1\"><td colspan=2 class=\"persontitle\">Add Additional Transit Reports</td></tr>");
        printf ("<tr id=\"firstforecastentry2\"><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Transits %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_forecasts_discount)/100.0),2));
        if ($extra_forecasts_discount)
            printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_forecasts_discount);
        printf ("</td><td class=\"entrystandard\"><select name=\"extraforecast1\">");
        for ($count = 0; $count < 100; $count++)
        {
            printf ("<option value=\"%d\"",$count);
            if ($count == $extraforecast1)
                printf (" selected");
            printf (">%d</option>",$count);
        }
        printf ("</select></td></tr>");

        printf ("<tr id=\"secondpersonentry1\"><td colspan=2 class=\"persontitle\">Enter Second Person's Details</td></tr>");
        if ($failednoname2)
            printf ("<tr id=\"secondpersonentry2\"><td colspan=2 class=\"errormessage\">*Please enter a Name</td></tr>");
        printf ("<tr id=\"secondpersonentry3\"><td class=\"entrytitle\">Name</td><td  class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" name=\"name2\" value=\"%s\"></input></td></tr>",$name2);
        printf ("</tr>");
        if ($failednogender2)
            printf ("<tr id=\"secondpersonentry4\"><td colspan=2 class=\"errormessage\">*Please select a Gender</td></tr>");
        printf ("<tr id=\"secondpersonentry5\"><td class=\"entrytitle\">Gender</td><td  class=\"entrystandard\"><select class=\"entryinput\" name=\"gender2\" id=\"gender2\" size=1>");
        printf ("<option value=\"\"></option>");
        printf ("<option");
        if ($gender2 == "M")
            printf (" selected\n");
        printf (" value=\"M\">Male</option>");
        printf ("<option");
        if ($gender2 == "F")
            printf (" selected");
        printf (" value=\"F\">Female</option>");
        printf ("<option");
        printf ("</select>");
        printf ("</td></tr>");
        if ($failednodob2)
            printf ("<tr id=\"secondpersonentry6\"><td colspan=2 class=\"errormessage\">*Please enter a Date of Birth</td></tr>");
        printf ("<tr id=\"secondpersonentry7\"><td class=\"entrytitle\">Date of Birth</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" onchange=\"sr_checkdate(this,'%s');\" id=\"dob2\" name=\"dob2\" value=\"%s\"></input></td></tr>",$dateinputstyle,$dob2);
        if ($failednotime2)
            printf ("<tr id=\"secondpersonentry8\"><td colspan=2 class=\"errormessage\">*Please enter a Time of Birth</td></tr>");
        printf ("<tr id=\"secondpersonentry9\"><td class=\"entrytitle\">Time of Birth</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" onchange=\"sr_checktime(this,'%s');\" id=\"time2\" name=\"time2\" value=\"%s\"></input></td></tr>",$timedisplaystyle,$time2);
        printf ("<tr id=\"secondpersonentry10\"><td class=\"entrytitle\">Unknown Time</td>");
        printf ("<td><input padding:0px; margin:0px; border:0px;\" name=\"unknowntime2\" id=\"unknowntime2\" type=\"checkbox\" onchange=\"sr_checkdisplaytime(2);\"");
        if ($unknowntime2 == 'Y')
            printf (" checked");
        printf ("></input></td></tr>");
        if ($failednotown2)
            printf ("<tr id=\"secondpersonentry11\"><td colspan=2 class=\"errormessage\">*Please enter a City</td></tr>");
        elseif ($foundvaguetown2)
        {
            printf ("<tr><td colspan=2 class=\"errormessage\">*Closest Town Listed / Please proceed to Confirm</td></tr>");
            $town2 = $townmatch2[0]->town;
        }
        elseif ($failedzerotown2)
            printf ("<tr id=\"secondpersonentry11\"><td colspan=2 class=\"errormessage\">*Failed to find that city in the US State / Country</td></tr>");
        if (!$foundmultipletown2)
        {
        printf ("<tr id=\"secondpersonentry12\"><td class=\"entrytitle\">Enter City Only</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" id=\"town2\" name=\"town2\" value=\"%s\"></input></td></tr>",$town2);
        if ($failednocountry2)
            printf ("<tr id=\"secondpersonentry13\"><td colspan=2 class=\"errormessage\">*Please select a US State / Country</td></tr>");
        printf ("<tr id=\"secondpersonentry14\">");
        printf ("<td class=\"entrytitle\">US State / Country</td>");
        printf ("<td class=\"entrystandard\">");
        $url = $starspath . "/listcountries.php";
        $returnxmlstring = sr_stars_loadXML($url);
        printf ("<select class=\"entryinput\" name=\"countryid2\" id=\"countryid2\" size=1>");
        printf ("<option value=-1></option>");
        $displaywales2 = FALSE;
        $displayscotland2 = FALSE;
        $displayengland2 = FALSE;
        $displaynireland2 = FALSE;
        for ($count = $nousstates; $count <$returnxmlstring->rowsreturned; $count++)
        {
            if ((strcasecmp($returnxmlstring->country[$count],"Wales") > 0) && $displaywales2 == FALSE)
            {
                printf ("<option value=%d", WALESID);
                if ($countryid2 == WALESID)
                    printf (" selected=selected");
                printf (">%s</option>","Wales");
                $displaywales2 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"Scotland") > 0) && $displayscotland2 == FALSE)
            {
                printf ("<option value=%d", SCOTLANDID);
                if ($countryid2 == SCOTLANDID)
                    printf (" selected=selected");
                printf (">%s</option>","Scotland");
                $displayscotland2 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"England") > 0) && $displayengland2 == FALSE)
            {
                printf ("<option value=%d", ENGLANDID);
                if ($countryid2 == ENGLANDID)
                    printf (" selected=selected");
                printf (">%s</option>","England");
                $displayengland2 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"Northern Ireland") > 0) && $displaynireland2 == FALSE)
            {
                printf ("<option value=%d", NIRELANDID);
                if ($countryid2 == NIRELANDID)
                    printf (" selected=selected");
                printf (">%s</option>","Northern Ireland");
                $displaynireland2 = TRUE;
            }
            printf ("<option value=%d", $count);
            if ($count == $countryid2)
                printf (" selected=selected");
            printf (">%s</option>",$returnxmlstring->country[$count]);
        }
        for ($count = 0; $count <$nousstates; $count++)
        {
            printf ("<option value=%d", $count);
            if ($count == $countryid2)
                printf (" selected=selected");
            printf (">%s</option>",$returnxmlstring->country[$count]);
        }
        printf ("</select>");
        printf ("</td>");
        printf ("</tr>");
    
        }
        else
        {
            printf ("<input type=\"hidden\" name=town2 value=\"%s\">",$town2);
            printf ("<input type=\"hidden\" name=countryid2 value=\"%d\">",$countryid2);
            printf ("<tr><td colspan=2 class=\"errormessage\">Multiple cities found. Please choose one.</td></tr>");
            printf ("<tr><td colspan=2 class=\"entrylong\"><select class=\"entryinput\" name=\"townselect2\" size=5>");
            for ($count = 0; $count <$towncount2; $count++)
            {
                $townidentifier = sprintf("%s#%s#%s#%d#%d#%d#%d", $townmatch2[$count]->town,$townmatch2[$count]->county,$townmatch2[$count]->country,$townmatch2[$count]->latitude,$townmatch2[$count]->longitude,$townmatch2[$count]->typetable,$townmatch2[$count]->zonetable);
                printf ("<option");
                if ($townidentifier == $townselect2)
                     printf (" selected");
                printf (" value=\"%s\">%s %s %s Lat (%.2lf) Long (%.2lf)</option>",$townidentifier,ucwords($townmatch2[$count]->town), ucwords($townmatch2[$count]->county), ucwords($townmatch2[$count]->country),($townmatch2[$count]->latitude/3600), ($townmatch2[$count]->longitude/3600)*-1);
            }
            printf ("</select></td></tr></select>");
        }
        printf ("<tr id=\"firstreturnentry1\"><td colspan=2 class=\"persontitle\">Enter Solar Return Details</td></tr>");
        printf ("<tr id=\"firstreturnentry2\"><td class=\"entrytitle\">Use Birth Location</td>");
        printf ("<td class=\"entrystandard\"><input style=\"padding:0px; margin:0px; border:0px;\" name=\"usebirth1\" id=\"usebirth1\" type=\"checkbox\" value=\"Y\" onclick=\"sr_checkusebirth(this,'first')\"");
        if (!isset( $_POST['submitted1']) || $usebirth1 == 'Y')
            printf (" checked");
        printf ("></input></td></tr>");
        if (!$foundmultipletownreturn1)
        {
        if ($failednotownreturn1)
            printf ("<tr id=\"firstreturnentry3\"><td colspan=2 class=\"errormessage\">*Please enter a City</td></tr>");
        elseif ($foundvaguetownreturn1)
        {
            printf ("<tr><td colspan=2 class=\"errormessage\">*Closest Town Listed / Please proceed to Confirm</td></tr>");
            $townreturn1 = $townmatchreturn1[0]->town;
        }
        elseif ($failedzerotownreturn1)
            printf ("<tr id=\"firstreturnentry4\"><td colspan=2 class=\"errormessage\">*Failed to find that city in the US State / Country</td></tr>");
        printf ("<tr id=\"firstreturnentry5\"><td class=\"entrytitle\">Enter City Only</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" id=\"townreturn1\" name=\"townreturn1\" value=\"%s\"></input></td></tr>",$townreturn1);
        if ($failednocountryreturn1)
            printf ("<tr id=\"firstreturnentry6\"><td colspan=2 class=\"errormessage\">*Please select a US State / Country</td></tr>");
        printf ("<tr id=\"firstreturnentry7\">");
        printf ("<td class=\"entrytitle\">US State / Country</td>");
        printf ("<td class=\"entrystandard\">");
        $url = $starspath . "/listcountries.php";
        $returnxmlstring = sr_stars_loadXML($url);
        printf ("<select class=\"entryinput\" name=\"countryidreturn1\" id=\"countryidreturn1\" size=1>");
        printf ("<option value=-1></option>");
        $displaywalesret1 = FALSE;
        $displayscotlandret1 = FALSE;
        $displayenglandret1 = FALSE;
        $displaynirelandret1 = FALSE;
        for ($count = $nousstates; $count <$returnxmlstring->rowsreturned; $count++)
        {
            if ((strcasecmp($returnxmlstring->country[$count],"Wales") > 0) && $displaywalesret1 == FALSE)
            {
                printf ("<option value=%d", WALESID);
                if ($countryidreturn1 == WALESID)
                    printf (" selected=selected");
                printf (">%s</option>","Wales");
                $displaywalesret1 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"Scotland") > 0) && $displayscotlandret1 == FALSE)
            {
                printf ("<option value=%d", SCOTLANDID);
                if ($countryidreturn1 == SCOTLANDID)
                    printf (" selected=selected");
                printf (">%s</option>","Scotland");
                $displayscotlandret1 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"England") > 0) && $displayenglandret1 == FALSE)
            {
                printf ("<option value=%d", ENGLANDID);
                if ($countryidreturn1 == ENGLANDID)
                    printf (" selected=selected");
                printf (">%s</option>","England");
                $displayenglandret1 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"Northern Ireland") > 0) && $displaynirelandret1 == FALSE)
            {
                printf ("<option value=%d", NIRELANDID);
                if ($countryidreturn1 == NIRELANDID)
                    printf (" selected=selected");
                printf (">%s</option>","Northern Ireland");
                $displaynirelandret1 = TRUE;
            }
            printf ("<option value=%d", $count);
            if ($count == $countryidreturn1)
                printf (" selected=selected");
            printf (">%s</option>",$returnxmlstring->country[$count]);
        }
        for ($count = 0; $count <$nousstates; $count++)
        {
            printf ("<option value=%d", $count);
            if ($count == $countryidreturn1)
                printf (" selected=selected");
            printf (">%s</option>",$returnxmlstring->country[$count]);
        }
        printf ("</select>");
        printf ("</td>");
        printf ("</tr>");
        }
        else
        {
            printf ("<input type=\"hidden\" name=townreturn1 value=\"%s\">",$townreturn1);
            printf ("<input type=\"hidden\" name=countryidreturn1 value=\"%d\">",$countryidreturn1);
            printf ("<tr><td id=\"firstreturnentry8\" colspan=2 class=\"errormessage\">Multiple cities found. Please choose one.</td></tr>");
            printf ("<tr><td id=\"firstreturnentry9\" colspan=2 class=\"entrylong\"><select class=\"entryinput\" name=\"townselectreturn1\" size=5>");
            for ($count = 0; $count <$towncountreturn1; $count++)
            {
                $townidentifier = sprintf("%s#%s#%s#%d#%d#%d#%d", $townmatchreturn1[$count]->town,$townmatchreturn1[$count]->county,$townmatchreturn1[$count]->country,$townmatchreturn1[$count]->latitude,$townmatchreturn1[$count]->longitude,$townmatchreturn1[$count]->typetable,$townmatchreturn1[$count]->zonetable);
                printf ("<option");
                if ($townidentifier == $townselectreturn1)
                     printf (" selected");
                printf (" value=\"%s\">%s %s %s Lat (%.2lf) Long (%.2lf)</option>",$townidentifier,ucwords($townmatchreturn1[$count]->town), ucwords($townmatchreturn1[$count]->county), ucwords($townmatchreturn1[$count]->country),($townmatchreturn1[$count]->latitude/3600), ($townmatchreturn1[$count]->longitude/3600)*-1);
            }
            printf ("</select></td></tr></select>");
        }
        printf ("<tr id=\"firstreturnentry10\"><td class=\"entrytitle\">Last (Last Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" stylee=\"padding:0px; margin:0px;\" name=\"whichreturn1\" value=\"LAST\"");
        if (!$whichreturn1 || $whichreturn1 == 'LAST')
            printf (" checked");
        printf ("></td></tr>");
        printf ("<tr id=\"firstreturnentry11\"><td class=\"entrytitle\">Next (Next Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" style=\"padding:0px; margin:0px;\" name=\"whichreturn1\" value=\"NEXT\"");
        if ($whichreturn1 == 'NEXT')
            printf (" checked");
        printf ("></td></tr>");
        printf ("<tr id=\"firstreturnentry12\"><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Solar Returns %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_solar_returns_discount)/100.0),2));
        if ($extra_solar_returns_discount)
            printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_solar_returns_discount);

        printf ("</td><td class=\"entrystandard\"><select name=\"extrareturn1\">");
        for ($count = 0; $count < 100; $count++)
        {
            printf ("<option value=\"%d\"",$count);
            if ($count == $extrareturn1)
                printf (" selected");
            printf (">%d</option>",$count);
        }
        printf ("</select></td></tr>");
        printf ("<tr id=\"firstlreturnentry1\"><td colspan=2 class=\"persontitle\">Enter Lunar Return Details</td></tr>");
        printf ("<tr id=\"firstlreturnentry2\"><td class=\"entrytitle\">Use Birth Location</td>");
        printf ("<td class=\"entrystandard\"><input style=\"padding:0px; margin:0px; border:0px;\" name=\"lusebirth1\" id=\"lusebirth1\" type=\"checkbox\" value=\"Y\" onclick=\"sr_checkusebirth(this,'firstl')\"");
        if (!isset( $_POST['submitted1'] ) || $lusebirth1 == 'Y')
            printf (" checked");
        printf ("></input></td></tr>");
        if (!$foundmultipletownlreturn1)
        {
        if ($failednotownilreturn1)
            printf ("<tr id=\"firstlreturnentry3\"><td colspan=2 class=\"errormessage\">*Please enter a City</td></tr>");
        elseif ($foundvaguetownlreturn1)
        {
            printf ("<tr><td colspan=2 class=\"errormessage\">*Closest Town Listed / Please proceed to Confirm</td></tr>");
            $townlreturn1 = $townmatchlreturn1[0]->town;
        }
        elseif ($failedzerotownlreturn1)
            printf ("<tr id=\"firstlreturnentry4\"><td colspan=2 class=\"errormessage\">*Failed to find that city in the US State / Country</td></tr>");
        printf ("<tr id=\"firstlreturnentry5\"><td class=\"entrytitle\">Enter City Only</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" id=\"townlreturn1\" name=\"townlreturn1\" value=\"%s\"></input></td></tr>",$townlreturn1);
        if ($failednocountrylreturn1)
            printf ("<tr id=\"firstlreturnentry6\"><td colspan=2 class=\"errormessage\">*Please select a US State / Country</td></tr>");
        printf ("<tr id=\"firstlreturnentry7\">");
        printf ("<td class=\"entrytitle\">US State / Country</td>");
        printf ("<td class=\"entrystandard\">");
        $url = $starspath . "/listcountries.php";
        $returnxmlstring = sr_stars_loadXML($url);
        printf ("<select class=\"entryinput\" name=\"countryidlreturn1\" id=\"countryidlreturn1\" size=1>");
        printf ("<option value=-1></option>");
        $displaywalesret1 = FALSE;
        $displayscotlandret1 = FALSE;
        $displayenglandret1 = FALSE;
        $displaynirelandret1 = FALSE;
        for ($count = $nousstates; $count <$returnxmlstring->rowsreturned; $count++)
        {
            if ((strcasecmp($returnxmlstring->country[$count],"Wales") > 0) && $displaywalesret1 == FALSE)
            {
                printf ("<option value=%d", WALESID);
                if ($countryidlreturn1 == WALESID)
                    printf (" selected=selected");
                printf (">%s</option>","Wales");
                $displaywalesret1 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"Scotland") > 0) && $displayscotlandret1 == FALSE)
            {
                printf ("<option value=%d", SCOTLANDID);
                if ($countryidlreturn1 == SCOTLANDID)
                    printf (" selected=selected");
                printf (">%s</option>","Scotland");
                $displayscotlandret1 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"England") > 0) && $displayenglandret1 == FALSE)
            {
                printf ("<option value=%d", ENGLANDID);
                if ($countryidlreturn1 == ENGLANDID)
                    printf (" selected=selected");
                printf (">%s</option>","England");
                $displayenglandret1 = TRUE;
            }
            if ((strcasecmp($returnxmlstring->country[$count],"Northern Ireland") > 0) && $displaynirelandret1 == FALSE)
            {
                printf ("<option value=%d", NIRELANDID);
                if ($countryidlreturn1 == NIRELANDID)
                    printf (" selected=selected");
                printf (">%s</option>","Northern Ireland");
                $displaynirelandret1 = TRUE;
            }
            printf ("<option value=%d", $count);
            if ($count == $countryidlreturn1)
                printf (" selected=selected");
            printf (">%s</option>",$returnxmlstring->country[$count]);
        }
        for ($count = 0; $count <$nousstates; $count++)
        {
            printf ("<option value=%d", $count);
            if ($count == $countryidlreturn1)
                printf (" selected=selected");
            printf (">%s</option>",$returnxmlstring->country[$count]);
        }
        printf ("</select>");
        printf ("</td>");
        printf ("</tr>");
        }
        else
        {
            printf ("<input type=\"hidden\" name=townlreturn1 value=\"%s\">",$townlreturn1);
            printf ("<input type=\"hidden\" name=countryidlreturn1 value=\"%d\">",$countryidlreturn1);
            printf ("<tr><td id=\"firstlreturnentry8\" colspan=2 class=\"errormessage\">Multiple cities found. Please choose one.</td></tr>");
            printf ("<tr><td id=\"firstlreturnentry9\" colspan=2 class=\"entrylong\"><select class=\"entryinput\" name=\"townselectlreturn1\" size=5>");
            for ($count = 0; $count <$towncountlreturn1; $count++)
            {
                $townidentifier = sprintf("%s#%s#%s#%d#%d#%d#%d", $townmatchlreturn1[$count]->town,$townmatchlreturn1[$count]->county,$townmatchlreturn1[$count]->country,$townmatchlreturn1[$count]->latitude,$townmatchlreturn1[$count]->longitude,$townmatchlreturn1[$count]->typetable,$townmatchlreturn1[$count]->zonetable);
                printf ("<option");
                if ($townidentifier == $townselectlreturn1)
                     printf (" selected");
                printf (" value=\"%s\">%s %s %s Lat (%.2lf) Long (%.2lf)</option>",$townidentifier,ucwords($townmatchlreturn1[$count]->town), ucwords($townmatchlreturn1[$count]->county), ucwords($townmatchlreturn1[$count]->country),($townmatchlreturn1[$count]->latitude/3600), ($townmatchlreturn1[$count]->longitude/3600)*-1);
            }
            printf ("</select></td></tr></select>");
        }
        printf ("<tr id=\"firstlreturnentry10\"><td class=\"entrytitle\">Last (Last Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" style=\"padding:0px; margin:0px;\" name=\"whichlreturn1\" value=\"LAST\"");
        if (!$whichreturn1 || $whichreturn1 == 'LAST')
            printf (" checked");
        printf ("></td></tr>");
        printf ("<tr id=\"firstlreturnentry11\"><td class=\"entrytitle\">Next (Next Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" style=\"padding:0px; margin:0px;\" name=\"whichlreturn1\" value=\"NEXT\"");
        if ($whichreturn1 == 'NEXT')
            printf (" checked");
        printf ("></td></tr>");
        printf ("<tr id=\"firstlreturnentry12\"><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Lunar Returns %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_lunar_returns_discount)/100.0),2));
        if ($extra_lunar_returns_discount)
            printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_lunar_returns_discount);

        printf ("</td><td class=\"entrystandard\"><select name=\"extralreturn1\">");
        for ($count = 0; $count < 100; $count++)
        {
            printf ("<option value=\"%d\"",$count);
            if ($count == $extralreturn1)
                printf (" selected");
            printf (">%d</option>",$count);
        }
        printf ("</select></td></tr>");
        printf ("<tr><td colspan=2 class=\"contacttitle\">Enter Contact Details</td></tr>");
        if ($failednoemail)
            printf ("<tr><td colspan=2 class=\"errormessage\">*Please enter an Email Address</td></tr>");
        printf ("<tr><td class=\"entrytitle\">Email Address</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" type=text id=\"email\" name=\"email\" value=\"%s\"></input></td></tr>",$email);
        printf ("<tr><td colspan=2 style=\"text-align:center; padding-top:25px;\">");
        $paramname = 'PAYMENT_METHOD';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $num_payment_methods = count($returnxmlstring->paramvalue);
        for ($count = 0; $count < $num_payment_methods; $count++)
        {
            $cur_payment_method = $returnxmlstring->paramvalue[$count];
            printf ("<button type=\"submit\" name=\"submitted1\" value=\"%s\" class=\"button green medium\">Pay via %s",$cur_payment_method,ucwords(strtolower($cur_payment_method)));
        }
        printf ("</tr>");
        printf ("</table>");
        printf ("<script>");
        printf ("sr_checkdisplaytime(1);");
        printf ("sr_checkdisplaytime(2);");
        printf ("sr_checkreporttype(document.getElementById('reporttype'));");
        printf ("</script>");
    }
    else
    {
        printf ("<input type=\"hidden\" name=\"lastpage\" value=2></input>");
        $paramname = 'REPORT_AMOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $report_amount= $returnxmlstring->paramvalue[0];
        
        $paramname = 'REPORT_CURRENCY';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $report_currency = $returnxmlstring->paramvalue[0];
        if ($report_currency == 'USD')
            $currencysymbol = '$';
        elseif ($report_currency == 'EUR')
            $currencysymbol = '€';
        elseif ($report_currency == 'GBP')
            $currencysymbol = '£';

        $paramname = 'EXTRA_FORECASTS_DISCOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $extra_forecasts_discount = (int) $returnxmlstring->paramvalue[0];
        $paramname = 'EXTRA_SOLAR_RETURNS_DISCOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $extra_solar_returns_discount = (int) $returnxmlstring->paramvalue[0];
        $paramname = 'EXTRA_LUNAR_RETURNS_DISCOUNT';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $extra_lunar_returns_discount = (int) $returnxmlstring->paramvalue[0];

        $paramname = 'REPORT_TYPE';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $num_reports = count($returnxmlstring->paramvalue);
        $paramname = 'REPORT_DESCRIPTION';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring2 = sr_stars_loadXML($url);
        printf ("<table class=\"entrytable\" style=\"max-width:600px\">");
        printf ("<tr><td class=\"additionaltitle\" colspan=5>Additional Reports you may wish to include</td></td>");
        $displaytitles = TRUE;
        if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
        {
            for ($count = 0; $count < $num_reports; $count++)
            {
                $cur_reporttype = $returnxmlstring->paramvalue[$count];
                $report_description = $returnxmlstring2->paramvalue[$count];
                if ($reporttype != $cur_reporttype)
                {
                    if ($cur_reporttype == 'SYNASTRY' || $cur_reporttype == 'STARMATCH' || $cur_reporttype == 'COMPOSITE')
                    {
                        if ($displaytitles)
                        {
                            printf ("<tr>");
                            printf ("<td class=\"additionalreporttitle\">Report Description</td>");
                            printf ("<td class=\"additionalreporttitle\">Price</td>");
                            printf ("<td colspan=2></td>");
                            printf ("</tr>");
                            $displaytitles = FALSE;
                        }
                        printf ("<tr>");
                        printf ("<td>%s</td>",$report_description);
                        printf ("<td>%s%.2lf</td>",$currencysymbol,$report_amount);
                        printf ("<td colspan=2 style=\"text-align:center\"><input");
                        if (isset($_POST['extraboth']) && in_array($cur_reporttype,$_POST['extraboth']))
                            printf (" checked");
                        printf (" type=\"checkbox\" name=\"extraboth[]\" value=\"%s\"></input></td>",$cur_reporttype);
                        printf ("</tr>");
                    }
                }
            }
        }
        $displaytitles = TRUE;
        for ($count = 0; $count < $num_reports; $count++)
        {
            $cur_reporttype = $returnxmlstring->paramvalue[$count];
            if ($cur_reporttype != 'ADULT' && $cur_reporttype != 'CHILD' && $unknowntime1 == 'Y')
                continue;
            if ($cur_reporttype == 'LUNAR RETURN')
                continue;
            $report_description = $returnxmlstring2->paramvalue[$count];
            if ($reporttype != $cur_reporttype)
            {
                if ($cur_reporttype != 'SYNASTRY' && $cur_reporttype != 'STARMATCH' && $cur_reporttype != 'COMPOSITE')
                {
                    if ($displaytitles)
                    {
                        printf ("<tr>");
                        printf ("<td class=\"additionalreporttitle\">Report Description</td>");
                        printf ("<td class=\"additionalreporttitle\">Price</td>");
                        printf ("<td class=\"additionalreporttitle\" style=\"text-align:center;\">%s</td>",ucwords(strtolower($name1)));
                        if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
                            printf ("<td class=\"additionalreporttitle\" style=\"text-align:center;\">%s</td>",ucwords(strtolower($name2)));
                        printf ("</tr>");
                        $displaytitles = FALSE;
                    }
                    printf ("<tr>");
                    printf ("<td>%s</td>",$report_description);
                    printf ("<td>%s%.2lf</td>",$currencysymbol,$report_amount);
                    printf ("<td style=\"text-align:center\"><input");
                    if (isset($_POST['extrauser0']) && in_array($cur_reporttype,$_POST['extrauser0']))
                        printf (" checked");
                    if ($cur_reporttype == 'SOLAR RETURN')
                    {
                        printf (" id=\"extrasolarreturn1\" onclick=\"sr_togglereturndetails(1);\"");
                        $displaysreturn0 = TRUE;
                    }
                    elseif ($cur_reporttype == 'FORECAST')
                        printf (" id=\"extraforecastcheck1\" onclick=\"sr_toggleforecastdetails(1);\"");
                    printf (" type=\"checkbox\" name=\"extrauser0[]\" value=\"%s\"></input></td>",$cur_reporttype);
                    if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
                    {
                        printf ("<td style=\"text-align:center\"><input");
                        if (isset($_POST['extrauser1']) && in_array($cur_reporttype,$_POST['extrauser1']))
                            printf (" checked");
                        if ($cur_reporttype == 'SOLAR RETURN')
                        {
                            printf (" id=\"extrasolarreturn2\" onclick=\"sr_togglereturndetails(2);\"");
                            $displaysreturn1 = TRUE;
                        }
                        elseif ($cur_reporttype == 'FORECAST')
                            printf (" id=\"extraforecastcheck2\" onclick=\"sr_toggleforecastdetails(2);\"");
                        printf (" type=\"checkbox\" name=\"extrauser1[]\" value=\"%s\"></input></td>",$cur_reporttype);
                    }
                    printf ("</tr>");

                    if ($cur_reporttype == 'FORECAST')
                    {
                        printf ("<tr id=\"forecastdetails1\" style=\"display:none\"><td colspan=5><table class=\"entrytable\">");
                        printf ("<tr><td colspan=2 class=\"persontitle\">Add Additional Transit Reports for %s</td></tr>",ucwords(strtolower($name1)));
                        printf ("<tr><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Transits %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_forecasts_discount)/100.0),2));
                        if ($extra_forecasts_discount)
                            printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_forecasts_discount);
                        printf ("</td><td class=\"entrystandard\"><select name=\"extraforecast1\">");
                        for ($extracount = 0; $extracount < 100; $extracount++)
                            printf ("<option value=\"%d\">%d</option>",$extracount,$extracount);
                        printf ("</select></td></tr>");
                        printf ("</table></td></tr>");
                        if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
                        {
                             printf ("<tr id=\"forecastdetails2\" style=\"display:none\"><td colspan=5><table class=\"entrytable\">");
                             printf ("<tr><td colspan=2 class=\"persontitle\">Add Additional Transit Reports for %s</td></tr>",ucwords(strtolower($name2)));
                             printf ("<tr><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Transits %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_forecasts_discount)/100.0),2));
                             if ($extra_forecasts_discount)
                                 printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_forecasts_discount);
                             printf ("</td><td class=\"entrystandard\"><select name=\"extraforecast2\">");
                             for ($extracount = 0; $extracount < 100; $extracount++)
                                 printf ("<option value=\"%d\">%d</option>",$extracount,$extracount);
                             printf ("</select></td></tr>");
                             printf ("</table></td></tr>");
                        }
                    }
                    if ($cur_reporttype == 'SOLAR RETURN')
                    {
                        printf ("<tr id=\"returndetails1\" style=\"display:none\"><td colspan=5><table class=\"entrytable\">");
                        printf ("<tr id=\"firstreturnentry1\"><td colspan=2 class=\"persontitle\">Enter Solar Return Details of %s</td></tr>",ucwords(strtolower($name1)));
                        printf ("<tr id=\"firstreturnentry2\"><td class=\"entrytitle\">Use Birth Location</td>");
                        printf ("<td class=\"entrystandard\"><input style=\"padding:0px; margin:0px; border:0px;\" name=\"usebirth1\" id=\"usebirth1\" type=\"checkbox\" value=\"Y\" onclick=\"sr_checkusebirth(this,'first');\"");
                        if ((!isset( $_POST['submitted2'] ) && $usebirth1 != 'N') || $usebirth1 == 'Y')
                            printf (" checked");
                        printf ("></input></td></tr>");
                        if (!$foundmultipletownreturn1)
                        {
                        if ($failednotownreturn1)
                            printf ("<tr id=\"firstreturnentry3\"><td colspan=2 class=\"errormessage\">*Please enter a City</td></tr>");
                        elseif ($foundvaguetownreturn1)
                        {
                            printf ("<tr><td colspan=2 class=\"errormessage\">*Closest Town Listed / Please proceed to Confirm</td></tr>");
                            $townreturn1 = $townmatchreturn1[0]->town;
                        }
                        elseif ($failedzerotownreturn1)
                            printf ("<tr id=\"firstreturnentry4\"><td colspan=2 class=\"errormessage\">*Failed to find that city in the US State / Country</td></tr>");
                        printf ("<tr id=\"firstreturnentry5\"><td class=\"entrytitle\">Enter City Only</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" id=\"townreturn1\" name=\"townreturn1\" value=\"%s\"></input></td></tr>",$townreturn1);
                        if ($failednocountryreturn1)
                            printf ("<tr id=\"firstreturnentry6\"><td colspan=2 class=\"errormessage\">*Please select a US State / Country</td></tr>");
                        printf ("<tr id=\"firstreturnentry7\">");
                        printf ("<td class=\"entrytitle\">US State / Country</td>");
                        printf ("<td class=\"entrystandard\">");
                        $url = $starspath . "/listcountries.php";
          		$returnxmlstring3 = sr_stars_loadXML($url);
                        printf ("<select class=\"entryinput\" name=\"countryidreturn1\" id=\"countryidreturn1\" size=1>");
                        printf ("<option value=-1></option>");
                        $displaywalesret1 = FALSE;
                        $displayscotlandret1 = FALSE;
                        $displayenglandret1 = FALSE;
                        $displaynirelandret1 = FALSE;
                        for ($countstate = $nousstates; $countstate <$returnxmlstring3->rowsreturned; $countstate++)
                        {
                            if ((strcasecmp($returnxmlstring3->country[$countstate],"Wales") > 0) && $displaywalesret1 == FALSE)
                            {
                                printf ("<option value=%d", WALESID);
                                if ($countryidreturn1 == WALESID)
                                    printf (" selected=selected");
                                printf (">%s</option>","Wales");
                                $displaywalesret1 = TRUE;
                            }
                            if ((strcasecmp($returnxmlstring3->country[$countstate],"Scotland") > 0) && $displayscotlandret1 == FALSE)
                            {
                                printf ("<option value=%d", SCOTLANDID);
                                if ($countryidreturn1 == SCOTLANDID)
                                    printf (" selected=selected");
                                printf (">%s</option>","Scotland");
                                $displayscotlandret1 = TRUE;
                            }
                            if ((strcasecmp($returnxmlstring3->country[$countstate],"England") > 0) && $displayenglandret1 == FALSE)
                            {
                                printf ("<option value=%d", ENGLANDID);
                                if ($countryidreturn1 == ENGLANDID)
                                    printf (" selected=selected");
                                printf (">%s</option>","England");
                                $displayenglandret1 = TRUE;
                            }
                            if ((strcasecmp($returnxmlstring3->country[$countstate],"Northern Ireland") > 0) && $displaynirelandret1 == FALSE)
                            {
                                printf ("<option value=%d", NIRELANDID);
                                if ($countryidreturn1 == NIRELANDID)
                                    printf (" selected=selected");
                                printf (">%s</option>","Northern Ireland");
                                $displaynirelandret1 = TRUE;
                            }
                            printf ("<option value=%d", $countstate);
                            if ($countstate == $countryidreturn1)
                                printf (" selected=selected");
                            printf (">%s</option>",$returnxmlstring3->country[$countstate]);
                        }
                        for ($countstate = 0; $countstate <$nousstates; $countstate++)
                        {
                            printf ("<option value=%d", $countstate);
                            if ($countstate == $countryidreturn1)
                                printf (" selected=selected");
                            printf (">%s</option>",$returnxmlstring3->country[$countstate]);
                        }
                        printf ("</select>");
                        printf ("</td>");
                        printf ("</tr>");
                        }
                        else
                        {
                            printf ("<input type=\"hidden\" name=townreturn1 value=\"%s\">",$townreturn1);
                            printf ("<input type=\"hidden\" name=countryidreturn1 value=\"%d\">",$countryidreturn1);
                            printf ("<tr><td id=\"firstreturnentry8\" colspan=2 class=\"errormessage\">Multiple cities found. Please choose one.</td></tr>");
                            printf ("<tr><td id=\"firstreturnentry9\" colspan=2 class=\"entrylong\"><select class=\"entryinput\" name=\"townselectreturn1\" size=5>");
                            for ($counttown = 0; $counttown <$towncountreturn1; $counttown++)
                            {
                                $townidentifier = sprintf("%s#%s#%s#%d#%d#%d#%d", $townmatchreturn1[$counttown]->town,$townmatchreturn1[$counttown]->county,$townmatchreturn1[$counttown]->country,$townmatchreturn1[$counttown]->latitude,$townmatchreturn1[$counttown]->longitude,$townmatchreturn1[$counttown]->typetable,$townmatchreturn1[$counttown]->zonetable);
                                printf ("<option");
                                if ($townidentifier == $townselectreturn1)
                                     printf (" selected");
                                printf (" value=\"%s\">%s %s %s Lat (%.2lf) Long (%.2lf)</option>",$townidentifier,ucwords($townmatchreturn1[$counttown]->town), ucwords($townmatchreturn1[$counttown]->county), ucwords($townmatchreturn1[$counttown]->country),($townmatchreturn1[$counttown]->latitude/3600), ($townmatchreturn1[$counttown]->longitude/3600)*-1);
                            }
                            printf ("</select></td></tr></select>");
                        }
                        printf ("<tr id=\"firstreturnentry10\"><td class=\"entrytitle\">Last (Last Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" stylee=\"padding:0px; margin:0px;\" name=\"whichreturn1\" value=\"LAST\"");
                        if (!$whichreturn1 || $whichreturn1 == 'LAST')
                            printf (" checked");
                        printf ("></td>");
                        printf ("<tr id=\"firstreturnentry11\"><td class=\"entrytitle\">Next (Next Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" style=\"padding:0px; margin:0px;\" name=\"whichreturn1\" value=\"NEXT\"");
                        if ($whichreturn1 == 'NEXT')
                            printf (" checked");
                        printf ("></td></tr>");
                        printf ("<tr id=\"firstreturnentry12\"><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Solar Returns %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_solar_returns_discount)/100.0),2));
                        if ($extra_solar_returns_discount)
                            printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_solar_returns_discount);
                        printf ("</td><td class=\"entrystandard\"><select name=\"extrareturn1\">");
                        for ($extracount = 0; $extracount < 100; $extracount++)
                            printf ("<option value=\"%d\">%d</option>",$extracount,$extracount);
                        printf ("</select></td></tr>");
                        printf ("</table></td></tr>");
                        printf ("<script>");
                        printf ("sr_checkusebirth(document.getElementById('usebirth1'),'first')");
                        printf ("</script>");

                        if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
                        {
                            printf ("<tr id=\"returndetails2\" style=\"display:none\"><td colspan=5><table class=\"entrytable\">");
                            printf ("<tr id=\"secondreturnentry1\"><td colspan=2 class=\"persontitle\">Enter Solar Return Details of %s</td></tr>",ucwords(strtolower($name2)));
                            printf ("<tr id=\"secondreturnentry2\"><td class=\"entrytitle\">Use Birth Location</td>");
                            printf ("<td class=\"entrystandard\"><input style=\"padding:0px; margin:0px; border:0px;\" name=\"usebirth2\" id=\"usebirth2\" type=\"checkbox\" value=\"Y\" onclick=\"sr_checkusebirth(this,'second')\"");
                            if ((!isset( $_POST['submitted2'] ) && $usebirth2 != 'N') || $usebirth2 == 'Y')
                                printf (" checked");
                            printf ("></input></td></tr>");
                            if (!$foundmultipletownreturn2)
                            {
                            if ($failednotownreturn2)
                                printf ("<tr id=\"secondreturnentry3\"><td colspan=2 class=\"errormessage\">*Please enter a City</td></tr>");
                            elseif ($foundvaguetownreturn2)
                            {
                                printf ("<tr><td colspan=2 class=\"errormessage\">*Closest Town Listed / Please proceed to Confirm</td></tr>");
                                $townreturn2 = $townmatchreturn2[0]->town;
                            }
                            elseif ($failedzerotownreturn)
                                printf ("<tr id=\"secondreturnentry4\"><td colspan=2 class=\"errormessage\">*Failed to find that city in the US State / Country</td></tr>");
                            printf ("<tr id=\"secondreturnentry5\"><td class=\"entrytitle\">Enter City Only</td><td class=\"entrystandard\"><input type=\"text\" class=\"entryinput\" id=\"townreturn2\" name=\"townreturn2\" value=\"%s\"></input></td></tr>",$townreturn2);
                            if ($failednocountryreturn2)
                                printf ("<tr id=\"secondreturnentry6\"><td colspan=2 class=\"errormessage\">*Please select a US State / Country</td></tr>");
                            printf ("<tr id=\"secondreturnentry7\">");
                            printf ("<td class=\"entrytitle\">US State / Country</td>");
                            printf ("<td class=\"entrystandard\">");
                            $url = $starspath . "/listcountries.php";
                            $returnxmlstring3 = sr_stars_loadXML($url);
                            printf ("<select class=\"entryinput\" name=\"countryidreturn2\" id=\"countryidreturn2\" size=1>");
                            printf ("<option value=-1></option>");
                            $displaywalesret2 = FALSE;
                            $displayscotlandret2 = FALSE;
                            $displayenglandret2 = FALSE;
                            $displaynirelandret2 = FALSE;
                            for ($countstate = $nousstates; $countstate <$returnxmlstring3->rowsreturned; $countstate++)
                            {
                                if ((strcasecmp($returnxmlstring3->country[$countstate],"Wales") > 0) && $displaywalesret2 == FALSE)
                                {
                                    printf ("<option value=%d", WALESID);
                                    if ($countryidreturn2 == WALESID)
                                        printf (" selected=selected");
                                    printf (">%s</option>","Wales");
                                    $displaywalesret2 = TRUE;
                                }
                                if ((strcasecmp($returnxmlstring3->country[$countstate],"Scotland") > 0) && $displayscotlandret2 == FALSE)
                                {
                                    printf ("<option value=%d", SCOTLANDID);
                                    if ($countryidreturn2 == SCOTLANDID)
                                        printf (" selected=selected");
                                    printf (">%s</option>","Scotland");
                                    $displayscotlandret2 = TRUE;
                                }
                                if ((strcasecmp($returnxmlstring3->country[$countstate],"England") > 0) && $displayenglandret2 == FALSE)
                                {
                                    printf ("<option value=%d", ENGLANDID);
                                    if ($countryidreturn2 == ENGLANDID)
                                        printf (" selected=selected");
                                    printf (">%s</option>","England");
                                    $displayenglandret2 = TRUE;
                                }
                                if ((strcasecmp($returnxmlstring3->country[$countstate],"Northern Ireland") > 0) && $displaynirelandret2 == FALSE)
                                {
                                    printf ("<option value=%d", NIRELANDID);
                                    if ($countryidreturn2 == NIRELANDID)
                                        printf (" selected=selected");
                                    printf (">%s</option>","Northern Ireland");
                                    $displaynirelandret2 = TRUE;
                                }
                                printf ("<option value=%d", $countstate);
                                if ($countstate == $countryidreturn2)
                                    printf (" selected=selected");
                                printf (">%s</option>",$returnxmlstring3->country[$countstate]);
                            }
                            for ($countstate = 0; $countstate <$nousstates; $countstate++)
                            {
                                printf ("<option value=%d", $countstate);
                                if ($countstate == $countryidreturn2)
                                    printf (" selected=selected");
                                printf (">%s</option>",$returnxmlstring3->country[$countstate]);
                            }
                            printf ("</select>");
                            printf ("</td>");
                            printf ("</tr>");
                            }
                            else
                            {
                                printf ("<input type=\"hidden\" name=townreturn2 value=\"%s\">",$townreturn2);
                                printf ("<input type=\"hidden\" name=countryidreturn2 value=\"%d\">",$countryidreturn2);
                                printf ("<tr><td id=\"secondreturnentry8\" colspan=2 class=\"errormessage\">Multiple cities found. Please choose one.</td></tr>");
                                printf ("<tr><td id=\"secondreturnentry9\" colspan=2 class=\"entrylong\"><select class=\"entryinput\" name=\"townselectreturn2\" size=5>");
                                for ($counttown = 0; $counttown <$towncountreturn; $counttown++)
                                {
                                    $townidentifier = sprintf("%s#%s#%s#%d#%d#%d#%d", $townmatchreturn2[$counttown]->town,$townmatchreturn2[$counttown]->county,$townmatchreturn2[$counttown]->country,$townmatchreturn2[$counttown]->latitude,$townmatchreturn2[$counttown]->longitude,$townmatchreturn2[$counttown]->typetable,$townmatchreturn2[$counttown]->zonetable);
                                    printf ("<option");
                                    if ($townidentifier == $townselectreturn2)
                                         printf (" selected");
                                    printf (" value=\"%s\">%s %s %s Lat (%.2lf) Long (%.2lf)</option>",$townidentifier,ucwords($townmatchreturn2[$counttown]->town), ucwords($townmatchreturn2[$counttown]->county), ucwords($townmatchreturn2[$counttown]->country),($townmatchreturn2[$counttown]->latitude/3600), ($townmatchreturn2[$counttown]->longitude/3600)*-1);
                                }
                                printf ("</select></td></tr></select>");
                            }
                            printf ("<tr id=\"secondreturnentry10\"><td class=\"entrytitle\">Last (Last Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" stylee=\"padding:0px; margin:0px;\" name=\"whichreturn2\" value=\"LAST\"");
                            if (!$whichreturn2 || $whichreturn2 == 'LAST')
                                printf (" checked");
                            printf ("></td>");
                            printf ("<tr id=\"secondreturnentry11\"><td class=\"entrytitle\">Next (Next Birthday)</td><td class=\"entrystandard\"><input type=\"radio\" style=\"padding:0px; margin:0px;\" name=\"whichreturn2\" value=\"NEXT\"");
                            if ($whichreturn2 == 'NEXT')
                                printf (" checked");
                            printf ("></td></tr>");
                            printf ("<tr id=\"secondreturnentry12\"><td class=\"entrytitle\" style=\"white-space: nowrap\">Following years Solar Returns %s%.2lf",$currencysymbol,round((float) $report_amount * ((100.0-$extra_solar_returns_discount)/100.0),2));
                            if ($extra_solar_returns_discount)
                                printf ("<span class=\"discount\"><br>(Discounted by %d%%)</span>",$extra_solar_returns_discount);
                            printf ("</td><td class=\"entrystandard\"><select name=\"extrareturn2\">");
                            for ($extracount = 0; $extracount < 100; $extracount++)
                                printf ("<option value=\"%d\">%d</option>",$extracount,$extracount);
                            printf ("</select></td></tr>");
                            printf ("<script>");
                            printf ("sr_checkusebirth(document.getElementById('usebirth2'),'second')");
                            printf ("</script>");
                            printf ("</table></td></tr>");
                        }
                    }
                }
            }
        }
        if ($displaysreturn0 == TRUE)
            printf ("<script>togglereturndetails(1);</script>");
        if ($displaysreturn1 == TRUE)
            printf ("<script>togglereturndetails(2);</script>");
        printf ("<tr><td colspan=5 style=\"text-align:center; padding-top:25px;\">");
        $paramname = 'PAYMENT_METHOD';
        $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
        $returnxmlstring = sr_stars_loadXML($url);
        $num_payment_methods = count($returnxmlstring->paramvalue);
        for ($count = 0; $count < $num_payment_methods; $count++)
        {
            $cur_payment_method = $returnxmlstring->paramvalue[$count];
            printf ("<button type=\"submit\" name=\"submitted2\" value=\"%s\" class=\"button green medium\">Pay via %s",$cur_payment_method,ucwords(strtolower($cur_payment_method)));
        }
        printf ("</tr>");
        printf ("</table>");
        printf ("<input type=\"hidden\" name=\"reporttype\" value=\"%s\"></input>",$reporttype);
        if ($reporttype == 'SOLAR RETURN')
            printf ("<input type=\"hidden\" name=\"extrareturn1\" value=\"%s\"></input>",$extrareturn1);
        if ($reporttype == 'LUNAR RETURN')
            printf ("<input type=\"hidden\" name=\"extralreturn1\" value=\"%s\"></input>",$extralreturn1);
        if ($reporttype == 'FORECAST')
            printf ("<input type=\"hidden\" name=\"extraforecast1\" value=\"%s\"></input>",$extraforecast1);

        printf ("<input type=\"hidden\" name=\"dob1\" value=\"%s\"></input>",$dob1);
        printf ("<input type=\"hidden\" name=\"time1\" value=\"%s\"></input>",$time1);
        if ($unknowntime1 == 'Y')
            printf ("<input type=\"hidden\" name=\"unknowntime1\" value=\"%s\"></input>",$unknowntime1);
        printf ("<input type=\"hidden\" name=\"name1\" value=\"%s\"></input>",$name1);
        printf ("<input type=\"hidden\" name=\"gender1\" value=\"%s\"></input>",$gender1);
        if ($townselect1)
            printf ("<input type=\"hidden\" name=\"townselect1\" value=\"%s\"></input>",$townselect1);
        else
            printf ("<input type=\"hidden\" name=\"townselect1\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatch1[0]->town,$townmatch1[0]->county,$townmatch1[0]->country,$townmatch1[0]->latitude,$townmatch1[0]->longitude,$townmatch1[0]->typetable,$townmatch1[0]->zonetable);
        printf ("<input type=\"hidden\" name=\"countryid1\" value=\"%s\"></input>",$countryid1);
        if ($reporttype == 'SOLAR RETURN')
        {
            printf ("<input type=\"hidden\" name=\"usebirth1\" value=\"%s\"></input>",$usebirth1);
            printf ("<input type=\"hidden\" name=\"whichreturn1\" value=\"%s\"></input>",$whichreturn1);
            printf ("<input type=\"hidden\" name=\"countryidreturn1\" value=\"%s\"></input>",$countryidreturn1);
        }
        if ($reporttype == 'LUNAR RETURN')
        {
            printf ("<input type=\"hidden\" name=\"lusebirth1\" value=\"%s\"></input>",$lusebirth1);
            printf ("<input type=\"hidden\" name=\"whichlreturn1\" value=\"%s\"></input>",$whichlreturn1);
            printf ("<input type=\"hidden\" name=\"countryidlreturn1\" value=\"%s\"></input>",$countryidlreturn1);
        }
        printf ("<input type=\"hidden\" name=\"dob2\" value=\"%s\"></input>",$dob2);
        printf ("<input type=\"hidden\" name=\"time2\" value=\"%s\"></input>",$time2);
        if ($unknowntime2 == 'Y')
            printf ("<input type=\"hidden\" name=\"unknowntime2\" value=\"%s\"></input>",$unknowntime2);
        printf ("<input type=\"hidden\" name=\"name2\" value=\"%s\"></input>",$name2);
        printf ("<input type=\"hidden\" name=\"gender2\" value=\"%s\"></input>",$gender2);
        if ($townselect2)
            printf ("<input type=\"hidden\" name=\"townselect2\" value=\"%s\"></input>",$townselect2);
        else
            printf ("<input type=\"hidden\" name=\"townselect2\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatch2[0]->town,$townmatch2[0]->county,$townmatch2[0]->country,$townmatch2[0]->latitude,$townmatch2[0]->longitude,$townmatch2[0]->typetable,$townmatch2[0]->zonetable);
        printf ("<input type=\"hidden\" name=\"countryid2\" value=\"%s\"></input>",$countryid2);
        if ($reporttype == 'SOLAR RETURN')
        {
            if ($townselectreturn1)
                printf ("<input type=\"hidden\" name=\"townselectreturn1\" value=\"%s\"></input>",$townselectreturn1);
            else
                printf ("<input type=\"hidden\" name=\"townselectreturn1\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchreturn1[0]->town,$townmatchreturn1[0]->county,$townmatchreturn1[0]->country,$townmatchreturn1[0]->latitude,$townmatchreturn1[0]->longitude,$townmatchreturn1[0]->typetable,$townmatchreturn1[0]->zonetable);
        }
        if ($reporttype == 'LUNAR  RETURN')
        {
            if ($townselectlreturn1)
                printf ("<input type=\"hidden\" name=\"townselectlreturn1\" value=\"%s\"></input>",$townselectlreturn1);
            else
                printf ("<input type=\"hidden\" name=\"townselectlreturn1\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchlreturn1[0]->town,$townmatchlreturn1[0]->county,$townmatchlreturn1[0]->country,$townmatchlreturn1[0]->latitude,$townmatchlreturn1[0]->longitude,$townmatchlreturn1[0]->typetable,$townmatchlreturn1[0]->zonetable);
        }
        if (isset( $_POST['submitted2']) && $reporttype != 'SOLAR RETURN')
        {
            if ($townselectreturn1)
                printf ("<input type=\"hidden\" name=\"townselectreturn1\" value=\"%s\"></input>",$townselectreturn1);
            else
                printf ("<input type=\"hidden\" name=\"townselectreturn1\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchreturn1[0]->town,$townmatchreturn1[0]->county,$townmatchreturn1[0]->country,$townmatchreturn1[0]->latitude,$townmatchreturn1[0]->longitude,$townmatchreturn1[0]->typetable,$townmatchreturn1[0]->zonetable);
            if ($townselectreturn2)
                printf ("<input type=\"hidden\" name=\"townselectreturn2\" value=\"%s\"></input>",$townselectreturn2);
            else
                printf ("<input type=\"hidden\" name=\"townselectreturn2\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchreturn2[0]->town,$townmatchreturn2[0]->county,$townmatchreturn2[0]->country,$townmatchreturn2[0]->latitude,$townmatchreturn2[0]->longitude,$townmatchreturn2[0]->typetable,$townmatchreturn2[0]->zonetable);
        }
        if ($reporttype == 'LUNAR RETURN')
        {
            if ($townselectlreturn1)
                printf ("<input type=\"hidden\" name=\"townselectlreturn1\" value=\"%s\"></input>",$townselectlreturn1);
            else
                printf ("<input type=\"hidden\" name=\"townselectlreturn1\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchlreturn1[0]->town,$townmatchlreturn1[0]->county,$townmatchlreturn1[0]->country,$townmatchlreturn1[0]->latitude,$townmatchlreturn1[0]->longitude,$townmatchlreturn1[0]->typetable,$townmatchlreturn1[0]->zonetable);
        }
        if (isset( $_POST['submitted2']) && $reporttype != 'LUNAR RETURN')
        {
            if ($townselectlreturn1)
                printf ("<input type=\"hidden\" name=\"townselectlreturn1\" value=\"%s\"></input>",$townselectlreturn1);
            else
                printf ("<input type=\"hidden\" name=\"townselectlreturn1\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchlreturn1[0]->town,$townmatchlreturn1[0]->county,$townmatchlreturn1[0]->country,$townmatchlreturn1[0]->latitude,$townmatchlreturn1[0]->longitude,$townmatchlreturn1[0]->typetable,$townmatchlreturn1[0]->zonetable);
            if ($townselectlreturn2)
                printf ("<input type=\"hidden\" name=\"townselectlreturn2\" value=\"%s\"></input>",$townselectlreturn2);
            else
                printf ("<input type=\"hidden\" name=\"townselectlreturn2\" value=\"%s#%s#%s#%d#%d#%d#%d\"></input>",$townmatchlreturn2[0]->town,$townmatchlreturn2[0]->county,$townmatchlreturn2[0]->country,$townmatchlreturn2[0]->latitude,$townmatchlreturn2[0]->longitude,$townmatchlreturn2[0]->typetable,$townmatchlreturn2[0]->zonetable);
        }
        printf ("<input type=\"hidden\" name=\"email\" value=\"%s\"></input>",$email);

    }
    printf ("</form>");
}
function sr_validate_page()
{
    global $townmatch1;
    global $towncount1;
    global $failednoname1;
    global $failednogender1;
    global $failednotown1;
    global $failednocountry1;
    global $failednodob1;
    global $failednotime1;
    global $failedzerotown1;
    global $foundmultipletown1;
    global $failedmultipletown1;
    global $foundvaguetown1;
    global $townmatch2;
    global $towncount2;
    global $failednoname2;
    global $failednogender2;
    global $failednotown2;
    global $failednocountry2;
    global $failednodob2;
    global $failednotime2;
    global $failedzerotown2;
    global $foundmultipletown2;
    global $failedmultipletown2;
    global $foundvaguetown2;
    global $townmatchreturn1;
    global $towncountreturn1;
    global $failednotownreturn1;
    global $failednocountryreturn1;
    global $failedzerotownreturn1;
    global $foundmultipletownreturn1;
    global $failedmultipletownreturn1;
    global $foundvaguetownreturn1;
    global $townmatchreturn2;
    global $towncountreturn2;
    global $failednotownreturn2;
    global $failednocountryreturn2;
    global $failedzerotownreturn2;
    global $foundmultipletownreturn2;
    global $failedmultipletownreturn2;
    global $foundvaguetownreturn2;
    global $townmatchlreturn1;
    global $towncountlreturn1;
    global $failednotownlreturn1;
    global $failednocountrylreturn1;
    global $failedzerotownlreturn1;
    global $foundmultipletownlreturn1;
    global $failedmultipletownlreturn1;
    global $foundvaguetownlreturn1;
    global $failednoemail;
    global $starspath;
    global $nextpage;

    $retstatus = 1;
    $lastpage = $_POST['lastpage'];
    $reporttype = $_POST['reporttype'];
    $dob1 = $_POST['dob1'];
    $time1 = $_POST['time1'];
    $unknowntime1 = $_POST['unknowntime1'];
    $name1 = $_POST['name1'];
    $gender1 = $_POST['gender1'];
    $town1 =  $_POST["town1"];
    $countryid1 = $_POST['countryid1'];
    $townselect1 = $_POST['townselect1'];
    $dob2 = $_POST['dob2'];
    $time2 = $_POST['time2'];
    $unknowntime2 = $_POST['unknowntime2'];
    $name2 = $_POST['name2'];
    $gender2 = $_POST['gender2'];
    $town2 =  $_POST["town2"];
    $countryid2 = $_POST['countryid2'];
    $townselect2 = $_POST['townselect2'];
    $email = $_POST['email'];
    $usebirth1 = $_POST['usebirth1'];
    if (!$usebirth1)
        $usebirth1 = 'N';
    $whichreturn1 = $_POST['whichreturn1'];
    $townreturn1 =  $_POST["townreturn1"];
    $countryidreturn1 = $_POST['countryidreturn1'];
    $townselectreturn1 = $_POST['townselectreturn1'];
    $usebirth2 = $_POST['usebirth2'];
    if (!$usebirth2)
        $usebirth2 = 'N';
    $whichreturn2 = $_POST['whichreturn2'];
    $townreturn2 =  $_POST["townreturn2"];
    $countryidreturn2 = $_POST['countryidreturn2'];
    $townselectreturn2 = $_POST['townselectreturn2'];
    $lusebirth1 = $_POST['lusebirth1'];
    if (!$lusebirth1)
        $lusebirth1 = 'N';
    $whichlreturn1 = $_POST['whichlreturn1'];
    $townlreturn1 =  $_POST["townlreturn1"];
    $countryidlreturn1 = $_POST['countryidlreturn1'];
    $townselectlreturn1 = $_POST['townselectlreturn1'];

    if (isset($unknowntime1))
       $unknowntime1 = 'Y';
    else
        $unknowntime1 = 'N';
    if (isset($unknowntime2))
       $unknowntime2 = 'Y';
    else
        $unknowntime2 = 'N';

    if ($lastpage == 1)
    {

        if (!$name1)
            $failednoname1 = TRUE;
        if (!$gender1)
            $failednogender1 = TRUE;
        if (!$town1)
            $failednotown1 = TRUE;
        if ($countryid1 < 0)
            $failednocountry1 = TRUE;
        if (!$dob1)
            $failednodob1 = TRUE;
        if (!$time1 && $unknowntime1 != 'Y')
            $failednotime1 = TRUE;
        if (!$email)
            $failednoemail = TRUE;
        $searchcountryid1 = $countryid1;
    
        if (!$failednotown1 && !$failednocountry1)
        {
            $url = $starspath . "/listcountries.php";
    		$returnxmlstring = sr_stars_loadXML($url);
            for ($count = 0; $count <$returnxmlstring->rowsreturned; $count++)
            {
                if (!strcasecmp($returnxmlstring->country[$count],"United Kingdom"))
                {
                    $ukid = $count;
                    break;
                }
            }
            if ($countryid1 == WALESID || $countryid1 == SCOTLANDID || $countryid1 == ENGLANDID || $countryid1 == NIRELANDID)
                $searchcountryid1 = $ukid;
            else
                $searchcountryid1 = $countryid1;
    
            $url = sprintf("%s/listtowns.php?countryid=%d&town=%s",$starspath,$searchcountryid1,urlencode($town1));
    		$returnxmlstring = sr_stars_loadXML($url);
            $towncount1 = $returnxmlstring->rowsreturned;
            for ($count = 0; $count < $towncount1; $count++)
            {
    
                if ($countryid1 == WALESID)
                    $country1 = 'Wales';
                elseif ($countryid1 == SCOTLANDID)
                    $country1 = 'Scotland';
                elseif ($countryid1 == ENGLANDID)
                    $country1 = 'England';
                elseif ($countryid1 == NIRELANDID)
                    $country1 = 'Northern Ireland';
                else
                    $country1 = $returnxmlstring->country[$count];
    
                $townmatch1[$count] = new StdClass;
                $townmatch1[$count]->town=$returnxmlstring->town[$count];
                $townmatch1[$count]->county=$returnxmlstring->county[$count];
                $townmatch1[$count]->country=$country1;
                $townmatch1[$count]->latitude=$returnxmlstring->latitude[$count];
                $townmatch1[$count]->longitude=$returnxmlstring->longitude[$count];
                $townmatch1[$count]->typetable=$returnxmlstring->typetable[$count];
                $townmatch1[$count]->zonetable=$returnxmlstring->zonetable[$count];
                $townmatch1[$count]->vague=$returnxmlstring->vague[$count];
    
            }
            if ($towncount1 == 0)
            {
                $failedzerotown1 = true;
            }
            else if ($townmatch1[0]->vague == 'TRUE')
            {
                $foundvaguetown1 = TRUE;
            }
            else if ($towncount1 > 1)
            {
                $foundmultipletown1 = true;
                if (!$townselect1)
                    $failedmultipletown1 = TRUE;
            }
        }
        if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
        {
            if (!$dob2)
                $failednodob2 = TRUE;
            if (!$time2 && $unknowntime2 != 'Y')
                $failednotime2 = TRUE;
            if (!$name2)
                $failednoname2 = TRUE;
            if (!$gender2)
                $failednogender2 = TRUE;
            if (!$town2)
                $failednotown2 = TRUE;
            if ($countryid2 < 0)
                $failednocountry2 = TRUE;
            if (!$failednotown2 && !$failednocountry2)
            {
                if ($countryid2 == WALESID || $countryid2 == SCOTLANDID || $countryid2 == ENGLANDID || $countryid2 == NIRELANDID)
                    $searchcountryid2 = $ukid;
                else
                    $searchcountryid2 = $countryid2;
    
                $url = sprintf("%s/listtowns.php?countryid=%d&town=%s",$starspath,$searchcountryid2,urlencode($town2));
    		    $returnxmlstring = sr_stars_loadXML($url);
                $towncount2 = $returnxmlstring->rowsreturned;
                for ($count = 0; $count < $towncount2; $count++)
                {
    
                    if ($countryid2 == WALESID)
                        $country2 = 'Wales';
                    elseif ($countryid2 == SCOTLANDID)
                        $country2 = 'Scotland';
                    elseif ($countryid2 == ENGLANDID)
                        $country2 = 'England';
                    elseif ($countryid2 == NIRELANDID)
                        $country2 = 'Northern Ireland';
                    else
                        $country2 = $returnxmlstring->country[$count];
    
                    $townmatch2[$count] = new StdClass;
                    $townmatch2[$count]->town=$returnxmlstring->town[$count];
                    $townmatch2[$count]->county=$returnxmlstring->county[$count];
                    $townmatch2[$count]->country=$country2;
                    $townmatch2[$count]->latitude=$returnxmlstring->latitude[$count];
                    $townmatch2[$count]->longitude=$returnxmlstring->longitude[$count];
                    $townmatch2[$count]->typetable=$returnxmlstring->typetable[$count];
                    $townmatch2[$count]->zonetable=$returnxmlstring->zonetable[$count];
                    $townmatch2[$count]->vague=$returnxmlstring->vague[$count];
    
                }
                if ($towncount2 == 0)
                {
                    $failedzerotown2 = true;
                }
                else if ($townmatch2[0]->vague == 'TRUE')
                {
                    $foundvaguetown2 = TRUE;
                }
                else if ($towncount2 > 1)
                {
                    $foundmultipletown2 = true;
                    if (!$townselect2)
                        $failedmultipletown2 = TRUE;
                }
            }
        }
        elseif ($reporttype == 'SOLAR RETURN')
        {
            if ($usebirth1 == 'N')
            {
                if (!$townreturn1)
                    $failednotownreturn1 = TRUE;
                if ($countryidreturn1 < 0)
                    $failednocountryreturn1 = TRUE;
                if (!$failednotownreturn1 && !$failednocountryreturn1)
                {
                    if ($countryidreturn1 == WALESID || $countryidreturn1 == SCOTLANDID || $countryidreturn1 == ENGLANDID || $countryidreturn1 == NIRELANDID)
                        $searchcountryidreturn1 = $ukid;
                    else
                        $searchcountryidreturn1 = $countryidreturn1;
        
                    $url = sprintf("%s/listtowns.php?countryid=%d&town=%s",$starspath,$searchcountryidreturn1,urlencode($townreturn1));
        		    $returnxmlstring = sr_stars_loadXML($url);
                    $towncountreturn1 = $returnxmlstring->rowsreturned;
                    for ($count = 0; $count < $towncountreturn1; $count++)
                    {
    
                        if ($countryidreturn1 == WALESID)
                            $countryreturn1 = 'Wales';
                        elseif ($countryidreturn1 == SCOTLANDID)
                            $countryreturn1 = 'Scotland';
                        elseif ($countryidreturn1 == ENGLANDID)
                            $countryreturn1 = 'England';
                        elseif ($countryidreturn1 == NIRELANDID)
                            $countryreturn1 = 'Northern Ireland';
                        else
                            $countryreturn1 = $returnxmlstring->country[$count];
    
                        $townmatchreturn1[$count]->town=$returnxmlstring->town[$count];
                        $townmatchreturn1[$count]->county=$returnxmlstring->county[$count];
                        $townmatchreturn1[$count]->country=$countryreturn1;
                        $townmatchreturn1[$count]->latitude=$returnxmlstring->latitude[$count];
                        $townmatchreturn1[$count]->longitude=$returnxmlstring->longitude[$count];
                        $townmatchreturn1[$count]->typetable=$returnxmlstring->typetable[$count];
                        $townmatchreturn1[$count]->zonetable=$returnxmlstring->zonetable[$count];
                        $townmatchreturn1[$count]->vague=$returnxmlstring->vague[$count];
    
                    }
                    if ($towncountreturn1 == 0)
                    {
                        $failedzerotownreturn1 = true;
                    }
                    else if ($townmatchreturn1[0]->vague == 'TRUE')
                    {
                        $foundvaguetownreturn1 = TRUE;
                    }
                    else if ($towncountreturn1 > 1)
                    {
                        $foundmultipletownreturn1 = true;
                        if (!$townselectreturn1)
                            $failedmultipletownreturn1 = TRUE;
                    }
                }
            }
        }
        elseif ($reporttype == 'LUNAR RETURN')
        {
            if ($lusebirth1 == 'N')
            {
                if (!$townlreturn1)
                    $failednotownlreturn1 = TRUE;
                if ($countryidlreturn1 < 0)
                    $failednocountrylreturn1 = TRUE;
                if (!$failednotownlreturn1 && !$failednocountrylreturn1)
                {
                    if ($countryidlreturn1 == WALESID || $countryidlreturn1 == SCOTLANDID || $countryidlreturn1 == ENGLANDID || $countryidlreturn1 == NIRELANDID)
                        $searchcountryidlreturn1 = $ukid;
                    else
                        $searchcountryidlreturn1 = $countryidlreturn1;
        
                    $url = sprintf("%s/listtowns.php?countryid=%d&town=%s",$starspath,$searchcountryidlreturn1,urlencode($townlreturn1));
        		    $returnxmlstring = sr_stars_loadXML($url);
                    $towncountlreturn1 = $returnxmlstring->rowsreturned;
                    for ($count = 0; $count < $towncountlreturn1; $count++)
                    {
    
                        if ($countryidlreturn1 == WALESID)
                            $countrylreturn1 = 'Wales';
                        elseif ($countryidlreturn1 == SCOTLANDID)
                            $countrylreturn1 = 'Scotland';
                        elseif ($countryidlreturn1 == ENGLANDID)
                            $countrylreturn1 = 'England';
                        elseif ($countryidlreturn1 == NIRELANDID)
                            $countrylreturn1 = 'Northern Ireland';
                        else
                            $countrylreturn1 = $returnxmlstring->country[$count];
    
                        $townmatchlreturn1[$count]->town=$returnxmlstring->town[$count];
                        $townmatchlreturn1[$count]->county=$returnxmlstring->county[$count];
                        $townmatchlreturn1[$count]->country=$countrylreturn1;
                        $townmatchlreturn1[$count]->latitude=$returnxmlstring->latitude[$count];
                        $townmatchlreturn1[$count]->longitude=$returnxmlstring->longitude[$count];
                        $townmatchlreturn1[$count]->typetable=$returnxmlstring->typetable[$count];
                        $townmatchlreturn1[$count]->zonetable=$returnxmlstring->zonetable[$count];
                        $townmatchlreturn1[$count]->vague=$returnxmlstring->vague[$count];
    
                    }
                    if ($towncountlreturn1 == 0)
                    {
                        $failedzerotownlreturn1 = true;
                    }
                    else if ($townmatchlreturn1[0]->vague == 'TRUE')
                    {
                        $foundvaguetownlreturn1 = TRUE;
                    }
                    else if ($towncountlreturn1 > 1)
                    {
                        $foundmultipletownlreturn1 = true;
                        if (!$townselectlreturn1)
                            $failedmultipletownlreturn1 = TRUE;
                    }
                }
            }
        }
        if (!$email)
            $failednoemail = TRUE;
        if ($failednodob1 || $failednotime1 || $failednoname1 || $failednogender1 || $failednotown1 || $failednocountry1 || $failedzerotown1 || $failedmultipletown1 || $foundvaguetown1 || $failednodob2 || $failednotime2 || $failednoname2 || $failednogender2 || $failednotown2 || $failednocountry2 || $failedzerotown2 || $failedmultipletown2 || $foundvaguetown2 || $failednotownreturn1 || $failednocountryreturn1 || $failedzerotownreturn1 || $failedmultipletownreturn1 || $foundvaguetownreturn1 || $failednotownlreturn1 || $failednocountrylreturn1 || $failedzerotownlreturn1 || $failedmultipletownlreturn1 || $foundvaguetownlreturn1 || $failednoemail)
            $retstatus = 0;
    }
    elseif ($lastpage == 2)
    {
        if(!empty($_POST['extrauser0']))
        {
            foreach($_POST['extrauser0'] as $check)
            {
                if ($check == 'SOLAR RETURN')
                    $extrasreturn0 = TRUE;
            }
        }
        if ($extrasreturn0 && $usebirth1 == 'N')
        {
            if (!$townreturn1)
                $failednotownreturn1 = TRUE;
            if ($countryidreturn1 < 0)
                $failednocountryreturn1 = TRUE;
            if (!$failednotownreturn1 && !$failednocountryreturn1)
            {
                if ($countryidreturn1 == WALESID || $countryidreturn1 == SCOTLANDID || $countryidreturn1 == ENGLANDID || $countryidreturn1 == NIRELANDID)
                    $searchcountryidreturn1 = $ukid;
                else
                    $searchcountryidreturn1 = $countryidreturn1;
    
                $url = sprintf("%s/listtowns.php?countryid=%d&town=%s",$starspath,$searchcountryidreturn1,urlencode($townreturn1));
    		    $returnxmlstring = sr_stars_loadXML($url);
                $towncountreturn1 = $returnxmlstring->rowsreturned;
                for ($count = 0; $count < $towncountreturn1; $count++)
                {

                    if ($countryidreturn1 == WALESID)
                        $countryreturn1 = 'Wales';
                    elseif ($countryidreturn1 == SCOTLANDID)
                        $countryreturn1 = 'Scotland';
                    elseif ($countryidreturn1 == ENGLANDID)
                        $countryreturn1 = 'England';
                    elseif ($countryidreturn1 == NIRELANDID)
                        $countryreturn1 = 'Northern Ireland';
                    else
                        $countryreturn1 = $returnxmlstring->country[$count];

                    $townmatchreturn1[$count]->town=$returnxmlstring->town[$count];
                    $townmatchreturn1[$count]->county=$returnxmlstring->county[$count];
                    $townmatchreturn1[$count]->country=$countryreturn1;
                    $townmatchreturn1[$count]->latitude=$returnxmlstring->latitude[$count];
                    $townmatchreturn1[$count]->longitude=$returnxmlstring->longitude[$count];
                    $townmatchreturn1[$count]->typetable=$returnxmlstring->typetable[$count];
                    $townmatchreturn1[$count]->zonetable=$returnxmlstring->zonetable[$count];
                    $townmatchreturn1[$count]->vague=$returnxmlstring->vague[$count];

                }
                if ($towncountreturn1 == 0)
                {
                    $failedzerotownreturn1 = true;
                }
                else if ($townmatchreturn1[0]->vague == 'TRUE')
                {
                    $foundvaguetownreturn1 = TRUE;
                }
                else if ($towncountreturn1 > 1)
                {
                    $foundmultipletownreturn1 = true;
                    if (!$townselectreturn1)
                        $failedmultipletownreturn1 = TRUE;
                }
            }
        }
        if(!empty($_POST['extrauser1']))
        {
            foreach($_POST['extrauser1'] as $check)
            {
                if ($check == 'SOLAR RETURN')
                    $extrasreturn1 = TRUE;
            }
        }
        if ($extrasreturn1 && $usebirth2 == 'N')
        {
            if (!$townreturn2)
                $failednotownreturn2 = TRUE;
            if ($countryidreturn2 < 0)
                $failednocountryreturn2 = TRUE;
            if (!$failednotownreturn2 && !$failednocountryreturn2)
            {
                if ($countryidreturn2 == WALESID || $countryidreturn2 == SCOTLANDID || $countryidreturn2 == ENGLANDID || $countryidreturn2 == NIRELANDID)
                    $searchcountryidreturn2 = $ukid;
                else
                    $searchcountryidreturn2 = $countryidreturn2;
    
                $url = sprintf("%s/listtowns.php?countryid=%d&town=%s",$starspath,$searchcountryidreturn2,urlencode($townreturn2));
    		    $returnxmlstring = sr_stars_loadXML($url);
                $towncountreturn2 = $returnxmlstring->rowsreturned;
                for ($count = 0; $count < $towncountreturn2; $count++)
                {

                    if ($countryidreturn2 == WALESID)
                        $countryreturn2 = 'Wales';
                    elseif ($countryidreturn2 == SCOTLANDID)
                        $countryreturn2 = 'Scotland';
                    elseif ($countryidreturn2 == ENGLANDID)
                        $countryreturn2 = 'England';
                    elseif ($countryidreturn2 == NIRELANDID)
                        $countryreturn2 = 'Northern Ireland';
                    else
                        $countryreturn2 = $returnxmlstring->country[$count];

                    $townmatchreturn2[$count]->town=$returnxmlstring->town[$count];
                    $townmatchreturn2[$count]->county=$returnxmlstring->county[$count];
                    $townmatchreturn2[$count]->country=$countryreturn2;
                    $townmatchreturn2[$count]->latitude=$returnxmlstring->latitude[$count];
                    $townmatchreturn2[$count]->longitude=$returnxmlstring->longitude[$count];
                    $townmatchreturn2[$count]->typetable=$returnxmlstring->typetable[$count];
                    $townmatchreturn2[$count]->zonetable=$returnxmlstring->zonetable[$count];
                    $townmatchreturn2[$count]->vague=$returnxmlstring->vague[$count];

                }
                if ($towncountreturn2 == 0)
                {
                    $failedzerotownreturn2 = true;
                }
                else if ($townmatchreturn2[0]->vague == 'TRUE')
                {
                    $foundvaguetownreturn2 = TRUE;
                }
                else if ($towncountreturn2 > 1)
                {
                    $foundmultipletownreturn2 = true;
                    if (!$townselectreturn2)
                        $failedmultipletownreturn2 = TRUE;
                }
            }
        }
        if ($failednotownreturn1 || $failednocountryreturn1 || $failedzerotownreturn1 || $failedmultipletownreturn1 || $foundvaguetownreturn1 || $failednotownreturn2 || $failednocountryreturn2 || $failedzerotownreturn2 || $failedmultipletownreturn2 || $foundvaguetownreturn2)
            $retstatus = 0;
    }
    return ($retstatus);

}
 
function sr_stars_loadXML($url)
{
    if (ini_get('allow_url_fopen') == true)
    {
         return sr_stars_load_xml_fopen($url);
    } 
    else if (function_exists('curl_init'))
    {
         return sr_stars_load_xml_curl($url);
    }
    else
    {
         throw new Exception("Can't load data.");
    }
}
 
function sr_stars_load_xml_fopen($url)
{
    return simplexml_load_file($url);
}
 
function sr_stars_load_xml_curl($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return simplexml_load_string($result);
}

function sr_stars_loadContents($url)
{
    if (ini_get('allow_url_fopen') == true)
    {
         return sr_stars_load_contents_get($url);
    }
    else if (function_exists('curl_init'))
    {
         return sr_stars_load_contents_curl($url);
    }
    else
    {
         throw new Exception("Can't xload data.");
    }
}
 
function sr_stars_load_contents_get($url)
{
    return file_get_contents($url);
}
 
function sr_stars_load_contents_curl($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
function sr_initialise() 
{
    if (IMPLEMENTATION == "WORDPRESS")
        ob_start();
    sr_display_input_form();
    if (IMPLEMENTATION == "WORDPRESS")
        return ob_get_clean();
}
if (IMPLEMENTATION == "WORDPRESS")
    add_action( 'init', 'sr_process_report' );
if (IMPLEMENTATION == "STANDARD")
    sr_process_report();

function sr_process_report()
{
    global $starspath;
    global $enginepath;
    global $enginename;
      
    global $townmatch1;
    global $townmatch2;
    global $townmatchreturn1;
    global $townmatchreturn2;
    global $apikey;
    global $nextpage;
    $lastpage = $_POST['lastpage'];
    $monthnames = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    if ( isset( $_POST['submitted1'] ) ||  isset( $_POST['submitted2']) ) 
    {
        if ($lastpage == 1)
        {

            $unknowntime1 = $_POST['unknowntime1'];
            if (isset($unknowntime1))
                $unknowntime1 = 'Y';
            else
                $unknowntime1 = 'N';

            $result = sr_validate_page();
            if ($result)
            {
                $paramname = 'REPORT_TYPE';
                $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
                $returnxmlstring = sr_stars_loadXML($url);
                $num_reports = count($returnxmlstring->paramvalue);
                for ($count = 0; $count < $num_reports; $count++)
                {
                    $cur_reporttype = $returnxmlstring->paramvalue[$count];
                    if ($cur_reporttype != 'CHILD' && $cur_reporttype != 'ADULT')
                    {
                        if ($unknowntime1 != 'Y')
                            $available_reports++;
                    }
                    else
                        $available_reports++;
                }

                if ($available_reports > 1)
                    $nextpage = 2;
                else
                    $proceedtopayment = TRUE;
            }
            else
                $nextpage = 1;
        }
        elseif ($lastpage == 2)
        {
            $result = sr_validate_page();
            if ($result)
                $proceedtopayment = TRUE;
            else
                $nextpage == 2;
        }
         
        if ($proceedtopayment)
        {

            $reporttype = $_POST['reporttype'];
            $dob1 = $_POST['dob1'];
            $time1 = $_POST['time1'];
            $unknowntime1 = $_POST['unknowntime1'];
            $name1 = $_POST['name1'];
            $gender1 = $_POST['gender1'];
            $town1 =  $_POST["town1"];
            $countryid1 = $_POST['countryid1'];
            $townselect1 = $_POST['townselect1'];
            $extraforecast1 = $_POST['extraforecast1'];
            $dob2 = $_POST['dob2'];
            $time2 = $_POST['time2'];
            $unknowntime2 = $_POST['unknowntime2'];
            $name2 = $_POST['name2'];
            $gender2 = $_POST['gender2'];
            $town2 =  $_POST["town2"];
            $countryid2 = $_POST['countryid2'];
            $townselect2 = $_POST['townselect2'];
            $extraforecast2 = $_POST['extraforecast2'];
            $email = $_POST['email'];
            $usebirth1 = $_POST['usebirth1'];
            if (!$usebirth1)
                $usebirth1 = 'N';
            $whichreturn1 = $_POST['whichreturn1'];
            $extrareturn1 = $_POST['extrareturn1'];
            $townreturn1 =  $_POST["townreturn1"];
            $countryidreturn1 = $_POST['countryidreturn1'];
            $townselectreturn1 = $_POST['townselectreturn1'];
            $usebirth2 = $_POST['usebirth2'];
            if (!$usebirth2)
                $usebirth2 = 'N';
            $whichreturn2 = $_POST['whichreturn2'];
            $extrareturn2 = $_POST['extrareturn2'];
            $townreturn2 =  $_POST["townreturn2"];
            $countryidreturn2 = $_POST['countryidreturn2'];
            $townselectreturn2 = $_POST['townselectreturn2'];
            $lusebirth1 = $_POST['lusebirth1'];
            if (!$lusebirth1)
                $lusebirth1 = 'N';
            $whichlreturn1 = $_POST['whichlreturn1'];
            $extralreturn1 = $_POST['extralreturn1'];
            $townlreturn1 =  $_POST["townlreturn1"];
            $countryidlreturn1 = $_POST['countryidlreturn1'];
            $townselectlreturn1 = $_POST['townselectlreturn1'];

            if (isset($unknowntime1))
               $unknowntime1 = 'Y';
            else
                $unknowntime1 = 'N';
            if (isset($unknowntime2))
               $unknowntime2 = 'Y';
            else
                $unknowntime2 = 'N';
        
            if ($townselect1)
            {
                $splittownselect1 = explode("#",$townselect1);
                $town1 = $splittownselect1[0];
                $county1 = $splittownselect1[1];
                $country1 = $splittownselect1[2];
                $latitude1 = $splittownselect1[3];
                $longitude1 = $splittownselect1[4];
                $typetable1 = $splittownselect1[5];
                $zonetable1 = $splittownselect1[6];
            }
            else
            {
                $town1 = $townmatch1[0]->town;
                $county1= $townmatch1[0]->county;
                $country1= $townmatch1[0]->country;
                $latitude1 = $townmatch1[0]->latitude;
                $longitude1 = $townmatch1[0]->longitude;
                $typetable1 = $townmatch1[0]->typetable;
                $zonetable1 = $townmatch1[0]->zonetable;
            }
	    $latitude1 = $latitude1 / 3600;
            $longitude1 = ($longitude1 / 3600) * -1;

            /*
                First Persons details
            */

            $_SESSION['session_dob1'] = $dob1;
            list($day1, $monthname1, $year1, $bc1) = sscanf($dob1, "%d %s %d %s");
            $month1 = array_search($monthname1, $monthnames) + 1;
            $dob1 = sprintf("%02d-%02d-%d %s",$day1,$month1,$year1,$bc1);
            $_SESSION['session_email'] = $email;
            $_SESSION['session_name1'] = $name1;
            $_SESSION['session_gender1'] = $gender1;
            $_SESSION['session_time1'] = $time1;
            $_SESSION['session_unknowntime1'] = $unknowntime1;
            $_SESSION['session_town1'] = (string) $town1;
            $_SESSION['session_countryid1'] = $countryid1;

            if ($reporttype == 'SOLAR RETURN' && $usebirth1 == 'N')
            {
                if ($townselectreturn1)
                {
                    $splittownselectreturn1 = explode("#",$townselectreturn1);
                    $townreturn1 = $splittownselectreturn1[0];
                    $countyreturn1 = $splittownselectreturn1[1];
                    $countryreturn1 = $splittownselectreturn1[2];
                    $latitudereturn1 = $splittownselectreturn1[3];
                    $longitudereturn1 = $splittownselectreturn1[4];
                    $typetablereturn1 = $splittownselectreturn1[5];
                    $zonetablereturn1 = $splittownselectreturn1[6];
                }
                else
                {
                    $townreturn1 = $townmatchreturn1[0]->town;
                    $countyreturn1= $townmatchreturn1[0]->county;
                    $countryreturn1= $townmatchreturn1[0]->country;
                    $latitudereturn1 = $townmatchreturn1[0]->latitude;
                    $longitudereturn1 = $townmatchreturn1[0]->longitude;
                    $typetablereturn1 = $townmatchreturn1[0]->typetable;
                    $zonetablereturn1 = $townmatchreturn1[0]->zonetable;
                }
    	        $latitudereturn1 = $latitudereturn1 / 3600;
                $longitudereturn1 = ($longitudereturn1 / 3600) * -1;
            }

            if ($reporttype == 'LUNAR RETURN' && $lusebirth1 == 'N')
            {
                if ($townselectlreturn1)
                {
                    $splittownselectlreturn1 = explode("#",$townselectlreturn1);
                    $townlreturn1 = $splittownselectlreturn1[0];
                    $countylreturn1 = $splittownselectlreturn1[1];
                    $countrylreturn1 = $splittownselectlreturn1[2];
                    $latitudelreturn1 = $splittownselectlreturn1[3];
                    $longitudelreturn1 = $splittownselectlreturn1[4];
                    $typetablelreturn1 = $splittownselectlreturn1[5];
                    $zonetablelreturn1 = $splittownselectlreturn1[6];
                }
                else
                {
                    $townlreturn1 = $townmatchlreturn1[0]->town;
                    $countylreturn1= $townmatchlreturn1[0]->county;
                    $countrylreturn1= $townmatchlreturn1[0]->country;
                    $latitudelreturn1 = $townmatchlreturn1[0]->latitude;
                    $longitudelreturn1 = $townmatchlreturn1[0]->longitude;
                    $typetablelreturn1 = $townmatchlreturn1[0]->typetable;
                    $zonetablelreturn1 = $townmatchlreturn1[0]->zonetable;
                }
    	        $latitudelreturn1 = $latitudelreturn1 / 3600;
                $longitudelreturn1 = ($longitudelreturn1 / 3600) * -1;
            }

            if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
            {
                if ($townselect2)
                {
                    $splittownselect2 = explode("#",$townselect2);
                    $town2 = $splittownselect2[0];
                    $county2 = $splittownselect2[1];
                    $country2 = $splittownselect2[2];
                    $latitude2 = $splittownselect2[3];
                    $longitude2 = $splittownselect2[4];
                    $typetable2 = $splittownselect2[5];
                    $zonetable2 = $splittownselect2[6];
                }
                else
                {
                    $town2 = $townmatch2[0]->town;
                    $county2= $townmatch2[0]->county;
                    $country2= $townmatch2[0]->country;
                    $latitude2 = $townmatch2[0]->latitude;
                    $longitude2 = $townmatch2[0]->longitude;
                    $typetable2 = $townmatch2[0]->typetable;
                    $zonetable2 = $townmatch2[0]->zonetable;
                }
    	        $latitude2 = $latitude2 / 3600;
                $longitude2 = ($longitude2 / 3600) * -1;
                $_SESSION['session_dob2'] = $dob2;
                list($day2, $monthname2, $year2, $bc2) = sscanf($dob2, "%d %s %d %s");
                $month2 = array_search($monthname2, $monthnames) + 1;
                $dob2 = sprintf("%02d-%02d-%d %s",$day2,$month2,$year2,$bc2);
                $_SESSION['session_name2'] = $name2;
                $_SESSION['session_gender2'] = $gender2;
                $_SESSION['session_time2'] = $time2;
                $_SESSION['session_unknowntime2'] = $unknowntime2;
                $_SESSION['session_town2'] = (string) $town2;
                $_SESSION['session_countryid2'] = $countryid2;
            }
            $paramname = 'PAYPAL_BUSINESS';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
                    $returnxmlstring = sr_stars_loadXML($url);
            $paypal_business = $returnxmlstring->paramvalue[0];
        
            $paramname = 'PAYPAL_URL';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
                    $returnxmlstring = sr_stars_loadXML($url);
            $paypal_url= $returnxmlstring->paramvalue[0];
        
            $paramname = 'PAYPAL_RETURN';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
                    $returnxmlstring = sr_stars_loadXML($url);
            $paypal_return = $returnxmlstring->paramvalue[0];
        
            $paramname = 'PAYPAL_NOTIFY_URL';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
                    $returnxmlstring = sr_stars_loadXML($url);
            $paypal_notify_url = $returnxmlstring->paramvalue[0];


            $paramname = 'EXTRA_FORECASTS_DISCOUNT';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
            $returnxmlstring = sr_stars_loadXML($url);
            $extra_forecasts_discount = (int) $returnxmlstring->paramvalue[0];

            $paramname = 'EXTRA_SOLAR_RETURNS_DISCOUNT';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
            $returnxmlstring = sr_stars_loadXML($url);
            $extra_solar_returns_discount = (int) $returnxmlstring->paramvalue[0];

            $paramname = 'EXTRA_LUNAR_RETURNS_DISCOUNT';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
            $returnxmlstring = sr_stars_loadXML($url);
            $extra_lunar_returns_discount = (int) $returnxmlstring->paramvalue[0];

            if ($reporttype == 'FORECAST')
                $processstyle = 'ASYNCHRONOUS';
            else
                $processstyle = 'SYNCHRONOUS';
            $report_count = 1;
            $params = sprintf('<?xml version="1.0" encoding="UTF-8" ?>
            <astroRequest responseFormat="xml" processStyle="%s">
                <reports>
                  <report index="0" type="%s" level="gold" userId="0" ',$processstyle,$reporttype);
            if ($reporttype == 'SOLAR RETURN')
                $params = sprintf ('%sextraYears="%s"',$params,$extrareturn1);
            if ($reporttype == 'LUNAR RETURN')
                $params = sprintf ('%sextraMonths="%s"',$params,$extralreturn1);
            if ($reporttype == 'FORECAST')
                $params = sprintf ('%sextraYears="%s" planetpositions="off"',$params,$extraforecast1);
            if ($reporttype == 'PROGRESSED')
                $params = sprintf ('%splanetpositions="off"',$params);
            $params = sprintf('%s/>',$params);
            if(!empty($_POST['extraboth']))
            {
                foreach($_POST['extraboth'] as $check)
                {
                    $params = sprintf('%s <report index="0" type="%s" level="gold" userId="0" />',$params, $check);
                    $report_count++;
                }
            }
            if(!empty($_POST['extrauser0']))
            {
                foreach($_POST['extrauser0'] as $check)
                {
                    $params = sprintf('%s <report index="0" type="%s" level="gold" userId="0" ',$params, $check);
                    $report_count++;
                    if ($check == 'SOLAR RETURN')
                    {
                        $extrasreturn0 = TRUE;
                        $params = sprintf ('%sextraYears="%s"',$params,$extrareturn1);
                    }
                    elseif ($check == 'FORECAST')
                        $params = sprintf ('%sextraYears="%s" planetpositions="off"',$params,$extraforecast1);
                    elseif ($check == 'PROGRESSED')
                        $params = sprintf ('%splanetpositions="off"',$params);
                    
                    $params = sprintf('%s/>',$params);
                }
            }
            if(!empty($_POST['extrauser1']))
            {
                foreach($_POST['extrauser1'] as $check)
                {
                    $params = sprintf('%s <report index="0" type="%s" level="gold" userId="1" ',$params, $check);
                    $report_count++;
                    if ($check == 'SOLAR RETURN')
                    {
                        $extrasreturn1 = TRUE;
                        $params = sprintf ('%sextraYears="%s"',$params,$extrareturn2);
                    }
                    elseif ($check == 'FORECAST')
                        $params = sprintf ('%sextraYears="%s" planetpositions="off"',$params,$extraforecast2);
                    elseif ($check == 'PROGRESSED')
                        $params = sprintf ('%splanetpositions="off"',$params);
                    $params = sprintf('%s/>',$params);
                }
            }
            if ($extrasreturn0 && $usebirth1 == 'N')
            {
                if ($townselectreturn1)
                {
                    $splittownselectreturn1 = explode("#",$townselectreturn1);
                    $townreturn1 = $splittownselectreturn1[0];
                    $countyreturn1 = $splittownselectreturn1[1];
                    $countryreturn1 = $splittownselectreturn1[2];
                    $latitudereturn1 = $splittownselectreturn1[3];
                    $longitudereturn1 = $splittownselectreturn1[4];
                    $typetablereturn1 = $splittownselectreturn1[5];
                    $zonetablereturn1 = $splittownselectreturn1[6];
                }
                else
                {
                    $townreturn1 = $townmatchreturn1[0]->town;
                    $countyreturn1= $townmatchreturn1[0]->county;
                    $countryreturn1= $townmatchreturn1[0]->country;
                    $latitudereturn1 = $townmatchreturn1[0]->latitude;
                    $longitudereturn1 = $townmatchreturn1[0]->longitude;
                    $typetablereturn1 = $townmatchreturn1[0]->typetable;
                    $zonetablereturn1 = $townmatchreturn1[0]->zonetable;
                }
    	        $latitudereturn1 = $latitudereturn1 / 3600;
                $longitudereturn1 = ($longitudereturn1 / 3600) * -1;
            }

            if ($extrasreturn1 && $usebirth2 == 'N')
            {
                if ($townselectreturn2)
                {
                    $splittownselectreturn2 = explode("#",$townselectreturn2);
                    $townreturn2 = $splittownselectreturn2[0];
                    $countyreturn2 = $splittownselectreturn2[1];
                    $countryreturn2 = $splittownselectreturn2[2];
                    $latitudereturn2 = $splittownselectreturn2[3];
                    $longitudereturn2 = $splittownselectreturn2[4];
                    $typetablereturn2 = $splittownselectreturn2[5];
                    $zonetablereturn2 = $splittownselectreturn2[6];
                }
                else
                {
                    $townreturn2 = $townmatchreturn2[0]->town;
                    $countyreturn2= $townmatchreturn2[0]->county;
                    $countryreturn2= $townmatchreturn2[0]->country;
                    $latitudereturn2 = $townmatchreturn2[0]->latitude;
                    $longitudereturn2 = $townmatchreturn2[0]->longitude;
                    $typetablereturn2 = $townmatchreturn2[0]->typetable;
                    $zonetablereturn2 = $townmatchreturn2[0]->zonetable;
                }
    	        $latitudereturn2 = $latitudereturn2 / 3600;
                $longitudereturn2 = ($longitudereturn2 / 3600) * -1;
            }
            $params = sprintf('%s 
                </reports>
                <users>
                  <user id="0" firstname="%s" lastname="" gender="%s" dob="%s" time="%s" unknowntime="%s"
                      latitude="%lf" longitude="%lf" country="%s" city="%s" typetable="%d" zonetable="%d"',$params,$name1,$gender1,$dob1,$time1,$unknowntime1,$latitude1,$longitude1,$country1,addslashes($town1),$typetable1,$zonetable1);
            if ($reporttype == 'SOLAR RETURN' || $extrasreturn0 )
            {
                if ($usebirth1 == 'Y')
                {
                    $params = sprintf('%s curlatitude="%lf" curlongitude="%lf" curcountry="%s" curcity="%s" curtypetable="%d" curzonetable="%d" retdirection="%s"',$params,$latitude1,$longitude1,$country1,addslashes($town1),$typetable1,$zonetable1,$whichreturn1);
                }
                else
                {
                    if ($reporttype == 'SOLAR RETURN')
                    {
                        $_SESSION['session_townreturn1_primary'] = (string) $townreturn1;
                        $_SESSION['session_countryidreturn1_primary'] = $countryidreturn1;
                    }
                    else
                    {
                        $_SESSION['session_townreturn1_additional'] = (string) $townreturn1;
                        $_SESSION['session_countryidreturn1_additional'] = $countryidreturn1;
                    }
               
                    $params = sprintf('%s curlatitude="%lf" curlongitude="%lf" curcountry="%s" curcity="%s" curtypetable="%d" curzonetable="%d" retdirection="%s"',$params,$latitudereturn1,$longitudereturn1,$countryreturn1,addslashes($townreturn1),$typetablereturn1,$zonetablereturn1,$whichreturn1);
                }
                if ($reporttype == 'SOLAR RETURN')
                {
                    $_SESSION['session_usebirth1_primary'] = $usebirth1;
                    $_SESSION['session_whichreturn1_primary'] = $whichreturn1;
                }
                else
                {
                    $_SESSION['session_usebirth1_additional'] = $usebirth1;
                    $_SESSION['session_whichreturn1_additional'] = $whichreturn1;
                }
                
            }
            if ($reporttype == 'LUNAR RETURN' || $extralreturn0 )
            {
                if ($lusebirth1 == 'Y')
                {
                    $params = sprintf('%s lcurlatitude="%lf" lcurlongitude="%lf" lcurcountry="%s" lcurcity="%s" lcurtypetable="%d" lcurzonetable="%d" lretdirection="%s"',$params,$latitude1,$longitude1,$country1,addslashes($town1),$typetable1,$zonetable1,$whichlreturn1);
                }
                else
                {
                    if ($reporttype == 'LUNAR RETURN')
                    {
                        $_SESSION['session_townlreturn1_primary'] = (string) $townlreturn1;
                        $_SESSION['session_countryidlreturn1_primary'] = $countryidlreturn1;
                    }
                    else
                    {
                        $_SESSION['session_townlreturn1_additional'] = (string) $townlreturn1;
                        $_SESSION['session_countryidlreturn1_additional'] = $countryidlreturn1;
                    }
               
                    $params = sprintf('%s lcurlatitude="%lf" lcurlongitude="%lf" lcurcountry="%s" lcurcity="%s" lcurtypetable="%d" lcurzonetable="%d" lretdirection="%s"',$params,$latitudelreturn1,$longitudelreturn1,$countrylreturn1,addslashes($townlreturn1),$typetablelreturn1,$zonetablelreturn1,$whichlreturn1);
                }
                if ($reporttype == 'LUNAR RETURN')
                {
                    $_SESSION['session_lusebirth1_primary'] = $lusebirth1;
                    $_SESSION['session_whichlreturn1_primary'] = $whichlreturn1;
                }
                else
                {
                    $_SESSION['session_lusebirth1_additional'] = $lusebirth1;
                    $_SESSION['session_whichlreturn1_additional'] = $whichlreturn1;
                }
                
            }
            $params = sprintf('%s/>',$params);
        
            if ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH' || $reporttype == 'COMPOSITE')
            {
                $params = sprintf('%s <user id="1" firstname="%s" lastname="" gender="%s" dob="%s" time="%s" unknowntime="%s"
                      latitude="%lf" longitude="%lf" country="%s" city="%s" typetable="%d" zonetable="%d"',$params,$name2,$gender2,$dob2,$time2,$unknowntime2,$latitude2,$longitude2,$country2 ,addslashes($town2),$typetable2,$zonetable2);
                if ($extrasreturn1)
                {
                    if ($usebirth2 == 'Y')
                    {
                        $params = sprintf('%s curlatitude="%lf" curlongitude="%lf" curcountry="%s" curcity="%s" curtypetable="%d" curzonetable="%d" retdirection="%s"',$params,$latitude2,$longitude2,$country2,addslashes($town2),$typetable2,$zonetable2,$whichreturn2);
                    }
                    else
                    {
                        $_SESSION['session_townreturn2_additional'] = (string) $townreturn2;
                        $_SESSION['session_countryidreturn2_additional'] = $countryidreturn2;
                        $params = sprintf('%s curlatitude="%lf" curlongitude="%lf" curcountry="%s" curcity="%s" curtypetable="%d" curzonetable="%d" retdirection="%s"',$params,$latitudereturn2,$longitudereturn2,$countryreturn2,addslashes($townreturn2),$typetablereturn2,$zonetablereturn2,$whichreturn2);
                    }
                    $_SESSION['session_usebirth2_additional'] = $usebirth2;
                    $_SESSION['session_whichreturn2_additional'] = $whichreturn2;                    
                }
                $params = sprintf('%s/>',$params);
            }
            $paramname = 'REPORT_AMOUNT';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
            $returnxmlstring = sr_stars_loadXML($url);
            $report_amount= $returnxmlstring->paramvalue[0];
            $report_total= (float) $report_amount * $report_count;
            if ($extraforecast1)
            {
                $extraforecast1_total = $extraforecast1 * round((float) $report_amount * ((100.0-$extra_forecasts_discount)/100.0),2);
                $report_total = $report_total + $extraforecast1_total;
            }
            if ($extraforecast2)
            {
                $extraforecast2_total = $extraforecast2 * round((float) $report_amount * ((100.0-$extra_forecasts_discount)/100.0),2);
                $report_total = $report_total + $extraforecast2_total;
            }
            if ($extrareturn1)
            {
                $extrareturn1_total = $extrareturn1 * round((float) $report_amount * ((100.0-$extra_solar_returns_discount)/100.0),2);
                $report_total = $report_total + $extrareturn1_total;
            }
            if ($extrareturn2)
            {
                $extrareturn2_total = $extrareturn2 * round((float) $report_amount * ((100.0-$extra_solar_returns_discount)/100.0),2);
                $report_total = $report_total + $extrareturn2_total;
            }
            if ($extralreturn1)
            {
                $extralreturn1_total = $extralreturn1 * round((float) $report_amount * ((100.0-$extra_lunar_returns_discount)/100.0),2);
                $report_total = $report_total + $extralreturn1_total;
            }
        
            $paramname = 'REPORT_CURRENCY';
            $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
       	    $returnxmlstring = sr_stars_loadXML($url);
            $report_currency = $returnxmlstring->paramvalue[0];
        
            $params = sprintf('%s</users>
               <auth apiKey="%s" email="%s"/>
               <pref lang="EN" houseSystem="%s" />
             </astroRequest>',$params,$apikey,$email,$housecode);
        
            $url = sprintf ("%s%s?requestxml=%s",$enginepath,$enginename,urlencode($params));
            $returnxmlstring = sr_stars_loadXML($url);
            $serviceid = $returnxmlstring->service["id"];
        
            // process form and redirect
            printf ("<form name=\"purchaseform\" action=\"%s\" method=\"post\" target=\"_top\">",$paypal_url);
            printf ("<input type=\"hidden\" name=\"business\" value=\"%s\">",$paypal_business);
            printf ("<input type=\"hidden\" name=\"item_number\" value=\"%d\">",$serviceid);
            printf ("<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">");
            printf ("<input type=\"hidden\" name=\"item_name\" value=\"%s\">",$report_description);
            printf("<input type=\"hidden\" name=\"amount\" value=\"%.2lf\">",$report_total);
            printf("<input type=\"hidden\" name=\"currency_code\" value=\"%s\">",$report_currency);
            printf("<input type=\"hidden\" NAME=\"return\" value=\"%s\">",$paypal_return);
            printf("<input type=\"hidden\" NAME=\"notify_url\" value=\"%s\">",$paypal_notify_url);
            printf ("</form>\n");
            printf ("<script>");
            printf ("document.purchaseform.submit();");
            printf ("</script>");
        }
    }
    else
        $nextpage = 1;
}
 
if (IMPLEMENTATION == "WORDPRESS")
    add_shortcode( 'starsreports', 'sr_initialise' );

if (IMPLEMENTATION == "STANDARD")
    sr_initialise();

?>
