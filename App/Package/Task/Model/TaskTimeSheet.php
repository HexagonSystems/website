<?php

namespace Task;

class TaskTimeSheet
{
	private $timesheetArray = array();
	private $timesheetDates = array();
	private $arrayOfUsersNames = array();
	private $arrayOfTaskNames = array();

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
	
	function getMembersName($memberId)
	{
		if(array_key_exists($memberId, $this->arrayOfUsersNames))
		{
			return $this->arrayOfUsersNames[$memberId];
		}else
		{
			var_dump($this->arrayOfUsersNames);
		}
	}
	
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
	
	
	
	function getDateArray()
	{
		return $this->timesheetDates;
	}

	function toArray()
	{
		return $this->timesheetArray;
	}

}

?>