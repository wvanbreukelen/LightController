<?php

/**
 * A time frame can be used to check if a specific time value is reached in a kind of condition
 */
class TimeFrame
{
	/**
	 * Check if the times has been reached in combination with an offset
	 * @param  string The time to check on
	 * @param  integer The offset you want to use
	 * @return boolean Is the time reached?
	 */
	public function isTimeReached($time, $offset = 3)
	{
		$date_a = new DateTime(date('H:i'));
		$date_b = new DateTime($time);

		$interval = date_diff($date_a,$date_b);

		$format = explode(':', $interval->format('%h:%i'));

		return ($offset >= $format[1]) ? true : false;
	}
}