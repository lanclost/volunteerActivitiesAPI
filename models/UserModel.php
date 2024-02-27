<?PHP
require_once("BaseModel.php");

class UserModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getUserLogin($username = '', $password = '')
    {
        $sql = " SELECT *, CONCAT(first_name,' ',last_name)AS name,
 	    (SELECT type_name FROM user_type WHERE user_type.user_type_id  =user.user_type_id) as type_name,
        (SELECT department_name FROM department WHERE department.department_id  =user.department_id) as department_name,
        (SELECT faculty_name FROM faculty WHERE faculty.faculty_id  =user.faculty_id) as faculty_name,
        (SELECT prefix_name FROM prefix WHERE prefix.prefix_id  =user.prefix_id) as prefix_name
        FROM user
        WHERE username = '" . $username . "'  
        AND password = '" . $password . "'  
        ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data['data'] = $row;
            }
            $data['require'] = true;
            $result->close();
            return $data;
        } else {
            return $sql;
        }
    }
    function getUserByCheck($data = [])
    {
        $condition = '';
        if (isset($data['username'])) {
            $condition .= "AND (username LIKE ('%" . $data['username'] . "%'))";
        }
        $sql = "SELECT *, CONCAT(first_name,' ',last_name)AS name,
        (SELECT type_name FROM user_type WHERE user_type.user_type_id  =user.user_type_id) as type_name,
        (SELECT department_name FROM department WHERE department.department_id  =user.department_id) as department_name,
        (SELECT faculty_name FROM faculty WHERE faculty.faculty_id  =user.faculty_id) as faculty_name,
        (SELECT prefix_name FROM prefix WHERE prefix.prefix_id  =user.prefix_id) as prefix_name
        FROM user 
        WHERE user_approve_status = '".$data['user_approve_status'] ."'
        AND username = '".$data['username'] ."' ";
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
    function getUserLastID($data = [])
    {
        $sql = "SELECT CONCAT('" .$data['code']. "' ,
        LPAD(IFNULL(MAX(CAST(SUBSTRING(user_id, 10, 3) AS SIGNED)), 0) +
            (SELECT COUNT(*) FROM user a2 WHERE a2.user_id LIKE ('%U%') AND a2.user_id < user.user_id) + 1, 3, '0'),
        SUBSTRING(MD5(RAND()), 1, 6)) AS user_maxcode
        FROM user
        WHERE user_id LIKE ('%U%')";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getUserBy($data = [])
    {
        $condition = '';
        if (isset($data['first_name'])) {
            $condition .= " AND first_name LIKE '%" . $data['first_name'] . "%'";
        }
        if (isset($data['user_approve_status'])) {
            $condition .= " AND user_approve_status = '" . $data['user_approve_status'] . "'";
        }
        if (isset($data['user_id'])) {
            $condition .= " AND user_id = '" . $data['user_id'] . "'";
        }
        $sql = "SELECT *, CONCAT(first_name,' ',last_name)AS name FROM user 
        INNER JOIN user_type ON user.user_type_id=user_type.user_type_id 
        INNER JOIN prefix ON user.prefix_id=prefix.prefix_id  
        WHERE 1
            " . $condition;
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        } 
    }

    function getUserByusername($data = [])
    {
        $sql = "SELECT * FROM user WHERE username = '".$data['username'] ."' ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getUserByApprove($data = [])
    {
        $condition = '';
        if (isset($data['username'])) {
            $condition .= "AND (username LIKE ('%" . $data['username'] . "%'))";
        }
        $sql = "SELECT * FROM user WHERE user_approve_status = '".$data['user_approve_status'] ."'
        AND username = '".$data['username'] ."' ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getUserByID($data = [])
    {
        $sql = "SELECT *, CONCAT(first_name,' ',last_name)AS name,
            (SELECT type_name FROM user_type WHERE user_type.user_type_id  =user.user_type_id) as type_name,
            (SELECT department_name FROM department WHERE department.department_id  =user.department_id) as department_name,
            (SELECT faculty_name FROM faculty WHERE faculty.faculty_id  =user.faculty_id) as faculty_name,
            (SELECT prefix_name FROM prefix WHERE prefix.prefix_id  =user.prefix_id) as prefix_name
        FROM user 
        WHERE user_id = '" . static::$db->real_escape_string($data['user_id']) . "' ";

        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getUserByTypeName($data = [])
    {
        $condition = '';
        if (isset($data['first_name'])) {
            $condition .= " AND (first_name LIKE ('%" . $data['first_name'] . "%'))";
        }
        if (isset($data['user_approve_status'])) {
            $condition .=" AND user_approve_status   ='".$data['user_approve_status']."' ";
        }
        $sql = "SELECT 
		        user_id, 
		        student_id,
		        CONCAT(first_name,' ',last_name)AS name,
		        (SELECT type_name FROM user_type WHERE user_type.user_type_id  =user.user_type_id) as type_name,
     	        (SELECT department_name FROM department WHERE department.department_id  =user.department_id) as department_name,
                (SELECT faculty_name FROM faculty WHERE faculty.faculty_id  =user.faculty_id) as faculty_name,
                (SELECT prefix_name FROM prefix WHERE prefix.prefix_id  =user.prefix_id) as prefix_name
	        FROM 
    	        user 
	        WHERE 1
                ".$condition."
            GROUP BY 
     	        user.user_id, 
     	        user.student_id
            HAVING 
                type_name = '" . $data['type_name'] . "'";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function updateUserByPassword($data = [])
    {
        $sql = " UPDATE user SET 
            password = '" . static::$db->real_escape_string($data['password']) . "'
       
        WHERE username = '" . static::$db->real_escape_string($data['username']) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function insertUser($data = [])
    {
        $sql = " INSERT INTO user ( 
                user_id,
                student_id,
            	first_name,
                last_name,
            	yearclass,
                telephone,
                username, 
            	password,
            	prefix_id,
            	department_id,
                user_type_id,
                image,
                faculty_id,
                user_approve_status
            )  VALUES ('" .
            $data['user_id'] . "','" .
            $data['student_id'] . "','" .
            $data['first_name'] . "','" .
            $data['last_name'] . "','" .
            $data['yearclass'] . "','" .
            $data['telephone'] . "','" .
            $data['username'] . "','" .
            $data['password'] . "','" .
            $data['prefix_id'] . "','" .
            $data['department_id'] . "','" .
            $data['user_type_id'] . "','" .
            $data['image'] . "','" .
            $data['faculty_id'] . "','" .
            $data['user_approve_status']  . "'  ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateUserBy($data = [])
    {
        $sql = " UPDATE user SET 
        	student_id = '" . static::$db->real_escape_string($data['student_id']) . "',  
            first_name = '" . static::$db->real_escape_string($data['first_name']) . "', 
        	last_name = '" . static::$db->real_escape_string($data['last_name']) . "', 
            yearclass = '" . static::$db->real_escape_string($data['yearclass']) . "', 
            telephone = '" . static::$db->real_escape_string($data['telephone']) . "', 
            username = '" . static::$db->real_escape_string($data['username']) . "', 
            password = '" . static::$db->real_escape_string($data['password']) . "', 
        	prefix_id = '" . static::$db->real_escape_string($data['prefix_id']) . "', 
            department_id	 = '" . static::$db->real_escape_string($data['department_id']) . "', 
            user_type_id = '" . static::$db->real_escape_string($data['user_type_id']) . "', 
            image = '" . static::$db->real_escape_string($data['image']) . "',
            faculty_id = '" . static::$db->real_escape_string($data['faculty_id']) . "',
            user_approve_status = '" . static::$db->real_escape_string($data['user_approve_status']) . "'
       
        WHERE user_id = '" . static::$db->real_escape_string($data['user_id']) . "' ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }
    function updateUserByApprove($data = [])
    {
        $sql = " UPDATE user SET 
        user_approve_status = '" . static::$db->real_escape_string($data['user_approve_status']) . "'
        WHERE user_id = '" . static::$db->real_escape_string($data['user_id']) . "'
        ";
        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }
    function deleteUserByID($code)
    {
        $sql = " DELETE FROM user WHERE user_id  = '$code' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
