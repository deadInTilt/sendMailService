<?php

$msg_box = "";
$errors = array();

// Проверка на метод запроса
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die("Недопустимый запрос.");
}

// Проверка передаваемых полей на предмет наличия данных
if (!isset($_POST['user_name']) || $_POST['user_name'] == "") {
    $errors[] = "Имя не указано.";
}

if (!isset($_POST['user_email']) || $_POST['user_email'] == "" || filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) == false) {
    $errors[] = "Email не указан или некорректный.";
}

if (!isset($_POST['user_phone']) || $_POST['user_phone'] == "" || strlen($_POST['user_phone']) < 12) {
    $errors[] = "Номер не указан или некорректный.";
}

if (!isset($_POST['text_comment']) || $_POST['text_comment'] == "") {
    $errors[] = "Текст письма не введен.";
}


// Отправка пиьсма или вывод ошибок
if (empty($errors)) {
    $message = "ФИО пользователя: " . $_POST['user_name'] . "<br>";
    $message .= "Email пользователя: " . $_POST['user_email'] . "<br>";
    $message .= "Номер телефона пользователя: " . $_POST['user_phone'] . "<br>";
    $message .= "Текст письма: " . $_POST['text_comment'];
    send_mail($message);
    $msg_box = "<span style='color: green;'>Сообщение успешно отправлено!</span>";
} else {
    $msg_box = "";
    foreach ($errors as $error) {
        $msg_box .= "<span style='color: red;'>$error</span><br>";
    }
}

// Функция отправки письма
function send_mail($message) {
    $mail_to = "nstalmakov@inbox.ru";
    $subject = "Письмо с обратной связью";

    $headers = "MIME - version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Текстовое письмо <no-replay@test.com>\r\n";

    mail($mail_to, $subject, $message, $headers);
}

// Возвращаем данные в формате JSON
header('Content-Type: application/json');
$response = array();

if (empty($errors)) {
    $response['message'] = "<span style='color: green;'>Сообщение успешно отправлено!</span>";
} else {
    $response['message'] = "";
    foreach ($errors as $error) {
        $response['message'] .= "<span style='color: red;'>$error</span><br>";
    }
}

echo json_encode($response);
exit();
