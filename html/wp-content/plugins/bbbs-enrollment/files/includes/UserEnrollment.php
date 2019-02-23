<?php


class UserEnrollment {

    protected $userId;
    protected $user;

    protected $firstName;
    protected $lastName;

    protected $createdAt = null;

    protected $completedForms = array();
    protected $lastUpdatedAt = null;

    public function __construct($user) {

        if (is_object($user)) {
            $this->user = $user;
            $this->userId = $user->ID;
        } else {
            // string ID
            $this->userId = $user;
            $user = get_user_by("id",$user);
            if ($user === false) {
                throw new Exception("Could not find user with ID " . $user);
            }
            $this->user = $user;
        }

        // load up details of the user
        $this->parseName();
        $this->setCreatedAt();
        $this->findCompletedForms();
        $this->determineLatestEntry();
    }

    protected function parseName() {

        $name = $this->user->display_name;
        $parts = explode(" ",$name);

        if (count($parts) == 0) {
            $this->firstName = "";
            $this->lastName = "";
        }
        elseif (count($parts) == 1) {
            $this->firstName = "";
            $this->lastName = $parts[0];
        } elseif (count($parts) == 2) {
            $this->firstName = $parts[0];
            $this->lastName = $parts[1];
        } else {
            $this->firstName = array_shift($parts);
            $this->lastName = implode(" ", $parts);
        }
    }

    protected function setCreatedAt() {
        $createdAt = $this->user->user_registered;
        $this->createdAt = $createdAt;
    }

    protected function findCompletedForms() {
        $search_criteria = array();
        $search_criteria['status'] = 'active';
        $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $this->userId );
        $this->completedForms = GFAPI::get_entries(0,$search_criteria);
    }

    protected function determineLatestEntry() {

        $latestTs = array_reduce($this->completedForms,function($acc,$cur) {
            $tsCreated = strtotime($cur['date_created']);
            if ($tsCreated > $acc) {
                $acc = $tsCreated;
            }
            return $acc;
        },0);

        if ($latestTs > 0) {
            $this->lastUpdatedAt = date("Y-m-d H:i:s",$latestTs);
        } else {
            $this->lastUpdatedAt = null;
        }

    }

    public function getId() {
        return $this->userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getCreatedAt($format = "Y-m-d H:i:s") {
        return ($this->createdAt) ? date($format,strtotime($this->createdAt)) : null;
    }

    public function getLastUpdatedAt($format = "Y-m-d H:i:s") {
        return ($this->lastUpdatedAt) ? date($format,strtotime($this->lastUpdatedAt)) : null;
    }

    public function getCompletedForms() {
        return $this->completedForms;
    }

    public function getUniqueCompletedFormCount() {
        return count(array_reduce($this->completedForms,function($acc,$cur) {
            $formId = $cur['form_id'];
            if (in_array($formId,$acc) == false) {
                $acc[] = $formId;
            }

            return $acc;
        },array()));
    }

    public function hasCompletedForm($formId) {
        $ids = array_map(function($cur) {
            return $cur['form_id'];
        },$this->completedForms);
        return in_array($formId,$ids);
    }

    public function completedFormEntryId($formId) {
        $entryId = array_reduce($this->completedForms,function ($acc,$cur) use ($formId) {
            if ($formId == $cur['form_id']) {
                $acc = $cur['id'];
            } 
            return $acc;
        },false);
        return $entryId;
    }
}