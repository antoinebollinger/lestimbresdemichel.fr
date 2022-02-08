<?php 
function print_tab($tab){
	if (!is_array($tab)) return false;
	static $balise_fermante = array();
	$str = "<ul class=\"ul_session\">\r\n";
	$balise_fermante[] = "</ul>\r\n";
	foreach ($tab as $k => $v) {
		if(is_array($v)){
			$str .= "<li>[<b>$k</b>] => <em>array</em>\r\n";
			$balise_fermante[] = "</li>\r\n";
			$str .= print_tab($v);
		} else {
			$str .= "<li>[<b>$k</b>] => <span style=\"color:#4285f4;\">".htmlentities($v)."</span>";
			$balise_fermante[] = "</li>\r\n";
		}
		$str .= array_pop($balise_fermante);
	}
	$str .= array_pop($balise_fermante);
	return $str;
}
?>