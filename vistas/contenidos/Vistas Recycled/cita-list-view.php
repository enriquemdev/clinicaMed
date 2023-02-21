<!-- Page header -->
<div class="full-box page-header">
                <h3 class="text-left">
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; LISTA DE CITAS 
                </h3>
                <p class="text-justify">
                    se mostrara todos las citas registradas 
                </p>
            </div>

            <div class="container-fluid">
                <ul class="full-box list-unstyled page-nav-tabs">
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA CITA</a>
                    </li>
                    <li>
                        <a class="active" href="<?php echo SERVERURL; ?>cita-list/"><i class="far fa-calendar-alt"></i> &nbsp; LISTA DE CITAS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-historial/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; HISTORIAL DE CITAS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR CITA</a>
                    </li>
                </ul>
            </div>

             <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr class="text-center roboto-medium">
                                <th>#</th>
                                <th>PACIENTE</th>
                                <th>FECHA DE REGISTRO</th>
                                <th>FECHA DE CITA</th>
                                <th>ESTADO</th>
                                <th>FACTURA</th>
                                <th>ACTUALIZAR</th>
                                <th>ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" >
                                <td>1</td>
                                <td>NOMBRE PACIENTE</td>
                                <td>2017/10/8</td>
                                <td>2017/10/10</td>
                                <td><span class="badge badge-danger">Pendiente</span></td>
                                <td>
                                    <a href="#" class="btn btn-info">
                                            <i class="fas fa-file-pdf"></i> 
                                    </a>
                                </td>
                                <td>
                                    <a href="cita-update.html" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i> 
                                    </a>
                                </td>
                                <td>
                                    <form action="">
                                        <button type="button" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="text-center" >
                                <td>2</td>
                                <td>NOMBRE PACIENTE</td>
                                <td>2017/10/8</td>
                                <td>2017/10/10</td>
                                <td><span class="badge badge-danger">Pendiente</span></td>
                                <td>
                                    <a href="#" class="btn btn-info">
                                            <i class="fas fa-file-pdf"></i> 
                                    </a>
                                </td>
                                <td>
                                    <a href="cita-update.html" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i> 
                                    </a>
                                </td>
                                <td>
                                    <form action="">
                                        <button type="button" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="text-center" >
                                <td>3</td>
                                <td>NOMBRE PACIENTE</td>
                                <td>2017/10/8</td>
                                <td>2017/10/10</td>
                                <td><span class="badge badge-primary">Cancelado</span></td>
                                <td>
                                    <a href="#" class="btn btn-info">
                                            <i class="fas fa-file-pdf"></i> 
                                    </a>
                                </td>
                                <td>
                                    <a href="cita-update.html" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i> 
                                    </a>
                                </td>
                                <td>
                                    <form action="">
                                        <button type="button" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="text-center" >
                                <td>4</td>
                                <td>NOMBRE PACIENTE</td>
                                <td>2017/10/8</td>
                                <td>2017/10/10</td>
                                <td><span class="badge badge-danger">Pendiente</span></td>
                                <td>
                                    <a href="#" class="btn btn-info">
                                            <i class="fas fa-file-pdf"></i> 
                                    </a>
                                </td>
                                <td>
                                    <a href="cita-update.html" class="btn btn-success">
                                            <i class="fas fa-sync-alt"></i> 
                                    </a>
                                </td>
                                <td>
                                    <form action="">
                                        <button type="button" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
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
            </div>