<?php

require_once(__DIR__ . "/UserEnrollment.php");

class EnrollmentCollection {

    public function __construct() {

    }

    public function allEnrollments() {
        //$users = get_users(array("role"=>"um_volunteer"));
        $users = get_users();

        return array_map(function($user) {
            return new UserEnrollment($user);
        },$users);
    }
}