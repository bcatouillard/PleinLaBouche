<?php
Class Produit{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectByID;
    private $selectCount;
    private $selectLimit;
    private $selectAll;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select=$db->prepare("select * from produit order by designation");
        $this->insert=$db->prepare("insert into produit(id, designation,description,prix,nbPersonne) values(:id, :designation, :description,:prix,:nbPersonne)");
        $this->update=$db->prepare("update produit set designation=:designation, description=:description, prix=:prix, nbPersonne=:nbPersonne where id=:id");
        $this->delete=$db->prepare("delete from produit where id=:id");
        $this->selectByID=$db->prepare("select * from produit where id=:id");
        $this->selectCount=$db->prepare("select count(*) as nb from produit");
        $this->selectLimit = $db->prepare("select * from produit order by designation limit :inf,:limite");
        $this->selectAll = $db->prepare("select produit.id as id, designation, description, prix, nom, nbPersonne from produit left outer join photo on produit.id=photo.idProduit order by designation");
    }
    
    public function select(){
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($id, $designation,$description, $prix,$nbPersonne){
        $r = true;
        $this->insert->execute(array(':id'=>$id,':designation'=>$designation,':description'=>$description,':prix'=>$prix,':nbPersonne'=>$nbPersonne));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($id,$designation, $description, $prix,$nbPersonne){
        $r = true;
        $this->update->execute(array(':id'=>$id,':designation'=>$designation,':description'=>$description,':prix'=>$prix,':nbPersonne'=>$nbPersonne));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function selectByID($id){
        $this->selectByID->execute(array(':id'=>$id));
        if ($this->selectByID->errorCode()!=0){
             print_r($this->selectByID->errorInfo());  
        }
        return $this->selectByID->fetch();
    }
    
    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
             print_r($this->selectCount->errorInfo());  
        }
        return $this->selectCount->fetch();   
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
    
    public function selectAll(){
        $liste = $this->selectAll->execute();
        if ($this->selectAll->errorCode()!=0){
             print_r($this->selectAll->errorInfo());  
        }
        return $this->selectAll->fetchAll();
    }
}
?>