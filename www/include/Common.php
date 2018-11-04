<?php

class Cl_Common extends Cl_Messages
{
	protected $_con;

	public function __construct()
	{
		$db = new Cl_DBclass();
		$this->_con = $db->con;
		$this->_con = $db->con;
	}
	
	public function redirect($url)
	{
		header('Location:'.$url);
		exit();
	}
	
	public function stringLimit($string,$limit)
	{
		$string = strip_tags($string);
		$string = substr($string,0,$limit);
		$string = substr($string,0,strrpos($string," "));
		return $string;
	}
	
	
	public function registration( $data )
	{
		if( !empty( $data ))
		{
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($data['firstname'], ENT_QUOTES) );
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($data['lastname'], ENT_QUOTES) );
			$email = mysqli_real_escape_string( $this->_con, htmlentities($data['email'], ENT_QUOTES) );
			$mobile = mysqli_real_escape_string( $this->_con, htmlentities($data['mobile'], ENT_QUOTES) );
			$username = mysqli_real_escape_string( $this->_con, htmlentities($data['username'], ENT_QUOTES) );
			$type = mysqli_real_escape_string( $this->_con, htmlentities($data['type'], ENT_QUOTES) );
			$password = mysqli_real_escape_string( $this->_con, md5($data['password']) );


			if ($this->is_email( $email)) 
			{
				$email = mysqli_real_escape_string( $this->_con, $email);
			} 
			else 
			{				
				parent::add('e', 'Please enter a valid email address!');
				return false;
			}

			if((empty($firstname)) || (empty($lastname)) || (empty($email)) || (empty($username)) || (empty($password))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$conditions = array('email'=>$email,'type'=>$type);
			if(!$this->checkrecord('pr_members','*',$conditions) )
			{
				$conditions = array('username'=>$username,'type'=>$type);
				if(!$this->checkrecord('pr_members','*',$conditions) )
				{
					$code = md5($_POST['email'].rand().rand());
					$entered = date('Y-m-d h:i:s');
					$query = "INSERT INTO pr_members SET firstname = '$firstname', lastname = '$lastname', mobile = '$mobile', email = '$email', username = '$username', password = '$password', code = '$code', type = '$type', date = '$entered'";
					if(mysqli_query($this->_con, $query))
					{
						$subject = "PhotoRunner-Account verification ";
						$message ="<html><body>
						<div style='100%; font-family:arial; border:0px solid #00A2B5; font-family:arial; font-size:18px; border-radius:0px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your Account with ".APP_NAME."</div>
						<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
						"<div style='color:#00A2B5; font-family:arial; font-size:18px; font-weight:bold; margin:20px;'>Hi ".$_POST['firstname']." </div>".
						"<div style='color:#00A2B5; font-family:arial; font-size:18px; font-weight:bold; margin:20px;'>Thanks for registration with us.</div>".					


						"<div style='color:#6B555A; font-family:arial; border:1px solid #ccc; margin-top:30px; width:80%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center'>To complete the registration process. Please verify your email id by click on given below Verify Account button.<br/><br/>".



						"<div style='margin-top:15px; font-family:arial; margin-bottom:15px;'> <a href='".APP_URL."log-in.php/?verifykey=".$code."'' style='color:#fff; text-decoration:none; font-size:20px; font-weight:bold; margin:20px; padding:15px; width:230px; margin-left:auto; margin-right:auto; background-color:#00A2B5; border-radius:3px; font-family:arial;'>Verify Account</a></div></div><br/><br/>".



						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>Your login detail are given below:</div><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center;'><b>Username:</b> ".$data['username']."</div><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center;'><b>Password:</b> ".$data['password']."</div><br/><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
						"<div style='font-size:14px; font-family:arial; text-align:left;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";
						if($this->sendemail($email,$subject,$message))
						{
							parent::add('s', 'Registration has been completed successfully. Please activate your accout from your email address.');
							return true;
						}
						else
						{
							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}
				else
				{
					parent::add('e', 'Username already exist. Please try again.');	
					return false;				}
			}
			else
			{
				parent::add('e', 'Email already exist. Please try again.');	
				return false;
			}
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}


	public function facebookregistration( $data )
	{
		if( !empty( $data ))
		{
			$data['type'] = 'buyer';
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($data['fb_username'], ENT_QUOTES) );
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($data['fb_username'], ENT_QUOTES) );
			$email = mysqli_real_escape_string( $this->_con, htmlentities($data['fb_email'], ENT_QUOTES) );
			//$mobile = mysqli_real_escape_string( $this->_con, htmlentities($data['mobile'], ENT_QUOTES) );
			$username = mysqli_real_escape_string( $this->_con, htmlentities($data['fb_username'], ENT_QUOTES) );
			$type = mysqli_real_escape_string( $this->_con, htmlentities($data['type'], ENT_QUOTES) );
			$password = mysqli_real_escape_string( $this->_con, md5($data['password']) );


			if ($this->is_email( $email)) 
			{
				unset($_SESSION['fb_1758055857740600_code']);
				unset($_SESSION['fb_1758055857740600_access_token']);
				unset($_SESSION['fb_1758055857740600_user_id']);
				unset($_SESSION['fb_id']);
				unset($_SESSION['fb_username']);
				unset($_SESSION['fb_email']);
				unset($_SESSION['facebboktype']);
				$email = mysqli_real_escape_string( $this->_con, $email);
			} 
			else 
			{				
				parent::add('e', 'Please enter a valid email address!');
				return false;
			}

			if((empty($firstname)) || (empty($lastname)) || (empty($email)) || (empty($username)) || (empty($password))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$conditions = array('email'=>$email,'type'=>$type);
			if(!$this->checkrecord('pr_members','*',$conditions) )
			{
				$conditions = array('username'=>$username,'type'=>$type);
				if(!$this->checkrecord('pr_members','*',$conditions) )
				{

					$digits = 8;
					$password = rand(pow(10, $digits-1), pow(10, $digits)-1);
					$passwordHash = md5($password);

					$code = md5($_POST['email'].rand().rand());
					$entered = date('Y-m-d h:i:s');
					$query = "INSERT INTO pr_members SET firstname = '$firstname', lastname = '$lastname', mobile = '$mobile', email = '$email', username = '$username', password = '$passwordHash', code = '$code', type = '$type', date = '$entered'";
					if(mysqli_query($this->_con, $query))
					{
						$subject = "PhotoRunner-Account verification ";
						$message ="<html><body>
						<div style='100%; font-family:arial; border:0px solid #00A2B5; font-family:arial; font-size:18px; border-radius:0px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your Account with ".APP_NAME."</div>
						<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
						"<div style='color:#00A2B5; font-family:arial; font-size:18px; font-weight:bold; margin:20px;'>Hi ".$username." </div>".
						"<div style='color:#00A2B5; font-family:arial; font-size:18px; font-weight:bold; margin:20px;'>Thanks for registration with us.</div>".					


						"<div style='color:#6B555A; font-family:arial; border:1px solid #ccc; margin-top:30px; width:80%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center'>To complete the registration process. Please verify your email id by click on given below Verify Account button.<br/><br/>".



						"<div style='margin-top:15px; font-family:arial; margin-bottom:15px;'> <a href='".APP_URL."log-in.php/?verifykey=".$code."'' style='color:#fff; text-decoration:none; font-size:20px; font-weight:bold; margin:20px; padding:15px; width:230px; margin-left:auto; margin-right:auto; background-color:#00A2B5; border-radius:3px; font-family:arial;'>Verify Account</a></div></div><br/><br/>".



						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>Your login detail are given below:</div><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center;'><b>Username:</b> ".$username."</div><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center;'><b>Password:</b> ".$password."</div><br/><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
						"<div style='font-size:14px; font-family:arial; text-align:left;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";
						if($this->sendemail($email,$subject,$message))
						{
							unset($_SESSION['fb_1758055857740600_code']);
							unset($_SESSION['fb_1758055857740600_access_token']);
							unset($_SESSION['fb_1758055857740600_user_id']);
							unset($_SESSION['fb_id']);
							unset($_SESSION['fb_username']);
							unset($_SESSION['fb_email']);
							unset($_SESSION['facebboktype']);

							parent::add('s', 'Your registration has been completed successfully. We have sent login details in your email address. Please activate your accout from your email address.');
							return true;
						}
						else
						{
							unset($_SESSION['fb_1758055857740600_code']);
							unset($_SESSION['fb_1758055857740600_access_token']);
							unset($_SESSION['fb_1758055857740600_user_id']);
							unset($_SESSION['fb_id']);
							unset($_SESSION['fb_username']);
							unset($_SESSION['fb_email']);
							unset($_SESSION['facebboktype']);

							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						unset($_SESSION['fb_1758055857740600_code']);
						unset($_SESSION['fb_1758055857740600_access_token']);
						unset($_SESSION['fb_1758055857740600_user_id']);
						unset($_SESSION['fb_id']);
						unset($_SESSION['fb_username']);
						unset($_SESSION['fb_email']);
						unset($_SESSION['facebboktype']);
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}
				else
				{
					unset($_SESSION['fb_1758055857740600_code']);
					unset($_SESSION['fb_1758055857740600_access_token']);
					unset($_SESSION['fb_1758055857740600_user_id']);
					unset($_SESSION['fb_id']);
					unset($_SESSION['fb_username']);
					unset($_SESSION['fb_email']);
					unset($_SESSION['facebboktype']);
					parent::add('e', 'Username already exist. Please try again.');	
					return false;				
				}
			}
			else
			{
				unset($_SESSION['fb_1758055857740600_code']);
				unset($_SESSION['fb_1758055857740600_access_token']);
				unset($_SESSION['fb_1758055857740600_user_id']);
				unset($_SESSION['fb_id']);
				unset($_SESSION['fb_username']);
				unset($_SESSION['fb_email']);
				unset($_SESSION['facebboktype']);
				parent::add('e', 'Email already exist. Please try again.');	
				return false;
			}
		} 
		else
		{
			unset($_SESSION['fb_1758055857740600_code']);
			unset($_SESSION['fb_1758055857740600_access_token']);
			unset($_SESSION['fb_1758055857740600_user_id']);
			unset($_SESSION['fb_id']);
			unset($_SESSION['fb_username']);
			unset($_SESSION['fb_email']);
			unset($_SESSION['facebboktype']);
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}


	public function googleregistration( $data )
	{
		if( !empty( $data ))
		{
			$data['type'] = 'buyer';
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($data['given_name'], ENT_QUOTES) );
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($data['family_name'], ENT_QUOTES) );
			$email = mysqli_real_escape_string( $this->_con, htmlentities($data['email'], ENT_QUOTES) );
			$username = mysqli_real_escape_string( $this->_con, htmlentities($data['name'], ENT_QUOTES) );
			$type = mysqli_real_escape_string( $this->_con, htmlentities($data['type'], ENT_QUOTES) );


			if((empty($firstname)) || (empty($lastname)) || (empty($email)) || (empty($username))) 
			{
				unset($_SESSION['google_data']);
				unset($_SESSION['token']);
				unset($_SESSION['gog_email']);	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$conditions = array('email'=>$email,'type'=>$type);
			if(!$this->checkrecord('pr_members','*',$conditions) )
			{
				$conditions = array('username'=>$username,'type'=>$type);
				if(!$this->checkrecord('pr_members','*',$conditions) )
				{

					$digits = 8;
					$password = rand(pow(10, $digits-1), pow(10, $digits)-1);
					$passwordHash = md5($password);

					$code = md5($_POST['email'].rand().rand());
					$entered = date('Y-m-d h:i:s');
					$query = "INSERT INTO pr_members SET firstname = '$firstname', lastname = '$lastname', mobile = '$mobile', email = '$email', username = '$username', password = '$passwordHash', code = '$code', type = '$type', date = '$entered'";
					if(mysqli_query($this->_con, $query))
					{
						$subject = "PhotoRunner-Account verification ";
						$message ="<html><body>
						<div style='100%; font-family:arial; border:0px solid #00A2B5; font-family:arial; font-size:18px; border-radius:0px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your Account with ".APP_NAME."</div>
						<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
						"<div style='color:#00A2B5; font-family:arial; font-size:18px; font-weight:bold; margin:20px;'>Hi ".$username." </div>".
						"<div style='color:#00A2B5; font-family:arial; font-size:18px; font-weight:bold; margin:20px;'>Thanks for registration with us.</div>".					


						"<div style='color:#6B555A; font-family:arial; border:1px solid #ccc; margin-top:30px; width:80%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center'>To complete the registration process. Please verify your email id by click on given below Verify Account button.<br/><br/>".



						"<div style='margin-top:15px; font-family:arial; margin-bottom:15px;'> <a href='".APP_URL."log-in.php/?verifykey=".$code."'' style='color:#fff; text-decoration:none; font-size:20px; font-weight:bold; margin:20px; padding:15px; width:230px; margin-left:auto; margin-right:auto; background-color:#00A2B5; border-radius:3px; font-family:arial;'>Verify Account</a></div></div><br/><br/>".



						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>Your login detail are given below:</div><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center;'><b>Username:</b> ".$username."</div><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center;'><b>Password:</b> ".$password."</div><br/><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
						"<div style='font-size:14px; font-family:arial; text-align:left;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";
						if($this->sendemail($email,$subject,$message))
						{
							unset($_SESSION['google_data']);
							unset($_SESSION['token']);
							unset($_SESSION['gog_email']);

							parent::add('s', 'Your registration has been completed successfully. We have sent login details in your email address. Please activate your accout from your email address.');
							return true;
						}
						else
						{
							unset($_SESSION['google_data']);
							unset($_SESSION['token']);
							unset($_SESSION['gog_email']);

							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						unset($_SESSION['google_data']);
						unset($_SESSION['token']);
						unset($_SESSION['gog_email']);
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}
				else
				{
					unset($_SESSION['google_data']);
					unset($_SESSION['token']);
					unset($_SESSION['gog_email']);
					parent::add('e', 'Username already exist. Please try again.');	
					return false;				
				}
			}
			else
			{
				unset($_SESSION['google_data']);
				unset($_SESSION['token']);
				unset($_SESSION['gog_email']);
				parent::add('e', 'Email already exist. Please try again.');	
				return false;
			}
		} 
		else
		{
			unset($_SESSION['google_data']);
			unset($_SESSION['token']);
			unset($_SESSION['gog_email']);
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}


	public function sellerregistration( $postdata, $filesdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$email = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['email'], ENT_QUOTES ));
			$username = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['username'], ENT_QUOTES ));
				
			$password = mysqli_real_escape_string( $this->_con, md5($trimmed_data['passwordd']));

			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['firstname'], ENT_QUOTES ));
				
			$bankname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['bankname'], ENT_QUOTES ));
			$owner_name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['owner_name'], ENT_QUOTES ));
			$banknumber = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['banknumber'], ENT_QUOTES ));


			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['lastname'], ENT_QUOTES ));
			$business_name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['business_name'], ENT_QUOTES ));
			$phone_number = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['phone_number'], ENT_QUOTES ));
			$country = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['country'], ENT_QUOTES ));
			$state = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['state'], ENT_QUOTES ));
			$city = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['city'], ENT_QUOTES ));
			$zip_code = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['zip_code'], ENT_QUOTES ));

			$about = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['about'], ENT_QUOTES ));
			$area = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['area'], ENT_QUOTES ));
			$price = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['price'], ENT_QUOTES ));

			$priceeuro = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['priceeuro'], ENT_QUOTES ));

			$pricetext = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['pricetext'], ENT_QUOTES ));

			if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
			{
				parent::add('e', 'Code Not Matched');	
				return false;
			}
