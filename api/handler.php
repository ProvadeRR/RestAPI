<?php


header('Content-Type: application/json');
require_once 'rb.php';
R::setup('mysql:host=localhost;dbname=test','root','root');
R::freeze(true);

$response = [
    'code' => '200',
    'status' => 'OK',
];

function seeds(){
    for ($i = 0 ; $i < 50 ; $i++){
        $user = R::dispense('users');
        $user->name='Стас';
        $user->surname = 'Карноза';
        $user->role = '3';
        R::store($user);
    }
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(!$_GET['id']){
        echo json_encode(R::findAll('users'));
    }
    else{
        echo json_encode(R::findOne('users','WHERE id =?' , [$_GET['id']]));
    }
}
elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!isset($_GET['id'])){
        $user = R::dispense('users');
        $user->name=$_POST['name'];
        $user->surname = $_POST['surname'];
        $user->role = $_POST['role'];
        R::store($user);
        echo json_encode($response);
    }
}
elseif($_SERVER['REQUEST_METHOD'] == 'PATCH'){
    if(isset($_GET['id'])){
        $data  = file_get_contents('php://input');
        $data = json_decode($data);
        $user = R::load('users',$_GET['id']);
        $user->name = $data->name;
        $user->surname = $data->surname;
        $user->role = $data->role;
        R::store($user);
        echo json_encode($response);
    }
}
elseif($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    if(isset($_GET['id'])){
        $user = R::load('users',$_GET['id']);
        R::trash($user);
        echo json_encode($response);
    }
}