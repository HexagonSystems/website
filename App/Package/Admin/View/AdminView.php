<?php
namespace Admin;
/**
 * Handles the view functionality of our MVC framework
 */
class AdminView
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
    	$template = AppBase.'/'.Base.'/View/Template/'.$template.'Template.php';
        if (file_exists($template)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->render = $template;
        }else
        {
        	echo "FILE DOES NOT EXIST";
        	echo $template;
        }
        if (file_exists(AppBase.'/View/Template/'.$footer.".html")) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */
            $this->footer = AppBase.'/View/Template/'.$footer.".html";
        }else
        {
        	echo "Failed loading footer " . AppBase.'/View/Template/'.$footer;
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
        include_once($this->footer);

    }
} // end class