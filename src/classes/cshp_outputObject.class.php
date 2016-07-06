<?php 

require_once("cshp_Selector.class.php");

class Cshp_OutputObject {

  //This class will contain the "css" code itself, "CRUD" and other methods

  private $outputArray = [];
  private $indexArray = [];

  public function erase(){
  }

  public function addString($string = ""){
    array_push($this->outputArray, $string);
    array_push($this->indexArray, "?");
  }

  public function addStringAtBeginning($string = ""){
    array_unshift($this->outputArray, $string);
    array_unshift($this->indexArray, "?");
  }

  public function addSelector(Cshp_Selector $selector){

    $mainSelectors = explode(",", $selector->mainSelector);

    foreach ($mainSelectors as $mainSelector) { 

      $existentIndex = array_search($mainSelector, $this->indexArray);

      if($existentIndex === false){
        $selector->mainSelector = $mainSelector;
        array_push($this->outputArray, $selector);
        array_push($this->indexArray, $mainSelector);
      } else {
        //Add the new properties
        $this->outputArray[$existentIndex]->properties = array_merge($this->outputArray[$existentIndex]->properties, $selector->properties);
      }

    }

    print_r($this->outputArray);
  }

  

}

?>