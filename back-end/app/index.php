<?php

/**
 * Initial class
 */
class InitialClass{
	public function __construct()
	{
		$this->getSortedTravelWithSentences();
	}

	public function getSortedTravelWithSentences()
	{
		$travels = $this->getSortedTravel();
		var_dump($travels);
	}

	public function getSortedTravel()
	{
		$travels = $this->getTravelData();
		$departures = [];
		$arrivals = [];
		foreach($travels as $travel){
			array_push($departures, $travel['from']);
			array_push($arrivals, $travel['to']);
		}

		$allStops = array_merge($departures, $arrivals);
		$firstAndLastTravel = $this->determineFirstAndLastStop($travels, $this->getFirstAndLastStop($allStops));

		return $this->sortTravels($travels, $firstAndLastTravel);
	}

	/**
	 * Determine first and last stop from stops without reccurent stops
	 *
	 * @param array $allStops
	 * @return array
	 */
	public function getFirstAndLastStop(array $allStops): array
	{
		$valuesOfStops = array_count_values($allStops);
		$firstAndLast = [];
		foreach ($valuesOfStops as $key => $stop){
			if($stop === 1){
				array_push($firstAndLast, $key);
			}
		}

		return $firstAndLast;
	}

	/**
	 * Determine first stop from stops
	 *
	 * @param array $allStops
	 * @return array
	 */
	public function determineFirstAndLastStop(array $travels, array $firstAndLast): array
	{
		$firstAndLastTravels = [];
		foreach ($travels as $travel){
			foreach($firstAndLast as $stop){
				if($travel['from'] === $stop){
					$firstTravel = $travel;
				}
				if($travel['to'] === $stop){
					$lastTravel = $travel;
				}
			}
		}

		array_push($firstAndLastTravels, $firstTravel);
		array_push($firstAndLastTravels, $lastTravel);

		return $firstAndLastTravels;
	}

	/**
	 * Sorting all travels
	 *
	 * @param array $travels
	 * @param array $startStop
	 * @return array
	 */
	public function sortTravels(array $travels, array $firstAndLastTravel): array
	{
		$startStop = $firstAndLastTravel[0];
		$sortedTravel = $startStop;
		foreach ($travels as $travel){
			if($travel['from'] === $startStop['to']){
				array_push($sortedTravel, $travel);
				$startStop = $travel;
			}
		}
		array_push($sortedTravel, $firstAndLastTravel[1]);

		return $sortedTravel;
	}

	public function sentenceWithTrain()
	{
	}

	/**
	 * Mock travels
	 *
	 * @return array
	 */
	public function getTravelData(): array
	{
		return $unorderTravels = [
			"travel2" => [
				"from" => 'Barcelona',
				"to" => 'Gerona',
				"transport" => 'airbus',
				"seatNumber" => ""
			],
			"travel1" => [
				"from" => 'Madrid',
				"to" => 'Barcelona',
				"transport" => 'train',
				"seatNumber" => "45B"
			],
			"travel4" => [
				"from" => 'Stockholm',
				"to" => 'New York',
				"transport" => 'airbus',
				"seatNumber" => "7B",
				"gate" => "22B",
				"baggageDrop" => "auto",
			],
			"travel3" => [
				"from" => 'Gerona',
				"to" => 'Stockholm',
				"transport" => 'airbus',
				"seatNumber" => "3A",
				"flight" => "SK455",
				"gate" => "45B",
				"baggageDrop" => "344",
			]
		];
	}
}

$init = new InitialClass;
