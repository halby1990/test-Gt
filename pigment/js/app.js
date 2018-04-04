(function ($) {

    var actus = {
        un: {
            photo: '../images/chaise.jpg',
            texte: 'Deuxieme texte à afficher',
            vues: '240'
        },
        deux: {
            photo: '../images/slider_1.jpg',
            texte: 'Lorem ipsum dolor',
            vues: '100'
        },
        trois: {
            photo: '../images/rocher.jpg',
            texte: 'Dolor sit amen',
            vues: '198'
        }
    };


    $(document).ready(function () {

        $('#slider').slick({
            autoplay: true,
            autoplaySpeed: 3000
        });

        $('#burger').click(
            function () {
                if ($(this).hasClass('open')) {
                    $(this).removeClass('open');
                } else {
                    $(this).addClass('open');
                }
            }
        );


        $('[data-fancybox]').fancybox({
            protect : true
        });

        $('#menu_haut a').click(function (event) {
            if ($(this).next('.submenu').length) {
                event.preventDefault();
                if ($(this).hasClass('ouvert')) {
                    $(this).removeClass('ouvert');
                    $(this).next('.submenu').slideUp();
                } else {
                    if ($('#menu_haut a.ouvert').length) {
                        $('#menu_haut a.ouvert').next('.submenu').slideUp();
                        $('#menu_haut a.ouvert').removeClass('ouvert');
                        $(this).addClass('ouvert');
                        $(this).next('.submenu').slideDown();
                    } else {
                        $(this).addClass('ouvert');
                        $(this).next('.submenu').slideDown();
                    }
                }
            }
        });


        var actuBox = $('#actuFlash');

        if (actuBox.length) {
            var close = $('<span id="closeActu" data-view=""></span>');
            actuBox.append(close);

            close.click(function () {
                var img = actuBox.find('.imageActu');
                var h2 = actuBox.find('h2');
                var view = actuBox.find('a.blanc');

                var data = $(this).attr('data-view');

                var att;
                var o;

                switch (data) {
                    case '' :
                        att='un';
                        o = actus.un;
                    break;
                    case 'un' :
                        att='deux';
                        o = actus.deux;
                    break;
                    case 'deux' :
                        att='trois';
                        o = actus.trois;
                    break;
                    case 'trois' :
                        att='un';
                        o = actus.un;
                    break;
                }

                img.attr('src', o.photo);
                h2.html(o.texte);
                view.html(o.vues);

                $(this).attr('data-view', att);

            });
        }


        $(window).scroll(function () {
            var s = $(window).scrollTop();
            var h = $('#droite').offset().top;
            
            if (h < (s + 300)) {
                $('#droite').addClass('visible');
            } else {
                $('#droite').removeClass('visible');
            }
        });

    });

})(jQuery);