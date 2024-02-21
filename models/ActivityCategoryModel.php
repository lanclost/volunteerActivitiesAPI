<?PHP
require_once("BaseModel.php");

class ActivityCategoryModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getActivityCategoryBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *
                FROM activity_category WHERE NOT ac_category_id = '7'";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getActivityCategoryByID($data = [])
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


    function insertActivityCategory($data = [])
    {
        $sql = " INSERT INTO activity_category ( 
                category_num,
            	category_name,
                hour_max
            )  VALUES ('" .
            $data['category_num'] . "','" .
            $data['category_name'] . "','" .
            $data['hour_max'] . "' ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateActivityCategoryBy($data = [])
    {
        $sql = " UPDATE activity_category SET 
        
        category_num = '" . static::$db->real_escape_string($data['category_num']) . "',  
        category_name = '" . static::$db->real_escape_string($data['category_name']) . "', 
        hour_max = '" . static::$db->real_escape_string($data['hour_max']) . "'
       
        WHERE ac_category_id = '" . static::$db->real_escape_string($ac_category_id) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteActivityCategoryByID($data = [])
    {
        $sql = " DELETE FROM activity_category WHERE ac_category_id  = '" . static::$db->real_escape_string($data['ac_category_id ']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
