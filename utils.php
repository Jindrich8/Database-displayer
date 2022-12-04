<?php

function get_id_from_INPUT_GET()
{
    return filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );
}

function get_sql_order_statement_from_INPUT_GET($cols,&$col,&$direction)
{
    $sort_query = null;
    get_order_params_from_INPUT_GET($col, $direction);
    if (($col = $cols[$col] ?? null) !== null) {
        $sort_query = "ORDER BY $col $direction";
    }
    return $sort_query;
}

function get_order_params_from_INPUT_GET(&$col, &$direction)
{
    $sort = $_GET['sort'] ?? null;
    if ($sort !== null) {
        $col = $sort;
        $index = mb_strrpos($sort, '_');
        if ($index >= 0) {
            $col = mb_substr($sort, 0, $index);
            $direction = 'ASC';
            if (mb_substr($sort, $index + 1) === 'down') {
                $direction = 'DESC';
            }
        }
    }
}

function echo_table_sortable_title_row($headers, $link,$activeHeader,$activeHeaderDirection)
{
    echo "<tr>";
    foreach ($headers as $key => $header) {
        $down_sort = null;
        $up_sort = null;
        if($key === $activeHeader){
            if($activeHeaderDirection == 'DESC'){
                $down_sort = "class='sort'";
            }
            else{
                $up_sort = "class='sort'";
            }
        }
        
        echo "<th>$header<a href='$link?sort=" . $key . "_down' $down_sort data-sort='down' title='sort down'></a><a href='$link?sort=" . $key . "_up' $up_sort data-sort='up' title = 'sort up'></a></th>";
    }
    echo "</tr>";
}

function http_bad_request_die($message = null)
{
    http_response_code(HttpResponseCodes::$HTTP_BAD_REQUEST);
    $status_msg = "Error " . HttpResponseCodes::$HTTP_BAD_REQUEST . ": Bad request";
    echo_html5_error_template($status_msg, $message);
    die();
}

function http_not_found_die($message = null)
{
    http_response_code(HttpResponseCodes::$HTTP_NOT_FOUND);
    $status_msg = "Error " . HttpResponseCodes::$HTTP_NOT_FOUND . ": Not found";
    echo_html5_error_template($status_msg, $message);
    die();
}

function call_if_closure_and_ret($var){
    return $var instanceof Closure ? $var() : $var;
}

function echo_html5_error_template($error_status, $error_message)
{
    echo_html5_template(
        [],
        $error_status,
        "<h1>$error_status</h1><p>$error_message</p>"
    );
}
function echo_html5_template($styles,$title, $body)
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        foreach($styles as $style){
            echo "<link rel='stylesheet' href='",$style,"'>";
        }
        echo "<title>",$title,"</title>";
        ?>
    </head>

    <body>
        <?php
        echo call_if_closure_and_ret($body);
        ?>
    </body>

    </html>
<?php
}
abstract class HTTPResponseCodes
{
    public static $HTTP_OK = 200;
    public static $HTTP_CREATED = 201;
    public static $HTTP_NO_CONTENT = 204;
    public static $HTTP_BAD_REQUEST = 400;
    public static $HTTP_UNAUTHORIZED = 401;
    public static $HTTP_FORBIDDEN = 403;
    public static $HTTP_NOT_FOUND = 404;
    public static $HTTP_METHOD_NOT_ALLOWED = 405;
    public static $HTTP_NOT_ACCEPTABLE = 406;
    public static $HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public static $HTTP_REQUEST_TIMEOUT = 408;
    public static $HTTP_CONFLICT = 409;
    public static $HTTP_GONE = 410;
    public static $HTTP_LENGTH_REQUIRED = 411;
    public static $HTTP_PRECONDITION_FAILED = 412;
    public static $HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    public static $HTTP_REQUEST_URI_TOO_LONG = 414;
    public static $HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public static $HTTP_RANGE_NOT_SATISFIABLE = 416;
    public static $HTTP_EXPECTATION_FAILED = 417;
    public static $HTTP_UNPROCESSABLE_ENTITY = 422;
    public static $HTTP_LOCKED = 423;
    public static $HTTP_FAILED_DEPENDENCY = 424;
    public static $HTTP_TOO_EARLY = 425;
    public static $HTTP_UPGRADE_REQUIRED = 426;
    public static $HTTP_PRECONDITION_REQUIRED = 428;
    public static $HTTP_TOO_MANY_REQUESTS = 429;
    public static $HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public static $HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    public static $HTTP_INTERNAL_SERVER_ERROR = 500;
    public static $HTTP_NOT_IMPLEMENTED = 501;
    public static $HTTP_BAD_GATEWAY = 502;
    public static $HTTP_SERVICE_UNAVAILABLE = 503;
    public static $HTTP_GATEWAY_TIMEOUT = 504;
    public static $HTTP_VERSION_NOT_SUPPORTED = 505;
    public static $HTTP_VARIANT_ALSO_NEGOTIATES = 506;
    public static $HTTP_INSUFFICIENT_STORAGE = 507;
    public static $HTTP_LOOP_DETECTED = 508;
    public static $HTTP_NOT_EXTENDED = 510;
    public static $HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
    public static $HTTP_HOST_UNREACHABLE = 520;
    public static $HTTP_MISDIRECTED_REQUEST = 521;
}
