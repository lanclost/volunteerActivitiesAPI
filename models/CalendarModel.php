<?PHP
require_once("BaseModel.php");

class CalendarModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getCalendarBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *, CONCAT(calendar_start,' ',calendar_end)AS dateCalendar
                FROM calendar  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getCalendarLastID($data = []){
        $sql = "SELECT CONCAT('" .$data['code']. "' , 
        LPAD(IFNULL(MAX(CAST(SUBSTRING(calendar_id,10,3) AS SIGNED)),0) + 1,3,'0' )) AS  calendar_maxcode 
        FROM calendar
        WHERE calendar_id LIKE ('%U%') ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getCalendarByID($code)
    {

        $sql = "SELECT * FROM calendar  WHERE  calendar_id = '" .
            $code['calendar_id'] . "' ";


        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function insertCalendar($data = [])
    {
        $sql = " INSERT INTO calendar ( 
                calendar_id,
                calendar_start,
            	calendar_end,
                calendar_detail
            )  VALUES ('" .
            $data['calendar_id'] . "','" .
            $data['calendar_start'] . "','" .
            $data['calendar_end'] . "','" .
            $data['calendar_detail'] . "' ) ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateCalendarBy($data = [])
    {
        $sql = " UPDATE calendar SET 
        
        calendar_start = '" . static::$db->real_escape_string($data['calendar_start']) . "',  
        calendar_end = '" . static::$db->real_escape_string($data['calendar_end']) . "', 
        calendar_detail = '" . static::$db->real_escape_string($data['calendar_detail']) . "'
        WHERE calendar_id = '" . static::$db->real_escape_string($data['calendar_id']) . "'";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteCalendarByID($data = [])
    {
        $sql = " DELETE FROM calendar WHERE calendar_id  = '" . static::$db->real_escape_string($data['calendar_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }


}
