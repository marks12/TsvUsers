<?php
// namespace TsvUsers\Entity;
// use Doctrine\ORM\Mapping as ORM;
// /** @ORM\Entity */
// class Project {
// 	/**
// 	 * @ORM\Id @ORM\Column(type="integer")
// 	 * @ORM\GeneratedValue(strategy="AUTO")
// 	 */
// 	protected $id;

// 	/** @ORM\Column(type="string") */
// 	protected $name;

    
//     /**
//      * Magic getter
//      * @param $property
//      * @return mixed
//      */
//     public function __get($key)
//     {
//     	if(property_exists($this, $key))
//     	return $this->{$key};
//     	else
//     	die("Requested property {$key} not exists");
    	
//     }
    
//     /**
//      * Magic setter
//      * @param $key
//      * @param $value
//      */
//     public function __set($key, $value)
//     {
//     	if(property_exists($this, $key))
//     	$this->{$key} = $value;
//     	else
//     	die("Requested property {$key} not exists");
//     }
    
// }