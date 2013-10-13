
<?php

class Controller
{
    protected $footer;
    protected $nav;
    protected $database;
    protected $template = 'index';

    public function __construct($template = NULL)
    {
        if($template != NULL)
            $this->template = $template;

        $this->nav = new HeadController();
        $this->footer = 'footer';
    } //end constructor

    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    public function invoke()
    {
            $this->nav->invoke();

    } // end function
}
