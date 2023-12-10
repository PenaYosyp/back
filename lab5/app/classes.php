<?php
	ini_set('display_errors', 1); 
	ini_set('display_startup_errors', 1); 
	error_reporting(E_ALL);

	abstract class BaseList {
		protected $dataArray;
		protected $index;
		public function __construct() {
			$this->dataArray=[];
			$this->index=0;
		}
		public function convertToJSON() {
			header("Content-type: application/json");
			$jsonArray=[];
			for($i = 0; $i < count($this->dataArray); $i++)
				array_push($jsonArray,$this->dataArray[$i]->getAsJSONObject());
			return json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
		}
		public function getTable() {
			$tableContent='';
			for($i = 0; $i < count($this->dataArray); $i++)
				$tableContent.=$this->dataArray[$i]->getDataAsTableRow();
			return $tableContent;
		}
		public function showAll() {
			for($i = 0; $i < count($this->dataArray); $i++)
				echo $this->dataArray[$i]->displayInfo();
		}
		public abstract function importFromFile($fileName);
		public function delete($id) {
			for($i = 0; $i < count($this->dataArray); $i++) {
				if($this->dataArray[$i]->getId() == $id) {
					array_splice($this->dataArray, $i, 1);
					break;
				}
			}
		}
		public function exportToFile($fileName) {
			if(($handle = fopen($fileName, "w")) !== FALSE) {
				for($i = 0; $i < count($this->dataArray); $i++)
					fwrite($handle, $this->dataArray[$i]->getDataAsCSVRow());
				fclose($handle);
			}
		}
	}

	class CategoryList extends BaseList {
		public function importFromFile($fileName) {
			$row = 1;
			if(($handle = fopen($fileName, "r")) !== FALSE) {
				while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$this->add($data[0]);
					$row++;	
				}
				fclose($handle);
			}
		}
		public function getDataAsXML() {
			header("Content-type: text/xml");
			$result='<?xml version="1.0" encoding="UTF-8"?>
			<categories>';
			for($i = 0; $i < count($this->dataArray); $i++)
				$result.=$this->dataArray[$i]->getDataAsXML();
			$result.='</categories>';
			return $result;
		}
		public function getDataAsSelect() {
			$result='<select name="category">';
			for($i = 0; $i < count($this->dataArray); $i++)
				$result.=$this->dataArray[$i]->getDataAsOption();
			$result.='</select>';
			return $result;
		}
		public function add($name) {
			$id=++$this->index;
			$nc = new Category($id, $name);
			array_push($this->dataArray, $nc);
			return $id;
		}
		public function edit($id, $name) {
			for($i = 0; $i < count($this->dataArray); $i++) {
				if($this->dataArray[$i]->getId() == $id) {
					$this->dataArray[$i]->edit($name);
					break;
				}
			}
		}
	}

	class Category {
		private $id;
		private $name;
		public function __construct($id, $name){
			$this->id = $id;
			$this->name = $name;		
		}
		public function getId() {
			return $this->id;
		}
		public function edit($name) {
			$this->name = $name;
		}
		public function getDataAsXML() {
			return "
				<category>
					<id>".$this->id."</id>
					<name>".$this->name."</name>
				</category>
			";
		}
		public function getDataAsOption() {
			return "<option value='".$this->name."'>".$this->name."</option>";
		}
		public function getDataAsTableRow() {
			return "
				<tr>
					<td>".$this->id."</td>
					<td>".$this->name."</td>
				</tr>
			";
		}
		public function displayInfo() {
			return $this->id.". ".$this->name."</br>";
		}
		public function getDataAsCSVRow() {
			return '"'.addslashes($this->name).'"'."\n";
		}
		public function __destruct() {
			echo "";	
		}
		public function getAsJSONObject() {
			return get_object_vars($this);
		}
	}

	class OpticalDriveList extends BaseList{
		public function add($name, $vendor, $category, $price, $properties) {
			$id=++$this->index;
			$nd = new OpticalDrive($id, $name, $vendor, $category, $price, $properties);
			array_push($this->dataArray, $nd);
			return $id;
		}
		public function getDataAsXML() {
			header("Content-type: text/xml");
			$result = '<?xml version="1.0" encoding="UTF-8"?>
			<opticalDrives>';
			for($i=0; $i<count($this->dataArray); $i++)
				$result.=$this->dataArray[$i]->getDataAsXML();
			$result.='</opticalDrives>';
			return $result;
		}
		public function importFromFile($fileName) {
			$row = 1;
			if(($handle = fopen($fileName, "r")) !== FALSE) {
				while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					eval('$propsArray='.$data[4].';');
					$this->add($data[0],$data[1],$data[2],$data[3],$propsArray);
					$row++;	
				}
			fclose($handle);
			}
		}
		public function edit($id, $name, $vendor, $category, $price, $properties) {
			for($i = 0; $i < count($this->dataArray); $i++) {
				if($this->dataArray[$i]->getId() == $id) {
					$this->dataArray[$i]->edit($name, $vendor, $category, $price, $properties);
					break;
				}
			}
		}
	}

	class OpticalDrive {
		private $id;
		private $name;
		private $vendor;
		private $category;
		private $price;
		private $properties;
		public function __construct($id, $name, $vendor, $category, $price, $properties) {
			$this->id = $id;
			$this->name = $name;	
			$this->vendor = $vendor;
			$this->category = $category;
			$this->price = $price;
			$this->properties = $properties;	
		}
		public function getId() {
			return $this->id;
		}
		public function getDataAsCSVRow() {
			return '"'.addslashes($this->name).'","'.addslashes($this->vendor).'","'.addslashes($this->category).'","'.addslashes($this->price).'","'.$this->getPropertiesForCSV().'"'."\n";
		}
		public function getDataAsXML() {
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
		public function getDataAsTableRow() {
			return "
				<tr>
					<td>".$this->id."</td>
					<td>".$this->name."</td>
					<td>".$this->vendor."</td>
					<td>".$this->category."</td>
					<td>".$this->price."</td>
					<td>".$this->displayProperties()."</td>
				</tr>
			";
		}
		public function edit($name, $vendor, $category, $price, $properties) {
			$this->name = $name;	
			$this->vendor = $vendor;
			$this->category = $category;
			$this->price = $price;
			$this->properties = $properties;	
		}
		public function getAsJSONObject() {
			return get_object_vars($this);
		}
		private function getPropertiesForCSV() {
			$result="[";
			foreach($this->properties as $key => $value) {
				$result.=  "'".addslashes($key) . "' => '" . addslashes($value)."'";
				$result.=",";
			}
			$result=substr_replace($result ,"", -1);
			$result.="]";
			return $result;
		}
		private function displayProperties() {
			$result='';
			foreach($this->properties as $key => $value) {
				$result.=  $key . ": " . $value;
			  	$result.=  "<br>";
			}
			return $result;
		}
		private function getPropertiesAsXML() {
			$result='';
			foreach($this->properties as $key => $value)
			  	$result.="<property><key>".$key."</key><value>".$value."</value></property>";
			return $result;
		}
		public function displayInfo() {
			return $this->id.". <b>".$this->vendor." ".$this->name."</b></br>
			Ціна: ".$this->price."<br>
			Категорія: ".$this->category."<br>". $this->displayProperties();
		}
		public function __destruct() {
			echo "";	
		}
	}

	class PropertyList extends BaseList {
		public function add($name, $units) {
			$id=++$this->index;
			$np = new Property($id,$name,$units);
			array_push($this->dataArray,$np);
			return $id;
		}
		public function getDataAsXML() {
			header("Content-type: text/xml");
			$result='<?xml version="1.0" encoding="UTF-8"?>
			<properties>';
			for ($i=0; $i<count($this->dataArray);$i++)
				$result.=$this->dataArray[$i]->getDataAsXML();
			$result.='</properties>';
			return $result;
		}
		public function importFromFile($fileName){
			$row = 1;
			if(($handle = fopen($fileName, "r")) !== FALSE) {
			while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$this->add($data[0],$data[1]);
				$row++;	
			}
			fclose($handle);
			}
		}
		public function edit($id, $name, $units) {
			for ($i = 0; $i < count($this->dataArray); $i++){
				if ($this->dataArray[$i]->getId() == $id){
					$this->dataArray[$i]->edit($name, $units);
					break;
				}
			}
		}
	}

	class Property{
		private $id;
		private $name;
		private $units;
		public function __construct($id, $name, $units){
			$this->id = $id;
			$this->name = $name;
			$this->units = $units;		
		}
		public function getId() {
			return $this->id;
		}
		public function getAsJSONObject() {
			return get_object_vars($this);
		}
		public function edit($name,$units) {
			$this->name = $name;
			$this->units = $units;	
		}
		public function getDataAsXML() {
			return "
				<property>
					<id>".$this->id."</id>
					<name>".$this->name."</name>
					<units>".$this->units."</units>
				</property>
			";
		}
		public function getDataAsTableRow() {
			return "
				<tr>
					<td>".$this->id."</td>
					<td>".$this->name."</td>
					<td>".$this->units."</td>
				</tr>
			";
		}
		public function displayInfo() {
			return $this->id.". ".$this->name." <i>(".$this->units.")</i></br>";
		}
		public function getDataAsCSVRow() {
			return '"'.addslashes($this->name).'","'.addslashes($this->units).'"'."\n";
		}
		public function __destruct() {
			echo "";	
		}
	}
?>
