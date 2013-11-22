<?php

		/**
		 * This file assigns the template to a variable
		 *
		 * @author Sam Imeneo
		 */

class SiteMapController extends Controller
{
    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {
		
			$this->template = 'SiteMapTemplate';
			$view = new AboutView($this->template, $this->footer);

		}
    } // end function
}

?>