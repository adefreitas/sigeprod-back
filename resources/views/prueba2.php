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
			<div class="col-xs-12">
				<?php foreach($files as $file){ echo ('<img src="'.$file.'"/><br/><br/><br/>');} ?>
			</div>
			<div class="col-xs-3">
			</div>
		</div>
	</div>
</body>
</html>
