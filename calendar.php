<?php
// fonction pour afficher le calendrier
function showCalendar($month, $year)
{
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
    var_dump($birthdaysInMonths);


    // récupérer less données du json appointment
    $appointment = file_get_contents('data/appointment.json');
    $appointment = json_decode($appointment, true);
    // afficher les données du json appointment
    foreach ($appointment as $key => $value) {
        $aday[] = $value["date"];
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
        // afficher jour courant
        if (date('d-M-Y', mktime(0, 0, 0, $month, $i, $year)) == date('d-M-Y')) {
            echo 'fw-bold text-info';
        }
        // afficher les jours fériés
        if (array_key_exists(mktime(0, 0, 0, $month, $i, $year), $publicholidays)) {
            echo 'publicholiday';
        }

        echo '"><div class="d-flex justify-content-end"> ';
  
        // afficher les anniversaire
        // if (array_key_exists($i, $birthdaysInMonths)) {
        //     echo "<td data-bs-toggle='modal' data-bs-target='#$birthdaysInMonths[$i]' class='orange'>$i - $birthdaysInMonths[$i]</td>";
        //     // $fulldate = $i . ' ' . $months[$month - 1] . ' ' . $year;
        //     // createBirthdayModals($birthdaysInMonths[$i], $fulldate, $birthdayDate);
        // }
        // if (in_array(date('m-d', mktime(0, 0, 0, $month, $i, $year)), $bday)) {

        //     ';

        // vérifier si le jour du mois correspond a $birthdaysInMonths
        if (array_key_exists($i,$birthdaysInMonths)) {
            echo '<button class="btn" data-bs-toggle="modal" data-bs-target="#' . $birthdaysInMonths[$i] . '"><img src="https://img.icons8.com/ios-filled/16/000000/birthday-cake.png"/></button>';
        }


        // }
        // afficher les rendez-vous
        if (in_array(date('Y-m-d', mktime(0, 0, 0, $month, $i, $year)), $aday)) {
            echo '<button class="btn" data-bs-toggle="modal" data-bs-target="#' . $value["name"] . '"><img src="https://img.icons8.com/tiny-color/16/null/calendar-plus.png"/></button>';
            // showAppointment($value, $month, $year);
        }
        echo '</div>' . $i . '</td>';


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

// fonction affichant $birthdays $value['name'] et ['date'] dans une modal au click de l'icone
function showBirthdays($i, $birthdaysInMonths)
{
    echo '<div class="modal fade" id="'.$birthdaysInMonths[$i].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><img src="https://img.icons8.com/tiny-color/16/null/gift.png"/> Anniversaire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>' .$birthdaysInMonths[$i]. ' fête son anniversaire aujourd\'hui !</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>';
}

// fonction affichant $appointment $value['name'] et ['date'] dans une modal au click de l'icone
// function showAppointment($value, $month, $year)
// {
//     echo '<div class="modal fade" id="' . $value['name'] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
//             <div class="modal-dialog">
//                 <div class="modal-content">
//                     <div class="modal-header">
//                         <h3 class="modal-title" id="exampleModalLabel"><img src="https://img.icons8.com/tiny-color/16/null/calendar-plus.png"/>Rendez-vous</h3>
//                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
//                     </div>
//                     <div class="modal-body">
//                         <p>Vous avez un rendez vous aujourd\'hui : ' . $value['name'] . '</p>
//                         <p>Heure : ' . $value['hour'] . '</p>
//                         <p>Lieu : ' . $value['location'] . '</p>
//                     </div>
//                     <div class="modal-footer">
//                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
//                     </div>
//                 </div>
//             </div>
//         </div>';
// }
