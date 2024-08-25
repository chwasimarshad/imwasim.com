(function ($) {
    "use strict";


// preloader start
window.addEventListener("load", function () {
	let preloader = document.querySelector("#preloader");
	
	// Check if the preloader element exists in the DOM
	if (preloader !== null) {
	  preloader.classList.add("preloaded");
	  setTimeout(function () {
		preloader.remove();
	  }, 1500);
	}
  });
  // preloader end
  
  window.addEventListener("load", function () {
	// meanmenu start
	$(".main_menu_1 > div").meanmenu({
	  meanMenuContainer: ".mobile_menu",
	  meanScreenWidth: "1399",
	});
  
	$(".main_menu_2 > div").meanmenu({
	  meanMenuContainer: ".mobile_menu",
	  meanScreenWidth: "991",
	});

	$(".main_menu_3 > div").meanmenu({
		meanMenuContainer: ".mobile_menu",
		meanScreenWidth: "991",
	  });
  
});

	// mobile menu start
	let menutoggole = document.querySelector(".toggle_menu");
	let mobilemenu = document.querySelector(".mobile-menu");
	menutoggole.onclick = function () {
	  menutoggole.classList.toggle("active");
	  mobilemenu.classList.toggle("active");
	};
	// mobile menu end
  
	// dark mood start
	var darktoggle = document.querySelector(".dark-btn-icon");
	
	  // Function to toggle the dark theme
	  function toggleDarkTheme() {
		// Toggle the class on the body element
		$("body").toggleClass("dark-theme");
	
		// Store the preference in local storage
		const isDarkTheme = $("body").hasClass("dark-theme");
		localStorage.setItem("darkTheme", isDarkTheme);
	
		if (isDarkTheme) {
		  darktoggle.src = "assets/img/icon/sun-icon.png";
		} else {
		  darktoggle.src = "assets/img/icon/mon-icon.png";
		}
	  }
	  // Check if the user preference is already stored in local storage
	  $(document).ready(function () {
		const isDarkTheme = localStorage.getItem("darkTheme") === "true";
	
		// Apply the dark theme if the preference is set to true
		if (isDarkTheme) {
		  $("body").addClass("dark-theme");
		  darktoggle.src = "assets/img/icon/sun-icon.png";
		}
	
		// Attach click event to the specified div
		$(".dark-btn").on("click", toggleDarkTheme);
	  });
	  // dark mood end

	  jQuery(window).on('load', function() {
		// init Masonry
		var $grid = $('.grid,.wbarchivess ul.products').masonry({
			// options
			itemSelector: '.grid-item,#single-products',
		});
		// layout Masonry after each image loads
		$grid.imagesLoaded().progress( function() {
			$grid.masonry('layout');
		});
	});


})(jQuery);
