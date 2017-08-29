$(function(){
	var menus;
	var _el;
	var baigoAccordion = function(el, multiple) {
        "use strict";
		this.el = el || {};
		this.multiple = multiple || false;

		// Variables privadas
		menus = this.el.find(".menu");
		// Evento
		menus.on("click", {
        		el: this.el,
        		multiple: this.multiple
    		},
    		this.dropdown
        );
	};

	baigoAccordion.prototype.dropdown = function(e) {
		_el = e.data.el,
			_this = $(this),
			_next = _this.next();

		_next.slideToggle();
		_this.parent().toggleClass("open");

		if (!e.data.multiple) {
			_el.find(".submenu").not(_next).slideUp().parent().removeClass("open");
		}
	};

	var accordion = new baigoAccordion($("[data-toggle='baigoAccordion']"), false);
});