$(".hexagon-hamburger").click(function() {
	$(".hexagon-mobile-dropdown").toggleClass("makevisible");
});

$(function() {
  $( "#tabs" ).tabs();
});

$(".hexagon-infoBlock").click(function() {
	$(this).siblings().addClass("hexagon-opacity0", 1000);
	$(this).css('position', 'absolute').css('top', offset.top).css('left', offset.left);
    $(this).switchClass("pure-u-1-3 hexagon-infoBlock", "hexagon-infoBlock-view", 1000, "easeInOutQuad");
});

$(".hexagon-searchBox img").click(function() {
	$(this).toggle("hexagon-displayNone").siblings().toggle("hexagon-displayBlock");
});