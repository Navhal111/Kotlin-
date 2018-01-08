<?php
// A complex example that shows excel worksheets data appropiate to excel file

$excel_file = "test.xls";
$sheet_data = '';         // to store html tables with excel data, added in page
$table_output = array();  // store tables with worksheets data

$max_rows = 0;        // USE 0 for no max
$max_cols = 8;        // USE 0 for no max
$force_nobr = 0;      // USE 1 to Force the info in cells Not to wrap unless stated explicitly (newline)

require_once 'excel_reader.php';       // include the class
$excel = new PhpExcelReader();
$excel->setOutputEncoding('UTF-8');     // sets encoding UTF-8 for output data
$excel->read($excel_file);       // read excel file data
$nr_sheets = count($excel->sheets);       // gets the number of worksheets

// function used to add A, B, C, ... for columns (like in excel)
function make_alpha_from_numbers($number) {
  $numeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  if($number<strlen($numeric)) return $numeric[$number];
  else {
    $dev_by = floor($number/strlen($numeric));
    return make_alpha_from_numbers($dev_by-1) . make_alpha_from_numbers($number-($dev_by*strlen($numeric)));
  }
}

// create html table data
for($sheet=0;$sheet<$nr_sheets;$sheet++) {
  $table_output[$sheet] = '<table class="table_body"><tr><td>&nbsp;</td>';
  for($i=0;$i<$excel->sheets[$sheet]['numCols']&&($i<=$max_cols||$max_cols==0);$i++)  {
    $table_output[$sheet] .= '<td class="table_sub_heading">'. make_alpha_from_numbers($i) .'</td>';
  }
  for($row=1;$row<=$excel->sheets[$sheet]['numRows']&&($row<=$max_rows||$max_rows==0);$row++) {
    $table_output[$sheet] .= '<tr><td class="table_sub_heading">'. $row .'</td>';
    for($col=1;$col<=$excel->sheets[$sheet]['numCols']&&($col<=$max_cols||$max_cols==0);$col++) {
      if(isset($excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan']) && $excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'] >=1 && isset($excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan']) && $excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'] >=1) {
        $this_cell_colspan = ' colspan="'. $excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'] .'" ';
        $this_cell_rowspan = ' rowspan="'. $excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'] .'" ';

        for($i=1;$i<$excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];$i++)  {
          $excel->sheets[$sheet]['cellsInfo'][$row][$col+$i]['dontprint']=1;
        }
        for($i=1;$i<$excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'];$i++) {
          for($j=0;$j<$excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];$j++)  {
            $excel->sheets[$sheet]['cellsInfo'][$row+$i][$col+$j]['dontprint']=1;
          }
        }
      }
      else if(isset($excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan']) && $excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'] >=1) {
        $this_cell_colspan = ' colspan="'. $excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'] .'" ';
        $this_cell_rowspan = '';
        for($i=1;$i<$excel->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];$i++)  {
          $excel->sheets[$sheet]['cellsInfo'][$row][$col+$i]['dontprint']=1;
        }
      }
      else if(isset($excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan']) && $excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'] >=1) {
        $this_cell_colspan = "";
        $this_cell_rowspan = ' rowspan="'. $excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'] .'" ';
        for($i=1;$i<$excel->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'];$i++)  {
          $excel->sheets[$sheet]['cellsInfo'][$row+$i][$col]['dontprint']=1;
        }
      }
      else {
        $this_cell_colspan = "";
        $this_cell_rowspan = "";
      }
      if(!isset($excel->sheets[$sheet]['cellsInfo'][$row][$col]['dontprint'])) {
        $table_output[$sheet] .= '<td class="table_data" '. $this_cell_colspan. $this_cell_rowspan .'>';
        if($force_nobr == 1) $table_output[$sheet] .= '<nobr>';
        if(isset($excel->sheets[$sheet]['cells'][$row][$col])) $table_output[$sheet] .= nl2br(htmlentities($excel->sheets[$sheet]['cells'][$row][$col]));
        if($force_nobr == 1) $table_output[$sheet] .= '</nobr>';
        $table_output[$sheet] .= "</td>";
      }
    }
    $table_output[$sheet] .= "</tr>";
  }
  $table_output[$sheet] .= "</table>";
  $table_output[$sheet] = str_replace(array("\n", "\r", "\t"), '', $table_output[$sheet]);

  $sheet_data .= '<div class="hide_div" id="sheet_div_'. $sheet .'">'. $table_output[$sheet] ."</div>\n";
}

// Tabs witth WorkSheets Name
$sheet_tabs = '<table class="table_body" name="tab_table"><tr>';
for($sheet=0;$sheet<$nr_sheets;$sheet++) {
  $sheet_tabs .= '<td class="tab_base" id="sheet_tab_'. $sheet .'" onclick="changeWSTabs('. $sheet .');">'. $excel->boundsheets[$sheet]['name'] .'</td>';
}

$sheet_tabs .= '<tr></table>';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Example PHP Excel Reader</title>
<style>
.table_data {
  border:2px ridge #000;
  padding:1px 3px;
}
.tab_base {
  background:#C8DaDD;
  font-weight:bold;
  border:2px ridge #000;
  cursor:pointer;
  padding: 2px 4px;
}
.table_sub_heading {
  background:#CCCCCC;
  font-weight:bold;
  border:2px ridge #000;
  text-align:center;
}
.table_body {
  background:#F0F0F0;
  font-wieght:normal;
  font-family:Calibri, sans-serif;
  font-size:16px;
  border:2px ridge #000;
  border-spacing: 0px;
  border-collapse: collapse;
}
.tab_loaded {
  background:#222222;
  color:white;
  font-weight:bold;
  border:2px groove #000;
  cursor:pointer;
}
.hide_div { display:none;}
</style>
</head><body>
<?php
// adds tabs and Divs with tables with worksheets data
echo $sheet_tabs;
echo $sheet_data;
?>
<script>
// shows the Div with worksheet content according to clicked tab
function changeWSTabs(sheet) {
  for(i=0; i< <?php echo $nr_sheets; ?>; i++) {
    document.getElementById('sheet_tab_' + i).className = 'tab_base';
    document.getElementById('sheet_div_' + i).className = 'hide_div';
  }
  document.getElementById('sheet_tab_' + sheet).className = 'tab_loaded';
  document.getElementById('sheet_div_' + sheet).className = 'show_div';
}

// displays the first sheet 
changeWSTabs(0);
</script>
</body>
</html>