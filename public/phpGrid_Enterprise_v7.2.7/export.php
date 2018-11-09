<?php
use phpGrid\C_DataBase as C_DataBase;
use phpGrid\C_DataGrid as C_DataGrid;
use phpGrid\C_DataArray as C_DataArray;
use phpGrid\C_Utility as C_Utility;
use phpGrid\C_SessionMaker as C_SessionMaker;

require_once("phpGrid.php");

$session = C_SessionMaker::getSession(FRAMEWORK);

$gridName = isset($_GET['gn']) ? $_GET['gn'] : die('PHPGRID_ERROR: URL parameter "gn" is not defined');

$grid_sql	= $session->get(GRID_SESSION_KEY.'_'.$gridName.'_sql');
$sql_key	= $session->get(GRID_SESSION_KEY.'_'.$gridName.'_sql_key');
$sql_fkey	= $session->get(GRID_SESSION_KEY.'_'.$gridName.'_sql_fkey');
$sql_table	= $session->get(GRID_SESSION_KEY.'_'.$gridName.'_sql_table');  
$sql_filter	= $session->get(GRID_SESSION_KEY.'_'.$gridName.'_sql_filter');       
$db_connection = unserialize($session->get(GRID_SESSION_KEY.'_'.$gridName.'_db_connection'));
$export_type= isset($_GET['export_type']) ? $_GET['export_type'] : $session->get(GRID_SESSION_KEY.'_'.$gridName.'_export_type');
$col_titles	= unserialize($session->get(GRID_SESSION_KEY.'_'.$gridName.'_col_titles'));
$col_hiddens= array_keys(unserialize($session->get(GRID_SESSION_KEY.'_'.$gridName.'_col_hiddens')));	// extract the keys only from multiple dimension       
$col_edittypes	= unserialize($session->get(GRID_SESSION_KEY.'_'.$gridName.'_col_edittypes'));
$pdf_logo   = unserialize($session->get(GRID_SESSION_KEY.'_'.$gridName.'_pdf_logo'));

if(!is_array($grid_sql)){
// establish db connection
    $cn = $db_connection;
    if(empty($cn)){
        $db = new C_DataBase(PHPGRID_DB_HOSTNAME, PHPGRID_DB_USERNAME, PHPGRID_DB_PASSWORD, PHPGRID_DB_NAME, PHPGRID_DB_TYPE, PHPGRID_DB_CHARSET);
    }
    else {
        $db = new C_DataBase($cn["hostname"],$cn["username"],$cn["password"],$cn["dbname"],$cn["dbtype"],$cn["dbcharset"]);
    }
}else{
    $db = new C_DataArray($grid_sql);
}


// die if export wasn't enabled
if($export_type==null){
    die('Cannot export the grid. Please use enable_export() method to enable this feature.');
}



// --------------------- 4/20/2015 Richard ----------------
// if it is a masterdetail/subgrid, obtain the value of the foreign key to used later for filtering
$src = isset($_GET['src'])?$_GET['src']:'';
if($src=='md' || $src=='sg'){
    $fkey = $sql_fkey;//$_GET['fkey'];
    $fkey_value = $_GET['fkey_value'];

    if(C_Utility::is_debug()){
        echo 'fkey: ' ."\n";
        print_r($fkey);
        echo  "\n";
        echo 'fkey_value: ' ."\n";
        print_r($fkey_value);
    }
}
// create filter if exporting from a detail grid. Must check if the key is actual a database field
$sql_filter_md = '';
if($src=='md' || $src=='sg'){
    $sql_filter_md = $db->quote_field($grid_sql, $fkey, C_Utility::add_slashes($fkey_value));     // 1. master foreign key filter
}
// ------------ end of master detail filter ----------------



$sord = (isset($_GET['sord']))?$_GET['sord']:'asc'; 
$sidx = (isset($_GET['sidx']))?$_GET['sidx']:1; 
if(!$sidx) $sidx =1; 

$rs     = $db->select_limit($grid_sql, 1, 1);



