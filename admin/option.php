<?php
/* Save default options */
$discy_admin_options = new discy_admin_options;
$default_options = $discy_admin_options->get_default_values();
if (!get_option(discy_options)) {
	add_option(discy_options,$default_options);
}
if (discy_theme_version >= 3.9) {
	if (get_option("discy_old_version_done") == "") {
		update_option("discy_old_version",get_option(discy_options));
		update_option("discy_old_version_done",true);
	}
}?>