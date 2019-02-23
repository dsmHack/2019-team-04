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

		//$enrollForms = new EnrollmentForms();
		//$formids = $enrollForms->getAllFormIDs();

        // Get all from ids for current volunteer user.
        
        //var_dump($this->userId);

        $search_criteria = array();

        $search_criteria['status'] = 'active';
        $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $this->userId );

        $this->completedForms = GFAPI::get_entries(0,$search_criteria);

        //var_dump($this->completedForms);

        /*
        foreach($this->completedForms as $form) {
            echo $form['id'] . "<br />";
            echo $form['form_id'] . "<br />";
        }
        */

        
		//$volunteerids = array_column($returnval, 'id');

		// Check which form IDs are missing from submitted forms.
		//$missingforms = array_diff($formids, $volunteerids)


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


    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getCreatedAt($format = "Y-m-d H:i:s") {
        return date($format,strtotime($this->createdAt));
    }

    public function getLastUpdatedAt($format = "Y-m-d H:i:s") {
        return date($format,strtotime($this->lastUpdatedAt));
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

}