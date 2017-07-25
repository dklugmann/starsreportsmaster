<?php
include("stars_config.php");
include("stars_functions.php");
$reporttype = $_POST['reporttype'];
$name1 = $_POST['name1'];
$dob1 = $_POST['dob1'];
$time1 = $_POST['time1'];
$unknowntime1 = $_POST['unknowntime1'];
$country1 = $_POST['country1'];
$town1 = $_POST['town1'];
$latitude1 = $_POST['latitude1'];
$longitude1 = $_POST['longitude1'];
$typetable1 = $_POST['typetable1'];
$zonetable1 = $_POST['zonetable1'];
$name2 = $_POST['name2'];
$dob2 = $_POST['dob2'];
$time2= $_POST['time2'];
$unknowntime2 = $_POST['unknowntime2'];
$country2 = $_POST['country2'];
$town2 = $_POST['town2'];
$latitude2 = $_POST['latitude2'];
$longitude2 = $_POST['longitude2'];
$typetable2 = $_POST['typetable2'];
$zonetable2 = $_POST['zonetable2'];
$housecode = $_POST['housecode'];
$serviceid = $_GET['serviceid'];
$sequence = $_GET['sequence'];
$email = $_GET['email'];

$curtime = getdate();
$m =  $curtime['mon'];
$y =  $curtime['year'];
$d =  $curtime['mday'];

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/tr/html4/strict.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="stars.css">
<script type="text/javascript" src="stars.js?<?php echo time(); ?>" language="javascript1.2"></script>
</head>
<body>
<?
   $url = $starspath . "/getreport.php?serviceid=" . $serviceid . "&sequence=" . $sequence . "&apikey=" . urlencode($apikey) . "&email=" . urlencode($email);
   $returnxmlstring = loadXML($url);

   $report = $returnxmlstring->reports[0]->report;
   $reporttype = $report["type"];
   if ($reporttype == 'SYNASTRY' || $reporttype == 'STARATCH' || $reporttype == 'COMPOSITE')
      $user = 'both';
   else
      $user = '1';

   $paramname = 'REPORT_TYPE';
   $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
   $returnxmlstring1 = loadXML($url);
   $num_reports = count($returnxmlstring1->paramvalue);

   $paramname = 'REPORT_DESCRIPTION';
   $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
   $returnxmlstring2 = loadXML($url);
   for ($count = 0; $count < $num_reports; $count++)
   {
        $cur_reporttype = $returnxmlstring1->paramvalue[$count];
        if ((string) $reporttype == (string) $cur_reporttype)
        {
            $report_description = $returnxmlstring2->paramvalue[$count];
            break;
        }
   }

   $paramname = 'REPORT_HEADER';
   $url = $starspath . "/getserviceparam.php?apikey=" . urlencode($apikey) . "&paramname=" . urlencode($paramname);
   $returnxmlstring3 = loadXML($url);
   $num_reports = count($returnxmlstring3->paramvalue);

   $chaptercount = count($report->sections[0]->section);
   $name1 = $report->names[0]->name[0]["firstname"];
   $name2 = $report->names[0]->name[1]["firstname"];


   


/*
   printf ("<div style=\"clear:both\"></div>");
   printf ("<div style=\"position:absolute; left:10px; top:10px; font-size:12px; padding:0px; width:150px; z-index:500;\">");
*/
   
   printf ("<div style=\"float:left; max-width:150px; font-size:12px; padding:0px; width:15%%\">");
   $i = 0;
   printf ("<ul style=\"border:2px outset rgb(240,240,240)\">");
   foreach ($report->sections[0]->section as $chapter)
   {
           printf ("<li id=\"MENU%d\" class=\"li1\"", $i+1);
           if (!$i)
               printf (" style=\"background-color:rgb(200,220,240)\"");
           else
               printf (" style=\"background-color:white\"");
           printf ("><a href=# onclick=\"displayelement('%d',%d); return false;\">%s</a></li>", $i+1, $chaptercount, ucwords(strtolower($chapter["title"])));
           $i++;
   }

   $chartid1 = $report->charts[0]->chart[0]["path"];
   $chartid2 = $report->charts[0]->chart[1]["path"];
   $chartid3 = $report->charts[0]->chart[2]["path"];

   if ($reporttype == 'SOLAR RETURN')
   {
       $chart_description1 = sprintf("Solar Return Chart of %s",ucwords(strtolower($name1)));
       $chart_description2 = sprintf("Birth Chart of %s",ucwords(strtolower($name1)));
       printf ("<li id=\"MENU-2\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-2',%d); return false;\">%s</a></li>", $chaptercount, $chart_description1);
       printf ("<li id=\"MENU-1\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-1',%d); return false;\">%s</a></li>", $chaptercount, $chart_description2);
   }
   elseif ($reporttype == 'LUNAR RETURN')
   {
       $chart_description1 = sprintf("Lunar Return Chart of %s",ucwords(strtolower($name1)));
       $chart_description2 = sprintf("Birth Chart of %s",ucwords(strtolower($name1)));
       printf ("<li id=\"MENU-2\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-2',%d); return false;\">%s</a></li>", $chaptercount, $chart_description1);
       printf ("<li id=\"MENU-1\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-1',%d); return false;\">%s</a></li>", $chaptercount, $chart_description2);
   }
   elseif ($reporttype == 'PROGRESSED')
   {
       $chart_description1 = sprintf("Progressed Chart of %s",ucwords(strtolower($name1)));
       $chart_description2 = sprintf("Birth Chart of %s",ucwords(strtolower($name1)));
       printf ("<li id=\"MENU-2\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-2',%d); return false;\">%s</a></li>", $chaptercount, $chart_description1);
       printf ("<li id=\"MENU-1\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-1',%d); return false;\">%s</a></li>", $chaptercount, $chart_description2);
   }
   elseif ($reporttype == 'SYNASTRY' || $reporttype == 'STARMATCH')
   {
       $chart_description1 = sprintf("Chart of %s",ucwords(strtolower($name1)));
       $chart_description2 = sprintf("Chart of %s",ucwords(strtolower($name2)));
       printf ("<li id=\"MENU-1\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-1',%d); return false;\">%s</a></li>", $chaptercount, $chart_description1);
       printf ("<li id=\"MENU-2\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-2',%d); return false;\">%s</a></li>", $chaptercount, $chart_description2);
   }
   elseif ($reporttype == 'COMPOSITE')
   {
       $chart_description1 = sprintf("Chart of %s",ucwords(strtolower($name1)));
       $chart_description2 = sprintf("Chart of %s",ucwords(strtolower($name2)));
       $chart_description3 = sprintf("Composite Chart of %s and %s",ucwords(strtolower($name1)),ucwords(strtolower($name2)));
       printf ("<li id=\"MENU-1\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-1',%d); return false;\">%s</a></li>", $chaptercount, $chart_description1);
       printf ("<li id=\"MENU-2\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-2',%d); return false;\">%s</a></li>", $chaptercount, $chart_description2);
       printf ("<li id=\"MENU-3\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-3',%d); return false;\">%s</a></li>", $chaptercount, $chart_description3);
   }
   else
    {
       $chart_description1 = sprintf("Chart of %s",ucwords(strtolower($name1)));
       printf ("<li id=\"MENU-1\" class=\"li1\" style=\"background-color:white\"><a href=# onclick=\"displayelement('-1',%d); return false;\">%s</a></li>", $chaptercount, $chart_description1);
   }
   printf ("</ul>");
   printf ("</div>");

