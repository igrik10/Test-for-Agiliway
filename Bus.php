<?php

interface IBus
{
	public function openDoor();
	public function closeDoor();
	public function letIn(int $quantity) :array;
	public function letOut(int $quantity) :array;
	public function rout(bool $reversed);
}

class Bus implements IBus {
	/**
	 * max person in bus
	 * @var int MAX_PASSENGERS
	 */
	const MAX_PASSENGERS = 20;

	/**
	 * max bus stop
	 * @var int MAX_STOPS
	 */
	const MAX_STOPS = 5;

	/**
	 * sleep time in seconds
	 * @var int SLEEP_TIME
	 */
	const SLEEP_TIME = 60;

	/** @var int $doorCondition */
	public $doorCondition = 0; //0 - close, 1 - open

	/** @var int $passangersIn */
	private $passengersIn = 0;

	/** @var int stop */
	private $stop = 0;

	/**
	 * open door
	 */
	public function openDoor() {
		$this->doorCondition = 1;
	}

	/**
	 * close door
	 */
	public function closeDoor() {
		$this->doorCondition = 0;
	}

	/**
	 * count passengers on the bus
	 *
	 * @param $passengersIn
	 */
	private function setPassengersIn(int $passengersIn) {
		$this->passengersIn = $passengersIn;
	}

	/**
	 * return count passengers on the bus
	 *
	 * @return int
	 */
	private function getPassengersIn(): int {
		return $this->passengersIn;
	}

	/**
	 * count bus stops
	 *
	 * @param $stop
	 */
	private function setStop(int $stop) {
		$this->stop = $stop;
	}

	/**
	 * return count bus stops
	 *
	 * @return int
	 */
	private function getStop(): int {
		return $this->stop;
	}

	/**
	 * let in passengers
	 *
	 * @param int $quantity
	 * @return array
	 */
	public function letIn(int $quantity): array {
		if ($quantity < 0) {
			return [
				'status'  => false,
				'message' => 'Quantity is not correct'
			];
		}

		$this->openDoor();

		$passengers = $this->getPassengersIn() + $quantity;

		if ($passengers <= self::MAX_PASSENGERS) {
			$this->setPassengersIn($passengers);

			$message = 'All passengers let in';
		} else {
			$canGet = $quantity - ($passengers - self::MAX_PASSENGERS);

			$this->setPassengersIn(self::MAX_PASSENGERS);

			$message = 'Bus is full. Only ' . $canGet . ' passengers let in';
		}

		$this->closeDoor();

		return [
			'status'  => true,
			'message' => $message
		];
	}

	/**
	 * let out passengers
	 *
	 * @param int $quantity
	 * @return array
	 */
	public function letOut(int $quantity): array {
		if ($quantity < 0) {
			return [
				'status'  => false,
				'message' => 'Quantity is not correct'
			];
		}

		$this->openDoor();

		$passengers = $this->getPassengersIn() - $quantity;

		if ($passengers >= 0) {
			$this->setPassengersIn($passengers);

			$message = 'All passengers let out';
		} else {
			$passengerIn = $this->getPassengersIn();
			$this->setPassengersIn(0);

			$message = 'Bus has only ' . $passengerIn . ' passengers, all passengers let out. Bus is empty now';
		}

		$this->closeDoor();

		return [
			'status'  => true,
			'message' => $message
		];
	}

	/**
	 * bus rout
	 *
	 * @param bool $reversed
	 * @return bool
	 * @throws \Exception
	 */
	public function rout(bool $reversed = false): bool {
		// when bus return back
		if ($reversed) {
			while ($this->getStop() > 0) {
				$this->setStop($this->getStop() - 1);
				$this->letOut(random_int(0, 20));
				$this->letIn(random_int(0, 20));
				sleep(self::SLEEP_TIME);
			}
		} else {
			while ($this->getStop() < self::MAX_STOPS) {
				$this->setStop($this->getStop() + 1);
				$this->letOut(random_int(0, 20));
				$this->letIn(random_int(0, 20));
				sleep(self::SLEEP_TIME);
			}
		}

		return true;
	}
}


$bus = new Bus();

$bus->rout();
$bus->rout(true);
