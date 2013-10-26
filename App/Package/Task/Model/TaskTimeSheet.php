<?php

namespace Task;

class TaskTimeSheet
{
	private $timesheetArray = array();
	private $timesheetDates = array();
	private $arrayOfUsersNames = array();
	private $arrayOfTaskNames = array();
	private $timesheetTotals = array();

	/**
	 * Builds and organises all of the data given through the array of Task Hours
	 *
	 * @param array $taskHourArray
	*/
	function buildTimeSheetFromTaskHourArray($taskHourArray)
	{
		foreach($taskHourArray as $taskHour)
		{
			$date = $taskHour->getDate();
			$taskData = $taskHour->getTask();
			$memberData = $taskHour->getMember();

			/* ADD TASK NAMES TO ARRAY FOR EASY ACCESS */
			if(!array_key_exists($taskData['id'], $this->arrayOfTaskNames))
			{
				$this->arrayOfTaskNames[$taskData['id']] = $taskData['value'];
			}

			/* ADD MEMBER NAMES TO ARRAY FOR EASY ACCESS */
			if(!array_key_exists($memberData['id'], $this->arrayOfUsersNames))
			{
				$this->arrayOfUsersNames[$memberData['id']] = $memberData['value'];
			}

			$this->timesheetArray[$memberData['id']][$taskData['id']][$date] = $taskHour->getHours();
		}

		foreach($this->timesheetDates as $date)
		{
			foreach($this->timesheetArray as $user => $userTimesheet)
			{
				foreach($userTimesheet as $task => $taskTimesheet)
				{
					if(!array_key_exists($date, $taskTimesheet))
					{
						$this->timesheetArray[$user][$task][$date] = "-";
					}
				}
			}
		}

		foreach($this->timesheetArray as $user => $userTimesheet)
		{
			foreach($userTimesheet as $task => $taskTimesheet)
			{
				ksort($this->timesheetArray[$user][$task]);
			}
		}

		/*
		 * For testing purposes
		var_dump($this->timesheetDates);
		echo "<br/>";
		echo "<br/>";
		var_dump($this->timesheetArray);
		*/
	}

	function generateTotals()
	{
		foreach($this->timesheetArray as $user => $userTimesheet)
		{
			/* CREATE THE USER ARRAY IN TIME SHEET TOTALS */
			if(!array_key_exists($user, $this->timesheetTotals))
			{
				$this->timesheetTotals[$user] 			= array();
				$this->timesheetTotals[$user]['task'] 	= array();
				$this->timesheetTotals[$user]['date']	= array();
				$this->timesheetTotals[$user]['total']	= 0;
			}
				
			$totalTaskHours = 0;
			foreach($userTimesheet as $task => $taskTimesheet)
			{
				/* CREATE THE TASK ARRAY IN TIME SHEET TOTALS */
				if(!array_key_exists($task, $this->timesheetTotals[$user]['task']))
				{
					$this->timesheetTotals[$user]['task'][$task] = 0;
				}

				foreach($taskTimesheet as $date => $hours)
				{
					/* CREATE THE DATE ARRAY IN TIME SHEET TOTALS */
					if(!array_key_exists($date, $this->timesheetTotals[$user]['date']))
					{
						$this->timesheetTotals[$user]['date'][$date] = 0;
					}
					
					if($hours !== "-")
					{
						$this->timesheetTotals[$user]['date'][$date]	+= $hours;
						$this->timesheetTotals[$user]['task'][$task]	+= $hours;
						$this->timesheetTotals[$user]['total']			+= $hours;
					}

				}
			}
		}
		
		echo "Totals = <br/>";
		var_dump($this->timesheetTotals);
		
		
	}

	/**
	 * creating between two date
	 * @param string since
	 * @param string until
	 * @param string step
	 * @param string date format
	 * @return array
	 * @author Ali OYGUR <alioygur@gmail.com>
	 *
	 * Code taken from http://stackoverflow.com/questions/4312439/php-return-all-dates-between-two-dates-in-an-array
	 */
	function setDateRange($first, $last, $step = '+1 day', $format = 'Y-m-d' ) {

		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);

		while( $current <= $last ) {

			$dates[] = date($format, $current);
			$current = strtotime($step, $current);
		}

		$this->timesheetDates = $dates;
	}

	/**
	 * Returns either the date or the date's index in the date array depending on which is requested
	 *
	 * @param unknown $value
	 * @param unknown $searchByIndex
	 * @return multitype:|mixed
	 */
	function getDate($value, $searchByIndex)
	{
		if($searchByIndex)
		{
			return $this->timesheetDates[$value];
		}else
		{
			return array_search($value, $this->timesheetDates);
		}
	}

	/**
	 * Returns the member's name using it's id
	 *
	 * @param int $taskId
	 * @return string
	 */
	function getMembersName($memberId)
	{
		if(array_key_exists($memberId, $this->arrayOfUsersNames))
		{
			return $this->arrayOfUsersNames[$memberId];
		}else
		{
			return "Id $memberId not found";
		}
	}

	/**
	 * Returns the task name using the task's id
	 *
	 * @param int $taskId
	 * @return string
	 */
	function getTaskName($taskId)
	{
		if(array_key_exists($taskId, $this->arrayOfTaskNames))
		{
			return $this->arrayOfTaskNames[$taskId];
		}else
		{
			return "Task name not found";
		}
	}
	
	function getTaskTotal($userId, $taskId)
	{
		if(array_key_exists($userId, $this->timesheetTotals))
		{
			if(array_key_exists($taskId, $this->timesheetTotals[$userId]['task']))
			{
				return $this->timesheetTotals[$userId]['task'][$taskId];
			}else
			{
				return "Task ID not found";
			}
		}else
		{
			return "User ID not found";
		}
	}


	/**
	 * Returns the array holding all of the dates for this timesheet
	 *
	 * @return array:
	 */
	function getDateArray()
	{
		return $this->timesheetDates;
	}
	
	/**
	 * Returns the array holding the totals for the timesheets
	 * 
	 * @return array:
	 */
	function getTotalsArray()
	{
		return $this->timesheetTotals;
	}

	/**
	 * Returns the array holding all of the timesheet data
	 *
	 * @return array
	 */
	function toArray()
	{
		return $this->timesheetArray;
	}

}

?>