!function ($) {
    "use strict";

    var SweetAlert = function () {
    };

    SweetAlert.prototype.init = function () {
        //Auto Close Not registed
        $('.sa-not-registed').click(function () {
            swal({
                title: "Not registered!",
                text: "You must be logged in to use this feature.",
                timer: 2000,
                showConfirmButton: false
            });
        });
    },
        //init
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.SweetAlert.init()
    }(window.jQuery);
