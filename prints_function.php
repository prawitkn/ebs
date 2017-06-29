<?php
function toThaiNumber($str){
	$str = str_replace("1","๑",$str);
	$str = str_replace("2","๒",$str);
	$str = str_replace("3","๓",$str);
	$str = str_replace("4","๔",$str);
	$str = str_replace("5","๕",$str);
	$str = str_replace("6","๖",$str);
	$str = str_replace("7","๗",$str);
	$str = str_replace("8","๘",$str);
	$str = str_replace("9","๙",$str);
	$str = str_replace("0","๐",$str);
	return $str;
}
function toDateWen($date, $remark){
	$d = date('d', strtotime($date));
	$str = (int)$d;
	$str = toThaiNumber($str);
	
	$dayOfWeek = date('l', strtotime($date));
	switch($dayOfWeek){
		case 'Saturday' : $str .= ' (ส)';	break;
		case 'Sunday' : $str .= ' (อ)';	break;
		default :
			if($remark <> ""){
				$str .= ' (ย)';
			}			
			break;
	}
	return $str;
}
function toThaiMobileFormat($str){
	if(strlen($str)<10){
		return toThaiNumber($str);
	}else{
		return toThaiNumber(substr($str,0,2).' '.substr($str,2,4).' '.substr($str,6));
	}
}
function toThaiShortMonthYear($str){
	$year = substr($str, -5);
	$thai_yy = toThaiNumber(substr($year, -2));
	return $str = str_replace($year,$thai_yy,$str);
}
function to_thai_short_date_fdt($eng_date){
	if(strlen($eng_date) != 19){
		return null;
	}else{
		$new_datetime = explode(' ', $eng_date);
		$new_date = explode('-', $new_datetime[0]);

		$new_y = (int) $new_date[0] + 543;
		$new_m = $new_date[1];
		$new_d = $new_date[2];

		$thai_date = $new_d . '/' . $new_m . '/' . substr($new_y, 2, 2);
		//replace thai month
		$thai_short_date = '';
		switch($new_m){
			case '01' : $thai_short_date = str_replace("/01/"," ม.ค.",$thai_date); break;
			case '02' : $thai_short_date = str_replace("/02/"," ก.พ.",$thai_date); break;
			case '03' : $thai_short_date = str_replace("/03/"," มี.ค.",$thai_date); break;
			case '04' : $thai_short_date = str_replace("/04/"," เม.ย.",$thai_date); break;
			case '05' : $thai_short_date = str_replace("/05/"," พ.ค.",$thai_date); break;
			case '06' : $thai_short_date = str_replace("/06/"," มิ.ย.",$thai_date); break;
			case '07' : $thai_short_date = str_replace("/07/"," ก.ค.",$thai_date); break;
			case '08' : $thai_short_date = str_replace("/08/"," ส.ค.",$thai_date); break;
			case '09' : $thai_short_date = str_replace("/09/"," ก.ย.",$thai_date); break;
			case '10' : $thai_short_date = str_replace("/10/"," ต.ค.",$thai_date); break;
			case '11' : $thai_short_date = str_replace("/11/"," พ.ย.",$thai_date); break;
			case '12' : $thai_short_date = str_replace("/12/"," ธ.ค.",$thai_date); break;
		}
		
		//$thai_short_date = to_thai_number($thai_short_date);
		
		return $thai_short_date.', '.substr($new_datetime[1],0,5);
	}
}
?>