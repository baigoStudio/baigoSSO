/*重新载入图片*/
function reloadImg(imgId, imgSrc) {
	var _append = '&' + new Date().getTime() + 'at' + Math.random();
	$("#" + imgId).attr('src', imgSrc + _append);
}