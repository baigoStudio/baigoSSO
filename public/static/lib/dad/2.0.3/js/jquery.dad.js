/*!
 * jquery.dad.js v2 (http://konsolestudio.com/dad)
 * Author William Lima
 */

(function ($) {
  "use strict";

  var global = {};

  global.supportsTouch = "ontouchstart" in window || navigator.msMaxTouchPoints;
  global.shouldScroll = true;

  if (global.supportsTouch) {
    var scrollListener = function (e) {
      if (!global.shouldScroll) {
        e.preventDefault();
      }
    };

    document.addEventListener("touchmove", scrollListener, { passive: false });
  }

  /**
   * Mouse constructor
   */
  function DadMouse() {
    this.positionX = 0;
    this.positionY = 0;
    this.offsetX = 0;
    this.offsetY = 0;
  }

  /**
   * Mouse udpate event
   * @param {Event}
   */
  DadMouse.prototype.update = function (e) {
    // Check if it is touch
    if (global.supportsTouch && e.type == "touchmove") {
      var targetEvent = e.originalEvent.touches[0];
      var mouseTarget = document.elementFromPoint(
        targetEvent.clientX,
        targetEvent.clientY
      );
      $(mouseTarget).trigger("touchenter");

      // update mouse coordinates from touch
      this.positionX = targetEvent.pageX;
      this.positionY = targetEvent.pageY;
    } else {
      this.positionX = e.pageX;
      this.positionY = e.pageY;
    }
  };

  /**
   * DAD class constructor
   * @param {element} element
   * @param {options} options
   */
  function Dad(element, options) {
    this.options = this.parseOptions(options);

    // jQuery elements
    this.$container = $(element);
    this.$current = null;
    this.$target = null;
    this.$clone = null;

    // Inner variables
    this.mouse = new DadMouse();
    this.holding = false;
    this.dragging = false;
    this.dropzones = [];

    // Configure and setup
    this.setActive(this.options.active);
    this.setup();
  }

  /**
   * Static attribute that stores default dad options
   */
  Dad.defaultOptions = {
    active: true,
    draggable: false,
    exchangeable: true,
    placeholderTarget: false,
    placeholderTemplate: "<div />",
    placeholderClass: "dad-placeholder",
    targetClass: "dad-target",
    cloneClass: "dad-clone",
    transition: 200,
  };

  /**
   * Merge provided options with the defaults
   */
  Dad.prototype.parseOptions = function (options) {
    // Make defaults immutable
    var parsedOptions = $.extend(true, {}, Dad.defaultOptions);

    if (options) {
      $.each(parsedOptions, function (key, value) {
        var overrideValue = options[key];

        if (typeof overrideValue !== "undefined") {
          // Valid for arrays as well
          if (typeof overrideValue === "object") {
            parsedOptions[key] = $.extend(parsedOptions[key], overrideValue);
          } else {
            parsedOptions[key] = overrideValue;
          }
        }
      });
    }

    return parsedOptions;
  };

  /**
   * Add all required listeners and
   * styles that prevents some issues when dragging
   */
  Dad.prototype.setup = function () {
    var self = this;

    // Prevent user from highlight text
    this.$container.css({
      position: "relative",
      "-webkit-touch-callout": "none",
      "-webkit-user-select": "none",
      "-khtml-user-select": "none",
      "-moz-user-select": "none",
      "-ms-user-select": "none",
      "user-select": "none",
    });

    // Prevent dragging images on IE
    this.$container.find("img").attr("ondragstart", "return false");

    // Create a callback for click event
    function onChildClick(e) {
      var $target = $(this);
      self.prepare(e, $target);
    }

    // Create a callback for enter event
    function onChildEnter(e) {
      if (self.$current) {
        var $this = $(this);
        var isFromCurrent = !!self.$current.find(this).length;
        var isExchangeable = self.options.exchangeable;

        var shouldExchange = self.dragging && (isFromCurrent || isExchangeable);

        if (shouldExchange) {
          self.updatePlaceholder(e, $this);
        }
      }
    }

    // Set container communication
    this.$container.on("mouseenter touchenter", function (e) {
      if (self.$current) {
        var $this = $(this);
        var isNotCurrent = !$this.is(self.$current);
        var isExchangeable = self.options.exchangeable;

        var shouldExchange = self.dragging && isNotCurrent && isExchangeable;

        if (shouldExchange) {
          self.updatePlaceholder(e, $this, true);
        }
      }
    });

    // Add element event listeners
    this.$container.on("mousedown touchstart", "> *", onChildClick);
    this.$container.on("mouseenter touchenter", "> *", onChildEnter);

    // Add window event listeners
    $("body").on("mousemove touchmove", this.update.bind(this));
    $("body").on("mouseup touchend", this.end.bind(this));

    // Cancelling drag due to browser native actions
    // Note: Using window on mouseleave causes a bug...
    $("body").on("mouseleave", this.end.bind(this));
    $(window).on("blur", this.end.bind(this));
  };

  /**
   * Prepare container to start dragging
   *
   * @param {*} event click/mousedown event
   * @param {*} element target element
   */
  Dad.prototype.prepare = function (e, $target) {
    var draggable = this.options.draggable;
    var $draggable = draggable && $(draggable);
    var shouldStartDragging =
      this.active &&
      ($draggable
        ? $draggable.is(e.target) || $draggable.find(e.target).length
        : true);

    if (shouldStartDragging) {
      this.holding = true;
      this.$target = $target;
      this.$current = $target.closest(this.$container);
      this.mouse.update(e);
    }
  };

  /**
   * First step, occurs on mousedown
   * @param {Event}
   */
  Dad.prototype.start = function (e) {
    var options = this.options;
    var $target = this.$target;
    var $current = this.$current;

    // Add clone
    var $clone = $target.clone().css({
      position: "absolute",
      zIndex: 9999,
      pointerEvents: "none",
      height: $target.outerHeight(),
      width: $target.outerWidth(),
    });

    // Add placeholder
    var $placeholder = $(options.placeholderTemplate).css({
      position: "absolute",
      pointerEvents: "none",
      zIndex: 9998,
      margin: 0,
      padding: 0,
      height: $target.outerHeight(),
      width: $target.outerWidth(),
    });

    // Set mouse offset values
    this.mouse.offsetX = this.mouse.positionX - $target.offset().left;
    this.mouse.offsetY = this.mouse.positionY - $target.offset().top;

    // Hide target
    $target.css("visibility", "hidden");

    // Add custom classes
    $target.addClass(options.targetClass);
    $clone.addClass(options.cloneClass);
    $placeholder.addClass(options.placeholderClass);

    // Setting variables
    if (global.supportsTouch) global.shouldScroll = false;

    this.dragging = true;
    this.$target = $target;
    this.$clone = $clone;
    this.$placeholder = $placeholder;

    // Add elements to container
    $current.append($placeholder).append($clone);

    // Set clone and placeholder position
    this.updateClonePosition();
    this.updatePlaceholderPosition();

    // Trigger custom events
    $($current).trigger("dadDragStart", [$target[0]]);
  };

  /**
   * Middle step, occurs on mousemove
   */
  Dad.prototype.update = function (e) {
    this.mouse.update(e);

    // If user is holding but not dragging
    // Call start method
    if (this.holding && !this.dragging) {
      this.start(e);
    }

    if (this.dragging) {
      this.updateClonePosition();
    }
  };

  /**
   * Final step, ocurrs on mouseup
   */
  Dad.prototype.end = function (e) {
    this.holding = false;

    // Finish dragging if is dragging
    if (this.dragging) {
      if (global.supportsTouch) global.shouldScroll = true;

      var options = this.options;
      var $current = this.$current;
      var $target = this.$target;
      var $clone = this.$clone;
      var $placeholder = this.$placeholder;

      var animateToX = $target.offset().left - this.getContainerX();
      var animateToY = $target.offset().top - this.getContainerY();

      // Trigger callback
      $($current).trigger("dadDragEnd", [$target[0]]);

      // Do transition from clone to target
      $clone.animate(
        {
          top: animateToY,
          left: animateToX,
          height: $target.outerHeight(),
          width: $target.outerWidth(),
        },
        this.options.transition,
        function () {
          // Remove dad elements
          $clone.remove();
          $placeholder.remove();

          // Normalize target
          $target.removeClass(options.targetClass);
          $target.css("visibility", "");

          // On dad dropped
          $($current).trigger("dadDrop", [$target[0]]);
        }
      );

      // Reset variables
      this.dragging = false;

      // Reset elements
      this.$current = null;
      this.$target = null;
      this.$clone = null;
      this.$placeholder = null;
    }
  };

  /**
   * Get current container X position, including horizontal scroll
   */
  Dad.prototype.getContainerX = function () {
    return this.$current.offset().left - this.$current.scrollLeft()
  }

  /**
   * Get current container Y position, including vertical scroll
   */
  Dad.prototype.getContainerY = function () {
    return this.$current.offset().top - this.$current.scrollTop()
  }

  /**
   * Dad update clone position based on the mouse position
   */
  Dad.prototype.updateClonePosition = function () {
    // Get positions
    var targetX =
      this.mouse.positionX - this.getContainerX() - this.mouse.offsetX;
    var targetY =
      this.mouse.positionY - this.getContainerY() - this.mouse.offsetY;

    // Update clone
    this.$clone.css({ top: targetY, left: targetX });
  };

  /**
   * Dad update placeholder position by
   * checking the current placeholder position
   */
  Dad.prototype.updatePlaceholder = function (e, $element, isContainer) {
    var $current = this.$current;
    var $target = this.$target;
    var $clone = this.$clone;
    var $placeholder = this.$placeholder;

    if (isContainer) {
      // Move target
      $element.append($target);

      // And also move dad elements for positioning
      $element.append($clone);
      $element.append($placeholder);

      // Update current container
      this.$current = $element;

      // Exchange custom event
      $($current).trigger("dadDragExchange", [$current[0], $element[0]]);
    } else {
      if ($element.index() > $target.index()) {
        $element.after($target);
      } else {
        $element.before($target);
      }

      // Update custom event
      $($current).trigger("dadDragUpdate", [$target[0]]);
    }

    this.updatePlaceholderPosition();
  };

  /**
   * Update placeholder position based on its options
   */
  Dad.prototype.updatePlaceholderPosition = function () {
    var placeholderTarget = this.options.placeholderTarget;

    var $target = placeholderTarget
      ? this.$target.find(placeholderTarget)
      : this.$target;

    var targetTop = $target.offset().top - this.getContainerY();
    var targetLeft = $target.offset().left - this.getContainerX();

    var targetHeight = $target.outerHeight();
    var targetWidth = $target.outerWidth();

    this.$placeholder.css({
      top: targetTop,
      left: targetLeft,
      width: targetWidth,
      height: targetHeight,
    });
  };

  Dad.prototype.onDrop = function (selector, onDrop) {
    var $dropzone = $(selector);

    function onDropzoneEnter(e) {
      var $this = $(this);

      $this.attr("data-dad-active", true);
    }

    $dropzone.on("mouseenter touchenter", onDropzoneEnter);
  };

  /**
   * Update container active status which later
   * will prevent the dragging to start on the prepare function
   */
  Dad.prototype.setActive = function (isActive) {
    this.active = isActive;
    this.$container.attr("data-dad-active", isActive);
  };

  Dad.prototype.activate = function () {
    this.setActive(true);
  };

  Dad.prototype.deactivate = function () {
    this.setActive(false);
  };

  $.fn.dad = function (options) {
    return new Dad(this, options);
  };
})(jQuery);
