<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create a page with this user's best performance for each exercise -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/exercise_definition.php');

// print a single column in the best performance table row
function printBestPerformanceTableColumn($inner_div_class, $content)
{
    echo "<div class='col'>";
    if (!is_null($inner_div_class)) {
        echo "<div class='$inner_div_class'>";
    }
    echo $content;
    if (!is_null($inner_div_class)) {
        echo "</div>";
    }
    echo "</div>";
}

// printa single row in best performances table
function printBestPerformanceTableRow($exercise_name, $exercise_unit, $best_performances, $days_of_best_performances)
{
    echo "<div>";
    echo "<p class='best_training'>";
    echo "<div class='row best_performances'>";

    printBestPerformanceTableColumn('best_performances_exercise_name', $exercise_name);
    printBestPerformanceTableColumn('best_performances_training_values', $best_performances[$exercise_name] . " " . $exercise_unit);
    printBestPerformanceTableColumn('best_performances_training_days', $days_of_best_performances[$exercise_name]);

    echo "</div>";
}

function printBestPerformanceTable($all_training_sessions, $exercise_names_to_units)
{
    $best_performances = [];
    $days_of_best_performances = [];

    // set keys and initialize values to zero 
    foreach ($exercise_names_to_units as $exercise_name => $exercise_unit) {
        $best_performances[$exercise_name] = 0;
        $days_of_best_performances[$exercise_name] = "";
    }

    // find day and value of best performance for each exercise
    while ($training_session = $all_training_sessions->fetch_assoc()) {
        foreach ($best_performances as $exercise_name => $best_performance) {
            $perfomance = (int) $training_session[$exercise_name];
            if ($perfomance > $best_performance) {
                $best_performances[$exercise_name] = $perfomance;
                $days_of_best_performances[$exercise_name] = $training_session['Date of training'];
            }
        }
    }

    // prints the best performance day and value for each exercise with the correct units (m, km, kg...)
    foreach ($exercise_names_to_units as $exercise_name => $exercise_unit) {
        printBestPerformanceTableRow($exercise_name, $exercise_unit, $best_performances, $days_of_best_performances);
    }
}
?>

<div id="best_performances_title">
    <h1><i class="fas fa-award"></i> Best Performances <i class="fas fa-award"></i></h1>
    <?php
    echo "<div class='container' id='best_performances_div'>";
    echo "<div id='best_performances_titles'  class='row'>";

    $inner_div_class = null;
    printBestPerformanceTableColumn($inner_div_class, "Training Name");
    printBestPerformanceTableColumn($inner_div_class, "Best Performance");
    printBestPerformanceTableColumn($inner_div_class, "Achievement Day");

    echo "</div>";

    $all_training_sessions = prepareAndExecuteQuery($con, "SELECT * FROM posts WHERE added_by= ? ", 's', [$_SESSION['username']]);
    printBestPerformanceTable($all_training_sessions, $exercise_names_to_units);
    ?>
</div>

<div>