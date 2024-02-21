<?PHP
require_once("BaseModel.php");

class EvidenceActivityModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getEvidenceActivityBy($data = [])
    {
        $sql = "SELECT *
                FROM evidence_activity  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getEvidenceActivityByID($code)
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
        //Create primary ID information
        function getEvidenceActivityID($data = [])
        {
            $sql = "SELECT CONCAT('" .$data['code']. "' ,
            LPAD(IFNULL(MAX(CAST(SUBSTRING(evidence_id, 10, 3) AS SIGNED)), 0) +
                (SELECT COUNT(*) FROM evidence_activity a2 WHERE a2.evidence_id LIKE ('%U%') AND a2.evidence_id < evidence_activity.evidence_id) + 1, 3, '0'),
            SUBSTRING(MD5(RAND()), 1, 6)) AS evidence_activity_maxcode
            FROM evidence_activity
            WHERE evidence_id LIKE ('%U%')";
            if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
                $result->close();
                return $data;
            }
        }

    function insertEvidenceActivity($data = [])
    {
        $sql = " INSERT INTO evidence_activity ( 
                evidence_id,
                evidence_file,
            	ac_id,
                user_id
            )  VALUES ('" .
            $data['evidence_id'] . "','" .
            $data['evidence_file'] . "','" .
            $data['ac_id'] . "','" .
            $data['user_id'] . "' ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateEvidenceActivityBy($evidence_id, $data = [])
    {
        $sql = " UPDATE evidence_activity SET 
        
        file_iamge = '" . static::$db->real_escape_string($data['file_iamge']) . "',  
        file_video = '" . static::$db->real_escape_string($data['file_video']) . "', 
        result_evidence = '" . static::$db->real_escape_string($data['result_evidence']) . "', 
        ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "'
       
        WHERE evidence_id = '" . static::$db->real_escape_string($evidence_id) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteEvidenceActivityByID($data = [])
    {
        $sql = " DELETE FROM evidence_activity WHERE evidence_id  = '" . static::$db->real_escape_string($data['evidence_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
