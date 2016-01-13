<?php
return "<h3>概述</h3>
    <ol>
        <li>升级前请务必 <mark>备份数据库</mark> 如果您在最初部署 baigo SSO 时，采用了高级部署方式，修改了 <mark>./config/config.inc.php</mark> 文件，请同时备份该文件；</li>
        <li>将下载到的程序包解压，然后将所有文件上传到服务器，假设网站 URL 为 <span class=\"text-primary\">http://www.domain.com</span> 上传到根目录 /，以下说明均以此为例；</li>
        <li>登录后台，即用浏览器打开 <span class=\"text-primary\">http://www.domain.com/admin/</span> 系统将自动跳转到升级界面。或者直接用浏览器打开 <span class=\"text-primary\">http://www.domain.com/install/ctl.php?mod=upgrade</span></li>
        <li>关于高级部署方式，请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=deploy\">高级部署</a>。</li>
    </ol>";
