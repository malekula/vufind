$(document).ready(function() {

    (function setWorkdayEnd() {
        var time = ['мы работаем до 19:00', 'мы работаем до 21:00', 'мы работаем до 21:00', 'мы работаем до 21:00', 'мы работаем до 21:00', 'мы работаем до 21:00', 'мы работаем до 19:00'];
        $('.closing-time').text('Сегодня ' + time[moment().day()]);
    })();

    $("#widthTempOption").html($('#sort_options_1 option:selected').text());
    $("#sort_options_1").width($("#selectTagWidth").width()).css("width", "-=20");

    $('#sort_options_1').change(function(){
        $("#widthTempOption").html($('#sort_options_1 option:selected').text());
        $(this).width($("#selectTagWidth").width()).css("width", "-=20");
    });

    /* CUSTOM POPUP */
    $('body').on('click', '.search_readme a', function(e) {
        e.preventDefault();
        $('.popup-box').hide();
        var text = $('.search_readme a').data('text');
        $(".popup-text").html(text);
        $('#popup-wrapper, #popup-readme').show();
        $('header, nav, main, #footer').css('filter', 'blur(5px)');
    }).on('click', '#popup-wrapper, .popup-close, .mobile-close', function(e) {
        if ($(this).hasClass('popup-close') || $(this).hasClass('mobile-close') || $(e.target).attr('id') == 'popup-wrapper') {
            $('.popup-box').hide();
            $('#popup-wrapper').hide();
            $('header, nav, main, #footer').css('filter', 'blur(0)');
            $('body').css('overflow', 'auto');
        }
    });
    /* */

    if($('body').hasClass('template-dir-eds')) {
        $('.ebsco-annotation').show();
        $('a.global_search').removeClass('becomelink');
        $('a.global_search').text('Более 2 000 280 000 документов');
        $('a.global_search').attr('href', '/');
        $('a.global_search').css('cursor', 'default').css('pointer-events', 'none');
        //$('.search_readme a').attr('href', 'https://connect.ebsco.com/s/article/Русскoязычные-ресурсы?language=en_US');
        $('.search_readme a').data('text', '333');
    } else {
        $('a.global_search').text('Более 1 528 000 книг');
        $('a.global_search').attr('href', '/Search/Results?lookfor=&type=AllFields');
        $('.search_readme a').data('text', '<h2>Как пользоваться Каталогом?</h2><p>Авторизуйтесь, чтобы воспользоваться возможностями электронного Каталога.</p><p>Добавьте книгу в корзину</p>');
    }

    /* Placeholder */

    function changeCatalogueSearchPlaceholder() {
        var placeholders = ['James Joyce', 'Les Misérables', 'The Great Gatsby', 'Götz von Berlichingen', 'Золотой телёнок', 'Il gattopardo', 'Antoine de Saint-Exupéry', 'Françoise Sagan', 'Доктор Живаго'];
        $("#searchForm_lookfor").attr('placeholder', placeholders[Math.floor(Math.random()*placeholders.length)]);
    }

    changeCatalogueSearchPlaceholder();
    /*$("#index_search").keypress(function(e){
        var q=$(this).val()
        if (q.length && e.which==13) {
            $("#index_search_form").submit();
        }
    })*/


    var homePageBreadcrumb = $(".searchHomeContent").parents("#content").parents(".main").siblings(".breadcrumbs").children(".content-wrap");
    $(homePageBreadcrumb).children(".breadcrumb-btn").addClass("disabled");

    $(homePageBreadcrumb).children("ul").children("li").children("a").html("");


    $('body').on('mouseenter', '.js-availability-hover', function() {
        $('.book-availability-note').fadeIn(300);
    }).on('mouseleave', '.js-availability-hover', function() {
        $('.book-availability-note').fadeOut(300);
    });




    function resizeSelectForIphone() {
        if(navigator.userAgent.match(/(iPhone|iPod|iPad)/i)) {
            $('#limit_MethodOfAccess').prepend('<option value="" selected="selected" disabled="disabled">Не выбрано</option>');
            $('#limit_fund').prepend('<option value="" selected="selected" disabled="disabled">Не выбрано</option>');
            $('#limit_Location').prepend('<option value="" selected="selected" disabled="disabled">Не выбрано</option>');
            $('#limit_language').prepend('<option value="" selected="selected" disabled="disabled">Не выбрано</option>');
            $('#limit_format').prepend('<option value="" selected="selected" disabled="disabled">Не выбрано</option>');
            $('#limit_topic_facet').prepend('<option value="" selected="selected" disabled="disabled">Не выбрано</option>');
        }
    }
    resizeSelectForIphone();

    /* Random quote */

    var NumberOfDivsToRandomDisplay = 4,
        CookieName = 'DivRamdomValueCookie';

    function DisplayRandomQuote() {
        var r = Math.ceil(Math.random() * NumberOfDivsToRandomDisplay);
        if(NumberOfDivsToRandomDisplay > 1) {
            var ck = 0;
            var cookiebegin = document.cookie.indexOf(CookieName + "=");
            if(cookiebegin > -1) {
                cookiebegin += 1 + CookieName.length;
                cookieend = document.cookie.indexOf(";",cookiebegin);
                if(cookieend < cookiebegin) { cookieend = document.cookie.length; }
                ck = parseInt(document.cookie.substring(cookiebegin,cookieend));
            }
            while(r == ck) { r = Math.ceil(Math.random() * NumberOfDivsToRandomDisplay); }
            document.cookie = CookieName + "=" + r;
        }
        for( var i=1; i<=NumberOfDivsToRandomDisplay; i++) {
            document.getElementById("random_quote"+i).style.display='none';
        }
        document.getElementById("random_quote"+r).style.display='block';
    }

    DisplayRandomQuote();

    /**/

    $('.vf_search_wrapper img').on('click', function() {
        $(this).toggleClass('active');
    });

    /*$('.vf_search_wrapper img').on('click', function() {
     var rotator = $('.vf_search_wrapper img');
     if (rotator.hasClass('clicked')) {
     $('.vf_search_wrapper img').removeClass('clicked');
     } else {
     $(this).toggleClass('clicked');
     }
     });*/


    /*var CurrentPage = window.location.pathname;
    $('#nav .menu a, #fixed_menu .menu a').each(function(i,e) {
        if (CurrentPage.indexOf($(e).attr('href')) + 1) {
            $(e).parent().addClass('active');
        }
    });*/

    function changeSearchPlaceholder() {
        var placeholders = ['Charlotte Bronte', 'Пётр Великий', 'Kaze no uta o kike', 'Атлант расправил плечи'];
        $("#search").attr('placeholder', placeholders[Math.floor(Math.random()*placeholders.length)]);
    }
    changeSearchPlaceholder();

    $("#search").keypress(function(e){
        var q = $(this).val();
        if (q.length && e.which==13) {
            var wrap = $('#search_wrapper');
            if (wrap.hasClass('catalogue')) {
                var search_url = 'http://catalog.libfl.ru/Search/Results?';
                switch ($(this).attr('data-filters')) {
                    case 'author':
                        search_url += 'sort=relevance&join=AND&lookfor0%5B%5D=' + encodeURI(q) + '&type0%5B%5D=Author&bool0%5B%5D=AND&illustration=-1&daterange%5B%5D=publishDate';
                        break;
                    case 'title':
                        search_url += 'sort=relevance&join=AND&lookfor0%5B%5D=' + encodeURI(q) + '&type0%5B%5D=Title&bool0%5B%5D=AND&illustration=-1&daterange%5B%5D=publishDate';
                        break;
                    case 'subject':
                        search_url += 'sort=relevance&join=AND&lookfor0%5B%5D=' + encodeURI(q) + '&type0%5B%5D=Subject&bool0%5B%5D=AND&illustration=-1&daterange%5B%5D=publishDate';
                        break;
                    case 'isbn':
                        search_url += 'sort=relevance&join=AND&lookfor0%5B%5D=' + encodeURI(q) + '&type0%5B%5D=ISN&bool0%5B%5D=AND&illustration=-1&daterange%5B%5D=publishDate';
                        break;
                    default:
                        search_url += 'lookfor=' + encodeURI(q) + '&type=AllFields';
                        break;
                }
                window.location = search_url;
            } else {
                var pp = window.location.pathname.split("/");
                var lang = pp[0] || pp[1] || "ru";
                var filters = '';
                if ($(this).attr('data-filters')) filters = '&f=' + $(this).attr('data-filters');
                window.location = "http://libfl.ru/ru/search?q=" + encodeURI(q) + filters;
            }
        }
    });

    changeSearchPlaceholder();
    $("#index_search").keypress(function(e){
        var q=$(this).val()
        if (q.length && e.which==13) {
            $("#index_search_form").submit();
        }
    })

    if ($('.index_centers_wrapper').length || $('.index_columns').length) {
        $('.content').addClass('index_content');
    }

    window.dateChanged = false;
    $('body').on('change', '.end_date, .start_date', function() {
        window.dateChanged = true;
    });

    /*if ($('input[type="radio"], input[type="checkbox"]').length)
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal'
        });*/

    /*var D = new Date();
     var Day = D.getDate().toString().length == 1 ? '0' + D.getDate() : D.getDate();
     var Month = (D.getMonth() + 1).toString().length == 1 ? '0' + (D.getMonth() + 1) : (D.getMonth() + 1);
     var hash = window.location.hash;
     if (typeof hash != 'undefined' && hash.length > 1) {
     var type = JSON.parse(hash.substr(1)).type;
     if (type == 'report' || type == 'news') {
     $('input.start_date').val(Day + '.' + Month + '.' + (D.getFullYear() - 1));
     $('input.end_date').val(Day + '.' + Month + '.' + D.getFullYear());
     } else {
     $('input.start_date').val(Day + '.' + Month + '.' + D.getFullYear());
     $('input.end_date').val(Day + '.' + Month + '.' + (D.getFullYear() + 1));
     }
     } else {
     $('input.start_date').val(Day + '.' + Month + '.' + D.getFullYear());
     $('input.end_date').val(Day + '.' + Month + '.' + (D.getFullYear() + 1));
     }*/

    $('.input-daterange').addClass('selected');

    /*function resizeRecalc() {
        if (window.innerWidth < 700) {
            $('.event_image_wrapper, .inner_page_teaser_image').css('opacity', 0);
            setTimeout(function() {
                $('.event_image_wrapper, .inner_page_teaser_image').css('width', $('body').width()).css('opacity', 1);
            }, 600);
        } else {
            $('.event_image_wrapper, .inner_page_teaser_image').css('width', 'auto');
        }
        if (window.innerWidth < 760) {
            $('.listing_wrapper, .filters_wrapper, .switcher_wrapper').removeClass('list_type');
            if ($('.about_block').length) {
                $('.about_block span').each(function(i,e) {
                    $(e).html($(e).html().replace(/-<br>/g, '-').replace(/<br>/g, ' '));
                });
            }
        }
    }*/

    /*resizeRecalc();
    $(window).on('resize', function() {
        resizeRecalc();
    });
    if (window.location.hash) {
        try {
            var hash = JSON.parse(window.location.hash.substr(1));
            if (hash.category) {
                $('#filter_category').val(hash.category).trigger('change').selectmenu('refresh');
                $('#filter_category-button').addClass('ui-selected');
            } else if (hash.news) {
                $('#filter_department').val(hash.news).trigger('change').selectmenu('refresh');
                $('#filter_department-button').addClass('ui-selected');
            } else if (hash.collection) {
                $('#collection_filter').val(hash.collection).trigger('change').selectmenu('refresh');
                $('#collection_filter-button').addClass('ui-selected');
            }
        } catch (err) {
            console.log(err);
        }
    }*/

    var is_inner = false;
    if ($('.inner_menu').length) {
        is_inner = true;
        //$('#fixed_menu').addClass('with_inner');
    }

    $(window).on('scroll', function(e) {
        if (window.pageYOffset > ($('#header').height() + $('#header').offset()['top']) && !$('#fixed_menu').hasClass('active')) {
            $('#fixed_menu').addClass('active');
        } else if (window.pageYOffset < $('#nav').height()) {
            $('#fixed_menu').removeClass('active');
        }
        if (window.pageYOffset > 30 && !$('#mobile_menu_wrapper').hasClass('scrolled')) {
            $('#mobile_menu_wrapper, #header, #nav').addClass('scrolled');
        } else if (window.pageYOffset < 30) {
            $('#mobile_menu_wrapper, #header, #nav').removeClass('scrolled');
        }
        if (is_inner) {
            if (window.pageYOffset > ($('.event_title').offset()['top'] + $('.event_title').height())) {
                $('#fixed_menu').addClass('with_inner');
                $('.inner_menu').addClass('active');
            } else {
                $('#fixed_menu').removeClass('with_inner');
                $('.inner_menu').removeClass('active');
            }
        }
    });

    /*$('#datetimepicker').datepicker({
        format: "dd.mm.yy",
        weekStart: 1,
        language: "ru",
        focusOnShow: false,
        keepOpen: false
    }).on('change', function() {
        if ($(this).val()) {
            $('#show_more_btn').data('s', 0);
            $('#picker-container').hide();
            $('.events_cal_btn').addClass('active');
            $('.clear_events_date').show();
            $('.datepicker.dropdown-menu').hide();
        }
    });*/

    /*$('.events_cal_btn').on('click', function(e) {
        if ($(e.target).hasClass('clear_events_date')) return;
        $('#picker-container').show();
        $('#events_date_start').data("datepicker").show()
    });
    $('.events_today_btn, .clear_events_date').on('click', function() {
        $('#show_more_btn').data('s', 0);
        $('.events_cal_btn').removeClass('active');
        $('.events_today_btn').addClass('active');
        $('#events_date_start').val('').trigger('change');
        $('.clear_events_date').hide();
    });*/

    /*$('#date_input').datepicker({
        format: "dd.mm.yyyy",
        weekStart: 1,
        language: "ru",
        focusOnShow: false,
        keepOpen: false
    }).on('change', function() {
        if ($(this).val()) {
            $('#show_more_btn').data('s', 0);
            $('#picker-container').hide();
            $('.datepicker.dropdown-menu').hide();
        }
        if ($(this).siblings('.remove_date').length) {
            if ($(this).val()) {
                $(this).siblings('.remove_date').show();
                $(this).parent().addClass('active');
            } else {
                $(this).siblings('.remove_date').hide();
                $(this).parent().removeClass('active');
            }
        }
    });*/

    /*$('#date_btn').on('click', function() {
        $('#picker-container').show();
        $('#date_input').data("datepicker").show()
    });
    $('#picker-container').on('click', function(e) {
        if ($(e.target).attr('id') == 'picker-container') $('#picker-container').hide();
    });*/

    /*if ($('.fotorama').length) {
        var f_width = '100%';
        var a_width  = 81;
        if ($('.inner_navigation_text').length) {
            if (window.innerWidth < 760) a_width = 30;
            f_width = $('.inner_navigation_text').width() - a_width*2;
        }
        $('.fotorama').fotorama({
            width: f_width,
            maxWidth: '777px',
            nav: false,
            transition: 'crossfade',
            arrows: 'always',
            allowfullscreen: 'true',
            loop: true
        }).on('fotorama:showend fotorama:ready', function(e, fotorama, extra) {
            var from = fotorama.size;
            var slide = fotorama.activeFrame.i;
            if (from > 1)
                $('.fotorama_counter').text(slide + ' из ' + from);
        });
    }*/

    /*if ($('.linked_doc').length) {
        $('.linked_doc_title span').each(function(i,e) {
            if ($(e).height() < 90) {
                $(e).parents('.linked_doc').addClass('small_doc');
            }
        });
    }*/

    /*var Host = new RegExp(window.location.hostname, 'g');
    $('a').each(function(i,e) {
        var href = $(e).attr('href');
        var is_url = /http/.test(href);
        var is_inner = Host.test(href);
        if (is_url && !is_inner) {
            $(e).attr('target', '_blank');
        }
    });*/

    /*var pins_html = '';
    var bannerShow;
    var showAnimTime = 400;
    $('.learning_show_slides a').each(function(i,e) {
        if (i == 0) {
            $(e).css({
                'opacity': 1,
                'z-index': 10
            });
            pins_html += '<div class="learning_show_pin active"></div>';
        } else {
            $(e).css('opacity', 0);
            pins_html += '<div class="learning_show_pin"></div>';
        }
    });*/

    /*if ($('.learning_show_slides a').length > 1) {
        $('.learning_show_pins').html(pins_html);
        bannerShow = setInterval(function () {
            var ind = $('.learning_show_pin.active').index();
            $('.learning_show_slides a').eq(ind).animate({'opacity': 0}, showAnimTime).css('z-index', 1);
            if ($('.learning_show_pin.active').next().length) {
                $('.learning_show_pin.active').removeClass('active').next().addClass('active');
                $('.learning_show_slides a').eq(ind + 1).animate({'opacity': 1}, showAnimTime).css('z-index', 10);
            } else {
                $('.learning_show_pin.active').removeClass('active');
                $('.learning_show_pin').first().addClass('active');
                $('.learning_show_slides a').eq(0).animate({'opacity': 1}, showAnimTime).css('z-index', 10);
            }

        }, 4000);
    }*/

    /* logo randomizer */

    function logoRandomizer() {
        var logoVariations = ['/themes/bootstrap3/images/svg/accent-logo.svg', '/themes/bootstrap3/images/svg/acute-logo.svg', '/themes/bootstrap3/images/svg/breve-logo.svg', '/themes/bootstrap3/images/svg/circumflex-logo.svg', '/themes/bootstrap3/images/svg/tilde-logo.svg', '/themes/bootstrap3/images/svg/umlaut-logo.svg'];
        var randomNum = Math.floor(Math.random() * logoVariations.length);
        document.getElementById("random").src = logoVariations[randomNum];
    }
    logoRandomizer();

    /**/

    /*function backShowBlock(elem, time) {
        setTimeout(function() {
            elem.removeClass('moved moved_back');
        }, time);

    }
    function recalcShowArrows() {
        var visible = Math.round($('.blocks_show_wrapper').width()/($('.float_small_block').width() + parseInt($('.float_small_block').css('margin-right'))));
        var active = $('.inner_wrapper.active');
        var len = Math.floor((active.find('.float_small_block').length - 1)/visible);
        var offset = parseInt(active.attr('data-offset'));
        if (offset > 0) $('.blocks_show_prev').removeClass('disabled');
        if (len != 0) $('.blocks_show_next').removeClass('disabled');
        if (offset == len) $('.blocks_show_next').addClass('disabled');
        if (offset == 0) $('.blocks_show_prev').addClass('disabled');
    }
    function recalcRecommendArrows() {
        var visible = Math.round($('.recommend_content').width()/($('.recommend_block').width()));
        var w = $('.recommend_block').width() * visible;
        var active = $('.recommend_inner');
        var len = Math.floor((active.find('.recommend_block').length - 1)/visible);
        var offset = parseInt(active.attr('data-offset'));
        if (offset > 0) $('.recommend_arrow_left').removeClass('disabled');
        if (len != 0) $('.recommend_arrow_right').removeClass('disabled');
        if (offset == len) $('.recommend_arrow_right').addClass('disabled');
        if (offset == 0) $('.recommend_arrow_left').addClass('disabled');
    }
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }*/

    /*if ($('.index_float_blocks .float_block').length < 5) {
        $('.index_float_blocks').removeClass('wide_content').addClass('content');
    }
    $('.index_float_blocks').show();*/

    /*if ($('.etage_wrapper').length) {
        var sel, img;
        $('.etage_wrapper').each(function(i,e) {
            $(e).find('.section_switch_block').first().addClass('active');
            sel = $(e).find('.section_switch_block').first().attr('data-selector');
            img = $(sel).find('.section_image_path').html();
            $(e).find('.etage_section').attr('src', img);
            if ($(sel).find('.section_info_wrapper').length) {
                var info = $(sel).find('.section_info_wrapper').html();
                $(e).find('.section_info_content').html(info);
                $(e).find('.etage_section_info_btn').addClass('active');
            } else {
                $(e).find('.etage_section_info_btn').removeClass('active');
            }
        });
        sel = $('.etage_wrapper.active .section_switch_block.active').attr('data-selector');
        if ($(sel).find('.section_info_wrapper').length) {
            var info = $(sel).find('.section_info_wrapper').html();
            $('.etage_wrapper.active').find('.section_info_content').html(info);
            $('.etage_wrapper.active').find('.etage_section_info_btn').addClass('active');
        }
    }*/

    if (window.innerWidth > 1100) {
        $('body').on('mouseenter', '.js-working-hours-hover, .working-hours', function() {
            $('.working-hours').show();
        }).on('mouseleave', '.js-working-hours-hover, .working-hours', function() {
            $('.working-hours').hide();
        });
    }


    /*if (window.innerWidth < 760) {
        $('.listing_wrapper, .filters_wrapper, .switcher_wrapper').removeClass('list_type');
    }

    if ($('#accordion').length) {
        $( "#accordion" ).accordion({
            heightStyle: "content",
            collapsible: true,
            active: false
        });
    }*/

    /*if (typeof Centers_Banners != 'undefined') {
        var Banner_rnd = Math.trunc(Math.random()*Centers_Banners.length);
        $("img.cc_banner_image").attr("src", Centers_Banners[Banner_rnd]);
        setInterval(function() {
            var i = Math.trunc(Math.random()*Centers_Banners.length);
            if (i == Banner_rnd && i == Centers_Banners.length) {
                i--;
            } else if (i == Banner_rnd) {
                i++;
            }
            Banner_rnd = i;
            $("img.cc_banner_image").fadeOut(400, function() {
                $(this).attr("src", Centers_Banners[i]).fadeIn(400);
            });
        }, 5000);
    }*/

    /*var Index_Btn_Href = $('a.index_other_events').attr('href');

    $('.event_content .btn_social, .collection_page_socials .btn_social, .inner_menu_share .inner_share_btn').each(function(i,e) {
        //$(e).attr('href', $(e).attr('href') + window.location.href)
        $(e).attr('href', $(e).attr('href').replace('%url%',  window.location.href))
    });
    if ($('meta[property="og:image"]').length) {
        //$('meta[property="og:image"]').attr('content', 'http://libfl.ru' + $('meta[property="og:image"]').attr('content'));
    }*/

    $('body').on('click', '*', function(e) {
        var t = $(e.target);
        if (!t.parents('.vf_search_form').length || t.attr('id') == 'search') {
            $('.vf_search_select').hide();
        }
        if (!t.hasClass('breadcrumb_current')) $('.breadcrumb_links').hide();
    }).on('touchend', '.hamburger', function() {
        $('#nav').toggleClass('active');
        $(this).toggleClass('is-active');
    }).on('click', '.switch_type', function() {
        $('.listing_wrapper, .filters_wrapper, .switcher_wrapper').toggleClass('list_type');
    }).on('change', '.end_date', function() {
        if ($(this).val() != '') {
            $(this).parent().addClass('selected');
        } else {
            $(this).parent().removeClass('selected');
        }
    }).on('click', '.switch_block', function() {
        var wrapper = $(this).parent().attr('data-selector');
        var selector = $(this).attr('data-active');
        $(this).parents('.switcher_wrapper').find('.switch_block').removeClass('active');
        $(this).addClass('active');
        $(wrapper).removeClass('active');
        $(selector).addClass('active');
    }).on('click', '.learning_show_pin', function() {
        clearInterval(bannerShow);
        var ind = $('.learning_show_pin.active').index();
        var nind = $(this).index();
        $('.learning_show_pin').removeClass('active');
        $(this).addClass('active');
        $('.learning_show_slides a').eq(ind).animate({'opacity' : 0}, showAnimTime).css('z-index', 1);
        $('.learning_show_slides a').eq(nind).animate({'opacity' : 1}, showAnimTime).css('z-index', 10);
    }).on('click', '.blocks_show_switcher', function() {
        var wrapper = $(this).parent().attr('data-selector');
        var selector = $(this).attr('data-active');
        var text = $(this).attr('data-text');
        var hash = $(this).attr('data-hash');
        $(this).parents('.blocks_show_switchers').find('.blocks_show_switcher').removeClass('active');
        $(this).addClass('active');
        $(wrapper).removeClass('active');
        $(selector).addClass('active');
        $('a.index_other_events').attr('href', Index_Btn_Href + '#' + hash).text(text);
        recalcShowArrows();
    }).on('click', '.blocks_show_next', function() {
        var visible = Math.round($('.blocks_show_wrapper').width()/($('.float_small_block').width() + parseInt($('.float_small_block').css('margin-right'))));
        var w = ($('.float_small_block').width() + parseInt($('.float_small_block').css('margin-right'))) * visible;
        var active = $('.inner_wrapper.active');
        var len = Math.floor((active.find('.float_small_block').length - 1)/visible);
        var offset = parseInt(active.attr('data-offset'));
        if (offset < len) {
            offset++;
            var a = 0;
            for (var i = visible * offset; i < visible * offset + visible; i++) {
                active.find('.float_small_block').eq(i).addClass('moved');
                backShowBlock(active.find('.float_small_block').eq(i), a * 200);
                a++;
            }
            active.attr('data-offset', offset);
        }
        recalcShowArrows();
        active.css({
            '-webkit-transform' : 'translateX(-'  + offset * w + 'px)',
            '-moz-transform' : 'translateX(-'  + offset * w + 'px)',
            '-ms-transform' : 'translateX(-'  + offset * w + 'px)',
            'transform' : 'translateX(-'  + offset * w + 'px)'
        });

    }).on('click', '.blocks_show_prev', function() {
        var visible = Math.round($('.blocks_show_wrapper').width()/($('.float_small_block').width() + parseInt($('.float_small_block').css('margin-right'))));
        var w = ($('.float_small_block').width() + parseInt($('.float_small_block').css('margin-right'))) * visible;
        var active = $('.inner_wrapper.active');
        var len = Math.floor((active.find('.float_small_block').length - 1)/visible);
        var offset = parseInt(active.attr('data-offset'));
        if (offset > 0) {
            offset--;
            var a = 0;
            for (var i = visible * offset + visible - 1; i > visible * offset - 1; i--) {
                console.log(i);
                active.find('.float_small_block').eq(i).addClass('moved_back');
                backShowBlock(active.find('.float_small_block').eq(i), a * 200);
                a++;
            }
            active.attr('data-offset', offset);
        }
        recalcShowArrows();
        active.css({
            '-webkit-transform' : 'translateX(-'  + offset * w + 'px)',
            '-moz-transform' : 'translateX(-'  + offset * w + 'px)',
            '-ms-transform' : 'translateX(-'  + offset * w + 'px)',
            'transform' : 'translateX(-'  + offset * w + 'px)'
        });

    }).on('click', '.recommend_arrow_right', function() {
        var visible = Math.round($('.recommend_content').width()/($('.recommend_block').width() + 1));
        var w = ($('.recommend_block').width() + 1) * visible;
        var active = $('.recommend_inner');
        var len = Math.floor((active.find('.recommend_block').length - 1)/visible);
        var offset = parseInt(active.attr('data-offset'));
        if (offset < len) {
            offset++;
            var a = 0;
            for (var i = visible * offset; i < visible * offset + visible; i++) {
                active.find('.recommend_block').eq(i).addClass('moved');
                backShowBlock(active.find('.recommend_block').eq(i), a * 200);
                a++;
            }
            active.attr('data-offset', offset);
        }
        recalcRecommendArrows();
        active.css({
            '-webkit-transform' : 'translateX(-'  + offset * w + 'px)',
            '-moz-transform' : 'translateX(-'  + offset * w + 'px)',
            '-ms-transform' : 'translateX(-'  + offset * w + 'px)',
            'transform' : 'translateX(-'  + offset * w + 'px)'
        });

    }).on('click', '.recommend_arrow_left', function() {
        var visible = Math.round($('.recommend_content').width()/($('.recommend_block').width() + 1));
        var w = ($('.recommend_block').width() + 1) * visible;
        var active = $('.recommend_inner');
        var len = Math.floor((active.find('.recommend_block').length - 1)/visible);
        var offset = parseInt(active.attr('data-offset'));
        if (offset > 0) {
            offset--;
            var a = 0;
            for (var i = visible * offset + visible - 1; i > visible * offset - 1; i--) {
                console.log(i);
                active.find('.recommend_block').eq(i).addClass('moved_back');
                backShowBlock(active.find('.recommend_block').eq(i), a * 200);
                a++;
            }
            active.attr('data-offset', offset);
        }
        recalcRecommendArrows();
        active.css({
            '-webkit-transform' : 'translateX(-'  + offset * w + 'px)',
            '-moz-transform' : 'translateX(-'  + offset * w + 'px)',
            '-ms-transform' : 'translateX(-'  + offset * w + 'px)',
            'transform' : 'translateX(-'  + offset * w + 'px)'
        });

    }).on('click', '.section_switch_block', function() {
        $(this).addClass('active').siblings('.section_switch_block').removeClass('active');
        var sel = $(this).attr('data-selector');
        var img = $(sel).find('.section_image_path').html();
        $(this).parents('.etage_wrapper').find('.etage_section').attr('src', img);
        if ($(sel).find('.section_info_wrapper').length) {
            var info = $(sel).find('.section_info_wrapper').html();
            $(this).siblings('.section_info').find('.section_info_content').html(info);
            $(this).parents('.etage_wrapper').find('.etage_section_info_btn').addClass('active');
        } else {
            $(this).parents('.etage_wrapper').find('.etage_section_info_btn').removeClass('active');
        }
    }).on('click', '.etage_section_info_btn', function() {
        $(this).toggleClass('selected');
        $(this).parents('.etage_wrapper').find('.section_info').toggleClass('active');
    }).on('click', '.scheme_switch_block', function() {
        var s = $(this).parents('.scheme_switcher').attr('data-selector');
        var a = $(this).attr('data-active');
        $(this).addClass('active').siblings().removeClass('active');
        $(s).removeClass('active');
        $(a).addClass('active');
    }).on('click', '.section_info_print', function() {
        printDiv('print_wrapper');
    }).on('click', '.section_info_close', function() {
        $(this).parents('.section_info').removeClass('active');
        $(this).parents('.etage_wrapper').find('.etage_section_info_btn').removeClass('selected');
    }).on('click', '.open_popup', function(e) {
        e.preventDefault();
        var el = $('#' + $(this).attr('data-id'));
        $('.popup_box').hide();
        if ($(this).data('book')) {
            $.ajax({
                url: '/ajax/books?p=documents topImage&f={"_id":"'+$(this).data('book').replace(/"/g,'')+'"}',
                type: 'get',
                success: function (d) {
                    d = d.data[0];
                    var lang = $('#current_language').data('lang');
                    var wrap = $('#popup_book');
                    $('#popup_wrapper').show();
                    if (d) {
                        console.log(d);
                        if (d.topImage) wrap.find('.popup_book_image').attr('src', '/attachments/' + d.topImage.attachment.original.path);
                        if (d.author && d.author[lang]) wrap.find('.collection_book_author').html(d.author[lang]);
                        else wrap.find('.collection_book_author').html("");

                        if (d.title && d.title[lang]) wrap.find('.collection_book_title').html(d.title[lang]);
                        else wrap.find('.collection_book_title').html("");

                        if (d.edition && d.edition.publisher) {
                            wrap.find('.collection_book_publisher').html(d.edition.publisher + " " + d.edition.year);
                        } else wrap.find('.collection_book_publisher').html("");

                        if (d.description && d.description[lang]) wrap.find('.collection_book_description').html(d.description[lang]);
                        else wrap.find('.collection_book_description').html("");

                        if (d.quote && d.quote[lang])
                            wrap.find('.collection_book_cite').html(d.quote[lang]).show();
                        else wrap.find('.collection_book_cite').hide();

                        if (d.review && d.review[lang]) wrap.find('.collection_book_review').html(d.review[lang]);
                        else wrap.find('.collection_book_review').html("");

                        if (d.lang && d.lang[lang]) wrap.find('.collection_book_lang').html(d.lang[lang]);
                        else wrap.find('.collection_book_lang').html("");

                        if (d.location && d.location[lang]) wrap.find('.collection_book_location').html(d.location[lang]);
                        else wrap.find('.collection_book_location').html("");

                        if (d.bibliog_desc && d.bibliog_desc[lang]) wrap.find('.collection_book_bibliog_desc').html(d.bibliog_desc[lang]);
                        else wrap.find('.collection_book_bibliog_desc').html("");

                        if (d.availInLib) wrap.find('.collection_book_readinghall').show();
                        else wrap.find('.collection_book_readinghall').hide();

                        if (d.lang && d.lang[lang]) {
                            wrap.find('.collection_book_label').html(d.lang[lang]).show();
                            wrap.addClass('with_label');
                        } else {
                            wrap.find('.collection_book_label').html("").hide();
                            wrap.removeClass('with_label');
                        }

                        if (d.location && d.location[lang]) {
                            wrap.find('.collection_book_place').html(d.location[lang]);
                            wrap.find('.collection_book_place_wrapper').show();
                        } else wrap.find('.collection_book_place_wrapper').hide();

                        if (d.documents && d.documents.length) {
                            $('.collection_book_download').attr('href', '/attachments/' + d.documents[0].attachment.original.path).show();
                        } else $('.collection_book_download').hide();
                    }
                }
            })
        } else {
            $('#popup_wrapper').show();
        }
        el.show();
        $('header, nav, main, #footer').css('filter', 'blur(5px)');
        $('body').css('overflow', 'hidden');
    }).on('click', '#popup_wrapper, .popup_close, .mobile_close', function(e) {
        if ($(this).hasClass('popup_close') || $(this).hasClass('mobile_close') || $(e.target).attr('id') == 'popup_wrapper') {
            $('.popup_box').hide();
            $('#popup_wrapper').hide();
            $('header, nav, main, #footer').css('filter', 'blur(0)');
            $('body').css('overflow', 'auto');
        }
    }).on('change', '.search_filter input', function() {
        var sel = '.search_' + $(this).val();
        if (this.checked) {
            $(sel).show();
        } else {
            $(sel).hide();
        }
    }).on('click', '.open_place_popup', function(e) {
        e.preventDefault();
        $('.popup_box').hide();
        $('#popup_wrapper').show();
        $('#popup_map').show();
    }).on('click', '.btn_event_register', function(e) {
        e.preventDefault();
        $('.popup_box').hide();
        $('#popup_wrapper').show();
        $('#popup_event').show();
    }).on('click', '.become_link', function(e) {
        e.preventDefault();
        $('.popup_box').hide();
        $('#popup_wrapper, #popup_reader').show();
        $('header, nav, main, #footer').css('filter', 'blur(5px)');
    }).on('click', '.index_search_wrapper img', function() {
        $('.index_search_select').toggleClass('active');
    }).on('click', '.index_search_option', function() {
        var text = $(this).attr('data-text');
        var act = $(this).attr('data-action');
        $('#index_search').attr('placeholder', text);
        $('#index_search_form').attr('action', act);
        $('.index_search_select').removeClass('active');
    }).on('click', '#pageNext, #pagePrev, .paginator_links .avaible', function() {
        var elem = $('.switcher_wrapper').length ? $('.switcher_wrapper') : $('.filters_wrapper');
        var offs = elem.offset()['top'] - $('#fixed_menu').height();
        window.scrollTo(0, offs);
    }).on('click', '.event_free_enter', function(e) {
        e.preventDefault();
        $('.popup_box').hide();
        $('#popup_wrapper').show();
        $('#popup_enter_free').show();
    }).on('click', '.remove_date', function() {
        $(this).siblings('input').val('').trigger('change');
    }).on('click', '.events_type', function(e) {
        e.preventDefault();
        var id = $(this).attr('href');
        var sel = $('#filter_category').length ? $('#filter_category') : $('#filter_department');
        sel.val(id).trigger('change').selectmenu('refresh');
    }).on('change', '#filter_category', function() {
        var v = $(this).val();
        if (v)
            window.location.hash = JSON.stringify({category: $(this).val()});
        else
            window.location.hash = '';
    }).on('change', '#filter_department', function() {
        var v = $(this).val();
        if (v)
            window.location.hash = JSON.stringify({news: $(this).val()});
        else
            window.location.hash = '';
    }).on('change', '#collection_filter', function() {
        var v = $(this).val();
        if (v)
            window.location.hash = JSON.stringify({collection: $(this).val()});
        else
            window.location.hash = '';
    }).on('click', '.vf_search_form img', function() {
        var wrap = $('#search_wrapper');
        if (wrap.hasClass('catalogue')) {
            $(this).siblings('.site_search').hide();
            $(this).siblings('.catalogue_search').toggle();
        } else {
            $(this).siblings('.catalogue_search').hide();
            $(this).siblings('.site_search').toggle();
        }

    }).on('click', '.breadcrumb_current', function() {
        $(this).find('.breadcrumb_links').toggle();
    }).on('click', '.vf_search_option', function() {
        $('#search').attr({
            'data-filters': $(this).data('action'),
            'placeholder': $(this).data('text')
        });
        $('.vf_search_select').hide();
    }).on('click', '.banners_show_up', function() {
        var $container = $('.banners_show_inner');
        var current_pos = $container.attr('data-y') ? parseInt($container.attr('data-y')) : 0;
        var shift = 200;
        if (current_pos > 0) {
            if (current_pos - shift < 0) current_pos = shift;
            $container.css('transform', 'translateY(-' + (current_pos - shift) + 'px)')
            $container.attr('data-y', current_pos - shift);
        }
    }).on('click', '.banners_show_down', function() {
        var $container = $('.banners_show_inner');
        var current_pos = $container.attr('data-y') ? parseInt($container.attr('data-y')) : 0;
        var shift = 200;
        var max = ($container.find('.column_banner').length - 3) * shift;
        if (current_pos < max) {
            if (current_pos + shift > max) current_pos = max - shift;
            $container.css('transform', 'translateY(-' + (current_pos + shift) + 'px)')
            $container.attr('data-y', current_pos + shift);
        }
    }).on('click', '.search_switch', function() {
        var wrap = $('#search_wrapper');
        if (wrap.hasClass('catalogue')) {
            wrap.removeClass('catalogue');
            $(this).html('Поиск <b>по сайту</b>');
            $('#search').attr('placeholder', 'Поиск по сайту');
        } else {
            wrap.addClass('catalogue');
            $(this).html('Поиск <b>по каталогу</b>');
            $('#search').attr('placeholder', 'Поиск по каталогу');
        }
        changeSearchPlaceholder();
    });
    if (window.location.search.indexOf('&f=') > 0) {
        var filter = window.location.search.substr(window.location.search.indexOf('&f=') + 3);
        console.log(filter);
        $('.search_filter input').each(function(i,e) {
            if ($(e).val() != filter) $(e).parents('label').click();
        });
    }


    /*
     ===================== News subscription  =====================
     */

    var options= {
        success: processSubscribe,
        error: errorSubscribe,
        beforeSubmit: beforeSubscrive

    };
    function processSubscribe(d) {
        console.log(d);
        $('#popup_post_result .popup_text a').text($('#popup_subscribe input[name="email"]').val());
        $('#popup_post_result .popup_text').show();
        $('#popup_post_result .popup_error_text').hide();
        $('.popup_box').hide();
        $('#popup_post_result').show();
    }
    function errorSubscribe(d) {
        if (d.status==200)
            return processSubscribe(d);
        console.log(d);
        $('#popup_post_result .popup_text').hide();
        $('#popup_post_result .popup_error_text').show();
        $('.popup_box').hide();
        $('#popup_post_result').show();

    }
    function beforeSubscrive() {
        if ($('.subscribe_email').val() == '') {
            $('.subscribe_email').addClass('error');
            e.preventDefault();
            return false;
        }
    }
    var form = $('#subscribe_form');
    if (form.ajaxForm)
        form.ajaxForm(options);
    /*
     ===================== end of News subscription  =================
     */

    /*
     ===================== EVENT REGISTRATION  =====================
     */

    var options= {
        success: processReg,
        error: errorReg,
        beforeSubmit: beforeReg

    };
    function processReg(d) {
        console.log(d);
        $('#popup_post_result .popup_text a').text($('#popup_event input[name="email"]').val());
        $('#popup_post_result .popup_text').show();
        $('#popup_post_result .popup_error_text').hide();
        $('.popup_box').hide();
        $('#popup_post_result').show();
    }
    function errorReg(d) {
        if (d.status==200)
            return processReg(d);
        console.log(d);
        $('#popup_post_result .popup_text').hide();
        $('#popup_post_result .popup_error_text').show();
        $('.popup_box').hide();
        $('#popup_post_result').show();
    }
    function beforeReg() {
        var err = false;
        $('#popup_event .req input').each(function(i,e) {
            if ($(e).val() == '') {
                $(e).addClass('error');
                err = true;
            } else {
                $(e).removeClass('error');
            }
        });
        if (err) {
            e.preventDefault();
            return false;
        }
    }
    var form = $('#event_form');
    if (form.ajaxForm)
        form.ajaxForm(options);

    if ($('.about_block').length) {
        $('.about_block span').css('visibility', 'hidden');
        setTimeout(function() {
            $('.about_block span').css('visibility', 'visible');
        }, 2000);
    }
    /*
     ===================== EVENT REGISTRATION =================
     */

    /*
     ===================== NEW NEWS/EVENTS LIST =================
     */
    /*moment.tz.setDefault('Europe/Moscow');
    if ($('#block_types').length) {
        var lang = $('#current_language').data('lang');
        moment.locale(lang);
        var BLOCKS = {
            event : {
                url: '/' + lang + '/eventlist',
                container: '.event_by_date',
                template: $('#event_template').html()
            },
            recommend: {
                url: '/ajax/events_suggested',
                container: '.recommend_inner',
                template: $('#recommend_block').html()
            },
            news: {
                url: '/' + lang + '/newslist',
                container: '.news_wrapper',
                template: $('#event_template').html()
            }
        };
        var this_blocks = $('#block_types').val().split(' ');
        function blocks_success(block, append, data) {
            var d = data.data;
            if (block == 'event') {
                //alert(JSON.stringify(d));
            }
            var cur_block = BLOCKS[block];
            if (d) {
                switch (block) {
                    case 'event':
                        var d_by_date = {};
                        $.each(d, function (i, e) {
                            var date = moment(e.schedule.from).format('DD MMMM YYYY, dddd');
                            if (typeof d_by_date[date] == 'object') {
                                d_by_date[date].push(e);
                            } else {
                                d_by_date[date] = [e];
                            }
                        });
                        var d_html = '';
                        var d_tmpl = $('#date_template').html();
                        $.each(d_by_date, function (i, e) {
                            var html = '';
                            $.each(e, function (j, o) {
                                var flag = o.department.logo ? '<img class="events_flag" src="' + o.department.logo + '">' : '';
                                var label = false;
                                if (o.tag) label = '<div class="events_lang">' + o.tag + '</div>';
                                var time = moment(o.schedule.from).format('HH:mm')+"—"+moment(o.schedule.to).format('HH:mm');
                                html += cur_block.template.replace(/%image%/g, o.topImage? o.topImage.previewURL : '').replace(/%flag%/g, flag)
                                    .replace(/%type%/g, o.category ? o.category._id : '').replace(/%type_name%/g, o.category ? o.category.title : '')
                                    .replace(/%tag%/g, o.subCategory ? ('&ensp;|&ensp;' + o.subCategory) : '')
                                    .replace(/%title%/g, o.title).replace(/%time%/g, time)
                                    .replace(/%place%/g, o.place ? '<div class="events_place">' + o.place.title + '</div>' : '')
                                    .replace(/%views%/g, o.views)
                                    .replace(/%url%/g, o.url).replace(/%label%/g, label || '');
                            });
                            d_html += d_tmpl.replace(/%date%/g, i).replace(/%html%/g, html);
                        });
                        if (append)
                            $(cur_block.container).append(d_html);
                        else
                            $(cur_block.container).html(d_html);
                        break;
                    case 'recommend':
                        var html = '';
                        var ids = [];
                        var show;
                        show = d.filter(function(e) {
                            if (ids.indexOf(e._id) < 0) {
                                ids.push(e._id);
                                return true;
                            } else return false;
                        });
                        // var num;
                        // if (window.innerWidth > 1160)
                        //     num = show.length % 4;
                        // else
                        //     num = show.length % 3;
                        // var removed = show.splice(show.length-num, num);
                        $.each(show, function (i, e) {
                            if (e.schedule.length>1) {
                                var B = [];
                                e.schedule.forEach(function(date) {
                                    B.push(new Date(date.from)-0)
                                });
                                var min = Math.min.apply(null, B);
                                var max = Math.max.apply(null, B);
                                var date = moment(min).format('D MMMM')+"—"+moment(max).format('D MMMM')
                            } else
                                var date = moment(e.schedule[0].from).format('D MMMM');
                            html += cur_block.template.replace(/%image%/g, "/attachments/"+e.topImage.attachment.middle.defaultUrl)
                                .replace(/%type%/g, '/'+lang+'/item/events#{category:'+e.category._id+'}').replace(/%type_name%/g, e.category.title[lang])
                                .replace(/%tag%/g, e.subCategory ? ('&ensp;|&ensp;' + e.subCategory) : '').replace(/%date%/g, date)
                                .replace(/%title%/g, e.title[lang]).replace(/%url%/g, '/'+lang+'/event/'+e.slug);
                        });
                        if (show.length < 5) $('.recommend_arrow_right').addClass('disabled');
                        $(cur_block.container).html(html);
                        break;
                    case 'news':
                        var html = '';
                        $.each(d, function (i, e) {
                            var date = moment(e.date).format('D MMMM YYYY');
                            var tag = e.tag ? '<div class="events_lang">' + e.tag + '</div>' : '';
                            var type;
                            switch (e.type) {
                                case 'report':
                                    type = 'Репортаж';
                                    break;
                                case 'news':
                                    type = 'Новость';
                                    break;
                                case 'press':
                                    type = 'Пресса о нас';
                                    break;
                            }
                            var image = '';
                            if (e.topImage && e.topImage.previewURL) {
                                var flag = e.department.logo ? '<img class="events_flag" src="' + e.department.logo + '">' : '';
                                image = $('#events_image').html().replace(/%image%/g, e.topImage.previewURL).replace(/%flag%/g, flag).replace(/%url%/g, e.url);
                            }
                            html += cur_block.template.replace(/%image_block%/g, image).replace(/%url%/g, e.url)
                                .replace(/%type%/g, e.type).replace(/%type_name%/g, type)
                                .replace(/%date%/g, date).replace(/%special%/g, tag).replace(/%title%/g, e.title);
                        });
                        if (append)
                            $(cur_block.container).append(html);
                        else
                            $(cur_block.container).html(html);
                        break;
                    default:
                        console.error('Неизвестный тип блока');
                        break;
                }
                $('#nothing_found').hide();
                if (data.l + data.s >= data.total) {
                    $('#show_more_btn').hide();
                } else $('#show_more_btn').show();
            } else {
                $(cur_block.container).html('');
                $('#nothing_found').show();
                $('#show_more_btn').hide();
            }
        }

        for (var b = 0; b < this_blocks.length; b++) {
            ajax_blocks(this_blocks[b]);
        }
        function ajax_blocks(block, append) {
            var filters = {};
            if ($('#block_types').data('filters')) {
                $.extend(filters, $('#block_types').data('filters'));
            }
            if (block != 'recommend') {
                $('.events_filters_wrapper').find('select, input').each(function(i,e) {
                    var name = $(e).attr('name');
                    var val = $(e).val();
                    if ($(e).attr('type') == 'checkbox' && e.checked) filters[name] = true;
                    if (name == 'from') {
                        if (block == 'news') {
                            if (val != '')
                                val = moment(val, 'DD.MM.YYYY').add(1, 'days').toISOString();
                            filters.o = '{"date":-1}';
                            name = 'to';
                        } else if (val != '') {
                            val = moment(val, 'DD.MM.YYYY').toISOString();
                        }
                    }
                    if ($(e).attr('type') != 'checkbox' && val != '') filters[name] = val;
                });
                var btn = $('#show_more_btn');
                filters.s = parseInt(btn.data('s'));
                filters.l = parseInt(btn.data('l'));
                btn.data('s', filters.s + filters.l);
            }
            if (block == 'event') {
                //alert(JSON.stringify(filters));
                //alert(BLOCKS[block].url);
            }
            $.ajax({
                type: 'get',
                data: filters,
                url: BLOCKS[block].url,
                success: blocks_success.bind(this, block, append)
            });
        }

        var f_block = false;
        $('.events_filters_wrapper').find('input, select').on('change', function() {
            if (f_block) return;
            f_block = true;
            $('#show_more_btn').data('s', 0);
            var block = 'event';
            if ($('.news_wrapper').length) block = 'news';
            ajax_blocks(block);
            setTimeout(function() {
                f_block = false;
            }, 500);
        });

        $('#show_more_btn').on('click', function() {
            ajax_blocks($(this).data('block'), true);
        });
    }

    if ($('.content_big.events_wrapper').length) {
        $(window).on('scroll', function(ev) {
            var date = false;
            $('.events_wrapper .events_date_self').each(function(i,e) {
                if (window.scrollY + 50 > $(e).offset()['top']) {
                    date = $(e).text();
                }
            });
            if (date) {
                $('#fixed_date_wrapper').addClass('active');
                $('#current_date').text(date);
            } else {
                $('#fixed_date_wrapper').removeClass('active');
                $('#current_date').text('');
            }
        });
    }*/

    /*
     ===================== NEW NEWS/EVENTS LIST =================
     */

    if ($('#popup_plan').length) {
        var months = {
            'en': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'ru': ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
        };
        $('#popup_plan .popup_text').hide();
        var date;
        var time_to;
        var html = '';
        var m_html = '';
        var d_html = '';
        var cur_month = false;
        var month = '<div class="popup_days_month"><div class="popup_month_name">%month% %year%</div>%days_html%</div>';
        var day = '<div class="popup_days_day"> \
                        <span class="addtocalendar atc-style-blue"> \
                            <a class="atcb-link">%day%</a> \
                            <var class="atc_event"> \
                                <var class="atc_date_start">%atc_date_start%</var> \
                                <var class="atc_date_end">%atc_date_end%</var> \
                                <var class="atc_timezone">Europe/Moscow</var> \
                                <var class="atc_title">%atc_title%</var> \
                                <var class="atc_description">%atc_description%</var> \
                                <var class="atc_location">Moscow</var> \
                                <var class="atc_organizer">%atc_organizer%</var> \
                                <var class="atc_organizer_email">%atc_organizer_email%</var> \
                            </var> \
                            <small>%time%</small> \
                        </span> \
                   </div>';
        var days_found = false;
        var lang = window.location.pathname.split("/")[1] || "ru";
        var email = (window.event_obj && window.event_obj.responsible && window.event_obj.responsible.profile)?window.event_obj.responsible.profile.email:window.event_obj.department.email;
        email = email || "";


        // console.log("DATA2:", local_data2);
        $('.plan_day_wrapper').each(function(i,e) {
            date = moment(JSON.parse($(e).text()).from, 'YYYY-MM-DD HH:mm:ss zzZZ');
            // date = moment($(e).text(), 'ddd MMM D YYYY HH:mm:ss zzZZ');
            time_to = moment(JSON.parse($(e).text()).to, 'YYYY-MM-DD HH:mm:ss zzZZ');
            if (date.diff(moment(), 'days') >= 0) {
                days_found = true;
                if (cur_month && cur_month != date.month()) {
                    m_html = month.replace(/%month%/g, months[window.location.pathname.split('/')[1]][cur_month]).replace(/%year%/g, date.format('YYYY')).replace(/%days_html%/g, d_html);
                    html += m_html;
                    d_html = '';
                    cur_month = date.month();
                }
                d_html += day.replace(/%day%/g, date.format('D'))
                    .replace(/%time%/g, date.format('HH:mm')+"—"+time_to.format('HH:mm'))
                    .replace(/%atc_date_start%/g, date.format("YYYY-MM-DD HH:mm"))
                    .replace(/%atc_date_end%/g, time_to.format("YYYY-MM-DD HH:mm"))
                    .replace(/%atc_title%/g, window.event_obj.category.title[lang])
                    .replace(/%atc_description%/g, window.event_obj.title[lang])
                    .replace(/%atc_organizer%/g, window.event_obj.responsible && window.event_obj.responsible.profile?window.event_obj.responsible.profile.name:window.event_obj.department.title[lang])
                    .replace(/%atc_organizer_email%/g, email);
                if (!cur_month) cur_month = date.month();
            }
        });
        m_html = month.replace(/%month%/g, months[window.location.pathname.split('/')[1]][cur_month]).replace(/%year%/g, date.format('YYYY')).replace(/%days_html%/g, d_html);
        html += m_html;
        $('#popup_plan .popup_text').html(html).show();
        if (days_found) {
            $('.open_popup_plan').show();
        }
    }

});
$(window).load(function() {

    if ($('.about_block').length) {
        $('.about_block span').css('visibility', 'hidden');
        $('.about_block').each(function(i,e) {
            var w = $(e).width();
            var iw = $(e).find('span').width();
            var fz = 24;
            while (iw > w) {
                fz -= 1;
                $(e).find('span').css('font-size', fz + 'px');
                iw = $(e).find('span').width();
            }
            $(e).find('span').css('visibility', 'visible');
        });
    }
});

$(window).on('scroll', function() {
    var wScroll = $(this).scrollTop();
    var introHeight = $('.index_news_wrapper').height();
    $('.news_right_image').css({
        'transform': 'translate(0px, ' + wScroll * 0.3 + 'px)'
    });
    $('.news_left_image').css({
        'transform': 'translate(0px, -' + wScroll * 0.3 + 'px)'
    });
});