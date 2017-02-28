<?php

/**
 * Class EventDTO
 */
class EventDTO {

	const INPUT_DATE_FORMAT = 'n/j/Y';
	const OUTPUT_DATE_FORMAT = 'j/n/Y';

	/**
	 * @var string
	 */
	private $day;

	/**
	 * @var string
	 */
	private $date;

	/**
	 * @var string
	 */
	private $event;

	/**
	 * @var string;
	 */
	private $time;

	/**
	 * @var string
	 */
	private $team;

	/**
	 * @var boolean
	 */
	private $isJunior;

	/**
	 * @var boolean
	 */
	private $isAdultTraining;

	/**
	 * @var boolean
	 */
	private $isJuniorTraining;

	/**
	 * @var string
	 */
	private $note;

	private $isStartRacing;

	/**
	 * @see CSVParser->getData()
	 * @var array
	 */
	private $CSVRowData;

	/**
	 * @var array
	 */
	private $CSVColumnMapping = array(

		'0' => 'day',
		'1' => 'date',
		'2' => 'event',
		'3' => 'time',
		//'4' => 'endtime',
		'4' => 'team',
		'5' => 'isJunior',
		'6' => 'note',
	);

	public $colour;

	/**
	 * @var SailType SailType
	 */
	private $sailType;

	public $weekday;

	/**
	 * @var string
	 * @see RaceSeries::$nidMapping value
	 */
	private $typeName;

	/**
	 * @var int
	 * @see RaceSeries::$nidMapping
	 */
	private $typeInt;

	/**
	 * @var
	 */
	private $raceSeriesId;
	private $raceSeriesName;

	/**
	 * @var string
	 */
	private $endtime;

	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;

	/**
	 * @return string
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * @param string $endtime
	 */
	public function setEndtime( $endtime ) {
		$this->endtime = $endtime;
	}

	/**
	 * EventDTO constructor.
	 *
	 * @param $line
	 * @param array $CSVRowData
	 * @param SailType $sailType
	 * @param RaceSeries $raceSeries
	 * @param SafetyTeams $safetyTeams
	 */
	function __construct( $line, array $CSVRowData, SailType $sailType, RaceSeries $raceSeries, SafetyTeams $safetyTeams ) {

		if ( count( $CSVRowData ) != 7 ) {
			throw new Exception( 'Line ' . $line . ' does not have seven columns' );
		}

		if ( ! array( $CSVRowData ) || empty( $CSVRowData ) ) {
			throw new Exception( '$data is not an array or empty' );
		}

		$this->CSVRowData = $CSVRowData;
		$this->setFromCSV( $this->CSVRowData );

		$this->sailType    = $sailType;
		$this->safetyTeams = $safetyTeams;

		$event = $this->getEvent();

		$iType = $this->sailType->get( $this );

		if ( $iType == SailType::JUNIOR_TRAINING ) {
			$this->isJuniorTraining = true;
		}

		if ( $iType == SailType::ADULT_TRAINING ) {
			$this->isAdultTraining = true;
		}

		if ( preg_match( "/start Racing/i", $event ) ) {
			$this->isStartRacing = true;
		}

		if (
			$iType == SailType::CUP_RACE
		) {
			$this->raceSeriesId   = $raceSeries->getId( $this );
			$this->raceSeriesName = $raceSeries->getName( $this );
			$this->colour         = 'purple';
		}

		if (
			$iType == SailType::RACE_SERIES
		) {
			$this->raceSeriesId   = $raceSeries->getId( $this );
			$this->raceSeriesName = $raceSeries->getName( $this );
			$this->colour         = $raceSeries->getColor( $this );
		}

		if ( $iType == SailType::CRUISE ) {
			$this->colour = '#0099CC';
		}

		if (
			$iType == SailType::DAYLIGHTSAVING
		) {
			$this->colour = 'red';
		}

		$this->typeName = $this->sailType->getTypeName( $this );
		$this->typeInt  = $this->sailType->get( $this );

	}

	private function setFromCSV( $data ) {

		$columnsNumber = count( $this->CSVColumnMapping );

		// Dynamic getters and setters
		for ( $i = 0; $i <= $columnsNumber; $i ++ ) {
			$value = null;

			if ( ! empty( $data[ $i ] ) ) {

				$method = 'set' . ucfirst( $this->CSVColumnMapping[ $i ] );

				$value = trim( preg_replace( '/\s+/', ' ', $data[ $i ] ) );
				if ( ! empty( $value ) ) {
					try {
						$this->$method( $value );
					} catch ( Exception $e ) {
						throw new Exception ( $e->getMessage() );
					}
				}
			}
		}
	}

