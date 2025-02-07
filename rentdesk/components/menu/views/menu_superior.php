 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
 	<div class="container-fluid">
 		<div class="navbar-wrapper">
 			<div class="navbar-toggle">
 				<button type="button" class="navbar-toggler border-0" style="display:block; box-shadow:none">

 					<i class="fas fa-bars"></i>
 				</button>
 			</div>
 			<a class="navbar-brand no-decoration" href="index.php?component=dashboard&view=dashboard">
 				<span style="display: flex;
                  font-size: .8rem;
                  align-items: center;
                  gap: 0.5rem;">
 					<i class="fa-solid fa-chevron-left"></i>Inicio</span>
 			</a>
 		</div>

 		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
 			<span class="navbar-toggler-bar navbar-kebab"></span>
 			<span class="navbar-toggler-bar navbar-kebab"></span>
 			<span class="navbar-toggler-bar navbar-kebab"></span>
 		</button>

 		<div class="collapse navbar-collapse justify-content-start" id="navigation">
 			<ul class="navbar-nav me-auto">
 				<?php echo generarMenuSuperior("menuPrincipal"); ?>
 			
			</ul>
 			<ul class="navbar-nav">
 				<li class="nav-item dropdown btn-rotate  d-flex dropstart">
 					<a class="nav-link no-decoration" href="" id="sub-suc" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20" style="display: flex; align-items: center; gap: 0.3rem; padding:.5rem"><i class="fa-solid fa-building font-icon-sup"></i>|<i class="fa-solid fa-shop font-icon-sup"></i><i class="fas fa-chevron-down"></i>
 						<p><span class="d-lg-none d-md-block">Subsidiaria|Sucursal</span></p>
 					</a>
 					<div class="dropdown-menu p-4" aria-labelledby="sub-suc">
 						<!-- <div class="menu-general-subsidiaria-select"> -->
 						<div class="d-flex">
 							<?php include("includes/select-subsidiaria-vista.php"); ?>

 							<?php include("includes/select-sucursal-vista.php"); ?>

 						</div>
 						<!-- </div> -->
 					</div>
 				</li>

 			</ul>


 			<ul class="navbar-nav ">

 				<?php echo generarMenuSuperior("menuAcciones"); ?>

 				<?php include("components/menu/menu_conf.php"); ?>

 				<?php echo generarMenuSuperior("menuPerfil"); ?>
 			</ul>
 		</div>
 	</div>
 </nav>