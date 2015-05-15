$(document).ready(function () {
    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });
    var nav = $('#sidebar .nav-stacked');
    nav.append('<li><a href="#top">Top</a></li>');
    $('.anchor').each(function() {
        var id = $(this).attr('id');
        nav.append('<li><a href="#'+id+'">'+$(this).next('h1,h2,h3,h4').text()+'</a></li>');
    });
    $('body').scrollspy({ target: '#sidebar' });
});

