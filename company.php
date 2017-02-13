<?php include("header.php") ?>
	<!-- Ya valimos -->
<!-- Codigo -->
			<div class="row">
				<div class="col-lg-12" style="margin-bottom:20px;">
					<button type="button" id="btnAddCompany" class="btn btn-round btn-lg btn-success"><i class="fa fa-plus"></i> Agregar Compañía </button>
				</div>
			</div>

            <div class="row">
            <div class="col-lg-12">
            <section class="panel">
            <header class="panel-heading head-border">
                Custom Table
            </header>
            <table class="table table-striped custom-table table-hover">
                <thead>
                <tr>
                    <th><i class="fa fa-bookmark-o"></i>Id</th>
                    <th><i class="fa fa-building"></i> Compañía</th>
                    <th><i class=" fa fa-edit"></i> Estatus</th>
                    <th class="hidden-xs"><i class="fa fa-cogs"></i> Acción</th>
                </tr>
                </thead>
                <tbody id="bodyData">
                <tr>
                    <td><a href="#">1</a></td>
                    <td class="hidden-xs">Migesa</td>
                    <td><span class="label label-success label-mini">Activo</span></td>    
                    <td class="hidden-xs">
                        <button type="button" class="btnEditCompany btn btn-success btn-xs"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btnDeleteCompany btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                    </td>
                </tr>
                <tr>
                    <td><a href="#">2</a></td>
                    <td class="hidden-xs">Mexicana de gas</td>
                    <td><span class="label label-danger label-mini">Cancelado</span></td>    
                    <td class="hidden-xs">
                        <button class="btnEditCompany btn btn-success btn-xs"><i class="fa fa-pencil"></i></button>
                        <button class="btnDeleteCompany btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
            </section>
            </div>
            </div>


	<div class="modal fade disable-scroll" id="modalform" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" id="btnModalFormClose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="titleCompany">Agregar Compañía</h4>  
          </div>
			<div class="modal-body">
                <form class="cmxform" role="form" id="formNewCompany">
                    <div class="form-group">
                        <label>Nombre</label>
                            <input type="text" id="txtName" name="txtName" class="form-control">
                        </div>
                    <div class="form-group">
                        <label>Correo</label>
                            <input type="text" id="txtMail" name="txtMail" class="form-control">
                        </div>
                </form>
            </div>       
            <div class="modal-footer">
                <button type=button id="btnCancel" class="btn btn-danger">Cancelar</button>
                <button type=button id="btnAdd" class="btn btn-success">Agregar</button>
              </div>                
          </div><!-- modal content-->
        </div><!-- modal-dialog-->
      </div><!-- /.modal-->
    

        <div class="modal fade disable-scroll" id="modalDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cancelar compañía</h4>
          </div>
            <div class="modal-body">
                            <form role="form">
                                <div class="form-group">
                                    <label>¿Esta seguro de cancelar esta compañía?</label>
                                    </div>
                            </form>
                        </div> 
               <div class="modal-footer">
                <button type=button id="btnCancelCompany" class="btn btn-danger">Sí, cancelar</button>
              </div>                   
          </div><!-- modal content-->
        </div><!-- modal-dialog-->
      </div><!-- /.modal-->
    
<?php include("footer.php") ?>

<!-- Abajo los js que incluimos -->


<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>


<script type="text/javascript">

    $(document).ready(function() {

        var rowPick;
        


    	$("#titleHeader").html("Compañía")
		$("#subtitle-header").html("Detalle de mis Compañía")    	

	    $('#btnAddCompany').click(function() {
                resetValidation()   
	            $('#modalform').modal('show')
                $('#titleCompany').html('Agregar Compañía')
                $('#btnAdd').html('Agregar')
                cleanFields()
	    });


        $('#btnAdd').click(function(){
            var xName= $('#txtName').val()
            var xMail= $('#txtMail').val()
            createRow(xName,xMail)        
        });

        $(document.body).on( "click",".btnEditCompany", function() {
          rowPick = $(this).parent().parent()
                resetValidation()
                cleanFields()
                $('#modalform').modal('show')
                $('#titleCompany').html('Editar Compañía')
                $('#btnAdd').html('Guardar');
        });

        $('#btnCancel').click(function(){
            resetValidation()   
            $('#modalform').modal('hide')
        });

        $(document.body).on("click",".btnDeleteCompany", function(){
            rowPick = $(this).parent().parent()
            $('#modalDelete').modal('show')
        })

        $('#btnCancelCompany').click(function(){
            $('#modalDelete').modal('hide')
            rowPick.remove()
            rowPick = '';
        });        

        $('#btnModalFormClose').click(function(){
            cleanFields();
        });

  

        function createRow(name,mail){

            if($('#formNewCompany').valid()){

                var xrow = ''
                    xrow += '<tr>'
                    xrow += '<td><a href="#">2</a></td>'
                    xrow += '<td class="hidden-xs">'+name+'</td>'
                    xrow += '<td><span class="label label-success label-mini">Activo</span></td>'   
                    xrow += '<td class="hidden-xs"><button type="button" class="btnEditCompany btn btn-success btn-xs"><i class="fa fa-pencil"></i></button> <button type="button" class="btnDeleteCompany btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>'
                    xrow +='</td>'
                    xrow +='</tr>'

                    $("#bodyData").append(xrow)
                    $('#modalform').modal('hide')
                    cleanFields()            

            }
        }   

        function cleanFields(){
               $("#txtName").val('')
               $("#txtMail").val('')  
                  
        }

        function resetValidation(){
            form_company.resetForm();
        }


      // validate signup form on keyup and submit
        var form_company = $("#formNewCompany").validate({
            rules: {
                txtName: {
                    required: true,
                    minlength: 4
                },
                txtMail: {
                    required: true,
                    email: true
                }
            },
            messages: {
                txtName: {
                    required: "Es necesario este campo",
                    minlength: "El nombre debera ser mayor a tres caracteres"
                },
                txtMail: "Es necesario agregar un correo valido"
            }
        });

    });

</script>


</script>