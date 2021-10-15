/*
v0.1.2 jQuery baigoUbb plugin UBB 编辑器
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
  $.fn.baigoUbb = function(options) {
    'use strict';
    if (this.length < 1) {
      return this;
    }

    var obj_field     = $(this);
    var obj_selection = obj_field[0];

    var opts_default = {
      menu_selector: '.bg-ubb-menu',
      menu_text: {
        color: 'Color',
        bgcolor: 'Background color',
        heading: 'Heading'
      },
      format: {
        strong: {
          text: 'Bold',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M333.49 238a122 122 0 0 0 27-65.21C367.87 96.49 308 32 233.42 32H34a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h31.87v288H34a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h209.32c70.8 0 134.14-51.75 141-122.4 4.74-48.45-16.39-92.06-50.83-119.6zM145.66 112h87.76a48 48 0 0 1 0 96h-87.76zm87.76 288h-87.76V288h87.76a56 56 0 0 1 0 112z"/></svg>'
        },
        i: {
          text: 'Italic',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M320 48v32a16 16 0 0 1-16 16h-62.76l-80 320H208a16 16 0 0 1 16 16v32a16 16 0 0 1-16 16H16a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h62.76l80-320H112a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h192a16 16 0 0 1 16 16z"/></svg>'
        },
        u: {
          text: 'Underline',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M32 64h32v160c0 88.22 71.78 160 160 160s160-71.78 160-160V64h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16H272a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h32v160a80 80 0 0 1-160 0V64h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16H32a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16zm400 384H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"/></svg>'
        }
      },
      color: {
        aqua: {
          text: 'Aqua',
          ext: '#333'
        },
        black: 'Black',
        blue: 'Blue',
        fuchsia: 'Fuchsia',
        gray: 'Gray',
        green: 'Green',
        lime: {
          text: 'Lime',
          ext: '#333'
        },
        maroon: 'Maroon',
        navy: 'Navy',
        olive: 'Olive',
        purple: 'Purple',
        red: 'Red',
        silver: 'Silver',
        teal: 'Teal',
        white: {
          text: 'White',
          ext: '#333'
        },
        yellow: {
          text: 'Yellow',
          ext: '#333'
        }
      },
      bgcolor: {
        aqua: {
          text: 'Aqua',
          ext: '#000'
        },
        black: 'Black',
        blue: 'Blue',
        fuchsia: 'Fuchsia',
        gray: 'Gray',
        green: 'Green',
        lime: {
          text: 'Lime',
          ext: '#000'
        },
        maroon: 'Maroon',
        navy: 'Navy',
        olive: 'Olive',
        purple: 'Purple',
        red: 'Red',
        silver: 'Silver',
        teal: 'Teal',
        white: {
          text: 'White',
          ext: '#000'
        },
        yellow: {
          text: 'Yellow',
          ext: '#000'
        }
      },
      heading: {
        h1: 'Heading 1',
        h2: 'Heading 2',
        h3: 'Heading 3',
        h4: 'Heading 4',
        h5: 'Heading 5',
        h6: 'Heading 6'
      },
      dialog: {
        img: {
          text: 'Image',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 448H48c-26.51 0-48-21.49-48-48V112c0-26.51 21.49-48 48-48h416c26.51 0 48 21.49 48 48v288c0 26.51-21.49 48-48 48zM112 120c-30.928 0-56 25.072-56 56s25.072 56 56 56 56-25.072 56-56-25.072-56-56-56zM64 384h384V272l-87.515-87.515c-4.686-4.686-12.284-4.686-16.971 0L208 320l-55.515-55.515c-4.686-4.686-12.284-4.686-16.971 0L64 336v48z"/></svg>',
          ext: ['src', 'text']
        },
        url: {
          text: 'Link',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"/></svg>',
          ext: ['href', 'text']
        }
      },
      single: {
        hr: {
          text: 'Horizontal rule',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"/></svg>'
        },
        br: {
          text: 'Break line',
          icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M448 48v32a16 16 0 0 1-16 16h-48v368a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V96h-32v368a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V352h-32a160 160 0 0 1 0-320h240a16 16 0 0 1 16 16z"/></svg>'
        }
      }
    };

    var opts = $.extend(true, opts_default, options);

    var menu_tpl = '<div class="bg-ubb-menu mb-2">' +
      '<div class="btn-toolbar">' +
        '<div class="btn-group btn-group-sm mr-2 bg-ubb-format"></div>' +
        '<div class="btn-group btn-group-sm mr-2">' +
          '<div class="btn-group btn-group-sm">' +
            '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
               opts.menu_text.heading +
            '</button>' +
            '<div class="dropdown-menu bg-ubb-heading"></div>' +
          '</div>' +
          '<div class="btn-group btn-group-sm">' +
            '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
               opts.menu_text.color +
            '</button>' +
            '<div class="dropdown-menu bg-ubb-color"></div>' +
          '</div>' +
          '<div class="btn-group btn-group-sm">' +
            '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
               opts.menu_text.bgcolor +
            '</button>' +
            '<div class="dropdown-menu bg-ubb-bgcolor"></div>' +
          '</div>' +
        '</div>' +
        '<div class="btn-group btn-group-sm mr-2 bg-ubb-dialog"></div>' +
        '<div class="btn-group btn-group-sm bg-ubb-single"></div>' +
      '</div>' +
    '</div>';

    if ($(opts.menu_selector).length < 1) {
      obj_field.before(menu_tpl);
    }

    var process = {
      addMenu: function(menus, selector, type) {
        var menu_html = '';

        $.each(menus, function(_key, _value){
          var _show_text  = '';
          var _show_str   = '';
          var _show_icon  = '';
          var _ext_param  = '';

          if (typeof _value == 'string') {
            _show_text = _value;
          } else if (typeof _value == 'object') {
            if (typeof _value.text == 'string') {
              _show_text = _value.text;
            }

            if (typeof _value.icon == 'string') {
              _show_str = _value.icon;
            } else {
              _show_str = _show_text;
            }

            if (typeof _value.ext == 'string') {
              _ext_param = _value.ext;
            }
          }

          menu_html += '<button type="button"';

          switch (type) {
            case 'heading':
              _show_str = '<' + _key + '>' + _show_text + '</' + _key + '>';
              menu_html += ' class="dropdown-item bg-pair" data-tag="' + _key + '"';
            break;

            case 'color':
              _show_str = '<span style="color:' + _key + ';';

              if (_ext_param.length > 0) {
                _show_str += 'background-color:' + _ext_param + ';';
              }

              _show_str += '">' + _show_text + '</span>';

              menu_html += ' class="dropdown-item bg-tag" data-tag="color" data-value="' + _key + '"';
            break;

            case 'bgcolor':
              if (_ext_param.length < 1) {
                _ext_param = 'white';
              }

              _show_str = '<span style="background-color:' + _key + ';color:' + _ext_param + ';">' + _show_text + '</span>';

              menu_html += ' class="dropdown-item bg-tag" data-tag="bgcolor" data-value="' + _key + '"';
            break;

            case 'format':
              menu_html += ' class="btn btn-outline-secondary bg-pair" data-tag="' + _key + '"';
            break;

            default:
              menu_html += ' class="btn btn-outline-secondary bg-' + type + '" data-tag="' + _key + '"';
            break;
          }

          menu_html += ' title="' + _show_text + '">' + _show_str + '</button>';
        });

        $(opts.menu_selector + ' ' + selector).html(menu_html);
      }
    };

    var el = {
      addSingle: function(tag) {
        if (typeof tag != 'undefined' && tag.length > 0) {
          var _len     = obj_selection.value.length;
          var _begin   = obj_selection.selectionStart;
          var _end     = obj_selection.selectionEnd;

          obj_selection.value = obj_selection.value.substring(0, _begin) + '[' + tag + ']' + obj_selection.value.substring(_end, _len);
        }
      },
      addPair: function(tag) {
        if (typeof tag != 'undefined' && tag.length > 0) {
          var _len     = obj_selection.value.length;
          var _begin   = obj_selection.selectionStart;
          var _end     = obj_selection.selectionEnd;

          var _select  = obj_selection.value.substring(_begin, _end);

          if (_select.length > 0) {
            var _replace = '[' + tag + ']' + _select + '[/' + tag + ']';

            obj_selection.value = obj_selection.value.substring(0, _begin) + _replace + obj_selection.value.substring(_end, _len);
          }
        }
      },
      addTag: function(tag, value) {
        if (typeof tag != 'undefined' && tag.length > 0) {
          var _len     = obj_selection.value.length;
          var _begin   = obj_selection.selectionStart;
          var _end     = obj_selection.selectionEnd;

          var _select  = obj_selection.value.substring(_begin, _end);

          if (_select.length > 0) {
            var _replace = '[' + tag;

            if (typeof value != 'undefined' && value.length > 0 && typeof value == 'string') {
              _replace += '=' + value + ']';
            } else {
              _replace += ']';
            }

            _replace += _select + '[/' + tag + ']';

            obj_selection.value = obj_selection.value.substring(0, _begin) + _replace + obj_selection.value.substring(_end, _len);
          }
        }
      },
      insertTag: function(tag, value) {
        var _len     = obj_selection.value.length;
        var _begin   = obj_selection.selectionStart;

        if (typeof tag != 'undefined' && tag.length > 0 && typeof value != 'undefined' && value.length > 0) {
          var _insert = '[' + tag;

          if (typeof value == 'object') {
            if (typeof value[0] != 'undefined' && value[0].length > 0 && typeof value[0] == 'string' && typeof value[1] != 'undefined' && value[1].length > 0 && typeof value[1] == 'string') {
              _insert += '=' + value[0] + ']' + value[1];
            } else if (typeof value[0] != 'undefined' && value[0].length > 0 && typeof value[0] == 'string') {
              _insert += ']' + value[0];
            }
          } else if (typeof value == 'string') {
            _insert += ']' + value;
          }

          _insert += '[/' + tag + ']';

          obj_selection.value = obj_selection.value.substring(0, _begin) + _insert + obj_selection.value.substring(_begin, _len);
        }
      }
    };

    process.addMenu(opts.format, '.bg-ubb-format', 'format');
    process.addMenu(opts.heading, '.bg-ubb-heading', 'heading');
    process.addMenu(opts.color, '.bg-ubb-color', 'color');
    process.addMenu(opts.bgcolor, '.bg-ubb-bgcolor', 'bgcolor');
    process.addMenu(opts.dialog, '.bg-ubb-dialog', 'dialog');
    process.addMenu(opts.single, '.bg-ubb-single', 'single');

    obj_field.keyup(function(event){
      if (event.keyCode == 13) {
        el.addSingle('br');
      }
    });

    $(opts.menu_selector).on('click', '.bg-pair', function(){
      var _tag = $(this).data('tag');
      el.addPair(_tag);
    });

    $(opts.menu_selector).on('click', '.bg-single', function(){
      var _tag = $(this).data('tag');
      el.addSingle(_tag);
    });

    $(opts.menu_selector).on('click', '.bg-tag', function(){
      var _tag   = $(this).data('tag');
      var _value = $(this).data('value');
      el.addTag(_tag, _value);
    });

    $(opts.menu_selector).on('click', '.bg-dialog', function(){
      var _tag    = $(this).data('tag');
      var _input  = _tag;

      if (typeof opts.dialog[_tag] != 'undefined') {
        if (typeof opts.dialog[_tag] == 'string') {
          _input = opts.dialog[_tag];
        } else if (typeof opts.dialog[_tag] == 'object') {
          if (typeof opts.dialog[_tag].ext != 'undefined') {
            _input = opts.dialog[_tag].ext;
          } else if (typeof opts.dialog[_tag].text != 'undefined') {
            _input = opts.dialog[_tag].text;
          }
        }
      }

      var _obj_dialog = $.baigoDialog();

      _obj_dialog.input(_input, function(act, value){
        if (typeof act != 'undefined' && act == 'ok') {
          el.insertTag(_tag, value);
        }
      });
    });

    if (typeof options == 'string' && typeof el[options] != 'undefined') {
      el[options].apply(this, Array.prototype.slice.call(arguments, 1));
    }

    return el;
  };
})(jQuery);
