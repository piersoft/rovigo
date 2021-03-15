<?php //printf($_GET['id'];
header('Content-Type: text/html; charset=utf-8');
function h($data,$encoding='UTF-8')
{
 return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);

}
?>
<!DOCTYPE html>
<html>
<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet" type="text/css">

<head>
<style>
table, th, td {
      font-family: Titillium Web, Arial, Sans-Serif;
    border: 1px solid black;
}
</style>
</head>
<body>

<style>

    #loadImg{
      position:absolute;
      left:30%;
      top:35%;
    font-family: Titillium Web, Arial, Sans-Serif;
    }

  </style>
  <script>
window.onload=function(){
var iframe=document.getElementById('mioIFRAME');
 if(iframe){
    var altezza = iframe.contentWindow.document.body.scrollHeight;
    iframe.height = altezza+"px";
 }
}
</script>
      <div id="loadImg">Caricamento...</br></br>      <div><img src="ajax-loader3.gif" /></div></div>
         <iframe id="mioIFRAME" border=0 name=iframe src="orari.php?sname=<?php
				 if (strpos($_SERVER['HTTP_REFERER'], '<script>') === false ){
 printf(h($_GET['sname']).'&id='.h($_GET['id']).'&stop_ids='.h($_GET['stop_ids']).'&stop_arrives='.h($_GET['stop_arrives']).'&trip_ids='.h($_GET['trip_ids']).'&route_short_namer='.h($_GET['route_short_namer']).'&route_long_namer='.h($_GET['route_long_namer']).'&route_idr='.h($_GET['route_idr']).'&service_idc='.h($_GET['service_idc']).'&trip_idt='.h($_GET['trip_idt']).'&service_idt='.h($_GET['service_idt']).'&route_idt='.h($_GET['route_idt']).'&calendar_monday='.h($_GET['calendar_monday']).'&end_date='.h($_GET['end_date']).'&start_date='.h($_GET['start_date']));} ?>" width="100%" scrolling="no" frameborder="0" onload="document.getElementById('loadImg').style.display='none';"></iframe>
     </body>
       </html>
