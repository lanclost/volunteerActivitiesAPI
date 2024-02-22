<?PHP
require_once("BaseModel.php");

class AccumulatedModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getAccumulated($data = [])
    {
        $condition = '';
        $sql = "SELECT *
                FROM accumulated  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getAccumulatedByID($data = [])
    {
        $sql = "SELECT * FROM accumulated
        WHERE ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "' 
        AND user_id = '" . static::$db->real_escape_string($data['user_id']) . "'  ";

        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getAccumulatedByList($data = [])
    {
        $sql = "SELECT *,
        (SELECT ac_name FROM activity WHERE activity.ac_id = accumulated.ac_id) as activity_name,
        (SELECT CONCAT(category_num,' ',category_name) FROM activity_category WHERE activity_category.ac_category_id = accumulated.ac_category_id) as category,
        (SELECT type_name FROM activity_type WHERE activity_type.ac_type_id = accumulated.ac_type_id) as type_name
        FROM `accumulated` 
        WHERE user_id = '" . $data['user_id'] . "' ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getAccumulatedByRemaining($data = [])
    {
        $sql = "SELECT
            accumulated_id,
            COALESCE(SUM(accumulated_hours), 0) as sum_accumulated_hours,
            (SELECT COALESCE(hour_max, 0) FROM activity_category WHERE ac_category_id = '7') as maximum_hour,
            GREATEST((SELECT COALESCE(hour_max, 0) FROM activity_category WHERE ac_category_id = '7') - COALESCE(SUM(accumulated_hours), 0), 0) as remaining_hours
        FROM `accumulated` 
        WHERE user_id = '" . $data['user_id'] . "' ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //Create primary ID information
    function getAccumulatedLastID($data = [])
    {
        $sql = "SELECT CONCAT('" .$data['code']. "' ,
            LPAD(IFNULL(MAX(CAST(SUBSTRING(accumulated_id, 10, 3) AS SIGNED)), 0) +
            (SELECT COUNT(*) FROM accumulated a2 WHERE a2.accumulated_id LIKE ('%U%') AND a2.accumulated_id < accumulated.accumulated_id) + 1, 3, '0'),
            SUBSTRING(MD5(RAND()), 1, 6)) AS accumulated_maxcode
            FROM accumulated
            WHERE accumulated_id LIKE ('%U%')";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function insertAccumulated($data = [])
    {   
        $sql = " INSERT INTO accumulated ( 
            accumulated_id,
            accumulated_hours,
            user_id,
            ac_id,
            ac_category_id,
            ac_type_id,
            result_accumulated
        )  VALUES ('" .
        $data['accumulated_id'] . "','" .
        $data['accumulated_hours'] . "','" .
        $data['user_id'] . "','" .
        $data['ac_id'] . "','" .
        $data['ac_category_id'] . "','" .
        $data['ac_type_id'] . "','" .
        $data['result_accumulated'] . "' ) ";

    return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateAccumulatedByID($data = [])
    {
        $sql = " UPDATE calendar SET 
        ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "', 
        user_id = '" . static::$db->real_escape_string($data['user_id']) . "'
        WHERE activity_list_id = '" . static::$db->real_escape_string($data['activity_list_id']) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function updateAccumulatedByApprove($data = [])
    {
        $sql = " UPDATE activity SET 
        activity_approve_status = '" . static::$db->real_escape_string($data['activity_approve_status']) . "'
        WHERE ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "'
        ";
        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteAccumulatedByID($data = [])
    {
        $sql = " DELETE FROM calendar WHERE activity_list_id  = '" . static::$db->real_escape_string($data['activity_list_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
