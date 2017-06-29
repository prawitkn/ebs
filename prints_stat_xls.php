<?php
session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$fullname = $_SESSION['fullname'];
include('config.php');
include 'inc_helper.php';

$file="ebs_stat.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");

		$sql = '
				select *
				from  rtarfwen_m_users 
				where username='.$_SESSION['username']
				;
		$ses_sql = mysqli_query($db,$sql);
		$rowcount=mysqli_num_rows($ses_sql);
		$usr='';
		if($rowcount == 1){
		   $usr = mysqli_fetch_array($ses_sql);	
		}else{
		   echo 'error';
		}

		if( isset($_GET['building_code']) && !empty($_GET['building_code']) && isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to']) ){
			
			$building_code = $_GET['building_code'];
			$date_from = $_GET['date_from'];
			$date_to = $_GET['date_to'];
			$date_from = str_replace(".","/",$date_from);
			$date_to = str_replace(".","/",$date_to);
			
						
			
			$sql = "
					select x.mid, y.title_abb_name_surname as fullname, 
					sum(x.c_work_day) as c_work_day,
					sum(x.c_off_day) as c_off_day,
					sum(x.c_off2_day) as c_off2_day
					from (
						select c.id as mid,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=0
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_work_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=1
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=2
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off2_day
						from rtarfwen_t_duty_headers a 
						inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
							and b.date between '".$date_from."' and '".$date_to."'
						inner join core_persons c on c.id=b.mid
						where 1=1
						and a.building_code=".$building_code." 
						group by c.id

						union

						select c.id as mid,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=0
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_work_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=1
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=2
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off2_day
						from rtarfwen_t_duty_headers a
						inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
							and b.date between '".$date_from."' and '".$date_to."'
						inner join core_persons c on c.id=b.mid2
						where 1=1
						and a.building_code=".$building_code." 
						group by c.id 

						union

						select c.id as mid,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=0
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_work_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=1
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=2
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off2_day
						from rtarfwen_t_duty_headers a
						inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
							and b.date between '".$date_from."' and '".$date_to."'
						inner join core_persons c on c.id=b.mid3
						where 1=1
						and a.building_code=".$building_code." 
						group by c.id 

						union 

						select c.id as mid,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=0
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_work_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=1
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=2
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off2_day
						from rtarfwen_t_duty_headers a
						inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
							and b.date between '".$date_from."' and '".$date_to."'
						inner join core_persons c on c.id=b.mid4
						where 1=1
						and a.building_code=".$building_code." 
						group by c.id 

						union

						select c.id as mid,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=0
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_work_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=1
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=2
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off2_day
						from rtarfwen_t_duty_headers a
						inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
							and b.date between '".$date_from."' and '".$date_to."'
						inner join core_persons c on c.id=b.mid5
						where 1=1
						and a.building_code=".$building_code." 
						group by c.id 

						union

						select c.id as mid,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=0
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_work_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=1
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off_day,
						(select count(*) from rtarfwen_t_duty_details x1 
								inner join core_days cd on x1.date=cd.date and cd.date_type_code=2
								where x1.mid=c.id 
								and x1.hdr_id=b.hdr_id 
								and x1.date=b.date) as c_off2_day
						from rtarfwen_t_duty_headers a
						inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
							and b.date between '".$date_from."' and '".$date_to."'
						inner join core_persons c on c.id=b.mid6
						where 1=1
						and a.building_code=".$building_code." 
						group by c.id ) as x
					inner join core_persons y on x.mid=y.id
					group by  x.mid, y.title_abb_name_surname

					";

			
			//$sql .= 'order by a.year_month_code desc ';
			//$sql .= 'limit 100 ';
			//echo $sql;
		   $ses_sql = mysqli_query($db,$sql);
		   $rowcount=mysqli_num_rows($ses_sql);
			
			//echo '<input type="hidden" name="year_month_code" value="'.$year_month_code.'">';
		   
		   $html = '';
		   if($rowcount>0){
				$tmp = mysqli_fetch_row($ses_sql);
			   $html .= '<table id="tbl_main" class="table">';
			   $html .= '<thead>
						<tr>					
							<td>ลำดับ</td>
							<td>ยศ ชื่อ นามสกุล</td>
							<td>จำนวนครั้ง วันปฏิบัติงาน</td>
							<td>จำนวนครั้ง วันหยุด</td>
							<td>จำนวนครั้ง วันหยุด2</td>
							<td>รวม</td>
						</tr>
					</thead>';
			   mysqli_data_seek($ses_sql,0);
				$icount = 1;
			   while($r = mysqli_fetch_array($ses_sql)) {
					$html .= '<tr>';
					$html .= '	<td style="width: 100px!; overflow: hidden;">
							'.$icount.'
							</td>';
					$html .= '	<td style="width: 100px!; overflow: hidden;">
							'.$r['fullname'].'
							</td>';
					$html .= '	<td style="width: 100px!; overflow: hidden;">
							'.$r['c_work_day'].'
							</td>';
					$html .= '	<td style="width: 100px!; overflow: hidden;">
							'.$r['c_off_day'].'
							</td>';
					$html .= '	<td style="width: 100px!; overflow: hidden;">
							'.$r['c_off2_day'].'
							</td>';	
					$html .= '	<td style="width: 100px!; overflow: hidden;">
							'.($r['c_work_day']+$r['c_off_day']+$r['c_off2_day']).'
							</td>';							
					$html .= '</tr>';
					$icount +=1;
				}
				$html .= '</table>';
		   }else{
			   $html .= '';
		   }//rowcount>0	
			echo $html;
		}//if isset POST
		
	?>