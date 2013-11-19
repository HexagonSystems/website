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
    private $header = FALSE;
    private $nav = FALSE;
    /**
     * Accept a template to load
     */
    public function __construct($header,$nav)
    {

        if (file_exists($header)) {

            $this->header = $header;
        }
        if (file_exists($nav)) {

            $this->nav = $nav;
        }
    }
    /** 
     * Receives assignments from controller and stores in local data array
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
        
        $data = $this->data;

        foreach ($data['navigation'] as $key => $navItem) {
            if($navItem['parentId'] != null){
                $data['navigation'][$navItem['parentId']]['children'][] = $navItem;
                unset($data['navigation'][$key]);
            }
        }

        //render view
        include_once($this->header);
        include_once($this->nav);
    }
} //end class
