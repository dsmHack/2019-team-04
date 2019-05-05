<?php
function bbbs_volunteer_menu() {
    add_menu_page("BBBS Volunteer", "BBBS Volunteer", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page" ,null, 15);
    bbbs_add_volunteer_submenu();
}

function bbbs_add_volunteer_submenu() {
    add_submenu_page("bbbs-volunteer", "Dashboard", "Dashboard", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page");
    add_submenu_page("bbbs-volunteer", "Reports", "Reports", "manage_options", "bbbs-reports", "volunteer_reports_page");
    add_submenu_page("bbbs-volunteer", "Support Videos", "Support Videos", "manage_options", "bbbs-support-videos", "bbbs_support_videos");
}

require_once(__DIR__ . "/includes/UserEnrollment.php");
require_once(__DIR__ . "/includes/EnrollmentCollection.php");
require_once(__DIR__ . "/includes/EnrollmentForms.php");

function volunteer_exports_page() {
    ?>
    <script type="text/javascript">
    window.location = '/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries';
    </script>
    <?php
}

function volunteer_dashboard_page() {
    $volunteersFinished = 0;
    $applicationCompleted = 0;
    $volunteersEnrollDate = [];

    $ec = new EnrollmentCollection();
    $enrolled = $ec->allEnrollments();

    $ef = new EnrollmentForms();
    $totalFormCount = count($ef->getAllForms());

    foreach ($enrolled as $e) {
        $completedForms = $e->getCompletedForms();
        foreach ($completedForms as $c) {
            $form = $ef->getFormById($c["form_id"]);
            if ($form["title"] == "Volunteer Application") {
                $applicationCompleted++;
                break;
            }
        }

        if (count($completedForms) == $totalFormCount) {
            $volunteersFinished++;
        }

        array_push($volunteersEnrollDate, $e->getCreatedAt('U'));
    }
    ?>
    <style type="text/css">
        .vol-dash-metric {
            font-size: 16px;
            float: left;
            width: 30%;
            border-left: 4px solid #AAA;
            padding-left: 10px;
        }
        .vol-graph-header {
            font-size: 14px;
            margin-top: 30px;
            border-bottom: 1px solid #AAA;
            padding: 7px 3px;
            float: left;
            clear: both;
        }

        #vol-enroll-chart-container {
            width: 80%;
        }
    </style>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

    <h1>BBBS Volunteer Dashboard</h1>
    <h4 class="vol-dash-metric"><?=count($enrolled)?> Enrolled Volunteers</h4>
    <h4 class="vol-dash-metric"><?=$applicationCompleted?> Volunteer Applications Completed</h4>
    <h4 class="vol-dash-metric"><?=$volunteersFinished?> Finished Volunteers</h4>
    
    <h5 class="vol-graph-header">Volunteers Enrolled By Day (last week)</h5>
    <div id="vol-enroll-chart-container"><canvas id="vol-enroll-chart" width="400" height="100"></canvas></div>

    <script type="text/javascript">
        jQuery(document).ready( function () {
            build_enrollment_chart();
        } );

        function build_enrollment_chart() {
            var enrolled_users = JSON.parse('<?=json_encode($volunteersEnrollDate)?>');
  
            var now = moment();
            var beginning = now.subtract(1, 'weeks').startOf('day');
            
            var buckets = [];
            for (var x = 0;x <= 7; x++) {
                var d = moment(beginning).add(x, 'day').endOf('day');
                buckets.push({ 'date': d, 'ts': d.unix(), 'count': 0 });
            }

            enrolled_users.forEach(u => {
                for (var x = 0;x < buckets.length;x++) {
                    if (u > buckets[x]['ts']) {
                        if (x >= buckets.length || u <= buckets[x + 1]['ts']) {
                            buckets[x]['count']++;
                            break;
                        }
                    }
                }
            });

            var chartData = { labels: [], datasets: [ { label: '# of Enrolled Volunteers', data: [], backgroundColor: [] } ] };
            for (var x = 0;x < buckets.length - 1;x++) {
                chartData['labels'].push(buckets[x]['date'].add(12, 'hours').format('ddd M/D'));
                chartData['datasets'][0]['data'].push(buckets[x]['count']);
                chartData['datasets'][0]['backgroundColor'].push('rgba(66, 158, 244, 0.2)');
            }

            var ctx = document.getElementById("vol-enroll-chart");
            var chart = new Chart(ctx, {
                type: 'bar',
                data: chartData
            });
        }
    </script>
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

    $ef = new EnrollmentForms();
    $forms = $ef->getAllForms();
    $totalFormCount = count($forms);

    $volunteerForms = $ef->getVolunteerForms();
    $staffForms = $ef->getStaffForms();


    /*
    $altForms = array_map(function($cur) {
        return array(
            "id"=>$cur['id'],
            "title"=>$cur['title'],
            "form_visibility"=>$cur['form_visibility'],
            "form_order"=>$cur['form_order']
        );
    },$forms);

    echo "<pre>";
    var_dump($altForms);
    echo "</pre>";
    */



    ?>

    <h1><?php echo $userEnrollment->getFirstName(); ?> <?php echo $userEnrollment->getLastName(); ?></h1>

    <p>
        <label>Accounted Created On</label>
        <span><?php $caDate = $userEnrollment->getCreatedAt(); echo ($caDate) ? $caDate : "Not Created"; ?></span>
    </p>

    <p>
        <label>Last Update On</label>
        <span><?php $luDate = $userEnrollment->getLastUpdatedAt(); echo ($luDate) ? $luDate : "No Form Submissions";?></span>
    </p>

    <p>
        <label>Completed Form Count</label>
        <span><?php echo $userEnrollment->getUniqueCompletedFormCount(); ?></span>
    </p>

    <h2>Volunteer Form Status</h2>

    <ul>
        <?php foreach($volunteerForms as $form): ?>
            <li style="list-style: square; margin-left: 15px;">
                <?php $entryId = $userEnrollment->completedFormEntryId($form['id']); ?>

                <?php if ($entryId !== false): ?>
                    <a href="?page=gf_entries&view=entry&id=<?php echo $form['id']; ?>&lid=<?php echo $entryId; ?>&order=ASC&filter&paged=1&field_id&operator#"><?php echo $form['title']; ?></a>
                <?php else: ?>
                    <?php echo $form['title']; ?>
                <?php endif; ?>


                <?php if ($userEnrollment->hasCompletedForm($form['id'])): ?>
                ✅
                <?php else: ?>
                ❌
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Staff Form Status</h2>

    <ul>
        <?php foreach($staffForms as $form): ?>
            <li style="list-style: square; margin-left: 15px;">
                <?php $entryId = $userEnrollment->completedFormEntryId($form['id']); ?>

                <?php if ($entryId !== false): ?>
                    <a href="?page=gf_entries&view=entry&id=<?php echo $form['id']; ?>&lid=<?php echo $entryId; ?>&order=ASC&filter&paged=1&field_id&operator#"><?php echo $form['title']; ?></a>
                <?php else: ?>
                    <?php echo $form['title']; ?>
                <?php endif; ?>


                <?php if ($userEnrollment->hasCompletedForm($form['id'])): ?>
                ✅
                <?php else: ?>
                ❌
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <p>
        <a href="?page=bbbs-reports" class="button button-primary">Return To Report</a>
    </p>

    <?php
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

function bbbs_support_videos() {

    ?>

    <h1>Admin Support Videos</h1>

    <ul>
        <li style="list-style: square; margin-left: 15px;">
            <a href="https://drive.google.com/open?id=10mDaKm9lzuM6V562nHpf8u6oytMvpSpK" target="_blank">Intro</a>
        <li>
        <li style="list-style: square; margin-left: 15px;">
            <a href="https://drive.google.com/open?id=1zfZhy2nKJsrWFR7F_U_BYCQcYkUEnsyZ" target="_blank">Gravity Form Modifications</a>
        <li>
        <li style="list-style: square; margin-left: 15px;">
            <a href="https://drive.google.com/open?id=1OT2VHJlyQotTiqc7SjRTCfoDjQQ5lJIv" target="_blank">Entry Access and Reporting</a>
        <li>
        <li style="list-style: square; margin-left: 15px;">
            <a href="https://drive.google.com/open?id=1AfbGCuZTtyCW3RKohYFKIGJBgloZO-Tl" target="_blank">Data Exporting</a>
        <li>
        <li style="list-style: square; margin-left: 15px;">
            <a href="https://drive.google.com/open?id=1Sgr5C_hxn72yrJGbQ2MBgTFIZT9es237" target="_blank">Removing Users and Misc</a>
        <li>
    </ul>

    <?php
}
?>