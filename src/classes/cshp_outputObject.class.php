<?php 

require_once("cshp_Selector.class.php");

class Cshp_OutputObject {

  //This class will contain the "css" code itself, "CRUD" and other methods

  private $outputArray = [];
  private $indexArray = [];

  public function erase(){
    $this->outputArray = [];
    $this->indexArray = [];
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

    $existentIndex = array_search($selector->mainSelector, $this->indexArray);

    if($existentIndex === false){
      array_push($this->outputArray, $selector);
      array_push($this->indexArray, $selector->mainSelector);
    } else {
      //Add the new properties
      $this->outputArray[$existentIndex]->properties = array_merge($this->outputArray[$existentIndex]->properties, $selector->properties);
    }

  }

  public function compile($compress = false){
    $outputCSS = "";

    $lineBreak = !$compress ? "\n" : "";
    $space = !$compress ? " " : "";
    $ident = !$compress ? "  " : "";

    $lastString = false;

    foreach ($this->outputArray as $selector) {
      if(gettype($selector) == "object"){
        if($lastString){ $outputCSS .= $lineBreak; }
        
        $outputCSS .= $selector->mainSelector . $space . "{" . $lineBreak;
        foreach ($selector->properties as $propKey => $propValue) {
          $outputCSS .= $ident.$propKey . ':' . $space . $propValue . ";" . $lineBreak;
        }
        $outputCSS .= "}".$lineBreak;
        
        $lastString = false;
      } else {
        if(!$lastString){ $outputCSS .= $lineBreak; }
        $outputCSS .= $selector.$lineBreak;
        $lastString = true;
      }
    }

    return $outputCSS;
  }

}

?>