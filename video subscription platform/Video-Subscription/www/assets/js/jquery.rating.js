;(function ($) {
	$(function () {
		$('#rating').raty({
			score: function () {
				return $(this).attr('data-score');
			},
			click: function (score) {
				$.get("/video/updateNote/"+$(this).attr('data-video')+'/'+score);
				swal({
					title: "Note saved!",
					text: "I will close in 2 seconds.",
					timer: 2000,
					showConfirmButton: false
				});
			},
			half: true,
			starHalf: 'fa fa-star-half-o text-warning',
			starOff: 'fa fa-star-o text-warning',
			starOn: 'fa fa-star text-warning'
		});
		$('#nr-rating').raty({
			score: function () {
				return $(this).attr('data-score');
			},
			click: function (score) {
				swal({
					title: "Not registered!",
					text: "You must be logged in to use this feature.",
					timer: 2000,
					showConfirmButton: false
				});
			},
			half: true,
			starHalf: 'fa fa-star-half-o text-warning',
			starOff: 'fa fa-star-o text-warning',
			starOn: 'fa fa-star text-warning'
		});
	});
})(jQuery);
