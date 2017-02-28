<?php

/**
 * Class SailTypeFilter
 *
 * This object is passed to the CSVParser. The values are retrieved from SailingEventForm
 *
 * @see classes/CSVParser.php
 * @see classes/SailingEventForm.php
 *
 * Example
 *
 * $arrResult = $csvParser->getData(
 *        new SailTypeFilter($form->getEventsSelected(), $form->getTeamsSelected())
 *   );
 */
class SailTypeFilter {

	/**
	 * @var array
	 */
	private $sailEventTypes;

	/**
	 * @var array
	 */
	private $teams;

	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;

	/**
	 * @var SailType;
	 */
	private $sailType;

	/**
	 * SailTypeFilter constructor.
	 *
	 * @param array $types
	 * @param array $teams
	 */
	public function __construct(
		SafetyTeams $safetyTeams, SailType $sailType, $sailEventTypes = array(),
		$teams = array
		()
	) {

		if ( ! is_array( $sailEventTypes ) ) {
			throw new Exception( sprintf( 'Invalid argument. If set, $sailEventTypes must be an array' ) );
		}
		if ( ! is_array( $teams ) ) {
			throw new Exception( sprintf( 'Invalid argument. If set, $teams must be an array' ) );
		}

		if ( ! $sailType->isValid( $sailEventTypes ) ) {
			throw new Exception( sprintf( '%s invalid sailing event type(s)', print_r( $sailEventTypes, 1 ) ) );
		}

		if ( ! $safetyTeams->isValid( $teams ) ) {
			throw new Exception( sprintf( '%s invalid safety team(s)', print_r( $teams, 1 ) ) );
		}

		$this->sailEventTypes = $sailEventTypes;
		$this->teams          = $teams;
	}

	/**
	 * @return array
	 */
	public function getTeamFilter() {
		return $this->teams;
	}

	/**
	 * @return array
	 */
	public function getTypeFilter() {
		return $this->sailEventTypes;
	}

}