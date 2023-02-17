<?php
include('db.php');
include('smtp/PHPMailerAutoload.php');

if($_POST['id_socio'] == 'all'){

	$filtro = $_POST['filtro'];

	if($filtro == 'todos'){
		$sql = '';
	} else {
		$sql = "WHERE t.id_time = $filtro";
	}
 	
	//envio em massa
 	$res=mysqli_query($con,"SELECT s.nome,t.descricao,s.email,s.send,s.open,p. * FROM socio s 
	INNER JOIN pagamento p ON s.id_socio = p.id_socio 
	INNER JOIN time t ON p.id_time = t.id_time $sql");

	$all_email = array();

	while($row=mysqli_fetch_assoc($res)){
		$all_email[] = $row['email'];
	}
	
	//enviando emails
	foreach ($all_email as $single_email) {	

		//texto
		$html="Msg test! ITA Ventures";
		smtp_mailer($single_email,'Test', $html);			
	
	}

	$msg = "Mensagens enciadas!";
	$status = true;



} else if(isset($_POST['id_socio']) && $_POST['id_socio']>0){
	$id=mysqli_real_escape_string($con,$_POST['id_socio']);
	$res=mysqli_query($con,"select * from socio where id_socio='$id'");
	if(mysqli_num_rows($res)>0){
		$row=mysqli_fetch_assoc($res);
		$email=$row['email'];
		//texto
		$html="Msg test! ITA Ventures";
		smtp_mailer($email,'Test', $html);
		mysqli_query($con,"update socio set send=1 where id_socio='$id'");
		$status=true;
		$msg="Enviado";
	}else{
		$status=false;
		$msg="falha no envio!";
	}
}else{
	$status=false;
	$msg="Id not found";
}

echo json_encode(array('status'=>$status,'msg'=>$msg));





function smtp_mailer($to,$subject, $msg){
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'tls'; 
	$mail->Host = "smtp.elasticemail.com";
	$mail->Port = 2525; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Username = "isaquesene26@gmail.com";
	$mail->Password = '6AE5B252098E91A7F301511A2A2564D7358A';
	$mail->SetFrom("isaquesene26@gmail.com");
	$mail->Subject = $subject;
	$mail->Body =$msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	if(!$mail->Send()){
		//echo $mail->ErrorInfo;
	}else{
		//echo 'Sent';
	}
}
?>