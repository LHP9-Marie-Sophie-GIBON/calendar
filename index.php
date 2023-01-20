<?php
require('calendar.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier intéractif</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="row justify-content-center align-items-center">
        <div class="card">
            <div class="card-header">

                <form action="" method="post">

                    <!-- Options pour tous les mois -->

                    <select name="month" id="month" class="btn btn-outline-info fw-bolder">
                        <option value="default" selected disabled>
                            <!-- afficher le mois sélectionner ou le mois courant -->
                            <?php
                            if (isset($_POST['month'])) {
                                $months = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
                                $month = (int)$_POST['month'];

                                if ($month >= 1 && $month <= 12) {
                                    echo $months[$month - 1];
                                }
                            } else {
                                $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'UTC', IntlDateFormatter::GREGORIAN, 'MMMM');
                                echo $formatter->format(new DateTime());
                            }
                            ?>
                        </option>
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                        <option value="6">Juin</option>
                        <option value="7">Juillet</option>
                        <option value="8">Août</option>
                        <option value="9">Septembre</option>
                        <option value="10">Octobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Décembre</option>
                    </select>


                    <!-- Options pour toutes les années -->
                    <select name="year" id="year" class="btn btn-outline-info fw-bolder">
                        <option value="2023" selected>
                            <?php
                            if (isset($_POST['year'])) {
                                echo $_POST['year'];
                            } else {
                                // afficher l'année courante
                                echo date('Y');
                            }
                            ?>
                        </option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>

                    <button type="submit" class="btn text-info fs-3"><i class="bi bi-eye-fill"></i></button>

                </form>
            </div>
            <div class="card-body animate__animated animate__zoomIn">
                <?php
                if (isset($_POST['year']) && isset($_POST['month'])) {
                    $month = $_POST['month'];
                    $year = $_POST['year'];
                    showCalendar($month, $year);
                } else {
                    $month = date('n');
                    $year = date('Y');
                    showCalendar($month, $year);
                };
                ?>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>