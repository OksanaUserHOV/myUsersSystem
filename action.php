<?php 
require_once 'connect.php';

function existId($ids, $place_holders='', $count_ids=1){
	
	global $pdo;

	if( $count_ids > 1){
		$qwery = $pdo->prepare("SELECT 1 FROM users WHERE id IN ($place_holders)" );
		$qwery->execute($ids);

	}else{

		$qwery = $pdo->prepare("SELECT 1 FROM users WHERE id = ? LIMIT 1");
		$qwery->execute($ids);
		
	}
	if ( $qwery->rowCount() < $count_ids ) {

			$error = 'Error ID';
			$res = ['status' => false, 'error' => ['code' => 100, 'message' => $error] ]; 
			echo json_encode($res);
		return false;
	}
	return true;

}

if ($_POST['action'] == 'add') {

	$name_first = trim($_POST['first_name']);
	$name_last = trim($_POST['last_name']);

	if ($_POST['status'] === 'true') {
		$status_active = 1;
	}
	else{
		$status_active = 0;
	}

	$role =$_POST['role'];

	$msg ='';
	if ( empty($name_first) ) {
		$msg .= 'Enter First name'.'<br>';		
	}
	if ( empty($name_last) ) {
		$msg .= 'Enter Last name'.'<br>';
	}
	if ($role == '-Please Select-') {
		$msg .= 'Select role';		
	}


	if ( $msg !=='' ) {	
		
		$error = ['code' => 100, 'message' => $msg];
		$res = ['status' => false, 'error' => $error];
		echo json_encode($res);		
		exit();
	}else{

			$sql = 'INSERT INTO users (name_first, name_last, status, role) VALUES (:name_first, :name_last, :status, :role)';  
	  		$insert = $pdo->prepare($sql);

			$insert->execute([
				'name_first' => $name_first,
				'name_last' => $name_last,
				'status' => $status_active,
				'role' => $role 
			]);
			$id = $pdo->lastInsertId();
				

			$res = ['status' => true, 'error' => null, 
					'user' => [	'id' => $id,
								'name_first' => $name_first,
								'name_last' => $name_last,
								'status' => (bool)$status_active,
								'role' => $role
								]	
					];
			echo json_encode($res);
	}
}
 


if ($_POST['action'] == 'edit'){

	$name_first = trim($_POST['first_name']);
	$name_last = trim($_POST['last_name']);
	if ($_POST['status'] === 'true') {
		$status_active = 1;
	}
	else{
		$status_active = 0;
	}

	$role = $_POST['role'];
	$id = $_POST['id'];

	$msg ='';
	if ( empty($name_first) ) {
		$msg .= 'Enter First name'.'<br>';		
	}
	if ( empty($name_last) ) {
		$msg .= 'Enter Last name'.'<br>';
	}
	if ($role == '-Please Select-') {
		$msg .= 'Select role';		
	}


	// if (!existId($id)) {
	// 	exit();
	// }
	if (existId([$id])) {
		if ( $msg !=='' ) {	
			$error = ['code' => 100, 'message' => $msg];
			$res = ['status' => false, 'error' => $error];
			echo json_encode($res);		
			exit();

		}else{
			
				$edit = $pdo->prepare( "UPDATE users
										SET name_first = :name_first, 
											name_last = :name_last,
											status = :status,
											role = :role
									 	WHERE id = :id");

				$edit->execute([
								'name_first' =>	$name_first,
								'name_last' =>	$name_last,
								'status' =>	$status_active,
								'role' => $role,
								'id' => $id
							]);
				$res = ['status' => true, 'error' => null, 
						'user' => [	'id' => $id,
										'name_first' => $name_first,
										'name_last' => $name_last,
										'status' => (bool)$status_active,
										'role' => $role
									]	
						];
				echo json_encode($res);	
					
		}
	}	
}


if ($_POST['action'] == 'delete'){
	$pos = strpos($_POST['ids'], 'item');
	if ($pos !== false) {
		$ids_str = str_replace('item-', '', $_POST['ids']);
		$ids = explode(",", $ids_str);
		$count = count($ids);
		$place_holders = implode(',', array_fill(0, $count, '?'));

		if (existId($ids, $place_holders, $count)){
			$delete = $pdo->prepare("DELETE FROM users WHERE id IN ($place_holders)");
			$delete->execute($ids);
			
			$res = ['status' => true, 'error' => null, 
					'ids' => $ids ]; 
			echo json_encode($res);			
		}

	}else{
		$ids =[$_POST['ids']];
		if (existId($ids)) {
		
			$delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
			$delete->execute([ $_POST['ids'] ]);	
			
			$res = ['status' => true, 'error' => null, 
					'ids' => [ $_POST['ids'] ] ];
			echo json_encode($res);
		}

	}
}

if ($_POST['action'] == 'update'){
	$ids = str_replace('item-', '', $_POST['users']);
	$count = count($ids);
	$place_holders = implode(',', array_fill(0, $count, '?'));

	if (existId($ids, $place_holders, $count)) {
		if ($_POST['selectAction'] == 'Set active'){

	
			$update = $pdo->prepare( "UPDATE users
									SET status = ?
								 	WHERE id IN ($place_holders)");
			
			array_unshift($ids, 1);
			$update->execute($ids);
			array_shift($ids);

			$res = ['status' => true, 'error' => null, 'ids' => $ids ]; 
			echo json_encode($res);
		}

		if ($_POST['selectAction'] == 'Set not active'){

			$update = $pdo->prepare( "UPDATE users
									SET status = ?
								 	WHERE id IN ($place_holders)");
			
			array_unshift($ids, 0);
			$update->execute($ids);
			array_shift($ids);

			$res = ['status' => true, 'error' => null, 'ids' => $ids ]; 
			echo json_encode($res);
		
		}		
	}
}
