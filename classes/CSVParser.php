<?php

class CSVParser {

	/**
	 * Full system path to CSV file
	 * @var string
	 */
	private $CSVPath;

	/**
	 * @var string
	 */
	private $dayFilter;

	/**
	 * @var string
	 */
	private $sailEventFilter;

	/**
	 * @var SailType
	 */
	private $sailType;

	/**
	 * @var RaceSeries
	 */
	private $raceSeries;


	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;


	/**
	 * CSVParser constructor.
	 *
	 * @param $CSVPath
	 * @param SailType $sailType
	 * @param RaceSeries $raceSeries
	 * @param SafetyTeams $safetyTeams
	 *
	 * @throws Exception
	 */
	public function __construct( $CSVPath, SailType $sailType, RaceSeries $raceSeries, SafetyTeams $safetyTeams ) {
		if ( ! file_exists( $CSVPath ) ) {
			throw new Exception( sprintf( 'File at path %s does not exist', $CSVPath ) );
		}
		$this->csvPath     = $CSVPath;
		$this->sailType    = $sailType;
		$this->raceSeries  = $raceSeries;
		$this->safetyTeams = $safetyTeams;
	}

	/**
	 * @todo add a check to $days for valid days
	 *
	 * @param $days array
	 */
	public function setDayFilter( array $days = array() ) {
		$this->dayFilter = $days;
	}

	/**
	 * @todo check for valid sail events
	 * @see
	 *
	 * @param $sailEventTypes array
	 *
	 * public function setSailEventFilter(array $sailEventTypes = array()){
	 *
	 * if(!$this->sailType->isValidSailingEventID($sailEventTypes)){
	 * throw new Exception('%s is not a valid ID or array of SailType IDs', print_r($sailEventTypes,1));
	 * }
	 * $this->sailEventFilter = $sailEventTypes;
	 * } */


	/**
	 * @param SailTypeFilter $sailTypeFilter
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function getData( SailTypeFilter $sailTypeFilter ) {


		$tmp = array();

		$out = array(
			'data'   => array(),
			'errors' => array(),
		);

		$handle = fopen( $this->csvPath, "r" );

		if ( ! $handle ) {
			throw new Exception( 'Could not get handle for file: ' . $this->CSVPath );
		}

		$line = 0;
		while ( ( $data = fgetcsv( $handle ) ) !== false ) {
			try {
				$tmp[] = $o = new EventDTO( $line, $data, $this->sailType, $this->raceSeries, $this->safetyTeams );
			} catch ( Exception $e ) {
				$out['errors'][] = 'CSV Error line: ' . $line . ' ' . $e->getMessage();
			}
			$line ++;
		}
		fclose( $handle );

		$teamsToFilterOn      = $sailTypeFilter->getTeamFilter();
		$sailEventsToFilterOn = $sailTypeFilter->getTypeFilter();

		/** @var $dto EventDTO */
		foreach ( $tmp as $i => $dto ) {

			if ( ! empty( $teamsToFilterOn ) && ! in_array( $dto->getTeam(), $teamsToFilterOn ) ) {
				continue;
			}

			if ( empty( $sailEventsToFilterOn ) ) {
				$out['data'][ $dto->getDate() ][] = $dto;
			} elseif ( in_array( $dto->getType(), $sailEventsToFilterOn ) ) {
				$out['data'][ $dto->getDate() ][] = $dto;
			}
		}

		return $out;
	}
}