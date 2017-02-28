<?php


class RaceSeries {

	private $mapping = array(

		"260" => "Autumn Series",
		"263" => "Cup race",
		"261" => "Fun Series",
		"255" => "Spring Evening Series",
		"256" => "Spring Series",
		"257" => "Summer Evening Series",
		"258" => "Summer Series",
		"259" => "Twilight Series",
		"262" => "Winter Fun Series",
	);

	private $seriesColors = array(
		'Spring Series'         => 'green',
		'Spring Evening Series' => 'red',
		'Summer Series'         => '#3333CC',
		'Summer Evening Series' => 'orange',
		'Twilight Series'       => 'Brown',
		'Autumn Series'         => '#339999',
	);

	/**
	 * Get id of race series. Note every year this should be different since
	 * with a new year, we recreate the series names so that we have e.g.
	 * Autumn series 2015
	 *
	 * @param $dto EventDTO
	 *
	 * @return int node id
	 */
	public function getId( EventDTO $dto ) {

		$event = $dto->getEvent();
		if ( empty( $event ) ) {
			return null;
		}

		foreach ( $this->mapping as $id => $name ) {

			$pattern = "/$name/i";
			if ( preg_match( $pattern, $event ) ) {
				return $id;
			}
		}
	}

	public function getName( EventDTO $dto ) {
		$id = $this->getId( $dto );
		if ( ! empty( $id ) ) {
			return $this->mapping[ $id ];
		}
	}


	public function getColor( EventDTO $dto ) {
		foreach ( $this->seriesColors as $series => $colour ) {

			$pattern = "/" . $series . "/i";

			if ( preg_match( $pattern, $dto->getEvent() ) ) {
				return $colour;
			}
		}
	}
}