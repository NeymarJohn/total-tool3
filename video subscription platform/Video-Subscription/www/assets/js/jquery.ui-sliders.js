$(document).ready(function () {
    // With JQuery
    $('#slider').slider({
        formatter: function(value) {
            $('iframe, object[id="flash"]')
            	.width(value)
            	.height(value/1.2);
        }
    });
});
