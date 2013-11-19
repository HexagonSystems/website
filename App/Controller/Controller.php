
<?php

class Controller
{
    protected $footer;
    protected $header;
    protected $database;
    // TODO Deprecated Remove after 20/11/13
    protected $template = 'index';
    protected $get;
    protected $post;
    protected $navigation;

    /**
     * This is the default constructor for Controllers it features a non-required ability to add templates.
     * It also feature the ability to set get and post this can be used for adding extra fields or for mocking unit
     * tests. It defaults to loading $_GET and $_POST automatically if $get and $post aren't defined.
     * @param Array  $get       The $_GET data from the browser or a mock array for testing
     * @param Array  $post      The $_POST data from the browser or a mock array for testing
     * @return Void
     */
    public function __construct($get = NULL, $post = NULL)
    {
        if($get != NULL){
            $this->get = $get;
        }else{
            $this->get = $_GET;
        };

        if($post != NULL){
            $this->post = $post;
        }else{
            $this->post = $_POST;
        };

        $this->header = new HeadController();
        $this->footer = 'footer';
    } //end constructor

    /**
     * Simple setter for the Database connection of the controller this is used for injecting the DB into any models the
     * controller class.
     * @param PDO $database Database connection to the App's database.
     * @return Void
     */
    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Simple setter for the Database connection of the controller this is used for injecting the DB into any models the
     * controller class.
     * @param PDO $database Database connection to the App's database.
     * @return Void
     */
    public function setNavigation($navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * Abstract function for triggering the controller.
     * This method needs to be extended in subclasses to add functionality.
     * @return Void
     */
    public function invoke()
    {
        if($this->navigation == null){

            $sth = $this->database->prepare("SELECT * FROM menu");
            $sth->execute();
            $pages = $sth->fetchAll(PDO::FETCH_ASSOC);

            $nav = array();

            foreach ($pages as $key => $value) {
                $value['link'] = 'index.php?location='.$value['link'];
                $nav[$value['menuId']] = $value;
            }

            $this->navigation = $nav;
        }

        $this->header->setNavigation($this->navigation);
        $this->header->invoke();

    } // end function
}
