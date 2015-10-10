<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Notificación</title>
<link href="styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body>

<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" width="600">
			<div class="content">
				<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
					<tr>
						<td class="content-wrap">
							<meta itemprop="name" content="Confirm Email"/>
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="content-block">
										Estimado(a) {{ $name }} {{ $lastname }},
									</td>
								</tr>
								<br>
								<tr>
									<td class="content-block">
										Sus propuestas han sido rechazadas por el {{ $role }}, con las siguientes observaciones:
									</td>
								</tr>
								<br>
								<tr>
									<td class="content-block" style="border: dotted 1px; padding: 5px 5px 5px 5px">
										{{ $rejectionMessage }}
									</td>
								</tr>
								<br>
								<tr>
									<td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler">
										Para modificar sus propuestas, por favor acceda a la aplicación a través del siguiente link: <a href="http://home-sigeprod.rhcloud.com/" class="btn-primary" itemprop="url">www.sigeprod.ucv.ve</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
		</td>
		<td></td>
	</tr>
</table>

</body>