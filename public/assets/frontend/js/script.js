/*-----------------------------------------------------------
    * Template Name    : Multispace
    * Author           : KreativDev
    * File Description : This file contains the javaScript functions for the actual template, this
                        is the file you need to edit to change the functionality of the template.
    *------------------------------------------------------------
*/

!(function ($) {
    "use strict";

    /*============================================
        Mobile menu
    ============================================*/
    var mobileMenu = function () {
        // Variables
        var body = $("body"),
            mainNavbar = $(".main-navbar"),
            mobileNavbar = $(".mobile-menu"),
            cloneInto = $(".mobile-menu-wrapper"),
            cloneItem = $(".mobile-item"),
            menuToggler = $(".menu-toggler"),
            offCanvasMenu = $("#offcanvasMenu"),
            backdrop,
            _initializeBackDrop = function () {
                backdrop = document.createElement('div');
                backdrop.className = 'menu-backdrop';
                backdrop.onclick = function hideOffCanvas() {
                    menuToggler.removeClass("active"),
                        body.removeClass("mobile-menu-active"),
                        backdrop.remove();
                };
                document.body.appendChild(backdrop);
            };

        menuToggler.on("click", function () {
            $(this).toggleClass("active");
            body.toggleClass("mobile-menu-active");
            _initializeBackDrop();
            if (!body.hasClass("mobile-menu-active")) {
                $('.menu-backdrop').remove();
            }
        })

        mainNavbar.find(cloneItem).clone(!0).appendTo(cloneInto);

        if (offCanvasMenu) {
            body.find(offCanvasMenu).clone(!0).appendTo(cloneInto);
        }

        mobileNavbar.find("li").each(function (index) {
            var toggleBtn = $(this).children(".toggle")
            toggleBtn.on("click", function (e) {
                $(this)
                    .parent("li")
                    .children("ul")
                    .stop(true, true)
                    .slideToggle(350);
                $(this).parent("li").toggleClass("show");
            })
        })

        // check browser width in real-time
        var checkBreakpoint = function () {
            var winWidth = window.innerWidth;
            if (winWidth <= 1199) {
                mainNavbar.hide();
                mobileNavbar.show()
            } else {
                mainNavbar.show();
                mobileNavbar.hide();
                $('.menu-backdrop').remove();
            }
        }
        checkBreakpoint();

        $(window).on('resize', function () {
            checkBreakpoint();
        });
    }
    mobileMenu();

    var getHeaderHeight = function () {
        var headerNext = $(".header-next");
        var header = headerNext.prev(".header-area");
        var headerHeight = header.height();

        headerNext.css({
            "margin-top": headerHeight
        })
    }
    getHeaderHeight();

    $(window).on('resize', function () {
        getHeaderHeight();
    });

    /*============================================
        Navlink active class
    ============================================*/
    var links = document.querySelectorAll("#mainMenu .nav-link");
    var currentUrl = window.location.href;

    links.forEach(function (link) {
        if (link.href === currentUrl) {
            link.classList.add("active");

            // Add active to parent .toggle too
            let parentToggle = link.closest("ul.menu-dropdown")?.previousElementSibling;
            if (parentToggle?.classList.contains("toggle")) {
                parentToggle.classList.add("active");
            }
        }
    });


    /*============================================
        Sticky header
    ============================================*/
    $(window).on("scroll", function () {
        var header = $(".header-area");
        // If window scroll down .is-sticky class will added to header
        if ($(window).scrollTop() >= 100) {
            header.addClass("is-sticky");
        } else {
            header.removeClass("is-sticky");
        }
    });


    /*============================================
        Password icon toggle
    ============================================*/
    $(".show-password-field").on("click", function () {
        var showIcon = $(this).children(".show-icon");
        var passwordField = $(this).prev("input");
        showIcon.toggleClass("show");
        if (passwordField.attr("type") == "password") {
            passwordField.attr("type", "text")
        } else {
            passwordField.attr("type", "password");
        }
    })


    /*============================================
        Quantity button
    ============================================*/
    $(document).on('click', '.quantity-down', function () {
        var numcourse = Number($(this).next().val());
        if (numcourse > 0) $(this).next().val(numcourse - 1);
    });
    $(document).on('click', '.quantity-up', function () {
        var numcourse = Number($(this).prev().val());
        $(this).prev().val(numcourse + 1);
    })


    /*============================================
        Image to background image
    ============================================*/
    var bgImage = $(".bg-img")
    bgImage.each(function () {
        var el = $(this),
            src = el.attr("data-bg-img");

        el.css({
            "background-image": "url(" + src + ")",
            "background-repeat": "no-repeat"
        });
    });


    /*============================================
        Toggle List
    ============================================*/

    $("[data-toggle-list]").each(function (i) {

        var list = $(this).children();
        var listShow = $(this).data("toggle-show");
        var listShowBtn = $(this).next("[data-toggle-btn]");

        // Check if there are more items than the show limit
        if (list.length > listShow) {
            listShowBtn.show(); // Show the button
            list.slice(listShow).hide();

            listShowBtn.on("click", function () {
                var showMoreText = $(this).attr('data-show-more');
                var showLessText = $(this).attr('data-show-less');
                list.slice(listShow).slideToggle(300, function () {
                    if (list.slice(listShow).is(':visible')) {
                        $(this).text(showLessText);
                    } else {
                        $(this).text(showMoreText);
                    }
                }.bind(this));
            });
        } else {
            listShowBtn.hide();
        }
    });


    /*============================================
        Sidebar toggle
    ============================================*/
    $(".list-dropdown .category-toggle").on("click", function (t) {
        var i = $(this).closest("li"),
            o = i.find("ul").eq(0);

        if (i.hasClass("open")) {
            o.slideUp(300, function () {
                i.removeClass("open")
            })
        } else {
            o.slideDown(300, function () {
                i.addClass("open")
            })
        }
        t.stopPropagation(), t.preventDefault()
    })


    // Widget Categories
    $(document).ready(function () {
        function widgetcategories() {
            if ($(window).width() <= 1199.99) {
                $(".widget-subcategories").hide();
                $(".category-search").off("click").on("click", function (e) {
                    e.preventDefault();
                    const $categoryCollapse = $(this).siblings(".widget-subcategories");
                    if ($categoryCollapse.length > 0) {
                        $(".widget-subcategories").not($categoryCollapse).slideUp();
                        $categoryCollapse.stop(true, true).slideToggle();
                    }
                });
            } else {
                $(".widget-subcategories").show();
                $(".category-search").off("click");
            }
        }

        widgetcategories();
        $(window).resize(function () {
            widgetcategories();
        });
    });

    /*============================================
        Tabs mouse hover animation
    ============================================*/
    $("[data-hover='fancyHover']").mouseHover();

    /*============================================
        review color set
    ============================================*/
    $(document).on('click', '.review-value', function () {
        $('.review-value i').removeClass('review-color');
        $(this).find('i').addClass('review-color');
        let reviewValue = $(this).find('i').first().data('ratingval');
        $('#reviewValue').val(reviewValue);
    });

    /*============================================
        Sliders
    ============================================*/
    // Testimonial Slider
    var testimonialSlider1 = new Swiper("#testimonial-slider-1", {
        speed: 800,
        spaceBetween: 25,
        loop: true,
        slidesPerView: 1,

        // Pagination
        pagination: {
            el: '#testimonial-slider-1-pagination',
            clickable: true
        },

        on: {
            init: function () {
                var pagination = $('#testimonial-slider-1-pagination'),
                    paginationLength = $('#testimonial-slider-1-pagination span'),
                    currentSlide = 1,
                    totalSlide = paginationLength.length.toString().padStart(2, '0');
                pagination.append(`
                        <div class="fraction">
                            <span class='min'></span>
                            <span class='max'></span>
                        </div>
                    `)

                pagination.find(".min").text('0' + currentSlide)
                pagination.find(".max").text(totalSlide)
            },
        }
    });
    var testimonialSlider2 = new Swiper("#testimonial-slider-2", {
        speed: 1200,
        spaceBetween: 30,
        slidesPerView: 3,
        autoplay: {
            delay: 3000,
        },

        // Pagination bullets
        pagination: {
            el: "#testimonial-slider-2-pagination",
            clickable: true,
        },

        breakpoints: {
            320: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3
            }
        },

        on: {
            init: function () {
                var pagination = $('#testimonial-slider-2-pagination'),
                    paginationLength = $('#testimonial-slider-2-pagination span'),
                    currentSlide = 1,
                    totalSlide = paginationLength.length.toString().padStart(2, '0');
                pagination.append(`
                        <div class="fraction">
                            <span class='min'></span>
                            <span>/</span>
                            <span class='max'></span>
                        </div>
                    `)

                pagination.find(".min").text('0' + currentSlide)
                pagination.find(".max").text(totalSlide)
            },
        }
    });
    var testimonialSlider3 = new Swiper("#testimonial-slider-3", {
        speed: 1000,
        spaceBetween: 30,
        slidesPerView: 1,
        loop: true,
        autoplay: {
            delay: 3000,
        },
        effect: "creative",
        creativeEffect: {
            prev: {
                shadow: true,
                translate: [0, -400, 0],
            },
            next: {
                translate: [0, "100%", 0],
            },
        },

        // Pagination bullets
        pagination: {
            el: "#testimonial-slider-3-pagination",
            clickable: true,
        },

        on: {
            init: function () {
                var pagination = $('#testimonial-slider-3-pagination'),
                    paginationLength = $('#testimonial-slider-3-pagination span'),
                    currentSlide = 1,
                    totalSlide = paginationLength.length.toString().padStart(2, '0');
                pagination.append(`
                        <div class="fraction">
                            <span class='min'></span>
                            <span>/</span>
                            <span class='max'></span>
                        </div>
                    `)

                pagination.find(".min").text('0' + currentSlide)
                pagination.find(".max").text(totalSlide)
            },
        }
    });

    // Product Slider
    $(".product-slider").each(function () {
        var id = $(this).attr("id");
        var sliderId = "#" + id;

        var swiper = new Swiper(sliderId, {
            speed: 800,
            spaceBetween: 25,
            loop: false,
            slidesPerView: 3,

            // Navigation arrows
            navigation: {
                nextEl: sliderId + "-next",
                prevEl: sliderId + "-prev",
            },

            // Pagination
            pagination: {
                el: sliderId + '-pagination',
                clickable: true
            },

            breakpoints: {
                320: {
                    slidesPerView: 1
                },
                992: {
                    slidesPerView: 2
                },
                1200: {
                    slidesPerView: 3
                },
            }
        })
    })

    // City Slider

    document.addEventListener('DOMContentLoaded', function () {
        var sliderEl = document.getElementById('city-slider-1');
        if (!sliderEl) return; 

        // initial markup theke slide count (Swiper init er age)
        var slideCount = sliderEl.querySelectorAll('.swiper-wrapper > .swiper-slide').length;

        // loop only if 3 or more slides 
        var shouldLoop = slideCount >= 3;

        var citySlider1 = new Swiper("#city-slider-1", {
            speed: 800,
            spaceBetween: 25,
            loop: shouldLoop,
            watchOverflow: true, 
            slidesPerView: 3,

            pagination: {
                el: '#city-slider-1-pagination',
                clickable: true
            },

            breakpoints: {
                320: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
            },

            on: {
                init: function () {
                    var pagination = $('#city-slider-1-pagination'),
                        paginationLength = $('#city-slider-1-pagination span'),
                        currentSlide = 1,
                        totalSlide = paginationLength.length.toString().padStart(2, '0');

                    pagination.append(`
                    <div class="fraction">
                        <span class='min'></span>
                        <span class='max'></span>
                    </div>
                `);

                    pagination.find(".min").text('0' + currentSlide);
                    pagination.find(".max").text(totalSlide);
                },
            }
        });
    });


    if ($('.product-slider-style-2-thump').length > 0) {
        var swiperThumb = new Swiper(".product-slider-style-2-thump", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            // Add responsiveness for slidesPerView
            breakpoints: {
                0: { slidesPerView: 2 },
                480: { slidesPerView: 2 },
                576: { slidesPerView: 2 },
                768: { slidesPerView: 4 },
            },

        });
    }

    if ($('.product-slider-style-2').length > 0) {
        var swiper = new Swiper(".product-slider-style-2", {
            speed: 1000,
            slidesPerView: 1,
            navigation: {
                nextEl: ".slider-btn-next",
                prevEl: ".slider-btn-prev",
            },
            thumbs: {
                swiper: swiperThumb,
            },
        });
    }

    if ($('.related-product-slider').length > 0) {
        var swiper = new Swiper(".related-product-slider", {
            slidesPerView: 4,
            spaceBetween: 30,
            loop: false,
            speed: 1000,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".slider-btn-next",
                prevEl: ".slider-btn-prev",
            },
            breakpoints: {
                0: { slidesPerView: 1 },
                480: { slidesPerView: 2 },
                575: { slidesPerView: 2 },
                768: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                },
            },
        });
    }

    



    /*============================================
        Youtube popup
    ============================================*/
    $(".youtube-popup").magnificPopup({
        disableOn: 300,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    })


    /*============================================
        Gallery popup
    ============================================*/
    $(".gallery-popup").each(function () {
        $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            mainClass: 'mfp-fade',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            },
            callbacks: {
                elementParse: function (item) {
                    // the class name
                    if (item.el.hasClass("video-link")) {
                        item.type = 'iframe';
                    } else {
                        item.type = 'image';
                    }
                }
            },
            removalDelay: 500, //delay removal by X to allow out-animation
            closeOnContentClick: true,
            midClick: true
        });
    })


    // Gallery image popup again
    $('.gallery-item').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom',
        gallery: {
            enabled: true
        }
    });


    /*============================================
        Data tables
    ============================================*/
    var dataTable = function () {
        var dTable = $("#myTable");

        if (dTable.length) {
            dTable.DataTable()
        }
    }

    /*============================================
        Image upload
    ============================================*/
    var fileReader = function (input) {
        var regEx = new RegExp(/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i);
        var errorMsg = $("#errorMsg");

        if (input.files && input.files[0] && regEx.test(input.value)) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            errorMsg.html("Please upload a valid file type")
        }
    }
    $("#imageUpload").on("change", function () {
        fileReader(this);
    });
 

    /*============================================
        Read more toggle button
    ============================================*/
    $(".read-more-btn").on("click", function () {
        $(this).prev().toggleClass('show');

        if ($(this).prev().hasClass("show")) {
            $(this).text(readLess);
        } else {
            $(this).text(readMore);
        }
    })

    // click - show // Show/hide read more button based on line count
    $('.click-show').each(function () {
        var content = $(this).find('.show-content p');
        if (content.length === 0) {
            return;
        }
        var lineHeight = parseFloat(content.css('line-height'));
        var height = content[0].scrollHeight;
        var maxLines = 2;

        if (height <= lineHeight * maxLines) {
            $(this).find('.read-more-btn').hide();
        }
    });



    /*============================================
        Go to top
    ============================================*/
    $(window).on("scroll", function () {
        // If window scroll down .active class will added to go-top
        var goTop = $(".go-top");

        if ($(window).scrollTop() >= 200) {
            goTop.addClass("active");
        } else {
            goTop.removeClass("active")
        }
    })
    $(".go-top").on("click", function (e) {
        $("html, body").animate({
            scrollTop: 0,
        }, 0);
    });


    /*============================================
        Lazyload image
    ============================================*/
    var lazyLoad = function () {
        window.lazySizesConfig = window.lazySizesConfig || {};
        window.lazySizesConfig.loadMode = 2;
        lazySizesConfig.preloadAfterLoad = true;
    }


    /*============================================
        Odometer
    ============================================*/
    $(".counter").counterUp({
        delay: 10,
        time: 1000
    });


    /*============================================
        Nice select
    ============================================*/
    $(".niceselect").niceSelect();

    var selectList = $(".nice-select .list")
    $(".nice-select .list").each(function () {
        var list = $(this).children();
        if (list.length > 5) {
            $(this).css({
                "height": "160px",
                "overflow-y": "scroll"
            })
        }
    })


    /*============================================
      Select2
    ============================================*/
    $('.select2').select2();



    /*============================================
        Tooltip
    ============================================*/
    var tooltipTriggerList = [].slice.call($('[data-tooltip="tooltip"]'))

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    /*============================================
        Footer date
    ============================================*/
    var date = new Date().getFullYear();
    $("#footerDate").text(date);


    /*============================================
        Document on ready
    ============================================*/
    $(document).ready(function () {
        lazyLoad(),
            dataTable()
    })

    


    /*============================================
        Date-range Picker
    ============================================*/

    $('body').on('apply.daterangepicker', '.checkInEventDate', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('body').on('cancel.daterangepicker', '.checkInEventDate', function (ev, picker) {
        $(this).val('');
    });
    // this date range picker initialized for space index end

    // This script initializes a date range picker with custom validation to Block selection of holidays and weekends for the space details page start

    let spaceType = (typeof type !== 'undefined') ? type : 1;
    $(function () {
        $('.checkInDate').daterangepicker({
            singleDatePicker: (spaceType == 3) ? false : true,
            drops: "auto",
            autoApply: (spaceType == 3) ? false : true,
            timePicker: false,
            autoUpdateInput: false,
            minDate: new Date(),
            maxDate: moment().add(2, 'years').toDate(),

            // Add custom classes for holidays, weekends, and booked dates
            isCustomDate: function (date) {
                let classes = [];

                let isHoliday = holidayDate.some(holiday => moment(holiday.date).isSame(date, 'day'));
                if (isHoliday) classes.push('holiday-date');

                let isWeekend = weekendDays.some(day => date.format('dddd') === day.name);
                if (isWeekend) classes.push('weekend-day');

                let todaysAndFutureBookings = window.bookingsArray.filter(booking => {
                    let bookingStartDate = moment(booking.bookingStartDate).startOf('day');
                    let bookingEndDate = moment(booking.bookingEndDate).startOf('day');
                    return bookingStartDate.isSameOrAfter(moment().startOf('day')) || bookingEndDate.isSameOrAfter(moment().startOf('day'));
                });

                let isBooked = todaysAndFutureBookings.some(booking => {
                    let bookingStartDate = moment(booking.bookingStartDate);
                    let bookingEndDate = moment(booking.bookingEndDate);
                    return date.isBetween(bookingStartDate, bookingEndDate, null, '[]');
                });

                if (isBooked) classes.push('booked-date');

                return classes.length > 0 ? classes : undefined;
            },

            // Disable dates that are weekends, holidays, booked, or beyond 2 years from now
            isInvalidDate: function (date) {
                let checkDate = moment(date).startOf('day');
                let isWeekend = weekendDays.some(day => date.format('dddd') === day.name);
                let isAfterTwoYears = date.isAfter(moment().add(2, 'years'));
                let isHoliday = holidayDate.some(holiday => moment(holiday.date).isSame(checkDate, 'day'));

                let todaysAndFutureBookings = window.bookingsArray.filter(booking => {
                    let bookingStartDate = moment(booking.bookingStartDate).startOf('day');
                    let bookingEndDate = moment(booking.bookingEndDate).startOf('day');
                    return bookingStartDate.isSameOrAfter(checkDate) || bookingEndDate.isSameOrAfter(checkDate);
                });

                let isBookingDate = todaysAndFutureBookings.some(booking => {
                    let bookingStartDate = moment(booking.bookingStartDate);
                    let bookingEndDate = moment(booking.bookingEndDate);
                    return checkDate.isBetween(bookingStartDate, bookingEndDate, null, '[]');
                });

                return isWeekend || isAfterTwoYears || isBookingDate || isHoliday;
            }

        });

        // Function to add 'weekend-day' class to calendar header cells for weekends
        function addWeekendClassToHeaders() {
            const dayAbbreviations = {
                Sunday: 'Su',
                Monday: 'Mo',
                Tuesday: 'Tu',
                Wednesday: 'We',
                Thursday: 'Th',
                Friday: 'Fr',
                Saturday: 'Sa'
            };

            $('.daterangepicker .calendar-table th').each(function () {
                let headerText = $(this).text().trim();
                let isWeekendHeader = weekendDays.some(day => dayAbbreviations[day.name] === headerText);
                if (isWeekendHeader) {
                    $(this).addClass('weekend-day');
                } else {
                    $(this).removeClass('weekend-day');
                }
            });
        }

        // Attach weekend header class on showing and updating the picker
        $('body').on('show.daterangepicker apply.daterangepicker', '.checkInDate', function () {
            addWeekendClassToHeaders();
        });

        // Use MutationObserver to detect calendar updates for next/prev button clicks
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'childList') {
                    addWeekendClassToHeaders();
                }
            });
        });

        const calendarTable = document.querySelector('.daterangepicker .calendar-table');
        if (calendarTable) {
            observer.observe(calendarTable, {
                childList: true,
                subtree: true
            });
        }

        // Disconnect observer when the date picker is closed
        $('body').on('hide.daterangepicker', '.checkInDate', function () {
            observer.disconnect();
        });

        // On apply event, validate the entire selected range for any weekends or holidays
        $('body').on('apply.daterangepicker', '.checkInDate', function (ev, picker) {
            let start = moment(picker.startDate).startOf('day');
            let end = moment(picker.endDate).startOf('day');
            let invalid = false;

            for (let m = moment(start); m.isSameOrBefore(end); m.add(1, 'days')) {
                let isHoliday = holidayDate.some(holiday => moment(holiday.date).isSame(m, 'day'));
                let isWeekend = weekendDays.some(day => m.format('dddd') === day.name);
                if (isHoliday || isWeekend) {
                    invalid = true;
                    break;
                }
            }

            if (invalid) {
                alert(warningMsgForMultiday);
                $(this).val('');
            } else {
                if (spaceType == 3) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                } else {
                    $(this).val(picker.startDate.format('MM/DD/YYYY'));
                }
            }
        });

        // Clear input on cancel event
        $('body').on('cancel.daterangepicker', '.checkInDate', function () {
            $(this).val('');
        });
    });

    // This script initializes a date range picker with custom validation to Block selection of holidays and weekends for the space details page end

    // Check-out
    $('.checkOutDate').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        autoUpdateInput: false,
    });
    $('.checkOutDate').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });
    $('.checkOutDate').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    // Check-in-out
    $('input[name="checkInOut"]').daterangepicker({
        "timePicker": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "startDate": "01/19/2024",
        "endDate": "01/31/2024"
    })

})(jQuery);


