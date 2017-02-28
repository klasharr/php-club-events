<?php

class FullEventsTable {

	public static $teamcount = 0;

	public static function getOpenTableTag() {
		return '<table id="results" cellspacing="0" cellpadding="0">';
	}

	public static function getHeader() {

		return '<thead>
            <th>Day</th>
            <th>Date</th>


            <th>Event</th>
            <th>Time</th>
            <th>Team</th>
            <th>Junior Event</th>
            <th>Time</th>
        </thead>';
	}


	public static function getRow( day $day ) {

		$out = '';

		/** @var $dto EventDTO */
		foreach ( $day->getEvents() as $dto ) {

			$event = $dto->getEvent();
			if ( empty( $event ) ) {
				continue;
			}

			if ( in_array( $dto->getType(), array( SailType::CUP_RACE, SailType::RACE_SERIES ) ) ) {
				self::$teamcount ++;
			}

			$style    = '';
			$class    = '';
			$rowStyle = '';
			if ( $dto->isAdultStartRaceTraining() ) {
				$style = "style='background-color:#FFE87C';";
			} elseif ( $dto->isAdultTraining() ) {
				$style = "style='background-color:#FFE87C';";
			}

			$firstColStyle = '';
			if (
				$dto->getType() == SailType::DAYLIGHTSAVING ||
				$dto->getType() == SailType::BANK_HOLIDAY
			) {
				$firstColStyle = 'style="background-color: #C0C0C0";';
			}


			$out .= sprintf( '<tr %s class="%s">', $rowStyle, $class );
			$out .= sprintf( '<td %s>%s</td>', $firstColStyle, $dto->weekday );
			$out .= sprintf( '<td>%s</td>', $dto->getDate() );

			if ( $dto->colour ) {
				$style = "style='color:" . $dto->colour . "';";
			}

			$out .= sprintf(
				'<td class="event" %s>%s</td>', $style, self::getAdultEvent( $dto ) . ' ' . self::getNote( $dto ) );

			$out .= sprintf( '<td>%s</td>', self::getAdultTime( $dto ) );
			$out .= sprintf( '<td>%s</td>', $dto->getTeam() );

			if ( $dto->isJuniorTraining() ) {
				$class = 'junior-training';
			}

			$out .= sprintf( '<td class="event %s">%s</td>', $class, self::getJuniorEvent( $dto ) );
			$out .= sprintf( '<td class="%s">%s</td>', $class, self::getJuniorTime( $dto ) );
			$out .= '</tr>';
		}

		return $out;
	}

	/**
	 * @param $dto EventDTO
	 */
	private static function getTableRowClass( EventDTO $dto ) {

	}


	/**
	 * @param $dto EventDTO
	 */
	private static function getNote( EventDTO $dto ) {
		return sprintf( '<span class="note">%s</span>', $dto->getNote() );
	}

	/**
	 * @param $dto EventDTO
	 *
	 * @return string
	 */
	private static function getAdultEvent( EventDTO $dto ) {
		if ( ! $dto->isJunior() ) {
			return $dto->getEvent();
		}

		return '&nbsp;';
	}

	/**
	 * @param $dto EventDTO
	 *
	 * @return string
	 */
	private static function getJuniorEvent( EventDTO $dto ) {
		if ( $dto->isJunior() ) {
			return $dto->getEvent();
		}

		return '&nbsp;';
	}

	/**
	 * @param $dto EventDTO
	 *
	 * @return string
	 */
	private static function getAdultTime( EventDTO $dto ) {
		if ( ! $dto->isJunior() ) {
			return $dto->getTime();
		}

		return '&nbsp;';
	}

	/**
	 * @param $dto EventDTO
	 *
	 * @return string
	 */
	private static function getJuniorTime( EventDTO $dto ) {
		if ( $dto->isJunior() ) {
			return $dto->getTime();
		}

		return '&nbsp;';
	}

	public static function getClosingTag() {
		return '</table>';
	}


	public static function getCSS() {

		return '<style type="text/css">

table#results {
    width:95%;
    border-top:1px solid #99CCFF;
    border-right:1px solid #99CCFF;
    margin:1em auto;
    font-family: sans-serif;
    }

table#results td {
border-bottom:1px solid #99CCFF;
border-left:1px solid #99CCFF;
text-align:center;
padding: 0.3em;
}


table#results    thead th, .day {
    background:#D7EBFF;
    text-align:center;
font-weight: bold;
    }

table#results tr.training {
  background-color: yellow;
}

table#results tr.race-training {
  background-color: #FFCC33;
}

table#results .cup {
color: purple
}

table#results .junior-training{
background-color: #66CCFF;
}

.training-box{
    padding: 0 5em;
 background-color: yellow;
}
.training-box{
    padding: 0 3em;
 background-color: yellow;
border: 1px solid black;
}
.training-box2{
    padding: 0 3em;
 background-color: #FFCC33;
border: 1px solid black;
}
.training-box3{
    padding: 0 3em;
 background-color: #66CCFF;
border: 1px solid black;
}

table#results td.left{
text-align: left;
}

table#results td.event{
text-align: left;
}

table#results span.note{
color: red;
}
</style>';

	}

}