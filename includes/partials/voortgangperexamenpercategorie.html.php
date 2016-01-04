<?php

function getExamQuestionResults($gebruiker_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare(" SELECT E.examenvak, E.examenjaar,E.tijdvak, ROUND(100 * SUM(S.vraag_score) / SUM(EV.maxscore),2), C.categorieomschrijving
        FROM categorie C
        JOIN examenvraag EV ON EV.categorie_id = C.categorie_id
         JOIN examen E ON EV.examen_id = E.examen_id
         JOIN resultaat R ON EV.examen_id = R.examen_id
         JOIN SCORE S ON S.examenvraag_id = EV.examenvraag_id

        WHERE
            S.gebruiker_id = 30
        GROUP BY EV.examen_id , EV.categorie_id
        ORDER BY R.timestamp , EV.categorie_id;
        ");
        $results->bindParam(1, $gebruiker_id);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $results = $results->fetchAll();
    $newResult = [];
    foreach ($results as $row) {
        $newResult[$row[0] . " " . $row[1] . " tijdvak " . $row[2]][$row[4]] = $row[3];
    }
    return $newResult;
}

$data = getExamQuestionResults($_SESSION['gebruiker_id']);
if(empty($examencijferresultaten)){
    echo"Er zijn nog geen resultaten ingevoerd, klik <a class='button' href='/dashboard/examenresultatentoevoegen.php'>hier</a> om resultaten toe te voegen.";
} else {
?>

<div class="table-responsive">
    <table class="table">

        <?php
        $test = checkCategorie();
        echo"<tr>";
        echo "<th></th>";
        foreach ($data as $key => $value) {
            echo "<th>" . $key . "</th>";
        }
        echo"</tr>";
        foreach ($test as $t) {

            echo"<tr>";
            $q = $t['categorieomschrijving'];
            echo "<th>" . $q . "</th>";
            foreach ($data as $key => $value) {
                if (array_key_exists($q, $value)) {
                    echo "<td>";
                    echo $value[$q] . "%";
                    echo "</td>";
                } else {
                    echo"<td>-</td>";
                }
            }


            echo"</tr>";
        }
        ?>
    </table>
</div>
<?php
}

?>