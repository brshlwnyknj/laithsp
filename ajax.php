<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'update_account'){
	$save = $crud->update_account();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_category"){
	$save = $crud->save_category();
	if($save)
		echo $save;
}

if($action == "delete_category"){
	$delete = $crud->delete_category();
	if($delete)
		echo $delete;
}
if($action == "save_house"){
	$save = $crud->save_house();
	if($save)
		echo $save;
}
if($action == "delete_house"){
	$save = $crud->delete_house();
	if($save)
		echo $save;
}

if($action == "save_tenant"){
	$save = $crud->save_tenant();
	if($save)
		echo $save;
}
if($action == "delete_tenant"){
	$save = $crud->delete_tenant();
	if($save)
		echo $save;
}
if($action == "get_tdetails"){
	$get = $crud->get_tdetails();
	if($get)
		echo $get;
}

if($action == "getBootByCategory"){
	$get = $crud->getBootByCategory();
	if($get)
		echo $get;
}

if($action == "save_in_out"){
	$save = $crud->save_in_out();
	if($save)
		echo $save;
}
if($action == "delete_in_out"){
	$save = $crud->delete_in_out();
	if($save)
		echo $save;
}

if($action == "save_payment"){
	$save = $crud->save_payment();
	if($save)
		echo $save;
}
if($action == "delete_payment"){
	$save = $crud->delete_payment();
	if($save)
		echo $save;
}

// if(!empty($_POST["category_id"])){ 
// 	// Fetch state data based on the specific country 
// 	$query =	$this->db->query("SELECT * FROM houses h  where h.category_id = ".$_POST['category_id']." ORDER BY id ASC");
// 	// $query = "SELECT * FROM houses WHERE category_id = ".$_POST['category_id']."  ORDER BY id ASC"; 
// 	$result = $db->query($query); 
	 
// 	// Generate HTML of state options list 
// 	if($result->num_rows > 0){ 
// 		echo '<option value="">Select State</option>'; 
// 		while($row = $result->fetch_assoc()){  
// 			echo '<option value="'.$row['id'].'">'.$row['house_no'].'</option>'; 
// 		} 
// 	}else{ 
// 		echo '<option value="">State not available</option>'; 
// 	} 
// }

ob_end_flush();
?>
