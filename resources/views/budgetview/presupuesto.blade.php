<!DOCTYPE html>
<html lang = "es">

	<head>

		<!-- META TAGS REQUIRED -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta charset="UTF-8">

		<style>			

			body
			{
				font-family: sans-serif;
			}

			.factura
			{
				float : right;
			}

			table
			{
				width: : auto;
			}
			
			thead tr th.tr_thead			
			{
				border-bottom: 1px solid black; 	
			}			

			tbody tr.tr_tbody
			{
				background-color: lightgray;
			}

			tbody td
			{
				text-align: center;
			}
			
			tbody td.td_price
			{
				padding-right: 10px;
			}
			
			tfoot td.alone
			{
				border: 1px solid white;
				background-color: white;
			}
			
			tfoot td.pago
			{
				padding-left: 45px;
				background-color: lightgray;
			}

			tfoot td.total
			{
				padding-left: 55px;
				background-color: lightgray;
			}	
			.imagen
			{
				width: 100px;
				height: 100px;
			}			
		</style>


		<title>Presupuesto</title>	
	</head>

	<body>

		<header>
				
			<div class="container">

				<div class="row">
					
					<div class="col-12 col-sm-6">						

						<img class = "imagen" src= "http://localhost/apiaudiophone/assets/img/Estudio.jpg" alt="logo"/>

						<div class="factura">
							
							<h2 class="h2">Factura</h2>

							<p>
								<b>N°:</b> {{ $ids }} <br>
							   	<b>Fecha:</b> {{ $todays }} <br> 
							</p>			
						</div>
						

						<p class="info_study">
						   Avenida Principal El Manicomio,<br> 
						   Esquina Trinchera, Casa #152<br>	
						   Caracas - Venezuela<br>							   
						   Telefonos: 0212-8623492, 0416-9055706<br>
						   Email: Eaudiophonesa@gmail.com	
						</p>
					</div>		
				</div>


				<div class="row">
					
					<div class="col-12">

						<h2 class="h2">Datos Del Cliente</h2>

						<p class="name_client">
						   <b>Nombre:</b>{{ $names }}						   
						</p>
						<p class="email_client">						   
						   <b>Email:</b>{{ $emails }}
						</p>
						<p class="phone_client">
						   <b>Teléfono de Contacto:</b>{{ $phones }}
						</p>
						<p class="social_client">						   
						   <b>Social_Network:</b>{{ $networks }}
						</p>
						<p class="service">						   
						   <b>Servicio:</b>{{ $services }}	
						</p>
					</div>
				</div>				
			</div>
		</header>

		<main>
			

			<div class="container">

				<h2 class="h3">Desglose:</h2>
				
				<table class="table">
					
					<thead class="thead-dark">
						
						<tr>
							<th class = "tr_thead">Cantidad</th>
							<th class = "tr_thead">Descripcion</th>
							<th class = "tr_thead">Precio Unitartio $</th>
							<th class = "tr_thead">Total Precio $</th>
						</tr>
					</thead>
					
					<tbody>
						
						@foreach($items as $item)

							<tr class="tr_tbody">
								<td class = "td_tbody">{{ $item['apiaudiophonebudgets_items_quantity'] }}</td>
								<td class = "td_tbody">{{ $item['apiaudiophonebudgets_items_description'] }}</td>
								<td class = "td_price">{{ $item['apiaudiophonebudgets_items_unit_price'] }}</td>
								<td class = "td_price">{{ $item['apiaudiophonebudgets_items_subtotal'] }}</td>
							</tr>
						@endforeach
					</tbody>
					
					<tfoot>
						
						<tr class = "tr_tfoot">
							
							<td class = "alone"></td>
							
							<td class = "alone"></td>
							
							<td class = "pago"><b> Total a Pagar: </b></td>
							
							<td class = "total"><b> {{ $totals }} </b></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</main>

		<footer>

			<div class="container">
				
				<h5>Gracias por confiar en Estudios Audiophone S.A.</h5>
			</div>
		</footer>
	</body>
</html>