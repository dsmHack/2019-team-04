<?php

class EnrollmentForms {

    protected $forms = array();

    public function __construct() {
        $this->retrieveEnrollmentForms();
    }

    protected function retrieveEnrollmentForms() {
        //global $wpdb;
        //$rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gf_form", ARRAY_A );
        $forms = GFAPI::get_forms();
        $this->forms = $forms;
    }

    public function getAllForms() {
        return $this->forms;
    }

    public function getAllFormNames() {
        return array_map(function($cur) {
            return $cur['title'];
        },$this->forms);
    }

    public function getAllFormIDs() {
        return array_map(function($cur) {
            return $cur['id'];
        },$this->forms);
    }

    public function getVolunteerForms() {
        return array_reduce($this->forms,function($acc,$cur) {

            if ($cur['description'] == "volunteer") {
                $acc[] = $cur;
            }

            return $acc;
        },array());
    }

    public function getVolunteerFormNames() {
        return array_map(function($cur) {
            return $cur['title'];
        },$this->getVolunteerForms());
    }

    public function getStaffForms() {
        return array_reduce($this->forms,function($acc,$cur) {

            if ($cur['description'] == "staff") {
                $acc[] = $cur;
            }

            return $acc;
        },array());
    }

    public function getStaffFormNames() {
        return array_map(function($cur) {
            return $cur['title'];
        },$this->getStaffForms());
    }
}