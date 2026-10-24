$(document).ready(function () {

    $('#mobile-menu-toggler1').on('click',function () {

        console.log('1231')
        if ($('#mobile-menu-toggler1').hasClass('active')){

            $('#mobile-menu-toggler1').removeClass('active');
            $('#mobileNav').hide('slow');
        }else {

            $('#mobile-menu-toggler1').addClass('active');
            $('#mobileNav').show('slow');
        }
    });


});

