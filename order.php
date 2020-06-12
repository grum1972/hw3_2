<?php
require_once('functions.php');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=users", 'root', 'root');
} catch (PDOException $e) {
    echo $e->getMessage();
    die;
}


$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$street = $_GET['street'] ?? '';
$house = $_GET['house'] ?? '';
$part = $_GET['part'] ?? ''; //корпус
$appt = $_GET['appt'] ?? '';  //квартира
$floor = $_GET['floor'] ?? ''; //этаж
$comment = $_GET['comment'] ?? '';
$payment = $_GET['payment'] ?? '';

//Проверяем наличие почты и адреса

if (empty($email)) {
    echo "Введите email";
    exit();
}
if (empty(getAddress($street, $house))) {
    echo "Введите адрес. Обязательные поля улица и дом";
    exit();
}


$client = [];

// Проверка на существование клиента с текущим email

$client = checkEmail($pdo, $email);

// Если нет то добавляем

if (sizeof($client) === 0) {
    $lastId = addNewClient($pdo, $name, $email);
} else {
    //Запоминаем ID клиента
    $lastId = getId($client, 'id');
}

//Получаем номер текущего заказа

$orderId = getOrderId($pdo) + 1;

$data = [
    'client_id' => $lastId,
    'order_id' => $orderId,
    'user_street' => $street,
    'user_house' => $house,
    'user_part' => $part,
    'user_appt' => $appt,
    'user_floor' => $floor,
    'user_comment' => $comment,
    'user_payment' => $payment
];

//Записываем заказ
addOrder($pdo, $data);

//Считаем общее количество заказов клиента

$countOrders = countOrder($pdo, $data);

print_r("Спасибо, Ваш заказ будет доставлен по адресу: {$street} {$house} \n");
print_r("Номер Вашего заказ: {$orderId} \n");
print_r("Это Ваш {$countOrders} заказ \n");

//}