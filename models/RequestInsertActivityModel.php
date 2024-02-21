<?PHP
require_once("BaseModel.php");

class RequestInsertActivityModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getRequestInsertActivityBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *
                FROM request_insert_activity  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getRequestInsertActivityByID($code)
    {
        $sql = "";


        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }


    function insertRequestInsertActivity($data = [])
    {
        $sql = " INSERT INTO request_insert_activity ( 
                	detail,
                    ac_id
            )  VALUES ('" .
            $data['detail'] . "','" .
            $data['ac_id']  . "'  ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateRequestInsertActivityBy($request_id, $data = [])
    {
        $sql = " UPDATE request_insert_activity SET 
        
        detail = '" . static::$db->real_escape_string($data['detail']) . "', 
        ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "'
        WHERE request_id = '" . static::$db->real_escape_string($request_id) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteRequestInsertActivityByID($code)
    {
        $sql = " DELETE FROM request_insert_activity WHERE request_id  = '$code' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
