<?php
Namespace Task;

class Controller
{
    protected $footer;
    protected $nav;
    protected $currentPagePosts;
    protected $database;
    protected $template = 'index';

    public function __construct($template = NULL)
    {
        if($template != NULL)
            $this->template = $template;

        $this->nav = new HeadController();
        $this->footer = Base.'/View/Template/footer.php';
    } //end constructor

    public function setDatabase(\PDO $database)
    {
        $this->database = $database;
    }

    public function invoke()
    {
            $this->nav->invoke();
            
            $this->template = Base.'/View/Template/'.$this->template.'Template.php';

    } // end function
}
