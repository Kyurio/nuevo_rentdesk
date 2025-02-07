<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ARPIS Login</title>
	
	<link rel="shortcut icon" href="favicon.ico">

	<!-- CSS -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/views/assets/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/views/assets/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login/views/assets/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/views/assets/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/views/assets/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login/views/assets/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/css/util.css">
	<link rel="stylesheet" type="text/css" href="login/css/main.css">
<!--===============================================================================================-->





	 

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicon and touch icons -->
	
    

</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
			<form role="form" action="javascript: restauraClave('<?php echo $token_recuperar_clave;?>');" method="post" name="registro" id="registro" class="login-form login100-form validate-form flex-sb flex-w">
					
<input type="hidden" name="accion" value="login">					
					<span class="login100-form-title p-b-32">
						<a href="https://apps.fuenzalida.com/intranetFP/index.php"><img href="https://apps.fuenzalida.com/intranetFP/index.php" src="images/logo.png" alt="login" ></a>
					</span>

					<span class="txt1 p-b-11">
						Ingresa tu nueva contraseña:
					</span>
					<div class="wrap-input100 validate-input m-b-36" data-validate = "Debe indicar una contraseña">
						<input class="input100 segclave" type="password" name="password" id="password" placeholder="Contraseña" >
						<span class="focus-input100"></span>
					</div> 
					
					
					
					
					
<div id="pswd_info">
    <h4>La contraseña nueva debe cumplir:</h4>
    <ul>
      <li id="letter" class="invalid">Al menos <strong>una letra en minúscula</strong>
      </li>
      <li id="capital" class="invalid">Al menos <strong>una letra mayúscula</strong>
      </li>
      <li id="number" class="invalid">Al menos <strong>un número</strong>
      </li>
      <li id="length" class="invalid">Al menos <strong>8 caracteres en total</strong>
      </li>
    </ul>
  </div>
					
					
					
					<span class="txt1 p-b-11">
						Reingresa tu nueva contraseña:
					</span>
					<div class="wrap-input100 validate-input m-b-36" data-validate = "Debe repetir su contraseña">
						<input class="input100" type="password" name="password2" id="password2" placeholder="Contraseña" >
						<span class="focus-input100"></span>
					</div>
					
		 
					

					<div class="container-login100-form-btn">
					
					
					<button type="submit" class="login100-form-btn" >Ingresar</button>
					
					
					
					<div class="flex-sb-m w-full p-b-48">
						<div>
							<a href="index.php" class="txt3">
								Ir al inicio
							</a>
						</div>
					</div>
						 
					</div>

				</form>
			</div>
		</div>
	</div>
	
	
	

	
	

	<div id="dropDownSelect1"></div>
	
 
	
	

	
	<!-- Javascript -->
<!--===============================================================================================-->
	<script src="login/views/assets/jquery/jquery-3.4.1.min.js"></script>
<!--===============================================================================================-->
	<script src="login/views/assets/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="login/views/assets/bootstrap/js/popper.js"></script>
	<script src="login/views/assets/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="login/views/assets/select2/select2.min.js"></script>
<!--==============================================================================================-->
	<script src="login/views/assets/daterangepicker/moment.min.js"></script>
	<script src="login/views/assets/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="login/views/assets/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->

<script src="login/js/bootstrap-show-modal.js"></script>

	<script src="login/js/main.js"></script>
	
 
	
	
	<script src="js/validadores.js"></script>
	<script src="login/js/js.js"></script>
	
	
	<!--[if lt IE 10]>
		<script src="login/js/placeholder.js"></script>
	<![endif]-->
</body>
</html>
