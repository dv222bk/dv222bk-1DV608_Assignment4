<?php

namespace view;

class DateTimeView {

	public function show() {

		$timeString = date("l") . ', the ' . date("jS \of F Y") . ', The time is ' . date("H:i:s");

		return '<p>' . $timeString . '</p>';
	}
}