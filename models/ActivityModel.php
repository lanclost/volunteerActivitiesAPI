<?PHP
require_once("BaseModel.php");

class ActivityModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    //Show activity information pending approval.
    function getActivityByWait($data = [])
    {
        $condition = '';
        if (isset($data['ac_name'])) {
            $condition .= " AND ac_name LIKE '%" . $data['ac_name'] . "%'";
        }
        if (isset($data['activity_approve_status'])) {
            $condition .= " AND activity_approve_status = '" . $data['activity_approve_status'] . "'";
        }
        $sql = "SELECT
                    activity.ac_id,
                    activity.ac_name,
                    activity.user_id,
                    CONCAT(activity.date_start, ' ', activity.date_end) AS DateRange,
                    CONCAT(activity.time_start, ' - ', activity.time_end) AS TimeRange,
                    (SELECT CONCAT(COUNT(activity_list.user_id), '/', activity.participants_num) 
                        FROM activity_list 
                        WHERE activity_list.ac_id  = activity.ac_id) as participating
                FROM activity
                WHERE 1" . $condition .
                " GROUP BY activity.ac_id";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    
    //Displays approved activity information.
    function getActivityBy($data = [])
    {
        $condition = '';
        if (isset($data['ac_name'])) {
            $condition .= " AND ac_name LIKE '%" . $data['ac_name'] . "%'";
        }
        if (isset($data['activity_approve_status'])) {
            $condition .=" AND activity_approve_status   ='".$data['activity_approve_status']."' ";
        }
        $sql = "SELECT
                activity.ac_id,
                activity.ac_name,
                activity.user_id,
                CONCAT(activity.date_start, ' ', activity.date_end) AS DateRange,
                CONCAT(activity.time_start, ' - ', activity.time_end) AS TimeRange,
                (SELECT CONCAT(COUNT(activity_list.user_id), '/', activity.participants_num) 
                    FROM activity_list 
                    WHERE activity_list.ac_id  = activity.ac_id) as participating
            FROM activity
            WHERE 1" . $condition .
                "AND ((CURDATE() = date_start AND DATE_ADD(CURTIME(), INTERVAL 7 HOUR) <= time_start) OR CURDATE() < date_start)
                GROUP BY activity.ac_id";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //Displays activity information in progress.
    function getActivityByConduct($data = [])
    {
        $condition = "";
        if (isset($data['ac_name'])) {
            $condition .= " AND ac_name LIKE '%" . $data['ac_name'] . "%'";
        }
        if (isset($data['activity_approve_status'])) {
            $condition .= " AND activity_approve_status = '" . $data['activity_approve_status'] . "'";
        }
        $sql = "SELECT
            activity.ac_id,
            activity.ac_name,
            activity.user_id,
            activity.date_start,
            activity.date_end,
            activity.time_start,
            activity.time_end,
            CONCAT(activity.date_start, ' ', activity.date_end) AS DateRange,
            CONCAT(activity.time_start, ' - ', activity.time_end) AS TimeRange,
            (SELECT CONCAT(COUNT(activity_list.user_id), '/', activity.participants_num) 
                FROM activity_list 
                WHERE activity_list.ac_id  = activity.ac_id) as participating
        FROM
            `activity`
        WHERE 1
            " . $condition ."
            AND CURDATE() BETWEEN date_start AND date_end
            AND (( DATE_ADD(CURTIME(), INTERVAL 7 HOUR) BETWEEN activity.time_start AND activity.time_end ) 
            OR ( DATE_ADD(CURTIME(), INTERVAL 7 HOUR) > activity.time_end AND CURDATE() < activity.date_end ))
        GROUP BY
            activity.ac_id, activity.ac_name, activity.user_id,
            activity.date_start, activity.date_end, activity.time_start, activity.time_end ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //Shows information about activities the user has participated in.
    function getActivityByAssociated($data = [])
    {
        $condition = "";
        if (isset($data['ac_name'])) {
            $condition .= " AND ac_name LIKE '%" . $data['ac_name'] . "%'";
        }
        if (isset($data['activity_approve_status'])) {
            $condition .= " AND activity_approve_status = '" . $data['activity_approve_status'] . "'";
        }
        if (isset($data['user_id'])) {
            $condition .= " AND activity_list.user_id = '" . $data['user_id'] . "'";
        }
        $sql = "SELECT
        activity.ac_id,
        activity.ac_name,
        activity.user_id,
        activity.date_start,
        activity.date_end,
        activity.time_start,
        activity.time_end,
        activity_list.user_id AS Associated,
        CONCAT(activity.date_start, ' ', activity.date_end) AS DateRange,
        CONCAT(activity.time_start, ' - ', activity.time_end) AS TimeRange,
        (SELECT CONCAT(COUNT(activity_list.user_id), '/', activity.participants_num) 
            FROM activity_list 
            WHERE activity_list.ac_id = activity.ac_id) AS participating
    FROM
        activity
        INNER JOIN activity_list ON activity.ac_id = activity_list.ac_id
    WHERE 1
    ".$condition."
    GROUP BY
        activity_list.ac_id;";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //Show information about completed activities.
    function getActivityByEnd($data = [])
    {
        $condition = "";
        if (isset($data['ac_name'])) {
            $condition .= " AND ac_name LIKE '%" . $data['ac_name'] . "%'";
        }
        if (isset($data['activity_approve_status'])) {
            $condition .= " AND activity_approve_status = '" . $data['activity_approve_status'] . "'";
        }
        $sql = "SELECT
                    activity.ac_id,
                    activity.ac_name,
                    activity.user_id,
                    CONCAT(activity.date_start,' ', activity.date_end) AS DateRange,
                    CONCAT(activity.time_start,' - ', activity.time_end) AS TimeRange,
                    (SELECT CONCAT(COUNT(activity_list.user_id), '/', activity.participants_num) 
                        FROM activity_list 
                        WHERE activity_list.ac_id  = activity.ac_id) as participating
                FROM
                    `activity`
                WHERE
                    ((CURDATE() = date_end AND DATE_ADD(CURTIME(), INTERVAL 7 HOUR) >= time_end) OR CURDATE() > date_end)" . $condition;
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getActivityBySucceed($data = [])
    {
        $condition = '';
        if (isset($data['ac_name'])) {
            $condition .= " AND ac_name LIKE '%" . $data['ac_name'] . "%'";
        }
        if (isset($data['activity_approve_status'])) {
            $condition .= " AND activity_approve_status = '" . $data['activity_approve_status'] . "'";
        }
        $sql = "SELECT
                    activity.ac_id,
                    activity.ac_name,
                    activity.user_id,
                    CONCAT(activity.date_start, ' ', activity.date_end) AS DateRange,
                    CONCAT(activity.time_start, ' - ', activity.time_end) AS TimeRange,
                    (SELECT CONCAT(COUNT(activity_list.user_id), '/', activity.participants_num) 
                        FROM activity_list 
                        WHERE activity_list.ac_id  = activity.ac_id) as participating
                FROM activity
                WHERE 1" . $condition .
                " GROUP BY activity.ac_id";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getActivityByID($data = [])
    {
        $sql = "SELECT *, CONCAT(first_name,' ',last_name)AS name FROM activity
        INNER JOIN activity_type ON activity.ac_type_id=activity_type.ac_type_id
        INNER JOIN activity_category ON activity.ac_category_id = activity_category.ac_category_id 
        INNER JOIN user ON activity.user_id = user.user_id
        WHERE ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "' ";

        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    //Shows all the user's participated activities.
    function getActivityByComeDateTime($data = [])
    {
        $sql = "SELECT
        activity.user_id,
        activity.ac_id,
        activity.ac_name,
        activity_list.user_id AS user_list,
        activity.date_start,
        activity.date_end,
        activity.time_start,
        activity.time_end
    FROM
        `activity`
        INNER JOIN activity_list ON activity_list.ac_id = activity.ac_id
    WHERE activity_list.user_id = '" . static::$db->real_escape_string($data['user_id']) . "'";
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
    function getActivityLastID($data = [])
    {
        $sql = "SELECT CONCAT('" .$data['code']. "' ,
        LPAD(IFNULL(MAX(CAST(SUBSTRING(ac_id, 10, 3) AS SIGNED)), 0) +
            (SELECT COUNT(*) FROM activity a2 WHERE a2.ac_id LIKE ('%U%') AND a2.ac_id < activity.ac_id) + 1, 3, '0'),
        SUBSTRING(MD5(RAND()), 1, 6)) AS activity_maxcode
        FROM activity
        WHERE ac_id LIKE ('%U%')";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function insertActivity($data = [])
    {
        $sql = " INSERT INTO activity ( 
                ac_id,
            	ac_name,
                location,
                participants_num,
                date_start,
                date_end, 
            	time_start,
            	time_end,
                hour_num,
                detail,
                image,
                ac_type_id,
                ac_category_id,
                user_id,
                activity_approve_status
            )  VALUES ('" .
            $data['ac_id'] . "','" .
            $data['ac_name'] . "','" .
            $data['location'] . "','" .
            $data['participants_num'] . "','" .
            $data['date_start'] . "','" .
            $data['date_end'] . "','" .
            $data['time_start'] . "','" .
            $data['time_end'] . "','" .
            $data['hour_num'] . "','" .
            $data['detail'] . "','" .
            $data['image'] . "','" .
            $data['ac_type_id']  . "','".
            $data['ac_category_id'] . "','" .
            $data['user_id'] . "','" . 
            $data['activity_approve_status'] . "') ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateActivityBy($data = [])
    {
        $sql = " UPDATE activity SET 
        ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "',  
        ac_name  = '" . static::$db->real_escape_string($data['ac_name']) . "',  
        location = '" . static::$db->real_escape_string($data['location']) . "',  
        participants_num	 = '" . static::$db->real_escape_string($data['participants_num']) . "', 
        date_start = '" . static::$db->real_escape_string($data['date_start']) . "', 
        date_end = '" . static::$db->real_escape_string($data['date_end']) . "', 
        time_start = '" . static::$db->real_escape_string($data['time_start']) . "', 
        time_end = '" . static::$db->real_escape_string($data['time_end']) . "', 
        hour_num = '" . static::$db->real_escape_string($data['hour_num']) . "', 
        detail = '" . static::$db->real_escape_string($data['detail']) . "', 
        image	 = '" . static::$db->real_escape_string($data['image']) . "', 
        ac_type_id = '" . static::$db->real_escape_string($data['ac_type_id']) . "', 
        ac_category_id = '" . static::$db->real_escape_string($data['ac_category_id']) . "',
        user_id = '" . static::$db->real_escape_string($data['user_id']) . "',
        activity_approve_status = '" . static::$db->real_escape_string($data['activity_approve_status']) . "'
        WHERE ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }
    function updateActivityByApprove($data = [])
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
    function deleteactivityByID($data = [])
    {
        $sql = " DELETE FROM activity WHERE ac_id  = '" . static::$db->real_escape_string($data['ac_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
