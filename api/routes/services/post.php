<?php

require_once "models/connection.php";
require_once "controllers/post.controller.php";
require_once "controllers/random.controller.php";

// 👉 Ruta personalizada para generar números aleatorios
if (isset($_GET["random"]) && $_GET["random"] == "sales") {

    if (
        isset($_POST["id_raffle"]) &&
        isset($_POST["id_client"]) &&
        isset($_POST["id_order"])
    ) {
        // Buscar la orden para obtener numbers_order
        $order = GetModel::getDataFilter("orders", "*", "id_order", $_POST["id_order"], null, null, null, null);
        if (empty($order)) {
            echo json_encode([
                "status" => 404,
                "msg" => "Orden no encontrada."
            ]);
            return;
        }

        $order = $order[0];
        $qty = intval($order->numbers_order);

        if ($qty <= 0) {
            echo json_encode([
                "status" => 400,
                "msg" => "La cantidad de números en la orden es inválida."
            ]);
            return;
        }

        RandomController::generateRandomSales(
            $_POST["id_raffle"],
            $_POST["id_client"],
            $_POST["id_order"],
            $qty
        );
        return;

    } else {
        echo json_encode([
            "status" => 400,
            "msg" => "Faltan parámetros: id_raffle, id_client, id_order"
        ]);
        return;
    }
}


if(isset($_POST)){

	/*=============================================
	Separar propiedades en un arreglo
	=============================================*/

	$columns = array();
	
	foreach (array_keys($_POST) as $key => $value) {

		array_push($columns, $value);
			
	}

	/*=============================================
	Validar la tabla y las columnas
	=============================================*/

	if(empty(Connection::getColumnsData($table, $columns))){

		$json = array(
		 	'status' => 400,
		 	'results' => "Error: Fields in the form do not match the database"
		);

		echo json_encode($json, http_response_code($json["status"]));

		return;

	}

	$response = new PostController();

	/*=============================================
	Peticion POST para registrar usuario
	=============================================*/	

	if(isset($_GET["register"]) && $_GET["register"] == true){

		$suffix = $_GET["suffix"] ?? "user";

		$response -> postRegister($table,$_POST,$suffix);

	/*=============================================
	Peticion POST para login de usuario
	=============================================*/	

	}else if(isset($_GET["login"]) && $_GET["login"] == true){

		$suffix = $_GET["suffix"] ?? "user";

		$response -> postLogin($table,$_POST,$suffix);

	}else{


		if(isset($_GET["token"])){

			/*=============================================
			Peticion POST para usuarios no autorizados
			=============================================*/

			if($_GET["token"] == "no" && isset($_GET["except"])){

				/*=============================================
				Validar la tabla y las columnas
				=============================================*/

				$columns = array($_GET["except"]);

				if(empty(Connection::getColumnsData($table, $columns))){

					$json = array(
					 	'status' => 400,
					 	'results' => "Error: Fields in the form do not match the database"
					);

					echo json_encode($json, http_response_code($json["status"]));

					return;

				}

				/*=============================================
				Solicitamos respuesta del controlador para crear datos en cualquier tabla
				=============================================*/		

				$response -> postData($table,$_POST);

			/*=============================================
			Peticion POST para usuarios autorizados
			=============================================*/

			}else{

				$tableToken = $_GET["table"] ?? "users";
				$suffix = $_GET["suffix"] ?? "user";

				$validate = Connection::tokenValidate($_GET["token"],$tableToken,$suffix);

				/*=============================================
				Solicitamos respuesta del controlador para crear datos en cualquier tabla
				=============================================*/		

				if($validate == "ok"){
		
					$response -> postData($table,$_POST);

				}

				/*=============================================
				Error cuando el token ha expirado
				=============================================*/	

				if($validate == "expired"){

					$json = array(
					 	'status' => 303,
					 	'results' => "Error: The token has expired"
					);

					echo json_encode($json, http_response_code($json["status"]));

					return;

				}

				/*=============================================
				Error cuando el token no coincide en BD
				=============================================*/	

				if($validate == "no-auth"){

					$json = array(
					 	'status' => 400,
					 	'results' => "Error: The user is not authorized"
					);

					echo json_encode($json, http_response_code($json["status"]));

					return;

				}

			}

		/*=============================================
		Error cuando no envía token
		=============================================*/	

		}else{

			$json = array(
			 	'status' => 400,
			 	'results' => "Error: Authorization required"
			);

			echo json_encode($json, http_response_code($json["status"]));

			return;	

		}	

	}

}