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

    $detailsUserId = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : null;


    if ($detailsUserId) {
        //echo "details";
        $userEnrollment = new UserEnrollment($detailsUserId);
        render_report_details($userEnrollment);
    } else {
        $ec = new EnrollmentCollection();
        $enrolls = $ec->allEnrollments();
        render_report_table($enrolls);
    }

}

function render_report_details($userEnrollment) {

    var_dump($userEnrollment);
}

function render_report_table($collection) {

    $headers = array("Last Name", "First Name", "Enrollment Date", "Latest Update On", "Forms Completed","&nbsp;");

    ?>
    <h2>BBBS Volunteer Reports</h2>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    <table id="myTable">
        <thead>
            <tr>
                <?php foreach($headers as $header): ?>
                <th><?php echo $header; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($collection as $userEnroll): ?>
            <tr>
                <td><?php echo $userEnroll->getLastName(); ?></td>
                <td><?php echo $userEnroll->getFirstName(); ?></td>
                <td><?php $caDate = $userEnroll->getCreatedAt(); echo ($caDate) ? $caDate : "Not Defined"; ?></td>
                <td><?php $luDate = $userEnroll->getLastUpdatedAt(); echo ($luDate) ? $luDate : "No Form Submissions";?></td>
                <td><?php echo $userEnroll->getUniqueCompletedFormCount(); ?></td>
                <td><a href="?page=bbbs-reports&id=<?php echo $userEnroll->getId(); ?>">Details</a></td>
            </tr>
            <?php endforeach; ?>
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