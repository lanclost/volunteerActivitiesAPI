<?PHP
require_once("BaseModel.php");

class ActivityListModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getActivityListBy($data = [])
    {
        $condition = '';
        $sql = "SELECT *
                FROM activity_list  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //List of students participating in the activity
    function getActivityListByParticipants($data = [])
    {
        $sql = "SELECT activity_list.activity_list_id, 
        activity_list.ac_id, user.student_id,
        activity_list.user_id, department.department_name, 
        faculty.faculty_name,  
        CONCAT(prefix.prefix_name,'',user.first_name,' ',user.last_name)AS name
    FROM 
        `activity_list` 
    INNER JOIN 
        user ON user.user_id = activity_list.user_id
    INNER JOIN 
        department ON department.department_id  = user.department_id
    INNER JOIN 
        faculty ON faculty.faculty_id  = user.faculty_id
    INNER JOIN 
        prefix ON prefix.prefix_id  = user.prefix_id
     WHERE  
        ac_id = '" .$data['ac_id'] . "'  ";
           
    if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
        $data = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
        }
        $result->close();
        return $data;
        }
    }
    
    //Check the number of activity participants
    function getActivityListByComeActivity($data = [])
    {
        $sql = "SELECT activity_list.activity_list_id, activity_list.ac_id, activity.participants_num,
        COUNT(activity_list.user_id) AS user_number
        FROM `activity_list`
        INNER JOIN activity ON activity.ac_id = activity_list.ac_id
        WHERE activity_list.ac_id = '" .$data['ac_id']. "' 
        GROUP BY activity_list.user_id
        HAVING activity.participants_num >= user_number ";
           
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getActivityListByUserList($data = [])
    {
        $sql = "SELECT activity_list.activity_list_id, activity_list.ac_id, activity_list.user_id
        FROM `activity_list`
        WHERE activity_list.user_id = '" .$data['user_id']. "' ";
           
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //Add activitylist and create activity
    function insertActivityList($data = [])
    {   
        $sql = "";
        foreach($data['users'] as $row) {
            $sql = " INSERT INTO activity_list ( 
            	ac_id,
                user_id
            )  VALUES ('" .
            $data['ac_id'] . "','" .
            $row['user_id'] . "' ) ";  
            if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
                return true;
            } else {
                return false;
            }
        };   
        return true;
    }

    //Add activitylist
    function insertActivityListByComeActivity($data = [])
    {   
        $sql = " INSERT INTO activity_list (
            ac_id,
            user_id
        )  VALUES ('" .
        $data['ac_id'] . "','" .
        $data['user_id'] . "' ) ";

    return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateActivityListBy($data = [])
    {
        $sql = " UPDATE activity_list SET 
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

    function deleteActivityListByID($data = [])
    {
        $sql = " DELETE FROM activity_list WHERE activity_list_id  = '" . static::$db->real_escape_string($data['activity_list_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
