<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//main array with keys and values
$result = prepareAndExecuteQuery($con, "SELECT * FROM posts WHERE added_by= ? ", 's', [$user_logged_in]);
//TODO: close result   

function bestPerformance($result){  

    $best_performances = [];
    $day_of_performances = [];
    $training_names = ["Cycling", "Running", "Swimming", "Deadlift", "Back Barbell", "Back Machine", "Biceps Dumbbell", "Biceps Barbell", "Biceps Machine", "Bench Press",
                        "Chest Dumbbell", "Chest Machine", "Leg Press", "Squats", "Calf", "Shoulders Dumbbell", "Shoulders Barbell", "Shoulders Machine", "Triceps Dumbbell",
                        "Triceps Barbell", "Triceps Machine"];
    $km_units = ["Cycling", "Running"];
    $m_units = ["Swimming"];
    
    for($i = 0; $i < sizeof($training_names); $i++){
        $best_performances[$training_names[$i]] = 0;
        $day_of_performances[$training_names[$i]]= "";
    }
    
    $result->data_seek(0);      
    while ($row = $result->fetch_assoc()) {
            foreach($best_performances as $key => $value){
                $performace = (int)$row[$key];
                if($performace > $best_performances[$key]){
                    $best_performances[$key] = $performace;
                    $day_of_performances[$key] = $row['Date of training'];
                }

            }
        }
    echo "<div class='container' id='best_performances_div'>";
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
        for($i = 0; $i < sizeof($training_names); $i++){
            echo "<div>";
                echo "<p class='best_training'>";
                    if(in_array($training_names[$i], $km_units)) {
                        echo "<div class='row best_performances'>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_names'>";
                                     echo $training_names[$i];
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_values'>";
                                    echo $best_performances[$training_names[$i]] . " km" ;
                                echo "</div>";       
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_days'>";
                                    echo $day_of_performances[$training_names[$i]];
                                echo "</div>";   
                            echo "</div>";
                        echo "</div>";
                    }
                    else if (in_array($training_names[$i], $m_units)) {
                        echo "<div class='row best_performances'>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_names'>";
                                    echo $training_names[$i];
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_values'>";
                                    echo $best_performances[$training_names[$i]] . " m" ;
                                echo "</div>";   
                            echo "</div>";
                            echo "<div class='col'>";
                                echo "<div class='best_performances_training_days'>";
                                     echo $day_of_performances[$training_names[$i]];
                                echo "</div>";   
                            echo "</div>";
                        echo "</div>";   
                    }
                    else {
                    echo "<div class='row best_performances'>";
                        echo "<div class='col'>";
                            echo "<div class='best_performances_training_names'>";
                                echo $training_names[$i];
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='col'>";
                            echo "<div class='best_performances_training_values'>";
                                echo $best_performances[$training_names[$i]] . " kg" ;
                            echo "</div>";   
                        echo "</div>";
                        echo "<div class='col'>";
                            echo "<div class='best_performances_training_days'>";
                                echo $day_of_performances[$training_names[$i]];
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

    <div id="best_performances_title">
        <h1><i class="fas fa-award"></i> Best Performances <i class="fas fa-award"></i></h1>
       <?php
        bestPerformance($result);
       ?>
    </div>
   
<div>