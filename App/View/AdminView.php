<?php
namespace Task;
/**
 * Handles the view functionality of our MVC framework
 */
class timesheetView
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
    private $template = FALSE;
    private $currentPagePosts = FALSE;
    /**
     * Accept a template to load
     */
    public function __construct($template,$footer,$currentPagePosts)
    {
        // echo "In Constructor" ;
        if (file_exists(AppBase.'/'.$template)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->render = AppBase.'/'.$template;
        }else
        {
        	echo "FILE DOES NOT EXIST";
        	echo AppBase.'/'.$template;
        }
        if (file_exists(AppBase.'/'.$footer)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->footer = $footer;
        }
        if ($currentPagePosts) {
            $this->currentPagePosts = $currentPagePosts;
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
    
    private function printCurrentPagePosts()
    {
        foreach ($this->currentPagePosts as $blog) {
            include AppBase.'/View/Template/postPreviewTemplate.php';
        }
    }
    public function __destruct()
    {
        //parse data variables into local variables, so that they render to the view
        $data = $this->data;
        //echo "In Destructor" ;
        //render view
        
        try {
            include($this->render);
        } catch (Exception $e) {

        }
        include_once(AppBase.'/'.$this->footer);

    }
} // end class