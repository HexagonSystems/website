
<?php

class Controller
{
    protected $footer;
    protected $header;
    protected $database;
    protected $template = 'index';
    protected $get;
    protected $post;

    public function __construct($template = NULL, $get = NULL, $post = NULL)
    {
        if($template != NULL)
            $this->template = $template;

        if($get != NULL)
            $this->get = $get;

        if($post != NULL)
            $this->post = $post;

        $this->header = new HeadController();
        $this->footer = 'footer';
    } //end constructor

    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    public function invoke()
    {
            $this->header->invoke();

    } // end function
}
