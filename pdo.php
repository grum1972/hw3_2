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
$payment = $_GET['payment'] ?? '';

if (!empty($email) && !empty($name)) {

    $client = [];

    // Проверка на существование клиента с текущим email

    $client = checkEmail($pdo, $email);

    // Если нет то добавляем

    if (sizeof($client) === 0) {
        $lastId = addNewClient($pdo, $name, $email);
    } else {

        //Запоминаем ID клиента
        $lastId = $client[0]['id'];
    }

    //Получаем номер текущего заказа
    $result=getOrderId($pdo);
    $orderId = $result[0]['order_id'] + 1;

    $data = [
        'client_id' => $lastId,
        'order_id' => $orderId,
        'user_street' => $street,
        'user_house' => $house,
        'user_payment' => $payment
    ];

    //Записываем заказ
    addOrder($pdo,$data);

    //Считаем общее количество заказов клиента

    $countOrders = countOrder($pdo,$data);

    //Выводим сообщение клиенту

    echo '<pre>';
    print_r("Спасибо, Ваш заказ будет доставлен по адресу: {$street} {$house} \n");
    print_r("Номер Вашего заказ: {$orderId} \n");
    print_r("Это Ваш {$countOrders} заказ \n");
    echo '</pre>';
}