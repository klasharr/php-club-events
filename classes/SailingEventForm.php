<?php

include_once( 'SafetyTeams.php' );
include_once( 'mappers/SailType.php' );

class SailingEventForm {

	/**
	 * @var array
	 */
	private $selectedTeams = array();

	/**
	 * @var array
	 */
	private $selectedEventTypes = array();

	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;

	/**
	 * @var SailType
	 */
	private $sailType;

	/**
	 * @var string
	 */
	private $selectedWeekendTeam;

	/**
	 * @var string
	 */
	private $selectedThursdayTeam;


	/**
	 * SailingEventForm constructor.
	 *
	 * @param SafetyTeams $safetyTeams
	 * @param SailType $sailType
	 */
	public function __construct( SafetyTeams $safetyTeams, SailType $sailType ) {
		$this->safetyTeams = $safetyTeams;
		$this->sailType    = $sailType;
		$this->retrieveFormValues();
	}


	/**
	 * @todo some checking on the type similar to that of the safety teams
	 */
	private function retrieveFormValues() {

		$this->selectedEventTypes = isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ? $_GET['type'] :
			array();

		$w = isset( $_GET['w'] ) && $_GET['w'] != 'all' && in_array( $_GET['w'], $this->safetyTeams->getWeekendTeams() ) ? $_GET['w'] :
			null;
		$t = isset( $_GET['t'] ) && $_GET['t'] != 'all' && in_array( $_GET['t'], $this->safetyTeams->getThursdayTeams() ) ? $_GET['t'] :
			null;

		if ( $w ) {
			$this->selectedTeams[] = $this->selectedWeekendTeam = $w;
		}
		if ( $t ) {
			$this->selectedTeams[] = $this->selectedThursdayTeam = $t;
		}
	}

	/**
	 * @return array
	 */
	public function getSailEventTypesSelected() {
		return $this->selectedEventTypes;
	}

	/**
	 * @return array
	 */
	public function getTeamsSelected() {
		return $this->selectedTeams;
	}

	/**
	 * @return array
	 */
	public function getAllWeekendTeams() {
		return $this->safetyTeams->getWeekendTeams();
	}

	/**
	 * @return array
	 */
	public function getAllThursdayTeams() {
		return $this->safetyTeams->getThursdayTeams();
	}

	/**
	 * @return mixed string or false
	 */
	public function getSelectedThursdayTeam() {
		return $this->selectedThursdayTeam;
	}

	/**
	 * @return  mixed string or false
	 */
	public function getSelectedWeekendTeam() {
		return $this->selectedWeekendTeam;
	}

	/**
	 * @return string
	 */
	public function getFormHead() {
		return "
        <form  method='get'>
            <h1>Sailing type / event filter</h1>
            <p>Multiple selections are possible</p>
                <table>
                    <tr>\n";
	}

	/**
	 * @return string
	 */
	public function getThursdaySafetyTeamFormElement() {

		$out = "Thursday teams <br/>";
		$out .= "<select name = 't'>";
		$out .= "<option value = 'all'>Select</option>";

		foreach ( $this->getAllThursdayTeams() as $teamLetter ) {

			$out .= sprintf( "<option value = '%s' %s>%s</option>",
				$teamLetter,
				( $teamLetter == $this->getSelectedThursdayTeam() ) ? 'selected' : null,
				$teamLetter );
		}

		$out .= "</select>";

		return $out;
	}

	/**
	 * @return string
	 */
	public function getWeekendTeamFormElement() {

		$out = "Weekend teams <br/>";
		$out .= "<select name = 'w'>";
		$out .= "<option value = 'all'>Select</option>";

		foreach ( $this->getAllWeekendTeams() as $teamNumber ) {
			$out .= sprintf( "<option value = '%d' %s>%d</option>",
				$teamNumber,
				( $teamNumber == $this->getSelectedWeekendTeam() ) ? 'selected' : null,
				$teamNumber );
		}

		$out .= "</select>";

		return $out;
	}

	/**
	 * @return string
	 */
	public function getSailEventFormColumns() {

		$count  = 0;
		$out    = "<td>";
		$thirds = round( count( $this->sailType->getSailingTypes() ) / 3 );

		foreach ( $this->sailType->getSailingTypes() as $i => $sailType ) {

			$checked = in_array( $i, $this->getSailEventTypesSelected() ) ? 'checked' : null;

			$out .= sprintf( "<input type='checkbox' name='type[]' %s value='%d' /> %s<br/>\n", $checked, $i, $sailType );

			if ( $count == $thirds ) {
				$thirds += $thirds;
				$out .= "</td>\n<td>";
			}

			$count ++;

		}

		$out .= "<input type='checkbox' name='type[]' value='999' />Unspecified<br/>";
		$out .= "</td>\n";

		return $out;
	}
}