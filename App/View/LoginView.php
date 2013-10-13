<?php
/**
 * Handles the view functionality of our MVC framework
 */
class LoginView
{
    /**
     * Holds variables assigned to template
     */
    private $data = array();
    /**
     * Holds render status of view.
    */
    private $render = FALSE;
    private $footer = FALSE;
    /**
     * Accept a template to load
     */
    public function __construct($template,$footer)
    {
        // echo "In Consttructor" ;
        if (file_exists($template)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->render = $template;
        }
        if (file_exists($footer)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->footer = $footer;
        }
    }
    /*** Receives assignments from controller and stores in local data array
     *
    * @param $variable
    * @param $value
    */
    public function assign($variable , $value)
    {
        $this->data[$variable] = $value;
    }
    public function __destruct()
    {
        //parse data variables into local variables, so that they render to the view
        $data = $this->data;
        //echo "In Destructor" ;
        //render view
        include_once($this->render);
        include_once($this->footer);
    }
} //end class
