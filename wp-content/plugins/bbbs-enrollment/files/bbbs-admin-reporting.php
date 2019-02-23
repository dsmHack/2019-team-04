<?php
function bbbs_volunteer_menu() {
    add_menu_page("BBBS Volunteer", "BBBS Volunteer", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page" ,null, 15);
    bbbs_add_volunteer_submenu();
}

function bbbs_add_volunteer_submenu() {
    add_submenu_page("bbbs-volunteer", "BBBS Dashboard", "BBBS Dashboard", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page");
    add_submenu_page("bbbs-volunteer", "BBBS Reports", "BBBS Reports", "manage_options", "bbbs-reports", "volunteer_reports_page");
}


require_once(__DIR__ . "/includes/UserEnrollment.php");
require_once(__DIR__ . "/includes/EnrollmentCollection.php");

function volunteer_dashboard_page() {
?>
<h2>BBBS Volunteer Dashboard</h2>
<?php
}

function volunteer_reports_page() {

    /*
        user
        first name
        last name
        started
        last updated
        Forms remaining
    */

    //$users = get_users(array("role"=>"Volunteer"));
    //$users = get_users(array("role"=>"um_volunteer"));

    //$userEnrollment = new UserEnrollment("1");
    //var_dump($userEnrollment->getUniqueCompletedFormCount());



    $ec = new EnrollmentCollection();
    $enrolls = $ec->allEnrollments();

    echo "<pre>";
    //var_dump($userEnrollment);

    var_dump(count($enrolls));
    var_dump($enrolls);

    //var_dump($users);
    echo "</pre>";


?>
<h2>BBBS Volunteer Reports</h2>
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<table id="myTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Step</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>John Doe</td>
            <td>Step 3</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jane Smith</td>
            <td>Step 1</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Bob Rob</td>
            <td>Step 1</td>
        </tr>
    </tbody>
</table>


<script type="text/javascript">
jQuery(document).ready( function () {
    jQuery('#myTable').DataTable();
} );
</script>
<?php


}
?>