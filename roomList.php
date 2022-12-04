<?php
require_once "db.inc.php";
require_once "utils.php";

$title = "Seznam místností";
echo_html5_template(
   $styles = ["styleReset.css", "listStyle.css"],
   $title,
   $body = function () use ($pdo) {
?>
   <table>
      <tbody>
         <?php

         abstract class SqlCols
         {
            public static string $id = "id";
            public static string $name = "fullName";
            public static string $no = "no";
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
            "SELECT room_Id as " . SqlCols::$id
               . ", name as " . SqlCols::$name
               . ", no as " . SqlCols::$no
               . ", phone as " . SqlCols::$phone
               . " FROM room $sort_query"
         );
         echo_table_sortable_title_row([
            SqlCols::$name => "Název",
            SqlCols::$no => "Číslo",
            SqlCols::$phone =>  "Telefon"
         ], "roomList.php",$col,$direction);

         $o = SqlCols::$id;
         while ($row = $stmt->fetch()) {
            echo "<tr><td><a href='room.php?id={$row[SqlCols::$id]}'>{$row[SqlCols::$name]}</a></td>";
            echo "<td>{$row[SqlCols::$no]}</td>";
            echo "<td>{$row[SqlCols::$phone]}</td></tr>";
         }
         ?>
      </tbody>
   </table>
<?php
   }
);
?>