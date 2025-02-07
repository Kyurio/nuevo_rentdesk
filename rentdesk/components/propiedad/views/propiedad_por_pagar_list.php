<script>
    loadPropiedad();
</script>
<div id="header" class="header-page">
    <div>
        <!-- <h2 class="mb-3">Propiedades</h2> -->
        <div style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb d-flex align-items-center m-0">
                <li class="breadcrumb-item"><a href="index.php?component=dashboard&view=dashboard" style="text-decoration: none;color:#66615b"><span>Inicio</span></a></li>
                <li class="breadcrumb-item" style="color: #e62238" aria-current="page"><a href="index.php?component=propiedad&view=propiedad_list" style="text-decoration: none;color:#66615b">Propiedades</a></li>
                <li class="breadcrumb-item active" style="font-weight:600;color: #e62238" aria-current="page">Propiedades por Liquidar</li>
            </ol>
        </div>
    </div>

</div>

<div class="content content-page" >

    <!-- <div class="d-flex justify-content-end">

        <div class="card">
            <div class="card-body"> <a href='index.php?component=propiedad&view=propiedad' style="justify-content: center;
display: inline-flex;
align-items: center;
padding: 0;
gap: 0.5rem;
text-decoration: none;"><i class="fa fa-plus-square fa-2x" aria-hidden="true"></i>
                    <span style="font-size:1rem;">Agregar una Propiedad</span>
                </a></div>
        </div>
    </div> -->


    <!-- 
    <div class="row top-100">
        <div class="col p-0">
            <form class="my-3">
                <fieldset class="form-group border p-3">
                    <legend>
                        <h5 class="mt-0" style="font-size:14px !important;margin-bottom:5px !important;">Criterios de Búsqueda <small>(Debe ingresar al menos un campo)</small></h5>
                    </legend>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código de Propiedad</label>
                                <input type="text" class="form-control" id="cod_propiedad" name="cod_propiedad" value="" placeholder="Ingrese Código">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" maxlength="250" name="direccion" id="direccion" placeholder="Dirección" required data-validation-required value="<?php echo @$result->direccion; ?>" onBlur="elimina_slash(this);elimina_comillas(this);elimina_blancos_inicio_fin(this);">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="oficina">Oficina</label>
                                <select name="oficina" id="oficina" class="form-control">
                                    <option value="CENTRO-MONEDA" id="89">CENTRO-MONEDA</option>
                                    <option value="NUEVA LAS CONDES" id="127">NUEVA LAS CONDES</option>
                                    <option value="LA SERENA" id="128">LA SERENA</option>
                                    <option value="PROVIDENCIA" id="129">PROVIDENCIA</option>
                                    <option value="REÑACA" id="130">REÑACA</option>
                                    <option value="SAN MIGUEL" id="131">SAN MIGUEL</option>
                                    <option value="ADMINISTRACIONES" id="132">ADMINISTRACIONES</option>
                                    <option value="MAIPU PLAZA" id="133">MAIPU PLAZA</option>
                                    <option value="SANTIAGO-CENTRO" id="134">SANTIAGO-CENTRO</option>
                                    <option value="OFICINA PLAN B (SEGURO)" id="135">OFICINA PLAN B (SEGURO)</option>
                                    <option value="MAIPU PAJARITOS" id="136">MAIPU PAJARITOS</option>
                                    <option value="TALAGANTE" id="137">TALAGANTE</option>
                                    <option value="PLAZA EGAÑA" id="138">PLAZA EGAÑA</option>
                                    <option value="ROSARIO SUR" id="139">ROSARIO SUR</option>
                                    <option value="LA FLORIDA" id="140">LA FLORIDA</option>
                                    <option value="ISABEL LA CATOLICA" id="141">ISABEL LA CATOLICA</option>
                                    <option value="VITACURA" id="142">VITACURA</option>
                                    <option value="VICUÑA MACKENNA" id="143">VICUÑA MACKENNA</option>
                                    <option value="LA REINA" id="144">LA REINA</option>
                                    <option value="PUENTE ALTO" id="145">PUENTE ALTO</option>
                                    <option value="NULL" id="146">NULL</option>
                                    <option value="BULNES" id="147">BULNES</option>
                                    <option value="ÑUÑOA" id="148">ÑUÑOA</option>
                                    <option value="APOQUINDO" id="149">APOQUINDO</option>
                                    <option value="LOS DOMINICOS" id="150">LOS DOMINICOS</option>
                                    <option value="CONCEPCION" id="151">CONCEPCION</option>
                                    <option value="NUEVA COSTANERA" id="152">NUEVA COSTANERA</option>
                                    <option value="LAS CONDES" id="153">LAS CONDES</option>
                                    <option value="LAS TRANQUERAS" id="154">LAS TRANQUERAS</option>
                                    <option value="ANA MARIA DUQUE" id="155">ANA MARIA DUQUE</option>
                                    <option value="PEÑALOLEN" id="156">PEÑALOLEN</option>
                                    <option value="ESCUELA MILITAR" id="157">ESCUELA MILITAR</option>
                                    <option value="LA DEHESA" id="158">LA DEHESA</option>
                                    <option value="E-MAIL" id="159">E-MAIL</option>
                                    <option value="TABANCURA" id="160">TABANCURA</option>
                                    <option value="LOS MILITARES" id="161">LOS MILITARES</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> RUT</label>
                                <input type="text" class="form-control" id="rut" name="rut" value="" placeholder="Ingrese Rut">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="" placeholder="Ingrese Nombre">
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <button type="button" class="btn btn-primary">Buscar</button>
                        </div>

                    </div>
                </fieldset>
            </form>
            <div class="row">
                <button class="btn btn-info btn-mas-filtros" style="width:auto; text-align:left;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Filtros <i class="fas fa-chevron-down"></i></button>
                <div class="collapse col-12 col-md-12 col-lg-12 p-0" id="collapseExample">
                    <form>
                        <fieldset class="form-group border p-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo Propiedad</label>
                                        <?php echo $opcion_tipo_propiedad; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Región</label>
                                        <select name="regiones" id="regiones" class="form-control">
                                            <option value="15">METROPOLITANA</option>
                                            <option value="1">ARICA Y PARINACOTA</option>
                                            <option value="2">TARAPACÁ</option>
                                            <option value="3">ANTOFAGASTA</option>
                                            <option value="4">ATACAMA </option>
                                            <option value="5">COQUIMBO </option>
                                            <option value="6">VALPARAÍSO </option>
                                            <option value="7">DEL LIBERTADOR GRAL. BERNARDO O HIGGINS</option>
                                            <option value="8">DEL MAULE</option>
                                            <option value="17">ÑUBLE</option>
                                            <option value="9">DEL BIOBÍO</option>
                                            <option value="10">DE LA ARAUCANÍA</option>
                                            <option value="11">DE LOS RÍOS</option>
                                            <option value="12">DE LOS LAGOS</option>
                                            <option value="13">AISÉN DEL GRAL. CARLOS IBAÑEZ DEL CAMPO</option>
                                            <option value="14">MAGALLANES Y DE LA ANTÁRTICA CHILENA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <label>Comuna</label>
                                    <div class="form-group" id="divcomuna" name="divcomuna">
                                        <select name="comunas" id="comunas" class="form-control">
                                            <option value="">Seleccione</option>
                                            <optgroup label="METROPOLITANA">
                                                <option value="779">Buin</option>
                                                <option value="780">Calera de Tango</option>
                                                <option value="47">Cerrillos</option>
                                                <option value="55">Cerro Navia</option>
                                                <option value="772">Colina (Chicureo)</option>
                                                <option value="145">Conchalí</option>
                                                <option value="784">Curacaví</option>
                                                <option value="155">El Bosque</option>
                                                <option value="786">El Monte</option>
                                                <option value="194">Estación Central</option>
                                                <option value="203">Huechuraba</option>
                                                <option value="315">Independencia</option>
                                                <option value="787">Isla de Maipo</option>
                                                <option value="374">La Cisterna</option>
                                                <option value="419">La Florida</option>
                                                <option value="422">La Granja</option>
                                                <option value="773">Lampa</option>
                                                <option value="423">La Pintana</option>
                                                <option value="424">La Reina</option>
                                                <option value="425">Las Condes</option>
                                                <option value="426">Lo Barnechea (La Dehesa)</option>
                                                <option value="427">Lo Espejo</option>
                                                <option value="428">Lo Prado</option>
                                                <option value="429">Macul</option>
                                                <option value="430">Maipú</option>
                                                <option value="720">María Pinto</option>
                                                <option value="247">Melipilla</option>
                                                <option value="431">Ñuñoa</option>
                                                <option value="785">Padre Hurtado</option>
                                                <option value="783">Paine</option>
                                                <option value="730">Pedro Aguirre Cerda</option>
                                                <option value="788">Peñaflor</option>
                                                <option value="731">Peñalolén</option>
                                                <option value="775">Pirque</option>
                                                <option value="732">Providencia</option>
                                                <option value="733">Pudahuel</option>
                                                <option value="777">Puente Alto</option>
                                                <option value="734">Quilicura</option>
                                                <option value="735">Quinta Normal</option>
                                                <option value="736">Recoleta</option>
                                                <option value="737">Renca</option>
                                                <option value="778">San Bernardo</option>
                                                <option value="738">San Joaquín</option>
                                                <option value="776">San José de Maipo</option>
                                                <option value="739">San Miguel</option>
                                                <option value="721">San Pedro</option>
                                                <option value="740">San Ramón</option>
                                                <option value="26">Santiago</option>
                                                <option value="789">Talagante</option>
                                                <option value="774">Tiltil</option>
                                                <option value="741">Vitacura</option>
                                            </optgroup>
                                            <optgroup label="ARICA Y PARINACOTA">
                                                <option value="439">Arica</option>
                                                <option value="440">Camarones</option>
                                                <option value="442">General Lagos</option>
                                                <option value="441">Putre</option>
                                            </optgroup>
                                            <optgroup label="TARAPACÁ">
                                                <option value="433">Alto Hospicio</option>
                                                <option value="444">Camiña</option>
                                                <option value="445">Colchane</option>
                                                <option value="446">Huara</option>
                                                <option value="432">Iquique</option>
                                                <option value="443">Pozo Almonte</option>
                                            </optgroup>
                                            <optgroup label="ANTOFAGASTA">
                                                <option value="436">Antofagasta</option>
                                                <option value="807">Calama</option>
                                                <option value="454">María Elena</option>
                                                <option value="437">Mejillones</option>
                                                <option value="850">Ollahue</option>
                                                <option value="452">San Pedro de Atacama</option>
                                                <option value="448">Sierra Gorda</option>
                                                <option value="449">Taltal</option>
                                                <option value="453">Tocopilla</option>
                                            </optgroup>
                                            <optgroup label="ATACAMA ">
                                                <option value="460">Alto del Carmen</option>
                                                <option value="455">Caldera</option>
                                                <option value="457">Chañaral</option>
                                                <option value="421">Copiapó</option>
                                                <option value="458">Diego de Almagro</option>
                                                <option value="461">Freirina</option>
                                                <option value="462">Huasco</option>
                                                <option value="456">Tierra Amarilla</option>
                                                <option value="804">Vallenar</option>
                                            </optgroup>
                                            <optgroup label="COQUIMBO ">
                                                <option value="465">Andacollo</option>
                                                <option value="470">Canela</option>
                                                <option value="474">Combarbalá</option>
                                                <option value="747">Coquimbo</option>
                                                <option value="748">Illapel</option>
                                                <option value="466">La Higuera</option>
                                                <option value="746">La Serena</option>
                                                <option value="749">Los Vilos</option>
                                                <option value="752">Ovalle</option>
                                                <option value="467">Paihuano</option>
                                                <option value="476">Punitaqui</option>
                                                <option value="750">Salamanca</option>
                                                <option value="751">Vicuña</option>
                                            </optgroup>
                                            <optgroup label="VALPARAÍSO ">
                                                <option value="753">Algarrobo</option>
                                                <option value="496">Cabildo</option>
                                                <option value="754">Calera</option>
                                                <option value="492">Calle Larga</option>
                                                <option value="755">Cartagena</option>
                                                <option value="756">Casablanca</option>
                                                <option value="506">Catemu</option>
                                                <option value="757">Concón</option>
                                                <option value="758">El Quisco</option>
                                                <option value="759">El Tabo</option>
                                                <option value="760">Hijuelas</option>
                                                <option value="490">Isla de Pascua</option>
                                                <option value="486">Juan Fernández</option>
                                                <option value="503">La Cruz</option>
                                                <option value="761">La Ligua</option>
                                                <option value="762">Limache</option>
                                                <option value="763">Llaillay</option>
                                                <option value="491">Los Andes</option>
                                                <option value="764">Nogales</option>
                                                <option value="765">Olmué</option>
                                                <option value="508">Panquehue</option>
                                                <option value="766">Papudo</option>
                                                <option value="498">Petorca</option>
                                                <option value="796">Puchuncavi.</option>
                                                <option value="487">Puchuncaví</option>
                                                <option value="767">Putaendo</option>
                                                <option value="500">Quillota</option>
                                                <option value="791">Quilpué</option>
                                                <option value="768">Quintero</option>
                                                <option value="493">Rinconada</option>
                                                <option value="2">San Antonio</option>
                                                <option value="494">San Esteban</option>
                                                <option value="505">San Felipe</option>
                                                <option value="510">Santa María</option>
                                                <option value="769">Santo Domingo</option>
                                                <option value="483">Valparaíso</option>
                                                <option value="770">Villa Alemana</option>
                                                <option value="743">Viña del Mar</option>
                                                <option value="771">Zapallar</option>
                                            </optgroup>
                                            <optgroup label="DEL LIBERTADOR GRAL. BERNARDO O HIGGINS">
                                                <option value="812">Chépica</option>
                                                <option value="834">Chimbarongo</option>
                                                <option value="849">Codegua</option>
                                                <option value="790">Coinco</option>
                                                <option value="848">Coltauco</option>
                                                <option value="847">Doñihue</option>
                                                <option value="843">El Olivar</option>
                                                <option value="818">Graneros</option>
                                                <option value="837">La Estrella</option>
                                                <option value="809">Las Cabras</option>
                                                <option value="814">Litueche</option>
                                                <option value="833">Lolol</option>
                                                <option value="810">Machalí</option>
                                                <option value="845">Malloa</option>
                                                <option value="836">Marchihue</option>
                                                <option value="844">Mostazal</option>
                                                <option value="832">Nancagua</option>
                                                <option value="816">Navidad</option>
                                                <option value="831">Palmilla</option>
                                                <option value="835">Paredones</option>
                                                <option value="830">Peralillo</option>
                                                <option value="842">Peumo</option>
                                                <option value="806">Pichidegua</option>
                                                <option value="808">Pichilemu</option>
                                                <option value="829">Placilla</option>
                                                <option value="828">Pumanque</option>
                                                <option value="841">Quinta De Tilcoco</option>
                                                <option value="801">Rancagua</option>
                                                <option value="820">Rengo</option>
                                                <option value="839">Requinoa</option>
                                                <option value="817">San Fco. Mostazal</option>
                                                <option value="793">San Fernando</option>
                                                <option value="815">Santa Cruz</option>
                                                <option value="838">San Vicente</option>
                                            </optgroup>
                                            <optgroup label="DEL MAULE">
                                                <option value="558">Cauquenes</option>
                                                <option value="559">Chanco</option>
                                                <option value="571">Colbún</option>
                                                <option value="549">Constitución</option>
                                                <option value="550">Curepto</option>
                                                <option value="561">Curicó</option>
                                                <option value="551">Empedrado</option>
                                                <option value="562">Hualañe</option>
                                                <option value="563">Licantén</option>
                                                <option value="570">Linares</option>
                                                <option value="572">Longaví</option>
                                                <option value="552">Maule</option>
                                                <option value="564">Molina</option>
                                                <option value="573">Parral</option>
                                                <option value="553">Pelarco</option>
                                                <option value="560">Pelluhue</option>
                                                <option value="554">Pencahue</option>
                                                <option value="565">Rauco</option>
                                                <option value="574">Retiro</option>
                                                <option value="555">Río Claro</option>
                                                <option value="566">Romeral</option>
                                                <option value="567">Sagrada Familia</option>
                                                <option value="556">San Clemente</option>
                                                <option value="575">San Javier</option>
                                                <option value="557">San Rafael</option>
                                                <option value="548">Talca</option>
                                                <option value="568">Teno</option>
                                                <option value="852">Vichuquen</option>
                                                <option value="576">Villa Alegre</option>
                                                <option value="577">Yerbas Buenas</option>
                                            </optgroup>
                                            <optgroup label="ÑUBLE">
                                                <option value="604">Bulnes</option>
                                                <option value="792">Chillán</option>
                                                <option value="608">Chillán Viejo</option>
                                                <option value="605">Cobquecura</option>
                                                <option value="606">Coelemu</option>
                                                <option value="607">Coihueco</option>
                                                <option value="609">El Carmen</option>
                                                <option value="610">Ninhue</option>
                                                <option value="611">Ñiquén</option>
                                                <option value="612">Pemuco</option>
                                                <option value="613">Pinto</option>
                                                <option value="614">Portezuelo</option>
                                                <option value="615">Quillón</option>
                                                <option value="616">Quirihue</option>
                                                <option value="617">Ranquil</option>
                                                <option value="618">San Carlos</option>
                                                <option value="619">San Fabian</option>
                                                <option value="620">San Ignacio</option>
                                                <option value="621">San Nicolás</option>
                                                <option value="622">Treguaco</option>
                                                <option value="623">Yungay</option>
                                            </optgroup>
                                            <optgroup label="DEL BIOBÍO">
                                                <option value="602">Alto Biobío</option>
                                                <option value="590">Antuco</option>
                                                <option value="826">Arauco</option>
                                                <option value="591">Cabrero</option>
                                                <option value="825">Cañete</option>
                                                <option value="580">Chiguayante</option>
                                                <option value="578">Concepción</option>
                                                <option value="824">Contulmo</option>
                                                <option value="579">Coronel</option>
                                                <option value="823">Curanilahue</option>
                                                <option value="581">Florida</option>
                                                <option value="589">Hualpén</option>
                                                <option value="582">Hualqui</option>
                                                <option value="592">Laja</option>
                                                <option value="827">Lebu</option>
                                                <option value="822">Los Alamos</option>
                                                <option value="438">Los Angeles</option>
                                                <option value="583">Lota</option>
                                                <option value="593">Mulchén</option>
                                                <option value="594">Nacimiento</option>
                                                <option value="595">Negrete</option>
                                                <option value="584">Penco</option>
                                                <option value="596">Quilaco</option>
                                                <option value="597">Quilleco</option>
                                                <option value="585">San Pedro de la Paz</option>
                                                <option value="598">San Rosendo</option>
                                                <option value="599">Santa Bárbara</option>
                                                <option value="586">Santa Juana</option>
                                                <option value="587">Talcahuano</option>
                                                <option value="821">Tirua</option>
                                                <option value="588">Tomé</option>
                                                <option value="600">Tucapel</option>
                                                <option value="601">Yumbel</option>
                                            </optgroup>
                                            <optgroup label="DE LA ARAUCANÍA">
                                                <option value="645">Angol</option>
                                                <option value="625">Carahue</option>
                                                <option value="644">Cholchol</option>
                                                <option value="646">Collipulli</option>
                                                <option value="626">Cunco</option>
                                                <option value="647">Curacautín</option>
                                                <option value="627">Curarrehue</option>
                                                <option value="648">Ercilla</option>
                                                <option value="628">Freire</option>
                                                <option value="629">Galvarino</option>
                                                <option value="630">Gorbea</option>
                                                <option value="631">Lautaro</option>
                                                <option value="795">Loncoche</option>
                                                <option value="649">Lonquimay</option>
                                                <option value="650">Los Sauces</option>
                                                <option value="651">Lumaco</option>
                                                <option value="633">Melipeuco</option>
                                                <option value="634">Nueva Imperial</option>
                                                <option value="635">Padre Las Casas</option>
                                                <option value="636">Perquenco</option>
                                                <option value="637">Pitrufquén</option>
                                                <option value="803">Pucón</option>
                                                <option value="652">Puren</option>
                                                <option value="653">Renaico</option>
                                                <option value="639">Saavedra</option>
                                                <option value="794">Temuco</option>
                                                <option value="640">Teodoro Schmidt</option>
                                                <option value="641">Toltén</option>
                                                <option value="654">Traiguén</option>
                                                <option value="655">Victoria</option>
                                                <option value="642">Vilcún</option>
                                                <option value="802">Villarrica</option>
                                            </optgroup>
                                            <optgroup label="DE LOS RÍOS">
                                                <option value="657">Corral</option>
                                                <option value="665">Futrono</option>
                                                <option value="666">Lago Ranco</option>
                                                <option value="658">Lanco</option>
                                                <option value="664">La Unión</option>
                                                <option value="659">Los Lagos</option>
                                                <option value="660">Máfil</option>
                                                <option value="819">Mantilhue</option>
                                                <option value="661">Mariquina</option>
                                                <option value="662">Paillaco</option>
                                                <option value="663">Panguipulli</option>
                                                <option value="667">Río Bueno</option>
                                                <option value="656">Valdivia</option>
                                            </optgroup>
                                            <optgroup label="DE LOS LAGOS">
                                                <option value="678">Ancud</option>
                                                <option value="669">Calbuco</option>
                                                <option value="677">Castro</option>
                                                <option value="694">Chaitén</option>
                                                <option value="813">Chonchi</option>
                                                <option value="679">Chonchi (Chiloe)</option>
                                                <option value="670">Cochamó</option>
                                                <option value="680">Curaco de Vélez</option>
                                                <option value="681">Dalcahue</option>
                                                <option value="671">Fresia</option>
                                                <option value="672">Frutillar</option>
                                                <option value="695">Futaleufú</option>
                                                <option value="696">Hualaihué</option>
                                                <option value="674">Llanquihue</option>
                                                <option value="673">Los Muermos</option>
                                                <option value="675">Maullín</option>
                                                <option value="687">Osorno</option>
                                                <option value="697">Palena</option>
                                                <option value="668">Puerto Montt</option>
                                                <option value="688">Puerto Octay</option>
                                                <option value="676">Puerto Varas</option>
                                                <option value="682">Puqueldón</option>
                                                <option value="689">Purranque</option>
                                                <option value="690">Puyehue</option>
                                                <option value="683">Queilén</option>
                                                <option value="684">Quellón</option>
                                                <option value="685">Quemchi</option>
                                                <option value="686">Quinchao</option>
                                                <option value="691">Río Negro</option>
                                                <option value="692">San Juan de la Costa</option>
                                                <option value="693">San Pablo</option>
                                            </optgroup>
                                            <optgroup label="AISÉN DEL GRAL. CARLOS IBAÑEZ DEL CAMPO">
                                                <option value="698">Aisén</option>
                                                <option value="703">Chile Chico</option>
                                                <option value="79">Cisnes</option>
                                                <option value="700">Cochrane</option>
                                                <option value="123">Coihaique</option>
                                                <option value="699">Guaitecas</option>
                                                <option value="152">Lago Verde</option>
                                                <option value="701">OHiggins</option>
                                                <option value="704">Río Ibáñez</option>
                                                <option value="702">Tortel</option>
                                            </optgroup>
                                            <optgroup label="MAGALLANES Y DE LA ANTÁRTICA CHILENA">
                                                <option value="435">Antártica</option>
                                                <option value="434">Cabo de Hornos (Ex-Navarino)</option>
                                                <option value="706">Laguna Blanca</option>
                                                <option value="709">Porvenir</option>
                                                <option value="710">Primavera</option>
                                                <option value="712">Puerto Natales</option>
                                                <option value="705">Punta Arenas</option>
                                                <option value="707">Río Verde</option>
                                                <option value="708">San Gregorio</option>
                                                <option value="711">Timaukel</option>
                                                <option value="713">Torres del Paine</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sucursal">Sucursal</label>
                                        <select name="sucursal" id="sucursal" class="form-control">
                                            <option value="SUCURSAL 1" id="89">SUCURSAL 1</option>
                                            <option value="SUCURSAL 2" id="127">SUCURSAL 2</option>
                                            <option value="SUCURSAL 3" id="128">SUCURSAL 3</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div id="resultado" name="resultado" style="width:100%;"></div>
    </div>


    <div class="herramientas">
        <button type="button" class="btn btn-info btn-sm" onClick="document.location.reload();">
            <span class="glyphicon glyphicon-refresh"></span> Recargar Datos
        </button>
    </div>


    <div class="row">

        <p><strong>Total por Pagar:</strong> $1.113.814</p>
        <div style="flex-flow: wrap;gap: 0.5rem" class="d-flex mb-4">
            <a href="https://adm.controlpropiedades.cl/staff/workbooks/rent_movements/39247" target="_blank" type="button" class="btn btn-default btn-outline-primary btn-sm pull-right m-0" style="padding: .5rem;white-space: nowrap;" title="Ingresar Descuento">
                <span>Descargar Resumen</span>
            </a>

        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive overflow-auto">
                <table id="propiedades" class="table table-striped" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Dirección</th>
                            <th>Propietario</th>
                            <th>Saldo Última Liquidación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($dataTablePropiedadesPorPagar as $row) : ?>
                            <tr>
                                <?php foreach ($row as $key => $cell) : ?>

                                    <?php if (in_array($key, [1]) && (isset($cell) && $cell !== "-")) : ?>
                                        <td><a href="index.php?component=propietario&view=propietario_ficha_tecnica" class="link-info"><?php echo $cell; ?></a></td>

                                    <?php else : ?>
                                        <td><?php echo $cell; ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <!-- <td>
                                    <a href="index.php?component=propiedad&view=propiedad_ficha_tecnica" type="button" class="btn btn-info m-0" style="padding: .5rem;" title="Ver Ficha Técnica">
                                        <i class="fa-solid fa-magnifying-glass" style="font-size: .75rem;"></i>
                                    </a>
                                </td> -->
                                <td>
                                    -
                                    <!-- <div class="d-flex" style="gap: .5rem;">
                                        <a href="index.php?component=propiedad&view=propiedad" type="button" class="btn btn-info m-0" style="padding: .5rem;" aria-label="Editar" title="Editar">
                                            <i class="fa-regular fa-pen-to-square" style="font-size: .75rem;"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger m-0" style="padding: .5rem;" title="Eliminar">
                                            <i class="fa-regular fa-trash-can" style="font-size: .75rem;"></i>
                                        </button>
                                    </div> -->
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>