<!DOCTYPE html>
<html>
<head>
 <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet" type="text/css">
<meta charset=utf-8 />
<style type="text/css">
body,td,th {
  font-family: Titillium Web, Arial, Sans-Serif;
  padding: 5px;
font-size: 13px;
top:0;
right:0;
left:0;
bottom:10;
font-family: Titillium Web, Arial, Sans-Serif;
}
</style>
</head>
<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('memory_limit', '4048M');
ini_set('max_execution_time', '180');
flush();
function h($value) {
	return htmlspecialchars(strip_tags($value));
}
//echo $_SERVER['HTTP_REFERER'];

// && strpos($_SERVER['HTTP_REFERER'], '<script>') === false){
$idstop=$_GET["id"];
$idname=$_GET["sname"];
$trip_idt=$_GET["trip_idt"];
$service_idt=$_GET["service_idt"];
$route_idt=$_GET["route_idt"];
$service_idc=$_GET["service_idc"];
$route_idr=$_GET["route_idr"];
$route_long_namer=$_GET["route_long_namer"];
$route_short_namer=$_GET["route_short_namer"];
$stop_ids=$_GET["stop_ids"];
$stop_arrives=$_GET["stop_arrives"];
$trip_ids=$_GET["trip_ids"];
$calendar_monday=$_GET["calendar_monday"];
$start_date=$_GET["start_date"];
$end_date=$_GET["end_date"];
//debug
//echo $trip_idt.",".$service_idt.",".$route_idt.",".$service_idc.",".$route_idr.",".$route_long_namer.",".$route_short_namer.",".$stop_ids.",".$stop_arrives.",".$trip_ids.",startdate:".$start_date.",enddate:".$end_date.",".$calendar_monday."</br>";
//$homepage1c=false;
date_default_timezone_set("Europe/Rome");
$ora=date("H:i:s", time());
if ( (strpos($_SERVER['HTTP_REFERER'], 'tmp.php') !== false) && (strpos($_SERVER['HTTP_REFERER'], 'script') === false)){
echo "<b><h2>".$idname."</h2></b> (Fermata: ".$idstop.")</BR></BR>".$ora.", <i>arrivi pianificati nella prossima ora:</i></BR></BR>";
echo get_stopid($idstop);
echo "</br>";
}
function get_corse($corsa)
    {
  GLOBAL $service_idt;
    GLOBAL $trip_idt;
      GLOBAL $route_idt;
      $corsa=trim($corsa);
    //  $titolo=str_replace("à","%E0",$titolo);
    //  $corsa1="".$corsa;
    //  echo $corsa;
      $url="gtfs/trips.txt";
      $inizio=0;
      $homepage ="";
     //  echo $url;
      $csv = array_map('str_getcsv', file($url));
      $count = 0;
    //  $trip_id="0";
    //  $service_id="0";
    //  $route_id="0";
      foreach($csv as $data=>$csv1){
    //  if ($csv[0][$count]=="trip_id") $trip_id=$count;
    //  if ($csv[0][$count]=="service_id") $service_id=$count;
    //  if ($csv[0][$count]=="route_id") $route_id=$count;

        $count++;

      }
      if ($count == 0){
        $homepage="Nessun corsa";
      //  return   $homepage;
      }
      if ($count > 40){
      //  $homepage="errore generico corsa";
      //  return   $homepage;
      }

    //  echo $count;
      for ($i=$inizio;$i<$count;$i++){
        $filter= $csv[$i][$trip_idt];

        if ($filter==$corsa){
      //  echo $csv[$i][$trip_idt]."</br>";
      //  echo $csv[$i][$route_idt]."</br>";
      //ATTENZIONE
      // in assenza di calendar.txt si usa calendar_dates.txt. si assume che service_id sia in posizione 0 e date in posizione 1 di tale files.
          $filename = 'gtfs/calendar.txt';
      if (file_exists($filename)) {
          $homepage1c =get_calendar($csv[$i][$service_idt]);
      } else {
          $homepage1c =get_calendardates($csv[$i][$service_idt]);
      }
    //    $homepage1c =get_calendar($csv[$i][$service_idt]);
      //  echo "homepage ".$homepage1c."</br>";
    if ($homepage1c==true) $homepage =get_linee($csv[$i][$route_idt])."</br>";
      //  else $homepage =get_linee($csv[$i][0])." nel giorno ".$homepage1c." </br>";
}
    }
return   $homepage;
}
function get_calendardates($linea){
    $today = date("Ymd");
  $url1="gtfs/calendar_dates.txt";
//  $today = date("20161225"); // debug Christmas
  $inizio1=1;
  $homepage1 =0;
//  $service_id="0";
 //echo $url1;
  $csv1 = array_map('str_getcsv', file($url1));
  $count1 = 0;
  foreach($csv1 as $data1=>$csv11){

  //    if ($csv1[0][$count1]=="service_id") $service_id=$count1;
    $count1 = $count1+1;
  }
  $service_idcd="0";
  $date_c="0";
  for ($i=0;$i<5;$i++){
    if ($csv1[0][$i]=="service_id") $service_idcd=$i;
    if ($csv1[0][$i]=="date") $date_c=$i;
}
  for ($ii=$inizio1;$ii<$count1;$ii++){
    $filter1= $csv1[$ii][$service_idcd];
  //  echo $filter1."</br>";
      if ($filter1==$linea){
        if ($csv1[$ii][$date_c]==$today){
           $homepage1=true;
        // echo "oggi</br>";
        }
        }
}
return   $homepage1;
}
function get_calendar($linea)
    {
      GLOBAL $service_idc;
      GLOBAL $calendar_monday;
      GLOBAL $start_date;
      GLOBAL $end_date;
      $numero_giorno_settimana = date("w");
      $today = date("Ymd");
    //  $today = date("20161225"); // debug Christmas
    //  echo $today."</BR>";
      $linea=trim($linea);
      $giornoposizione=3; // inserire la posizione del Monday in calendar.txt
      if ($numero_giorno_settimana ==0) $giornoposizione=$calendar_monday+6;
      if ($numero_giorno_settimana ==1) $giornoposizione=$calendar_monday;
      if ($numero_giorno_settimana ==2) $giornoposizione=$calendar_monday+1;
      if ($numero_giorno_settimana ==3) $giornoposizione=$calendar_monday+2;
      if ($numero_giorno_settimana ==4) $giornoposizione=$calendar_monday+3;
      if ($numero_giorno_settimana ==5) $giornoposizione=$calendar_monday+4;
      if ($numero_giorno_settimana ==6) $giornoposizione=$calendar_monday+5;
    //  echo "oggi è: ".$numero_giorno_settimana."</br>";
    //  echo "giornoposizione: ".$giornoposizione."</br>";
    $url1="gtfs/calendar.txt";
    $inizio1=1;
    $homepage1 =0;
  //  $service_id="0";
   //echo $url1;
    $csv1 = array_map('str_getcsv', file($url1));
    $count1 = 0;

    foreach($csv1 as $data1=>$csv11){

    //    if ($csv1[0][$count1]=="service_id") $service_id=$count1;
      $count1 = $count1+1;
    }
    //  echo "oggi: ".$numero_giorno_settimana."</br>";
    for ($ii=$inizio1;$ii<$count1;$ii++){
      $filter1= $csv1[$ii][$service_idc];

      if ($filter1==$linea){

      if ($csv1[$ii][$giornoposizione]==1)
      {
        if ($today >=$csv1[$ii][$start_date] && $today <=$csv1[$ii][$end_date])
        {
      $homepage1=1;
    //  echo "bingo";
        }
      }
      //echo get_calendardates($filter1)."</br>";
      if (get_calendardates($filter1)==true) {
        $homepage1=1;
      //  echo "Buone Feste</br>";
      }
  }
      }
  return   $homepage1;
  }
