<?PHP
require_once("BaseModel.php");

class SendEvidenceModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }

    function getSendEvidenceBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *
                FROM send_evidence  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getSendEvidenceByID($code)
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


    function insertSendEvidence($data = [])
    {
        $sql = " INSERT INTO send_evidence ( 
                result_send_evidence,
                ac_id
            )  VALUES ('" .
            $data['result_send_evidence'] . "','" .
            $data['ac_id']  . "'  ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateSendEvidenceBy($evidence_id, $data = [])
    {
        $sql = " UPDATE send_evidence SET 
        
        result_send_evidence = '" . static::$db->real_escape_string($data['result_send_evidence']) . "',  
            ac_id = '" . static::$db->real_escape_string($data['ac_id']) . "'
        WHERE evidence_id = '" . static::$db->real_escape_string($evidence_id) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteSendEvidenceByID($code)
    {
        $sql = " DELETE FROM send_evidence WHERE evidence_id  = '$code' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
