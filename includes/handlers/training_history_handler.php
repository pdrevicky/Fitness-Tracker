<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_helpers.php');

// if session is set make user_logged_in variable
if (isset($_SESSION['username'])) {
    $user_logged_in = $_SESSION['username'];
    $user_details_query = prepareAndExecuteQuery($con, "SELECT * FROM users WHERE username= ? ", 's', [$user_logged_in]);
    $user = mysqli_fetch_array($user_details_query); //get all info about user as an array  
} else {
    header("Location: register.php"); //if user is not logged in redirect back to register.php
    exit();
}

function exerciseFromTrainingGroupDone($training_group, $row)
{
    foreach ($training_group as $exercise) {
        if ($row[$exercise] != 0) {
            return true;
        }
    }
    return false;
}

// generate report from cardio training and calories burned
function cardioTraining($row, $exercise_names)
{
    if (exerciseFromTrainingGroupDone($exercise_names['cardio'], $row)) {
        echo "<div class='training_cardio'>";
        echo "<div id='cardio_training_history'>";
        echo "<h3>Cardio Training </h3><hr>";
        echo "</div>";
        if ($row['Swimming'] != 0) {
            echo "<b>Swimming: </b>" . $row['Swimming'] . " m<br>";
        }
        if ($row['Running'] != 0) {
            echo "<b>Running: </b>" . $row['Running'] . " km<br>";
        }
        if ($row['Cycling'] != 0) {
            echo "<b>Cycling: </b>" . $row['Cycling'] . " km<br>";
        }
        echo "<br>";
        echo "</div>";
    }
}

// generate report from weight training and calories burned
function weightTraining($row, $exercise_names)
{
    if (exerciseFromTrainingGroupDone($exercise_names['all_weight'], $row)) {
        echo "<div class='training_weight'>";
        echo "<div id='weight_training_history'>";
        echo "<h3>Weight Training </h3><hr>";
        echo "</div>";
        if (exerciseFromTrainingGroupDone($exercise_names['back'], $row)) {
            // TODO: Simplify printing
            echo "<h4>Back Training </h4>";
            if ($row['Deadlift'] != 0) {
                echo "<b>Deadlift: </b>" . $row['Deadlift'] . " kg<br>";
            }
            if ($row['Back Barbell'] != 0) {
                echo "<b>Back Barbell: </b>" . $row['Back Barbell'] . " kg<br>";
            }
            if ($row['Back Machine'] != 0) {
                echo "<b>Back Machine: </b>" . $row['Back Machine'] . " kg<br>";
            }
            echo "<br>";
        }
        if (exerciseFromTrainingGroupDone($exercise_names['biceps'], $row)) {
            echo "<h4>Biceps Training </h4>";
            if ($row['Biceps Dumbbell'] != 0) {
                echo "<b>Biceps Dumbbell: </b>" . $row['Biceps Dumbbell'] . " kg<br>";
            }
            if ($row['Biceps Barbell'] != 0) {
                echo "<b>Biceps Barbell: </b>" . $row['Biceps Barbell'] . " kg<br>";
            }
            if ($row['Biceps Machine'] != 0) {
                echo "<b>Biceps Machine: </b>" . $row['Biceps Machine'] . " kg<br>";
            }
            echo "<br>";
        }
        if (exerciseFromTrainingGroupDone($exercise_names['chest'], $row)) {
            echo "<h4>Chest Training </h4>";
            if ($row['Bench Press'] != 0) {
                echo "<b>Bench Press: </b>" . $row['Bench Press'] . " kg<br>";
            }
            if ($row['Chest Dumbbell'] != 0) {
                echo "<b>Chest Dumbbell: </b>" . $row['Chest Dumbbell'] . " kg<br>";
            }
            if ($row['Chest Machine'] != 0) {
                echo "<b>Chest Machine: </b>" . $row['Chest Machine'] . " kg<br>";
            }
            echo "<br>";
        }
        if (exerciseFromTrainingGroupDone($exercise_names['legs'], $row)) {
            echo "<h4>Legs Training </h4>";
            if ($row['Leg Press'] != 0) {
                echo "<b>Leg Press: </b>" . $row['Leg Press'] . " kg<br>";
            }
            if ($row['Squats'] != 0) {
                echo "<b>Squats: </b>" . $row['Squats'] . " kg<br>";
            }
            if ($row['Calf'] != 0) {
                echo "<b>Calf: </b>" . $row['Calf'] . " kg<br>";
            }
            echo "<br>";
        }
        if (exerciseFromTrainingGroupDone($exercise_names['shoulders'], $row)) {
            echo "<h4>Shoulders Training </h4>";
            if ($row['Shoulders Dumbbell'] != 0) {
                echo "<b>Shoulders Dumbbell: </b>" . $row['Shoulders Dumbbell'] . " kg<br>";
            }
            if ($row['Shoulders Barbell'] != 0) {
                echo "<b>Shoulders Barbell: </b>" . $row['Shoulders Barbell'] . " kg<br>";
            }
            if ($row['Shoulders Machine'] != 0) {
                echo "<b>Shoulders Machine: </b>" . $row['Shoulders Machine'] . " kg<br>";
            }
            echo "<br>";
        }
        if (exerciseFromTrainingGroupDone($exercise_names['triceps'], $row)) {
            echo "<h4>Triceps Training </h4>";
            if ($row['Triceps Dumbbell'] != 0) {
                echo "<b>Triceps Dumbbell: </b>" . $row['Triceps Dumbbell'] . " kg<br>";
            }
            if ($row['Triceps Barbell'] != 0) {
                echo "<b>Triceps Barbell: </b>" . $row['Triceps Barbell'] . " kg<br>";
            }
            if ($row['Triceps Machine'] != 0) {
                echo "<b>Triceps Machine: </b>" . $row['Triceps Machine'] . " kg<br>";
            }
            echo "<br>";
        }
        echo "</div>";
    }
}
// if there is some note from training show it
function trainingNote($row)
{
    if ($row['Note'] != null) {
        echo "<div class='training_note'>";
        echo "<hr>";
        echo "<b>Note: </b>" . $row['Note'];
        echo "</div>";
    }
}

