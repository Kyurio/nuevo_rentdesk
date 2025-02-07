	<div class="content content-page mb-5">

		<br><br>
		<!-- cabecera -->
		<fieldset id="info-cliente" class="form-group border p-3  mt-5">

			<ul class="nav" id="myTab" role="tablist">

				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="cierre-pago-tab" data-bs-toggle="tab" data-bs-target="#cierre-pago-tab-pane"
						type="button" role="tab" aria-controls="cierre-pago-tab-pane" aria-selected="true">
						Cierre Pagos
					</button>
				</li>

				<!-- <li class="nav-item" role="presentation">
					<button class="nav-link" id="cierre-liquidaciones-tab" data-bs-toggle="tab" data-bs-target="#cierre-liquidaciones-tab-pane" type="button" role="tab" aria-controls="cierre-liquidaciones-tab-pane" aria-selected="false">
						Cierre Liquidaciones
					</button>
				</li> -->


				<li class="nav-item" role="presentation">
					<button class="nav-link" id="documentos-liquidaciones-tab" data-bs-toggle="tab" data-bs-target="#documentos-liquidaciones-tab-pane" type="button" role="tab" aria-controls="documentos-liquidaciones-tab-pane" aria-selected="false">
						Documentos Office Banking
					</button>
				</li>
			</ul>


		</fieldset>

		<!-- contenido -->
		<fieldset id="info-cliente" class="form-group border p-3">
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="cierre-pago-tab-pane" role="tabpanel" aria-labelledby="cierre-tab" tabindex="0">
					<div class="content content-page">

						<h1>Cierre Pagos</h1>
						<!-- <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModalListadoPropiedeades" onclick="CargarListadoPropiedades()"> Nuevo Cierre. </button> -->

						<div class="row mb-3 h-auto">
							<div class="col-auto p-0 m-0">
								<button id="marcarTodosCierres" class="btn btn-info me-2">Marcar Todos</button>
							</div>
							<div class="col-auto p-0 m-0">
								<button id="DesmarcarTodosCierres" class="btn btn-info me-2">Desmarcar Todos</button>
							</div>
							<div class="col-auto p-0 m-0">
								<button id="generarOfficeBanking" class="btn btn-primary me-2">Generar Office Banking</button>
							</div>
							<div class="col-3 p-0 my-auto">
								<input id="barraBusquedaLiquidacion" class="form-control" type="search" placeholder="Buscar por id cierre" aria-label="Buscar por id cierre">
							</div>
						</div>
						<?php require 'components/officesbanking/views/components/tables/CierreLiquidaciones.php' ?>

					</div>
				</div>
				<div class="tab-pane fade" id="cierre-liquidaciones-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
					<div class="content content-page">
						<h1>Cierre Liquidaciones</h1>
						<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModalListadoPropiedeades" onclick="CargarListadoPropiedades()"> Nuevo Cierre. </button>

					</div>
				</div>
				<div class="tab-pane fade" id="documentos-liquidaciones-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
					<div class="content content-page">
						<!-- El contenido dinámico de la tabla será cargado aquí por JavaScript -->
					</div>
				</div>
			</div>

		</fieldset>

	</div>