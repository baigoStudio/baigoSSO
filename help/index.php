<?php
include_once("../config/config.inc.php"); //载入配置
include_once(BG_PATH_FUNC . "common.func.php"); //载入通用函数

error_reporting(E_ALL & ~E_NOTICE);

$_arr_title = array(
	"en"       => "Help",
	"zh_CN"    => "帮助",
);

$_str_lang  = fn_get("lang");
$_str_mod   = fn_get("mod");
$_str_act   = fn_get("act");

if (!$_str_lang) {
	$_str_lang = "zh_CN";
}
if (!$_str_mod) {
	$_str_mod = "intro";
}
if (!$_str_act) {
	$_str_act = "index";
}

$_arr_alert = include_once(BG_PATH_LANG . $_str_lang . "/alert.php"); //载入提示代码
?>
<!DOCTYPE html>
<html lang="<?php echo($_str_lang); ?>">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>baigoSSO <?php echo($_arr_title[$_str_lang]); ?></title>
	<script src="../static/js/jquery.min.js" type="text/javascript"></script>
	<link href="../static/js/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
	<script src="../static/js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<link href="./css/help.css" type="text/css" rel="stylesheet">
</head>

<body>

	<?php
	include_once("./" . $_str_lang . "/head.php");
	?>

	<div class="container">
		<div class="row">
			<div class="col-md-9">
			<?php
			include_once("./" . $_str_lang . "/" . $_str_mod . "/title.php");
			include_once("./" . $_str_lang . "/" . $_str_mod . "/" . $_str_act . ".php");
			?>
			</div>
			<?php
			include_once("./" . $_str_lang . "/" . $_str_mod . "/menu.php");
			?>
		</div>
	</div>

	<?php
	include_once("./" . $_str_lang . "/foot.php");
	?>

</body>
</html>
