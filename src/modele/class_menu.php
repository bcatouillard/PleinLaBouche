<?php
Class Menu{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectByID;
    private $selectCount;
    private $selectLimit;
    private $selectByDate;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select=$db->prepare("select DATE_FORMAT(menu.date,'%d/%m/%Y') as dateC, menu.date, menu.description, menu.id, produit.designation, produit.description as descriptionP from menu inner join produit on menu.idProduit=produit.id order by date");
        $this->insert=$db->prepare("insert into menu(id, date, description, idProduit) values(:id, :date, :description, :idProduit)");
        $this->update=$db->prepare("update menu set date=:date, description=:description, idProduit=:idProduit where id=:id");
        $this->delete=$db->prepare("delete from menu where id=:id");
        $this->selectByID=$db->prepare("select DATE_FORMAT(menu.date,'%d/%m/%Y') as dateC, menu.date, menu.description, menu.id, menu.idProduit from menu where id=:id");
        $this->selectCount=$db->prepare("select count(*) as nb from menu");
        $this->selectLimit = $db->prepare("select * from menu order by date limit :inf,:limite");
        $this->selectByDate = $db->prepare("select * from menu inner join produit on menu.idProduit=produit.id left outer join photo on produit.id=photo.idProduit where date=substr(Now(),1,10)");
    }

    public function select(){
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($id,$date, $description, $idProduit){
        $r = true;
        $this->insert->execute(array(':id'=>$id,':date'=>$date,':description'=>$description,':idProduit'=>$idProduit));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($id,$date, $description, $idProduit){
        $r = true;
        $this->update->execute(array(':id'=>$id,':date'=>$date,':description'=>$description,':idProduit'=>$idProduit));
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
    
    public function selectByDate(){
        $liste = $this->selectByDate->execute();
        if ($this->selectByDate->errorCode()!=0){
             print_r($this->selectByDate->errorInfo());  
        }
        return $this->selectByDate->fetchAll();
    }
}
?>