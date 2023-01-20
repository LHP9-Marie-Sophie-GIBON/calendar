<?php

function showCalendar($month, $year)
{
    // récupérer les données du json holidays
    $holidays = file_get_contents('data/holidays.json');
    $holidays = json_decode($holidays, true);

    $holidays_days = array();
    foreach ($holidays as $holiday) {
        $start = new DateTime($holiday['start']);
        $end = new DateTime($holiday['end']);
        while($start <= $end){
            $holidays_days[] = $start->format("Y-m-d");
            $start->modify('+1 day');
        }
    }
    $holidaysInMonths = [];
    foreach ($holidays_days as $value){
        $hday = explode('-', $value);
        if ($hday[0] == $year && $hday[1] == $month) {
            $holidaysInMonths[intval($hday[2])] = $value;
        }
    }


    // récupérer les données du json birthdays 
    $birthdays = file_get_contents('data/birthday.json');
    $birthdays = json_decode($birthdays, true);

    $birthdaysInMonths = [];
    foreach ($birthdays as $birthday) {
        $bday = explode('-', $birthday['date']);

        if ($bday[1] == $month) {
            $birthdaysInMonths[intval($bday[2])] = $birthday['name'];
        }
    };

    // récupérer less données du json appointment
    $appointments = file_get_contents('data/appointment.json');
    $appointments = json_decode($appointments, true);

    $appointmentsInMonths = [];
    foreach ($appointments as $appointment) {
        $aday = explode('-', $appointment["date"]);

        if ($aday[0] == $year && $aday[1] == $month) {
            $appointmentsInMonths[intval($aday[2])] = [
                "name" => $appointment['name'],
                "hour" => $appointment['hour'],
                "location" => $appointment['location']
            ];
        }
    };


    // tableau des jours fériés
    $publicholidays = [
        mktime(0, 0, 0, 1, 1, $year) => 'Jour de l\'an',
        strtotime('+1 day', easter_date($year)) => 'Lundi de Pâques',
        mktime(0, 0, 0, 5, 1, $year) => 'Fête du Travail',
        mktime(0, 0, 0, 5, 8, $year) => 'Victoire 45',
        strtotime('+39 days', easter_date($year)) => 'Ascension',
        strtotime('+50 days', easter_date($year)) => 'Lundi de Pentecôte',
        mktime(0, 0, 0, 7, 14, $year) => 'Fête Nationale',
        mktime(0, 0, 0, 8, 15, $year) => 'Assomption',
        mktime(0, 0, 0, 11, 1, $year) => 'Toussaint',
        mktime(0, 0, 0, 11, 11, $year) => 'Armistice',
        mktime(0, 0, 0, 11, 11, $year) => 'Jour de Noël',
    ];

    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    echo '
    <table>
        <thead>
            <tr>
                <th>Lundi</th>
                <th>Mardi</thclass=>
                <th>Mercredi</th>
                <th>Jeudi</th>
                <th>Vendredi</th>
                <th>Samedi</th>
                <th>Dimanche</th>
            </tr>
        </thead>
        <tbody>
    ';
    // boucle pour parcourir les jrs du mois + fonction date() pour déterminer le jr de la semaine 
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $dayOfWeek = date('N', mktime(0, 0, 0, $month, $i, $year));

        // creation de cellules vides avant le 1er du mois
        if ($i == 1) {
            echo '<tr>';
            for ($j = 1; $j < $dayOfWeek; $j++) {
                echo '<td class="vide"></td>';
            }
        }
        // afficher les jrs du mois
        echo '<td class="';

        // afficher le jour courant
        if (date('d-M-Y', mktime(0, 0, 0, $month, $i, $year)) == date('d-M-Y')) {
            echo 'fw-bold text-info';
        }
        // afficher les jours fériés
        if (array_key_exists(mktime(0, 0, 0, $month, $i, $year), $publicholidays)) {
            echo 'publicholiday';
        }

        echo '"><div class="d-flex justify-content-end ';

        // afficher les vacances
        if (array_key_exists($i, $holidaysInMonths)) {
            echo 'vacances';
        }

        echo '"><div class="buttonsGroup"> ';

        // afficher les anniversaire
        if (array_key_exists($i, $birthdaysInMonths)) {
            echo '<button class="btn" data-bs-toggle="modal" data-bs-target="#' . $birthdaysInMonths[$i] . '"><img src="https://img.icons8.com/tiny-color/16/null/packaging.png"/></button>';
            showBirthdays($birthdaysInMonths[$i]);
        }


        // afficher les rendez-vous
        if (array_key_exists($i, $appointmentsInMonths)) {
            echo '<button class="btn" data-bs-toggle="modal" data-bs-target="#' . $appointmentsInMonths[$i]['name'] . '"><img src="https://img.icons8.com/tiny-color/16/null/calendar-plus.png"/></button>';
            showAppointment($appointmentsInMonths[$i]['name'], $appointmentsInMonths[$i]['hour'], $appointmentsInMonths[$i]['location']);
        }
        echo '</div></div>' . $i . '</td>';

        // creation de cellules vides après le dernier du mois
        if ($dayOfWeek == 7) {
            echo '</tr>';
        } else if ($i == $daysInMonth) {
            for ($j = $dayOfWeek; $j < 7; $j++) {
                echo '<td class="vide"></td>';
            }
            echo '</tr>';
        }
    }
    echo "</tbody>";
    echo "</table>";
}





// fonction affichant $birthdays $appointment['name'] et ['date'] dans une modal au click de l'icone
function showBirthdays($name)
{
    echo '<div class="modal fade" id="' . $name . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><img src="https://img.icons8.com/tiny-color/16/null/gift.png"/> Anniversaire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>' . $name . ' fête son anniversaire aujourd\'hui !</p>
                    </div>
                </div>
            </div>
        </div>';
}






// fonction affichant $appointments$appointment['name'] et ['date'] dans une modal au click de l'icone
function showAppointment($name, $hour, $location)
{
    echo '<div class="modal fade" id="' . $name . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><img src="https://img.icons8.com/tiny-color/16/null/calendar-plus.png"/>Rendez-vous</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Vous avez un rendez vous aujourd\'hui : ' . $name . '</p>
                        <p>Heure : ' . $hour . '</p>
                        <p>Lieu : ' . $location . '</p>
                    </div>
                </div>
            </div>
        </div>';
}
