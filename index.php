<?php
include('db.php');
$res = mysqli_query($con, "SELECT s.nome,t.descricao,s.email,s.send,s.open,p. * FROM socio s 
INNER JOIN pagamento p ON s.id_socio = p.id_socio 
INNER JOIN time t ON p.id_time = t.id_time;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Email Sending Script</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style>
		.container {
			width: 700px;
			margin-top: 50px;
		}
	</style>
</head>

<body>
	<div class="container">

		<select id='filtro'>
			<option value="todos">Todos</option>
			<?php

			// $id_empresa = $_GET['id_empresa']; 
			$sql = "select id_time, descricao from time";
			$result = mysqli_query($con, $sql);

			while ($row = mysqli_fetch_assoc($result)) {
				?>
				<option value="<?php echo $row['id_time']; ?>"><?php echo $row['descricao']; ?></option>
			<?php } ?>
		</select>

		<h2>Email Sending Script</h2>
		<?php if (mysqli_num_rows($res) > 0) { ?>
			<table class="table table-bordered" id="myTable">
				<thead>
					<tr>
						<!--<th>S.No</th>-->
						<th>Socio</th>
						<th>Email</th>
						<th>Modalidade</th>
						<th>Enviar</th>
						<th>Status do Envio</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//$i=1;
					while ($row = mysqli_fetch_assoc($res)) { ?>
						<tr>
							<!--<td><?php echo $i++ ?></td>-->
							<td>
								<?php echo $row['nome'] ?>
							</td>
							<td>
								<?php echo $row['email'] ?>
							</td>
							<td>
								<?php echo $row['descricao'] ?>
							</td>
							<td id="btn<?php echo $row['id_socio'] ?>">
								<!--send & open-->

								<button type="button" class="btn btn-success"
									onclick="send_msg('<?php echo $row['id_socio'] ?>')">Enviar</button>

							</td>
							<td>
								<?php
								if ($row['send'] == 1) {
									echo "Sim";
								} else {
									echo "Não";
								}
								?>
							</td>
						</tr>
					<?php
					} ?>
				</tbody>
			</table>
		<?php } else {
			echo "No data found";
		} ?>

		<button id="btn_all" onclick="send_msg_all()">Enviar para todos</button>
	</div>
	<script>
		//confirmação de envoi de e-mail
		function send_msg(id) {
			var check = confirm('Enviando e-mail, clique "OK" para continuar! ');
			if (check == true) {
				jQuery('#btn' + id).html('Por favor, aguarde...');
				jQuery.ajax({
					url: 'send_msg.php',
					type: 'post',
					data: 'id_socio=' + id,
					success: function (result) {
						result = jQuery.parseJSON(result);

						if (result.status == true) {
							jQuery('#btn' + id).html('<button type="button" class="btn btn-success" onclick=send_msg("' + id + '")>Enviar</button>');
						}
						if (result.status == false) {
							jQuery('#btn' + id).html('<button type="button" class="btn btn-success" onclick=send_msg("' + id + '")>Enviar</button><div clsss="error_msg">' + result.msg + '</div>');
						}
					}
				});
			}
		}

		//confirmação de envoi de e-mail
		function send_msg_all() {
			var check = confirm('Enviar email em massa, clique "OK" para continuar! ');
			if (check == true) {

				var filtro = jQuery('#filtro').val();

				jQuery('#btn_all').html('Por favor, aguarde...');
				jQuery.ajax({
					url: 'send_msg.php',
					type: 'post',
					data: 'id_socio=all&filtro=' + filtro,
					success: function (result) {
						result = jQuery.parseJSON(result);
						console.log(result.status);
						if (result.status == true) {
							jQuery('#btn' + id).html('Enviado');
						}
						if (result.status == false) {
							jQuery('#btn' + id).html('<button type="button" class="btn btn-success" onclick=send_msg("' + id + '")>Send</button><div clsss="error_msg">' + result.msg + '</div>');
						}
					}
				});
			}
		}
	</script>

	<!--filter search table enviar beneficio-->
	<script type="text/javascript">
		function myFunction() {
			// Declare variables
			var input, filter, table, tr, td, i, txtValue;
			input = document.getElementById("filtro");
			filter = input.value.toUpperCase();
			table = document.getElementById("myTable");
			tr = table.getElementsByTagName("tr");

			// Loop through all table rows, and hide those who don't match the search query
			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[2];
				if (td) {
					txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}
			}
		}
	</script>
</body>

</html>