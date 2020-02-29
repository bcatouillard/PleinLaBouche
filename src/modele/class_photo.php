<?php
Class Photo{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectCount;
    private $selectByProduit;
    private $selectByID;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select=$db->prepare("select * from photo");
        $this->insert=$db->prepare("insert into photo(id, nom, idProduit) values(:id, :nom, :idProduit)");
        $this->update=$db->prepare("update photo set nom=:nom where idProduit=:idProduit");
        $this->delete=$db->prepare("delete from photo where idProduit=:idProduit");
        $this->selectCount=$db->prepare("select count(*) as nb from photo");
        $this->selectByProduit=$db->prepare("select id, nom from photo where idProduit=:idProduit");
        $this->selectByID=$db->prepare("select * from photo where id=:id");
    }
    
    public function select(){
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($id, $nom, $idProduit){
        $r = true;
        $this->insert->execute(array(':id'=>$id,':nom'=>$nom, ':idProduit'=>$idProduit));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($nom, $idProduit){
        $r = true;
        $this->update->execute(array(':nom'=>$nom,':idProduit'=>$idProduit));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function delete($id){
        $r = true;
        $this->delete->execute(array(':idProduit'=>$id));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
             print_r($this->selectCount->errorInfo());  
        }
        return $this->selectCount->fetch();   
    }
    
    public function selectByProduit($idProduit){
        $this->selectByProduit->execute(array(':idProduit'=>$idProduit));
        if ($this->selectByProduit->errorCode()!=0){
             print_r($this->selectByProduit->errorInfo());  
        }
        return $this->selectByProduit->fetch();
    }
    
    public function selectByID($id){
        $this->selectByID->execute(array(':id'=>$id));
        if ($this->selectByID->errorCode()!=0){
             print_r($this->selectByID->errorInfo());  
        }
        return $this->selectByID->fetch();
    }
}
?>