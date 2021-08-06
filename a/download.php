<?php include('header.php'); ?>
<div class="container">
<div class="jumbotron"><p class="text-xl-center">
<?php
// retrieve link
if (isset($_GET["link"]) && preg_match('/^[0-9A-F]{40}$/i', $_GET["link"])) {
    $link = $_GET["link"];
}else{
    echo "<h1>Valid link not provided.</h1>";
	exit();
}
//starting verification with the $ct variable
$ct=0;
$currenttime= $_SERVER["REQUEST_TIME"];
$currentdate = date('M d, Y h:i:s A', $currenttime);
echo  'Current Date '.$currentdate.'<br/>';
// verify link get necessary information we will need to preocess request
$result = $db->query("SELECT * FROM links WHERE link='$link' ") ;
while ($row = $result->fetch_assoc()) {
$linkdb = $row['link'];
$filedownload = $row['file'];
$tstamp = $row['tstamp'];
$expire = $row['expire'];
$counting = $row['counting'];
$newcount=$counting-1;


//convert timestamp to readable date the show expiration date and time
$date = date('M d, Y h:i:s A', $expire);
echo 'To Expire on '.$date.'<br/>';

// Check to see if link has expired
if ($currenttime > $expire) {
    echo "We are so sorry the link has expired.";
	exit();
// delete link so it can't be used again
mysqli_query($db,"DELETE FROM links WHERE link='$link' ");
	exit();
}

if ($linkdb==$link) {
    echo 'You have '.$newcount.' more times to access this link.';
	mysqli_query($db,"UPDATE links SET counting='$newcount' WHERE link='$linkdb' ");
	$ct=1;
}
else {
    echo "Valid link not provided or link has expired.";
	exit();
}
}

// delete link so it can't be used again
mysqli_query($db,"DELETE FROM links WHERE link='$link' AND counting < '1' ");

//FILE DOWNLOAD
//path to file
if($ct==1){
$path = ''; 
$path = "files/$filedownload"; 
echo $path;

$mime_type=mime_content_type($path); 

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$path.'"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($path)); //Absolute URL
ob_clean();
flush();
readfile($path); //Absolute URL
exit();
}else{
	echo '<p>This file has already been dowloaded the maximum number of times.</p>';
}
?>
</p>
</div>
<?php
include_once('footer.php');