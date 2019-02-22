<?php
function bbbs_volunteer_menu() {
    add_menu_page("BBBS Volunteer", "BBBS Volunteer", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page" ,null, 15);
    bbbs_add_volunteer_submenu();
}

function bbbs_add_volunteer_submenu() {
    add_submenu_page("bbbs-volunteer", "BBBS Dashboard", "BBBS Dashboard", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page");
    add_submenu_page("bbbs-volunteer", "BBBS Reports", "BBBS Reports", "manage_options", "bbbs-reports", "volunteer_reports_page");
}

function volunteer_dashboard_page() {
?>
<h2>BBBS Volunteer Dashboard</h2>
<?php
}

function volunteer_reports_page() {
?>
<h2>BBBS Volunteer Reports</h2>
<?php
}
?>