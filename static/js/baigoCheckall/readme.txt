v1.0 jQuery baigoCheckall plugin 表单全选插件
(c) 2015 baigo studio - http://www.baigo.net/jquery/baigocheckall.html
License: http://www.opensource.org/licenses/mit-license.php

文件
baigoCheckall.js 全选插件
baigoCheckall.min.js 全选插件压缩版
readme.txt 使用说明

载入 jQuery 库文件，jQuery 库文件请到 http://www.jquery.com 下载，例：
<script src="jquery.min.js" type="text/javascript"></script>

载入 baigoCheckall 核心文件，例：
<script src="baigoCheckall.min.js" type="text/javascript"></script>

在需要操作的 checkbox 中定义 id 和 data-parent，其中，id 代表选框本身，data-parent 代表父元素的 id，例：
<input type="checkbox" id="first" data-parent="none">
<input type="checkbox" id="second_1" data-parent="first">
<input type="checkbox" id="second_2" data-parent="first">
<input type="checkbox" id="second_3" data-parent="first">
<input type="checkbox" id="second_4" data-parent="first">
特别提示：v1.0 之前，由 class 定义父元素的 id，v1.0 之后改为 data-parent 属性。

初始化插件，例：
<script type="text/javascript">
$(document).ready(function(){
  $("#form_demo").baigoCheckall();
});
</script>

当用户选中 id 为 first 的选框的时候，data-parent 为 first 的选框将全部被选中，反之则为未选中；当任意一个 data-parent 为 first 的选框未被选中时，id 为 first 的选框也会处于未被选中状态。