$sqlWhere = "";
$searchOn = (isset($_REQUEST['_search']) && $_REQUEST['_search'] =='true')?true:false;
if($searchOn) {
    $col_dbnames = array();
    $col_dbnames = $db->get_col_dbnames($rs);
    foreach($_REQUEST as $key=>$value) {
        if(in_array($key, $col_dbnames)){
            $fm_type = $db->field_metatype($rs, $db->field_index($rs, $key));
            switch ($fm_type) {
                case 'I':
                case 'N':
                case 'R':
                case 'L':
                    $sqlWhere .= " AND ".$key." = ".$value;
                    break;
                default:
                    $sqlWhere .= " AND ".$key." LIKE '".$value."%'";
                    break;
            }    
        }
        
    }
	//advanced search    
	if(isset($_REQUEST['filters']) && $_REQUEST['filters'] !=''){
		$operation = array("eq"=>" ='%s' ","ne"=>" !='%s' ","lt"=>" < %s ",
			"le"=>" <= %s ","gt"=>" > %s ","ge"=>" >= %s ",
			"bw"=>" like '%s%%' ","bn"=>" not like '%s%%' " ,
			"in"=> " in (%s) ","ni"=> " not in (%s) ",
			"ew"=> " like '%%%s' ","en"=> " not like '%%%s' ",
			"cn"=> " like '%%%s%%' ","nc"=> " not like '%%%s%%' ");
		
		$filters = json_decode(stripcslashes($_REQUEST['filters']));
		$groupOp = $filters->groupOp;
		$rules = $filters->rules;
		
		for($i=0;$i<count($rules);$i++){                   

            $filter = $operation[$rules[$i]->op];

            // surround date fields with quotes for SQL date comparison
            $fm_type = $db->field_metatype($rs, $db->field_index($rs, $rules[$i]->field));  
            if( $fm_type == 'D' ){

                $dateOps = array('eq', 'ne', 'lt', 'le', 'gt', 'ge');                
                $op = $rules[$i]->op;                
                if( in_array( $op, $dateOps ) ){
                    $filter = str_replace("%s", "'%s'", $filter);
                } 

            }

			$sqlWhere .=  $groupOp . " ". $rules[$i]->field .
				 sprintf($filter, C_Utility::add_slashes($rules[$i]->data));
		}
	}
}


// Sheldon (earthlink) contribution
// remove leading logical operator
$posAND = strpos($sqlWhere,'AND ');
$posOR = strpos($sqlWhere,'OR ');
if ($posAND !== false) {
    $sqlWhere = substr_replace($sqlWhere,'',$posAND,strlen('AND '));
}
elseif ($posOR !== false) {
    $sqlWhere = substr_replace($sqlWhere,'',$posOR,strlen('OR '));
}
else { //do nothing
}


// set ORDER BY. Don't use if user hasn't select a sort
$sqlOrderBy = (!$sidx) ? "" : " ORDER BY $sidx $sord";

      
// ********* prepare the final query ***********************
$SQL = '';
if(!is_array($grid_sql)){
    if($sql_filter != '' && $searchOn){
        $SQL = $grid_sql .' WHERE '
                            .(($sql_filter_md!='') ? $sql_filter_md.' AND ' : '')
                            .$sql_filter .' AND ' .$sqlWhere 
                            .$sqlOrderBy;
    }elseif($sql_filter != '' && !$searchOn){
        $SQL = $grid_sql .' WHERE '
                            .(($sql_filter_md!='') ? $sql_filter_md.' AND ' : '') 
                            .$sql_filter 
                            .$sqlOrderBy;
    }elseif($sql_filter == '' && $searchOn){
        $SQL = $grid_sql .' WHERE '
                            .(($sql_filter_md!='') ? $sql_filter_md.' AND ' : '')
                            .$sqlWhere 
                            .$sqlOrderBy;
    }else{  // if($sql_filter == '' && !$searchOn){
        $SQL = $grid_sql .  (($sql_filter_md!='') ? ' WHERE '.$sql_filter_md : '')
                            .$sqlOrderBy;
    }
}
//echo 'sql_filter: '. $sql_filter;
//echo 'searchOn: '. $searchOn;
//echo $SQL; 

$result     = $db->db_query($SQL);
$row_count  = $db->num_rows($result);
$col_count  = $db->num_fields($result);

// $col_titles = array();
// $col_titles = $col_titles();
$j = 0;

