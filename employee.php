<?php
require_once "db.inc.php";
require_once "utils.php";

$id = get_id_from_INPUT_GET();
if ($id === false || $id === null) {
    http_bad_request_die("Url parameter 'id' is required and must be number bigger than 1");
}

$stmt = $pdo->query("SELECT e.name, e.surname, e.job, e.wage, r.name as room  FROM employee e JOIN room r ON e.room = r.room_id WHERE e.employee_id = $id");
if (!($employee = $stmt->fetch())) {
    http_not_found_die("Employee with id $id was not found");
} else {
    $title = "Karta osoby " . $employee['surname'] . " " . mb_substr($employee['name'], 0, 1);
    echo_html5_template(
        $styles = [
            "styleReset.css",
            "detailStyle.css"
        ],
        $title,
        $body = function () use ($pdo, $id, $title, $employee) {
            echo "<h1>", $title, "</h1>";
?>
        <table>
            <tbody>
                <?php

                $headers = ["Jméno", "Příjmení", "Pozice", "Mzda", "Místnost", "Klíče"];
                echo "<tr><th>", $headers[0], "</th>", "<td>", $employee['name'], "</td></tr>",
                "<tr><th>", $headers[1], "</th>", "<td>", $employee['surname'], "</td></tr>",
                "<tr><th>", $headers[2], "</th>", "<td>", $employee['job'], "</td></tr>",
                "<tr><th>", $headers[3], "</th>", "<td>", $employee['wage'], "</td></tr>",
                "<tr><th>", $headers[4], "</th>", "<td>", $employee['room'], "</td></tr>",
                "<tr><th>", $headers[5], "</th>", "<td><ul>";
                $stmt = $pdo->query("SELECT k.room as id, r.name FROM `key` k JOIN room r ON k.room = r.room_Id WHERE k.employee = $id");
                while ($room = $stmt->fetch()) {
                    echo "<li><a href='room.php?id=", $room['id'], "'>",
                    $room['name'],
                    "</a></li>";
                }
                echo "</ul></td></tr>";
                ?>
            </tbody>
        </table>
        <a href='employeeList.php' class='back'>Zpět na seznam zaměstnanců</a>
<?php
        }
    );
}
?>