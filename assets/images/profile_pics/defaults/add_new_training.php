<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Create add new training page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/exercise_definition.php');

// add new training to database if post button is pressed
if (isset($_POST['post'])) {
    $user_obj = new User($con, $_SESSION['username']);
    $user_obj->submitPost(
        $_POST['Swimming'],
        $_POST['Running'],
        $_POST['Cycling'],
        $_POST['Deadlift'],
        $_POST['Back_Barbell'],
        $_POST['Back_Machine'],
        $_POST['Biceps_Dumbbell'],
        $_POST['Biceps_Barbell'],
        $_POST['Biceps_Machine'],
        $_POST['Bench_Press'],
        $_POST['Chest_Dumbbell'],
        $_POST['Chest_Machine'],
        $_POST['Leg_Press'],
        $_POST['Squats'],
        $_POST['Calf'],
        $_POST['Shoulders_Dumbbell'],
        $_POST['Shoulders_Barbell'],
        $_POST['Shoulders_Machine'],
        $_POST['Triceps_Dumbbell'],
        $_POST['Triceps_Barbell'],
        $_POST['Triceps_Machine'],
        $_POST['post_text'],
        $_POST['day_of_training']
    );

    header("Location: training_history.php");
    exit;
}
?>

<!-- form for adding new training -->
<h1><i class="fas fa-calendar-plus"></i> Add New Training <i class="fas fa-calendar-plus"></i></h1>
<div class="container">
<div id="main_column" class="main_column column">
    <form action="add_new_training.php" class="post_form" id="main_form" method="POST">
            <div id="day_of_training">
                <h3><i class="fas fa-calendar-day"></i> Day of Training <input id="add_new_training_date" type="date" required name="day_of_training"></h3>
                <hr>
            </div>

            <!-- generate form for cardio training where User can add his performance -->
            <div id="cardio_training">
                <h3><i class="fas fa-running"></i> Cardio Training</h3>
                <br>
                <div class="row">
                    <?php
                    foreach ($cardio_exercises as $exercise_name) {
                        $exercise_unit = $exercise_names_to_units[$exercise_name];
                        echo "<div class='col-4'>";
                        echo "<span>$exercise_name&nbsp<input type='number' value='0' min='0' name='$exercise_name'>&nbsp $exercise_unit</span>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <hr>

            <!-- generate form for weight training where User can add his performance -->
            <div id="weight_training">
                <h3><i class="fas fa-dumbbell"></i> Weight Training</h3>
                <br>
                <?php
                foreach ($weight_exercise_groups as $body_part => $exercise_names) {
                    echo "<div class='row'>";
                    echo "<div class='col-3'>";
                    echo "<span class='body_part_title'>$body_part</span>";
                    echo "</div>";
                    foreach ($exercise_names as $exercise_name) {
                        $exercise_unit = $exercise_names_to_units[$exercise_name];
                        echo "<div class='col-3'>";
                        echo "<div class='row'>";
                        echo "<div class='col-4'>";
                        echo "<span>$exercise_name&nbsp</span>";
                        echo "</div>";
                        echo "<div class='col-8'>";
                        echo "<input type='number' value='0' min='0' name='$exercise_name' >&nbsp$exercise_unit";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
            <hr>

            <!-- generate textarea for training where you User can add his note from training -->
            <div id="textarea_training">
                <div class="row">
                    <div class="col-9">
                        <textarea name="post_text" id="post_text" placeholder="Some note from your training ?"></textarea>
                    </div>
                    <div class="col-3">
                        <!-- on submit add information about training to database and generate new training in training_history.php  -->
                        <input type="submit" name="post" id="post_button" class='btn-primary' value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>

</body>

</html>