switch(strtoupper($export_type)){
    case 'HTML': 
        header("Content-type: text/html");
        header("Content-disposition:  attachment; filename=Grid_". $gridName ."_".date("Y-m-d").".htm");
        header ('Expires: 0');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>'. "\n";
        
        echo '<table border="1" cellspacing="0" cellpadding="2">' ."\n";  
        echo '<thead>' ."\n";      
            echo '<tr style="background-color:black;color:white">';
            for($j = 0; $j < $db->num_fields($rs); $j++) {
                $col_name = $db->field_name($rs, $j);                             
                if(!in_array($col_name, $col_hiddens)){
                    if(isset($col_titles[$col_name])){
                        echo '<th>'. $col_titles[$col_name] .'</th>';                
                    }else{
                        echo '<th>'. $col_name .'</th>';                                
                    }
                    
                }        
            }
            echo '</tr>' ."\n";            
        echo '</thead>' ."\n";
            
        echo '<tbody>' ."\n";
        if(!is_array($grid_sql)){
            while($row = $db->fetch_array_assoc($result)) {
                echo '<tr>';
                for($j = 0; $j < $db->num_fields($result); $j++) {
                    $col_name = $db->field_name($result, $j);
                    if(!in_array($col_name, $col_hiddens)){                // if not hidden
                        echo '<td>'. text_by_edittype($col_edittypes, $col_name, $row[$col_name]) .'&nbsp;</td>';
                    }
                }
                echo '</tr>' ."\n";
            }
        }else{
            foreach($result as $row){
                echo '<tr>';
                for($j = 0; $j < $db->num_fields($result); $j++) {
                    $col_name = $db->field_name($row, $j);
                    // echo 'col_name: '. $col_name ."\n";
                    if(!in_array($col_name, $col_hiddens)){
                        echo '<td>'. text_by_edittype($col_edittypes, $col_name, $row[$col_name]) .'&nbsp;</td>';
                        // echo '<td>'. $row[$col_name] .'&nbsp;</td>';
                    }
                }
                echo '</tr>' ."\n";
            }
        }
        echo '</tbody>' ."\n";
        echo '</table>' ."\n";      
        
        echo '</body></html>';      
		
    break;

    case 'EXCEL':
        // Just convert the HTML output to Excel and save the day. 
        $html_table = '';
        $html_table .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>'. "\n";
        
        $html_table .= '<table border="1" cellspacing="0" cellpadding="2">' ."\n";  
        $html_table .= '<thead>' ."\n";      
            $html_table .= '<tr style="background-color:black;color:white">';
            for($j = 0; $j < $db->num_fields($rs); $j++) {
                $col_name = $db->field_name($rs, $j);                             
                if(!in_array($col_name, $col_hiddens)){
                    if(isset($col_titles[$col_name])){
                        $html_table .= '<th>'. $col_titles[$col_name] .'</th>';                
                    }else{
                        $html_table .= '<th>'. $col_name .'</th>';                                
                    }
                    
                }        
            }
            $html_table .= '</tr>' ."\n";            
        $html_table .= '</thead>' ."\n";
            
        $html_table .= '<tbody>' ."\n";
        if(!is_array($grid_sql)){
            while($row = $db->fetch_array_assoc($result)) {
                $html_table .= '<tr>';
                for($j = 0; $j < $db->num_fields($result); $j++) {
                    $col_name = $db->field_name($result, $j);
                    if(!in_array($col_name, $col_hiddens)){                // if not hidden
                        $html_table .= '<td>'. text_by_edittype($col_edittypes, $col_name, $row[$col_name]) .'&nbsp;</td>';
                    }
                }
                $html_table .= '</tr>' ."\n";
            }
        }else{
            foreach($result as $row){
                $html_table .= '<tr>';
                for($j = 0; $j < $db->num_fields($result); $j++) {
                    $col_name = $db->field_name($row, $j);
                    // echo 'col_name: '. $col_name ."\n";
                    if(!in_array($col_name, $col_hiddens)){
                        $html_table .= '<td>'. text_by_edittype($col_edittypes, $col_name, $row[$col_name]) .'&nbsp;</td>';
                        // echo '<td>'. $row[$col_name] .'&nbsp;</td>';
                    }
                }
                $html_table .= '</tr>' ."\n";
            }
        }
        $html_table .= '</tbody>' ."\n";
        $html_table .= '</table>' ."\n";      
        
        $html_table .= '</body></html>'; 


        $filename = "Grid_". $gridName ."_".date("Y-m-d") .".xls";

        // save $table inside temporary file that will be deleted later
        $tmpfile = tempnam(sys_get_temp_dir(), 'html');
        file_put_contents($tmpfile, $html_table);

        $objPHPExcel     = new PHPExcel();
        $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
        @$excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel); // @ to surpress any HTML warning
        $objPHPExcel->getActiveSheet()->setTitle($gridName); // Change sheet's title

        unlink($tmpfile); // delete temporary file because it isn't needed anymore

        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=". $filename);  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);

        // Creates a writer to output the $objPHPExcel's content
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;

    break;

	case 'CSV':		
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=Grid_". $gridName ."_".date("Y-m-d").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$rows_all = array();
		$row_header = array();
        for($j = 0; $j < $db->num_fields($rs); $j++) {
            $col_name = $db->field_name($rs, $j);
            if(!in_array($col_name, $col_hiddens)){
                if(isset($col_titles[$col_name])){
                    $row_header[] = $col_titles[$col_name];
                }else{
                    $row_header[] = $col_name;
                }

            }
        }
		$rows_all[] = $row_header;
        if(!is_array($grid_sql)){
            while($row = $db->fetch_array_assoc($result)) {
                $row_body = array();
                for($j = 0; $j < $db->num_fields($result); $j++) {
                    $col_name = $db->field_name($result, $j);
                    if(!in_array($col_name, $col_hiddens)){
                        $row_body[] = text_by_edittype($col_edittypes, $col_name, $row[$col_name]);
                        // $row_body[] = $row[$col_name];
                    }
                }
                $rows_all[] = $row_body;
            }
        }else{
            foreach($result as $row) {
                $row_body = array();
                for($j = 0; $j < $db->num_fields($result); $j++) {
                    $col_name = $db->field_name($row, $j);
                    if(!in_array($col_name, $col_hiddens)){
                        $row_body[] = text_by_edittype($col_edittypes, $col_name, $row[$col_name]);
                        // $row_body[] = $row[$col_name];
                    }
                }
                $rows_all[] = $row_body;
            }
        }

		outputCSV($rows_all);
	break;

	case 'PDF':
		$htmloutput = '';
        $column_count = $db->num_fields($rs);

        // PDF logo (jpg only)
        if(!empty($pdf_logo)){
            $img_path   = $pdf_logo[0];  //realpath('logos/phpgrid-logo.jpg');
            $imgsize    = getimagesize($img_path);

            $width      = (isset($pdf_logo[1]))?$pdf_logo[1]:$imgsize[0]/4;
            $height     = (isset($pdf_logo[2]))?$pdf_logo[2]:$imgsize[1]/4;
            $static_txt = (isset($pdf_logo[3]))?$pdf_logo[3]:'';

            $htmloutput .='<table border=0><tr><td align=r><img src='. $img_path .' width=' .intval($width) .' height='. intval($height) .' /><br><br></td></tr><tr><td>'. $static_txt .'</td></tr><tr><td></td></tr></table>';
        }
        
        $htmloutput .= '<table border=1>' ;
        $htmloutput .= '<tr bgcolor=#9CC8FF repeat>';	// repeat this row on every page (header)
        for($j = 0; $j < $column_count; $j++) {
            $col_name = $db->field_name($rs, $j);
            if(!in_array($col_name, $col_hiddens)){
                if(isset($col_titles[$col_name])){
                    $htmloutput .= '<td>'. $col_titles[$col_name] .'</td>';
                }else{
                    $htmloutput .= '<td>'. $col_name .'</td>';
                }

            }
        }
        $htmloutput .= '</tr>' ;

        if(!is_array($grid_sql)){
            while($row = $db->fetch_array_assoc($result)) {
                $htmloutput .= '<tr>';
                for($j = 0; $j < $column_count; $j++) {
                    $col_name = $db->field_name($result, $j);
                    if(!in_array($col_name, $col_hiddens))
                        $htmloutput .= '<td>'. text_by_edittype($col_edittypes, $col_name, $row[$col_name]) .'&nbsp;</td>';
                        // $htmloutput .= '<td>'. $row[$col_name] .'&nbsp;</td>';
                }
                $htmloutput .= '</tr>' ;
            }
        }else{
            foreach($result as $row) {
                $htmloutput .= '<tr>';
                for($j = 0; $j < $column_count; $j++) {
                    $col_name = $db->field_name($row, $j);
                    if(!in_array($col_name, $col_hiddens))
                        $htmloutput .= '<td>'. text_by_edittype($col_edittypes, $col_name, $row[$col_name]) .'&nbsp;</td>';
                        // $htmloutput .= '<td>'. $row[$col_name] .'&nbsp;</td>';
                }
                $htmloutput .= '</tr>' ;
            }
        }

		$htmloutput .= '</table>' ;
		
		$pdf = new PDFTable();
		$pdf->AddPage();
		$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
		$pdf->SetFont('DejaVu','',7);
		$pdf->htmltable($htmloutput);
		$pdf->output("Grid_". $gridName ."_".date("Y-m-d").".pdf", "I");
    break;

    case 'EXCELXML':
        header("Content-type: text/xml");
        header("Content-disposition:  attachment; filename=Grid_". $gridName ."_".date("Y-m-d").".xml");
        header ('Expires: 0');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');
        // Excel XML
        // ExpandedColumnCount and ExpandedRowCount must be greater than the actual # of cols and rows.
        echo '<?xml version="1.0"?>
            <?mso-application progid="Excel.Sheet"?>
            <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
             xmlns:o="urn:schemas-microsoft-com:office:office"
             xmlns:x="urn:schemas-microsoft-com:office:excel"
             xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
             xmlns:html="http://www.w3.org/TR/REC-html40">
             <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
              <Author>phpGrid.com</Author>
              <Created></Created>
              <LastSaved></LastSaved>
              <Version></Version>
             </DocumentProperties>
             <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
                <WindowHeight>768</WindowHeight>
                <WindowWidth>1024</WindowWidth>
                <WindowTopX>0</WindowTopX>
                <WindowTopY>0</WindowTopY>
                <ProtectStructure>False</ProtectStructure>
                <ProtectWindows>False</ProtectWindows>
            </ExcelWorkbook>
            <Styles>
                <Style ss:ID="Default" ss:Name="Normal">
                    <Alignment ss:Vertical="Bottom" />
                    <Borders/>
                    <Font ss:FontName="Arial" ss:Size="8" />
                    <Interior/>
                    <NumberFormat />
                    <Protection />
                </Style>
                <Style ss:ID="sHyperlink" ss:Name="Hyperlink">
                    <Font ss:Color="#0000FF" ss:Underline="Single" />
                </Style>
                <Style ss:ID="sDate">
                    <NumberFormat ss:Format="Short Date"/>
                </Style>
                <Style ss:ID="sNumber">
                    <NumberFormat/>
                </Style>                
                <Style ss:ID="sHeader">
                    <Font ss:Family="Arial" ss:Bold="1" />
                </Style>
                <Style ss:ID="sDecimal">
                    <NumberFormat ss:Format="Fixed"/>
                </Style>
            </Styles>';
        echo '<Worksheet ss:Name="Sheet1">
            <Table ss:ExpandedColumnCount="'. $col_count .'" 
              ss:ExpandedRowCount="'. ($row_count+1) .'" x:FullColumns="1"
              x:FullRows="1">';

        // grid header
        echo '<Row>';
        for($j = 0; $j < $col_count; $j++) {
            $col_name = $db->field_name($rs, $j);
            if(!in_array($col_name, $col_hiddens)){
                if(isset($col_titles[$col_name])){
                    echo '<Cell ss:StyleID="sHeader"><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $col_titles[$col_name])) .'</Data></Cell>';
                }else{
                    echo '<Cell ss:StyleID="sHeader"><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $col_name)) .'</Data></Cell>';
                }

            }
        }
        echo '</Row>' ."\n";

        // grid body
        $fm_type = 'C'; // field meta type
        if(!is_array($grid_sql)){
            while($row = $db->fetch_array_assoc($result)) {
                echo '<Row>';
                for($j = 0; $j < $col_count; $j++) {
                    $col_name = $db->field_name($result, $j);
                    if(!in_array($col_name, $col_hiddens)){
                        // $fm_type   = $db->field_metatype($result, $db->field_index($result, $col_name));
                        $display_text = text_by_edittype($col_edittypes, $col_name, $row[$col_name]);
                        echo '<Cell ss:StyleID="sDate"><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $display_text)) .'</Data></Cell>';
                        /*
                        switch($fm_type){
                            case 'D':
                                echo '<Cell ss:StyleID="sDate"><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $display_text)) .'</Data></Cell>';
                                break;
                            case 'I':
                            case 'R':
                                echo '<Cell ss:StyleID="sNumber"><Data ss:Type="Number">'. $row[$col_name] .'</Data></Cell>';
                                break;
                            case 'N':
                                echo '<Cell ss:StyleID="sDecimal"><Data ss:Type="Number">'. $row[$col_name] .'</Data></Cell>';
                                break;
                            default:
                                echo '<Cell><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $display_text)) .'</Data></Cell>';
                        }
                        */
                    }
                }
                echo '</Row>' ."\n";
            }
        }else{
            foreach($result as $row) {
                echo '<Row>';
                for($j = 0; $j < $col_count; $j++) {
                    $col_name = $db->field_name($row, $j);
                    if(!in_array($col_name, $col_hiddens)){
                        // $fm_type   = $db->field_metatype($result, $db->field_index($result, $col_name));
                        $display_text = text_by_edittype($col_edittypes, $col_name, $row[$col_name]);
                        echo '<Cell ss:StyleID="sDate"><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $display_text)) .'</Data></Cell>';
                        /*
                        switch($fm_type){
                            case 'D':
                                echo '<Cell ss:StyleID="sDate"><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $row[$col_name])) .'</Data></Cell>';
                                break;
                            case 'I':
                            case 'R':
                                echo '<Cell ss:StyleID="sNumber"><Data ss:Type="Number">'. $row[$col_name] .'</Data></Cell>';
                                break;
                            case 'N':
                                echo '<Cell ss:StyleID="sDecimal"><Data ss:Type="Number">'. $row[$col_name] .'</Data></Cell>';
                                break;
                            default:
                                echo '<Cell><Data ss:Type="String">'. str_replace('>', '&gt;', str_replace('<', '&lt;', $row[$col_name])) .'</Data></Cell>';
                        }
                        */
                    }
                }
                echo '</Row>' ."\n";
            }
        }


        echo '</Table>';
        echo '<WorksheetOptions 
              xmlns="urn:schemas-microsoft-com:office:excel">
                <Print>
                    <ValidPrinterInfo />
                    <HorizontalResolution>800</HorizontalResolution>
                    <VerticalResolution>0</VerticalResolution>
                </Print>
                <Selected />
                <Panes>
                    <Pane>
                        <Number>3</Number>
                        <ActiveRow>1</ActiveRow>
                    </Pane>
                </Panes>
                <ProtectObjects>False</ProtectObjects>
                <ProtectScenarios>False</ProtectScenarios>
            </WorksheetOptions>
        </Worksheet>
        </Workbook>';    
    break;
}

