<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- META TAGS REQUIRED -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta charset="UTF-8">
		<title>Balance CLiente</title>
		<style>
				
			body
			{
				font-family: sans-serif;
			}

			table
			{
				width: 100%;
				border: solid;
				font-size: 15px;
			}

			thead tr th
			{
				text-align: center;
				background-color: black;
				color: white;
			}

			tbody tr td
			{
				text-align: center;
			}

			.tamafec
			{
				font-size: 12px;
			}
			.imagen
			{
				width: 60px;
				height: 60px;
				float: right;
			}
			.desc {
				font-size: 16px;
			}

			.center {
				text-align: center;
			}

		</style>
	</head>
	
	<body>

		<!--         CABECERA DEL REPORTE          -->

		<header>
			
			<div class="container">
				
				<img 
					class="imagen" 
					src="http://localhost/apiaudiophone/resources/views/balanceview/Estudio.jpg" 
					alt="logo" 
				/>

				<div class="row">
					
					<div class="col-12 col-sm-6">
						
						<p class="desc">Caracas, {{ $today }}<!-- Variable Fecha del día debe venir del controller--><br/>
						Nombre: {{ $client_name }}<!-- Variable Nombre--><br/>
						C.I: {{ $client_ident }}<!-- Variable Identificación--><br/>
						Teléfono: {{ $client_phone }}</p><!-- Variable Teléfono--><br/>
					</div>
				</div>
			</div>
		</header>
		
		<!--         CUERPO DEL REPORTE          -->
		
		<main>
			
			<div class="container">

				<h2>Balance General</h2>
				
				<table class="table">
					
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Descripción</th>
							<th>Horas Laboradas</th>
							<th>Tarifa</th>
							<th>Debe</th>
							<th>Haber</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						

						@foreach($apiaudiophonebalancepdf as $apiaudiophonebalancepdf_data) <!-- variables para ingresarlas en el reporte -->

							<tr>
								<td class = "tamafec">{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_date'] }}</td>
								<td>{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_desc'] }}</td>
								<td>{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_horlab'] }}</td>
								<td>{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_tarif'] }}</td>
								<td>{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_debe'] }}</td>
								<td>{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_haber'] }}</td>
								<td>{{ $apiaudiophonebalancepdf_data['apiaudiophonebalances_total'] }}</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td>
								<!-- Hasta los momentos no hay nada que colocar en el footer -->
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</main>
		<footer>

			<div class="container">
				<h5 style="float: right;">
					Total de horas laboradas: {{ $acum_hours }} <br> <!-- cambiar las horas laboradas -->
					Saldo: {{ $saldo_final }} <br><br><br> <!-- saldo el ultimo valor del total -->
				</h5>
				<h5>Gracias por confiar en Estudios Audiophone S.A.</h5>
			</div>		
		</footer>	
	</body>
</html>