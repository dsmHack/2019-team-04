<?php

require_once(__DIR__ . "/UserEnrollment.php");

class EnrollmentCollection {

    private $volunteer_role = "um_volunteer";

    public function __construct() {

    }

    public function allEnrollments() {
        $users = get_users(array("role"=> $this->volunteer_role));

        return array_reduce($users,function($acc,$user) {
            $status = get_user_meta($user->ID,"account_status");

            if ($status[0] != "inactive") {
                $acc[] = new UserEnrollment($user);
            }
            return $acc;
        },array());
    }
}