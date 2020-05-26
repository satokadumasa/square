<?php
$to = "sato.kadumasa@gmail.com";
$subject = "TEST";
$message = "This is TEST.\r\nHow are you?";
$headers = "From: webmaster@september-rain.com";
mail($to, $subject, $message, $headers);