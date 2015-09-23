<?php

namespace view;

class DateTimeView {

	/**
	 * Create and return a date string with the current date
	 * @return The current date in a html string
	 */
	public function show() {
		$timeString = date("l") . ', the ' . date("jS \of F Y") . ', The time is ' . date("H:i:s");
		return '<p>' . $timeString . '</p>';
	}
}