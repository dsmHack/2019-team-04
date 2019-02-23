<?php
require_once(__DIR__ . "/includes/EnrollmentForms.php");

function bbbs_status( $params ) {

    $enrollForms = new EnrollmentForms();
    $formNames = $enrollForms->getFormNames();
    return "<h1>" . implode("<br/>", $formNames) . "</h1>"; // has to return all the HTML
}

?>