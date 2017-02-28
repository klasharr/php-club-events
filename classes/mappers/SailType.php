<?php

class SailType {

	const
		ADULT_TRAINING = 13,
		CRUISE = 4,
		CUP_RACE = 2,
		FUN_SERIES = 11,
		JUNIOR_TRAINING = 14,
		OPEN_EVENT = 10,
		OTHER = 17,
		RACE_SERIES = 1,
		SPECIAL_RACE = 5,
		TGIF = 3,
		ADULT_SOCIAL_SAILING = 18,
		CLUBHOUSE_MEETING = 19,
		DAYLIGHTSAVING = 20,
		BANK_HOLIDAY = 21,
		JUNIOR_WEEK = 22;

	private $typeMapping = array(

		self::RACE_SERIES    => "Race series",
		self::CUP_RACE       => "Cup race",
		self::TGIF           => "TGIF",
		self::CRUISE         => "Cruise",
		self::SPECIAL_RACE   => "Special race",
		self::ADULT_TRAINING => "Adult Training",

		self::FUN_SERIES      => "Fun Series",
		self::JUNIOR_TRAINING => "Junior Training",
		self::OPEN_EVENT      => "Open event",
		self::OTHER           => "Other",

		self::ADULT_SOCIAL_SAILING => "Adult Social Sailing",
		self::CLUBHOUSE_MEETING    => "Clubhouse meeting",
		self::DAYLIGHTSAVING       => 'Daylight saving change',
		self::BANK_HOLIDAY         => 'Bank holiday',
		self::JUNIOR_WEEK          => 'Junior week start/end',
	);

	private $cupRaces = array(
		'The Opener',
		'1974 Cup',
		'Commodores Cup',
		'James Day Cup',
		'Owerdale Cup',
		'Coronation Cup',
		'Elizabeth Cup',
		'Wessex Shield',
		'Vikki Thornhill Cup',
		'Chellingworth Cup',
		'Fleming Trophy',
		'RNLI Pennant',
		'Rees Cup',
		'Knoll Cup',
		'Bent Cup',
		'Macdona',
	);

	private $meetings = array(
		'AGM',
		'Active Sailors Meeting',
		'Guest speaker talk',
		'RYA Race Procedures Course',
		'Beach Clean and boat move',
		'First Aid Course',
		'Boat move and Beach Clean',
		'Winter Berthing starts',
	);

	/*
	 * Get the name for this type of sailing event.
	 *
	 * @param $dto EventDTO
	 * @return string
	 */
	public function get( EventDTO $dto ) {
		$event = trim( $dto->getEvent() );
		if ( empty( $event ) ) {
			throw new Exception( 'Event is empty.' );
		}

		if ( preg_match( "/Junior Training/i", $dto->getEvent() ) && $dto->isJunior()
		) {
			return self::JUNIOR_TRAINING;
		}

		if ( preg_match( "/Bank holiday/i", $dto->getEvent() )
		) {
			return self::BANK_HOLIDAY;
		}

		if ( preg_match( "/Junior week/i", $dto->getEvent() )
		) {
			return self::JUNIOR_WEEK;
		}

		if ( preg_match( "/BRITISH SUMMER TIME/i", $dto->getEvent() )
		) {
			return self::DAYLIGHTSAVING;
		}

		if ( preg_match( "/Adult Training/i", $dto->getEvent() ) ||
		     preg_match( "/Powerboat Practice/i", $dto->getEvent() ) ||
		     preg_match( "/start Racing/i", $dto->getEvent() )
		) {
			return self::ADULT_TRAINING;
		}

		if ( preg_match( "/Winter Fun Series/i", $dto->getEvent() ) ) {
			return self::FUN_SERIES;
		}

		if ( preg_match( "/Series/i", $dto->getEvent() ) ) {
			return self::RACE_SERIES;
		}

		if ( preg_match( "/cruise/i", $dto->getEvent() ) ) {
			return self::CRUISE;
		}

		if ( preg_match( "/TGIF/i", $dto->getEvent() ) ) {
			return self::TGIF;
		}

		if ( preg_match( "/regatta/i", $dto->getEvent() ) ) {
			return self::OPEN_EVENT;
		}

		if ( $this->isClubHouseMeeting( $dto ) ) {
			return self::CLUBHOUSE_MEETING;
		}

		if ( $this->isCupRace( $dto ) ) {
			return self::CUP_RACE;
		}

		if ( preg_match( "/Adult Social Sailing/i", $dto->getEvent() ) ) {
			return self::FUN_SERIES;
		}

		throw new Exception( $dto->getEvent() . ' is not a recognised event' );
	}

	/**
	 * @param EventDTO $dto
	 *
	 * @return boolean
	 */
	private function isClubHouseMeeting( EventDTO $dto ) {
		foreach ( $this->meetings as $meeting ) {

			$pattern = "/" . $meeting . "/i";

			if ( preg_match( $pattern, $dto->getEvent() ) ) {
				return true;
			}
		}
	}

	/**
	 * @param EventDTO $dto
	 *
	 * @return int
	 */
	private function isCupRace( EventDTO $dto ) {

		foreach ( $this->cupRaces as $race ) {

			$pattern = "/" . $race . "/i";

			if ( preg_match( $pattern, $dto->getEvent() ) ) {
				return self::CUP_RACE;
			}
		}
	}

	/**
	 * Get the sailing event type name.
	 *
	 * @param EventDTO $dto
	 *
	 * @return string
	 */
	public function getTypeName( EventDTO $dto ) {
		$type = $this->get( $dto );

		return $this->typeMapping[ $type ];
	}

	/**
	 * @return array
	 */
	public function getSailingTypes() {
		return $this->typeMapping;
	}

	/**
	 * @todo tidy up
	 *
	 * @param $idOrIDArray mixed int/array
	 */
	public function isValid( $idOrIDArray ) {

		if ( is_array( $idOrIDArray ) && ! empty( $idOrIDArray ) ) {
			foreach ( $idOrIDArray as $id ) {
				if ( ! array_key_exists( (int) $id, $this->typeMapping ) ) {
					return false;
				}
			}
		} elseif ( ! empty( $idOrIDArray ) && ! array_key_exists( (int) $id, $this->typeMapping ) ) {
			return false;
		}

		return true;
	}
}