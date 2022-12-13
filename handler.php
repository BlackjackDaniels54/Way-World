<?php
    //Используем циганские фокусы для почты
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    //Подключаем отлов исключений
    require 'lib/phpmailer/src/Exception.php';
    //Подключаем класс-родитель для работы с сообщениями
    require 'lib/phpmailer/src/PHPMailer.php';


    //Подключаем капчу ReCaptcha
    require('lib/recaptcha/src/autoload.php');
    
    
    $form_fields = array('name' => 'Name', 'phone' => 'Phone', 'email' => 'Email'); 
    $mail_send_suceess = 'Contact form successfully submitted. Thank you, I will get back to you soon!';
    $mail_send_failed = 'There was an error while submitting the form. Please try again later';
    $recaptcha_secret_key = '6Ld8QUohAAAAAE74Eq5KuyDsMl_gxSCJXqydN8vk';
    try {
        if (!empty($_POST)) {            
            if (!isset($_POST['g-recaptcha-response'])) {
                throw new \Exception('ReCaptcha is not set.');
            }
            $recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret_key, new \ReCaptcha\RequestMethod\CurlPost());          
            $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if (!$response->isSuccess()) {
                throw new \Exception('ReCaptcha was not validated.');
            }        
            $message_body = "You have new message from contact form\n=============================\n";
            foreach ($_POST as $key => $value) {
                if (isset($form_fields[$key])) {
                    $message_body .= "$form_fields[$key]: $value\n";
                }
            }
            $mail = new PHPMailer;
        
            $mail->CharSet = 'UTF-8';
            $mail->From      = 'info@waytotheworld.org'; //С какой почты
            $mail->FromName  = 'WayWorld'; //От кого письмо
            $mail->Subject   = 'Сообщение с формы обратной связи'; //Тема письма
            $mail->Body      = $message_body; //Тело пиьсма
            $mail->AddAddress( 'info@waytotheworld.org' ); //Кому отправить
        
            // отправляем письмо
            if ($mail->Send()) {
              $responseArray = array('type' => 'success', 'message' => $mail_send_suceess);
            } 
        }
    } catch (\Exception $e) {
        $responseArray = array('type' => 'danger', 'message' => $mail_send_failed);
    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
    } else {
        echo $responseArray['message'];
    }
?>