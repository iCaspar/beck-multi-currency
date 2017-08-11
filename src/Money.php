<?php

namespace MultiCurrency;

class Money implements Expression {
	/**
	 * @var int
	 */
	protected $amount;

	/**
	 * @var string
	 */
	protected $currency;

	/**
	 * @param int $amount
	 * @param string $currency
	 */
	public function __construct( int $amount, string $currency ) {
		$this->amount   = $amount;
		$this->currency = $currency;
	}

	/**
	 * @param int $amount
	 *
	 * @return Money
	 */
	public static function dollar( int $amount ): Money {
		return new Money( $amount, 'USD' );
	}

	/**
	 * @param int $amount
	 *
	 * @return Money
	 */
	public static function franc( int $amount ): Money {
		return new Money( $amount, 'CHF' );
	}

	/**
	 * @param Money $addend
	 *
	 * @return Expression
	 */
	public function plus( Expression $addend ): Expression {
		return new Sum( $this, $addend );
	}

	/**
	 * @param int $multiplier
	 *
	 * @return Expression
	 */
	public function times( int $multiplier ): Expression {
		return new Money( $this->amount * $multiplier, $this->currency );
	}

	/**
	 * @param Money $money
	 *
	 * @return bool
	 */
	public function equals( Money $money ): bool {
		return $this->amount == $money->amount
		       && $this->currency() == $money->currency;
	}

	/**
	 * @param Bank $bank
	 * @param string $to
	 *
	 * @return Money
	 */
	public function reduce( Bank $bank, string $to ): Money {
		$rate = $bank->rate( $this->currency, $to );

		return new Money( $this->amount / $rate, $to );
	}

	/**
	 * @return string
	 */
	public function currency(): string {
		return $this->currency;
	}

	/**
	 * @return int
	 */
	public function amount(): int {
		return $this->amount;
	}

}