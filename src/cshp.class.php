<?php 

require_once("classes/cshp_OutputObject.class.php");
require_once("classes/cshp_Selector.class.php");

class Cshp {

  //Options
  private $compress, $cache;
  private $cshpFolder;

  //Data
  private $snippets;

  //The final CSS
  private $outputObject;

  function __construct($options = [], $cshpFolder = "src"){
    
    $this->outputObject = new Cshp_OutputObject();
    $this->cshpFolder = is_array($options) ? $cshpFolder : $options;

    try { 
      $this->compress = in_array("compress", $options) || in_array("compressed", $options); //True = compress, False = normal  
      $this->snippets = json_decode(file_get_contents($this->cshpFolder."/data/cshp_snippets.json"), true);
    } catch (Exception $error){
      $this->throwError($error->getMessage());
    }
  }

  private function throwError($errorMessage){ //Clear all the css and shows an error in a comment
    $this->outputObject->erase();
    $this->comment("error", $errorMessage);
  }

  public function comment($type = "info", $message = "Ops! We forget the message"){ //CSS comment
    $commentString = "";
    
    if($type == "info"){
      $commentString = "/* " . $message . " */ ";
    } else if ($type == "error"){
      $commentString  = "########## ERROR ##########\n";
      $commentString .= "/* " . $message . " \n";
      $commentString .= "--- Compilation stopped */ ";
    }

    $this->outputObject->addString($commentString);
  }

  public function import($file, $options = []){ //Add a import at the begining of the CSS file
    try {
      
      $importString = "@import '" . $file . "' ";
      $optionsSize = sizeof($options);

      if($optionsSize > 0){
        foreach($options as $opI => $option){
          $importString .= $option;
          if($opI < $optionsSize-1){
            $importString .= ", ";
          }
        }
      }

      $this->outputObject->addStringAtBeginning($importString."; ");

    } catch (Exception $error){
      $this->throwError($error->getMessage());
    }
  }

  public function select($selector, $properties = [], $functions = []){ //The main function
    try {
      
      $pseudoClasses = [];

      foreach ($properties as $propertie => $propValue) {
        if(gettype($propValue) == "array"){
          
          $mod = substr($propertie, 0, 1);
          $propName = substr($propertie, 1);

          if($mod == "&"){ //Pseudo-classes
            array_push($pseudoClasses, [
              "selector" => $selector.":".$propName, 
              "properties" => $propValue
            ]);
          } else if($mod == "%"){ //Nested properties
            foreach ($propValue as $propValueKey => $propValueVal) {
              $properties[$propName."-".$propValueKey] = $propValueVal;
            }
          }

          unset($properties[$propertie]);
        }
      }

      $selectorObj = new Cshp_Selector();
      $selectorObj->mainSelector = $selector;
      $selectorObj->properties = $properties;

      $selectorSnippets = array_intersect($properties, array_flip($this->snippets));
      
      foreach ($selectorSnippets as $selectorSnippetKey => $selectorSnippetValue) {
        $newPropertie = explode(":", $this->snippets[$selectorSnippetValue]);
        $selectorObj->properties[$selectorSnippetKey] = $newPropertie[1];
        $selectorObj->properties[$newPropertie[0]] = $selectorObj->properties[$selectorSnippetKey];
        unset($selectorObj->properties[$selectorSnippetKey]);
      }

      $this->outputObject->addSelector($selectorObj);

      foreach ($pseudoClasses as $pseudoSelector) {
        $this->select($pseudoSelector["selector"], $pseudoSelector["properties"]);
      }

    } catch (Exception $error) {
      $this->throwError($error->getMessage());
    }
  
  }

  public function compile($outputFolder = "", $outputFile = ""){
    if($outputFile == ""){
      header("Content-type: text/css");
      echo $this->outputObject->compile($this->compress);
    }
  }

}

?>