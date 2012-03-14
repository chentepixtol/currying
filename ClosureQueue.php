<?php

class ClosureQueue
{

	/**
	 *
	 * @var array
	 */
	protected $closures = array();

	/**
	 *
	 * @param Closure $closure
	 * @throws Exception
	 */
	protected function validateClosure($closure){
		if( !is_callable($closure) ){
			throw new Exception("Invalid Callback");
		}
	}

	/**
	 *
	 * @param Closure $closure
	 */
	public function push($closure)
	{
		$this->validateClosure($closure);
		$this->closures[] = $closure;
	}

	/**
	 *
	 * @param Closure $closure
	 */
	public function unshift($closure){
		$this->validateClosure($closure);
		array_unshift($this->closures, $closure);
	}

	/**
	 *
	 * run
	 */
	public function run(){
		$this->runFromArray(func_get_args());
	}

	/**
	 *
	 * run
	 */
	public function __invoke(){
		$this->runFromArray(func_get_args());
	}

	/**
	 *
	 * @param array $args
	 */
	protected function runFromArray($args)
	{
		foreach ($this->closures as $closure){
			call_user_func_array($closure, $args);
		}
	}

}
