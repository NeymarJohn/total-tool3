!function($) {
    "use strict";
    var Nestable = function() {};
    Nestable.prototype.updateOutput = function (e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output'),
            menu = $('.dd-item').data('menu', $('#mainMenu'));
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            console.log(list.nestable('serialize'));
            $.ajax({
                url: '/menus/update/',
                type: "post",
                data : {
                    list: window.JSON.stringify(list.nestable('serialize')),
                    menu: menu.attr('data-menu')
                },
                beforeSend: function() {
                  $(".loader").show();
                }
            }).done(function (data) {

            }).fail(function () {
                console.error(data);
            }).always(function () {

            });
        } else {
            output.val('JSON browser support required.');
        }
    },
    //init
    Nestable.prototype.init = function() {
        $('#defaultPages').nestable();
        $('#pages').nestable();
        $('#categories').nestable();
        $('#postCategories').nestable();
        $('#mainMenu').nestable().on('change', this.updateOutput);
        // output initial data sending
        this.updateOutput($('#mainMenu').data('output', $('#mainMenu_output')));
        // expand buttons
        $('#nestable_list_menu').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
    },
    //init
    $.Nestable = new Nestable, $.Nestable.Constructor = Nestable
}(window.jQuery),
//initializing
function($) {
    "use strict";
    $.Nestable.init()
}(window.jQuery);
