<?PHP
require_once("BaseModel.php");

class UploadModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }


    function getPaperBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *
                FROM paper  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getPaperByID($data = [])
    {
        $sql = "SELECT * FROM paper  WHERE  paper_id = '" . static::$db->real_escape_string($data['paper_id']) . "' ";

        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getPaperLastID($data = []){
        $sql = "SELECT CONCAT('" .$data['code']. "' ,
        LPAD(IFNULL(MAX(CAST(SUBSTRING(paper_id, 10, 3) AS SIGNED)), 0) +
            (SELECT COUNT(*) FROM paper a2 WHERE a2.paper_id LIKE ('%U%') AND a2.paper_id < paper.paper_id) + 1, 3, '0'),
        SUBSTRING(MD5(RAND()), 1, 6)) AS paper_maxcode
        FROM paper
        WHERE paper_id LIKE ('%U%')";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function insertPaper($data = [])
    {
        $sql = " INSERT INTO paper ( 
                paper_id,
                paper_detail,
            	paper
            )  VALUES ('" .
            $data['paper_id'] . "','" .
            $data['paper_detail'] . "','" .
            $data['paper']  . "'  ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updatePaperBy($data = [])
    {
        $sql = " UPDATE paper SET 
        
        paper_detail = '" . static::$db->real_escape_string($data['paper_detail']) . "',  
            paper = '" . static::$db->real_escape_string($data['paper']) . "'
       
        WHERE paper_id = '" . static::$db->real_escape_string($paper_id) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deletePaperByID($data = [])
    {
        $sql = " DELETE FROM paper WHERE paper_id  = '" . static::$db->real_escape_string($data['paper_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}