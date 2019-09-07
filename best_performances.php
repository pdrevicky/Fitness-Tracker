<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//main array with keys and values
$result = prepareAndExecuteQuery($con, "SELECT * FROM posts WHERE added_by= ? ", 's', [$user_logged_in]);
//TODO: close result   

function bestPerformance($result){  

    $firstTrainingNameNeeded = 1;
    $lastTrainingNameNeeded = 21;
    $bestPerforamnces = [];
    $dayOfPerformances = [];
    $trainingNames = ["Cycling", "Running", "Swimming", "Deadlift", "Back Barbell", "Back Machine", "Biceps Dumbbell", "Biceps Barbell", "Biceps Machine", "Bench Press",
                        "Chest Dumbbell", "Chest Machine", "Leg Press", "Squats", "Calf", "Shoulders Dumbbell", "Shoulders Barbell", "Shoulders Machine", "Triceps Dumbbell",
                        "Triceps Barbell", "Triceps Machine"];
    $km_units = ["Cycling", "Running"];
    $m_units = ["Swimming"];
    $kg_units = ["Deadlift", "Back Barbell", "Back Machine", "Biceps Dumbbell", "Biceps Barbell", "Biceps Machine", "Bench Press",
    "Chest Dumbbell", "Chest Machine", "Leg Press", "Squats", "Calf", "Shoulders Dumbbell", "Shoulders Barbell", "Shoulders Machine", "Triceps Dumbbell",
    "Triceps Barbell", "Triceps Machine"];
    
    for($i = 0; $i < sizeof($trainingNames); $i++){
        $bestPerforamnces[$trainingNames[$i]] = 0;
        $dayOfPerformances[$trainingNames[$i]]= "";
    }
    
    $result->data_seek(0);      
    while ($row = $result->fetch_assoc()) {
            foreach($bestPerforamnces as $key => $value){
                $performace = (int)$row[$key];
                if($performace > $bestPerforamnces[$key]){
                    $bestPerforamnces[$key] = $performace;
                    $dayOfPerformances[$key] = $row['Date of training'];
                }

            }
        }
    echo "<div class='container' id='bestPerformancesDiv'>";
        echo "<div id='best_performances_titles'  class='row'>";
            echo "<div class='col'>";
                echo "Training Name";
            echo "</div>";
            echo "<div class='col'>";
                echo "Best Performance";
            echo "</div>";
            echo "<div class='col'>";
                echo "Achievement day";
            echo "</div>";
        echo "</div>";
        for($i = 0; $i < sizeof($trainingNames); $i++){
            echo "<div>";
                echo "<p class='best-training'>";
                    if(in_array($trainingNames[$i], $km_units)) {
                        echo "<div class='row bestPerformances'>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_names'>";
                                     echo $trainingNames[$i];
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_values'>";
                                    echo $bestPerforamnces[$trainingNames[$i]] . " km" ;
                                echo "</div>";       
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_days'>";
                                    echo $dayOfPerformances[$trainingNames[$i]];
                                echo "</div>";   
                            echo "</div>";
                        echo "</div>";
                    }
                    else if (in_array($trainingNames[$i], $m_units)) {
                        echo "<div class='row bestPerformances'>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_names'>";
                                    echo $trainingNames[$i];
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_values'>";
                                    echo $bestPerforamnces[$trainingNames[$i]] . " m" ;
                                echo "</div>";   
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_days'>";
                                     echo $dayOfPerformances[$trainingNames[$i]];
                                echo "</div>";   
                            echo "</div>";
                        echo "</div>";   
                    }
                    else {
                    echo "<div class='row bestPerformances'>";
                        echo "<div class='col'>";
                            echo "<div class='best_performances_training_names'>";
                                echo $trainingNames[$i];
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='col'>";
                            echo "<div class='best_performances_training_values'>";
                                echo $bestPerforamnces[$trainingNames[$i]] . " kg" ;
                            echo "</div>";   
                        echo "</div>";
                        echo "<div class='col'>";
                            echo "<div class='best_performances_training_days'>";
                                echo $dayOfPerformances[$trainingNames[$i]];
                            echo "</div>";    
                        echo "</div>";
                    echo "</div>";   
                    }
                echo "</p>";
            echo "</div>";
        }
    echo "</div>";
}

?>

    <div id="bestPerformances-title">
        <h1><i class="fas fa-award"></i> Best Performances <i class="fas fa-award"></i></h1>
       <?php
        bestPerformance($result);
       ?>
    </div>
   
<div>