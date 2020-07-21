<?php 
	$nombre = $_POST['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset='utf-8'>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<style>
			html{
				width: 100%;
			}
			body{
				width: 100%;
				text-align: center;
			}
		</style>

		<script>

			var conn = null;
			var isConnected = false;

			$(function() {
				setOffline();
			});

			function setOnline() {
				$("#status").removeClass("label-warning");
				$("#status").addClass("label-success");
				$("#status").html("Conectado");
				$("button.connect").html("Finalizar conversacion");
				$("#offlineActions").hide();
				$("#onlineActions").show();
				isConnected = true;
			}

			function setOffline() {
				$("#status").addClass("label-warning");
				$("#status").removeClass("label-success");
				$("#status").html("Desconectado");
				$("button.connect").html("Iniciar conversacion");
				$("#offlineActions").show();
				$("#onlineActions").hide();
				isConnected = false;
			}

			function send() {
				msg = $("#message").val();
				if (msg == "") {
					alert("Can't send an empty message");
					return;
				}
				// let datos = ["<?php echo $nombre ?>",msg]
				dato= "<?php echo "<b>".$nombre."</b>"?>"+ ": " + msg;
				conn.send(dato); //// Aqui deberia retornar un arreglo donde la primera posicion nevio el nombre del usuario que esta enviando el mensaje y en la segunda posicion se envia el mensaje.
				$("#chatTarget").prepend( dato + "<br/>");
				$("#message").val("");
			}

			function toggleConnect() {
				var uri = "ws://" + $("#conn_str").val();
				if(isConnected) {
					setOffline();
					return;
				}
				conn = new WebSocket(uri);

				conn.onmessage = function(e) {
					$("#chatTarget").prepend(e.data+"<br/>");
				}

				conn.onopen = function(e) {
					console.log(e);
					setOnline();
					console.log("Conectado");
					isConnected = true;
				};

				conn.onclose = function(e) {
					console.log("Desconectado");
					setOffline();
				};
			}
		</script>
	</head>
	<body style="margin: 0 auto;">
		<h3> Chat con websockets: <?php echo "<b>".$nombre."</b>"?></h3> <a href="index.php">SALIR</a>

		<div id="offlineActions">
			<div>Server IP + Puerto: <input type="text" id="conn_str" value="127.0.0.1:8080"></div>
		</div>
		<div id="statusBox">
			<span id="status" class="label label-warning">Desconectado</span>
			<button onclick='toggleConnect();' class="connect">Conectar</button>
		</div>
		<div id="onlineActions" class="display: none">
			<input type="text" id="message">
			<button onclick="send();" class="send">Enviar Mensaje</button>
		</div>
		<div id="chatTarget" style="overflow-x: scroll; height: 400px; max-height: 400px;">
		</div>
	</body>
</html>