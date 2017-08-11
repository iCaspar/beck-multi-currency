<?php

namespace MultiCurrency;

class Bank {
	private $rates = [];

	/**
	 * @param Expression $source
	 * @param string $to
	 *
	 * @return Money
	 */
	public function reduce( Expression $source, string $to ): Money {
		return $source->reduce( $this, $to );
	}

	/**
	 * @param string $from
	 * @param string $to
	 * @param int $rate
	 *
	 * @return void
	 */
	public function addRate( string $from, string $to, int $rate ): void {
		$this->rates[ $from . $to ] = $rate;
	}

	/**
	 * @param string $from
	 * @param string $to
	 *
	 * @return int
	 */
	public function rate( string $from, string $to ): int {
		if ( $from == $to ) {
			return 1;
		}

		return (int) $this->rates[ $from . $to ];
	}
}