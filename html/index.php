<?
	header('Content-Type: text/html; charset=windows-1251'); 
	global $smarty, $dictionary, $elemet_types;
	//session_start();

	require_once("configure.php");
	require_once("includes/elementlist.php");
	require_once("includes/atom.php");
	require_once("includes/levellist.php");
	require_once("includes/transitionlist.php");
	require_once("includes/bibliolist.php");
	require_once("includes/spectrum.php");
	require_once("includes/user.class.php");

	///last-modified header generation
	$self_time = date("D, d M Y") . ", 00:00:00";
	$requested_time = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
	if($self_time == $requested_time)
	{
		Header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
		exit(); 
	}

	header("Last-Modified: " . $self_time . " GMT"); 
	// require_once("includes/counter.php");
	// $counter = new Counter;
	// $counter->Create();
	///

	//print_r($result);
	
	//���������� ����� �����������
	require_once("includes/localization.class.php");
	//���������� �������
	require_once("dictionary/dictionary.inc");
	
	$l10n = new Localisation($dictionary);
	$elements = new ElementList;
	//��������� ������� ��������� � ��������������� ������� � ��. ���������� = 0;	
	$elements->LoadPereodicTable($l10n->locale,0);
	$table=$elements->GetItemsArray();
	$smarty->assign('periodic_table',$table);
	
	//��������� ����. ���������� �������  ��. ���������� = 0;	
	//$elements->LoadMaxLevelsNUM(0);
	//$maxLevels=$elements->GetItemsArray();
	//$smarty->assign('MaxLevels',$maxLevels[0]["MAXLEVELS_NUM"]);
	
	
	//print_r($l10n->locale);
	//print_r($l10n->dictionary);
	//print_r($_COOKIE);
	//���� � ������ ������� ���� id �������� � ��� �����
	//print_r($_REQUEST);

	if((isset($_REQUEST['pagetype'])) && ($_REQUEST['pagetype']!="articles") 
	&& ($_REQUEST['pagetype']!="bibliography") && ($_REQUEST['pagetype']!="sources") 
	&& (isset($_REQUEST['element_id'])) && (is_numeric($_REQUEST["element_id"])))	
	{	
		$element_id=$_REQUEST['element_id'];
		
		//����
		$ion_list = new ElementList;
		$ion_list->Loadions($element_id);		

		//���� ������ �������� ������ �����
		$ions=$ion_list->GetItemsArray();
		if ($ions)	{
			//�������� ��� ��������
			$elname=$ions[0]['ELNAME'];		
			
			//�������� ������ ����� ���������(�� �������) � �������� ��� � smarty()			
			
			$smarty->assign('elemet_types', $elemet_types);
			//���� ����� ������ � �������� � ������� ��� ������
						
			$atom = new Atom;
			$atom->Load($element_id);
			
			$atom_sys=$atom->GetAllProperties();
			$smarty->assign('atom', $atom_sys);
			
			$ichi = '1S/'.$elname;
			$ichi .= !empty($atom_sys['IONIZATION']) ? "/q+".$atom_sys['IONIZATION'] : "";
			//$ichi_key = hash('sha256',$ichi);
			
			$smarty->assign('ichi', $ichi);
			//$smarty->assign('ichi_key', $ichi_key);
					
			
			//������
			$level_list = new LevelList;
			// ����� � ������ ����� �������
			$level_count = $level_list->LoadCount($element_id);			
			$smarty->assign('level_count', $level_count);

		
			//��������
			$transition_list = new TransitionList;
			// ����� � ������ ����� ���������
			$transition_count = $transition_list->LoadCount($element_id);
			$smarty->assign('transition_count', $transition_count);			
			
			//�������� ������������
//			$smarty->assign('book_count', 0);		
		
			$smarty->assign("bodyclass","elements");	
			
	
		}
	}

	//���� �� �������� ��� ��������
	if(isset($_REQUEST['pagetype'])){
	$pagetype=$_REQUEST['pagetype'];

	//���� ���� ���������� �� ���������� � �� - ��������� 
	if(isset($_REQUEST['interface'])){
		include "includes/auth.php"; 	
		$interface = "edit";	
		$page_type = "view";					
	
	}	else $interface="view";
	
	//���� ���� ���������� �� ��������
	if (isset($elname))		
	//� ����������� �� ���� �������� ��������� ������� ���������
	switch ($pagetype) 
	{	
		case "element": {
		
			// 	���� ������ ���������	
			$transition_list->LoadWithLevels($element_id);
			$transitions=$transition_list->GetItemsArray();
			// ���� json ������ ���� ���� � ����� ��� � ������
			$spectrum= new Spectrum();			
			$smarty->assign('spectrum_json',$spectrum->getSpectraSVG($transitions,0,1599900000));

    		$page_type="view_element.tpl"; 
    		$head="element_description";
    		$title="element_description";
    		$headline="element_description";
    		$bodyclass="element"; 
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";

			
			if (isset($_POST['export']))                                   
				$spectrum->export($transitions, $elname);
    		
    		break;
    	}

		case "compare" : {
			// 	���� ������ ���������	
			$transition_list->LoadWithLevels($element_id);
			$transitions=$transition_list->GetItemsArray();
			// ���� json ������ ���� ���� � ����� ��� � ������
			$spectrum= new Spectrum();			
			$smarty->assign('spectrum_json',$spectrum->getSpectraSVG($transitions,0,1599900000));

			$spectrum_json_uploaded = 0;
			
			if ((isset($_FILES['file']) && !$_FILES['file']['error']) || isset($_REQUEST['standard_file'])) {
				if (isset($_REQUEST['standard_file'])) {
					$file = $_REQUEST['standard_file'];

					if ($file == 1)
						$_FILES['file']['tmp_name'] = 'files/hghe500.csv';

				}  

				$spectrum_json_uploaded = $spectrum->parse_file($_FILES['file']);
			}
			
			
			$smarty->assign('spectrum_json_uploaded', $spectrum_json_uploaded);  

    		$page_type="compare_element.tpl"; 
    		$head="element_description";
    		$title="element_description";
    		$headline="element_description";
    		$bodyclass="element"; 
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";

			break;
		}

	    case "levels": {
	    
	    	//����� � ������ ������ �������
			$level_list->Load($element_id);
			
			$smarty->assign('level_list',$level_list->GetItemsArray());
				    
	    	//��������� ��� ������� � �������� ��������    		
			$page_type="view_levels.tpl"; 
    		$head="Atomic_levels";
    		$title="Atomic_levels";
    		$headline="Atomic_levels";
    		$bodyclass="levels"; 
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
		case "addlevels": {
	    //print_r($_GET);
			if (isset($_GET['attribute2']) || isset($_GET['attribute3'])){				
				$level_list->LoadFiltered($element_id,$_GET['attribute2'],$_GET['attribute3']);				
			} else $level_list->Load($element_id);
			
	    	//����� � ������ ������ �������		
			//$level_list->Load($element_id);
			if (isset($_GET['attribute1']))	$smarty->assign('transition_id',$_GET['attribute1']);
			if (isset($_GET['attribute2']))	$smarty->assign('position',$_GET['attribute2']);
			
			//print_r($level_list->GetItemsArray());
			$smarty->assign('level_list',$level_list->GetItemsArray());
				    
	    	//��������� ��� ������� � �������� ��������    		
			$page_type="add_levels.tpl"; 
    		$head="Atomic_levels";
    		$title="Atomic_levels";
    		$headline="Atomic_levels";
    		$bodyclass="levels"; 
    		$header_type="iframe_header.tpl";
    		$footer_type="iframe_footer.tpl";
    		break;
    	}
    	
		case "transitions": {

		    // ����� � ������ ������ ���������	
			$transition_list->LoadWithLevels($element_id);
			$smarty->assign('transition_list',$transition_list->GetItemsArray());
				
    		//��������� ��� ������� � �������� ��������    		
			$page_type="view_transitions.tpl"; 
    		$head="Atomic_transitions";
    		$title="Atomic_transitions";
    		$headline="Atomic_transitions";
    		$bodyclass="transitions"; 
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";
    		break;    		

    	}    	

    	case "diagramm": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type="view_diagramm.tpl"; 
    		$head="Grotrian_Charts";
    		$title="Grotrian_Charts";
    		$headline="Atomic_charts";
    		$bodyclass="diagramm";
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
	    case "chart": {
	    	$level_list->Load($element_id);
	    	$smarty->assign('level_list',json_encode($level_list->GetItemsArray()));
			
			$transition_list->LoadWithLevels($element_id);			
			$smarty->assign('transition_list',json_encode($transition_list->GetItemsArray()));
			
    		//��������� ��� ������� � �������� ��������    		
			$page_type="view_chart.tpl"; 
    		$head="Grotrian_Charts";
    		$title="Grotrian_Charts";
    		$headline="Atomic_charts";
    		$bodyclass="diagramm";
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";

    		
    		break;
    	}

	    case "newdiagramm": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type="view_new_diagramm.tpl"; 
    		$head="Grotrian_Charts";
    		$title="Grotrian_Charts";
    		$headline="Atomic_charts";
    		$bodyclass="new_diagramm";
    		$header_type="top_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
		
    	default: {
    		
    		//������
    		$level_list = new LevelList;
    		// ����� � ������ ����� �������
    		$level_count = $level_list->LoadCount($element_id);
    		$smarty->assign('level_count', $level_count);
    		
    		
    		//��������
    		$transition_list = new TransitionList;
    		// ����� � ������ ����� ���������
    		$transition_count = $transition_list->LoadCount($element_id);
    		$smarty->assign('transition_count', $transition_count);
    		
    		$page_type=$l10n->locale."/index.tpl"; 
    		$head="Information_system_Electronic_structure_of_atoms";
    		$title="About_project";
    		$headline="About_project";
    		$bodyclass="index"; 
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
	} else
	//���� ��� ���������� �� ��������
	switch ($pagetype) {
	    case "index": {
	    	
	    	//������
	    	$level_list = new LevelList;
	    	// ����� � ������ ����� �������
	    	$level_count = $level_list->LoadCount($element_id);
	    	$smarty->assign('level_count', $level_count);
	    	
	    	
	    	//��������
	    	$transition_list = new TransitionList;
	    	// ����� � ������ ����� ���������
	    	$transition_count = $transition_list->LoadCount($element_id);
	    	$smarty->assign('transition_count', $transition_count);
	    	
    		$page_type=$l10n->locale."/index.tpl"; 
    		$head="Information_system_Electronic_structure_of_atoms";
    		$title="About_project";
    		$headline="About_project";
    		$bodyclass="index"; 
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
		case "links": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type=$l10n->locale."/links.tpl"; 
    		$head="Other_resources_for_atomic_spectroscopy";
    		$title="Other_resources_for_atomic_spectroscopy";
    		$headline="Other_resources_for_atomic_spectroscopy";
    		$bodyclass="index";
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
		case "team": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type="ru/team.tpl"; 
    		$head="Project_team";
    		$title="Project_team";
    		$headline="Project_team";
    		$bodyclass="index";
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
    	
		case "sponsors": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type="ru/sponsors.tpl"; 
    		$head="Sponsors";
    		$title="Sponsors";
    		$headline="Sponsors";
    		$bodyclass="index";
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
		case "awards": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type="ru/awards.tpl"; 
    		$head="Awards";
    		$title="Awards";
    		$headline="Awards";
    		$bodyclass="awards";
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}   

    	case "periodictable": {
    		//��������� ��� ������� � �������� ��������    	
    			
			$page_type="view_periodictable.tpl"; 
    		$head="Periodic_Table";
    		$title="Periodic_Table";
    		$headline="Periodic_Table";
    		$bodyclass="periodictable";
    		$header_type="header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	} 

		case "login": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type="ru/login.tpl"; 
    		$head="Information_system_Electronic_structure_of_atoms";
    		$title="About_project";
    		$headline="About_project";
    		$bodyclass="index"; 
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
    	
		case "bibliography": {	
			$biblio_list = new BiblioList;	
			
			if(isset($_REQUEST['element_id']) && is_numeric($_REQUEST["element_id"])){
				$source_id=$_REQUEST["element_id"];

				$biblio_list->Load($source_id);			
				$BiblioItem = $biblio_list->GetItemsArray();
				$smarty->assign('BiblioItem',$BiblioItem[0]);	
				$biblio_list->GetAuthors($source_id);
				$smarty->assign('Authors',$biblio_list->GetItemsArray());				
				$page_type="view_bibliolink.tpl"; 
			} else {		
				
				//phpinfo();
				$biblio_list->LoadAll();			
				$smarty->assign('BiblioList',$biblio_list->GetItemsArray());   		
				$page_type="view_bibliography.tpl"; 
    			$head="Bibliography";
    			$title="Bibliography";
    			$headline="Bibliography";
    			$bodyclass="bibliography";
    			$header_type="header.tpl";
    			$footer_type="footer.tpl";
			}
    		break;
    	}  	
    	
		case "articles": {
    		//��������� ��� ������� � �������� ��������    		
			$page_type=$l10n->locale."/articles.tpl"; 
    		$head="Articles";
    		$title="Articles";
    		$headline="Articles";
    		$bodyclass="index";
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
			
    		if(isset($_REQUEST['element_id']) && is_numeric($_REQUEST["element_id"])){
    			$page_type=$l10n->locale."/articles/".$_REQUEST["element_id"].".tpl";
				if (!empty($page_type)){ 
    				$header_type="index_header.tpl";
    				$footer_type="footer.tpl";

    				if($_REQUEST["element_id"]>2) header('location: /'.$l10n->locale.'/articles');
				}
			}
    		break;
    	}

		default: {	

			//������
			$level_list = new LevelList;
			// ����� � ������ ����� �������
			$level_count = $level_list->LoadCount($element_id);
			$smarty->assign('level_count', $level_count);
			
			
			//��������
			$transition_list = new TransitionList;
			// ����� � ������ ����� ���������
			$transition_count = $transition_list->LoadCount($element_id);
			$smarty->assign('transition_count', $transition_count);
			
    		$page_type=$l10n->locale."/index.tpl"; 
    		$head="Information_system_Electronic_structure_of_atoms";
    		$title="About_project";
    		$headline="About_project";
    		$bodyclass="index"; 
    		$header_type="index_header.tpl";
    		$footer_type="footer.tpl";
    		break;
    	}
	}		
		$localDictionary=$l10n->localize;
		//������������ ������� �������� ���������� ����� � ������� ��� �����������
		$smarty->register_modifier("toRoman","numberToRoman");
		
		$smarty->assign('interface',$l10n->interface);
		$smarty->assign('locale',$l10n->locale);		
		$smarty->assign('l10n',$localDictionary);
		
		if(isset($head))$smarty->assign('head',$localDictionary[$head]);
		// var_dump($localDictionary);
		//if(isset($title))$smarty->assign("title",$localDictionary[$title]);
		// var_dump($title . '_title');
		if(isset($title))$smarty->assign("title",$localDictionary[$title . '_title'] . (isset($elname)?(" � ". $elname):("")));
		
		if(isset($headline))$smarty->assign('headline',$localDictionary[$headline]);		
		
		if (isset($element_id)) $smarty->assign('layout_element_id',$element_id);	
		if (isset($elname)) $smarty->assign('layout_element_name',$elname);
		if (isset($ions)) $smarty->assign('ions',$ions);
		
		if (isset($bodyclass))	$smarty->assign("bodyclass",$bodyclass);
		if (isset($pagetype))	$smarty->assign("pagetype",$pagetype);	
		
				
		if(isset($header_type)) $smarty->display("$interface/".$header_type);		
		$smarty->display("$interface/".$page_type);
		

		
		//print_r($_REQUEST);
		if(isset($header_type)) $smarty->display("$interface/".$footer_type);	
	}
?>
