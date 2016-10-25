<?
require_once("locallist.php");

class LevelList extends LocalList
{
	    
	function Load($element_id)
	{	
		/*$query = "SELECT class_levels.*, [Grotrian].[dbo].GetCfgType(class_levels.CONFIG) AS CONFIG_TYPE , class_elements.ID as elementID FROM class_levels 
		JOIN links ON links.TO_ELEMENT_ID=class_levels.ID
		JOIN class_elements ON links.FROM_ELEMENT_ID=class_elements.ID
		WHERE class_elements.ID='$element_id' ORDER BY ID";*/

		//$query = "SELECT *, GetCfgType(CONFIG) AS config_type FROM LEVELS WHERE ID_ATOM='$element_id' ORDER BY ID";
		$query = "SELECT LEVELS.* ,dbo.GetCfgType(CONFIG) AS config_type, dbo.ConcatSourcesID(ID,'L') AS SOURCE_IDS FROM LEVELS WHERE  ID_ATOM='$element_id' ORDER BY ENERGY asc";
		
		$this->LoadFromSQL($query);
	}
	
/*
function Load($element_id)
	{	
				
$query = "ALTER VIEW cfg1 AS
SELECT class_levels.*,[Grotrian].[dbo].GetCfgType(class_levels.CONFIG) AS CONFIG_TYPE , class_elements.ID as elementID FROM class_levels 
		JOIN links ON links.TO_ELEMENT_ID=class_levels.ID
		JOIN class_elements ON links.FROM_ELEMENT_ID=class_elements.ID
		WHERE class_elements.ID='8728'; 
		
ALTER VIEW cfg2 AS
SELECT  CONFIG_TYPE, [Grotrian].[dbo].CountCfg(CONFIG_TYPE) AS CFGT  
FROM cfg1 GROUP BY  CONFIG_TYPE;   
 

SELECT cfg1.*, CASE WHEN (cfg2.CFGT=1) THEN  cfg1.CONFIG ELSE cfg1.CONFIG_TYPE END AS CFG_TYPE
FROM  cfg1 LEFT JOIN  cfg2 ON  cfg1.CONFIG_TYPE= cfg2.CONFIG_TYPE";
		
		$this->LoadFromSQL($query);
	}

*/
	
	function LoadCount($element_id = null)
	{
		if ($element_id != null)
		{
			$stmt = GetStatement();
/*			$query = "SELECT count(class_levels.ID) FROM class_levels 
				JOIN links ON links.TO_ELEMENT_ID=class_levels.ID
				JOIN class_elements ON links.FROM_ELEMENT_ID=class_elements.ID
				WHERE class_elements.ID='$element_id' "; */
			
			$query = "SELECT count(ID) REPEATABLE FROM LEVELS WHERE ID_ATOM='$element_id' ";
			
			return $stmt->FetchField($query);
		}
		return $this->GetTotalRecords('LEVELS');
	}
	
	function Save($post){
		$count=$post['count'];
		$query="";
		for ($i=0; $i<$count; $i++) {
				$level_id = $post['row_id'][$i];
				$level_config   = empty($post['level_config'][$i])     ? "" : "'".$post['level_config'][$i]."'";
				$termSecondpart = ($post['termSecondpart'][$i] == "")  ? 'NULL' : "'".$post['termSecondpart'][$i]."'";
				$termPrefix     = ($post['termPrefix'][$i] == "")      ? 'NULL' : "'".$post['termPrefix'][$i]."'";
				$termFirstpart  = empty($post['termFirstpart'][$i])    ? " " : $post['termFirstpart'][$i];
				$termMultiply   = ($post['termMultiply'][$i]<>"")      ? 1 : 0;
				$j              = ($post['j'][$i] == "")               ? 'NULL' : "'".$post['j'][$i]."'";
				$energy         = ($post['energy'][$i] == "")          ? 'NULL' : $post['energy'][$i];
				$lifetime       = ($post['lifetime'][$i] == "")        ? 'NULL' : $post['lifetime'][$i];		
				
				$query .= " UPDATE LEVELS SET [CONFIG] = ".$level_config." ,[ENERGY] = ".$energy.",[LIFETIME] = ".$lifetime.", [J] = ".$j.", [TERMSECONDPART] = ".$termSecondpart." , [TERMPREFIX] = ".$termPrefix." ,[TERMMULTIPLY] = ".$termMultiply.", [TERMFIRSTPART] = '".$termFirstpart."' WHERE ID =".$level_id;
			}		
		//echo $query;
		$this->LoadFromSQL($query);
	}
	
/*	function Delete($post){
		$count=$post['count'];
		for ($i=0; $i<$count; $i++) {
				$level_id = $post['level_id'][$i];
				$query .= " DELETE FROM [LEVELS] WHERE ID =".$level_id;			
		}
		//echo $query
		$this->LoadFromSQL($query);
	}*/
	
