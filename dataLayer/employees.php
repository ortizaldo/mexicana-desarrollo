<?php include("header.php") ?>

	<!-- Codigo -->
	<div class="row">
		<div class="col-lg-12" style="margin-bottom:20px;">
			<button type="button" id="btnAddUser" class="btn btn-round btn-lg btn-success"><i class="fa fa-plus"></i> Agregar Usuario </button>
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
							<th><i class="fa fa-bookmark-o"></i> Id</th>
							<th><i class="fa fa-building"></i> Compañía</th>
							<th class="hidden-xs"><i class="fa fa-user"></i>nickname</th>
							<th class="hidden-xs"><i class="fa fa-user"></i>Nombre (nombre + Appellido)</th>

							<th class="hidden-xs"><i class="fa fa-user"></i> email</th>
							<th class="hidden-xs"><i class="fa fa-user"></i> phoneNumber</th>
							<th class="hidden-xs"><i class="fa fa-user"></i> rol</th>

							<th><i class="fa fa-bar-chart-o"></i> status</th>
							<th><i class="fa fa-line-chart"></i> Acción</th>
						</tr>
					</thead>
					<tbody id="bodyData">
						<tr>
							<td><a href="#">1</a></td>
							<td class="hidden-xs">Migesa</td>
							<td>Alejandro </td>
							<td><span class="label label-success label-mini">Activo</span></td>
							<td class="hidden-xs">
								<button type="button" class="btnEditUser btn btn-success btn-xs"><i class="fa fa-pencil"></i></button>
								<button type="button" class="btnDeleteUser btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
							</td>
						</tr>
						<tr>
							<td><a href="#">2</a></td>
							<td class="hidden-xs">Mexicana de gas</td>
							<td>sajxasj</td>
							<td><span class="label label-danger label-mini">Cancelado</span></td>
							<td class="hidden-xs">
								<button type="button" class="btnEditUser btn btn-success btn-xs"><i class="fa fa-pencil"></i></button>
								<button type="button" class="btnDeleteUser btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
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
					<h4 class="modal-title" id="titleCompany">Agregar Usuario</h4>
				</div>
				<div class="modal-body">
					<form class="cmxform" role="form" id="formNewUser">
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" id="txtName" name="txtName" class="form-control">
						</div>
						<div class="form-group">
							<label>Correo</label>
							<input type="text" id="txtMail" name="txtMail" class="form-control">
						</div>
						<div class="form-group">
							<label>Compañía</label>
							<select class="form-control" id="txtCompany">
																					<option value="Mexicana">Mexicana de gas</option>
																					<option value="text">Texto</option>                                        
																			</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type=button id="btnCancel" class="btn btn-danger">Cancelar</button>
					<button type=button id="btnAdd" class="btn btn-success">Agregar</button>
				</div>
			</div>
			<!-- modal content-->
		</div>
		<!-- modal-dialog-->
	</div>
	<!-- /.modal-->

	<div class="modal fade disable-scroll" id="modalDelete" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Cancelar usuario</h4>
				</div>
				<div class="modal-body">
					<form role="form">
						<div class="form-group">
							<label>¿Esta seguro de cancelar este usuario?</label>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type=button id="btnCancelUser" class="btn btn-danger">Sí, cancelar</button>
				</div>
			</div>
			<!-- modal content-->
		</div>
		<!-- modal-dialog-->
	</div>
	<!-- /.modal-->

<?php include("footer.php") ?>

<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
			
			//console.log($("#user_nickname").html());
			
			var string_nickname = $("#user_nickname").html();
			string_nickname = string_nickname.substring(0, string_nickname.indexOf('<'));
			//console.log(string_nickname.trim());
			
			$("#titleHeader").html("Usuarios");
			$("#subtitle-header").html("Detalle de mis Usuarios");
			
			var doSomeAjax = $.ajax({
					method: "POST",
					url: "/newProjectStructure/dataLayer/test.php",
					data: { nickname: string_nickname },
					dataType: "JSON",
					/*success: function( result ) {
						//$( "#weather-temp" ).html( "<strong>" + result + "</strong> degrees" );
						//alert( "Return: " + result );
						console.log(result.code);
						console.log(result.elems);
					}*/
				});
											
			doSomeAjax.done(function( msg ) {
				console.log(msg.code);
				console.log(msg.elems);
			});

			doSomeAjax.fail(function( jqXHR, textStatus ) {
				alert( "Request failed: " + textStatus );
			});

			//$(".limenu").removeClass("active")
			//$("#gouser").addClass("active")

			$('#newform').click(function() {
				$('#modalform').modal('show');
			});

			var rowPick;

			$('#btnAddUser').click(function() {
				resetValidation();
				$('#modalform').modal('show');
				$('#titleCompany').html('Agregar Usuario');
				$('#btnAdd').html('Agregar');
				cleanFields();
			});


			$('#btnAdd').click(function() {
				resetValidation()
				var xName = $('#txtName').val()
				var xMail = $('#txtMail').val()
				var xCompany = $('#txtCompany').val()
				createRow(xName, xMail, xCompany)
			});

			$(document.body).on("click", ".btnEditUser", function() {
				rowPick = $(this).parent().parent()
				resetValidation()
				cleanFields()
				$('#modalform').modal('show')
				$('#titleCompany').html('Editar Usuario')
				$('#btnAdd').html('Guardar');
			});

			$('#btnCancel').click(function() {
				$('#modalform').modal('hide')
			});

			$(document.body).on("click", ".btnDeleteUser", function() {
				rowPick = $(this).parent().parent()
				$('#modalDelete').modal('show')
			});

			$('#btnCancelUser').click(function() {
				$('#modalDelete').modal('hide')
				rowPick.remove()
				rowPick = '';
			});

			$('#btnModalFormClose').click(function() {
				cleanFields();
			});

		});

		function createRow(name, mail, company) {
			if ($('#formNewUser').valid()) {
				console.log(name)
				console.log(mail)
				console.log(company)
				var xrow = ''
				xrow += '<tr>'
				xrow += '<td><a href="#">1</a></td>'
				xrow += '<td class="hidden-xs">' + company + '</td>'
				xrow += '<td>' + name + '</td>'
				xrow += '<td><span class="label label-success label-mini">Activo</span></td>'
				xrow += '<td class="hidden-xs"><button type="button" class="btnEditUser btn btn-success btn-xs"><i class="fa fa-pencil"></i></button> <button type="button" class="btnDeleteUser btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>'
				xrow += '</td>'
				xrow += '</tr>'

				console.log(xrow)

				$("#bodyData").append(xrow)
				$('#modalform').modal('hide')
				cleanFields()
			}
		}

		function cleanFields() {
			$("#txtName").val('')
			$("#txtMail").val('')
		}

		function resetValidation() {
			form_user.resetForm();
		}

		var form_user = $("#formNewUser").validate({
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

</script>

</script>