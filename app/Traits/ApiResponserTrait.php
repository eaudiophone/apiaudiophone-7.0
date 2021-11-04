<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Jsonable;

trait ApiResponserTrait
{


  /*
 * Responser de afirmacciones estándar
 *
 */
  public function successResponse($data, $code)
  {

    return response()->json([

      'data' => $data,
      'code' => $code
    ], $code);
  }


 /*
 * Responser de afirmacciones estándar para ApiaudiophoneUser
 *
 */
  public function successResponseApiaudiophoneUser($ok = true, $code, $bduserstotal, $apiaudiophoneuserdata)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bduserstotal' => $bduserstotal,
      'apiaudiophoneuserdata' => $apiaudiophoneuserdata
    ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneUser
 *
 */
  public function successResponseApiaudiophoneUserCount($ok = true, $code, $bduserstotal, $apiaudiophoneusercount, $apiaudiophoneuserdata)
  {

    return response()->json([

    'ok' => $ok,
    'status' => $code,
    'bduserstotal' => $bduserstotal,
    'apiaudiophoneusercount' => $apiaudiophoneusercount,
    'apiaudiophoneuserdata' => $apiaudiophoneuserdata
    ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneUser
 *
 */
  public function successResponseApiaudiophoneUserStore($ok = null, $code, $apiaudiophoneusermessage, $apiaudiophoneusernew)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneusermessage' => $apiaudiophoneusermessage,
        'apiaudiophoneusernew' => $apiaudiophoneusernew
      ], $code);
  }


/*
 * Responser de afirmacciones estándar para pdfBalanceGenerate pdf
 *
 */
  public function pdfBalanceGenerateReport($ok = null, $code, $apiaudiophonebalancemessage, $suburl, $linkbase)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophonebalancemessage' => $apiaudiophonebalancemessage,
        'suburl' => $suburl,
        'linkbase' => $linkbase
      ], $code);
  }

 /*
 * Responser de afirmacciones estándar para ApiaudiophoneTerm
 *
 */
  public function successResponseApiaudiophoneTerm($ok = null, $code, $apiaudiophoneterm_mesaage, $apiaudiophoneservices_name, $days_events_array, $apiaudiophonetermshowdata)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneterm_message' => $apiaudiophoneterm_mesaage,
      'apiaudiophoneservices_name' => $apiaudiophoneservices_name,
      'days_events_array' => $days_events_array,
      'apiaudiophonetermshowdata' => $apiaudiophonetermshowdata
    ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneTerm
 *
 */
  public function successResponseApiaudiophoneTermDestroy($ok = null, $code, $apiaudiophoneterm_mesaage, $termconfiguration_last)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophonetermdelete' => $apiaudiophoneterm_mesaage,
      'termconfiguration_last' => $termconfiguration_last
    ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophonEvent
 *
 */
  public function successResponseApiaudiophonEventShow($ok = true, $code,  $apiaudiophoneventdata)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneventdata' => $apiaudiophoneventdata
    ], $code);
  }


  /*
 * Responser de afirmacion apiaudiophonevent
 *
 */
  public function successResponseApiaudiophonEventStore($ok = null, $code, $apiaudiophoneventmessage, $apiaudiophoneservicename, $apiaudiophoneventnew)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneventmessage' => $apiaudiophoneventmessage,
        'apiaudiophoneservicename' => $apiaudiophoneservicename,
        'apiaudiophoneventnew' => $apiaudiophoneventnew
      ], $code);
  }


  /*
 * Responser de afirmacion apiaudiophonevent
 *
 */
  public function successResponseApiaudiophonEventUpdate($ok = null, $code, $apiaudiophoneventmessage, $apiaudiophoneservicename, $apiaudiophoneventupdate)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneventmessage' => $apiaudiophoneventmessage,
        'apiaudiophoneservicename' => $apiaudiophoneservicename,
        'apiaudiophoneventupdate' => $apiaudiophoneventupdate
      ], $code);
  }


 /*
 * Responser de afirmacion para devolver los id de los terms basados en el id del servicio, caso cuando hay un solo termino creado.
 *
 */
  public function successResponseApiaudiophonEventCreateOnly($ok = null, $code, $apiaudiophoneventmessage, $apiaudiophonetermid, $id_service, $nombre_servicio)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneventmessage' => $apiaudiophoneventmessage,
        'apiaudiophoneterm_id' => $apiaudiophonetermid,
        'id_service' => $id_service,
        'nombre_servicio_term_' => $nombre_servicio
      ], $code);
  }


 /*
 * Responser de afirmacion para devolver los id de los terms basados en el id del servicio, hasta ahora solo dos servicios.
 *
 */
  public function successResponseApiaudiophonEventCreate($ok = null, $code, $apiaudiophoneventmessage, $apiaudiophonetermiduno, $nombre_servicio_uno, $apiaudiophonetermid_dos, $nombre_servicio_dos)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneventmessage' => $apiaudiophoneventmessage,
        'apiaudiophoneterm_id_uno' => $apiaudiophonetermiduno,
        'nombre_servicio_term_uno' => $nombre_servicio_uno,
        'apiaudiophoneterm_id_dos' => $apiaudiophonetermid_dos,
        'nombre_servicio_term_dos' => $nombre_servicio_dos
      ], $code);
  }


  /*
 * Responser de afirmacion estándar para ApiaudiophoneItem show
 *
 */
  public function successResponseApiaudiophoneItem($ok = true, $code, $itemstotal, $apiaudiophoneitemdata)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bditemstotal' => $itemstotal,
      'apiaudiophoneitemdata' => $apiaudiophoneitemdata
    ], $code);
  }


  /*
 * Responser de afirmacion estándar para ApiaudiophoneBudget show
 *
 */
  public function successResponseApiaudiophoneBudget($ok = true, $code, $budgetstotal, $apiaudiophonebudgetdata)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bdbudgetstotal' => $budgetstotal,
      'apiaudiophonebudgetsdata' => $apiaudiophonebudgetdata
    ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneItem create
 *
 */
  public function successResponseApiaudiophoneItemStore($ok = null, $code, $apiaudiophoneitemessage, $apiaudiophoneitemnew)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneitemessage' => $apiaudiophoneitemessage,
        'apiaudiophoneitemnew' => $apiaudiophoneitemnew
      ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneItem update
 *
 */
  public function successResponseApiaudiophoneItemUpdate($ok = null, $code, $apiaudiophoneitemessage, $apiaudiophoneitemupdate)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneitemessage' => $apiaudiophoneitemessage,
        'apiaudiophoneitemupdate' => $apiaudiophoneitemupdate
      ], $code);
  }

  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBudget update
 *
 */
  public function successResponseApiaudiophoneBudgetUpdate($ok = null, $code, $apiaudiophonebudgetmessage, $apiaudiophonebudgetupdate)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophonebudgetmessage' => $apiaudiophonebudgetmessage,
        'apiaudiophonebudgetupdate' => $apiaudiophonebudgetupdate
      ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBudget create
 *
 */
  public function successResponseApiaudiophoneBudgetStore($ok = null, $code, $apiaudiophonebudgetmessage, $apiaudiophonebudgetnew)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophonebudgetmessage' => $apiaudiophonebudgetmessage,
        'apiaudiophonebudgetnew' => $apiaudiophonebudgetnew
      ], $code);
  }


 /*
 * Responser de afirmacciones estándar para ApiaudiophoneClient show
 *
 */
  public function successResponseApiaudiophoneClientShow($ok = null, $code, $bdclientstotal, $apiaudiophoneclientshow)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bdclientstotal' => $bdclientstotal,
      'apiaudiophoneclientshow' => $apiaudiophoneclientshow
    ], $code);
  }

   /*
 * Responser de afirmacciones estándar para ApiaudiophoneClient
 *
 */
  public function successResponseApiaudiophoneClientCount($ok = true, $code, $bdclientstotal, $apiaudiophoneclientcount, $apiaudiophoneclientdata)
  {

    return response()->json([

    'ok' => $ok,
    'status' => $code,
    'bdclientstotal' => $bdclientstotal,
    'apiaudiophoneclientcount' => $apiaudiophoneclientcount,
    'apiaudiophoneclientshow' => $apiaudiophoneclientdata
    ], $code);
  }

  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBalance show
 *
 */
  public function successResponseApiaudiophoneBalanceCount($ok = null, $code, $bdbalancetotal, $bdbalance_client, $apiaudiophonebalanceshow)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bdbalancetotal' => $bdbalancetotal,
      'bdbalance_client' => $bdbalance_client,
      'apiaudiophonebalanceshow' => $apiaudiophonebalanceshow
    ], $code);
  }

  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBalance show
 *
 */
  public function successResponseApiaudiophoneBalanceShow($ok = null, $code, $bdbalancetotal, $count_balance_client, $apiaudiophonebalanceshow)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bdbalancetotal' => $bdbalancetotal,
      'count_balance_client' => $count_balance_client,
      'apiaudiophonebalanceshow' => $apiaudiophonebalanceshow
    ], $code);
  }

  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBalance create
 *
 */
  public function successResponseApiaudiophoneBalanceCreate($ok = null, $code, $bdbalancetotal, $bdbalance_client, $apiaudiophonebalancecreate)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bdbalancetotal' => $bdbalancetotal,
      'bdbalance_client' => $bdbalance_client,
      'apiaudiophonebalancecreate' => $apiaudiophonebalancecreate
    ], $code);
  }

  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBalance store
 *
 */
  public function successResponseApiaudiophoneBalanceStore($ok = null, $code, $message, $apiaudiophonebalancestore)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'message' => $message,
      'apiaudiophonebalancestore' => $apiaudiophonebalancestore
    ], $code);
  }

  /*
 * Responser de afirmacciones estándar para ApiaudiophoneBalance update
 *
 */
  public function successResponseApiaudiophoneBalanceUpdate($ok = null, $code, $message, $apiaudiophonebalanceupdate)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'message' => $message,
      'apiaudiophonebalanceupdate' => $apiaudiophonebalanceupdate
    ], $code);
  }


