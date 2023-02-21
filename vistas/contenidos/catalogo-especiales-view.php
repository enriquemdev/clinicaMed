<?php 
        $auxiliar = new loginControlador();
        $listaVistas = $auxiliar -> navLateral_controlador();

    if( !(in_array("Catalogos", $listaVistas)) ){
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }

?>

<!-- Page header -->
<div class="full-box page-header">
                <h3 class="text-left">
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; CATALOGO ESPECIALIDADES
                </h3>
            </div>
            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr class="text-center roboto-medium">
                                <th>ID</th>
                                <th>Nombre ESPECIALIDAD</th>
                                <th>FECHA DE REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" >
                                <td>1</td>
                                <td>NOMBRE ESPECIALIDAD</td>
                                <td>2017/10/8</td>
                                
                            </tr>
                            <tr class="text-center" >
                                <td>2</td>
                                <td>NOMBRE ESPECIALIDAD</td>
                                <td>2017/10/8</td>
                                
                            </tr>
                            <tr class="text-center" >
                                <td>3</td>
                                <td>NOMBRE ESPECIALIDAD</td>
                                <td>2017/10/8</td>
                                
                            </tr>
                            <tr class="text-center" >
                                <td>4</td>
                                <td>NOMBRE ESPECIALIDAD</td>
                                <td>2017/10/8</td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
					<form action="" class="form-neon" autocomplete="off">
						<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; AGREGAR ESPECIALIDAD</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="ESPECIALIDAD-ID" class="bmd-label-floating">ID</label>
										<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="ESPECIALIDAD-ID_reg" id="ESPECIALIDAD-ID" maxlength="40">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="ESPECIALIDAD-NOMBRE" class="bmd-label-floating">NOMBRE DE ESPECIACLIDAD</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="ESPECIALIDAD-NOMBRE_reg" id="ESPECIALIDAD-NOMBRE" maxlength="40">
									</div>
							    </div>
						   </div>
						</div>
						<br><br><br>
					<p class="text-center" style="margin-top: 40px;">
						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
					</p>
					</fieldset>
					</form>
				</div>
					
            </div>
