<?php

class ProjectController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($this->get['action'])) {
            //create a new view and pass it our template
            $view = new ProjectView($this->template,$this->footer);
        }

    } // end function
}

?>