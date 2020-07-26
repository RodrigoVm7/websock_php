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

			var socket = null;
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

			function enviar() {
				msg = $("#message").val();
				if (msg == "") {
					alert("No se puede enviar un mensaje vacío");
					return;
				}
				dato= "<?php echo "<b>".$nombre."</b>"?>"+ ": " + msg;
				socket.send(dato);
				$("#chatTarget").prepend( dato + "<br/>");
				$("#message").val("");
			}

			function establecerConexion() {
				var url = "ws://" + $("#conn_str").val();
				if(isConnected) {
					setOffline();
					return;
				}
				socket = new WebSocket(url);

				socket.onmessage = function(e) {
					$("#chatTarget").prepend(e.data+"<br/>");
				}

				socket.onopen = function(e) {
					console.log(e);
					setOnline();
					console.log("Conectado");
					isConnected = true;
				};

				socket.onclose = function(e) {
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
			<button onclick='establecerConexion();' class="connect">Conectar</button>
		</div>
		<div id="onlineActions" class="display: none">
			<input type="text" id="message">
			<button onclick="enviar();" class="send">Enviar Mensaje</button>
		</div>
		<div id="chatTarget" style="overflow-x: scroll; height: 400px; max-height: 400px;">
		</div>
	</body>
</html>