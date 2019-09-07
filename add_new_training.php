<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/includes/classes/post.php');

// add new training to database if post button is pressed
if (isset($_POST['post'])) {
    $post = new Post($con, $user_logged_in);
    $post->submitPost(
        $_POST['cycling'],
        $_POST['running'],
        $_POST['swimming'],
        $_POST['ba_deadlift'],
        $_POST['ba_barbell'],
        $_POST['ba_machine'],
        $_POST['b_dumbbell'],
        $_POST['b_barbell'],
        $_POST['b_machine'],
        $_POST['c_benchpress'],
        $_POST['c_dumbbell'],
        $_POST['c_machine'],
        $_POST['l_legPress'],
        $_POST['l_squats'],
        $_POST['l_calf'],
        $_POST['s_dumbbell'],
        $_POST['s_barbell'],
        $_POST['s_machine'],
        $_POST['t_dumbbell'],
        $_POST['t_barbell'],
        $_POST['t_machine'],
        $_POST['post_text'],
        $_POST['day_of_training']
    );
    header("Location: training_history.php");
    exit;
}
?>

<!-- form for adding new training -->
<div id="main_column" class="main_column column">
    <form action="add_new_training.php" class="post_form" id="main_form" method="POST">
        <div class="container">
            <div id="day_of_training">
                <h3><i class="fas fa-calendar-day"></i> Day of Training <input type="date" required name="day_of_training"></h3>
                <hr>
            </div>
            <!-- generate form for cardio training where User can add his performance -->
            <div id="cardio_training">
                <h3><i class="fas fa-running"></i> Cardio Training</h3>
                <div class="row">
                    <div class="col-4">
                        <span>Cycling&nbsp<input type="number" value="0" min="0" name="cycling">&nbspkm</span>
                    </div>
                    <div class="col-4">
                        <span>Running&nbsp<input type="number" value="0" min="0" name="running">&nbspkm</span>
                    </div>
                    <div class="col-4">
                        <span>Swimming&nbsp<input type="number" value="0" min="0" name="swimming">&nbspm</span>
                    </div>
                </div>
            </div>
            <hr>
            <!-- generate form for weight training where User can add his performance -->
            <div id="weight_training">
                <h3><i class="fas fa-dumbbell"></i> Weight Training</h3>
                <div class="row">
                    <div class="col-3">
                        <span class="body_part_title">Back</span>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>DeadLift&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="ba_deadlift">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Barbell&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="ba_barbell">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Machine&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="ba_machine">&nbspkg
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="body_part_title">Biceps</span>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Dumbbell&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="b_dumbbell">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Barbell&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="b_barbell">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Machine&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="b_machine">&nbspkg
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <span class="body_part_title">Chest</span>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Bench&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="c_benchpress">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Dumbbell&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="c_dumbbell">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-4">
                                <span>Machine&nbsp</span>
                            </div>
                            <div class="col-8">
                                <input type="number" value="0" min="0" name="c_machine">&nbspkg
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <span class="body_part_title">Legs</span>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-4">
                                    <span>LegPress&nbsp</span>
                                </div>
                                <div class="col-8">
                                    <input type="number" value="0" min="0" name="l_legPress">&nbspkg
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-4">
                                    <span>Squats&nbsp</span>
                                </div>
                                <div class="col-8">
                                    <input type="number" value="0" min="0" name="l_squats">&nbspkg
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-4">
                                    <span>Calf&nbsp</span>
                                </div>
                                <div class="col-8">
                                    <input type="number" value="0" min="0" name="l_calf">&nbspkg
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <span class="body_part_title">Shoulders</span>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <span>Dumbbell&nbsp</span>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" value="0" min="0" name="s_dumbbell">&nbspkg
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <span>Barbell&nbsp</span>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" value="0" min="0" name="s_barbell">&nbspkg
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <span>Machine&nbsp</span>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" value="0" min="0" name="s_machine">&nbspkg
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <span class="body_part_title">Triceps</span>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <span>Dumbbell&nbsp</span>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" value="0" min="0" name="t_dumbbell">&nbspkg
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <span>Barbell&nbsp</span>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" value="0" min="0" name="t_barbell">&nbspkg
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <span>Machine&nbsp</span>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" value="0" min="0" name="t_machine">&nbspkg
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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