function outputCSV($data) {
	$outstream = fopen("php://output", "w");
	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals); // add parameters if you want
	}
	array_walk($data, "__outputCSV", $outstream);
	fclose($outstream);
}

// e.g. str_kvpair: 1:San Francisco;2:Boston;3:NYC;test:Paris;5:Tokyo;6:Sydney;7:London
// convert to array e.g. $arr_kvpair[1] = 'San ...'
function array_kvpair($str_kvpair){
    $tmp = explode(';', $str_kvpair);
    $arr_kvpair = array();
    foreach($tmp as $key => $value){
        $k = substr($value, 0, strpos($value, ':'));
        $v = substr($value, strpos($value, ':')+1);
        $arr_kvpair[$k] = $v;
    }

    return $arr_kvpair;
}

// For now, the only edit type we need to deal with here is "select" 
function text_by_edittype(&$col_edtypes, $col, $select_key){
    $display_text = '';

    if(isset($col_edtypes[$col])){
        if($col_edtypes[$col]['type']=='select'){    // if edit type is select
            $arr_kvpair = array_kvpair($col_edtypes[$col]['value']);
            // print_r($arr_kvpair);
            if(isset($arr_kvpair[$select_key])){
                $display_text = $arr_kvpair[$select_key];
            }else{
                $display_text = '';
            }
        }else{
            $display_text = $select_key;
        }
    }else{
        $display_text = $select_key;    // default
    }

    return $display_text;
}
          
// free resource       
$db = null;