<?php

/**
 * Class Day
 *
 * One day, may contain multiple events. We use this where we need to turn two excel rows for events
 * on one day into an object for one day.
 */
class Day {
	/**
	 * @var array of DTO
	 */
	private $day = array();

	/**
	 * @return array
	 */
	public function get() {
		return $this->day;
	}

	/**
	 * @param $dto DTO
	 */
	public function addEvent( $dto ) {
		$this->day[] = $dto;
	}

	/**
	 * @return int
	 */
	public function getNumEvents() {
		return count( $this->day );
	}

	/**
	 * @return array of DTO
	 */
	public function getEvents() {
		return $this->day;
	}

}