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
		$sentences = [];
		foreach($travels as $travel){
			if($travel["transport"] === "train"){
				echo $this->sentenceForTrain($travel) . "<br>";
			}
			if($travel["transport"] === "airbus"){
				echo $this->sentenceForAirbus($travel) . "<br>";
			}
			if($travel["transport"] === "bus"){
				echo $this->sentenceForBus($travel) . "<br>";
			}
		}
	}

	/**
	 * Unsorted travel data sorted
	 *
	 * @return array
	 */
	public function getSortedTravel(): array
	{
		$travels = $this->getTravelData();
		$departures = [];
		$arrivals = [];
		foreach($travels as $travel){
			array_push($departures, $travel["from"]);
			array_push($arrivals, $travel["to"]);
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
				if($travel["from"] === $stop){
					$firstTravel = $travel;
				}
				if($travel["to"] === $stop){
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
		$sortedTravel = [];
		array_push($sortedTravel, $startStop);
		
		foreach ($travels as $travel){
			if($travel["from"] === $startStop["to"]){
				array_push($sortedTravel, $travel);
				$startStop = $travel;
			}
		}
		array_push($sortedTravel, $firstAndLastTravel[1]);

		return $sortedTravel;
	}

	/**
	 * Creating sentences from travel data
	 *
	 * @param array $travel
	 * @return string
	 */
	public function sentenceForTrain(array $travel): string
	{
		return  "Take train " .  $travel['number'] . " from " . $travel['from'] . " to " . $travel['to']. " Seat number: " . $this->getSeatInfo($travel); 
	}

	public function sentenceForAirbus(array $travel): string
	{
		return  "From " .  $travel['from'] . " take flight " . $travel['flight'] . " to " . $travel['to'] . " Gate " . $travel['gate'] .  ", seat 	" . $travel["seatNumber"] . ". " . $this->getBaggageInfo($travel); 
	}

	public function sentenceForBus(array $travel): string
	{
		return  "Take the airport bus from " .  $travel['from'] . " to " . $travel['to'] . " to " . $travel['to'] . $this->getSeatInfo($travel);
	}

	/**
	 * Checking baggage drop for baggage info
	 *
	 * @param array $travel
	 * @return string
	 */
	public function getBaggageInfo(array $travel): string
	{
		if(!empty($travel['baggageDrop'])){
			return "Baggage drop at ticket counter " . $travel['baggageDrop'] .  ".";
		} else {
			return "Baggage will we automatically transferred from your last leg.";
		}
	}

	/**
	 * Checking seat for seat info
	 *
	 * @param array $travel
	 * @return string
	 */
	public function getSeatInfo(array $travel): string
	{
		if(!empty($travel['seatNumber'])){
			return "Seat Number " . $travel['seatNumber'];
		} else {
			return "No seat assignment.";
		}
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
				"from" => "Barcelona",
				"to" => "Gerona Airport",
				"transport" => "bus",
				"seatNumber" => ""
			],
			"travel1" => [
				"from" => "Madrid",
				"to" => "Barcelona",
				"number" => "78A",
				"transport" => "train",
				"seatNumber" => "45B"
			],
			"travel4" => [
				"from" => "Stockholm",
				"to" => "New York",
				"transport" => "airbus",
				"seatNumber" => "7B",
				"gate" => "22B",
				"baggageDrop" => "",
			],
			"travel3" => [
				"from" => "Gerona Airport",
				"to" => "Stockholm",
				"transport" => "airbus",
				"seatNumber" => "3A",
				"flight" => "SK455",
				"gate" => "45B",
				"baggageDrop" => "344",
			]
		];
	}
}

$init = new InitialClass;
