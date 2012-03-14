<?php

require_once 'ClosureQueue.php';

/**
 *
 * @author chente
 *
 */
class MainTest extends PHPUnit_Framework_TestCase
{


	/**
	 *
	 * @test
	 */
	public function main()
	{
		$closureQueue = new ClosureQueue();
		$closureQueue->push(function(){echo 'Hello';});
		$closureQueue->push(function(){echo ' World';});
		$closureQueue->push(array($this, 'exclamation'));
		$closureQueue->push(array('MainTest', 'exclamation'));
		$closureQueue->push('MainTest::exclamation');

		$closureQueue2 = new ClosureQueue();
		$closureQueue2->push(function(){echo " says chentepixtol";});
		$closureQueue->push($closureQueue2);
		$closureQueue->unshift(function(){ echo '!!!';});

		ob_start();
    	ob_implicit_flush(0);
    	$closureQueue->run();
		$result = ob_get_clean();
		$this->assertEquals("!!!Hello World!!! says chentepixtol", $result);
	}

	/**
	 *
	 * @test
	 */
	public function withArgs()
	{
		$closureQueue = new ClosureQueue();
		$closureQueue->push(function(){echo 'Hello World!';});
		$closureQueue->push(function($name){echo " says {$name}";});

		ob_start();
    	ob_implicit_flush(0);
    	$closureQueue->run('vicentemmor');
		$result = ob_get_clean();
		$this->assertEquals("Hello World! says vicentemmor", $result);

		ob_start();
    	ob_implicit_flush(0);
    	$closureQueue('vicentemmor');
		$result = ob_get_clean();
		$this->assertEquals("Hello World! says vicentemmor", $result);
	}

	public static function exclamation(){
		echo '!';
	}

	/**
	 *
	 * @test
	 */
	public function currying()
	{
		require_once 'Currying.php';

		$add = new Currying(function ($a, $b, $c){
			return $a + $b + $c;
		});

		$this->assertEquals(6, $add(1, 2, 3));

		$add5 = $add('_', '_', 5);
		$add13 = $add5(8, '_');

		$this->assertEquals(20, $add13(7));

		$add10 = $add5('_', '5');
		$this->assertEquals(15, $add10(5));
	}

	/**
	 *
	 * @test
	 */
	public function calculator()
	{
		require_once 'Currying.php';

		$calc = new Currying(function ($operation, $operator1, $operator2){
			if( '+' == $operation ){
				return $operator1 + $operator2;
			}else if( '-' == $operation ){
				return $operator1 - $operator2;
			}else if( '*' == $operation ){
				return $operator1 * $operator2;
			}
		});

		$add = $calc('+');
		$sub = $calc('-');
		$multiply = $calc('*');

		$this->assertTrue($add instanceof Currying);
		$this->assertTrue($sub instanceof Currying);
		$this->assertTrue($multiply instanceof Currying);

		$add60 = $add(60);
		$sub20 = $sub("_", "20");
		$multiply2 = $multiply("2");

		$this->assertTrue($add60 instanceof Currying);
		$this->assertTrue($sub20 instanceof Currying);
		$this->assertTrue($multiply2 instanceof Currying);

		$this->assertEquals(100, $add60(40));
		$this->assertEquals(30, $sub20(50));
		$this->assertEquals(10, $multiply2(5));

		$this->assertEquals(240, $multiply2($add60($sub20(80))) );
	}
}
