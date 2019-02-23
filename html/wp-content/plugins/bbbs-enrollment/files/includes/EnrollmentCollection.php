<?php

require_once(__DIR__ . "/UserEnrollment.php");

class EnrollmentCollection {

    private $volunteer_role = "um_volunteer";

    public function __construct() {

    }

    public function allEnrollments() {
        $users = get_users(array("role"=> $this->volunteer_role));
        // $users = get_users();

        return array_map(function($user) {
            return new UserEnrollment($user);
        },$users);
    }
}