	function Delete($post){
		foreach ($post['row_id'] as $key=>$level_id) {
			$query .= " DELETE FROM [LEVELS] WHERE ID =".$level_id;			
		}
		$this->LoadFromSQL($query);
	}
	
/*	function Create($atom_id)
	{		
		$query = "INSERT INTO LEVELS ([ID],[ID_ATOM]) SELECT MAX(ID)+1,".$atom_id." FROM LEVELS
		SELECT MAX(ID) AS ID FROM LEVELS WHERE ID_ATOM=".$atom_id;
	 	$this->LoadFromSQL($query);				
	}*/


	function Create($atom_id)
	{	
		$query = "INSERT INTO LEVELS ([ID_ATOM]) VALUES (".$atom_id.")		
		SELECT MAX(ID) AS ID FROM LEVELS WHERE ID_ATOM=".$atom_id;
	 	$this->LoadFromSQL($query);				
	}
	
	
	
/*	function ApplySources($post){
		
		foreach ($post['level_id'] as $level_key=>$level_id){
			foreach ($post['source_id'] as $source_key=>$source_id)
			$query.=" IF ((SELECT ID_RECORD FROM BIBLIOLINKS WHERE [ID_RECORD]=".$level_id." AND [ID_SOURCE]=".$source_id." AND [RECORDTYPE] = ".$post['type'].")= NULL) BEGIN INSERT INTO BIBLIOLINKS ([RECORDTYPE],[ID_RECORD],[ID_SOURCE]) VALUES (".$post['type'].",".$level_id.",".$source_id.") END";
		}
		
		//echo $query;
		$this->LoadFromSQL($query);
	}

	function ApplyRemovingSources($post){ 
		foreach ($post['level_id'] as $level_key=>$level_id){
			foreach ($post['source_id'] as $source_key=>$source_id)
			$query.=" DELETE FROM [BIBLIOLINKS] WHERE [ID_RECORD]=".$level_id." AND [ID_SOURCE]=".$source_id." AND [RECORDTYPE]=".$post['type'];
		}
		//echo $query;
		$this->LoadFromSQL($query);
	}
	
	function GetSourceIDs($level_id)
	{
		$query = "SELECT dbo.ConcatSourcesID(ID,'L') AS SOURCE_IDS FROM LEVELS WHERE  ID=".$level_id." ORDER BY ID asc";		
		$this->LoadFromSQL($query);
	}*/	
	
	function LoadFiltered($atom_id,$position,$level_id)
	{		
		//echo "levId=".$level_id;
		$position = ($position == 'lower') ? "<" : ">";
		if (empty($level_id)) $query = "SELECT LEVELS.* ,dbo.GetCfgType(CONFIG) AS config_type, dbo.ConcatSourcesID(ID,'L') AS SOURCE_IDS FROM LEVELS WHERE  ID_ATOM=".$atom_id." ORDER BY ENERGY asc";
		else $query = "SELECT LEVELS.* ,dbo.GetCfgType(CONFIG) AS config_type, dbo.ConcatSourcesID(ID,'L') AS SOURCE_IDS FROM LEVELS WHERE  ID_ATOM=".$atom_id."  AND TERMMULTIPLY != (Select TERMMULTIPLY FROM LEVELS WHERE ID = ".$level_id.") AND ENERGY ".$position." (Select ENERGY FROM LEVELS WHERE ID = ".$level_id.") ORDER BY ENERGY asc";
	
		//echo $query; 			
		$this->LoadFromSQL($query);
	}


}
?>