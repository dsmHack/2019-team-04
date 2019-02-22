<?php

class EnrollmentForms {

    protected $forms = array();

    public function __construct() {
        $this->retrieveEnrollmentForms();
    }

    protected function retrieveEnrollmentForms() {
        global $wpdb;
        $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gf_form", ARRAY_A );
        $this->forms = $rows;
    }

    public function getForms() {
        return $this->forms;
    }

    public function getFormNames() {
        return array_map(function($cur) {
            return $cur['title'];
        },$this->forms);
    }

}