<?PHP
require_once("BaseModel.php");

class ResultRequestModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getResultRequestBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *
                FROM result_request  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getResultRequestByID($code)
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


    function insertResultRequest($data = [])
    {
        $sql = " INSERT INTO result_request ( 
                	result_request_ac,
                    detail,
                    request_id
            )  VALUES ('" .
            $data['result_request_ac'] . "','" .
            $data['detail'] . "','" .
            $data['request_id']  . "'  ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateResultRequestBy($result_request_id, $data = [])
    {
        $sql = " UPDATE result_request SET 
        
        result_request_ac = '" . static::$db->real_escape_string($data['result_request_ac']) . "',
        detail = '" . static::$db->real_escape_string($data['detail']) . "', 
        request_id = '" . static::$db->real_escape_string($data['request_id']) . "'
        WHERE result_request_id = '" . static::$db->real_escape_string($result_request_id) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteResultRequestByID($code)
    {
        $sql = " DELETE FROM result_request WHERE result_request_id  = '$code' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
