<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<link href='css/app.css' rel='stylesheet' type='text/css'>
	<style type="text/css">

		body{
			font-family: sans-serif;
			font-family: 'Roboto', sans-serif;
			width:100%;
			*{
				line-height: 1;
				text-transform: uppercase;
			}
		}
		.container{
			width:100%;
			padding:15px;
			display:block;
		}
		.logo-ucv{
			width:100px;
			height:auto;
			padding-left:5px;
		}
		.header h5{
			line-height:0.5;
		}
		h6{
			font-size:14px;
		}
		.list{
			margin:0px;
		}
		.list h6{
			line-height: 0.5;
		}

	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-4 col-xs-offset-5">
				<img src="http://www.ucv.ve/typo3temp/pics/6b94159b4e.png" class="logo-ucv">
			</div>
			<div class="col-xs-3">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1">
				<br>
				<div class="header">
					<h5 class="text-center">Universidad Central de Venezuela</h5>
					<h5 class="text-center">Facultad de Ciencias</h5>
				</div>
				<br><br>

				<h6><b>PARA: CONSEJO DE FACULTAD</b></h6>
				<h6><b>DE: CONSEJO DE ESCUELA DE COMPUTACION</b></h6>

				<br>

				<h6 class="text-right"><b>FECHA: //2015</b></h6>

				<hr>

				<h6 class="text-left">
					<b>ASUNTO:</b> NOMBRAMIENTO DE LA Ó EL <b>BR.</b> COMO PREPARADOR II EN LA ASIGNATURA A  PARTIR DEL <b>//2015</b> PREVIA APROBACION DE CONSEJO DE ESCUELA  EN SESION DEL DIA: <b>//2015</b>
				</h6>

				<hr>

				<h6 class="text-left"><b>DESCRIPCION:</b></h6>
				<div class="list">
					<h6>APELLIDOS: <span class="text-right"><?php echo $helper->user_lastname; ?></span></h6>
					<h6>NOMBRES: <span class="text-right"><?php echo $helper->user_name; ?></span></h6>
					<h6>CI: <span class="text-right"><?php echo $helper->user_id;?> </span></h6>
					<?php $cargo = ''?>
					<?php ($helper->type == 1 ? $cargo = 'PREPARADOR I' : ($helper->type == 2 ? $cargo = 'PREPARADOR II' : $cargo = 'AUXILIAR DOCENTE'))?>
					<h6>CARGO: <span class="text-right"><?php echo $cargo ?></span></h6>
					<?php $dedicacion = ''?>
					<?php ($helper->type == 1 ? $dedicacion = '6 HORAS' : ($helper->type == 2 ? $dedicacion = '12 HORAS' : $dedicacion = '16 HORAS'))?>
					<h6>DEDICACION:</h6>
					<h6>FECHA EFECTIVA DE NOMBRAMIENTO:</h6>
					<h6>UNIDAD EJECUTORA PROGRAMA:</h6>
					<h6>DISPONIBILIDAD PRESUPUESTARIA:</h6>
				</div>

				<hr>

				<h6><b>ANEXOS</b></h6>
				<div class="list">
					<h6>ACTA DEL CONCURSO</h6>
					<h6>KARDEX</h6>
					<h6>COPIA DE CEDULA DE IDENTIDAD</h6>
					<h6>PLANILLAS DE EMPLEO</h6>
					<h6>FOTOGRAFIAS TIPO CARNET</h6>
					<h6>PLANILLA DE DATOS PERSONALES</h6>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
