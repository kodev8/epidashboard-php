<?php
// 404 - Not Found

$intro_folder = 404;
$terminal_content = '
<span class="terminal-text center-self">Page not found !</span>

';

$button_content = "<a class='login-btn' href=\"javascript:history.go(-1)\"> Go Back  </a>";

require view('template/login.template.php');

?>


