<?php
    require_once './S3Mediator.php';

    $file_name = $_GET['file_name'];

    header('Content-Type: application/json');
    header("Content-Disposition: attachment; filename=$file_name");
    // echo $file_name;
    $response_array = (new Monosense\Exercise\S3Mediator($_SESSION['id']))->fetchTimeline($file_name);
    echo json_encode($response_array);
