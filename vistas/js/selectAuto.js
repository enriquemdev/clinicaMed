$(function () {
    $('.search-select-box select').selectpicker();
});


/*
<td class="col-12 col-md-6">
								  <label for="medicamento" class="bmd-label-static">Medicamento<span class="Obligar">*</span></label>
								  <!-- form-group -->
									<div class="search-select-box">
										<!-- form-control -->
										<!-- <select  pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,70}" class="" data-live-search="true" name="medicamento_reg[]" id="medicamento" maxlength="100">
											<option value="">1</option>
											<option value="">2</option>
											
										</select> -->
										<select class="w-100" data-live-search="true" name="codigo_medicamento_reg" id="codigo_medicamento">
											<option class="w-100" value="" selected="" disabled=""></option>
											<?php foreach($datos_item[0] as $campo){ ?>
											<option class="w-100" value="<?php echo$campo['Codigo'] ?>"><?php echo$campo['NombreComercial']." - ".$campo['Presentacion'] ?></option>
											<?php }?>
										</select>
									</div>
					  			</td>*/