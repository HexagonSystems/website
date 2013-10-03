
<?php

class TimesheetController extends Controller
{
	
	

    public function invoke()
    {
    	
        
        if (!isset($_GET['action'])) {
        	$template_viewAll = "timesheetViewAll";
        	$this->template = $template_viewAll;
        	parent::invoke();
            $taskLoader = new TaskLoader();
            $taskLoader->setDatabase($this->database);
            
            //create a new view and pass it our template
            $view = new TimesheetView($this->template,$this->footer, 0);
            $view->assign('title' , 'Logged in');
            $view->assign('task', $taskLoader->loadTasks(1));
        }else if($_GET['action'] == "single")
        {
        	if(!isset($_GET['param']))
        	{
        		$template_viewAll = "timesheetViewSingle";
        		$this->template = $template_viewAll;
        		parent::invoke();
        		 
        		//create a new view and pass it our template
        		$view = new TimesheetView($this->template,$this->footer, 0);
        		$view->assign('title' , 'Logged in');
        	}else
        	{
        		$template_viewAll = "timesheetViewSingle";
        		$this->template = $template_viewAll;
        		parent::invoke();
        		
        		$taskLoader = new TaskLoader();
        		$taskLoader->setDatabase($this->database);
        		$task = $taskLoader->loadTask($_GET['param']);
        		if($task === false)
        		{
        			/* HANDLE IF UNABLE TO LOAD TASK */
        			echo "fail";
        		}
        		
        		// maybe a method will go here that will determine which page to load (depending on GET/POST/SESSION
        		$task->loadComments(0);
        		 
        		//create a new view and pass it our template
        		$view = new TimesheetView($this->template,$this->footer, 0);
        		$view->assign('title' , 'Logged in');
        		
        		$view->assign('task' , $task);
        		$view->assign('comments' , $task->getComments());
        		
        		//$taskLoader->createComment("@addedTag", "Yay, adding tags is working");
        	}
        	
        }else
        {
        	echo "Something went wrong";
        }

    } // end function
}

?>