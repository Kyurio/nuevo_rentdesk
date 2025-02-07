 <div class="sidebar-wrapper">
 	<ul class="nav">
 		<?php if (generarMenuTabs()) : ?>
 			<?php echo generarMenuTabs(); ?>
 		<?php else : ?>
 			<?php echo generarMenuLateral(); ?>
 		<?php endif; ?>
 	</ul>


 	<div style="width:80%; margin-left:auto;margin-right:auto; margin-top:80px; cursor:pointer;" onClick="document.location.href='login/models/logout.php'; ">
 		<!-- <img src="images/icon-demo-user.jpg" alt="Fuenzalida"> -->
 		<div class="profile-card">
 			<div class="profile-info">
 				<div class="avatar">
 					<i class="fa-solid fa-user"></i>
 				</div>
 				<div class="info">
 					<span style="font-weight: 700;"><?php echo "$current_usuario->nombres " . "$current_usuario->apellidoPaterno"  ?></span>
 				</div>

 			</div>

 			<div class="profile-logout">
 				<i class="fa-solid fa-power-off" style="font-size: 20px;"></i>
 			</div>
 		</div>


 	</div>

 	<div class="mt-5">
 		<div class="text-center">
 			<a href="https://emasmas.cl/clientes/rentdeskhelper/" target="_blank"><i class="fa-solid fa-book"></i> Manual de usuario</a>
 		</div>
 	</div>


 	<div style="text-align: center; padding: 30px">
 		Version: <?php echo $version_app ?>
 	</div>

 </div>