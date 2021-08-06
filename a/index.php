<?php
/*
 *      genurl.php
 *      
 *      Copyright 2010-11 Sayan "Riju" Chakrabarti <me@sayanriju.co.cc>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 * ********************************************************************
 *
 * To use, first create a table in your mysql database using the
 * following SQL statement:
 * 
 *      CREATE TABLE IF NOT EXISTS `permalink_table` (
 *        `tid` varchar(14) NOT NULL,
 *        `permalink` varchar(2083) NOT NULL,
 *        `expires_at` int(11) NOT NULL,
 *        `single_use` tinyint(1) NOT NULL DEFAULT '0',
 *        PRIMARY KEY (`tid`)
 *      );
 * 
 *   Next, change the following database related variables accordingly.
 *
 * ********************************************************************
 */

$DBHOST="localhost";
$DBUSER="root";
$DBPASS="qwerty";
$DBNAME="mydb";
$TABLE_NAME="permalink_table";

function generate_temp_url($permalink,$redirector_page,$expires_in_minutes,$single_use=0,$dbhost,$dbuser,$dbpass,$dbname,$table_name){
    $tid=uniqid();
    $expires_at=time()+$expires_in_minutes*60;
    $single_use=($single_use!=0)?1:0;
    $conn=mysql_connect($dbhost,$dbuser,$dbpass) or die(mysql_error());
    mysql_select_db($dbname) or die(mysql_error());
    $sql=sprintf("INSERT INTO %s(tid,permalink,expires_at,single_use) VALUES('%s','%s','%s','%s')",
        mysql_real_escape_string($table_name),
        mysql_real_escape_string($tid),
        mysql_real_escape_string($permalink),
        mysql_real_escape_string($expires_at),
        mysql_real_escape_string($single_use));
    mysql_query($sql) or die(mysql_error());
    mysql_close($conn);

    return "$redirector_page?tid=$tid";   
}


function show_error(){
    header("HTTP/1.0 404 Not Found");
    die("<h1>Page Not Found!</h1>The link you specified is either invalid or has expired");    
}

if(!isset($_GET['tid'])):

/* ************** Generator Code Section *************** */

?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>untitled</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="generator" content="Geany 0.18" />
    <script type="text/javascript">
    var toggle=0;
    function fun(){
        var el=document.getElementById("expires_in");
        if(toggle==0){
            el.disabled=true;
            toggle=1;
        }
        else{
            el.disabled=false;
            toggle=0;
        }    
    }
    </script>    
    </head>
    <body>
<?php
    if(!isset($_POST['permalink'])):
        // First time in Page, show form:
?>
    <div style="text-align:center;margin:3em auto;">
    <form name="myform" id="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        Permalink:&nbsp;&nbsp;
        <input type="text" name="permalink" id="permalink" value="http://"/>
        <br/><br/>
        Expires in:&nbsp;&nbsp;&nbsp;
        <input type="text" name="expires_in" id="expires_in" size="6" value="60"/>&nbsp; minutes
        <br/><br/>
        Single Use Only?&nbsp;&nbsp;&nbsp;<input type="checkbox" name="single_use" id="single_use" onclick="javascript:fun();" />
        <br/><br/>
        <input type="submit" value="Generate"/>
    </form>
    </div>
    <?php
    else:
    // Form submitted, generate and return temporary url
    $curpage="http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $single_use=($_POST['single_use'])?1:0;
    echo "Redirection URL:<br/>";
    echo generate_temp_url($_POST['permalink'],$curpage,$_POST['expires_in'],$single_use,$DBHOST,$DBUSER,$DBPASS,$DBNAME,$TABLE_NAME);
    die();
    endif;
else:

/* ************ Redirection Code Section ******************* */

    $tid=$_GET['tid'];     // get temporary id from url
    // fetch corrs permalink from database
    $conn=mysql_connect($DBHOST,$DBUSER,$DBPASS) or die(mysql_error());
    mysql_select_db($DBNAME) or die(mysql_error());
    $sql="SELECT permalink,expires_at,single_use FROM $TABLE_NAME WHERE tid='".mysql_real_escape_string($tid)."'";
    $result=mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($result)==0)
        show_error();

    $row=mysql_fetch_row($result);
    $permalink=$row[0];
    $etime=$row[1];
    $single_use=$row[2];
    // Time expired?
    if((time()-$etime)>0 && $single_use==0)
        show_error();
    // First, delete from temp url database, if single_use url
    if($single_use==1){
        $sql="DELETE FROM $TABLE_NAME WHERE tid='".mysql_real_escape_string($tid)."'";
        mysql_query($sql) or die(mysql_error()) ;
    }
    mysql_close($conn);
    // Redirect to permalink
    header("HTTP/1.0 307 Temporary Redirect");
    header( 'Location: '.$permalink) ;
endif;
?>
    
</body>
</html>
