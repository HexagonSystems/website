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
            //The distance from the top of the sidebar
            var offsetTop      = $sideBar.offset().top
            //The thickness of the navbar
            var navOuterHeight = $('.navbar').height()
            return (this.top = offsetTop - navOuterHeight)
          } 
        , bottom: 0
        }
      })
    }, 100)

    setTimeout(function () {
      $('.bs-top').affix()
    }, 100)

});