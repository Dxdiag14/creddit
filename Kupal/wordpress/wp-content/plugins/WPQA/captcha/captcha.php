<?php

/* @author    2codeThemes
*  @package   WPQA/captcha
*  @version   1.0
*/

if (!session_id() && !headers_sent()) {
	session_start();
}
if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_register"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_register"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_register"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_login"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_login"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_login"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_password"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_password"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_password"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_post"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_post"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_post"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_category"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_category"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_category"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_question"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_question"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_question"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_group"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_group"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_group"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_message"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_message"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_message"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_comment"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_comment"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_comment"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_answer"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_answer"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_answer"])) {
	echo "wpqa_captcha_1";
}else if (isset($_REQUEST["wpqa_captcha"]) && isset($_SESSION["wpqa_code_captcha_custom"]) && $_REQUEST["wpqa_captcha"] == $_SESSION["wpqa_code_captcha_custom"] && !empty($_REQUEST["wpqa_captcha"]) && !empty($_SESSION["wpqa_code_captcha_custom"])) {
	echo "wpqa_captcha_1";
}else {
	echo "wpqa_captcha_0";
}?>