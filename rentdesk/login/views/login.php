<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fuenzalida Propiedades Login</title>

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


	<div class="d-flex container-fluid p-0" style="height:100%;">
		<div style="flex: 1;
				background-color: red;
				background-size: cover;
				background-repeat: no-repeat;
				background-image: url(images/fuenzalida_login.png);
				background-color: #cccccc;
			">

		</div>
		<div class="d-flex align-items-center">
			<div class="wrap-login100" style="max-width: 560px;
    width: 100%;    margin: 0 auto; padding:4rem">
				<!-- <div class="p-l-85 p-r-85 p-t-55 p-b-55"> -->
					<div class="login100-form-title " style="width: 100%;
									display: flex;
									align-items: center;
									justify-content: center;
										">
						<a href="https://apps.fuenzalida.com/intranetFP/index.php"><img href="https://apps.fuenzalida.com/intranetFP/index.php" src="images/logo_fuenzalida_propiedades.svg" alt="login" width="100%"></a>
					</div>

					<div class="mb-5">
						<h4 style="font-weight:700;">Bienvenido a Fuenzalida</h4>
						<span>Su panel de administración</span>
					</div>
					<form role="form" action="../models//login.php" method="post" name="formlogin" id="formlogin" class="login-form login100-form validate-form flex-sb flex-w" style="gap: 1rem;">
						<input type="hidden" name="accion" value="login">

						<div class="w-100">
							<span class="txt1 p-b-11">
								E-mail
							</span>
							<div class="wrap-input100 validate-input m-b-12" data-validate="Debe indicar su nombre de usuario">
								<input class="input100" type="text" name="correo" onkeyup="minusculas(this);" placeholder="Ingrese su correo">
								<span class="focus-input100"></span>
							</div>

							<span class="txt1 p-b-11">
								Contraseña
							</span>
							<div class="wrap-input100 validate-input m-b-12" data-validate="Debe indicar su contraseña">
								<span class="btn-show-pass">
									<i class="fa fa-eye"></i>
								</span>
								<input class="input100" type="password" name="password" placeholder="Contraseña">
								<span class="focus-input100"></span>
							</div>
						</div>

						<div class="d-flex justify-content-between align-items-center w-100">
							<div class="form-check m-0">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
								<label class="form-check-label" for="flexCheckDefault">
									Recordar Contraseña
								</label>

							</div>
							<a href="javascript: formOlvido();" class="txt3" style="font-weight: 600;color:#51acff">
								Olvidó mi contraseña?
							</a>

						</div>

						<div class="error-login"></div>

						<div class="container-login100-form-btn">
							<button type="submit" class="btn w-100 btn-fuenzalida">INGRESAR</button>
						</div>
						<!--
						<div class="flex-sb-m  my-3">
							<span class="mr-2">Nuevo en Fuenzalida?
								<a href="javascript: formOlvido();" class="txt3" style="font-weight: 600;color:#51acff">
									Crear Cuenta
								</a>
							</span>
						</div>
	-->

					</form>
				<!-- </div> -->
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