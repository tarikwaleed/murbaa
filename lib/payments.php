<?php
	class payments
	{
		/**The Default Method Like Main in java*/
		
		function __construct()
		{
			
		}
		
		public static function pay($db,$token,$amount,$id,$description,$cal_back,$currency = P_CURRENCY)
		{
			//get info
			$my_co = $db->select("SELECT co_id AS ID, co_name AS NAME 
								,co_name_en AS NAME_EN, co_phone AS PHONE
								,co_address AS ADDRESS, co_email AS EMAIL
								FROM ".DB_PREFEX."company AS COMP
								WHERE co_id = :ID"
								,array(":ID"=>session::get("company")));
			$my_co = $my_co[0];
			
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			
			if($ip == "127.0.0.1")
			{
				$ip = "1.1.1.1";
			}
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, P_URL);
			
			$options = array("profile_id" => P_PROFILE_ID
							,"tran_type"=>"sale"
							,"tran_class"=>"ecom"
							,"cart_id"=>$id
							,"payment_token"=>$_POST['token']
							,"cart_description"=> $description
							,"cart_currency"=> $currency
							,"customer_details"=> array("name"=> $my_co['NAME']
											,"email"=> $my_co['EMAIL']
											,"phone"=> $my_co['PHONE']
											,"street1"=> $my_co['ADDRESS']
											//,"city"=> "Dubai"
                                            ,"city"=> "Riyadh"
											//,"state"=> "du"
                                            ,"country"=> "SA"
											//,"country"=> "AE"
											//,"zip"=> "12345"
											,"ip"=> $ip
											)
							,"cart_amount"=> $amount
							,"callback"=>URL."pay_callback/index/".$id
							,"return"=>URL.$cal_back.$id
							);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($options));
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
									'authorization: '.P_URL_KEY,
									'content-type: application/json'
									));	
					
			$result = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($result, true);
			
			$ret = array();
			if(empty($data['tran_ref']))
			{
				return array('error'=>"call Error","error_data"=>$data);
			}else
			{
				$ret['tran_ref'] = $data['tran_ref'];
				if(!empty($data['redirect_url']))
				{
					$ret['redirect_url'] = $data['redirect_url'];
				}else
				{
					$ret['payment_result'] = $data['payment_result'];
				}
			}
			cookies::set($id,session::get('user_id'));
			return $data;
			
		}
	
		public static function callback($db,$id)
		{
			$form	= new form();
			if(!$form->single_valid($id,'Min_Length',5))
			{
				return array('error'=>$id);
			}
				
			//check NO:
			$data = $db->select("SELECT bi_id, bi_upd_data, bi_sql
									FROM ".DB_PREFEX."bill
									WHERE bi_code LIKE :ID AND bi_status LIKE 'PEND' "
									,array(":ID"=>$id));
			if(count($data) != 1)
			{
				return array('error'=>$id);
			}

            //check pay status
			if(empty($_POST['respStatus']))
			{
				return array('error'=>"call Error","error_data"=>"Data Not Compleated..");
			}
			if($_POST['respStatus'] != 'A')
			{
				return array('error'=>"call Error","error_data"=>$_POST['respMessage']);
			}
            
			$data = $data[0];
			
			$db->update(DB_PREFEX.'bill',array('bi_status'=>'A'),"bi_id = ".$data['bi_id']);
			
			if(!empty($data['bi_upd_data']))
			{
				$upd = json_decode($data['bi_upd_data'],true);
				$db->update($upd['table'],$upd['data'],$upd['where']);
			}
			if(!empty($data['bi_sql']))
			{
				$db->sql_quer($data['bi_sql']);
			}
            
            
			return array('id'=>$data['bi_id']);
		}
	}
?>