printf ("<div style=\"overflow:auto; padding-left:10px;\">");
   printf ("<div style=\"margin-left:auto; margin-right:auto; max-width:700px; text-align:justify\">");
   
   printf ("<div class=\"headerimage\"><img src=\"%s/%s\"></div>",$starspath,$returnxmlstring3->paramvalue);
   printf ("<div class=\"headerline\">");
   printf ("<div class=\"headertitle\">%s of %s", $report_description, ucwords(strtolower($name1)));
   if ($user == 'both')
       printf (" and %s", ucwords(strtolower($name2)));
   printf ("</div>");
   printf ("</div>");
/*
   printf ("<div style=\"margin-top:0px; margin-left:auto; margin-right:auto; width:700px; text-align:justify\">");
*/

   $i = 0;
   foreach ($report->sections[0]->section as $chapter)
   {
       printf ("<div id=\"para%d\"",$i+1);
       if ($i)
           printf (" style=\"display:none\"");
       printf (">");
       printf ("<div class=\"chapterheader\">%s</div>",ucwords(strtolower($chapter["title"])));
       printf ("<div style=\"padding-top:10px;\">");
       $original = array("<title>", "</title>", "<![CDATA[", "]]>");
       $replaced = array("<span class=\"title\">", "</span>", "", "");
       $chapter->content=  str_replace($original,$replaced,$chapter->content );
       if ($reporttype != 'FORECAST')
       {
           $chapter->content= nl2br($chapter->content);
           $chapter->content = str_ireplace("&lt","<",$chapter->content );
           $chapter->content= str_ireplace("&gt",">",$chapter->content );
           $chapter->content = preg_replace("/<pstopictitle>(.*?)\|/i","<span class=\"topictitle\">$1</span>",$chapter->content );
           $chapter->content = str_ireplace("|","",$chapter->content );
           $chapter->content = preg_replace("/<subheading name=\"(.*)\">/","<span class=\"$1\">",$chapter->content );
           $chapter->content= preg_replace("/<\/subheading>/","</span>",$chapter->content );
           $chapter->content = preg_replace("/<boldon>/i","<span class=\"boldon\">",$chapter->content );
           $chapter->content = preg_replace("/<boldoff>/i","</span>",$chapter->content );
           $chapter->content = preg_replace("/<italicon>/i","<span class=\"italicon\">",$chapter->content );
           $chapter->content = preg_replace("/<italicoff>/i","</span>",$chapter->content );
           $chapter->content = preg_replace("/<centre>/i","<div class=\"centertext\">",$chapter->content );
           $chapter->content = preg_replace("/<centreoff>/i","</div>",$chapter->content );
       }
       printf ("%s</div>",$chapter->content);
       printf ("</div>");
       $i++;
   }
   printf ("</div>");
   printf ("<div style=\"display:none\" id=\"chart1\"><img style=\"display:block; margin-left:auto; margin-right:auto; padding-top:20px;\" src=\"%s\"></img></div>",$chartid1);

   if (isset($chartid2))
        printf ("<div style=\"display:none\" id=\"chart2\"><img style=\"display:block; margin-left:auto; margin-right:auto; padding-top:20px;\" src=\"%s\"></img></div>",$chartid2);
   if (isset($chartid3))
        printf ("<div style=\"display:none\" id=\"chart3\"><img style=\"display:block; margin-left:auto; margin-right:auto; padding-top:20px;\" src=\"%s\"></img></div>",$chartid3);

   printf ("<div style=\"padding-top:20px\"></div>");\
 printf ("</div>");
   printf ("</div>");
  printf ("</div>");
   printf ("</div>");
  
printf ("</body>");
printf ("</html>");
?>

