<?php
function checkEmail($pdo, $email)
{
    $query = $pdo->prepare("SELECT * FROM client WHERE `email`= :user_email");
    $query->execute(['user_email' => $email]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function addNewClient($pdo, $name, $email)
{
    $data = ['user_name' => $name, 'user_email' => $email];
    $query = $pdo->prepare("INSERT INTO client (`name`,`email`) VALUES(:user_name,:user_email)");
    $query->execute($data);
    return $pdo->lastInsertId();
}

function getOrderId($pdo)
{
    $query = $pdo->prepare("SELECT order_id FROM client_order ORDER BY `id` DESC LIMIT 1");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function addOrder($pdo,$data){
    $query = $pdo->prepare("INSERT INTO client_order (`client_id`,`order_id`,`street`,`house`,`payment`) VALUES(:client_id,:order_id,:user_street,:user_house,:user_payment)");
    $query->execute($data);
}

function countOrder($pdo,$data){
    $query = $pdo->prepare("SELECT COUNT(*) FROM client_order WHERE `client_id`= :client_id");
    $query->execute(['client_id' => $data['client_id']]);
    return $query->fetchColumn();
}