let oTime = (typeof openingTime !== 'undefined') ? openingTime : '';
let cTime = (typeof closingTime !== 'undefined') ? closingTime : '';
let timeFormatDetailPage = (typeof timeFormatSpaceDetails !== 'undefined') ? timeFormatSpaceDetails : '24h';

$(document).ready(function () {

    let timePickerFormat, showMeridian;

    // Determine the format for timepicker
    if (timeFormatDetailPage === '12h') {
        timePickerFormat = 'h:mm p'; // 12h format
        showMeridian = true;
    } else {
        timePickerFormat = 'HH:mm';  // 24h format
        showMeridian = false;
    }

    // Function to compare times in same format
    function parseTime(timeStr) {
        let t = timeStr.match(/(\d+):(\d+) ?([APMapm]*)/);
        if (!t) return null;
        let hours = parseInt(t[1], 10);
        let minutes = parseInt(t[2], 10);
        let meridian = t[3].toUpperCase();
        if (meridian === 'PM' && hours < 12) hours += 12;
        if (meridian === 'AM' && hours === 12) hours = 0;
        return hours * 60 + minutes; // total minutes
    }

    let oMinutes = parseTime(oTime);
    let cMinutes = parseTime(cTime);

    // Handle overnight case: if closing time < opening time
    if (oMinutes !== null && cMinutes !== null && cMinutes < oMinutes) {
        // overnight: only set minTime
        $('.timepicker').timepicker({
            timeFormat: timePickerFormat,
            interval: 30,
            showMeridian: showMeridian,
            minTime: oTime,
            dropdown: true,
            scrollbar: true
        });
    } else {
        // normal case: set minTime and maxTime
        $('.timepicker').timepicker({
            timeFormat: timePickerFormat,
            interval: 30,
            showMeridian: showMeridian,
            minTime: oTime,
            maxTime: cTime,
            dropdown: true,
            scrollbar: true
        });
    }



});




