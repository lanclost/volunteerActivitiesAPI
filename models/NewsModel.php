<?PHP
require_once("BaseModel.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: *");

class NewsModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db, "utf8");
    }


    function getNewsBy($data = [])
    {
        $condition = '';


        $sql = "SELECT *
                FROM news  ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getNewsByID($code)
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
    function insertNews($data = [])
    {
        $sql = " INSERT INTO news 
            ( news_id, news_image)  
        VALUES 
            ('" .$data['news_id']  . "', '" .$data['news_image']  . "'  ) ";

        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function updateNewsBy($data = [])
    {
        $sql = " UPDATE news SET 
            news_image = '" . static::$db->real_escape_string($data['news_image']) . "' 
        WHERE news_id = '" . static::$db->real_escape_string($data['news_id']) . "'
        ";

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteNewsByID($data = [])
    {
        $sql = " DELETE FROM news WHERE news_id  = '" . static::$db->real_escape_string($data['news_id']) . "' ";
        return mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }
}
