
<?php

class IndexController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {
            //create a new view and pass it our template
            $view = new IndexView($this->template,$this->footer);
        }

    } // end function
}

?>