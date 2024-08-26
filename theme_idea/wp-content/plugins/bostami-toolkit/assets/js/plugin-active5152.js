(function ($) {
	"use strict";

	/*
	|--------------------------------------------------------------------------
	| Template Name: Bostami
	| Author: ib-themes
	| Version: 1.0.0
	|--------------------------------------------------------------------------
	|--------------------------------------------------------------------------
	| TABLE OF CONTENTS:
	|--------------------------------------------------------------------------
	|
	| 1. Preloader
	| 2. Mobile Menu
	| 3. Sticky Header
	| 4. Dynamic Background
	| 5. Slick Slider
	| 6. Parallax Slider Swiper
	| 7. Isotop Initialize
	| 8. Review
	| 9. Modal Video
	| 10. Tabs
	| 11. Accordian
	| 12. Counter Animation
	| 13. Ripple
	| 14. Progress Bar
	| 15. Dynamic contact form
	|
	*/

	// portfolio fillter start
	function enableMasonry2() {
	  // ----------------------------- isotop gallery
  
	  $(window).on("load", function () {
		if ($("#fillter-item-active").length) {
		  var $grid = $("#fillter-item-active").isotope({
			// options
			itemSelector: ".isotop-item",
			percentPosition: true,
			masonry: {
			  // use element for option
			  columnWidth: ".grid-sizer",
			},
		  });
  
		  // filter items on button click
		  $(".isotop-menu-wrapper").on("click", "li", function () {
			var filterValue = $(this).attr("data-filter");
			$grid.isotope({ filter: filterValue });
		  });
  
		  // change is-checked class on buttons
		  $(".isotop-menu-wrapper").each(function (i, buttonGroup) {
			var $buttonGroup = $(buttonGroup);
			$buttonGroup.on("click", "li", function () {
			  $buttonGroup.find(".is-checked").removeClass("is-checked");
			  $(this).addClass("is-checked");
			});
		  });
		}
	  });
	}
  
	enableMasonry2();
	// portfolio filter end
  
	
  
	// contact form
	$(".input-box.name").click(function () {
	  $(".input-box.name").addClass("height");
	  $(".input-box.name").css("borderBottom", "1px solid #FE7878");
	  $(".input-lebel.name").css("color", "#FE7878");
	});
  
	$(".input-box.gmail").click(function () {
	  $(".input-box.gmail").addClass("height");
	  $(".input-box.gmail").css("borderBottom", "1px solid #1B74E4");
	  $(".input-lebel.gmail").css("color", "#1B74E4");
	});
  
	$(".input-box.message").click(function () {
	  $(".input-box.message").addClass("height");
	  $(".input-box.message").css("borderBottom", "1px solid #CE65F3");
	  $(".input-lebel.message").css("color", "#CE65F3");
	});
	// contact form end
  
	// data background
	$("[data-background]").each(function () {
	  $(this).css(
		"background-image",
		"url(" + $(this).attr("data-background") + ") "
	  );
	});
  
	// //   odometer
	// $(".odometer").appear(function (e) {
	//   var odo = $(".odometer");
	//   odo.each(function () {
	// 	var countNumber = $(this).attr("data-count");
	// 	$(this).html(countNumber);
	//   });
	// });
  
	// WOW active
	new WOW().init();
  })(jQuery);
  