function get_linee($linea)
    {
      GLOBAL $route_idr;
      GLOBAL $route_short_namer;
      GLOBAL $route_long_namer;
      $linea=trim($linea);

    $url1="gtfs/routes.txt";
    $inizio1=0;
    $homepage1 ="";
   //echo $url1;
    $csv1 = array_map('str_getcsv', file($url1));
    $count1 = 0;
  //    $route_id="0";
  //    $route_long_name="0";
  //    $route_short_name="0";
    foreach($csv1 as $data1=>$csv11){
  //      if ($csv1[0][$count1]=="route_short_name") $route_short_name=$count1;
  //      if ($csv1[0][$count1]=="route_long_name") $route_long_name=$count1;
  //      if ($csv1[0][$count1]=="route_id") $route_id=$count1;
      $count1 = $count1+1;
    }
    if ($count1 == 0){
      $homepage1="Nessuna linea";
    //  return   $homepage1;
    }

    if ($count > 80){
      $homepage1="errore generico linea";
    //  return   $homepage1;
    }
    //  echo $count;
    for ($ii=$inizio1;$ii<$count1;$ii++){
      $filter1= $csv1[$ii][$route_idr];

      if ($filter1==$linea){
      //  echo $filter1."</br>";
      $homepage1 =$csv1[$ii][$route_short_namer]." (".$csv1[$ii][$route_long_namer].")";
    }
    }

  return   $homepage1;
  }
  function get_stopid($linea)
      {
GLOBAL $stop_arrives;
GLOBAL $stop_ids;
GLOBAL $trip_ids;

      date_default_timezone_set("Europe/Rome");
      $ora=date("H:i:s", time());
      $ora2=date("H:i:s", time()+ 60*60);
      //  $ora2="09:30:00"; //debug
      //  $ora="08:30:00";
      $linea=trim($linea);
      $corsa1="".$linea;
      $url1="gtfs/stop_times.txt";
      $inizio=0;
      $homepage1 ="";
     //echo $url1;
      $orari=[];
      $row=0;
      $c=0;
      $csv = array_map('str_getcsv', file($url1));
      $count = 0;
  //    $stop_id="0";
  //    $stop_arrive="0";
  //    $trip_id="0";
      foreach($csv as $data1=>$csv11){
    //    if ($csv[0][$count]=="stop_id") $stop_id=$count;
    //    if ($csv[0][$count]=="arrival_time") $stop_arrive=$count;
    //    if ($csv[0][$count]=="trip_id") $trip_id=$count;
        $count++;
      }

        for ($i=$inizio;$i<$count;$i++)
        {

          if ($csv[$i][$stop_arrives] <=$ora2 && $csv[$i][$stop_arrives] >$ora) {

            $filter1= $csv[$i][$stop_ids];
            //echo $filter1;
        if ($filter1==$linea){
        //   array_push($distanza[$i]['orario'],$csv[$i][1]);
          $distanza[$i]['orario']=$csv[$i][$stop_arrives];

            $distanza[$i]['linea']=get_corse($csv[$i][$trip_ids]);
            $c++;

        // echo "linea".$distanza[$i]['linea'];
          }
        }
          }
      if ($c == 0){
        $homepage1="<b>Non ci sono corse nella prossima ora!</b>";
      }
      if ($c > 80){
        $homepage1="errore generico linea";
      }
      sort($distanza);
    //  var_dump($distanza);
      for ($ii=0;$ii<$c;$ii++)
      {
        if (strpos($distanza[$ii]['linea'],')') !== false){

        $homepage1 .="<b>La linea ".$distanza[$ii]['linea']."</b><i>passa alle </i><b>".$distanza[$ii]['orario']."</b><br>*****************<br>";
      }

      }
    $link ="<b></i><a href='https://www.piersoft.it/rovigo/oraripaline.php?id=".$_GET["id"]."' target='_blank'>Link diretto alla fermata</a>";
  //echo "c:".$c."</br>";
    return   $homepage1."<br>".$link;
    }
//}
  ?>

<body>
</body>
</html>
