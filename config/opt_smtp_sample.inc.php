<?php
define("BG_SMTP_HOST", "smtp" . $_SERVER["SERVER_NAME"]);
define("BG_SMTP_PORT", 25);
define("BG_SMTP_AUTH", "true");
define("BG_SMTP_USER", "user@" . $_SERVER["SERVER_NAME"]);
define("BG_SMTP_PASS", "password");
define("BG_SMTP_FROM", "noreplay@" . $_SERVER["SERVER_NAME"]);
define("BG_SMTP_REPLY", "replay@" . $_SERVER["SERVER_NAME"]);
