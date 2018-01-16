<?php

require 'Slim/Slim.php';

$app = new Slim();
$app->get('/modelos', 'getModelos');//Obtener todos los modelos
$app->get('/modelos/:id', 'getModelo');//Obtener modelo por id
$app->get('/modelos/nombre/:query', 'findByName');//Obtener modelo por nombre
$app->get('/modelos/categoria/:query', 'findByCategory');//Obtener modelo por categoria
$app->get('/modelos/negocio/:query', 'findByNegocio');//Obtener modelo por negocio
$app->post('/modelos', 'addModelo');//Agregar modelo
$app->put('/modelos/:id', 'updateModelo');//Modificar modelo
$app->delete('/modelos/:id', 'deleteModelo');//Eliminar modelo

$app->run();

function getModelos() {
	$sql = "SELECT * FROM modelos ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($resultado);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getModelo($id) {
	$sql = "SELECT * FROM modelos WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$resultado = $stmt->fetchObject();  
		$db = null;
		echo json_encode($resultado); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addModelo() {
	error_log('addModelo\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$resultado = json_decode($request->getBody());
	$sql = "INSERT INTO modelos (nombre, ruta, categoria, negocio) VALUES (:nombre, :ruta, :categoria, :negocio)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nombre", $resultado->nombre);
		$stmt->bindParam("ruta", $resultado->ruta);
		$stmt->bindParam("categoria", $resultado->categoria);
		$stmt->bindParam("negocio", $resultado->negocio);
		$stmt->execute();
		$resultado->id = $db->lastInsertId();
		$db = null;
		echo json_encode($resultado); 
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateModelo($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$resultado = json_decode($body);
	$sql = "UPDATE modelos SET nombre=:nombre, ruta=:ruta, categoria=:categoria, negocio=:negocio WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nombre", $resultado->nombre);
		$stmt->bindParam("ruta", $resultado->ruta);
		$stmt->bindParam("categoria", $resultado->categoria);
		$stmt->bindParam("negocio", $resultado->negocio);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($resultado); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteModelo($id) {
	$sql = "DELETE FROM modelos WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByName($query) {
	$sql = "SELECT * FROM modelos WHERE modelo_nombre LIKE :query ORDER BY modelo_nombre";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($resultado);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByCategory($query) {
	$sql = "SELECT * FROM modelos WHERE categoria LIKE :query ORDER BY categoria";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($resultado);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByNegocio($query) {
	$sql = "SELECT * FROM modelos WHERE negocio LIKE :query ORDER BY negocio";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($resultado);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="apitsaya";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>