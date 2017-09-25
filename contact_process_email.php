<?php

    $to = "contato@tindo.com.br";
    $from = $_REQUEST['email'];  
    $headers = "From: $from";
    $subject = "E-mail enviado do site Tindo";

    $fields = array();  
    $fields{"email"} = "email";    

    $body = "Novo e-mail enviado pelo site Tindo:\n\n"; foreach($fields as $a => $b){   $body .= sprintf("%20s: %s\n",$b,$_REQUEST[$a]); }

    $send = mail($to, $subject, $body, $headers);

?>
<?php 

header("Location: http://www.tindo.com.br/");

?> 