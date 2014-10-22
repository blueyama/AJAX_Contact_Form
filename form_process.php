<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    require_once('./classes/SimpleMail.php');
    require_once('./classes/NewContactForm.php');

    $oCF = new NewContactForm($_POST);

    // If successful send an email.
    if($oCF->getSuccess() === true)
    {
        // SIMPLE MAIL
        $oMail = new SimpleMail();
        $oCF->sendEmail($oMail);
    }

    echo $oCF->getData('JSON');
}


