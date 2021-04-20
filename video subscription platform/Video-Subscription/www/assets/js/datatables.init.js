var handleDataTableButtons = function() {
        "use strict";
        0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
            dom: "Bfrtip",
            buttons: [{
                extend: "copy",
                className: "btn-sm btn-inverse"
            }, {
                extend: "csv",
                className: "btn-sm btn-inverse"
            }, {
                extend: "excel",
                className: "btn-sm btn-inverse"
            }, {
                extend: "pdf",
                className: "btn-sm btn-inverse"
            }, {
                extend: "print",
                className: "btn-sm btn-inverse"
            }],
            responsive: !0
        })
    },
    TableManageButtons = function() {
        "use strict";
        return {
            init: function() {
                handleDataTableButtons()
            }
        }
    }();
