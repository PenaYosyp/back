<?php
require_once('BaseList.php');
class PropertyList extends BaseList{
		public function add($name,$units){
			$id=++$this->index;
			$np=new Property($id,$name,$units);
			array_push($this->dataArray,$np);
			return $id;
		}
        public function getAllFromDatabase(){
            $sql = "SELECT * FROM property WHERE 1";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
            // output data of each row
                while($row = $result->fetch_assoc()) {
                    $np=new Property($row['id'],$row['name'],$row['units']);
                    array_push($this->dataArray,$np);
                }
            } else {
            echo "0 results";
            }
        }
		public function getFromDatabaseById($id){
            $sql = "SELECT * FROM property WHERE id=".$id;
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
		public function updateDatabaseById($id,$name,$units){
            $stmt = $this->conn->prepare("UPDATE property SET name=?,units=? WHERE id=?;");
            $stmt->bind_param("sss", $name,$units,$id);
            $stmt->execute();
        }
		public function deleteFromDatabase($id){
			$stmt = $this->conn->prepare("DELETE FROM opticaldrive_property WHERE property_id=?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();
			$stmt = $this->conn->prepare("DELETE FROM property WHERE id=?;");
            $stmt->bind_param("s", $id);
            $stmt->execute();
			
		}
		public function getDataAsArray(){
			$propArray=[];
			for ($i=0; $i<count($this->dataArray);$i++){
				array_push($propArray,$this->dataArray[$i]->getAsJSONObject());
			}
			return $propArray;
		}
		public function getDataAsInputBlock(){
			$result='<p>Характеристики</p>';
			for ($i=0; $i<count($this->dataArray);$i++){
				$result.=$this->dataArray[$i]->getDataAsInputField();
			}
			return $result;
		}
		public function getDataAsInputBlockWithValues($opticalDriveProps){
			$result='<p>Характеристики</p>';
			for ($i=0; $i<count($this->dataArray);$i++){
				$fieldHTML=$this->dataArray[$i]->getDataAsInputField();
				for ($j=0;$j<count($opticalDriveProps);$j++){
					if($opticalDriveProps[$j]['property_id']==$this->dataArray[$i]->getId()){
						$fieldHTML=$this->dataArray[$i]->getDataAsInputFieldWithValue($opticalDriveProps[$j]['value']);
						break;
					}
				}
				
				$result.=$fieldHTML;
			}
			
			return $result;
		}
        public function insertIntoDatabase($name,$units){
            $stmt = $this->conn->prepare("INSERT INTO property VALUES(DEFAULT,?,?);");
            $stmt->bind_param("ss", $name,$units);
            $stmt->execute();
            $last_id = $this->conn->insert_id;
            //$np=new Property($last_id,$name,$units);
            //array_push($this->dataArray,$np);
        }
		public function getDataAsXML(){
			header("Content-type: text/xml");
			$result='<?xml version="1.0" encoding="UTF-8"?>
			<properties>';
			for ($i=0; $i<count($this->dataArray);$i++){
				$result.=$this->dataArray[$i]->getDataAsXML();
			}
			$result.='</properties>';
			return $result;
		}
		public function importFromFile($fileName){
			$row = 1;
			if (($handle = fopen($fileName, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$this->add($data[0],$data[1]);
				$row++;	
			}
			fclose($handle);
			}
		}
		public function edit($id,$name,$units){
			for ($i=0; $i<count($this->dataArray);$i++){
				if ($this->dataArray[$i]->getId()==$id){
					$this->dataArray[$i]->edit($name,$units);
					break;
				}
			}
		}
	}
	class Property{
		private $id;
		private $name;
		private $units;
		public function __construct($id, $name,$units){
			$this->id=$id;
			$this->name=$name;
			$this->units=$units;		
		}
		public function getId(){
			return $this->id;
		}
		public function getAsJSONObject(){
			return get_object_vars($this);
		}
		public function edit($name,$units){
			$this->name=$name;
			$this->units=$units;	
		}
		public function getDataAsXML(){
			return "
				<property>
					<id>".$this->id."</id>
					<name>".$this->name."</name>
					<units>".$this->units."</units>
				</property>
			";
		}
		public function getDataAsInputField(){
			return '<p><input type="text" placeholder="'.$this->name.' ('.$this->units.')" name="prop_'.$this->id.'" required/></p>';
		}
		public function getDataAsInputFieldWithValue($value){
			return '<p><input type="text" value="'.$value.'" placeholder="'.$this->name.' ('.$this->units.')" name="prop_'.$this->id.'" required/></p>';
		}
		public function getDataAsTableRow(){
			return "
				<tr>
					<td>".$this->id."</td>
					<td>".$this->name."</td>
					<td>".$this->units."</td>
					<td>
					<a href='property.php?id=".$this->id."'>Редагувати</a>
					<form method='POST'>
						<input type='hidden' name='action' value='delete'/>
						<input type='hidden' name='id' value='".$this->id."'/>
						<button type='submit'>Видалити</button>	
					</form></td>
				</tr>
			";
		}
		public function displayInfo(){
			return $this->id.". ".$this->name." <i>(".$this->units.")</i></br>";
		}
		public function getDataAsCSVRow(){
			return '"'.addslashes($this->name).'","'.addslashes($this->units).'"'."\n";
		}
		public function __destruct(){
			echo "";	
		}
	}