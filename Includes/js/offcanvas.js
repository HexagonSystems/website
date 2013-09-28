$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });


    // back to top
    setTimeout(function () {
      var $sideBar = $('.sidebar-offcanvas')

      $sideBar.affix({
        offset: {
          top: function () {
            var offsetTop      = $sideBar.offset().top
            var sideBarMargin  = parseInt($sideBar.children(0).css('margin-top'), 10)
            var navOuterHeight = $('.navbar-hexagon').height()

            return (this.top = offsetTop - navOuterHeight - sideBarMargin)
          }
        , bottom: 0
        }
      })
    }, 100)

    setTimeout(function () {
      $('.bs-top').affix()
    }, 100)

});