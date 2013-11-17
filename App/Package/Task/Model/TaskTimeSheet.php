<?php

namespace Task;

class TaskTimeSheet
{
	private $timesheetArray = array();
	private $timesheetDates = array();
	private $arrayOfUsersNames = array();
	private $arrayOfTaskNames = array();
	private $timesheetTotals = array();

	private $timesheetFinal = array();

	private $configTimeFrame = -1;
	private $configTimeInterval = -1;
	private $configTimeRange = -1;

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

		/* LOOP THROUGH USER ARRAY */
		foreach($this->timesheetArray as $user => $userTimesheet)
		{
			if(!array_key_exists($user, $this->timesheetFinal))
			{
				$this->timesheetFinal[$user] =  array(); // Create the array if not already created
			}
			/* LOOP THROUGH TASK ARRAY INSIDE USER */
			foreach($userTimesheet as $task => $taskTimesheet)
			{
				if(!array_key_exists($task, $this->timesheetFinal[$user]))
				{
					$this->timesheetFinal[$user][$task] =  array(); // Create the array if not already created
				}
				/* LOOP THROUGH DATE USER INSIDE TASK */
				foreach($taskTimesheet as $date => $hours)
				{
					$dateFromDB = new \DateTime($date);
					$newIndex = -1; // Set the newIndex to -1 as default for error checking
					for($counter = 0; $counter < count($this->timesheetDates); $counter++)
					{

						$tempDateStart = new \DateTime($this->timesheetDates[$counter]); // Start date for the current date period
						$tempDateEnd = new \DateTime($this->timesheetDates[$counter]); // End date for the current date period
						$tempDateEnd->modify("+1 " . $this->getTimeFrameInterval()); // Add the interval to the start date to get the proper end date
						/* CHECK IF THE dateFromDB FITS INTO THE TIME OF THE CURRENT DATE PERIOD */
						if($dateFromDB >= $tempDateStart && $dateFromDB < $tempDateEnd)
						{
							$newIndex = $counter;
						}
					}
					/* CHECK FOR ERRORS */
					if($newIndex < 0)
					{
						echo "Error finding place for date $date<br/>";
					}

					if(!array_key_exists($this->timesheetDates[$newIndex], $this->timesheetFinal[$user][$task]))
					{
						$this->timesheetFinal[$user][$task][$this->timesheetDates[$newIndex]] =  0; // Create the array if not already created
					}
					$this->timesheetFinal[$user][$task][$this->timesheetDates[$newIndex]] += $hours;
				}

				/* FILL IN MISSING HOURS */
				foreach($this->timesheetDates as $date)
				{
					foreach($this->timesheetFinal as $user => $userTimesheet)
					{
						foreach($userTimesheet as $task => $taskTimesheet)
						{
							if(!array_key_exists($date, $taskTimesheet))
							{
								$this->timesheetFinal[$user][$task][$date] = "-";
							}
						}
					}
				}

				/* SORT DATES */
				ksort($this->timesheetFinal[$user][$task]);
			}
		}


		/*
		 * For testing purposes
		var_dump($this->timesheetDates);
		echo "<br/>";
		echo "<br/>";
		var_dump($this->timesheetArray);*/

		$this->timesheetArray = $this->timesheetFinal;

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
	function setDateRange($first, $last, $step = false, $format = 'Y-m-d' ) {
		if(!$step)
		{
			$step = "+1 " . $this->configTimeInterval;
		}
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
	 * Sets the timeframe (what to search for)
	 *
	 * @param String $timeframe
	 */
	function setTimeFrame($timeframe)
	{
		switch($timeframe)
		{
			case 'year': $this->configTimeRange = "+1 year"; $this->configTimeFrame = 'year'; $this->configTimeInterval = 'month';
			break;
			case 'month': $this->configTimeRange = "+1 month"; $this->configTimeFrame = 'month'; $this->configTimeInterval = 'week';
			break;
			case 'week':
			default: $this->configTimeRange = "+6 days"; $this->configTimeFrame = 'day'; $this->configTimeInterval = 'day';
		}
	}

	/**
	 * Returns the timeframe.
	 *
	 * Calls the setTimeFrame() method if the timeframe has not been set before.
	 *
	 * @return number
	 */
	function getTimeFrame()
	{
		if($this->configTimeFrame == -1)
		{
			$this->setTimeFrame('empty');
		}

		return $this->configTimeFrame;
	}

	/**
	 * Returns the timeframe interval.
	 *
	 * Calls the setTimeFrame() method if the timeframe has not been set before.
	 *
	 * @return number
	 */
	function getTimeFrameInterval()
	{
		if($this->configTimeFrame == -1)
		{
			$this->setTimeFrame('empty');
		}

		return $this->configTimeInterval;
	}

	/**
	 * Returns the format of the date.
	 *
	 * Created to create a universal formatting method
	 *
	 * @return String
	 */
	function getTimeFormat($override = false)
	{
		$switchOn = $this->configTimeFrame;

		if($override)
		{
			$switchOn = $override;
		}
		switch($switchOn)
		{
			case 'year':
			case 'month': return 'Y-m-01';
			break;
			case 'week':
			default: return 'Y-m-d';
		}
	}

	/**
	 * Return the formatted startDate.
	 *
	 * @param String $startDate
	 * @return string
	 */
	function getStartDate($startDate)
	{
		if($startDate)
		{
			$startDate = strtotime($startDate);
			return date($this->getTimeFormat(), $startDate);
		}else
		{
			date_default_timezone_set('Australia/Melbourne');
			$endDate = date(time());

			$startDate = strtotime("-1 ".$this->getTimeFrame(), $endDate);
			$startDate = date($this->getTimeFormat(), $startDate);
		}
	}

	/**
	 * Returns the end date
	 *
	 * @param Date $startDate
	 */
	function getEndDate($startDate)
	{
		$tempDate = strtotime($this->configTimeRange, strtotime("$startDate"));
		switch($this->configTimeFrame)
		{
			case 'year': $tempDate = strtotime("-1 day", "$tempDate");
			break;
		}

		return date($this->getTimeFormat('week'), $tempDate);
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