
<?php

class IndexController extends Controller
{
	protected $template = 'IndexTemplate';

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {
			$this->template = 'indexTemplate';
            //create a new view and pass it our template
            $view = new IndexView($this->template,$this->footer);
        }
        
    } // end function
}

?>