<?php

/**
 *
 *
 * @author chente
 *
 */
final class Currying
{
	/**
	 *
	 *
	 * @var callable
	 */
	private $fn;

	/**
	 *
	 *
	 * @var int
	 */
	private $numberOfParameters;

	/**
	 *
	 *
	 * @param callable $fn
	 * @param int $numberOfParameters
	 * @throws Exception
	 */
	public function __construct($fn, $numberOfParameters = null)
	{
		if( !is_callable($fn) ){
			throw new Exception("No es una funcion valida");
		}

		if( null == $numberOfParameters ){
			$ref = new ReflectionFunction($fn);
			$numberOfParameters = $ref->getNumberOfParameters();
		}

		$this->fn = $fn;
		$this->numberOfParameters = $numberOfParameters;
	}

	/**
	 *
	 *
	 */
	public function __invoke()
	{
		$args = func_get_args();

		$params = array();
		foreach ($args as $i => $arg){
			if( $arg !== '_' ){
				$params[$i] = $arg;
			}
		}

		if( count($params) >= $this->numberOfParameters ){
			return call_user_func_array($this->fn, $params);
		}else{
			return $this->create($params);
		}

	}

	/**
	 *
	 *
	 * @param array $params
	 */
	private function create($params)
	{
		$fn = $this->fn;
		$numberOfParameters = $this->numberOfParameters - count($params);

		$newClosure = function() use ( $fn, $params, $numberOfParameters ){
			$args = func_get_args();
			$array = array();
			for( $i = 0; $i <= $numberOfParameters; $i++ ){
				if( array_key_exists($i, $params) ){
					$array[$i] = $params[$i];
				}else{
					$array[$i] = array_shift($args);
				}
			}
			return call_user_func_array($fn, $array);
		};

		return new Currying($newClosure, $numberOfParameters);
	}

}
