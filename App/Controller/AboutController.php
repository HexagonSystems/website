<?php

class AboutController extends Controller
{
	protected $template = 'Index';

    public function invoke()
    {
        parent::invoke();
        
        //create a new view and pass it our template
        $view = new IndexView($this->template,$this->footer);
        
    } // end function
}

?>