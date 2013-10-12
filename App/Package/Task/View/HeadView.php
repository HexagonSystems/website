<?php
/**
 * Handles the view functionality of our MVC framework
 */
class HeadView
{
    /**
     * Holds variables assigned to template
     */
    private $data = array();
    /**
     * Holds render status of view.
    */
    private $render = FALSE;
    private $header = FALSE;
    private $nav = FALSE;
    /**
     * Accept a template to load
     */
    public function __construct($template,$header,$nav)
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
        if (file_exists($header)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->header = $header;
        }
        if (file_exists($nav)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->nav = $nav;
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
        include_once($this->header);
        include_once($this->nav);
    }
} //end class
