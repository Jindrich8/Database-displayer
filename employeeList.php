<?php
require_once "db.inc.php";
require_once "utils.php";

$title = "Seznam zaměstnanců";
echo_html5_template(
   $styles = ["styleReset.css", "listStyle.css"],
   $title,
   $body = function () use($pdo) {
?>
   <table>
      <tbody>
         <?php
         abstract class SqlCols
         {
            public static string $id = "id";
            public static string $name = "fullName";
            public static string $surname = "surname";
            public static string $room = "room";
            public static string $phone = "phone";
            public static string $job = "job";
         }
         $sort_query = get_sql_order_statement_from_INPUT_GET([
            SqlCols::$name => SqlCols::$name,
            SqlCols::$room => SqlCols::$room,
            SqlCols::$phone => SqlCols::$phone,
            SqlCols::$job => SqlCols::$job
         ],$col,$direction);

         $stmt = $pdo->query(
            "SELECT e.employee_Id as " . SqlCols::$id
               . ", CONCAT(e.surname, ' ', e.name) as " . SqlCols::$name
               . ", r.name as " . SqlCols::$room
               . ", r.phone as " . SqlCols::$phone
               . ", e.job as " . SqlCols::$job
               . " FROM employee e JOIN room r ON e.room = r.room_Id $sort_query"
         );


         echo_table_sortable_title_row([
            SqlCols::$name => "Jméno",
            SqlCols::$room => "Místnost",
            SqlCols::$phone => "Telefon",
            SqlCols::$job => "Pozice"
         ], "employeeList.php",$col,$direction);

         while ($employee = $stmt->fetch()) {
            echo "<tr>",
            "<td><a href='employee.php?id=", $employee[SqlCols::$id], "'>", $employee[SqlCols::$name], "</a></td>",
            "<td>", $employee[SqlCols::$room], "</td>",
            "<td>", $employee[SqlCols::$phone], "</td>",
            "<td>", $employee[SqlCols::$job], "</td>",
            "</tr>";
         }
         ?>
      </tbody>
   </table>
<?php
   }
);
?>