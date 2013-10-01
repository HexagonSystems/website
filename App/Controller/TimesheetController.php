
<?php

class TimesheetController extends Controller
{
	
	

    public function invoke()
    {
    	
        
        if (!isset($_GET['action'])) {
        	$template_viewAll = "timesheetViewAll";
        	$this->template = $template_viewAll;
        	parent::invoke();
            $task = new Task();
            $task->setDatabase($this->database);
            $arrayTask = $task->loadTasks(1);
            
            //create a new view and pass it our template
            $view = new TimesheetView($this->template,$this->footer, 0);
            $view->assign('title' , 'Logged in');
            $view->assign('task', $arrayTask);
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
        		$taskLoader = new Task();
        		$taskLoader->setDatabase($this->database);
        		
        		if(!$taskLoader->load($_GET['param']))
        		{
        			/* HANDLE IF UNABLE TO LOAD TASK */
        			echo "fail";
        		}
        		
        		$taskComments = $taskLoader->loadComments($_GET['param'], 0);
        		 
        		//create a new view and pass it our template
        		$view = new TimesheetView($this->template,$this->footer, 0);
        		$view->assign('title' , 'Logged in');
        		
        		$view->assign('task' , $taskLoader);
        		$view->assign('comments' , $taskComments);
        		
        		//$taskLoader->createComment("@addedTag", "Yay, adding tags is working");
        	}
        	
        }else
        {
        	echo "Something went wrong";
        }

    } // end function
}

?>