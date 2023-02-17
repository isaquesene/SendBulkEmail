<?php
include('db.php');
if(isset($_GET['id_socio']) && $_GET['id_socio']>0){
	$id=mysqli_real_escape_string($con,$_GET['id']);
	mysqli_query($con,"update socio set open=1 where id_socio='$id'");
	//mysqli_query($con,"update email_list set open=open+1 where id='$id'");
}
?>