<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/post.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/handlers/training_history_handler.php');
?>

<h1><i class="fas fa-book-open"></i> Your Trainings <i class="fas fa-book-open"></i></h1>
<div class="container">
    <div class="row">
        <?php
        newTraining($result, $con);
        ?>
    </div>
</div>
<div>