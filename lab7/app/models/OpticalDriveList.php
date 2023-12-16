<?php
require_once('BaseList.php');
	class OpticalDriveList extends BaseList{
		public function add($name, $vendor, $category, $price, $properties){
			$id=++$this->index;
			$nb=new OpticalDrive($id,$name, $vendor, $category, $price, $properties);
			array_push($this->dataArray,$nb);
			return $id;
		}
        public function getAllFromDatabase(){
            $sql = "SELECT opticaldrive.*,category.name catname FROM opticaldrive 
            INNER JOIN category ON OpticalDrive.category_id=category.id  WHERE 1";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
            // output data of each row
                while($row = $result->fetch_assoc()) {
                    $nc=new OpticalDrive($row['id'],$row['name'],$row['vendor'],$row['price'],$row['catname'],$this->getOpticalDrivePropertiesById($row['id']));
                    array_push($this->dataArray,$nc);
                }
            } else {
            echo "0 results";
            }
        }
		public function getFromDatabaseById($id){
            $sql = "SELECT * FROM opticaldrive WHERE id=".$id;
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
            // output data of each row
                while($row = $result->fetch_assoc()) {
                    return $row;
                }
            } else {
            echo "0 results";
            }
        }
		public function updateDatabaseById($id,$name,$vendor,$price,$category){
            $stmt = $this->conn->prepare("UPDATE opticalDrive SET name=?,vendor=?,price=?,category_id=? WHERE id=?;");
            $stmt->bind_param("sssss", $name,$vendor,$price,$category,$id);
            $stmt->execute();
        }
		public function deleteFromDatabase($id){
			$stmt = $this->conn->prepare("DELETE FROM opticaldrive WHERE id=?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();
			$stmt = $this->conn->prepare("DELETE FROM opticaldrive_property WHERE opticalDrive_id=?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();
		}
		public function insertIntoDatabase($name, $vendor, $price, $category){
            $stmt = $this->conn->prepare("INSERT INTO opticalDrive VALUES(DEFAULT,?,?,?,?);");
            $stmt->bind_param("ssss", $name, $vendor, $price, $category);
            $stmt->execute();
            $last_id = $this->conn->insert_id;
            //$nb=new OpticalDrive($last_id,$name, $vendor, $price, $category,[]);
            //array_push($this->dataArray,$nb);
			return $last_id;
        }
		public function addOpticalDriveProperty($opticalDriveId, $propertyId, $value){
			$stmt = $this->conn->prepare("INSERT INTO opticaldrive_property VALUES(DEFAULT,?,?,?);");
            $stmt->bind_param("sss", $opticalDriveId, $propertyId, $value);
            $stmt->execute();
		}
		public function refreshOpticalDriveProperty($opticalDriveId, $propertyId, $value){
			$stmtDelete = $this->conn->prepare("DELETE FROM opticaldrive_property WHERE property_id=? AND opticaldrive_id=? ");
            $stmtDelete->bind_param("ss", $propertyId,$opticalDriveId);
            $stmtDelete->execute();
			$stmtAdd = $this->conn->prepare("INSERT INTO opticaldrive_property VALUES(DEFAULT,?,?,?);");
            $stmtAdd->bind_param("sss", $opticalDriveId, $propertyId, $value);
            $stmtAdd->execute();
		}
		
        public function getOpticalDrivePropertiesById($id){
            $sql = "SELECT opticaldrive_property.*, property.name, property.units 
            FROM opticaldrive_property INNER JOIN property 
            ON property.id=opticaldrive_property.property_id 
            WHERE opticaldrive_property.opticaldrive_id=".$id;
            $result = $this->conn->query($sql);
            $propsArray=[];
            if ($result->num_rows > 0) {
                // output data of each row
                    while($row = $result->fetch_assoc()) {
                        array_push($propsArray,$row);
                    }
                } else {
                //echo "0 results";
                }
            return $propsArray;
        }
        /*public function insertIntoDatabase($name){
            $stmt = $this->conn->prepare("INSERT INTO category VALUES(DEFAULT,?);");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $last_id = $this->conn->insert_id;
            $nc=new Category($last_id,$name);
            array_push($this->dataArray,$nc);
        }*/
		public function getDataAsXML(){
			header("Content-type: text/xml");
			$result='<?xml version="1.0" encoding="UTF-8"?>
			<opticalDrives>';
			for ($i=0; $i<count($this->dataArray);$i++){
				$result.=$this->dataArray[$i]->getDataAsXML();
			}
			$result.='</opticalDrives>';
			return $result;
		}
		public function importFromFile($fileName){
			$row = 1;
			if (($handle = fopen($fileName, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				eval('$propsArray='.$data[4].';');
				$this->add($data[0],$data[1],$data[2],$data[3],$propsArray);
				$row++;	
			}
			fclose($handle);
			}
		}
        
		public function edit($id,$name, $vendor, $category, $price, $properties){
			for ($i=0; $i<count($this->dataArray);$i++){
				if ($this->dataArray[$i]->getId()==$id){
					$this->dataArray[$i]->edit($name, $vendor, $category, $price, $properties);
					break;
				}
			}
		}
	}
	class opticalDrive{
		private $id;
		private $name;
		private $vendor;
		private $category;
		private $price;
		private $properties;
		public function __construct($id, $name, $vendor, $category, $price, $properties){
			$this->id=$id;
			$this->name=$name;	
			$this->vendor=$vendor;
			$this->category=$category;
			$this->price=$price;
			$this->properties=$properties;	
		}
		public function getId(){
			return $this->id;
		}
		public function getDataAsCSVRow(){
			return '"'.addslashes($this->name).'","'.addslashes($this->vendor).'","'.addslashes($this->category).'","'.addslashes($this->price).'","'.$this->getPropertiesForCSV().'"'."\n";
		}
		public function getDataAsXML(){
			return "
				<opticalDrive>
					<id>".$this->id."</id>
					<name>".$this->name."</name>
					<vendor>".$this->vendor."</vendor>
					<category>".$this->category."</category>
					<price>".$this->price."</price>
					<properties>".$this->getPropertiesAsXML()."</properties>
				</opticalDrive>
			";
		}
		public function getDataAsTableRow(){
			return "
				<tr>
					<td>".$this->id."</td>
					<td>".$this->name."</td>
					<td>".$this->vendor."</td>
					<td>".$this->category."</td>
					<td>".$this->price."</td>
					<td>".$this->displayProperties()."</td>
					<td>
					<a href='opticalDrive.php?id=".$this->id."'>Редагувати</a>
					<form method='POST'>
						<input type='hidden' name='action' value='delete'/>
						<input type='hidden' name='id' value='".$this->id."'/>
						<button type='submit'>Видалити</button>	
					</form></td>
				</tr>
			";
		}
		public function edit($name, $vendor, $category, $price, $properties){
			$this->name=$name;	
			$this->vendor=$vendor;
			$this->category=$category;
			$this->price=$price;
			$this->properties=$properties;	
		}
		public function getAsJSONObject(){
			return get_object_vars($this);
		}
		private function getPropertiesForCSV(){
			$result="[";
			foreach($this->properties as $key => $value) {
				$result.=  "'".addslashes($key) . "' => '" . addslashes($value)."'";
				$result.=",";
			}
			$result=substr_replace($result ,"", -1);
			$result.="]";
			return $result;
		}
		private function displayProperties(){
			$result='<i>Характеристики:</i></br>';
			foreach($this->properties as $property) {
				$result.=  $property['name'] . ": " . $property['value']." (". $property['units'].")";
			  	$result.=  "<br>";
			}
			return $result;
		}
		private function getPropertiesAsXML(){
			$result='';
			foreach($this->properties as $key => $value) {
			  	$result.="<property><key>".$key."</key><value>".$value."</value></property>";
			}
			return $result;
		}
		public function displayInfo(){
			return $this->id.". <b>".$this->vendor." ".$this->name."</b></br>
			Ціна: ".$this->price."<br>
			Категорія: ".$this->category."<br>". $this->displayProperties();
		}
		public function __destruct(){
			echo "";	
		}
	}
?>