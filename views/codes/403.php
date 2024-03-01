<?php
// unauthorized page

$intro_folder = 403;
$terminal_content = '
<span class="terminal-text response-code">You are not authorized to view this Page !</span>
';

$button_content = "<a class='login-btn' href=\"javascript:history.go(-1)\"> Go Back  </a>";

require view('template/login.template.php');

?>