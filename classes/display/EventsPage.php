<?php


class EventsPage {
	public static function getPageHead() {

		return
			'<html>
    <head>
        <style type="text/css">
            body{
                font-family: sans-serif;
            }
            div.errors{
                color: red;
            }
            div.no-results{
                width: 500px;
                margin: auto;
            }
            td{
                vertical-align: top;
                padding: 0.2em;
            }
            th{
                padding: 0.5em;
                font-weight: bold;
            }
        </style>
    </head>
    <body>';

	}

	public static function getPageFooter() {
		return '</body></html>';
	}

	public static function getForm( SailingEventForm $form ) {

		$out = $form->getFormHead();
		$out .= $form->getSailEventFormColumns();
		$out .= '<td>';
		$out .= $form->getWeekendTeamFormElement();
		$out .= '</td>';
		$out .= '<td>';
		$out .= $form->getThursdaySafetyTeamFormElement();
		$out .= '</td>';
		$out .= '</tr>';
		$out .= '</table>';
		$out .= "<input type='submit' name='formSubmit' value='Submit' />";
		$out .= '</form>';

		return $out;

	}

	/**
	 * @param $errors array
	 */
	public static function displayErrors( array $errors ) {
		if ( empty( $errors ) ) {
			return;
		}

		$out = '<div class="errors"><h3>Errors</h3>';
		foreach ( $errors as $error ) {
			$out .= sprintf( '%s <br/>', $error );
		}
		$out .= '</div>';

		return $out;
	}

	public static function displayNoResultsMessage() {
		echo '<div class="no-results"><h2>No results</h2><p>Please try selecting different values in the 
        form</p></div>';
	}
}