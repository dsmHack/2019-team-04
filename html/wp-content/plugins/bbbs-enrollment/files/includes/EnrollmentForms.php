<?php

class EnrollmentForms {

    protected $forms = array();

    protected $defaultOrder = 0;

    protected $volunteerStaffVisibility = "volunteer_staff";
    protected $staffOnlyVisibility = "staff_only";

    public function __construct() {
        $this->retrieveEnrollmentForms();
    }

    protected function retrieveEnrollmentForms() {
        //global $wpdb;
        //$rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gf_form", ARRAY_A );
        $forms = GFAPI::get_forms();

        $forms = array_map(function($cur) {

            if ($cur['form_visibility'] === null) {
                $cur['form_visibility'] = $this->volunteerStaffVisibility;
            }

            if ($cur['form_order'] === null) {
                $cur['form_order'] = $this->defaultOrder;
            }

            return $cur;

        },$forms);

        usort($forms,function($a,$b) {
            return strcmp($a['form_order'],$b['form_order']);
        });

        $this->forms = $forms;
    }

    public function getAllForms() {
        return $this->forms;
    }

    public function getVolunteerForms() {

        $visibility = $this->volunteerStaffVisibility;

        return array_reduce($this->forms,function($acc,$cur) use ($visibility) {
            if ($cur['form_visibility'] == $visibility) {
                array_push($acc,$cur);
            }

            return $acc;
        },array());
    }

    public function getStaffForms() {

        $visibility = $this->staffOnlyVisibility;

        return array_reduce($this->forms,function($acc,$cur) use ($visibility) {
            if ($cur['form_visibility'] == $visibility) {
                array_push($acc,$cur);
            }

            return $acc;
        },array());
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

    public function getFormById($formId) {
        foreach ($this->forms as $f) {
            if ($f["id"] == $formId) {
                return $f;
            }
        }
        return null;
    }
}