<?php
include 'class.snoopy.php';
header("Content-Type: text/html; charset=UTF-8");

function stock_val($n){
	$snoopy=new snoopy;
	$o="";

	$html = '<tr>';
	$snoopy->fetch("http://stock.daum.net/item/main.daum?code=".$n);
	$txt=$snoopy->results;

	$rex="/\<h2 onclick=\"GoPage.+\"\>(.*)\<\/h2\>/";
	preg_match_all($rex,$txt,$o);
	//print_r($o[0][0]);
	$html .= '<td>'.$o[0][0].'</td>';

	$rex="/\<em class=\"curPrice.+\"\>(.*)\<\/em\>/";
	preg_match_all($rex,$txt,$o);
	//print_r($o[0][0]);
	$html .= '<td>'.$o[0][0].'</td>';
	$html .= '</tr>';
	return $html;
}

function get_stock(){
	$html = '';
	$html .= stock_val('004200');
	$html .= stock_val('123700');
	$html .= stock_val('083420');
	return $html;
}

?>



<table>
<tr>
	<td>name</td>
	<td>stock</td>
</tr>
<?=get_stock()?>
</table>