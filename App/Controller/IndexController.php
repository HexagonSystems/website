
<?php

class IndexController extends Controller
{

    public function invoke()
    {
        parent::invoke();
        
        if (!isset($_GET['action'])) {

            $post = new Post();
            $post->setDatabase($this->database);
            $this->currentPagePosts = $post->getPosts(0, 10);

            //create a new view and pass it our template
            $view = new IndexView($this->template,$this->footer, $this->currentPagePosts);
            $view->assign('title' , 'Logged in');
        }

    } // end function
}

?>