// counting burned calories and show on training report
function showBurnedCalories($row, $exercise_names)
{
    $km_kcal = 0;
    $m_kcal = 0;
    $kg_kcal = 0;

    // TODO: rename calories to kcals
    if (exerciseFromTrainingGroupDone($exercise_names['cardio'], $row)) {
        $calories_burned_per_km_of_running = 100;
        $calories_burned_per_km_of_cycling = 50;
        $calories_burned_per_m_of_swimming = 0.36;

        $running_kcal = (int) $row['Running'] * $calories_burned_per_km_of_running;
        $cycling_kcal = (int) $row['Cycling'] * $calories_burned_per_km_of_cycling;
        
        $km_kcal = $running_kcal + $cycling_kcal;

        $m_kcal = (int) $row['Swimming'] * $calories_burned_per_m_of_swimming;
    }
    
    if (exerciseFromTrainingGroupDone($exercise_names['all_weight'], $row)) {
        $reps_per_exercise = 40;
        $calories_burned_per_lifted_kg = 1.2;

        $dumbbell_exercises = ['Biceps Dumbbell', 'Chest Dumbbell', 'Shoulders Dumbbell', 'Triceps Dumbbell' ];
        $kg_cal = 0;
        foreach($exercise_names['all_weight'] as $exercise){
            if(in_array($exercise, $dumbbell_exercises)){
                $kg_cal = $kg_cal + (int) $row[$exercise] * 2;
            }
            else {
                $kg_cal = $kg_cal + (int) $row[$exercise];
            }
        }

        $kg_cal = $kg_cal * $reps_per_exercise * $calories_burned_per_lifted_kg  ;
        $kg_kcal = $kg_cal / 100;
    }

    $total_kcal_burned = $km_kcal + $m_kcal + $kg_kcal;
    echo "<div class='burned_kcal' >";
    echo "<b>Burned Calories:</b> " . $total_kcal_burned . " Kcal ";
    echo  "</div>";
}

// main associative array with keys and values
$result = prepareAndExecuteQuery($con, "SELECT * FROM posts WHERE added_by= ? ", 's', [$user_logged_in]);

// generating training report on trainig_history.php
function newTraining($result, $con)
{
    // TODO: create associative array and pass that as additional argument to cardioTraining, weightTraining, showBurnedCalories
    $exercise_names = array(
        'back' => ['Deadlift', 'Back Barbell', 'Back Machine'], 'biceps' => ['Biceps Dumbbell', 'Biceps Barbell', 'Biceps Machine'], 'chest' => ['Bench Press', 'Chest Dumbbell', 'Chest Machine'],
        'legs' => ['Squats', 'Leg Press', 'Shoulders Machine'], 'shoulders' => ['Shoulders Barbell', 'Shoulders Dumbbell', 'Triceps Machine'], 'triceps' => ['Triceps Machine', 'Triceps Barbell', 'Triceps Dumbbell']
    );

    $exercise_names['all_weight'] = array_merge($exercise_names['back'], $exercise_names['biceps'], $exercise_names['chest'], $exercise_names['legs'], $exercise_names['shoulders'], $exercise_names['triceps']);
    $exercise_names['cardio'] = ['Swimming', 'Running', 'Cycling'];

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        echo "<div class='training' id='$row[id]'>";
        echo "<div id='dateOfTraining'>";
        $europe_date_format = date("d.m.Y", strtotime($row['Date of training']));
        echo "<b>Date of Training: </b>" . "$europe_date_format";
        echo "</div>";
        cardioTraining($row, $exercise_names);
        weightTraining($row, $exercise_names);
        trainingNote($row);
        showBurnedCalories($row, $exercise_names);
        echo "<form action='training_history.php' method='POST'>";
        echo "<input type='hidden' name='trainingId' value='$id'/>";
        echo "<button type='submit' class='btn btn-danger' id='deleteTraining' name='delete'><i class='fas fa-trash'></i> Delete</button>";
        echo "</form>";
        echo "</div>";
        echo "<br>";
    }

    // delete training report from training_history.php
    if (isset($_POST['delete']) && $_POST['trainingId']) {
        $id = $_POST['trainingId'];
        mysqli_query($con, "DELETE FROM posts WHERE id='$id'");
        prepareAndExecuteQuery($con, "DELETE FROM posts WHERE id= ? ", 'i', [$id]);
        header("Refresh:0");
    }
}
