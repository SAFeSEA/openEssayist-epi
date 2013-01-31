/***
 *  dfsdsgf dfsg fdg fdsgfdsdfsg dfsg fdssfg 
 *  
 */
!function($) {

	/**
	 * fds g
	 */
	$(function() {

		var $window = $(window)

		// make code pretty
		window.prettyPrint && prettyPrint()

		// side bar
		$('.bs-docs-sidenav').affix({
			/*offset : {
				top : function() {
					return $(window).width() <= 980 ? 290 : 210
				},
				bottom : 0
			}*/
			offset: 20
		})
		
		if ($("[rel=tooltip]").length) {
        $("[rel=tooltip]").tooltip();
    }

	})

}(window.jQuery)