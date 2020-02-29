<?php
Class Appartenir{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectByProduit;
    private $selectCount;
    private $selectLimit;
    
    public function __construct($db){
        $this->db=$db;
        $this->select=$db->prepare("select * from appartenir");
        $this->insert=$db->prepare("insert into appartenir(idProduit, idCategorie) values(:idProduit, :idCategorie)");
        $this->delete=$db->prepare("delete from appartenir where idProduit=:idProduit");
        $this->update=$db->prepare("update appartenir set idCategorie=:idCategorie where idProduit=:idProduit");
        $this->selectCount=$db->prepare("select count(*) as nb from appartenir where idProduit=:idProduit");
        $this->selectByProduit=$db->prepare("select idCategorie from appartenir where idProduit=:idProduit");
    }
    
    public function select(){
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($idProduit,$idCategorie){
        $r = true;
        $this->insert->execute(array(':idProduit'=>$idProduit,':idCategorie'=>$idCategorie));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($idProduit, $idCategorie){
        $r = true;
        $this->update->execute(array(':idProduit'=>$idProduit,':idCategorie'=>$idCategorie));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function delete($idProduit){
        $r = true;
        $this->delete->execute(array(':idProduit'=>$idProduit));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function selectCount($idProduit){
        $this->selectCount->execute(array(':idProduit'=>$idProduit));
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
        return $this->selectByProduit->fetchAll();
    }
    
    public function selectLimit($inf, $limite){
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode()!=0){
             print_r($this->selectLimit->errorInfo());  
        }
        return $this->selectLimit->fetchAll();
    }
}