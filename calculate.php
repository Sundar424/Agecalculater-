<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dob'])) {
    $dob = $_POST['dob'];
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $diff = $today->diff($birthDate);

    if ($birthDate > $today) {
        echo json_encode([
            "age" => "Invalid (Future Date)",
            "next" => "--",
            "last" => "--"
        ]);
        exit;
    }

    $nextBirthday = new DateTime($today->format("Y") . "-" . $birthDate->format("m-d"));
    if ($nextBirthday < $today) {
        $nextBirthday->modify('+1 year');
    }
    $daysLeft = $today->diff($nextBirthday)->days;

    $lastBirthday = clone $nextBirthday;
    $lastBirthday->modify('-1 year');
    $daysSince = $lastBirthday->diff($today)->days;

    echo json_encode([
        "age" => "{$diff->y} years, {$diff->m} months, {$diff->d} days",
        "next" => "$daysLeft days",
        "last" => "$daysSince days"
    ]);
}
?>
