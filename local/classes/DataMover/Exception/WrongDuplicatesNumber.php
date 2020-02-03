<?php


namespace Oip\DataMover\Exception;

use Exception;

class WrongDuplicatesNumber extends  Exception
{
    public function __construct(string $tableName, string $wrongDuplicatesSet)
    {
          $message = "Data moving exception: Wrong number of duplicates has detected during data moving: table `$tableName`, "
              ." wrong db records ids: $wrongDuplicatesSet";
        parent::__construct($message, $code = 0, $previous = null);
    }
}