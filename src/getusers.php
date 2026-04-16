<?php

//headers
header('Access-Control-Allow-Origin: *'); //lets any domain access this api without auth
header('Content-Type: application/json');

//initializing our api
include_once('../core/initialize.php');

//instantiate user object
$users = new User($db);

//users query
$results = $users->getUsers();
$num = $results->rowCount();

if($num > 0) {
    //users array
    $users_arr = array();
    $users_arr['data'] = array();

    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $user_item = array(
            'id' => $id,
            'username' => $username,
            'display_name' => $display_name,
            'created_at' => $created_at
        );

        //push to "data"
        array_push($users_arr['data'], $user_item);
    }

    //turn to json and output
    echo json_encode($users_arr);

} else {
    //no users
    echo json_encode(
        array('message' => 'No users found')
    );
}

?>