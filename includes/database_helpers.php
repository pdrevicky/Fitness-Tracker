<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- prepared statements preventing SQL injections -->
<?php 
      function prepareAndExecuteQuery($con, $sqlStatement, $parameterTypes, $parameters)
      {
          $query = $con->prepare($sqlStatement);
          $query->bind_param($parameterTypes, ...$parameters);
          $query->execute();
          return $query->get_result();
      }
?>
