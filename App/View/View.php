<?php
/**
 * Handles the view functionality of our MVC framework
 */
class View
{
    /**
     * Holds variables assigned to template
     */
    protected $data = array();
    /**
     * Holds render status of view.
    */
    protected $render = FALSE;
    protected $footer = FALSE;
    protected $template = FALSE;
    /**
     * Accept a template to load
     */
    public function __construct($template,$footer)
    {
        $template = AppBase."/View/Template/".$template.".php";
        $footer = AppBase."/View/Template/".$footer.".php";
        if (file_exists($template)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->render = $template;
        }else{
        	echo "ERROR in ".__FILE__." on line: ".__LINE__.": File not found at:".$template;
        }
        if (file_exists($footer)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->footer = $footer;
        }else{
            echo "ERROR in ".__FILE__." on line: ".__LINE__.": File not found at:".$template;
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
        
        //render view
        try {
            include_once($this->render);
        } catch (Exception $e) {

        }

        include_once($this->footer);

    }
} //end class
