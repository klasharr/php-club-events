<?php

define( 'APP_DIR', dirname( __FILE__ ) );

include_once( 'includes/header.inc' );

define( 'DATES_CSV', 'dates.csv' );

try {

	$form = new SailingEventForm( new safetyTeams, new SailType );

	$safetyTeams = new SafetyTeams();
	$sailType    = new SailType();

	$sailFilter = new SailTypeFilter( $safetyTeams, $sailType, $form->getSailEventTypesSelected(),
		$form->getTeamsSelected()
	);

	$csvParser  = new CSVParser( DATES_CSV, $sailType, new RaceSeries, $safetyTeams );
	$eventsData = $csvParser->getData( $sailFilter );

} catch ( Exception $e ) {
	echo '<strong>Error: ' . $e->getMessage() . ' File:' . $e->getFile() . ', Line: ' . $e->getLine() . '</strong><br/>';
	print_r( $e->getTrace() );
	exit( 1 );
}

echo EventsPage::getPageHead();
echo EventsPage::displayErrors( $eventsData['errors'] );
echo EventsPage::getForm( $form );
echo FullEventsTable::getCSS();

if ( $eventsData['data'] ) {
	echo FullEventsTable::getOpenTableTag();
	echo FullEventsTable::getHeader();

	foreach ( $eventsData['data'] as $date => $DTOArray ) {
		$day = new day();
		foreach ( $DTOArray as $DTO ) {
			$day->addEvent( $DTO );
		}
		echo FullEventsTable::getRow( $day );
	}

	echo FullEventsTable::getClosingTag();
} else {
	echo EventsPage::displayNoResultsMessage();
}
echo EventsPage::getPageFooter();