$(window).on("load", function () {
    const delay = 350;

    /*============================================
    Preloader
    ============================================*/
    $("#preLoader").delay(delay).fadeOut('slow');

    /*============================================
        Aos animation
    ============================================*/
    var aosAnimation = function () {
        AOS.init({
            easing: "ease",
            duration: 1500,
            once: true,
            offset: 60,
            disable: 'mobile'
        });
    }
    if ($("#preLoader")) {
        setTimeout(() => {
            aosAnimation()
        }, delay);
    } else {
        aosAnimation();
    }
})


function initMap() {
    var inputs = document.getElementsByClassName('search-address');
    Array.prototype.forEach.call(inputs, function (input) {
        var searchBox = new google.maps.places.SearchBox(input);

        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();
            if (places.length === 0) {
                return;
            }

            var place = places[0];
            if (place.geometry) {

                var latInput = document.getElementById('latitude');
                var lngInput = document.getElementById('longitude');
                if (latInput && lngInput) {
                    latInput.value = place.geometry.location.lat();
                    lngInput.value = place.geometry.location.lng();
                }

            }
        });
    });
}

$(document).ready(function () {
    // On page load, set active-radio class
    $('.custom-radio input[type="radio"]:checked').each(function () {
        $(this).next('.form-radio-label').addClass('active-radio');
    });

    // Common radio change - set active class
    $('.custom-radio input[type="radio"]').on('change', function () {
        $(this).closest('ul').find('.form-radio-label').removeClass('active-radio');
        $(this).next('.form-radio-label').addClass('active-radio');
    });

    // 🛠 Rating change korle Rent reset
    $('.rating-list input[type="radio"]').on('change', function () {
        $('.rent-list input[type="radio"]').prop('checked', false);
        $('.rent-list .form-radio-label').removeClass('active-radio');

        // Only "All" option checked hobe
        $('.rent-list input[type="radio"]').first().prop('checked', true);
        $('.rent-list input[type="radio"]').first().next('.form-radio-label').addClass('active-radio');
    });

    // 🛠 Rent Type change korle Ratings reset korbo na anymore
});


