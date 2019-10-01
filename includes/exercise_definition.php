<!-- Author: Peter Drevicky 2019 -->
<!-- License: MIT -->

<!-- Arrays containing available exercises for profile, friend profile and messages pages-->
<?php
$exercise_names_to_units = [
    "Cycling" => "km", "Running" => "km", "Swimming" => "m", "Deadlift" => "kg", "Back Barbell" => "kg", "Back Machine" => "kg", "Biceps Dumbbell" => "kg", "Biceps Barbell" => "kg",
    "Biceps Machine" => "kg", "Bench Press" => "kg", "Chest Dumbbell" => "kg", "Chest Machine" => "kg", "Leg Press" => "kg", "Squats" => "kg", "Calf" => "kg",
    "Shoulders Dumbbell" => "kg", "Shoulders Barbell" => "kg", "Shoulders Machine" => "kg", "Triceps Dumbbell" => "kg", "Triceps Barbell" => "kg", "Triceps Machine" => "kg"
];

$weight_exercise_groups = array(
    'Back' => ['Deadlift', 'Back Barbell', 'Back Machine'],
    'Biceps' => ['Biceps Dumbbell', 'Biceps Barbell', 'Biceps Machine'],
    'Chest' => ['Bench Press', 'Chest Dumbbell', 'Chest Machine'],
    'Legs' => ['Leg Press', 'Squats', 'Calf'],
    'Shoulders' => ['Shoulders Dumbbell', 'Shoulders Barbell', 'Shoulders Machine'],
    'Triceps' => ['Triceps Dumbbell', 'Triceps Barbell', 'Triceps Machine']
);

$weight_exercises = array_merge(
    $weight_exercise_groups['Back'],
    $weight_exercise_groups['Biceps'],
    $weight_exercise_groups['Chest'],
    $weight_exercise_groups['Legs'],
    $weight_exercise_groups['Shoulders'],
    $weight_exercise_groups['Triceps']
);
$cardio_exercises = ['Swimming', 'Running', 'Cycling'];

$kcals_burned_per_unit_of_exercise = ['Running' => 100, "Cycling" => 50, "Swimming" => 0.36, "Weight Exercises" => 0.012];
?>