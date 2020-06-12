<?php
function getAddress($street, $house)
{
    if (!empty($street) && !empty($house)) {
        return $street . $house;
    }
    return '';

}

function getID($arr, $name_id)
{
    return $arr[0][$name_id];
}

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
    $query = $pdo->prepare("SELECT order_id FROM `order` ORDER BY `id` DESC LIMIT 1");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC)[0]['order_id'];
}

function addOrder($pdo, $data)
{
    $query = $pdo->prepare("INSERT INTO `order` (`client_id`,`order_id`,`street`,`house`,`part`,`appt`,`floor`,`comment`,`payment`) VALUES(:client_id,:order_id,:user_street,:user_house,:user_part,:user_appt,:user_floor,:user_comment,:user_payment)");
    $query->execute($data);
}

function countOrder($pdo, $data)
{
    $query = $pdo->prepare("SELECT COUNT(*) FROM `order` WHERE `client_id`= :client_id");
    $query->execute(['client_id' => $data['client_id']]);
    return $query->fetchColumn();
}