<?php

    $to = "contato@tindo.com.br";
    $from = $_REQUEST['email'];
    $name = $_REQUEST['name'];
    $tel = $_REQUEST['tel'];
    $site = $_REQUEST['site'];
    $empresa = $_REQUEST['empresa'];
    $headers = "From: $from";
    $subject = "Você recebeu um contato do site Tindo";

    $fields = array();
    $fields{"name"} = "Nome";
    $fields{"tel"} = "Telefone";
    $fields{"email"} = "E-mail";
    $fields{"site"} = "Site";
    $fields{"empresa"} = "Empresa";
    $fields{"message"} = "Mensagem";

    $body = "Nova mensagem enviada do site Tindo:\n\n"; foreach($fields as $a => $b){   $body .= sprintf("%20s: %s\n",$b,$_REQUEST[$a]); }

    $send = mail($to, $subject, $body, $headers);

?>
