<?php 
      function prepareAndExecuteQuery($con, $sqlStatement, $parameterTypes, $parameters)
      {
          $query = $con->prepare($sqlStatement);
          $query->bind_param($parameterTypes, ...$parameters);
          $query->execute();
          return $query->get_result();
      }
?>
