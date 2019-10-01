<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create training history page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/exercise_definition.php');

// if user is not logged in redirect back to register.php
if (!isset($_SESSION['username'])) {
    header("Location: register.php"); 
    exit();
}

// finds out if at least one exercise from training group (cardio or back etc..) was done, 
// this is used to determine whether to print html header to that group
function exerciseFromTrainingGroupDone($training_group, $training_session)
{
    foreach ($training_group as $exercise) {
        if ($training_session[$exercise] != 0) {
            return true;
        }
    }
    return false;
}

// generate report from cardio training 
function printCardioTraining($training_session, $cardio_exercises, $exercise_names_to_units)
{
    if (exerciseFromTrainingGroupDone($cardio_exercises, $training_session)) {
        echo "<br>";
        echo "<div class='training_cardio'>";
        echo "<div id='cardio_training_history'>";
        echo "<h3>Cardio Training </h3><hr>";
        echo "</div>";
        foreach ($cardio_exercises as $exercise_name) {
            if ($training_session[$exercise_name] != 0) {
                echo "<b>$exercise_name: </b>" . $training_session[$exercise_name] .  $exercise_names_to_units[$exercise_name] . "<br>";
            }
        }
        echo "<br>";
        echo "</div>";
    }
}

// generate report from weight training 
function printWeightTraining($training_session, $weight_exercises, $weight_exercise_groups, $exercise_names_to_units)
{
    if (exerciseFromTrainingGroupDone($weight_exercises, $training_session)) {
        echo "<div class='training_weight'>";
        echo "<div id='weight_training_history'>";
        echo "<br>";
        echo "<h3>Weight Training </h3><hr>";
        echo "</div>";
        foreach ($weight_exercise_groups as $body_part => $exercise_names) {
            if (exerciseFromTrainingGroupDone($weight_exercise_groups[$body_part], $training_session)) {
                echo "<h4>$body_part Training </h4>";
                foreach ($exercise_names as $exercise_name) {
                    if ($training_session[$exercise_name] != 0) {
                        echo "<b>$exercise_name: </b>" . $training_session[$exercise_name] . $exercise_names_to_units[$exercise_name] . "<br>";
                    }
                }
                echo "<br>";
            }
        }
        echo "</div>";
    }
}

// if there is some note from training show it
function printTrainingNote($training_session)
{
    if ($training_session['Note'] != null) {
        echo "<div class='training_note'>";
        echo "<hr>";
        echo "<b>Note: </b>" . $training_session['Note'];
        echo "</div>";
    }
}

// counting burned calories and show on training report
function printBurnedCalories($training_session, $weight_exercises, $cardio_exercises, $kcals_burned_per_unit_of_exercise)
{
    $cardio_kcal = 0;

    // accumulate cardio kcals
    foreach ($cardio_exercises as $exercise_name) {
        $cardio_kcal = $cardio_kcal + (int) $training_session[$exercise_name] * $kcals_burned_per_unit_of_exercise[$exercise_name];
    }

    // accumulate weight training kcals
    $reps_per_exercise = 40;

    // single hand exercises are accumulated twice
    $single_hand_exercises = ['Biceps Dumbbell', 'Chest Dumbbell', 'Shoulders Dumbbell', 'Triceps Dumbbell'];
    $weight_kcal = 0;
    foreach ($weight_exercises as $exercise) {
        $current_exercise_kcal = (int) $training_session[$exercise] * $reps_per_exercise * $kcals_burned_per_unit_of_exercise['Weight Exercises'];

        if (in_array($exercise, $single_hand_exercises)) {
            $current_exercise_kcal *= 2;
        }
        $weight_kcal += $current_exercise_kcal;
    }

    $total_kcal_burned = $cardio_kcal + $weight_kcal;
    echo "<div class='burned_kcal' >";
    echo "<b>Burned Calories:</b> " . $total_kcal_burned . " Kcal ";
    echo  "</div>";
}

// print which day was training done
function printDayOfTraining($training_session)
{
    echo "<div id='dateOfTraining'>";
    $europe_date_format = date("d.m.Y", strtotime($training_session['Date of training']));
    echo "<b>Date of Training: </b>" . "$europe_date_format";
    echo "</div>";
}

// prints logged user's training history 
function printTrainingHistory($con, $exercise_names_to_units, $cardio_exercises, $weight_exercises, $weight_exercise_groups, $kcals_burned_per_unit_of_exercise)
{
    $training_sessions_table = prepareAndExecuteQuery($con, "SELECT * FROM posts WHERE added_by= ? ", 's', [$_SESSION['username']]);

    $training_sessions_array = [];

    while ($training_session = $training_sessions_table->fetch_assoc()) {
        array_push($training_sessions_array, $training_session);
    }
    
    function sortTrainingSessionsByDate($a, $b){
        $t1 = strtotime($a['Date of training']);
        $t2 = strtotime($b['Date of training']);
        return $t2 - $t1;
    }
    
    // sort trainings based on date of training
    usort($training_sessions_array, 'sortTrainingSessionsByDate');

    foreach($training_sessions_array as $training_session) {
        
        echo "<div class='training' id='$training_session[id]'>";

        printDayOfTraining($training_session);
        printCardioTraining($training_session, $cardio_exercises, $exercise_names_to_units);
        printWeightTraining($training_session, $weight_exercises, $weight_exercise_groups, $exercise_names_to_units);
        printTrainingNote($training_session);
        printBurnedCalories($training_session, $weight_exercises, $cardio_exercises, $kcals_burned_per_unit_of_exercise);

        echo "<form action='training_history.php' method='POST'>";
        echo "<input type='hidden' name='trainingId' value='$training_session[id]'/>";
        echo "<button type='submit' class='btn btn-danger' id='deleteTraining' name='delete'><i class='fas fa-trash'></i> Delete</button>";
        echo "</form>";
        echo "</div>";
        echo "<br>";
    }

    // delete training report 
    if (isset($_POST['delete']) && $_POST['trainingId']) {
        prepareAndExecuteQuery($con, "DELETE FROM posts WHERE id= ? ", 'i', [$_POST['trainingId']]);
        header("Refresh:0");
    }
}
?>

<h1><i class="fas fa-book-open"></i> Your Trainings <i class="fas fa-book-open"></i></h1>
<div class="container">
    <div class="row">
        <?php
        printTrainingHistory($con, $exercise_names_to_units, $cardio_exercises, $weight_exercises, $weight_exercise_groups, $kcals_burned_per_unit_of_exercise);
        ?>
    </div>
</div>
<div>