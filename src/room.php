<?php
require_once "db.inc.php";
require_once "utils.php";

$id = get_id_from_INPUT_GET();
if ($id === false || $id === null) {
    http_bad_request_die("Url parameter 'id' is required and must be number bigger than 1");
}

$stmt = $pdo->query("SELECT r.room_id, r.no, r.name, r.phone, AVG(e.wage) as avg_wage  FROM room r JOIN employee e ON r.room_id = e.room WHERE r.room_id = $id");
if (!($room = $stmt->fetch()) || $room['room_id'] !== $id) {
    http_not_found_die("Room with id $id was not found");
}

$title = "Místnost č. {$room['no']}";
echo_html5_template(
    $styles = [
        "styleReset.css",
        "detailStyle.css"
    ],
    $title,
    $body = function () use ($pdo, $id, $title, $room) {
        echo "<h1>", $title, "</h1>";
?>
    <table>
        <tbody>
            <?php
            $headers = ["Číslo", "Název", "Telefon", "Lidé", "Průměrná mzda", "Klíče"];
            echo 
            "<tr><th>",$headers[0],"</th>","<td>", $room['no'], "</td></tr>",
            "<tr><th>",$headers[1],"</th>","<td>", $room['name'], "</td></tr>",
            "<tr><th>",$headers[2],"</th>","<td>", $room['phone'], "</td></tr>",
            "<tr><th>",$headers[3],"</th>","<td><ul>";
            $stmt = $pdo->query("SELECT e.employee_id as id, CONCAT(e.surname, ' ', SUBSTRING(e.name,1,1), '.') as name FROM employee e WHERE e.room = $id");
            while ($employee = $stmt->fetch()) {
                echo "<li><a href='employee.php?id=", $employee['id'], "'>", $employee['name'], "</a></li>";
            }
            echo "</ul></td></tr>";
            $avg_wage = $room['avg_wage'] ?? null;
            echo "<tr>","<th>",$headers[4],"</th>","<td>", $avg_wage, "</td>",
            "<tr><th>$headers[5]</th><td><ul>";
            $stmt = $pdo->query("SELECT e.employee_id as id, CONCAT(e.surname, ' ', SUBSTRING(e.name,1,1), '.') as name FROM `key` k JOIN employee e ON k.employee = e.employee_id WHERE k.room = $id");
            while ($employee = $stmt->fetch()) {
                echo "<li><a href='employee.php?id=",$employee['id'],"'>",$employee['name'],"</a></li>";
            }
            echo "</ul></td>",
            "</tr>";
            ?>
        </tbody>
    </table>
    <a href='roomList.php' class='back'>Zpět na seznam místností</a>
<?php
    }
);
?>