if((empty($email)) || (empty($username))  || (empty($about))  || (empty($area))  || (empty($price))  || (empty($priceeuro)) || (empty($password)) || (empty($firstname)) || (empty($lastname)) || (empty($business_name)) || (empty($phone_number)) || (empty($country)) || (empty($state)) || (empty($city)) || (empty($zip_code)) || (empty($bankname)) || (empty($owner_name)) || (empty($banknumber)) || (empty($pricetext))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$conditions = array('email'=>$email);
			if(!$this->checkrecord('pr_seller','*',$conditions) )
			{
				$conditions = array('username'=>$username);
				if(!$this->checkrecord('pr_seller','*',$conditions) )
				{
					if(!empty($filesdata['banner1']))
					{

						$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
						$ext = explode('.', basename($filesdata['banner1']['name']));
						$file_extension = end($ext);
						$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
						$file_target_path = APP_ROOT."uploads/seller/" . $filename;  

						if(in_array($file_extension, $validextensions)) 
						{
							if (move_uploaded_file($filesdata['banner1']['tmp_name'], $file_target_path)) 
							{ 

								if(!empty($filesdata['banner2']))
								{
									$ext1 = explode('.', basename($filesdata['banner2']['name']));
									$file_extension1 = end($ext1);
									$filename1 = md5(uniqid()) . "." . $ext1[count($ext1) - 1];
									$file_target_path1 = APP_ROOT."uploads/seller/" . $filename1;  
				
									if(in_array($file_extension, $validextensions)) 
									{
										if (move_uploaded_file($filesdata['banner2']['tmp_name'], $file_target_path1)) 
										{
											$code = md5($_POST['email'].rand().rand());
											$entered = date('Y-m-d h:i:s');
											$query = "INSERT into pr_seller SET email ='".$email."', 
											username ='".$username."',
											password ='".$password."',
											firstname ='".$firstname."', 
											lastname ='".$lastname."',
											business_name ='".$business_name."',
											phone_number ='".$phone_number."',
											country ='".$country."',
											state ='".$state."',
											zip_code ='".$zip_code."',
											code ='".$code."',

											bankname ='".$bankname."',
											owner_name ='".$owner_name."',
											banknumber ='".$banknumber."',

											about ='".$about."',
											area ='".$area."',
											price ='".$price."',

											priceeuro ='".$priceeuro."',

											banner1 ='".$filename."',
											banner2 ='".$filename1."',

											pricetext ='".$pricetext."',

											date ='".$entered."',
											city ='".$city."'";
											if(mysqli_query($this->_con, $query))
											{
												$subject = "PhotoRunner-Account verification ";
												$message ="<html><body>
												<div style='100%; border:0px solid #00A2B5; font-family:arial; font-family:arial; font-size:18px; border-radius:10px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your with Account ".APP_NAME."</div>
												<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".

												"<div style='color:#00A2B5; font-size:18px; font-family:arial; font-weight:bold; margin:20px;'>Hi ".$_POST['firstname']." </div>".
												"<div style='color:#00A2B5; font-size:18px; font-family:arial; font-weight:bold; margin:20px;'>Thanks for registration with us.</div>".			

												"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:80%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; font-family:arial; background-color:#F2F2F2; text-align:center'>To complete the registration process. Please verify your email id by click on given below Verify Account button.<br/><br/>".


												"<div style='margin-top:15px; margin-bottom:15px; font-family:arial;'> <a href='".APP_URL."log-in.php/?verifykeyy=".$code."'' style='color:#fff; text-decoration:none; font-size:20px; font-weight:bold; margin:20px; font-family:arial; padding:15px; width:230px; margin-left:auto; margin-right:auto; background-color:#00A2B5; border-radius:3px;'>Verify Account</a></div><div></div></div style='height:10px; clear:both'><br/><br/>".
												"<div style='font-size:16px; text-align:center; font-family:arial; color:#00A2B5;'>Your login detail are given below:</div><br/>".
												"<div style='font-size:16px; text-align:center; font-family:arial;'><b>Username:</b> ".$trimmed_data['username']."</div><br/>".
												"<div style='font-size:16px; text-align:center; font-family:arial;'><b>Password:</b> ".$trimmed_data['passwordd']."</div><br/><br/>".
												"<div style='font-size:16px; text-align:center; font-family:arial; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".

												"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
												"</div></body></html>";
												if($this->sendemail($email,$subject,$message))
												{
													parent::add('s', 'Registration has been completed successfully. Please activate your accout from your email address.');
													return true;
												}
												else
												{
													parent::add('e', 'Somthing went wrong. Please try again.');	
													return false;
												}
											}
											else
											{
												parent::add('e', 'Somthing went wrong. Please try again5.');	
												return false;
											}
										}
										else
										{
											parent::add('e', 'Somthing went wrong. Please try again4.');	
											return false;
										}
									}
									else
									{
										parent::add('e', 'Somthing went wrong. Please try again3.');	
										return false;
									}
								}
								else
								{
									parent::add('e', 'Somthing went wrong. Please try again2.');	
									return false;
								}
							}
							else
							{
								parent::add('e', 'Somthing went wrong. Please try again1.');	
								return false;
							}
						}
						else
						{
							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}
				else
				{
					parent::add('e', 'Username already exist. Please try again.');		
					return false;
				}
			}
			else
			{
				parent::add('e', 'Email Address already exist. Please try again.');		
				return false;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function facebooksellerregistration( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			
			$email = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fb_email'], ENT_QUOTES ));
			$username = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fb_username'], ENT_QUOTES ));
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fb_username'], ENT_QUOTES ));
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fb_username'], ENT_QUOTES ));
			if((empty($email)) || (empty($username))) 
			{
				unset($_SESSION['fb_1758055857740600_code']);
				unset($_SESSION['fb_1758055857740600_access_token']);
				unset($_SESSION['fb_1758055857740600_user_id']);
				unset($_SESSION['fb_id']);
				unset($_SESSION['fb_username']);
				unset($_SESSION['fb_email']);
				unset($_SESSION['facebboktype']);	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$conditions = array('email'=>$email);
			if(!$this->checkrecord('pr_seller','*',$conditions) )
			{
				$conditions = array('username'=>$username);
				if(!$this->checkrecord('pr_seller','*',$conditions) )
				{
					$digits = 8;
					$password = rand(pow(10, $digits-1), pow(10, $digits)-1);
					$passwordHash = md5($password);
					
					$code = md5($_POST['email'].rand().rand());
					$entered = date('Y-m-d h:i:s');
					$query = "INSERT into pr_seller SET email ='".$email."', username ='".$username."',password ='".$passwordHash."',firstname ='".$firstname."', lastname ='".$lastname."',date ='".$entered."',code ='".$code."'";
					if(mysqli_query($this->_con, $query))
					{
						unset($_SESSION['fb_1758055857740600_code']);
						unset($_SESSION['fb_1758055857740600_access_token']);
						unset($_SESSION['fb_1758055857740600_user_id']);
						unset($_SESSION['fb_id']);
						unset($_SESSION['fb_username']);
						unset($_SESSION['fb_email']);
						unset($_SESSION['facebboktype']);

						$subject = "PhotoRunner-Account verification ";
						$message ="<html><body>
						<div style='100%; border:0px solid #00A2B5; font-family:arial; font-family:arial; font-size:18px; border-radius:10px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your with Account ".APP_NAME."</div>
						<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".

						"<div style='color:#00A2B5; font-size:18px; font-family:arial; font-weight:bold; margin:20px;'>Hi ".$username." </div>".
						"<div style='color:#00A2B5; font-size:18px; font-family:arial; font-weight:bold; margin:20px;'>Thanks for registration with us.</div>".			

						"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:80%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; font-family:arial; background-color:#F2F2F2; text-align:center'>To complete the registration process. Please verify your email id by click on given below Verify Account button.<br/><br/>".


						"<div style='margin-top:15px; margin-bottom:15px; font-family:arial;'> <a href='".APP_URL."log-in.php/?verifykeyy=".$code."'' style='color:#fff; text-decoration:none; font-size:20px; font-weight:bold; margin:20px; font-family:arial; padding:15px; width:230px; margin-left:auto; margin-right:auto; background-color:#00A2B5; border-radius:3px;'>Verify Account</a></div><div></div></div style='height:10px; clear:both'><br/><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial; color:#00A2B5;'>Your login detail are given below:</div><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial;'><b>Username:</b> ".$username."</div><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial;'><b>Password:</b> ".$password."</div><br/><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".

						"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";
						if($this->sendemail($email,$subject,$message))
						{
							parent::add('s', 'Your registration has been completed successfully. We have sent your login details in your email. Please activate your accout from your email address.');
							return true;
						}
						else
						{
							unset($_SESSION['fb_1758055857740600_code']);
							unset($_SESSION['fb_1758055857740600_access_token']);
							unset($_SESSION['fb_1758055857740600_user_id']);
							unset($_SESSION['fb_id']);
							unset($_SESSION['fb_username']);
							unset($_SESSION['fb_email']);
							unset($_SESSION['facebboktype']);
							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						unset($_SESSION['fb_1758055857740600_code']);
						unset($_SESSION['fb_1758055857740600_access_token']);
						unset($_SESSION['fb_1758055857740600_user_id']);
						unset($_SESSION['fb_id']);
						unset($_SESSION['fb_username']);
						unset($_SESSION['fb_email']);
						unset($_SESSION['facebboktype']);
						parent::add('e', 'Somthing went wrong. Please try again5.');	
						return false;
					}
				}
				else
				{
					unset($_SESSION['fb_1758055857740600_code']);
					unset($_SESSION['fb_1758055857740600_access_token']);
					unset($_SESSION['fb_1758055857740600_user_id']);
					unset($_SESSION['fb_id']);
					unset($_SESSION['fb_username']);
					unset($_SESSION['fb_email']);
					unset($_SESSION['facebboktype']);
					parent::add('e', 'Username Address already exists.');	
					return false;
				}
			}
			else
			{
				unset($_SESSION['fb_1758055857740600_code']);
				unset($_SESSION['fb_1758055857740600_access_token']);
				unset($_SESSION['fb_1758055857740600_user_id']);
				unset($_SESSION['fb_id']);
				unset($_SESSION['fb_username']);
				unset($_SESSION['fb_email']);
				unset($_SESSION['facebboktype']);
				parent::add('e', 'Email Address already exists.');	
				return false;
			}	
		} 
		else
		{
			unset($_SESSION['fb_1758055857740600_code']);
			unset($_SESSION['fb_1758055857740600_access_token']);
			unset($_SESSION['fb_1758055857740600_user_id']);
			unset($_SESSION['fb_id']);
			unset($_SESSION['fb_username']);
			unset($_SESSION['fb_email']);
			unset($_SESSION['facebboktype']);
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function googlesellerregistration( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			
			$email = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['email'], ENT_QUOTES ));
			$username = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['name'], ENT_QUOTES ));
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['given_name'], ENT_QUOTES ));
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['family_name'], ENT_QUOTES ));


			if((empty($email)) || (empty($username))) 
			{
				unset($_SESSION['google_data']);
				unset($_SESSION['token']);
				unset($_SESSION['gog_email']);	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$conditions = array('email'=>$email);
			if(!$this->checkrecord('pr_seller','*',$conditions) )
			{
				$conditions = array('username'=>$username);
				if(!$this->checkrecord('pr_seller','*',$conditions) )
				{
					$digits = 8;
					$password = rand(pow(10, $digits-1), pow(10, $digits)-1);
					$passwordHash = md5($password);
					
					$code = md5($_POST['email'].rand().rand());
					$entered = date('Y-m-d h:i:s');
					$query = "INSERT into pr_seller SET email ='".$email."', username ='".$username."',password ='".$passwordHash."',firstname ='".$firstname."', lastname ='".$lastname."',date ='".$entered."',code ='".$code."'";
					if(mysqli_query($this->_con, $query))
					{
						unset($_SESSION['google_data']);
						unset($_SESSION['token']);
						unset($_SESSION['gog_email']);
						$subject = "PhotoRunner-Account verification ";
						$message ="<html><body>
						<div style='100%; border:0px solid #00A2B5; font-family:arial; font-family:arial; font-size:18px; border-radius:10px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your with Account ".APP_NAME."</div>
						<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".

						"<div style='color:#00A2B5; font-size:18px; font-family:arial; font-weight:bold; margin:20px;'>Hi ".$username." </div>".
						"<div style='color:#00A2B5; font-size:18px; font-family:arial; font-weight:bold; margin:20px;'>Thanks for registration with us.</div>".			

						"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:80%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; font-family:arial; background-color:#F2F2F2; text-align:center'>To complete the registration process. Please verify your email id by click on given below Verify Account button.<br/><br/>".


						"<div style='margin-top:15px; margin-bottom:15px; font-family:arial;'> <a href='".APP_URL."log-in.php/?verifykeyy=".$code."'' style='color:#fff; text-decoration:none; font-size:20px; font-weight:bold; margin:20px; font-family:arial; padding:15px; width:230px; margin-left:auto; margin-right:auto; background-color:#00A2B5; border-radius:3px;'>Verify Account</a></div><div></div></div style='height:10px; clear:both'><br/><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial; color:#00A2B5;'>Your login detail are given below:</div><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial;'><b>Username:</b> ".$username."</div><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial;'><b>Password:</b> ".$password."</div><br/><br/>".
						"<div style='font-size:16px; text-align:center; font-family:arial; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".

						"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";
						if($this->sendemail($email,$subject,$message))
						{
							parent::add('s', 'Your registration has been completed successfully. We have sent your login details in your email. Please activate your accout from your email address.');
							return true;
						}
						else
						{
							unset($_SESSION['google_data']);
							unset($_SESSION['token']);
							unset($_SESSION['gog_email']);
							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						unset($_SESSION['google_data']);
						unset($_SESSION['token']);
						unset($_SESSION['gog_email']);
						parent::add('e', 'Somthing went wrong. Please try again5.');	
						return false;
					}
				}
				else
				{
					unset($_SESSION['google_data']);
					unset($_SESSION['token']);
					unset($_SESSION['gog_email']);
					parent::add('e', 'Username Address already exists.');	
					return false;
				}
			}
			else
			{
				unset($_SESSION['google_data']);
				unset($_SESSION['token']);
				unset($_SESSION['gog_email']);
				parent::add('e', 'Email Address already exists.');	
				return false;
			}	
		} 
		else
		{
			unset($_SESSION['google_data']);
			unset($_SESSION['token']);
			unset($_SESSION['gog_email']);
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function login( array $data )
	{
		if( !empty( $data ) ){
			$username = mysqli_real_escape_string( $this->_con, $data['username']);
			$type = mysqli_real_escape_string( $this->_con, $data['set']);
			$password = mysqli_real_escape_string( $this->_con,  md5($data['password']) );


			if((empty($username)) || (empty($type)) || (empty($password)) ) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}	
			
			$conditions = array('username'=>$username,'password'=>$password,'type'=>$type);
			$conditions1 = array('email'=>$username,'password'=>$password,'type'=>$type);
			if($this->checkrecord('pr_members','*',$conditions))
			{
				$data = $this->getrecord('pr_members','*',$conditions);
				if($data->status == 1)
				{
					$_SESSION['account']['id'] = $data->id;
					$_SESSION['account']['email'] = $data->email;
					unset($_SESSION['seller']);
					unset($_SESSION['guast']);
					parent::add('s', 'Welcome! You are successfully login in your account panel.');					
					return true;
				}
				else
				{
					parent::add('e', 'You have not verified your Account yet. Please check your Email Account.');	
					return false;
				}
			}
			elseif($this->checkrecord('pr_members','*',$conditions1))
			{
				$data = $this->getrecord('pr_members','*',$conditions1);
				if($data->status == 1)
				{
					$_SESSION['account']['id'] = $data->id;
					$_SESSION['account']['email'] = $data->email;
					unset($_SESSION['seller']);
					unset($_SESSION['guast']);
					parent::add('s', 'Welcome! You are successfully login in your account panel.');					
					return true;
				}
				else
				{
					parent::add('e', 'You have not verified your Account yet. Please check your Email Account.');	
					return false;
				}
			}
			else
			{
				parent::add('e', 'Username and Password not matched.');	
				return false;
			}
			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}
	
	public function sellerlogin( array $data )
	{
		if( !empty( $data ) ){
			$username = mysqli_real_escape_string( $this->_con, $data['username']);
			$type = mysqli_real_escape_string( $this->_con, $data['set']);
			$password = mysqli_real_escape_string( $this->_con,  md5($data['password']) );


			if((empty($username)) || (empty($type)) || (empty($password)) ) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}	
			
			$conditions = array('username'=>$username,'password'=>$password,'type'=>$type);
			$conditions1 = array('email'=>$username,'password'=>$password,'type'=>$type);
			if($this->checkrecord('pr_seller','*',$conditions))
			{
				$data = $this->getrecord('pr_seller','*',$conditions);
				if($data->status == 1)
				{
					$_SESSION['seller']['id'] = $data->id;
					$_SESSION['seller']['email'] = $data->email;
					unset($_SESSION['account']);
					unset($_SESSION['guast']);
					parent::add('s', 'Welcome! You are successfully login in your account panel.');
					return true;
				}
				else
				{
					parent::add('e', 'You have not verified your Account yet. Please check your Email Account.');	
					return false;
				}
			}
			elseif($this->checkrecord('pr_seller','*',$conditions1))
			{
				$data = $this->getrecord('pr_seller','*',$conditions1);
				if($data->status == 1)
				{
					$_SESSION['seller']['id'] = $data->id;
					$_SESSION['seller']['email'] = $data->email;
					unset($_SESSION['account']);
					unset($_SESSION['guast']);
					parent::add('s', 'Welcome! You are successfully login in your account panel.');
					return true;
				}
				else
				{
					parent::add('e', 'You have not verified your Account yet. Please check your Email Account.');	
					return false;
				}
			}
			else
			{
				parent::add('e', 'Username and Password not matched.');	
				return false;
			}
			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function sendemail( $email, $subject, $message )
	{
		if( !empty( $email ) && !empty( $subject ) && !empty( $message ) )
		{

			if ($this->is_email( $email)) 
			{
				$email = mysqli_real_escape_string( $this->_con, $email);
			} 
			else 
			{				
				parent::add('e', 'Please enter a valid email address!');
				return false;
			}
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$headers .= 'From: '.APP_NAME.' <'.ADMIN_EMAIL.'>' . "\r\n";
			mail($email, $subject, $message, $headers);
			return true;
			
		} 
		else
		{
			parent::add('e', '(*) Fields are required ...');	
			return false;
		}
	}
	
	public function personal( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$address1 = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['address1'], ENT_QUOTES ));
			$address2 = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['address2'], ENT_QUOTES ));
			$postalcode = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['postalcode'], ENT_QUOTES ));
			$country = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['country'], ENT_QUOTES ));
			$state = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['state'], ENT_QUOTES ));
			$city = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['city'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($address1)) || (empty($address2)) || (empty($postalcode)) || (empty($country)) || (empty($state)) || (empty($city)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "insert into pr_memberinfo SET address1 ='".$address1."', 
				address2 ='".$address2."', 
				postalcode ='".$postalcode."',
				country ='".$country."',
				state ='".$state."',
				city ='".$city."', 
				member ='".$id."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Personal Information has been update in database successfully.');	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function addpayment( $postdata, $email )
	{
		if(!empty( $postdata ))
		{
			$trimmed_data = $postdata;
			$photoid = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photoid'], ENT_QUOTES ));
			$photographer = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photographer'], ENT_QUOTES ));
			$txnid = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['txnid'], ENT_QUOTES ));
			$amount = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['amount'], ENT_QUOTES ));
			$phototype = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['phototype'], ENT_QUOTES ));
			$photoname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photoname'], ENT_QUOTES ));
			$ack = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['ack'], ENT_QUOTES ));
			$size = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['size'], ENT_QUOTES ));
			$photographername1 = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photographername1'], ENT_QUOTES ));

			if((empty($photoid)) || (empty($photographer)) || (empty($txnid)) || (empty($amount)) || (empty($phototype)) || (empty($ack))) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				date_default_timezone_set("Europe/Oslo");
				$date = date('Y-m-d h:i:s');
				if(!empty($_SESSION['account']['id']))
				{
					$buyerreal = $_SESSION['account']['id'];
				}
				if(!empty($_SESSION['guast']['email']))
				{
					$buyerreal = $_SESSION['guast']['email'];
				}
				if($_SESSION['currency'] == 'USD') {
					$currency123 = 'USD';
				}
				if($_SESSION['currency'] == 'EURO') {
					$currency123 = 'EURO';
				}
				$query = "INSERT INTO pr_payments SET buyer = '".$buyerreal."', photo = '".$photoid."', photographer = '".$photographer."', type = '".$phototype."', txnid = '".$txnid."', currency = '".$currency123."', payment = '".$ack."', amount = '".$amount."', size = '".$size."', download = 'NotDownload', date = '".$date."'";
				if(mysqli_query($this->_con, $query))
				{
					$conditions = array('id'=>$photographer);
					$records = $this->getrecord('pr_seller','*',$conditions);
					$pemail = $records->email;

					$conditions = array('id'=>$photoid);
					$photodetail = $this->getrecord('pr_photos','*',$conditions);

					$image123465 = 'http://www.photorunner.no/uploads/photos/real/';

					$photolink = base64_encode($photoid);

					$subject = "Order Placement ".APP_NAME."";
					$message = "<div style='color:#00A2B5; font-size:46px; font-family:arial; font-weight:bold; margin:20px;'>PhotoRunner</div>".
					"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'>Dear User your order has been successfully placed</div><br/><br/>".
					"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'><b>Order Details:-</b><div></b> <br/>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer Name</div><div style='font-size:15px; width:420px;'> : ".$records->username."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer Country</div><div style='font-size:15px; width:420px;'> : ".$records->country."</div>".
"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer State</div><div style='font-size:15px; width:420px;'> : ".$records->state."</div>".
"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer City</div><div style='font-size:15px; width:420px;'> : ".$records->city."</div>".


"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Name</div><div style='font-size:15px; width:420px;'> : ".$photoname."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Type</div><div style='font-size:15px; width:420px;'> : ".$phototype."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Size</div><div style='font-size:15px; width:420px;'> : ".$size."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Amount</div><div style='font-size:15px; width:420px;'> : $ ".$amount." USD</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photo Width</div><div style='font-size:15px; width:420px;'> : ".$photodetail->imagewidth." px</div>".
"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photo Height</div><div style='font-size:15px; width:420px;'> : ".$photodetail->imageheight." px</div><br/>".

"<div style='font-size:15px; margin-left:20px;'><a href='".APP_URL."view-photo.php?view==".$photolink."' >Click here</a> to view purchase again</div><br/>".


"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'></div><div style='font-size:15px; width:420px;'><img src='".$image123465."".$photodetail->webfile."' style='margin:10px;' /></div>".


"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
					"</div>";
					if($this->sendemail($email,$subject,$message))
					{
						$conditions = array('id'=>$photoid);
						$galleryname1 = $this->getrecord('pr_photos','*',$conditions);

						$conditions = array('id'=>$galleryname1->gallery);
						$galleryname2 = $this->getrecord('pr_galleries','*',$conditions);

						$subject = "Photo Sold ".APP_NAME."";
						$image123 = 'http://www.photorunner.no/uploads/photos/real/';
						$message = "<div style='color:#00A2B5; font-size:46px; font-family:arial; font-weight:bold; margin:20px;'>PhotoRunner</div>".
						"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'>Dear photographer your photo has been sold successfully</div><br/><br/>".
						"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'><b>Order Details:-</b><div></b> <br/>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Gallery Name</div><div style='font-size:15px; width:420px;'> : ".$galleryname2->name."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Name</div><div style='font-size:15px; width:420px;'> : ".$photoname."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Type</div><div style='font-size:15px; width:420px;'> : ".$phototype."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Size</div><div style='font-size:15px; width:420px;'> : ".$size."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Amount</div><div style='font-size:15px; width:420px;'> :  $ ".$amount." USD</div><br/><br/>".

		"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'></div><div style='font-size:15px; width:420px;'><img src='".$image123."".$galleryname1->webfile."' style='width:280px; height:200px; margin:10px;' /></div>".
	
	"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
						"</div>";
						if($this->sendemail($pemail,$subject,$message))
						{
							return true;
						}
						else
						{
							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function addprintpayment( $postdata, $email )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$photoid = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photoid'], ENT_QUOTES ));
			$photographer = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photographer'], ENT_QUOTES ));
			$txnid = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['txnid'], ENT_QUOTES ));
			$amount = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['amount'], ENT_QUOTES ));
			$phototype = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['phototype'], ENT_QUOTES ));
			$photoname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photoname'], ENT_QUOTES ));
			$ack = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['ack'], ENT_QUOTES ));
			$size = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['size'], ENT_QUOTES ));
			$photographername1 = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photographername1'], ENT_QUOTES ));

			if((empty($photoid)) || (empty($photographer)) || (empty($txnid)) || (empty($amount)) || (empty($phototype)) || (empty($ack))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				if(!empty($_SESSION['account']['id']))
				{
					$buyerreal = $_SESSION['account']['id'];
				}
				if(!empty($_SESSION['guast']['email']))
				{
					$buyerreal = $_SESSION['guast']['email'];
				}
				if($_SESSION['currency'] == 'USD') {
					$currency123 = 'USD';
				}
				if($_SESSION['currency'] == 'EURO') {
					$currency123 = 'EURO';
				}
				$date = date('Y-m-d h:i:s');
				$query = "INSERT INTO pr_payments SET buyer = '".$buyerreal."', photo = '".$photoid."', photographer = '".$photographer."', type = '".$phototype."', txnid = '".$txnid."', currency = '".$currency123."', payment = '".$ack."', amount = '".$amount."', size = '".$size."', download = 'NotDownload', date = '".$date."'";
				if(mysqli_query($this->_con, $query))
				{
					$conditions = array('id'=>$photographer);
					$records = $this->getrecord('pr_seller','*',$conditions);
					$pemail = $records->email;

					$conditions = array('id'=>$photoid);
					$photodetail = $this->getrecord('pr_photos','*',$conditions);

					$image123465 = 'http://www.photorunner.no/uploads/photos/real/';
					$photolink = base64_encode($photoid);

					$subject = "Order Placement ".APP_NAME."";
					$message = "<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
					"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'>Dear User your order has been successfully placed</div><br/><br/>".
					"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'><b>Order Details:-</b><div></b> <br/>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer Name</div><div style='font-size:15px; width:420px;'> : ".$records->username."</div>".


"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer Country</div><div style='font-size:15px; width:420px;'> : ".$records->country."</div>".
"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer State</div><div style='font-size:15px; width:420px;'> : ".$records->state."</div>".
"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photographer City</div><div style='font-size:15px; width:420px;'> : ".$records->city."</div>".


"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Name</div><div style='font-size:15px; width:420px;'> : ".$photoname."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Type</div><div style='font-size:15px; width:420px;'> : ".$phototype."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Size</div><div style='font-size:15px; width:420px;'> : ".$size."</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Amount</div><div style='font-size:15px; width:420px;'> :  $ ".$amount." USD</div>".

"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photo Width</div><div style='font-size:15px; width:420px;'> : ".$photodetail->imagewidth." px</div>".
"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Photo Height</div><div style='font-size:15px; width:420px;'> : ".$photodetail->imageheight." px</div><br/>".

"<div style='font-size:15px; margin-left:20px;'><a href='".APP_URL."view-photo.php?view==".$photolink."' >Click here</a> to view purchase again</div><br/>".


"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'></div><div style='font-size:15px; width:420px;'><img src='".$image123465."".$photodetail->webfile."' style='margin:10px;' /></div>".


"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
					"</div>";
					if($this->sendemail($email,$subject,$message))
					{
						$conditions = array('id'=>$photoid);
						$galleryname1 = $this->getrecord('pr_photos','*',$conditions);

						$conditions = array('id'=>$galleryname1->gallery);
						$galleryname2 = $this->getrecord('pr_galleries','*',$conditions);

						$image1234 = 'http://www.photorunner.no/uploads/photos/real/';
						$subject = "Photo Sold ".APP_NAME."";
						$message = "<div style='color:#00A2B5; font-size:46px; font-family:arial; font-weight:bold; margin:20px;'>PhotoRunner</div>".
						"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'>Dear photographer your photo has been sold successfully</div><br/><br/>".
						"<div style='text-align:left; font-family:arial; font-size:16px; margin-left:60px;'><b>Order Details:-</b><div></b> <br/>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Gallery Name</div><div style='font-size:15px; width:420px;'> : ".$galleryname2->name."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Name</div><div style='font-size:15px; width:420px;'> : ".$photoname."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Type</div><div style='font-size:15px; width:420px;'> : ".$phototype."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>File Size</div><div style='font-size:15px; width:420px;'> : ".$size."</div>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'>Amount</div><div style='font-size:15px; width:420px;'> :  $ ".$amount." USD</div><br/><br/>".

	"<div style='font-size:15px; font-weight:bold; width:180px; float:left; font-family:arial; text-align:left; margin-left:20px;'></div><div style='font-size:15px; width:420px;'><img src='".$image1234."".$galleryname1->webfile."' style='width:280px; height:200px; margin:10px;' /></div>".

	"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
						"</div>";
						if($this->sendemail($pemail,$subject,$message))
						{
							return true;
						}
						else
						{
							parent::add('e', 'Somthing went wrong. Please try again.');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}

				}
				else
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}
	
			} 
				
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function downloadwebfilee( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));

			if((empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "Update pr_payments SET download = 'Download' WHERE id = '".$id."'";
				if(mysqli_query($this->_con, $query))
				{	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function printfile( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));

			if((empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "Update pr_payments SET download = 'Download' WHERE id = '".$id."'";
				if(mysqli_query($this->_con, $query))
				{	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function getsearch($tables,$coloums,$conditions) 
	{
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key LIKE '%".mysqli_real_escape_string( $this->_con, $value )."%'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
		
		if(!empty($tables))
		{			

			$tables = "SELECT $coloums FROM $tables $condition";
			
		}
			
		$query = $tables;
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	public function printfilee( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$print = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['print'], ENT_QUOTES ));

			if((empty($print))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "Update pr_payments SET download = 'Download' WHERE photo = '6511321651'";
				if(mysqli_query($this->_con, $query))
				{	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}



	public function editpersonal( $postdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['firstname'], ENT_QUOTES ));
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['lastname'], ENT_QUOTES ));
			$mobile = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['mobile'], ENT_QUOTES ));
			$address1 = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['address1'], ENT_QUOTES ));
			$address2 = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['address2'], ENT_QUOTES ));
			$postalcode = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['postalcode'], ENT_QUOTES ));
			$country = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['country'], ENT_QUOTES ));
			$state = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['state'], ENT_QUOTES ));
			$city = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['city'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($address1)) || (empty($address2)) || (empty($firstname)) || (empty($lastname)) || (empty($mobile)) || (empty($postalcode)) || (empty($country)) || (empty($state)) || (empty($city)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "UPDATE pr_memberinfo SET address1 ='".$address1."', 
				address2 ='".$address2."', 
				postalcode ='".$postalcode."',
				country ='".$country."',
				state ='".$state."',
				city ='".$city."' where member = '".$id."'";
				mysqli_query($this->_con, $query);

				$queryname = "UPDATE pr_members SET firstname ='".$firstname."', 
				lastname ='".$lastname."', 
				mobile ='".$mobile."' where id = '".$id."'";
				if(mysqli_query($this->_con, $queryname))
				{
					parent::add('s', 'Personal Information has been update successfully.');	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updateseller( $postdata, $filesdata )
	{
		if(!empty( $postdata ))
		{
			
			$trimmed_data = $postdata;
			$category = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['category'], ENT_QUOTES ));
			$firstname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['firstname'], ENT_QUOTES ));
			$lastname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['lastname'], ENT_QUOTES ));
			$zip_code = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['zip_code'], ENT_QUOTES ));
			$business_name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['business_name'], ENT_QUOTES ));
			$phone_number = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['phone_number'], ENT_QUOTES ));
			$country = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['country'], ENT_QUOTES ));
			$state = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['state'], ENT_QUOTES ));
			$city = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['city'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));

			$about = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['about'], ENT_QUOTES ));
			$area = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['area'], ENT_QUOTES ));
			$price = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['price'], ENT_QUOTES ));

			$bankname = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['bankname'], ENT_QUOTES ));
			$owner_name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['owner_name'], ENT_QUOTES ));
			$banknumber = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['banknumber'], ENT_QUOTES ));


			$priceeuro = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['priceeuro'], ENT_QUOTES ));

			$pricetext = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['pricetext'], ENT_QUOTES ));


			if((empty($category)) || (empty($firstname)) || (empty($about)) || (empty($area)) || (empty($price)) || (empty($priceeuro)) || (empty($lastname)) || (empty($zip_code)) || (empty($business_name)) || (empty($phone_number))  || (empty($pricetext)) || (empty($country)) || (empty($state)) || (empty($city)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				if(!empty($filesdata['banner1']))
				{

					$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
					$ext = explode('.', basename($filesdata['banner1']['name']));
					$file_extension = end($ext);
					$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path = APP_ROOT."uploads/seller/" . $filename;  

					if(in_array($file_extension, $validextensions)) 
					{
						if (move_uploaded_file($filesdata['banner1']['tmp_name'], $file_target_path)) 
						{ 
							$query1 = "UPDATE pr_seller SET banner1 ='".$filename."' where id = '".$id."'";
							mysqli_query($this->_con, $query1);
						}
					}
				}
				if(!empty($filesdata['banner2']))
				{

					$validextensions = array("jpeg", "jpg", "png", "gif");
					$ext = explode('.', basename($filesdata['banner2']['name']));
					$file_extension = end($ext);
					$filename1 = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path1 = APP_ROOT."uploads/seller/" . $filename1;  

					if(in_array($file_extension, $validextensions)) 
					{
						if (move_uploaded_file($filesdata['banner2']['tmp_name'], $file_target_path1)) 
						{ 
							$query2 = "UPDATE pr_seller SET banner2 ='".$filename1."' where id = '".$id."'";
							mysqli_query($this->_con, $query2);
						}
					}
				}
				$query = "UPDATE pr_seller SET category ='".$category."', 
				firstname ='".$firstname."', 
				lastname ='".$lastname."',
				zip_code ='".$zip_code."',
				business_name ='".$business_name."',
				phone_number ='".$phone_number."',
				country ='".$country."',
				state ='".$state."',
				about ='".$about."',

				bankname ='".$bankname."',
				owner_name ='".$owner_name."',
				banknumber ='".$banknumber."',

				area ='".$area."',
				price ='".$price."',

				priceeuro ='".$priceeuro."',

				pricetext ='".$pricetext."',

				city ='".$city."' where id = '".$id."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Seller Profile has been update successfully.');	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	
	
	public function photorunner( $postdata )
	{

		if(!empty( $postdata ))
		{
			$trimmed_data = $postdata;
			$coupons = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['coupons'], ENT_QUOTES ));
			$newsletter = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['newsletter'], ENT_QUOTES ));
			$photorunner = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['photorunner'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($coupons)) || (empty($newsletter)) || (empty($photorunner)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "UPDATE pr_members SET coupons ='".$coupons."', 
				newsletter ='".$newsletter."', 
				photorunner ='".$photorunner."' where id = '".$id."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Email Notification has been update successfully.');	
					return true;
				}	
			} 
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function addgallery( $data, $filesdata )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$password = mysqli_real_escape_string( $this->_con, $trimmed_data['password'] );
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = APP_ROOT."uploads/galleries/" . $filename;  
				
				if(in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						/*if($file_extension == 'jpeg')
						{
							$this->watermark_gallery1($file_target_path, $file_target_path);
						}
						if($file_extension == 'jpg')
						{
							$this->watermark_gallery1($file_target_path, $file_target_path);
						}
						if($file_extension == 'png')
						{
							$this->watermark_gallery2($file_target_path, $file_target_path);
						}
						if($file_extension == 'gif')
						{
							$this->watermark_gallery3($file_target_path, $file_target_path);
						}*/

						$entered = @date('Y-m-d H:i:s');
						$query = "INSERT INTO pr_galleries SET name = '".$name."',password = '".$password."', seller='".$_SESSION['seller']['id']."',image ='".$filename."',date ='".$entered."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Gallery has been added successfully.');	
							return true;
						}
					}
				}
			}
			else
			{
				parent::add('e', '(*)All Fields are required.');	
				return false;
			}
				
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	public function watermark_gallery1($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo45.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_gallery2($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo45.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_gallery3($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	
	public function updategallery( $data, $filesdata )
	{
		
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$password = mysqli_real_escape_string( $this->_con, $trimmed_data['password'] );
			if(!empty($name))   
			{
				if(!empty($filesdata['image']['name']))
				{
					$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
					$ext = explode('.', basename($filesdata['image']['name']));
					$file_extension = end($ext);
					$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path = "../uploads/galleries/" . $filename;  
					
					if(in_array($file_extension, $validextensions)) 
					{
						move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path);
						/*if($file_extension == 'jpeg')
						{
							$this->watermark_gallery1($file_target_path, $file_target_path);
						}
						if($file_extension == 'jpg')
						{
							$this->watermark_gallery1($file_target_path, $file_target_path);
						}
						if($file_extension == 'png')
						{
							$this->watermark_gallery2($file_target_path, $file_target_path);
						}
						if($file_extension == 'gif')
						{
							$this->watermark_gallery3($file_target_path, $file_target_path);
						}*/
						@unlink(APP_ROOT."uploads/galleries/" . $_POST['oldimage']);
						$files .= " , image = '$filename'";
					}
				}	
				$entered = @date('Y-m-d H:i:s');
				$query = "UPDATE pr_galleries SET name = '".$name."', password = '".$password."' $files  WHERE id = '".base64_decode($_GET['id'])."' AND seller = '".$_SESSION['seller']['id']."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Gallery has been updated successfully.');	
					return true;
				}					
			}
			else
			{
				parent::add('e', '(*)All Fields are required.');	
				return false;
			}
				
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function addphoto( $data, $filesdata )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$gallery = mysqli_real_escape_string( $this->_con, $trimmed_data['gallery'] );
			$webfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['webfileprice'] );
			$printfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['printfileprice'] );
			if(!empty($name) && !empty($category) && !empty($gallery) && !empty($webfileprice) && !empty($printfileprice))   
			{
				if(!empty($filesdata['webfile']['name']))
				{
					$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
					$ext = explode('.', basename($filesdata['webfile']['name']));
					$file_extension = end($ext);
					$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path = APP_ROOT."uploads/photos/real/" . $filename;  
					$file_target_path3 = APP_ROOT."uploads/photos/watermark/" . $filename; 
					$file_target_path4 = APP_ROOT."uploads/photos/bigwatermark/" . $filename; 

					if(in_array($file_extension, $validextensions)) 
					{

 						if (move_uploaded_file($_FILES['webfile']['tmp_name'], $file_target_path)) 
						{ 

							$this->watermark_image($file_target_path, $file_target_path3);
							$this->watermark_image2($file_target_path, $file_target_path4);

		
							if(!empty($filesdata['printfile']['name']))
							{
								$ext1 = explode('.', basename($filesdata['printfile']['name']));
								$file_extension1 = end($ext1);
								$filename1 = md5(uniqid()) . "." . $ext1[count($ext1) - 1];
								$file_target_path1 = APP_ROOT."uploads/photos/real/" . $filename1;  
								
								if(in_array($file_extension1, $validextensions)) 
								{
									if (move_uploaded_file($_FILES['printfile']['tmp_name'], $file_target_path1)) 
									{
										$entered = @date('Y-m-d H:i:s');
										$query = "INSERT INTO pr_photos SET name = '".$name."',seller='".$_SESSION['seller']['id']."',category='".$category."',gallery='".$gallery."', webfile ='".$filename."',printfile ='".$filename1."',webfileprice ='".$webfileprice."',printfileprice ='".$printfileprice."',date ='".$entered."'";
										if(mysqli_query($this->_con, $query))
										{
											parent::add('s', 'Photo Info has been added successfully.');	
											return true;
										}
									}
								}
								else
								{
									parent::add('e', 'Something went wrong. Please try again');	
									return false;
								}
							}
							else
							{
								parent::add('e', 'Please upload valid file extension and size.1');	
								return false;
							}
						}
						else
						{
echo 222; exit;
							parent::add('e', 'Something went wrong. Please try again');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Please upload valid file extension and size.2');	
						return false;
					}
				}
				else
				{
					parent::add('e', '(*)All Fields are required.');	
					return false;
				}
			}
			else
			{
				parent::add('e', '(*)All Fields are required.');	
				return false;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}



	public function addphoto1( $data, $filesdata,  $filesdate)
	{

		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$webfile = $filesdata;
			$printfile = $filesdate;
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$gallery = mysqli_real_escape_string( $this->_con, $trimmed_data['gallery'] );
			$webfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['webfileprice'] );
			$printfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['printfileprice'] );
			$printfilepricea3 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea3'] );
			$printfilepricea4 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea4'] );
			$printfilepricea5 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea5'] );

			$webfilepriceeuro = mysqli_real_escape_string( $this->_con, $trimmed_data['webfilepriceeuro'] );
			$printfilepricea3euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea3euro'] );
			$printfilepricea4euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea4euro'] );
			$printfilepricea5euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea5euro'] );
			$otherpriceeuro = mysqli_real_escape_string( $this->_con, $trimmed_data['otherpriceeuro'] );

			$othertitle = mysqli_real_escape_string( $this->_con, $trimmed_data['othertitle'] );
			$otherprice = mysqli_real_escape_string( $this->_con, $trimmed_data['otherprice'] );
			$massage = mysqli_real_escape_string( $this->_con, $trimmed_data['massage'] );

			$sellwebpublik = mysqli_real_escape_string( $this->_con, $trimmed_data['sellwebpublik'] );
			$sellprintpublik = mysqli_real_escape_string( $this->_con, $trimmed_data['sellprintpublik'] );


			$imagewidth = mysqli_real_escape_string( $this->_con, $trimmed_data['imagewidth'] );
			$imageheight = mysqli_real_escape_string( $this->_con, $trimmed_data['imageheight'] );


			if(!empty($name) && !empty($category) && !empty($gallery))   
			{
				if(!empty($webfile['name']))
				{
					$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
					$ext = explode('.', basename($webfile['name']));
					$file_extension = end($ext);
					$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path = APP_ROOT."uploads/photos/real/" . $filename;  
					//$file_target_path3 = APP_ROOT."uploads/photos/watermark/" . $filename; 
					$file_target_path4 = APP_ROOT."uploads/photos/bigwatermark/" . $filename; 

					if(in_array($file_extension, $validextensions)) 
					{

 						if (move_uploaded_file($webfile['tmp_name'], $file_target_path)) 
						{ 
							$image_info = getimagesize($webfile["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];
							if($file_extension == 'jpeg')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'jpg')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'png')
							{
								//$this->watermark_image3($file_target_path, $file_target_path3);
								$this->watermark_image4($file_target_path, $file_target_path4);
							}
							if($file_extension == 'gif')
							{
								//$this->watermark_image5($file_target_path, $file_target_path3);
								$this->watermark_image6($file_target_path, $file_target_path4);
							}

							if($file_extension == 'JPEG')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'JPG')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'PNG')
							{
								//$this->watermark_image3($file_target_path, $file_target_path3);
								$this->watermark_image4($file_target_path, $file_target_path4);
							}
							if($file_extension == 'GIF')
							{
								//$this->watermark_image5($file_target_path, $file_target_path3);
								$this->watermark_image6($file_target_path, $file_target_path4);
							}
							$entered = @date('Y-m-d H:i:s');



							$query = "INSERT INTO pr_photos SET name = '".$name."',seller='".$_SESSION['seller']['id']."',category='".$category."',gallery='".$gallery."', webfile ='".$filename."',printfile ='".$filename1."',webfileprice ='".$webfileprice."',printfileprice ='".$printfileprice."',printfilepricea3 ='".$printfilepricea3."',printfilepricea4 ='".$printfilepricea4."',printfilepricea5 ='".$printfilepricea5."', webfilepriceeuro ='".$webfilepriceeuro."',printfilepricea3euro ='".$printfilepricea3euro."',printfilepricea4euro ='".$printfilepricea4euro."',printfilepricea5euro ='".$printfilepricea5euro."',otherpriceeuro ='".$otherpriceeuro."', date ='".$entered."',othertitle ='".$othertitle."',otherprice ='".$otherprice."',massage ='".$massage."',imagewidth ='".$imagewidth."',imageheight ='".$imageheight."',sellwebpublik ='".$sellwebpublik."',sellprintpublik ='".$sellprintpublik."'";
							mysqli_query($this->_con, $query);
							$_SESSION['check']['query'] = 'yes';
						}
						else
						{
							parent::add('e', 'Something went wrong. Please try again');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Please upload valid file extension and size.');	
						return false;
					}
				}
				else
				{
					parent::add('e', '(*)All Fields are required2.');	
					return false;
				}
			}
			else
			{
				parent::add('e', '(*)All Fields are required3.');	
				return false;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required4.');	
			return false;
		}
	}

	


	public function addphoto5( $data, $filesdata,  $filesdate)
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$webfile = $filesdata;
			$printfile = $filesdate;
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$gallery = mysqli_real_escape_string( $this->_con, $trimmed_data['gallery'] );
			$webfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['webfileprice'] );
			$printfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['printfileprice'] );
			$printfilepricea3 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea3'] );
			$printfilepricea4 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea4'] );
			$printfilepricea5 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea5'] );

			$webfilepriceeuro = mysqli_real_escape_string( $this->_con, $trimmed_data['webfilepriceeuro'] );
			$printfilepricea3euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea3euro'] );
			$printfilepricea4euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea4euro'] );
			$printfilepricea5euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea5euro'] );
			$otherpriceeuro = mysqli_real_escape_string( $this->_con, $trimmed_data['otherpriceeuro'] );

			$imagewidth = mysqli_real_escape_string( $this->_con, $trimmed_data['imagewidth'] );
			$imageheight = mysqli_real_escape_string( $this->_con, $trimmed_data['imageheight'] );

			$sellwebpublik = mysqli_real_escape_string( $this->_con, $trimmed_data['sellwebpublik'] );
			$sellprintpublik = mysqli_real_escape_string( $this->_con, $trimmed_data['sellprintpublik'] );


			$othertitle = mysqli_real_escape_string( $this->_con, $trimmed_data['othertitle'] );
			$otherprice = mysqli_real_escape_string( $this->_con, $trimmed_data['otherprice'] );
			$massage = mysqli_real_escape_string( $this->_con, $trimmed_data['massage'] );


			if(!empty($name) && !empty($category) && !empty($gallery))   
			{
				if(!empty($webfile['name']))
				{
					$image_info = getimagesize($webfile["tmp_name"]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];

					$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
					$ext = explode('.', basename($webfile['name']));
					$file_extension = end($ext);
					$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path = APP_ROOT."uploads/photos/real/" . $filename;  
					//$file_target_path3 = APP_ROOT."uploads/photos/watermark/" . $filename; 
					$file_target_path4 = APP_ROOT."uploads/photos/bigwatermark/" . $filename; 

					if(in_array($file_extension, $validextensions)) 
					{

 						if (move_uploaded_file($webfile['tmp_name'], $file_target_path)) 
						{ 
							$image_info = getimagesize($webfile["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							if($file_extension == 'jpeg')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'jpg')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'png')
							{
								//$this->watermark_image3($file_target_path, $file_target_path3);
								$this->watermark_image4($file_target_path, $file_target_path4);
							}
							if($file_extension == 'gif')
							{
								//$this->watermark_image5($file_target_path, $file_target_path3);
								$this->watermark_image6($file_target_path, $file_target_path4);
							}

							if($file_extension == 'JPEG')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'JPG')
							{
								//$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'PNG')
							{
								//$this->watermark_image3($file_target_path, $file_target_path3);
								$this->watermark_image4($file_target_path, $file_target_path4);
							}
							if($file_extension == 'GIF')
							{
								//$this->watermark_image5($file_target_path, $file_target_path3);
								$this->watermark_image6($file_target_path, $file_target_path4);
							}

							$entered = @date('Y-m-d H:i:s');
							$query = "INSERT INTO pr_photos SET name = '".$name."',seller='".$_SESSION['seller']['id']."',category='".$category."',gallery='".$gallery."', webfile ='".$filename."',printfile ='".$filename1."',webfileprice ='".$webfileprice."',printfileprice ='".$printfileprice."',printfilepricea3 ='".$printfilepricea3."',printfilepricea4 ='".$printfilepricea4."',printfilepricea5 ='".$printfilepricea5."',webfilepriceeuro ='".$webfilepriceeuro."',printfilepricea3euro ='".$printfilepricea3euro."',printfilepricea4euro ='".$printfilepricea4euro."',printfilepricea5euro ='".$printfilepricea5euro."',otherpriceeuro ='".$otherpriceeuro."',date ='".$entered."',othertitle ='".$othertitle."',otherprice ='".$otherprice."',massage ='".$massage."',imagewidth ='".$imagewidth."',imageheight ='".$imageheight."',sellwebpublik ='".$sellwebpublik."',sellprintpublik ='".$sellprintpublik."'";
							if(mysqli_query($this->_con, $query))
							{
								$_SESSION['check']['query'] = 'yes';
								$lastid = mysqli_insert_id($this->_con);
								return $lastid;
							}
						}
						else
						{
							parent::add('e', 'Something went wrong. Please try again');	
							return false;
						}
					}
					else
					{
						parent::add('e', 'Please upload valid file extension and size.1');	
						return false;
					}
				}
				else
				{
					parent::add('e', '(*)All Fields are required2.');	
					return false;
				}
			}
			else
			{
				parent::add('e', '(*)All Fields are required3.');	
				return false;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required4.');	
			return false;
		}
	}






	public function sentgallery($gallery, $email, $password)
	{
		$lastid = mysqli_insert_id($this->_con);
		$password1 = $password->password;
		$code = base64_encode($_POST['gallery']);
		$subject = "Something New in Photorunner";
		$message ="<html><body>
		<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px; font-family:arial;'>PhotoRunner</div>".
		"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:50%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center; font-family:arial;'>To get in touch with new exciting things, just click on the button below<br/><br/>".
		"<a href='".APP_URL."photos.php?gallery=".$code."&&lock=unlock&&email=".$email."&&password=".$password1."' style='color:#fff; font-family:arial; text-decoration:none; font-size:22px; font-weight:bold; margin-bottom:20px; padding-bottom:10px;'><div style='width:250px; margin-left:auto; margin-right:auto; background-color:#00A2B5; height:50px; font-family:arial; border-radius:5px; padding-top:15px; padding-bottom:15px; margin-bottom:30px;'> Click Me !</div></a></div><br/><br/>".
		"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>Passsword = ".$password1."</div><br/>".
		"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
		"<div style='font-size:14px; text-align:left;'>Team<br/>Photo Runner</div>".
		"</div></body></html>";

		if($this->sendemail($email,$subject,$message))
		{
			return true;
		}

	}


	public function sentgalleryunsecore($gallery, $email)
	{
		$lastid = mysqli_insert_id($this->_con);
		$code = base64_encode($_POST['gallery']);
		$subject = "Something New in Photorunner";
		$message ="<html><body>
		<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px; font-family:arial;'>PhotoRunner</div>".
		"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:50%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center; font-family:arial;'>To get in touch with new exciting things, just click on the button below<br/><br/>".
		"<a href='".APP_URL."photos.php?gallery=".$code."&&email=".$email."&&password=".$password."' style='color:#fff; font-family:arial; text-decoration:none; font-size:22px; font-weight:bold; margin-bottom:20px; padding-bottom:10px;'><div style='width:250px; margin-left:auto; margin-right:auto; background-color:#00A2B5; height:50px; font-family:arial; border-radius:5px; padding-top:15px; padding-bottom:15px; margin-bottom:30px;'> Click Me !</div></a></div><br/><br/>".

		"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
		"<div style='font-size:14px; text-align:left;'>Team<br/>Photo Runner</div>".
		"</div></body></html>";

		if($this->sendemail($email,$subject,$message))
		{
			return true;
		}

	}	



	public function watermark_image($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function watermark_image2($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 1100;  
		$height = 800;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 710 - $w_width; 
		$pos_y = 440 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_image3($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function watermark_image4($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 1100;  
		$height = 800;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 710 - $w_width; 
		$pos_y = 440 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_image5($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function watermark_image6($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 1100;  
		$height = 800;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 710 - $w_width; 
		$pos_y = 440 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}


	public function watermark_imagesmall($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/Instagram.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 200;  
		$height = 150;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 120 - $w_width; 
		$pos_y = 100 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function watermark_image2small($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/Instagram.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 200;  
		$height = 150;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 120 - $w_width; 
		$pos_y = 100 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_image3small($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/Instagram.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 200;  
		$height = 150;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 120 - $w_width; 
		$pos_y = 100 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function watermark_image4small($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/Instagram.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 200;  
		$height = 150;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 120 - $w_width; 
		$pos_y = 100 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_image5small($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/Instagram.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 200;  
		$height = 150;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 120 - $w_width; 
		$pos_y = 100 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function watermark_image6small($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/Instagram.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 200;  
		$height = 150;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 120 - $w_width; 
		$pos_y = 100 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function updatephoto( $data, $filesdata )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$gallery = mysqli_real_escape_string( $this->_con, $trimmed_data['gallery'] );
			$webfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['webfileprice'] );
			$printfileprice = mysqli_real_escape_string( $this->_con, $trimmed_data['printfileprice'] );
			$printfilepricea3 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea3'] );
			$printfilepricea4 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea4'] );
			$printfilepricea5 = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea5'] );

			$imagewidth = mysqli_real_escape_string( $this->_con, $trimmed_data['imagewidth'] );
			$imageheight = mysqli_real_escape_string( $this->_con, $trimmed_data['imageheight'] );

			$sellwebpublik = mysqli_real_escape_string( $this->_con, $trimmed_data['sellwebpublik'] );
			$sellprintpublik = mysqli_real_escape_string( $this->_con, $trimmed_data['sellprintpublik'] );

			$webfilepriceeuro = mysqli_real_escape_string( $this->_con, $trimmed_data['webfilepriceeuro'] );
			$printfilepricea3euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea3euro'] );
			$printfilepricea4euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea4euro'] );
			$printfilepricea5euro = mysqli_real_escape_string( $this->_con, $trimmed_data['printfilepricea5euro'] );
			$otherpriceeuro = mysqli_real_escape_string( $this->_con, $trimmed_data['otherpriceeuro'] );


			$othertitle = mysqli_real_escape_string( $this->_con, $trimmed_data['othertitle'] );
			$otherprice = mysqli_real_escape_string( $this->_con, $trimmed_data['otherprice'] );

			if(!empty($name) && !empty($category) && !empty($gallery) && !empty($webfileprice))   
			{
				$files = '';
				if(!empty($filesdata['webfile']['name']))
				{
					$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
					$ext = explode('.', basename($filesdata['webfile']['name']));
					$file_extension = end($ext);
					$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
					$file_target_path = APP_ROOT."uploads/photos/real/" . $filename;  
					$file_target_path3 = APP_ROOT."uploads/photos/watermark/" . $filename; 
					$file_target_path4 = APP_ROOT."uploads/photos/bigwatermark/" . $filename;  
					
					if(in_array($file_extension, $validextensions)) 
					{
						if(move_uploaded_file($_FILES['webfile']['tmp_name'], $file_target_path))
						{
							if($file_extension == 'jpeg')
							{
								$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'jpg')
							{
								$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'png')
							{
								$this->watermark_image3($file_target_path, $file_target_path3);
								$this->watermark_image4($file_target_path, $file_target_path4);
							}
							if($file_extension == 'gif')
							{
								$this->watermark_image5($file_target_path, $file_target_path3);
								$this->watermark_image6($file_target_path, $file_target_path4);
							}
							if($file_extension == 'JPEG')
							{
								$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'JPG')
							{
								$this->watermark_image($file_target_path, $file_target_path3);
								$this->watermark_image2($file_target_path, $file_target_path4);
							}
							if($file_extension == 'PNG')
							{
								$this->watermark_image3($file_target_path, $file_target_path3);
								$this->watermark_image4($file_target_path, $file_target_path4);
							}
							if($file_extension == 'GIF')
							{
								$this->watermark_image5($file_target_path, $file_target_path3);
								$this->watermark_image6($file_target_path, $file_target_path4);
							}
							@unlink(APP_ROOT."uploads/photos/real/" . $_POST['oldwebfile']);
							@unlink(APP_ROOT."uploads/photos/watermark/" . $_POST['oldwebfile']);
							@unlink(APP_ROOT."uploads/photos/bigwatermark/" . $_POST['oldwebfile']);
							$files .= "webfile = '$filename',";
						}
					}
				}

				$query = "UPDATE pr_photos SET name = '".$name."', category='".$category."',gallery='".$gallery."', $files webfileprice ='".$webfileprice."',printfileprice ='".$printfileprice."',printfilepricea3 ='".$printfilepricea3."',printfilepricea4 ='".$printfilepricea4."',printfilepricea5 ='".$printfilepricea5."', webfilepriceeuro ='".$webfilepriceeuro."',printfilepricea3euro ='".$printfilepricea3euro."',printfilepricea4euro ='".$printfilepricea4euro."',printfilepricea5euro ='".$printfilepricea5euro."',otherpriceeuro ='".$otherpriceeuro."',othertitle ='".$othertitle."',otherprice ='".$otherprice."',imagewidth ='".$imagewidth."',sellwebpublik ='".$sellwebpublik."',sellprintpublik ='".$sellprintpublik."',imageheight ='".$imageheight."' WHERE id = '".base64_decode($_GET['id'])."' AND seller = '".$_SESSION['seller']['id']."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Photo Info has been updated successfully.');	
					return true;
				}
						
			}
			else
			{
				parent::add('e', '(*)All Fields are required.');	
				return false;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatephoto( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_photos SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Photo has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatephoto( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_photos SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Photo has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function verifyaccount( $verifykey )
	{
		if( !empty( $verifykey ) )
		{
			$verifykey = mysqli_real_escape_string( $this->_con, $verifykey);
			
			$conditions = array('code'=>$verifykey, 'status'=> 0);
			if($this->checkrecord('pr_members','*',$conditions) )
			{
				$query = "UPDATE pr_members SET status = '1' WHERE code = '".$verifykey."' AND status = '0'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Your account has been activated successfully');	
					return true;
				}
				else
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}	
			}
			else
			{
				parent::add('e', 'Somthing went wrong. Please try again.');	
				return false;
			}			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function verifyaccountt( $verifykey )
	{
		if( !empty( $verifykey ) )
		{
			$verifykey = mysqli_real_escape_string( $this->_con, $verifykey);
			
			$conditions = array('code'=>$verifykey, 'status'=> 0);
			if($this->checkrecord('pr_seller','*',$conditions) )
			{
				$query = "UPDATE pr_seller SET status = '1' WHERE code = '".$verifykey."' AND status = '0'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Your account has been activated successfully');	
					return true;
				}
				else
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}	
			}
			else
			{
				parent::add('e', 'Somthing went wrong. Please try again.');	
				return false;
			}			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	
	public function forgotpasswordbuyer( $data )
	{
		if(!empty( $data ) )
		{
			$email = mysqli_real_escape_string( $this->_con, htmlentities($data['email'], ENT_QUOTES) );
			if ($this->is_email( $email)) 
			{
				$email = mysqli_real_escape_string( $this->_con, $email);
			} 
			else 
			{				
				parent::add('e', 'Please enter a valid email address!');
				return false;
			}
			
			if(empty($email))
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$digits = 8;
			$password = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$passwordHash = md5($password);
		
			$conditions = array('email'=>$email, 'type'=>'buyer');
			if($this->checkrecord('pr_members','*',$conditions) )
			{

				$conditions = array('email'=>$email);
				$forgotpassword = $this->getrecord('pr_members','*',$conditions);

				$query = "UPDATE pr_members SET password ='".$passwordHash."' WHERE email ='".$email."' and type = 'buyer'";
				if(mysqli_query($this->_con, $query))
				{
					$forgotusername = $forgotpassword->username;
					$subject = "Forget your password ".APP_NAME."";
					$message = "<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
					"<div style='text-align:center; font-family:arial;'>Dear ".$forgotusername." Please follow the below details to login</div><br/><br/>".
					"<div style='text-align:center; font-family:arial;'><b>Forgot Password Details:-</b><div></b> <br/>".
					"Username: ".$forgotusername."<br/><br/>".
					"Password: ".$password."<br/><br/>".
					"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
					"</div>";
					if($this->sendemail($email,$subject,$message))
					{
						parent::add('s', 'Password has been updated & sent to your Email Address.');
						return true;
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
					
				}	
				else 
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}
			}
			else
			{
				parent::add('e', 'Somthing went wrong. Please try again.');	
				return false;
			}		
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function forgotpasswordseller( $data )
	{
		if(!empty( $data ) )
		{
			$email = mysqli_real_escape_string( $this->_con, htmlentities($data['email'], ENT_QUOTES) );
			if ($this->is_email( $email)) 
			{
				$email = mysqli_real_escape_string( $this->_con, $email);
			} 
			else 
			{				
				parent::add('e', 'Please enter a valid email address!');
				return false;
			}
			
			if(empty($email))
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$digits = 8;
			$password = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$passwordHash = md5($password);
		
			$conditions = array('email'=>$email, 'type'=>'seller');
			if($this->checkrecord('pr_seller','*',$conditions) )
			{

				$conditions = array('email'=>$email);
				$forgotpasswordseller = $this->getrecord('pr_seller','*',$conditions);
				$sellerforgot = $forgotpasswordseller->username;
				$query = "UPDATE pr_seller SET password ='".$passwordHash."' WHERE email ='".$email."' and type = 'seller'";
				if(mysqli_query($this->_con, $query))
				{
					$subject = "Forget your password ".APP_NAME."";
					$message = "<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
					"<div style='text-align:center; font-family:arial;'>Dear ".$sellerforgot." Please follow the below details to login</div><br/><br/>".
					"<div style='text-align:center; font-family:arial;'><b>Forgot Password Details:-</b><div></b> <br/>".
					"Username: ".$sellerforgot."<br/><br/>".
					"Password: ".$password."<br/><br/>".
					"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
					"</div>";
					if($this->sendemail($email,$subject,$message))
					{
						parent::add('s', 'Password has been updated & sent to your Email Address.');
						return true;
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
					
				}	
				else 
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}
			}
			else
			{
				parent::add('e', 'Somthing went wrong. Please try again.');	
				return false;
			}		
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function is_email($email) 
	{
		if(@eregi('\\\r',$email) == true || @eregi('\\\n',$email) == true){
			return false;
		}
		$regex = '/.*@.*\..*/';
		if(preg_match($regex, $email) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getrecords($table,$coloums,$conditions) 
	{
		
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	public function getrecordssss($table,$coloums,$conditions) 
	{
		
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition ORDER BY ID desc";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}


	public function getrecordss($table,$coloums,$conditions,$limt) 
	{
		
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition $limt";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	
	
	public function getrecordswithend($table,$coloums,$conditions,$endsql) 
	{
		
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition $endsql";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	public function getrecordswithendd($table,$coloums,$conditions,$endsql,$lmit) 
	{
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition $endsql $limt";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}
	
	
	public function getrecord($table,$coloums,$conditions) 
	{
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			
			$data = mysqli_fetch_object($result);
			return $data;
		}
		else
		{
			return false;
		}		
	}

	public function getrecordd($table,$coloums,$conditions,$limt) 
	{
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition,$limt";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			
			$data = mysqli_fetch_object($result);
			return $data;
		}
		else
		{
			return false;
		}		
	}
	
	public function checkrecord($table,$coloums,$conditions) 
	{
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			if(mysqli_num_rows($result) > 0)
			{
				return true;
			}
			else
			{
				return false;
			}			
		}
		else
		{
			return false;
		}		
	}	
	
	
	public function changepassword( array $data )
	{
		if( !empty( $data ) ){

			$trimmed_data = $data;
			$oldpassword = mysqli_real_escape_string( $this->_con, md5($trimmed_data['oldpassword']) );
			$password = mysqli_real_escape_string( $this->_con,  md5($trimmed_data['password']) );
			$id = mysqli_real_escape_string( $this->_con,  $trimmed_data['id'] );
			
			if((empty($oldpassword)) || (empty($password))  || (empty($id))) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "SELECT * FROM pr_members where id = '$id' and password = '$oldpassword' ";
			$result = mysqli_query($this->_con, $query) or die(mysqli_error());		
			$count = mysqli_num_rows($result);			
			if( $count == 1)
			{
				$query1 = "UPDATE pr_members SET password = '".$password."' WHERE id = '".$id."'";
				if(mysqli_query($this->_con, $query1))
				{
					parent::add('s', 'Password has been updated successfully.');	
					return true;
				}	
			}
			else
			{
				parent::add('e', 'Please Fill Your Correct Password.');	
				return false;
			}
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function changepasswordseller( array $data )
	{
		if( !empty( $data ) ){

			$trimmed_data = $data;
			$oldpassword = mysqli_real_escape_string( $this->_con, md5($trimmed_data['oldpassword']) );
			$password = mysqli_real_escape_string( $this->_con,  md5($trimmed_data['password']) );
			$id = mysqli_real_escape_string( $this->_con,  $trimmed_data['id'] );
			
			if((empty($oldpassword)) || (empty($password))  || (empty($id))) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "SELECT * FROM pr_seller where id = '$id' and password = '$oldpassword' ";
			$result = mysqli_query($this->_con, $query) or die(mysqli_error());		
			$count = mysqli_num_rows($result);			
			if( $count == 1)
			{
				$query1 = "UPDATE pr_seller SET password = '".$password."' WHERE id = '".$id."'";
				if(mysqli_query($this->_con, $query1))
				{
					parent::add('s', 'Password has been updated successfully.');	
					return true;
				}	
			}
			else
			{
				parent::add('e', 'Please Fill Your Correct Password.');	
				return false;
			}
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}
	
	
	public function count($table,$coloums,$conditions) 
	{
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			
			$data = mysqli_num_rows($result);
			return $data;
		}
		else
		{
			return false;
		}		
	}
	

	public function countreviews($table,$conditions) 
	{
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT sum( `rating` ) as rating , count( id ) as rows FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{	
			$data = mysqli_fetch_object($result);
			return $data;
		}
		else
		{
			return false;
		}		
	}


	public function getlimitrecords($table,$coloums,$conditions,$sqlend) 
	{
		
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
			
		$query = "SELECT $coloums FROM $table $condition $sqlend";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}
	
	public function changeemail( array $data )
	{
		if( !empty( $data ) ){

			$trimmed_data = $data;
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$password = mysqli_real_escape_string( $this->_con, md5($trimmed_data['password']) );
			$id = mysqli_real_escape_string( $this->_con,  $trimmed_data['id'] );
			
			if((empty($email)) || (empty($password))  || (empty($id))) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$conditions = array('email'=>$email);
			if(!$this->checkrecord('pr_members','*',$conditions) )
			{
				$query = "SELECT * FROM pr_members where id = '$id' and password = '$password' ";
				$result = mysqli_query($this->_con, $query) or die(mysqli_error());		
				$count = mysqli_num_rows($result);			
				if( $count == 1)
				{
					$query = "UPDATE pr_members SET email = '".$email."' WHERE id = '".$id."'";
					if(mysqli_query($this->_con, $query))
					{
						parent::add('s', 'Email has been updated successfully.');	
						return true;
						
					}	
				}
				else
				{
					parent::add('e', 'Please Fill Your Correct Password.');	
					return false;
				}
			}
			else
			{
				parent::add('e', 'Email already exist. Please try again.');	
				return false;
			}
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}
	
	public function updateprofilepicture( $data )
	{
		if(!empty( $data ) )
		{
			if(!empty($data['profilepicture']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
				$ext = explode('.', basename($data['profilepicture']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = APP_ROOT."uploads/buyer/" . $filename;  

				if(in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($data['profilepicture']['tmp_name'], $file_target_path)) 
					{
						if($file_extension == 'jpeg')
						{
							$this->watermark_buyer1($file_target_path, $file_target_path);
						}
						if($file_extension == 'jpg')
						{
							$this->watermark_buyer1($file_target_path, $file_target_path);
						}
						if($file_extension == 'png')
						{
							$this->watermark_buyer2($file_target_path, $file_target_path);
						}
						if($file_extension == 'gif')
						{
							$this->watermark_buyer3($file_target_path, $file_target_path);
						}
						$query = "UPDATE pr_members SET profilepicture ='".$filename."' WHERE id ='".$_SESSION['account']['id']."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Profile has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}
				else
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function watermark_buyer1($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo45.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_buyer2($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo45.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_buyer3($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	public function updateprofilepictureseller( $data )
	{
		if(!empty( $data ) )
		{
			if(!empty($data['profilepicture']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png", "gif", "JPEG", "JPG", "PNG", "GIF");
				$ext = explode('.', basename($data['profilepicture']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = APP_ROOT."uploads/seller/" . $filename;  

				if(in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($data['profilepicture']['tmp_name'], $file_target_path)) 
					{
						if($file_extension == 'jpeg')
						{
							$this->watermark_seller1($file_target_path, $file_target_path);
						}
						if($file_extension == 'jpg')
						{
							$this->watermark_seller1($file_target_path, $file_target_path);
						}
						if($file_extension == 'png')
						{
							$this->watermark_seller2($file_target_path, $file_target_path);
						}
						if($file_extension == 'gif')
						{
							$this->watermark_seller3($file_target_path, $file_target_path);
						}

						$query = "UPDATE pr_seller SET profilepicture ='".$filename."' WHERE id ='".$_SESSION['seller']['id']."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Profile has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						return false;
					}
				}
				else
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function watermark_seller1($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo45.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_seller2($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo45.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefrompng($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagepng($im, $new_image_name);
		imagedestroy($im);
		return true;
	}

	public function watermark_seller3($oldimage_name, $new_image_name)
	{
		$image_path = APP_ROOT."images/logo.png";
		list($owidth,$oheight) = getimagesize($oldimage_name);
		$width = 500;  
		$height = 400;  
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromgif($oldimage_name);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($image_path);
		list($w_width, $w_height) = getimagesize($image_path);        
		$pos_x = 460 - $w_width; 
		$pos_y = 220 - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagegif($im, $new_image_name);
		imagedestroy($im);
		return true;
	}
	
	
	public function delete($table,$conditions) 
	{
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' and ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "DELETE FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			parent::add('s', 'Record has been deleted successfully.');	
			return true;
		}
		else
		{
			parent::add('e', 'Something went wrong. Please try again.');	
			return false;
		}

	}

	public function deletereview( $data )
	{
		if( !empty( $data ))
		{

			$delete = mysqli_real_escape_string( $this->_con, htmlentities($data['delete'], ENT_QUOTES) );
		 
			$queryy = "DELETE from pr_review where photo = '".$delete."'";
			mysqli_query($this->_con, $queryy);
		  
			$query = "UPDATE pr_payments SET review = '0' where id = '".$delete."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Review has been deleted successfully.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}
	
	
	public function insertrecord( $table, $data)
	{
		if( !empty( $data ) )
		{
			$postvalue = array();
			foreach($data as $key=>$value)
			{
				if(!empty($value))
				{
					$value = mysqli_real_escape_string( $this->_con, htmlentities($value, ENT_QUOTES) );
					$postvalue[] = " $key = '$value' ";
				}				
			}
			
			$sqlvalues = implode(' , ', $postvalue);

			$query = "INSERT INTO $table SET $sqlvalues";
			if(mysqli_query($this->_con, $query))
			{
				$lastid = mysqli_insert_id($this->_con);
				return $lastid;
			}
			else
			{
				parent::add('e', 'Somthing went wrong. Please try again.');	
				return false;
			}			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	

	public function updaterecord( $table, $data, $conditions) 
	{
		if( !empty( $data ) )
		{
			// escape variables for security
			$postvalue = array();
			foreach($data as $key=>$value)
			{
				if(!empty($value) && $key != 'id' && $value != 'Save' && $value != 'Update'  )
				{
					$value = mysqli_real_escape_string( $this->_con, htmlentities($value, ENT_QUOTES) );
					$postvalue[] = " $key = '$value' ";
				}				
			}
			$sqlvalues = implode(' , ', $postvalue);
			
			// Trim all the incoming data:
			$conditions = @array_map('trim', $conditions);
			
			if(!empty($conditions))
			{			
				foreach($conditions as $key=>$value)
				{
					// escape variables for security
					$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
				}
				
				$condition = " WHERE ";	
				$condition .= @implode(' AND ', $conditionvalues);
			}
			else
			{
				$condition = "";
			}

			$query = "UPDATE $table SET $sqlvalues $condition";
			//echo"<br/>";
			if(mysqli_query($this->_con, $query))
			{
				return true;
			}
			else
			{
				parent::add('e', 'Somthing went wrong. Please try again.');	
				return false;
			}			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}
	
	public function deleterecords($table,$conditions) 
	{
		// Trim all the incoming data:
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				// escape variables for security
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "DELETE FROM $table $condition"; 
		$result = mysqli_query($this->_con, $query);
		if($result)
		{			
			return true;
		}
		else
		{
			return false;
		}		
	}

	public function getpagirecords($table,$coloums,$conditions) 
	{	
		$query = "SELECT $coloums FROM $table $conditions";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	public function getpagirecordss($table,$coloums,$conditions,$limt) 
	{	
		$query = "SELECT $coloums FROM $table $limt $conditions ";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}
	
	public function getpagirecordsss($table,$coloums,$endsql) 
	{	
		$query = "SELECT $coloums FROM $table  $endsql";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	public function getpagirecordssss($table,$coloums,$conditions) 
	{	
		$query = "SELECT $coloums FROM $table $conditions ";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}




	public function getpagirecordsql($table,$coloums,$conditions,$endsql) 
	{	
		$query = "SELECT $coloums FROM $table $conditions $endsql";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			$data = array();
			while($row = mysqli_fetch_object($result))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
			return false;
		}

	}

	public function pagination($query, $per_page = 10,$page = 1, $url = '?')
   	{
    	$query = "SELECT COUNT(*) as `num` FROM {$query}";
		$result = mysqli_query($this->_con, $query);
		$row = mysqli_fetch_array($result);
    	$total = $row['num'];
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
    } 


	public function paginationaaaaa($query,$test, $per_page = 10,$page = 1, $url = '?')
   	{
    	$query = "SELECT {$test}  FROM {$query}";
		$result = mysqli_query($this->_con, $query);
		$countrows = mysqli_num_rows($result);
    	$total = $countrows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
    	} 
 

	public function countrecords($table,$coloums,$conditions) 
	{
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				$conditionvalues[] = "$key = '".mysqli_real_escape_string( $this->_con, $value )."'";
			}
			
			$condition = " WHERE ";	
			$condition .= @implode(' AND ', $conditionvalues);
		}
		else
		{
			$condition = "";
		}
			
		$query = "SELECT $coloums FROM $table $condition";
		$result = mysqli_query($this->_con, $query);
		if($result)
		{
			
			$data = mysqli_num_rows($result);
			return $data;
		}
		else
		{
			return false;
		}		
	}

	public function add_productreview( $data )
	{
		if( !empty( $data ))
		{
			$paymentid = mysqli_real_escape_string( $this->_con, htmlentities($data['paymentid'], ENT_QUOTES) );
			$photo = mysqli_real_escape_string( $this->_con, htmlentities($data['photo'], ENT_QUOTES) );
			$rating = mysqli_real_escape_string( $this->_con, htmlentities($data['rating'], ENT_QUOTES) );
			$review = mysqli_real_escape_string( $this->_con, htmlentities($data['review'], ENT_QUOTES) );

			if((empty($photo)) || (empty($rating)) || (empty($review))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$buyer = $_SESSION['account']['id'];
			$buyer_email = $_SESSION['account']['email'];
			$date = @date('Y-m-d H:i:s');
		 

			$query = "INSERT INTO pr_review SET buyer = '".$buyer."', buyer_email = '".$buyer_email."', paymentid = '".$paymentid."',photo='".$photo."', rating = '".$rating."', review = '".$review."', date = '".$date."'";
			mysqli_query($this->_con, $query);
			$queryy = "Update pr_payments SET review = '1' WHERE id = '".$paymentid."'";
			if(mysqli_query($this->_con, $queryy))
			{
				parent::add('s', 'Review has been posted successfully.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}


	public function contact_us_email( $data )
	{
		if( !empty( $data ))
		{
			
			$trimmed_data = $data;
			$name = mysqli_real_escape_string( $this->_con, htmlentities($data['name'], ENT_QUOTES) );
			$email = mysqli_real_escape_string( $this->_con, htmlentities($data['email'], ENT_QUOTES) );
			$country = mysqli_real_escape_string( $this->_con, htmlentities($data['country'], ENT_QUOTES) );
			$enquiry = mysqli_real_escape_string( $this->_con, htmlentities($data['enquiry'], ENT_QUOTES) );
			$message = mysqli_real_escape_string( $this->_con, htmlentities($data['message'], ENT_QUOTES) );
			
			if((empty($name)) || (empty($email)) || (empty($country)) || (empty($enquiry)) || (empty($message))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
			{
				parent::add('e', 'Code Not Matched');	
				return false;
			}
			
			$datet = @date("Y-m-d H:i:s");
			$query = "INSERT INTO bz_contactus_msg SET message = '".$message."',name ='".$name."',email ='".$email."',country ='".$country."',enquiry ='".$enquiry."', date_time = '".$datet."'";
			if(mysqli_query($this->_con, $query))
			{
				$eng_email = $email;
				$subject = 'HMBYME Contact US';
				$message1 = $message;
			
				$subject = $subject;
				$message ="<html><body>
				<div style='width:100%; border:10px solid #00A2B5; font-family:arial; font-size:18px; border-radius:10px;'><div style='color:#6B555A;'>".$message1."</div><br/><br/><div style='font-size:14px; text-align:center;'>2016 Sell on HMbyme!. All Rights Reserved </div><br/>".
				"</div></body></html>";
				if($this->sendemail($eng_email,$subject,$message))
				{
					parent::add('s', 'Your Message has been Sent successfully');
					return true;
				}
				else
				{
					parent::add('e', 'Somthing went wrong. Please try again.');	
					return false;
				}
			}
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}
	


	
}
?>
