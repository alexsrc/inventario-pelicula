<?php

namespace App\Factories;

use InvalidArgumentException;

class ManagerInformationFactory {

	private $managerInformation = [
    	"Movie" => "Movie"
  	];

	public function __construct()
    {

    }

  	public function create($type)
  	{
      	if (!array_key_exists($type, $this->managerInformation)) {
        	throw new InvalidArgumentException("Report {$type} does not exist");
      	}

      	$managerInformation = '\App\ManagerInformation' .'\\'. $this->managerInformation[$type].'\\'. $this->managerInformation[$type];
      	if (!class_exists($managerInformation)) {
        	throw new InvalidArgumentException("Class {$managerInformation} does not exist");
      	}

      	return new $managerInformation();
  	}
}