	public function isJuniorTraining() {
		return $this->isJuniorTraining;
	}

	public function isAdultTraining() {
		return $this->isAdultTraining;
	}

	public function isAdultStartRaceTraining() {
		return $this->isStartRacing;
	}

	public function getRaceSeriesId() {
		return $this->raceSeriesId;
	}

	public function getRaceSeriesName() {
		return $this->raceSeriesName;
	}

	/**
	 * @return string
	 */
	public function getNote() {
		return ! empty( $this->note ) ? $this->note : "&nbsp;";
	}

	/**
	 * @param string $note
	 */
	public function setNote( $note ) {
		$this->note = $note;
	}

	/**
	 * @return string
	 */
	public function getDate() {
		return ! empty( $this->date ) ? $this->date : "&nbsp;";
	}

	/**
	 * @todo better date validation
	 *
	 * @param string $date
	 */
	public function setDate( $date ) {

		if ( ! $d = DateTime::createFromFormat( self::INPUT_DATE_FORMAT, $date ) ) {
			throw new Exception( sprintf( 'Bad date format %s', $date ) );
		}

		$month = $d->format( 'n' );
		$day   = $d->format( 'j' );
		$year  = $d->format( 'Y' );

		if ( ! checkdate( $month, $day, $year ) ) {
			throw new Exception( 'Invalidate date ' . $date );
		}
		$this->date = $d->format( self::OUTPUT_DATE_FORMAT );

		$this->weekday = $d->format( 'D' );

	}

	/**
	 * @return string
	 */
	public function getDay() {
		return $this->day;
	}

	/**
	 * @todo validate date against value of $this->weekday. Date is not used for display.
	 *
	 * @param string $day
	 */
	public function setDay( $day ) {
		$this->day = $day;
	}

	/**
	 * @return string
	 *    A descriptive name such a Race Series 1 of 7
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param string $event
	 */
	public function setEvent( $event ) {
		$this->event = $event;
	}

	/**
	 * @return boolean
	 */
	public function isJunior() {
		return $this->isJunior;
	}

	/**
	 * @todo validation and type casting
	 *
	 * @param boolean $isJunior
	 */
	public function setIsJunior( $isJunior ) {
		if ( ! empty( $isJunior ) && $isJunior == 1 ) {
			$this->isJunior = $isJunior;
		}

	}

	/**
	 * @return string
	 */
	public function getTeam() {
		return ! empty( $this->team ) ? $this->team : "&nbsp;";
	}

	/**
	 * @todo validation
	 *
	 * @param string $team
	 */
	public function setTeam( $team ) {
		/*
		if(!$this->safetyTeams){
			throw new Exception('$this->safetyTeams is null');
			die();
		}*/
		//$this->safetyTeams->getAllSafetyTeams();
		$this->team = $team;
	}

	/**
	 * @return string
	 */
	public function getTime() {
		return ! empty( $this->time ) ? $this->time : "&nbsp;";
	}

	/**
	 * @todo validation
	 *
	 * @param string $time
	 */
	public function setTime( $time ) {
		$this->time = $time;
	}

	/**
	 * @return string
	 */
	public function getTypeName() {
		return $this->typeName;
	}

	/**
	 * @return int
	 */
	public function getType() {
		return $this->typeInt;
	}

	public function toArray() {
		return array(
			'date'            => $this->getDate(),
			'day'             => $this->getDay(),
			'start_time'      => preg_replace( '/([0-9]{1,2})([0-9]{2})$/', '$1:$2', $this->getTime() ),
			'end_time'        => preg_replace( '/([0-9]{1,2})([0-9]{2})$/', '$1:$2', $this->getEndTime() ),
			'event'           => $this->getEvent(),
			'type_id'         => $this->getType(),
			'type_name'       => $this->getTypeName(),
			'raceseries_id'   => $this->getRaceSeriesId(),
			'raceseries_name' => $this->getRaceSeriesName(),
			'team'            => $this->getTeam(),
			'note'            => $this->getNote(),
		);
	}
}