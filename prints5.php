<?php
include('prints_function.php');

session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$username = $_SESSION['username'];
include('config.php');


// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
		$this->SetFont('THSarabun', '', 16, '', true);
		$this->SetY(11);			
		if($this->page != 1){
			//$this->Cell(0, 5, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			$this->Cell(0, 5, '- '.$this->getAliasNumPage().' -', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
    }
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        ///$this->SetY(-15);
        // Set font
        $this->SetFont('THSarabun', '', 14, '', true);
        // Page number
		$tmp = date('Y-m-d H:i:s');
		$tmp = to_thai_short_date_fdt($tmp);
		$this->Cell(0, 10,'วันที่พิมพ์ : '. $tmp, 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Prawit Khamnet');
$pdf->SetTitle('RTARF DUTY');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

//remove header
//$pdf->setPrintHeader(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins (left, top, right)
//$pdf->SetMargins(24, 26, 30);	//หน้า ๓ บนถึงตูดเลขหน้า ๒ ตูดเลขหน้าถึงตูดบรรทัดแรก ๑.๕
$pdf->SetMargins(20, 20, 10);	//หน้า ๓ บนถึงตูดเลขหน้า ๒ ตูดเลขหน้าถึงตูดบรรทัดแรก ๑.๕
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

/*$this->pdf->setPrintHeader(false);
$this->pdf->setPrintFooter(false);
$this->pdf->setTopMargin(15);
$this->pdf->SetLeftMargin(20);
$this->pdf->SetRightMargin(20);//thsarabunpsk*/

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('THSarabun', '', 16, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.


// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print

		
		$hdr_id = $_GET['hdr_id'];
						
			$sql = 'select a.*,
					b.name as building_name,
					c.name as year_month_name,
					d.name as status_name 
					from rtarfwen_t_duty_headers a
					left join rtarfwen_m_buildings b on a.building_code=b.code
					left join core_years_months c on a.year_month_code=c.code
					left join rtarfwen_m_status d on d.code=a.status 
					where id='.$hdr_id.'
					';
						
			$hdr = mysqli_query($db,$sql);
			$hr = mysqli_fetch_array($hdr);
			
			$sql = "SELECT
					a.*,
					b.name as org_name, b2.name as org_name2, b3.name as org_name3, b4.name as org_name4, b5.name as org_name5, b6.name as org_name6,
					c.remark
					from rtarfwen_t_duty_details a 
					left join rtarfwen_m_orgs b on a.org_code=b.code
					left join rtarfwen_m_orgs b2 on a.org_code2=b2.code
					left join rtarfwen_m_orgs b3 on a.org_code2=b3.code
					left join rtarfwen_m_orgs b4 on a.org_code2=b4.code
					left join rtarfwen_m_orgs b5 on a.org_code2=b5.code
					left join rtarfwen_m_orgs b6 on a.org_code2=b6.code
					left join core_days c on a.date=c.date
					where hdr_id=".$hdr_id."
					";
			$dtl = mysqli_query($db,$sql);
			
			$rowcount=mysqli_num_rows($dtl);		 
		   $sc = array('P', 'C');
		   if($rowcount>0){
				$pdf->AddPage('P');
				// นายทหารเวรผู้ใหญ่
				$html = '';
				$html .= ( !in_array($hr['status'], $sc) ? '<label style="color: red;">สถานะ : '.$hr['status_name'].'</label>' : '');				
				//Header
				$pnk = '';
				if($_GET['pnk']==1){
					switch($hr['building_code']){
						case 1 : $pnk='ผนวก ก'; break;
						case 2 : $pnk='ผนวก x'; break;
						case 3 : $pnk='ผนวก ข'; break;
						case 4 : $pnk='ผนวก ค'; break;
						case 5 : $pnk='ผนวก ง'; break;
						case 6 : $pnk='ผนวก จ'; break;
						case 7 : $pnk='ผนวก ฉ'; break;
						case 8 :  $pnk='ผนวก ช'; break;
						case 9 : $pnk='ผนวก ซ'; break;
						case 10 : $pnk='ผนวก ด'; break;
						default : $pnk='ผนวก X'; break;
					}
					$html .= '<br/><table style="width: 100%;">
							<tr>
								<td style="width: 50px;">'.$pnk.'</td>
								<td style="width: 600px;">บัญชีรายชื่อข้าราชการปฏิบัติหน้าที่เวรรักษาความปลอดภัยและเวรประชาสัมพันธ์ ประจำ'.$hr['building_name'].'</td>
							</tr>
							<tr>
								<td></td>
								<td>ประจำเดือน '.toThaiShortMonthYear($hr['year_month_name']).'</td>
							</tr>
							<tr>
								<td></td>
								<td>ประกอบคำสั่ง บก.ทท. (เฉพาะ) ที่</td>
							</tr>
						</table>';
				}else{
					$html .= '<div style="text-align: center; font-weight: bold;">บัญชีรายชื่อข้าราชการปฏิบัติหน้าที่เวรรักษาความปลอดภัยและเวรประชาสัมพันธ์ ประจำ'.$hr['building_name'].'<br/>ประจำเดือน '.toThaiShortMonthYear($hr['year_month_name']).'</div>';				
				}				
				$html .= '<table border="1">
							<thead>
								<tr>
									<td style="width: 50px; text-align: center; font-weight: bold;">วันที่</td>
									<td style="width: 250px; text-align: center; font-weight: bold;">นายทหารเวรผู้ใหญ่<br/>ยศ-ชื่อ-สกุล</td>
									<td style="width: 100px; text-align: center; font-weight: bold;">สังกัด</td>
									<td style="width: 110px; text-align: center; font-weight: bold;">โทรศัพท์<br/>เคลื่อนที่</td>
									<td style="width: 120px; text-align: center; font-weight: bold;">หมายเหตุ</td>
								</tr>
							</thead>
							<tbody>
						';
				$icount = 1;
			   while($r = mysqli_fetch_array($dtl)) {
					$html .= '<tr>';
					$html .= '	<td style="width: 50px; text-align: center;">
									'.toDateWen($r['date'],$r['remark']).'
								</td>';
					$html .= '	<td style="width: 250px;">
									&nbsp;'.$r['fullname'].'
								</td>';
					$html .= '	<td style="width: 100px; text-align: center;">
									'.$r['org_name'].'
								</td>';
					$html .= '	<td style="width: 110px; text-align: center;">
									'.toThaiMobileFormat($r['mobile_phone_no']).'
								</td>';
					$html .= '	<td style="width: 120px; text-align: center;">
									'.$r['remark'].'
								</td>';
					$html .= '</tr>';
					$icount +=1;
				}
				$html .= '</tbody>';
				$html .= '</table>';
				$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
				
				// นายทหารเวร เสมียนเวร
				$pdf->AddPage('L');				
				$html = '';
				$html .= '<table border="1">
							<thead>
								<tr>
									<td style="width: 50px; text-align: center; font-weight: bold;">วันที่</td>
									<td style="width: 250px; text-align: center; font-weight: bold;">เวรรักษาความปลอดภัย<br/>ยศ-ชื่อ-สกุล</td>
									<td style="width: 100px; text-align: center; font-weight: bold;">สังกัด</td>
									<td style="width: 110px; text-align: center; font-weight: bold;">โทรศัพท์<br/>เคลื่อนที่</td>
									<td style="width: 250px; text-align: center; font-weight: bold;">เวรประชาสัมพันธ์<br/>ยศ-ชื่อ-สกุล</td>
									<td style="width: 100px; text-align: center; font-weight: bold;">สังกัด</td>
									<td style="width: 110px; text-align: center; font-weight: bold;">โทรศัพท์<br/>เคลื่อนที่</td>
								</tr>
							</thead>
							<tbody>
						';
				$icount = 1;
				mysqli_data_seek($dtl,0);
				while($r = mysqli_fetch_array($dtl)) {
					$html .= '<tr>';
					$html .= '	<td style="width: 50px; text-align: center;">
									'.toDateWen($r['date'],$r['remark']).'
								</td>';
					$html .= '	<td style="width: 250px;">
									&nbsp;'.$r['fullname4'].'
								</td>';
					$html .= '	<td style="width: 100px; text-align: center;">
									'.$r['org_name4'].'
								</td>';
					$html .= '	<td style="width: 110px; text-align: center;">
									'.toThaiMobileFormat($r['mobile_phone_no4']).'
								</td>';
					$html .= '	<td style="width: 250px;">
									&nbsp;'.$r['fullname6'].'
								</td>';
					$html .= '	<td style="width: 100px; text-align: center;">
									'.$r['org_name6'].'
								</td>';
					$html .= '	<td style="width: 110px; text-align: center;">
									'.toThaiMobileFormat($r['mobile_phone_no6']).'
								</td>';
					$html .= '</tr>';
					$icount +=1;
				}
				$html .= '</tbody>';
				$html .= '</table>';
				
				//verify begin
				$html .= '	
							<table style="width: 100%">
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td style="text-align: right;">ตรวจถูกต้อง</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="4" style="font-size: 12px;">ตรวจถูกต้องเมื่อ : '.$hr['verify_time'].', บันทึกอนุมัติเมื่อ : '.$hr['approve_time'].'</td>
									<td colspan="4" style="text-align: center; ">										
										'.$hr['verify_fullname'].'
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td colspan="4" style="text-align: center;">
										'.$hr['verify_position'].'
									</td>
								</tr>
							</table>
				';
				//verify end
				
				$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
				
				
		   }//rowcount>0	
		   
  

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('rtarfwen_05.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
	?>