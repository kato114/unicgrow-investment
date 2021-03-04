(function($) {
	
    "use strict";

    $(window).on("load", function() {
		
		/* ----------------------------------------------------------- */
		/*  BITCOIN PRELOADER
		/* ----------------------------------------------------------- */
		
        if ($("#preloader")[0]) {
            $("#preloader").delay(500).fadeTo(500, 0, function() {
                $(this).remove();
            });
        }
		
    });

    $(document).ready(function() {
		
		/* ----------------------------------------------------------- */
		/*  ADD AND REMOVE OVERFLOW TO DOCUMENT WHEN MENU MOBILE IS OPENED
		/* ----------------------------------------------------------- */
		
		$(".navbar-toggle").click(function(){
			$("html").toggleClass("overflow-hidden");
		});
		
		/* ----------------------------------------------------------- */
		/*  REMOVE # FROM URL
		/* ----------------------------------------------------------- */
		
		$("a[href='#']").on("click", (function(e) {
			e.preventDefault();
		}));
		
		/* ----------------------------------------------------------- */
		/*  FIXED HEADER ON SCROLL
		/* ----------------------------------------------------------- */
		
		var navsite = $("#site-navigation");
		if (navsite.length) {
			var offset = $("#site-navigation").offset().top;
		}
        $(document).scroll(function() {
            var scrollTop = $(document).scrollTop();
            if (scrollTop > offset) {
                $("#site-navigation").addClass("fixed");

            } else {
                $("#site-navigation").removeClass("fixed");
            }
        });
		
		/* ----------------------------------------------------------- */
		/*  ADD HEIGHT TO NAVBAR IN MOBILE DEVICES
		/* ----------------------------------------------------------- */
		
		$(".navbar-collapse").css({ maxHeight: $(window).height() - $(".navbar-header").height() + "px" });
		
		/* ----------------------------------------------------------- */
		/*  BOOTSTRAP CAROUSEL
		/* ----------------------------------------------------------- */
		
		$("#main-slide").carousel({
			pause: true,
			interval: 100000,
		});
		
		/* ----------------------------------------------------------- */
		/*  BACK TO TOP
		/* ----------------------------------------------------------- */
		
        $(window).scroll(function() {
            if ($(this).scrollTop() > 800) {
                $("#back-to-top").addClass("show-back-to-top");
            } else {
                $("#back-to-top").removeClass("show-back-to-top");
            }
        });
        $("#back-to-top").on("click", function() {
            $("html, body").animate({
                scrollTop: 0
            }, 800);
            return false;
        });
		
		/* ----------------------------------------------------------- */
		/*  TESTIMONIAL CAROUSEL
		/* ----------------------------------------------------------- */
		
		$("#carousel-testimonials").carousel({
			wrap:true,
			pause: true,
			interval: 20000
		});
		
		/* ----------------------------------------------------------- */
		/*  REFRESH 503 PAGE
		/* ----------------------------------------------------------- */
		
		$("#refresh").on("click", function() {
			location.reload();
		});
		
		/* ----------------------------------------------------------- */
		/*  TESTIMONIAL CAROUSEL TOUCH OPTIMIZED [ MAIN SLIDER ]
		/* ----------------------------------------------------------- */

        var cr = $("#main-slide");
        cr.on("touchstart", function(event) {
            var xClick = event.originalEvent.touches[0].pageX;
            $(this).one("touchmove", function(event) {
                var xMove = event.originalEvent.touches[0].pageX;
                if (Math.floor(xClick - xMove) > 5) {
                    cr.carousel("next");
                } else if (Math.floor(xClick - xMove) < -5) {
                    cr.carousel("prev");
                }
            });
            cr.on("touchend", function() {
                $(this).off("touchmove");
            });
        });
		
		/* ----------------------------------------------------------- */
		/*  TESTIMONIAL CAROUSEL TOUCH OPTIMIZED [ CAROUSEL TESTIMONIALS ]
		/* ----------------------------------------------------------- */
		
		var ct = $("#carousel-testimonials");
        ct.on("touchstart", function(event) {
            var xClick = event.originalEvent.touches[0].pageX;
            $(this).one("touchmove", function(event) {
                var xMove = event.originalEvent.touches[0].pageX;
                if (Math.floor(xClick - xMove) > 5) {
                    ct.carousel("next");
                } else if (Math.floor(xClick - xMove) < -5) {
                    ct.carousel("prev");
                }
            });
            ct.on("touchend", function() {
                $(this).off("touchmove");
            });
        });
		
		/* ----------------------------------------------------------- */
		/*  WIDGET DATA FROM BITCOIN.COM
		/* ----------------------------------------------------------- */
		
		(function(b, i, t, C, O, I, N) {
			window.addEventListener("load", function() {
				if (b.getElementById(C)) return;
				I = b.createElement(i), N = b.getElementsByTagName(i)[0];
				I.src = t;
				I.id = C;
				N.parentNode.insertBefore(I, N);
			}, false)
		})(document, "script", "https://widgets.bitcoin.com/widget.js", "btcwdgt");
		
		/* ----------------------------------------------------------- */
		/*  VARIABLES FOR SELECT INPUT AND BITCOIN CALCULATOR FORM
		/* ----------------------------------------------------------- */
		
		var userAgent = navigator.userAgent.toLowerCase(),
        plugins = {
            selectFilter: $("#currency-select"),
            btcCalculator: $("#bitcoin-calculator"),
        };
		
		
		/* ----------------------------------------------------------- */
		/*  REPLACE OLD SELECT IN BITCOIN CALCULATOR FORM
		/* ----------------------------------------------------------- */

        if (plugins.selectFilter.length) {
            for (var i = 0; i < plugins.selectFilter.length; i++) {
                var select = $(plugins.selectFilter[i]);
                select.select2({
                    placeholder: select.attr("data-placeholder") ? select.attr("data-placeholder") : false,
                    minimumResultsForSearch: select.attr("data-minimum-results-search") ? select.attr("data-minimum-results-search") : 10,
                    maximumSelectionSize: 3,
                    dropdownCssClass: select.attr("data-dropdown-class") ? select.attr("data-dropdown-class") : ""
                });
            }
        }
		
		/* ----------------------------------------------------------- */
		/*  BITCOIN CALCULATOR [ WWW.BLOCKCHAIN.INFO API ]
		/* ----------------------------------------------------------- */
		
        if (plugins.btcCalculator.length) {

            $.getJSON("https://blockchain.info/ticker", function(btcJsonData) {
				var currencyList = [];
				var index = 0;

				for (var currency in btcJsonData) {
					currencyList.push({
						"id": index,
						"text": currency
					});
					index++;
				}

				for (var i = 0; i < plugins.btcCalculator.length; i++) {
					var btcForm = $(plugins.btcCalculator[i]),
						btcFormInput = $(btcForm.find('[name="btc-calculator-value"]')),
						btcFormOutput = $(btcForm.find('[name="btc-calculator-result"]')),
						btcFormCurrencySelect = $(btcForm.find('[name="btc-calculator-currency"]'));

					btcFormCurrencySelect.select2({
						placeholder: btcFormCurrencySelect.attr("data-placeholder") ? btcFormCurrencySelect.attr("data-placeholder") : false,
						minimumResultsForSearch: btcFormCurrencySelect.attr("data-minimum-results-search") ? btcFormCurrencySelect.attr("data-minimum-results-search") : 50,
						maximumSelectionSize: 3,
						dropdownCssClass: btcFormCurrencySelect.attr("data-dropdown-class") ? btcFormCurrencySelect.attr("data-dropdown-class") : '',
						data: currencyList
					});

					if (btcFormInput.length && btcFormOutput.length) {
						// BTC => Currency
						(function(btcFormInput, btcFormOutput, btcFormCurrencySelect) {
							var lastChanged = 'btc';

							btcFormInput.on('input', function() {
								// store current positions in variables
								var selectionStart = this.selectionStart,
									selectionEnd = this.selectionEnd;

								this.value = toCryptoCurrencyFormat(this.value);

								// restore cursor position
								this.setSelectionRange(selectionStart, selectionEnd);

								btcFormOutput.val(toCurrencyFormat('' + btcJsonData[btcFormCurrencySelect.select2('data')[0].text]["buy"] * this.value));
								lastChanged = 'btc';
							});

							// Currency => BTC
							btcFormOutput.on('input', function() {
								// store current positions in variables
								var selectionStart = this.selectionStart,
									selectionEnd = this.selectionEnd;

								this.value = toCurrencyFormat(this.value);

								// restore cursor position
								this.setSelectionRange(selectionStart, selectionEnd);

								btcFormInput.val(toCryptoCurrencyFormat('' + this.value / btcJsonData[btcFormCurrencySelect.select2('data')[0].text]["sell"]));
								lastChanged = 'currency';
							});

							btcFormInput.trigger('input');
							btcFormOutput.blur();

							btcFormCurrencySelect.on('change', function() {
								if (lastChanged === 'btc') {
									btcFormOutput.val(toCurrencyFormat('' + btcJsonData[btcFormCurrencySelect.select2('data')[0].text]["buy"] * btcFormInput.val()));
								} else {
									btcFormInput.val(toCryptoCurrencyFormat('' + btcFormOutput.val() / btcJsonData[btcFormCurrencySelect.select2('data')[0].text]["sell"]));
								}
							});
						})(btcFormInput, btcFormOutput, btcFormCurrencySelect);
					}
				}
			})
			.fail(function() {
				console.log('Error while fetching data from https://blockchain.info/ticker');
			});
		}

		function toCurrencyFormat(stringValue) {
			var value = parseFloat(stringValue.replace(/[^\d.]/g, '')).toFixed(2);
			return $.isNumeric(value) ? value : 0;
		}

		function toCryptoCurrencyFormat(stringValue) {
			var value = stringValue.replace(/[^\d.]/g, '');
			return $.isNumeric(value) ? value : 0;
		}

		/* ----------------------------------------------------------- */
		/*  PRICING TABLES SWITCH ANIMATION
		/* ----------------------------------------------------------- */
		
		checkScrolling($(".pricing-body"));
		$(window).on("resize", function() {
			window.requestAnimationFrame(function() {
				checkScrolling($(".pricing-body"))
			});
		});
		$(".pricing-body").on("scroll", function() {
			var selected = $(this);
			window.requestAnimationFrame(function() {
				checkScrolling(selected)
			});
		});

		function checkScrolling(tables) {
			tables.each(function() {
				var table = $(this),
					totalTableWidth = parseInt(table.children(".pricing-features").width(),10 ),
					tableViewport = parseInt(table.width(),10 );
				if (table.scrollLeft() >= totalTableWidth - tableViewport - 1) {
					table.parent("li").addClass("is-ended");
				} else {
					table.parent("li").removeClass("is-ended");
				}
			});
		}

		bouncy_filter($(".pricing-container"));

		function bouncy_filter(container) {
			container.each(function() {
				var pricing_table = $(this);
				var filter_list_container = pricing_table.children(".pricing-switcher"),
					filter_radios = filter_list_container.find("input[type='radio']"),
					pricing_table_wrapper = pricing_table.find(".pricing-wrapper");

				var table_elements = {};
				filter_radios.each(function() {
					var filter_type = $(this).val();
					table_elements[filter_type] = pricing_table_wrapper.find("li[data-type='" + filter_type + "']");
				});

				//detect input change event
				filter_radios.on("change", function(event) {
					event.preventDefault();
					//detect which radio input item was checked
					var selected_filter = $(event.target).val();

					//give higher z-index to the pricing table items selected by the radio input
					show_selected_items(table_elements[selected_filter]);

					//rotate each pricing-wrapper 
					//at the end of the animation hide the not-selected pricing tables and rotate back the .pricing-wrapper

					if (!Modernizr.cssanimations) {
						hide_not_selected_items(table_elements, selected_filter);
						pricing_table_wrapper.removeClass("is-switched");
					} else {
						pricing_table_wrapper.addClass("is-switched").eq(0).one("webkitAnimationEnd oanimationend msAnimationEnd animationend", function() {
							hide_not_selected_items(table_elements, selected_filter);
							pricing_table_wrapper.removeClass("is-switched");
							//change rotation direction if .pricing-list has the .bounce-invert class
							if (pricing_table.find(".pricing-list").hasClass("bounce-invert")) pricing_table_wrapper.toggleClass("reverse-animation");
						});
					}
				});
			});
		}

		function show_selected_items(selected_elements) {
			selected_elements.addClass("is-selected");
		}

		function hide_not_selected_items(table_containers, filter) {
			$.each(table_containers, function(key, value) {
				if (key != filter) {
					$(this).removeClass("is-visible is-selected").addClass("is-hidden");

				} else {
					$(this).addClass("is-visible").removeClass("is-hidden is-selected");
				}
			});
		}
			
		/* ----------------------------------------------------------- */
		/*  VIDEO POP UP
		/* ----------------------------------------------------------- */
		jQuery(".mfp-youtube").magnificPopup({
			type: "iframe",
			mainClass: "mfp-fade",
			removalDelay: 0,
			preloader: false,
			fixedContentPos: false,
			iframe: {
				patterns: {
					youtube: {
						src: "https://youtube.com/embed/%id%?autoplay=1&rel=0"
					},
				}
			}
		});
		
		/* ----------------------------------------------------------- */
		/*  SITE SEARCH
		/* ----------------------------------------------------------- */
		
		$(".navbar-nav .fa-search").on("click", function() {
			//$("html, body").animate({ }, "slow");
			$(".site-search .container").toggleClass("open");
		})

		$(".site-search .close").on("click", function() {
			$(".site-search .container").removeClass("open");;
		})
		
		/* ----------------------------------------------------------- */
		/*  AJAX CONTACT FORM
		/* ----------------------------------------------------------- */
		 
        $(".form-contact").on("submit", function() {
            $(".output_message").text("Loading...");

            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                method: form.attr("method"),
                data: form.serialize(),
                success: function(result) {
                    if (result == "success") {
						$(".form-contact").find(".output_message_holder").addClass("d-block");
						$(".form-contact").find(".output_message").addClass("success");
                        $(".output_message").text("Your message has been sent successfully!");
                    } else {
                        $(".form-contact").find(".output_message_holder").addClass("d-block");
						$(".form-contact").find(".output_message").addClass("error");
                        $(".output_message").text("Error while Sending email! try later");
                    }
                }
            });

            return false;
        });
		
		/* ----------------------------------------------------------- */
		/*  NUMBER SPINNER HORIZONTAL [ QUANTITY IN SHOPPING CART PAGE ]
		/* ----------------------------------------------------------- */
		
		var fieldName;
		// This button will increment the value
		$(".qtyplus").on("click", function(e){
			// Stop acting like a button
			e.preventDefault();
			// Get the field name
			fieldName = $(this).attr("data-field");
			// Get its current value
			var currentVal = parseInt($("input[name="+fieldName+"]").val(),10 );
			// If is not undefined
			if (!isNaN(currentVal)) {
				// Increment
				$("input[name="+fieldName+"]").val(currentVal + 1);
			} else {
				// Otherwise put a 0 there
				$("input[name="+fieldName+"]").val(0);
			}
		});
		// This button will decrement the value till 0
		$(".qtyminus").on("click", function(e) {
			// Stop acting like a button
			e.preventDefault();
			// Get the field name
			fieldName = $(this).attr("data-field");
			// Get its current value
			var currentVal = parseInt($("input[name="+fieldName+"]").val(),10 );
			// If it isn't undefined or its greater than 0
			if (!isNaN(currentVal) && currentVal > 1) {
				// Decrement one
				$("input[name="+fieldName+"]").val(currentVal - 1);
			} else if (currentVal == 0) {
				$("input[name="+fieldName+"]").val(1);
			}
			
			else {
				// Otherwise put a 1 there
				$("input[name="+fieldName+"]").val(1);
			}
		});
		
		/* ----------------------------------------------------------- */
		/*  TOOLTIP
		/* ----------------------------------------------------------- */
		
		$("[data-toggle='tooltip']").tooltip()

	});
		
		/* ----------------------------------------------------------- */

})(jQuery);