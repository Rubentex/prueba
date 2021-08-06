<?php include('header.php');
if(isset($_POST['submit'])){
	 $errors= array();
      $file_name = $_FILES['file']['name'];
      $file_size =$_FILES['file']['size'];
      $file_tmp =$_FILES['file']['tmp_name'];
      $file_type=$_FILES['file']['type'];
	  $fileend=explode('.',$file_name);
      $file_ext=strtolower(end($fileend));
      
      $extensions= array("jpeg","jpg","png","pdf");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"files/".$file_name);
         //echo "Success";
      }else{
         print_r($errors);
      }
   
   
$expire=$_POST['date'];
$counting=$_POST['counting'];
$date = date('M d, Y h:i:s A', strtotime($expire));
$dbdate = date('Y M d H:i:s', strtotime($expire));
$one= 'To Expire on '.$date.'<br/>';
$d = DateTime::createFromFormat(
    'Y M d H:i:s',
    $dbdate,
    new DateTimeZone('EST')
);

if ($d === false) {
    die("Incorrect date string");
} else {
    $expiredate=$d->getTimestamp();
}

$link = sha1(uniqid($file_name, true));

$tstamp=$_SERVER["REQUEST_TIME"];

mysqli_query($db,"INSERT INTO links(`link`,`file`, `counting`, `expire`, `tstamp`)
VALUES ('$link', '$file_name', '$counting','$expiredate','$tstamp')");

$two= '<a href="http://localhost/testing/download.php?link='.$link.' " target="_NEW">Link</a>';
}
?>
<div class="container">
<div class="jumbotron"><p class="text-xl-center"><?php if(isset($one)){echo $one.$two;};?></p></div>
<h1 class="animated bounce"><span class="glyphicon glyphicon-link"></span>Generate A Link That Expires</h1>
<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">	
	<form method="post" role="form" enctype="multipart/form-data">
	<div class="form-group">
	<label for="file">Select File:</label>
	<input type="file" class="form-control" name="file" required>
	</div>
	<div class="form-group">
	<label for="counting">How Many Times Can Link Be Accessed?:</label>
	<input type="tel" class="form-control" name="counting" required>
	</div>
	<div class="form-group">
	<label for="date">Set Expiration Date and Time For Link:</label>
	<input type="datetime-local" class="form-control" name="date" required>
	</div>
	<input type="submit" name="submit" class="btn btn-success btn-lg" value="submit" />
	</form>
	</div>
    <div class="col-sm-4"></div>
</div>
</div>
<?php 
include('footer.php');
?>