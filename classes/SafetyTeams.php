<?php

class SafetyTeams {

	private $weekend = array( 1, 2, 3, 4, 5, 6, 7, 8, 9 );

	private $thursday = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J' );

	/**
	 * @return array
	 */
	public function getWeekendTeams() {
		return $this->weekend;
	}

	/**
	 * @return array
	 */
	public function getThursdayTeams() {
		return $this->thursday;
	}

	/**
	 * @return array
	 */
	public function getAllSafetyTeams() {
		return array_merge( $this->weekend, $this->thursday );
	}

	public function isValid( $idOrIDArray ) {

		$allSafetyTeams = array_merge( $this->weekend, $this->thursday );

		if ( is_array( $idOrIDArray ) && ! empty( $idOrIDArray ) ) {
			foreach ( $idOrIDArray as $team ) {
				if ( ! in_array( $team, $allSafetyTeams ) ) {
					return false;
				}
			}
		} elseif ( ! empty( $idOrIDArray ) && ! in_array( $idOrIDArray, $allSafetyTeams ) ) {
			return false;
		}

		return true;
	}

}