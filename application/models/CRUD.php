<?php 
class CRUD extends CI_Model{
    /*
    * @TableName 
    */
    protected $primaryKey;
    protected $tableName;
    protected $tableColumns = array();

    function __construct($t=false){
        parent::__construct();

    }
    public function init($t){
        $this->tableName =$t; 
        //get all column names
        $Col_Query= $this->db->query("DESC `".$this->tableName."`");
        $cols = $Col_Query->row();
        while($cols != $Col_Query->row()){
            if ($cols->key !== 'PRI' || $cols->key !== 'UNI'){
                $this->tableColumns[] = $cols->field;
            }else{
                if ($cols->key == 'PRI'){
                    $this->primaryKey = $cols->field;
                }
            }
            
        }
    }
    /*
    * User can do 
    1. Login to the app 
    2. profile 
    3. logout 
    */

    public function add($data){
        $QueryCols = "";
        $QueryVals = "";
        foreach($data as $key=> $d){
            $QueryCols .= "`".$key."`,"; 
            $QueryVals .= "'".$d."',"; 
        }
        $QueryCols = rtrim($QueryCols,",");
        $QueryVals = rtrim($QueryVals,",");
        return $this->db->query("
                    INSERT INTO `".$this->tableName."`(
                        ".$QueryCols."
                    ) VALUES (
                        ".$QueryVals."
                    )"); 
    }
    public function remove($id){
        return $this->db->query("
            DELETE FROM `".$this->tableName."` WHERE `".$this->primaryKey."` = '".$id."'             
        ");
    }
    public function update($data){
        $id = $data['id'];
        unset($data['id']); 
        $Query = "";
        foreach($data as $key=> $d){
            $Query .= "`".$key."` = '".$d."',"; 
        }
        $Query = rtrim($Query,",");
        return $this->db->query("UPDATE `".$this->tableName."` SET ".$Query." WHERE `".$this->primaryKey."` = '".$id."'");
    }
    public function getById($id){
        $query = $this->db->query("SELECT * FROM `".$this->tableName."` WHERE `".$this->primaryKey."` = '".$id."'");
        return $query->row();
    }
    public function getAll(){
        $query = $this->db->query("SELECT * FROM `".$this->tableName."` WHERE `etms` = 0 ");
        return $query->row();
    }
}
?>