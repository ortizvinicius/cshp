<?php 

require_once("cshp_Selector.class.php");

class Cshp_OutputObject {

  //This class will contain the "css" code itself, "CRUD" and other methods

  private $outputArray = [];

  public function erase(){
  }

  public function addString($string = ""){
    array_push($outputArray, $string);
  }

  public function addStringAtBeginning($string = ""){
    array_unshift($outputArray, $string);
  }

  public function addSelector(Cshp_Selector $selector){
    //When a nested or a selector == existent, look for the selector and update it
  }

  

}

?>