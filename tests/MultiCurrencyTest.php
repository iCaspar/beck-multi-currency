<?php
/**
 * Description
 */

namespace MultiCurrency;

use DeepCopyTest\D;
use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class MultiCurrencyTest extends TestCase {
	public function testSimpleAddition() {
		$five    = Money::dollar( 5 );
		$sum     = $five->plus( $five );
		$bank    = new Bank();
		$reduced = $bank->reduce( $sum, 'USD' );

		$this->assertEquals( Money::dollar( 10 ), $reduced );
	}

	public function testMixedAddition() {
		$fiveBucks = Money::dollar( 5 );
		$tenFrancs = Money::franc( 10 );
		$bank      = new Bank();
		$bank->addRate( 'CHF', 'USD', 2 );
		$result = $bank->reduce( $fiveBucks->plus( $tenFrancs ), 'USD' );
		$this->assertEquals( Money::dollar( 10 ), $result );
	}

	public function testPlusReturnsSum() {
		$five   = Money::dollar( 5 );
		$result = $five->plus( $five );
		$sum    = $result;

		$this->assertEquals( $five, $sum->augend );
		$this->assertEquals( $five, $sum->addend );
	}

	public function testSumPlusMoney() {
		$fiveBucks = Money::dollar( 5 );
		$tenFrancs = Money::franc( 10 );
		$bank      = new Bank();
		$bank->addRate( 'CHF', 'USD', 2 );
		$sum    = new Sum( $fiveBucks, $tenFrancs );
		$sum    = $sum->plus( $fiveBucks );
		$result = $bank->reduce( $sum, 'USD' );
		$this->assertEquals( Money::dollar( 15 ), $result );

	}

	public function testReduceSum() {
		$sum    = new Sum( Money::dollar( 3 ), Money::dollar( 4 ) );
		$bank   = new Bank();
		$result = $bank->reduce( $sum, 'USD' );

		$this->assertEquals( Money::dollar( 7 ), $result );
	}

	public function testReduceMoney() {
		$bank   = new Bank();
		$result = $bank->reduce( Money::dollar( 1 ), 'USD' );

		$this->assertEquals( Money::dollar( 1 ), $result );
	}

	public function testMultiplication() {
		$five = Money::dollar( 5 );

		$this->assertEquals( Money::dollar( 10 ), $five->times( 2 ) );
		$this->assertEquals( Money::dollar( 15 ), $five->times( 3 ) );
	}

	public function testSumTimes() {
		$fiveDollars = Money::dollar(5);
		$tenFranks = Money::franc(10);
		$bank = new Bank();
		$bank->addRate('CHF', 'USD', 2);
		$sum = new Sum($fiveDollars,$tenFranks);
		$sumTimes = $sum->times(2);
		$result = $bank->reduce($sumTimes, 'USD');
		$this->assertEquals(Money::dollar(20), $result );
	}

	public function testEquality() {
		$five = Money::dollar( 5 );

		$this->assertTrue( $five->equals( Money::dollar( 5 ) ) );
		$this->assertFalse( $five->equals( Money::dollar( 6 ) ) );

		$this->assertFalse( Money::dollar( 5 ) == Money::franc( 5 ) );
	}

	public function testCurrency() {
		$this->assertEquals( 'USD', Money::dollar( 1 )->currency() );
		$this->assertEquals( 'CHF', Money::franc( 1 )->currency() );
	}

	public function testReduceMoneyDifferentCurrency() {
		$bank = new Bank();
		$bank->addRate( 'CHF', 'USD', 2 );
		$result = $bank->reduce( Money::franc( 2 ), 'USD' );
		$this->assertEquals( Money::dollar( 1 ), $result );
	}

	public function testIdentityRate() {
		$bank = new Bank();
		$this->assertEquals( 1, $bank->rate( 'USD', 'USD' ) );
	}
}
