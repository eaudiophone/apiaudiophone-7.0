<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*
	
	RUTAS DEL MODELO USUARIOS - LOGIN

*/

$router->get('api/test', function() {
	return 'test';
});

$router->post('api/login', [

	'middleware' => ['cors'],
	'as' => 'login.apiaudiophoneuser',
	'uses' => 'Apiaudiophonecontrollers\LoginAudiophoneUserController@loginApiaudiophoneUser'
]);

$router->post('api/login/refresh', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'login.apiaudiophoneuser',
	'uses' => 'Apiaudiophonecontrollers\LoginAudiophoneUserController@refreshTokenApiaudiophoneUser'
]);

$router->post('api/logout', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'logout.apiaudiophoneuser',
	'uses' => 'Apiaudiophonecontrollers\LoginAudiophoneUserController@logoutApiaudiophoneUser'
]);

//parametros opcionales para el show
$router->post('api/apiaudiophoneuser/show', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'user.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophoneUserController@showApiAudiophoneUser'
]);

$router->post('api/apiaudiophoneuser/store', [

	'middleware' => ['cors'],
	'as' => 'user.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophoneUserController@storeApiAudiophoneUser'
]);

$router->put('api/apiaudiophoneuser/update/{apiaudiophoneusers_id:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'user.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophoneUserController@updateApiAudiophoneUser'
]);

$router->put('api/apiaudiophoneuser/inactivate/{apiaudiophoneusers_id:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'user.inactivate',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophoneUserController@inactiveApiAudiophoneUser'
]);

$router->put('api/apiaudiophoneuser/activate/{apiaudiophoneusers_id:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'user.activate',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophoneUserController@activateApiAudiophoneUser'
]);

$router->delete('api/apiaudiophoneuser/destroy/{apiaudiophoneusers_id:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'user.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophoneUserController@destroyApiAudiophoneUser'
]);



/*
	
	RUTAS DEL MODELO DE TERMINO Y CONDICIONES

*/

$router->post('api/apiaudiophoneterm/show/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'term.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneTermController@showApiAudiophoneTerm'
]);


$router->post('api/apiaudiophoneterm/store/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'term.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneTermController@storeApiAudiophoneTerm'
]);


// ::: ESTAS RUTAS ACTUALMENTE NO SON USADAS POR LA APLICACIÓN, SE DEBEN DEJAR PORQ EL CONTROLLER LAS TIENE ::: //

/*$router->post('api/apiaudiophoneuser/token/show/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'token.user.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneTermController@showExpireTimeToken'
]);

$router->put('api/apiaudiophoneterm/update/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'term.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneTermController@updateApiAudiophoneTerm'
]);

$router->delete('api/apiaudiophoneterm/destroy/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'term.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneTermController@destroyApiAudiophoneTerm'
]);*/

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //


/*

	RUTAS PARA EL MODELO DE EVENTOS
*/

$router->post('api/apiaudiophonevent/show/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'event.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhonEventController@showApiAudiophonEvent'
]);


$router->get('api/apiaudiophonevent/create/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'event.create',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhonEventController@createApiAudiophonEvent'
]);


$router->post('api/apiaudiophonevent/store/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'event.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhonEventController@storeApiAudiophonEvent'
]);


$router->put('api/apiaudiophonevent/update/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'event.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhonEventController@updateApiAudiophonEvent'
]);


$router->put('api/apiaudiophonevent/status/update/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'event.status.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhonEventController@updateStatusEvent'
]);


$router->delete('api/apiaudiophonevent/destroy/{id_apiaudiophoneusers:[0-9]+}', [

	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'event.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhonEventController@destroyApiAudiophonEvent'
]);


/*
	
	RUTAS PARA GENERAR EL PDF DEL PRESUPUESTO
*/


$router->post('api/apiaudiophonebudget/show/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'budget.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneBudgetPdfController@showApiaudiophoneBudget'
]);


$router->post('api/apiaudiophonebudget/create/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'budget.create',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneBudgetPdfController@createApiaudiophoneBudget'
]);


$router->post('api/apiaudiophonebudget/store/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'budget.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneBudgetPdfController@storeApiaudiophoneBudget'
]);


$router->put('api/apiaudiophonebudget/update/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'budget.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneBudgetPdfController@updateApiaudiophoneBudget'
]);


$router->put('api/apiaudiophonebudgetstatus/update/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'budget.status_update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneBudgetPdfController@updateApiaudiophoneBudgetStatus'
]);


$router->delete('api/apiaudiophonebudget/destroy/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'budget.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneBudgetPdfController@destroyApiaudiophoneBudget'
]);


/*
	RUTAS PARA EL MODELO ITEMS
*/


$router->post('api/apiaudiophoneitem/show/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'item.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneItemController@showApiaudiophoneItem'
]);


$router->post('api/apiaudiophoneitem/store/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'item.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneItemController@storeApiaudiophoneItem'
]);


$router->put('api/apiaudiophoneitem/update/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'item.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneItemController@updateApiaudiophoneItem'
]);


$router->put('api/apiaudiophoneitemstatus/update/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'item.status_update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneItemController@updateApiaudiophoneItemStatus'
]);


$router->delete('api/apiaudiophoneitem/destroy/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'item.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneItemController@destroyApiaudiophoneItem'
]);

/*
	RUTAS PARA EL MODELO CLIENTES
*/

/*$router->get('api/prueba', function(){

	$clientes = ApiAudiophoneClient::simplePaginate(2);

	return $clientes;

});*/

$router->post('api/apiaudiophoneclient/show/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneClientController@showApiAudiophoneClient'
]);


$router->post('api/apiaudiophoneclient/store/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneClientController@storeApiAudiophoneClient'
]);


$router->put('api/apiaudiophoneclient/update/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneClientController@updateApiAudiophoneClient'
]);


$router->delete('api/apiaudiophoneclient/destroy/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudioPhoneClientController@destroyApiAudiophoneClient'
]);

/*
	RUTAS PARA EL MODELO BALANCES
*/

/*$router->get('api/prueba', function(){

	$clientes = ApiAudiophoneClient::simplePaginate(2);

	return $clientes;

});*/

$router->post('api/apiaudiophonebalance/show/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.show',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophonceBalanceController@showApiaudiophoneBalance'
]);


$router->post('api/apiaudiophonebalance/create/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophonceBalanceController@createApiaudiophoneBalance'
]);


$router->post('api/apiaudiophonebalance/store/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.store',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophonceBalanceController@storeApiaudiophoneBalance'
]);


$router->put('api/apiaudiophonebalance/update/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.update',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophonceBalanceController@updateApiaudiophoneBalance'
]);


$router->delete('api/apiaudiophonebalance/destroy/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophonceBalanceController@destroyApiaudiophoneBalance'
]);


$router->post('api/apiaudiophonebalance/balancepdf/{id_apiaudiophoneusers:[0-9]+}', [


	'middleware' => ['cors', 'client.credentials', 'auth:api'],
	'as' => 'client.destroy',
	'uses' => 'Apiaudiophonecontrollers\ApiAudiophonceBalanceController@pdfBalanceGenerate'
]);