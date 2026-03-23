$(document).ready(function () {
    // Dark theme toggle
    const htmlEl = $('html')[0];
    const toggleBtn = $('#theme-toggle');
    if (toggleBtn.length) {
        const icon = toggleBtn.find('i');
        const isDark = htmlEl.classList.contains('dark-theme');
        icon.removeClass('fa-moon-o fa-sun-o').addClass(isDark ? 'fa-sun-o' : 'fa-moon-o');
        toggleBtn.on('click', function() {
            const currentDark = htmlEl.classList.contains('dark-theme');
            if (currentDark) {
                htmlEl.classList.remove('dark-theme');
                icon.removeClass('fa-sun-o').addClass('fa-moon-o');
                localStorage.setItem('darkMode', 'disabled');
            } else {
                htmlEl.classList.add('dark-theme');
                icon.removeClass('fa-moon-o').addClass('fa-sun-o');
                localStorage.setItem('darkMode', 'enabled');
            }
        });
    }

    $("#brand-logo").owlCarousel({
        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items: 6,
        itemsDesktop: [1199, 6],
        itemsDesktopSmall: [979, 6]
    });

    $('.select2').select2();

    if ($('#thumbnails li img').length > 0) {
        $('#thumbnails li img').on('click', function () {
            $('#main-image')
                .attr('src', $(this).attr('src') +'?w=400')
                .attr('data-zoom', $(this).attr('src') +'?w=1200');
        });
    }

    $(".img-orderDetail").mouseover(function() {
      $(this).css({ width: '150px', height: '150px' });
    }).mouseout(function() {
      $(".img-orderDetail").css({ width: '50px', height: '50px'});
    });
});