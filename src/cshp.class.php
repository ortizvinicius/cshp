<?php 

require_once("classes/Cshp_OutputObject.class.php")

class Cshp {

  //Options
  private $compress, $cache;

  //The final CSS
  private $outputObject;

  function __construct($options = []){
    
    $this->outputObject = new Cshp_OutputObject();

    try { 

      $this->compress = in_array("compress", $options) || in_array("compressed", $options); //True = compress, False = normal
      $this->cache = in_array("cache", $options); //TO DO :P

      //CSS Header
      header("Content-type: text/css");

    } catch (Exception $error){
      $this->throwError($error->getMessage());
    }
  }

  public function throwError($errorMessage){
    $this->outputObject->erase();
    $this->comment("error", $errorMessage);
  }

  public function comment($type = "info", $message = "Ops! We forget the message"){
    $commentString = "";
    
    if($type == "info"){
      $commentString = "/* " . $message . " */";
    } else if ($type == "error"){
      $commentString  = "########## ERROR ##########\n";
      $commentString .= "/* " . $message . " \n";
      $commentString .= "--- Compilation stopped */";
    }

    $this->outputObject->addString($commentString);
  }

  public function import($file, $options = []){
    try {

      if(strpos($file, ".cshp") !== false || strpos($file, ".php") !== false){ //Import the content of cshp/php file (compile together)

      } else { //Creates a simple CSS import

        $importString = "@import '" . $file . "' ";
        $optionsSize = sizeof($options);

        if($optionsSize > 0)){
          foreach($options as $opI => $option){
            $importString .= $option;
            if($opI < $optionsSize-1){
              $importString .= ", ";
            }
          }
        }

        $this->outputObject->addStringAtBeginning($importString);

      }

    } catch (Exception $error){
      $this->throwError($error->getMessage());
    }
  }


}

?>