$('.toggle-list input[type="radio"]').on('change', function () {
    let currentUrl = new URL(window.location.href);
    let selectedRating = $('input[name="rating"]:checked').val(); // current rating selection
    let selectedCategory = $(this).val(); // current selected category

    if (selectedRating !== undefined) {
        currentUrl.searchParams.set('rating', selectedRating);
    }
    currentUrl.searchParams.set('category', selectedCategory);

    window.location.href = currentUrl.toString(); // redirect with new URL
});



$(document).ready(function () {
    // On page load, set active-radio class and li active
    $('#shiping-method-list input[type="radio"]:checked').each(function () {
        $(this).next('.form-radio-label').addClass('active-radio');
        $(this).closest('li.shiping-method-row').addClass('active');
    });

    // Radio button change event
    $('#shiping-method-list input[type="radio"]').on('change', function () {
        // Remove active-radio and active class from all
        $('#shiping-method-list .form-radio-label').removeClass('active-radio');
        $('#shiping-method-list .shiping-method-row').removeClass('active');

        // Add active-radio to current label
        $(this).next('.form-radio-label').addClass('active-radio');

        // Add active to current li
        $(this).closest('li.shiping-method-row').addClass('active');
    });
});


function billingNavTab() {
    // By default show vendor, hide customer
    $('#vendor-tab').addClass('active');
    $('#vendor-pane').removeClass('d-none');
    $('#customer-pane').addClass('d-none');

    // Vendor button click
    $('#vendor-tab').on('click', function () {
        $('#vendor-pane').removeClass('d-none');
        $('#customer-pane').addClass('d-none');
    });

    // Customer button click
    $('#customer-tab').on('click', function () {
        $('#vendor-pane').addClass('d-none');
        $('#customer-pane').removeClass('d-none');
    });
}
billingNavTab();