/*
 * Responser de afirmacciones estándar para ApiaudiophoneBalance update version 2
 *
 */
  public function successResponseApiaudiophoneBalanceUpdateDos($ok = null, $code, $message)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'message' => $message
    ], $code);
  }



 /*
 * Responser de afirmacciones estándar para ApiaudiophoneClient store
 *
 */
  public function successResponseApiaudiophoneClientStore($ok = null, $code, $apiaudiophoneclientmessage, $apiaudiophoneclientstore)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneclientmessage' => $apiaudiophoneclientmessage,
      'apiaudiophoneclientstore' => $apiaudiophoneclientstore
    ], $code);
  }


  /*
 * Responser de afirmacciones estándar para ApiaudiophoneClient update
 *
 */
  public function successResponseApiaudiophoneClientUpdate($ok = null, $code, $apiaudiophoneclientmessage, $apiaudiophoneclientsupdate)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneclientmessage' => $apiaudiophoneclientmessage,
      'apiaudiophoneclientsupdate' => $apiaudiophoneclientsupdate
    ], $code);
  }


  /*
 * Responser de Errores para Credenciales Vencidas
 *
 */
  public function errorResponse($message, $code){


    return response()->json([

      'errorMessage' => $message,
      'code' => $code
    ], $code);
  }


  /*
 * Responser de error estándar para ApiaudiophoneUser
 *
 */
  public function errorResponseApiaudiophoneUser($ok = true, $code, $bduserstotal, $apiaudiophoneusermessage)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'bduserstotal' => $bduserstotal,
      'apiaudiophoneusermessage' => $apiaudiophoneusermessage
    ], $code);
  }


 /*
 * Responser de error estándar para ApiaudiophoneTerm
 *
 */
  public function errorResponseApiaudiophoneUserUpdate($ok = null, $code, $apiaudiophoneusermessage, $apiaudiophoneuserinactive)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneusermessage' => $apiaudiophoneusermessage,
      'apiaudiophoneuserinactive' => $apiaudiophoneuserinactive
    ], $code);
  }


 /*
 * Responser de errores estándar para ApiaudiophoneTerm
 *
 */
  public function errorResponseApiaudiophoneTerm($ok = null, $code, $apiaudiophoneterm_mesaage)
  {

    return response()->json([

        'ok' => $ok,
        'status' => $code,
        'apiaudiophoneterm_mesaage' => $apiaudiophoneterm_mesaage
      ], $code);
  }


 /*
 * Responser de errores estándar para ApiaudiophoneTerm
 *
 */
  public function errorResponseApiaudiophoneTermShow($ok = null, $code, $apiaudiophoneterm_mesaage, $apiaudiophoneuser_status, $apiaudiophoneusers_fullname)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneusermessage' => $apiaudiophoneterm_mesaage,
      'apiaudiophoneuser_status' => $apiaudiophoneuser_status,
      'apiaudiophoneusers_fullname' => $apiaudiophoneusers_fullname
    ], $code);
  }


  /*
 * Responser de error ApiaudiophonEvent, valida cuando un usuario supera el limite de citas semanales y mensuales
 *
 */
  public function errorResponseQuantityEvents($ok = null, $code, $apiaudiophoneterm_message)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneusermessage' => $apiaudiophoneterm_message
    ], $code);
  }


 /*
 * Responser de error estándar para ApiaudiophoneTermDestroy
 *
 */
  public function errorResponseApiaudiophoneUserDestroy($ok = null, $code, $apiaudiophoneterm_mesaage)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneterm_mesaage' => $apiaudiophoneterm_mesaage
    ], $code);
  }


 /*
 * Responser de error estándar para ApiaudiophoneTermDestroy
 *
 */
  public function errorResponseApiaudiophoneTermDestroy($ok = null, $code, $apiaudiophoneterm_mesaage)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneterm_mesaage' => $apiaudiophoneterm_mesaage
    ], $code);
  }


  /*
 * Responser de error estándar para ApiaudiophoneTermDestroy
 *
 */
  public function errorResponseApiaudiophonEventDestroy($ok = null, $code, $apiaudiophonevent_mesaage)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophonevent_mesaage' => $apiaudiophonevent_mesaage
    ], $code);
  }


  /*
 * Responser estándar para ApiaudiophoneItem delete
 *
 */
  public function errorResponseApiaudiophoneItemDelete($ok = null, $code, $apiaudiophoneitem_mesaage)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophoneterm_mesaage' => $apiaudiophoneitem_mesaage
    ], $code);
  }

  /*
 * Responser estándar para ApiaudiophoneBudget delete
 *
 */
  public function errorResponseApiaudiophoneBudgetDelete($ok = null, $code, $apiaudiophonebudget_mesaage)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophonebudget_mesaage' => $apiaudiophonebudget_mesaage
    ], $code);
  }


  /*
 * Responser de error estándar para ApiaudiophoneClientDestroy
 *
 */
  public function errorResponseApiaudiophonClientDestroy($ok = null, $code, $apiaudiophonevent_message)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophonevent_message' => $apiaudiophonevent_message
    ], $code);
  }


  /*
 * Responser de error estándar para ApiaudiophoneBalanceDestroy
 *
 */
  public function errorResponseApiaudiophonBalanceDestroy($ok = null, $code, $apiaudiophonebalance_message)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophonebalance_message' => $apiaudiophonebalance_message
    ], $code);
  }


  /*
 * Responser de error ApiaudiophoneBudget, response cuando no hay budget creado en la base de datos
 *
 */
  public function errorResponseBudgetCreateUno($ok = null, $code, $apiaudiophonebudget_message, $nombre_serv_uno, $id_serv_uno, $nombre_serv_dos, $id_serv_dos, $bditems)
  {

    return response()->json([

      'ok' => $ok,
      'status' => $code,
      'apiaudiophonebudget_message' => $apiaudiophonebudget_message,
      'nombre_serv_uno' => $nombre_serv_uno,
      'id_serv_uno' => $id_serv_uno,
      'nombre_serv_dos' => $nombre_serv_dos,
      'id_serv_dos' => $id_serv_dos,
      'bd_items' => $bditems
    ], $code);
  }
}
