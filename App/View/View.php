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
    protected $footer = 'footer';
    protected $template = array();

    /**
     * Error pages
     */
    protected $error = array(404 => "/View/Template/404.html");

    /**
     * Accept a template to load
     */
    public function __construct($template,$footer = 'footer')
    {
        
        //Defaults set
        $this->error[404] = AppBase.$this->error[404];
        $footer = AppBase."/View/Template/".$footer.".php";

        //Place the template in the file structure
        $template = AppBase."/View/Template/".$template.".php";
        
        /**
         * trigger render to include file when this model is destroyed
         * if we render it now, we wouldn't be able to assign variables
         * to the view!
         */
        if (file_exists($template)) {
            $this->template[] = $template;
        }else{
        	//echo "ERROR in ".__FILE__." on line: ".__LINE__.": File not found at:".$template;
            $this->template[] = $this->error['404'];
        }
        if (file_exists($footer)) {
            $this->footer = $footer;
        }else{
            echo "ERROR in ".__FILE__." on line: ".__LINE__.": File not found at:".$footer;
        }
    }


    /**
     * Receives Template components from controller and stores in local array
     * @param  String|Int $name Key for the component being assigned
     * @param  String     $file Filename of the template component being added.
     * @return Void
     */
    public function setComponent($name , $filename)
    {
        $this->component[$name] = $filename;
    }

    /**
     * Receives data and model assignments from controller and stores in local data array
     * @param  String|Int $variable Key for the data being assigned
     * @param  Multiple   $value    Value of the data being assigned can be String, Int, Array, Object etc.
     * @return Void
     */
    public function assign($variable , $value)
    {
        $this->data[$variable] = $value;
    }

    /**
     * This function is designed to be overrided in sub classes to give the sub Views greater control over layout of
     * elements and handling of models and display data.
     * @return Void
     */
    protected function render(){
        //render view
        foreach ($this->template as $key => $value) {
            try {
                include_once($value);
            } catch (Exception $e) {
                include_once($this->error['404']);
            }
        }
    }

    /**
     * This function is designed to be overrided in sub classes to give the sub Views greater control over layout of
     * elements and handling of models and display data.
     * @return Void
     */
    protected function renderFooter(){
        try {
            include_once($this->footer);
        } catch (Exception $e) {
            echo 'Footer missing';
        }
    }

    public function __destruct()
    {
        //parse data variables into local variables, so that they render to the view
        $data = $this->data;

        $this->render();

        $this->renderFooter();

    }
} //end class
