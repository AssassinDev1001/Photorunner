<?php

class Cl_Common extends Cl_Messages
{
	protected $_con;

	public function __construct()
	{
		$db = new Cl_DBclass();
		$this->_con = $db->con;
	}
	
	public function redirect($url)
	{
		header('Location:'.$url);
		exit();
	}
	
	public function create_slug( $table, $column, $string ){
		$slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
		$conditions = array($column=>$slug);
		$checkExist = $this->checkrecord($table,'*',$conditions);
		if($checkExist)
		{
			$string = $slug."-copy";
			$slug = $this->create_slug( $table, $column, $string );
			return $slug;
		}
		return $slug;
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

	public function adminlogin( array $data )
	{
		$_SESSION['logged_in'] = false;
		if( !empty( $data ) ){

			$trimmed_data = $data;
			$username = mysqli_real_escape_string( $this->_con, $trimmed_data['username']);
			$password = mysqli_real_escape_string( $this->_con,  md5($trimmed_data['password']) );
			

			if((empty($username)) || (empty($password)) ) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$query = "SELECT * FROM pr_admin where username = '$username' and password = '$password' ";
			$result = mysqli_query($this->_con, $query) or die(mysqli_error());		
			$count = mysqli_num_rows($result);
			if( $count == 1)
			{
				$data = mysqli_fetch_assoc($result);
				$_SESSION['admin']['id'] = $data['id'];
				$_SESSION['admin']['email'] = $data['email'];
				parent::add('s', 'Welcome! You are successfully login in your account panel.');					
				return true;
			}
			else
			{
				parent::add('e', 'Username or Password not matched or Account Deactivated.');	
				return false;
			}
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function snewsletter( $data, $email )
	{
		$newsletter = $data['newsletter'];

		if((empty($newsletter))) 
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}

		$subject = "Photo Runner";
		$message = "<div style='color:#00A2B5; font-family:arial; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
		"".$newsletter."<br/><br/>".
		"<div style='font-size:14px; text-align:left; font-family:arial;'>Team<br/>Photo Runner</div>".
		"</div>";
		if($this->sendemail($email,$subject,$message))
		{
			return true;
		}
		else
		{	
			return false;
		}
	}

	public function deactivate( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_members SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Buyer has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateseller( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_seller SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Photographer has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivategallery( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_gallery SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Gallery has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activate( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_members SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Buyer has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activateseller( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_seller SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Photographer has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activategallery( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_gallery SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Gallery has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function category( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;

			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			if((empty($category))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO pr_categories SET category ='".$category."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Category has been saved in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function updatecategory( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($category)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE pr_categories SET category ='".$category."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Category has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatecategory( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_categories SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Category has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatecategory( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_categories SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Category has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateartist( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_artist SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateartist( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_artist SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function subcategory( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$header = mysqli_real_escape_string( $this->_con, $trimmed_data['header'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$subcategory = mysqli_real_escape_string( $this->_con, $trimmed_data['subcategory'] );
			$subcategory2 = mysqli_real_escape_string( $this->_con, $trimmed_data['subcategory2'] );
			if((empty($category)) || (empty($subcategory)) || (empty($subcategory2)) || (empty($header))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_subcategory SET subcategory2 = '".$subcategory2."',header='".$header."',category ='".$category."', subcategory ='".$subcategory."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Subcategory has been save in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatesubcategory( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$header = mysqli_real_escape_string( $this->_con, $trimmed_data['header'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$subcategory = mysqli_real_escape_string( $this->_con, $trimmed_data['subcategory'] );
			$subcategory2 = mysqli_real_escape_string( $this->_con, $trimmed_data['subcategory2'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($category)) || (empty($subcategory)) || (empty($id)) || (empty($subcategory2)) || (empty($header))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bz_subcategory SET subcategory2 = '".$subcategory2."',header='".$header."',category ='".$category."', subcategory ='".$subcategory."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Subcategory has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function deactivatesubcategory( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_subcategory SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Subcategory has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatesubcategory( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_subcategory SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Subcategory has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function country( array $data )
	{
		if(!empty( $data ))
		{

			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			if((empty($country))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO pr_country SET country ='".$country."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Country has been save in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	public function updatecountry( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($country)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE pr_country SET country ='".$country."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Country has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updatesocial( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$url = mysqli_real_escape_string( $this->_con, $trimmed_data['url'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($name)) || (empty($url)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE pr_social SET name ='".$name."', url ='".$url."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Social network has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function deactivatecountry( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_country SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Country has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatesocial( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_social SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Social network been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatecountry( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_country SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Country has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activatesocial( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_social SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Social network has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function state( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			if((empty($country)) || (empty($state))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO pr_state SET country ='".$country."', state ='".$state."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'State has been save in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatestate( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($country)) || (empty($state)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE pr_state SET country ='".$country."', state ='".$state."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'State has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function deactivatestate( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_state SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'State has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatestate( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_state SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'State has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
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
	
	
	public function news( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;

			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$code = mysqli_real_escape_string( $this->_con, $trimmed_data['code'] );
			if((empty($name)) || (empty($code))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bw_languages SET name ='".$name."', code ='".$code."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Language has been save in database successfully.');	
				return true;
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
	
	

	public function getrecord($table,$coloums,$conditions) 
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
	
	public function checkrecord($table,$coloums,$conditions) 
	{
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
	
	
	public function updatepage( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$headning = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['headning'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($headning)) || (empty($description)) || (empty($id))) 
			{	
				echo 111; exit;
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE pr_pages SET title = '".$title."', headning ='".$headning."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			echo 111; exit;
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function saveinformation( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$page_name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['page_name'], ENT_QUOTES ));
			$name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['name'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($language)) || (empty($page_name)) || (empty($name)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_Information SET language = '".$language."', page_name ='".$page_name."', name ='".$name."', description ='".$description."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Information Page has been save successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updateinformation( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$page_name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['page_name'], ENT_QUOTES ));
			$name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['name'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($page_name)) || (empty($name)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bz_Information SET language = '".$language."', page_name ='".$page_name."', name ='".$name."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Information Page has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	
	public function resumes( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$resumeslimit = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['resumeslimit'], ENT_QUOTES ));
			if((empty($resumeslimit))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_resumeslimit SET resumeslimit ='".$resumeslimit."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function updatelang( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$code = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['code'], ENT_QUOTES ));
			$name = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['name'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($code)) || (empty($name)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_languages SET code ='".$code."', name ='".$name."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function forgotpasswordbuyer( $data, $email )
	{
		if(!empty( $data ) )
		{
			$newsletter = mysqli_real_escape_string( $this->_con, htmlentities($data['newsletter'], ENT_QUOTES) );
			html_entity_decode($newsletter);			

			$subject = "NewsLetter ".APP_NAME."";
			$message = "<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px;'>PhotoRunner</div>".
			"<div style='text-align:center'>NewsLetter</div><br/><br/>".
			"<div style='text-align:center'><b>".$newsletter."</b><div></b> <br/>".
			"</div>";

			if($this->sendemail($email,$subject,$message))
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
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatenews( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_news SET title ='".$title."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatevent( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_events SET title ='".$title."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatejob( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_job_offers SET title ='".$title."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatecompanies( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_companies SET title ='".$title."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	
	public function updateinterviews( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_interviews SET title ='".$title."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	
	public function updateorientation( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bw_orientation SET title ='".$title."', description ='".$description."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	
	
	public function updateimage( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bw_news SET title ='".$title."', description ='".$description."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function eventimage( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bw_events SET title ='".$title."', description ='".$description."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function jobimage( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bw_job_offers SET title ='".$title."', description ='".$description."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function companiesimage( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bw_companies SET title ='".$title."', description ='".$description."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function interviewimage( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($description)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bw_interviews SET title ='".$title."', description ='".$description."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function addatirst( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$seller = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['seller'], ENT_QUOTES ));
			$heading = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['heading'], ENT_QUOTES ));
			$subheadning = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['subheadning'], ENT_QUOTES ));
			$story = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['story'], ENT_QUOTES ));
			if((empty($seller)) || (empty($heading)) || (empty($subheadning)) || (empty($story))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			if(!empty($filesdata['banner']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['banner']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/artist/" . $filename;  
				
				if(($_FILES["banner"]["size"] < 100000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['banner']['tmp_name'], $file_target_path)) 
					{
						$query = "insert into pr_artist SET seller ='".$seller."' , heading ='".$heading."' , subheadning ='".$subheadning."' , story ='".$story."' , banner ='".$filename."' , status ='1'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updateatirst( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$seller = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['seller'], ENT_QUOTES ));
			$heading = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['heading'], ENT_QUOTES ));
			$subheadning = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['subheadning'], ENT_QUOTES ));
			$story = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['story'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($seller)) || (empty($heading)) || (empty($subheadning)) || (empty($story)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			if(!empty($filesdata['banner']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['banner']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/artist/" . $filename;  
				
				if(($_FILES["banner"]["size"] < 100000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['banner']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE pr_artist SET seller ='".$seller."' , heading ='".$heading."' , subheadning ='".$subheadning."' , story ='".$story."' , banner ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			else
			{
				$query = "UPDATE pr_artist SET seller ='".$seller."' , heading ='".$heading."' , subheadning ='".$subheadning."' , story ='".$story."' WHERE id = '".$id."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Record has been updated successfully.');	
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

	public function homepage( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			$trimmed_data = $postdata;
			$number = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['number'], ENT_QUOTES ));
			$image8text = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['image8text'], ENT_QUOTES ));
			$image9text = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['image9text'], ENT_QUOTES ));
			$image10text = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['image10text'], ENT_QUOTES ));
			$image11text = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['image11text'], ENT_QUOTES ));
			$email = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['email'], ENT_QUOTES ));
			$bannerheading = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['bannerheading'], ENT_QUOTES ));
			$facilitiesheading = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['facilitiesheading'], ENT_QUOTES ));
			$firstdescription = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['firstdescription'], ENT_QUOTES ));
			$seconddescription = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['seconddescription'], ENT_QUOTES ));
			$thirddescription = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['thirddescription'], ENT_QUOTES ));
			$fourthdescription = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fourthdescription'], ENT_QUOTES ));
			$fifthdescription = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fifthdescription'], ENT_QUOTES ));
			$companydescription = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['companydescription'], ENT_QUOTES ));
			$copyright = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['copyright'], ENT_QUOTES ));

			$logotext = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['logotext'], ENT_QUOTES ));

			$firstimagetitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['firstimagetitle'], ENT_QUOTES ));
			$firstimagesubtitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['firstimagesubtitle'], ENT_QUOTES ));

			$secondimagetitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['secondimagetitle'], ENT_QUOTES ));
			$secondimagesubtitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['secondimagesubtitle'], ENT_QUOTES ));

			$thirdimagetitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['thirdimagetitle'], ENT_QUOTES ));
			$thirdimagesubtitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['thirdimagesubtitle'], ENT_QUOTES ));

			$fourtimagetitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fourtimagetitle'], ENT_QUOTES ));
			$fourtimagesubtitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fourtimagesubtitle'], ENT_QUOTES ));

			$fifthimagetitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fifthimagetitle'], ENT_QUOTES ));
			$fifthimagesubtitle = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['fifthimagesubtitle'], ENT_QUOTES ));


			if((empty($number)) || 
			(empty($email)) || 
			(empty($firstdescription)) || 
			(empty($seconddescription)) || 
			(empty($thirddescription)) || 
			(empty($fourthdescription)) || 
			(empty($fifthdescription)) || 
			(empty($logotext)) || 

			(empty($firstimagetitle)) || 
			(empty($firstimagesubtitle)) || 
			(empty($secondimagetitle)) || 
			(empty($secondimagesubtitle)) || 
			(empty($thirdimagetitle)) || 
			(empty($thirdimagesubtitle)) || 
			(empty($fourtimagetitle)) || 
			(empty($fourtimagesubtitle)) || 
			(empty($fifthimagetitle)) || 
			(empty($fifthimagesubtitle)) || 

			(empty($companydescription)) || (empty($copyright))) 
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image1']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image1']['name']));
				$file_extension = end($ext);
				$filename1 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename1;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image1']['tmp_name'], $file_target_path)) 
					{
						$query1 = "UPDATE pr_home SET image1 ='".$filename1."'";
						mysqli_query($this->_con, $query1);	
					} 
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image2']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image2']['name']));
				$file_extension = end($ext);
				$filename2 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename2;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image2']['tmp_name'], $file_target_path)) 
					{
						$query2 = "UPDATE pr_home SET image2 ='".$filename2."'";
						mysqli_query($this->_con, $query2);						
					} 
				}
			}
			if(!empty($filesdata['image3']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image3']['name']));
				$file_extension = end($ext);
				$filename3 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename3;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image3']['tmp_name'], $file_target_path)) 
					{
						$query3 = "UPDATE pr_home SET image3 ='".$filename3."'";	
						mysqli_query($this->_con, $query3);	
					} 
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image4']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image4']['name']));
				$file_extension = end($ext);
				$filename4 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename4;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image4']['tmp_name'], $file_target_path)) 
					{
						$query4 = "UPDATE pr_home SET image4 ='".$filename4."'";	
						mysqli_query($this->_con, $query4);	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image5']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image5']['name']));
				$file_extension = end($ext);
				$filename5 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename5;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image5']['tmp_name'], $file_target_path)) 
					{
						$query5 = "UPDATE pr_home SET image5 ='".$filename5."'";
						mysqli_query($this->_con, $query5);	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image6']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image6']['name']));
				$file_extension = end($ext);
				$filename6 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename6;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image6']['tmp_name'], $file_target_path)) 
					{
						$query6 = "UPDATE pr_home SET image6 ='".$filename6."'";	
						mysqli_query($this->_con, $query6);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image7']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image7']['name']));
				$file_extension = end($ext);
				$filename7 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename7;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image7']['tmp_name'], $file_target_path)) 
					{
						$query7 = "UPDATE pr_home SET image7 ='".$filename7."'";	
						mysqli_query($this->_con, $query7);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image8']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image8']['name']));
				$file_extension = end($ext);
				$filename8 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename8;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image8']['tmp_name'], $file_target_path)) 
					{
						$query8 = "UPDATE pr_home SET image8 ='".$filename8."'";	
						mysqli_query($this->_con, $query8);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image9']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image9']['name']));
				$file_extension = end($ext);
				$filename9 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename9;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image9']['tmp_name'], $file_target_path)) 
					{
						$query9 = "UPDATE pr_home SET image9 ='".$filename9."'";
						mysqli_query($this->_con, $query9);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image10']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image10']['name']));
				$file_extension = end($ext);
				$filename10 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename10;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image10']['tmp_name'], $file_target_path)) 
					{
						$query10 = "UPDATE pr_home SET image10 ='".$filename10."'";
						mysqli_query($this->_con, $query10);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image11']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image11']['name']));
				$file_extension = end($ext);
				$filename11 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename11;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image11']['tmp_name'], $file_target_path)) 
					{
						$query11 = "UPDATE pr_home SET image11 ='".$filename11."'";
						mysqli_query($this->_con, $query11);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image12']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image12']['name']));
				$file_extension = end($ext);
				$filename12 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename12;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image12']['tmp_name'], $file_target_path)) 
					{
						$query12 = "UPDATE pr_home SET image12 ='".$filename12."'";	
						mysqli_query($this->_con, $query12);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image13']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image13']['name']));
				$file_extension = end($ext);
				$filename13 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename13;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image13']['tmp_name'], $file_target_path)) 
					{
						$query13 = "UPDATE pr_home SET image13 ='".$filename13."'";
						mysqli_query($this->_con, $query13);	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image14']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image14']['name']));
				$file_extension = end($ext);
				$filename14 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename14;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image14']['tmp_name'], $file_target_path)) 
					{
						$query14 = "UPDATE pr_home SET image14 ='".$filename14."'";
						mysqli_query($this->_con, $query14);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			if(!empty($filesdata['image15']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image15']['name']));
				$file_extension = end($ext);
				$filename15 = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename15;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image15']['tmp_name'], $file_target_path)) 
					{
						$query15 = "UPDATE pr_home SET image15 ='".$filename15."'";
						mysqli_query($this->_con, $query15);		
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			$query = "UPDATE pr_home SET number ='".$number."', 
			email ='".$email."', 
			bannerheading ='".$bannerheading."', 
			facilitiesheading ='".$facilitiesheading."', 
			firstdescription ='".$firstdescription."', 
			seconddescription ='".$seconddescription."', 
			thirddescription ='".$thirddescription."', 
			fourthdescription ='".$fourthdescription."', 
			fifthdescription ='".$fifthdescription."', 
			copyright ='".$copyright."', 
			logotext ='".$logotext."', 

			firstimagetitle ='".$firstimagetitle."', 
			firstimagesubtitle ='".$firstimagesubtitle."', 
			secondimagetitle ='".$secondimagetitle."', 
			secondimagesubtitle ='".$secondimagesubtitle."', 
			thirdimagetitle ='".$thirdimagetitle."', 
			thirdimagesubtitle ='".$thirdimagesubtitle."', 
			fourtimagetitle ='".$fourtimagetitle."', 
			fourtimagesubtitle ='".$fourtimagesubtitle."', 
			fifthimagetitle ='".$fifthimagetitle."', 
			fifthimagesubtitle ='".$fifthimagesubtitle."', 
			image8text ='".$image8text."', 
			image9text ='".$image9text."', 
			image10text ='".$image10text."', 
			image11text ='".$image11text."', 
			companydescription ='".$companydescription."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Home Page has been updated successfully.');	
				return true;
			} 
			else 
			{
				$common->add('e', 'Somthing went wrong. Please try again.');	
				$common->redirect(APP_FULL_URL);
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
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
	
	public function addnews( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_news','news_url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_news SET url ='".$url."', title ='".$title."', description ='".$description."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in databse successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function addevent( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_events','url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_events SET url ='".$url."', title ='".$title."', description ='".$description."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in database successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function addjob( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_job_offers','url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_job_offers SET url ='".$url."', title ='".$title."', description ='".$description."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record been save in databse successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function addcompanies( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_companies','url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_companies SET url ='".$url."', title ='".$title."', description ='".$description."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in databse successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function addinterviews( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_interviews','url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_interviews SET url ='".$url."', title ='".$title."', description ='".$description."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in databse successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	
	
	
	
	
	public function addorientation( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_orientation','url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_orientation SET url ='".$url."', title ='".$title."', description ='".$description."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in databse successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function image( $filesdata )
	{
		if(!empty( $filesdata ))
		{
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "INSERT INTO bw_image SET image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in databse successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
			else
			{
				parent::add('e', '(*)All Fields are required.');	
				return false;
			}
			
		} 
	}
	
	
	
	public function video( $postdata )
	{
		if(!empty( $postdata ))
		{
			$trimmed_data = $postdata;
			$url = $this->create_slug('bw_video','url',$trimmed_data['title']);
			$title = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['title'], ENT_QUOTES ));
			$video = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['video'], ENT_QUOTES ));
			$description = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['description'], ENT_QUOTES ));
			if((empty($title)) || (empty($video)) || (empty($description))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			else
			{
				$query = "INSERT INTO bw_video SET url ='".$url."', title ='".$title."', video ='".$video."', description ='".$description."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Record has been save in databse successfully.');	
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
	
	public function slider( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bw_slider SET image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	
	public function stringLimit($string,$limit)
	{
		$string = strip_tags($string);
		$string = substr($string,0,$limit);
		$string = substr($string,0,strrpos($string," "));
		return $string;
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
    
    
    
   	public function getsearch($tables,$coloums,$conditions) 
	{
		
		// Trim all the incoming data:
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				// escape variables for security
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
			foreach($tables as $tables)
			{
				// escape variables for security
				$tablesvalues[] = "SELECT $coloums FROM $tables $condition";
			}
			
			$tables = "";	
			$tables .= @implode(' UNION ', $tablesvalues);
		}
		else
		{
			$tables = "";
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

	public function addlanguage( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			if((empty($language))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						
						$query = "INSERT INTO bz_language SET language ='".$language."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been saved in database successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function updatelanguage( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bz_language SET language ='".$language."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activatelanguage( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_language SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Language has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatelanguage( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_language SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Language has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	
	public function deactivatecity( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_city SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'City has been Deactivate successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatecity( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE pr_city SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'City has been Activate successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function city( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			if((empty($country)) || (empty($state)) || (empty($city))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO pr_city SET country ='".$country."', state ='".$state."',city = '".$city."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'City has been save in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function updatecity( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($country)) || (empty($state)) || (empty($id)) || (empty($city))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE pr_city SET country ='".$country."', state ='".$state."',city = '".$city."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'City has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function admin_query( $data )
	    {   
		if(!empty( $data ) )
		{

		    $trimmed_data = $data;            
		    $email = mysqli_real_escape_string( $this->_con, $trimmed_data['user_email'] );            
		    $message = mysqli_real_escape_string( $this->_con, $trimmed_data['message'] );
		    
		    if((empty($email)) ||  (empty($message)) ) 
		    {   
		        parent::add('e', '(*) Fields are required.');   
		        return false;
		    }            
		    $query = "INSERT INTO bz_contactus_msg SET email ='".$email."',message ='".$message."',status='0'";
		    if(mysqli_query($this->_con, $query))
		    {
		        parent::add('s', 'Message has been send successfully.');  
		        return true;
		    }   
		} 
		else
		{
		    parent::add('e', '(*)All Fields are required.');    
		    return false;
		}
	    }


	public function adminchangepassword( array $data )
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
			$query = "SELECT * FROM pr_admin where id = '$id' and password = '$oldpassword' ";
			$result = mysqli_query($this->_con, $query) or die(mysqli_error());		
			$count = mysqli_num_rows($result);			
			if( $count == 1)
			{
				$query1 = "UPDATE pr_admin SET password = '".$password."' WHERE id = '".$id."'";
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


	public function add_admin( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$logid = mysqli_real_escape_string( $this->_con, $trimmed_data['logid'] );
			$password = mysqli_real_escape_string( $this->_con, $trimmed_data['password'] );
			$fname = mysqli_real_escape_string( $this->_con, $trimmed_data['fname'] );
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$phone_no = mysqli_real_escape_string( $this->_con, $trimmed_data['phone_no'] );
			$manage_user = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_user'] );
			$manage_payment = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_payment'] );
			$manage_order = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_order'] );
			$manage_time = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_time'] );
			$manage_news = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_news'] );

			if((empty($logid)) || (empty($password)) || (empty($fname)) || (empty($email)) || (empty($phone_no))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			if((empty($manage_user)))
			{
				$manage_user1 = '0';
			}
			else
			{
				$manage_user1 = $manage_user;
			}

			if((empty($manage_payment)))
			{
				$manage_payment1 = '0';
			}
			else
			{
				$manage_payment1 = $manage_payment;
			}

			if((empty($manage_order)))
			{
				$manage_order1 = '0';
			}
			else
			{
				$manage_order1 = $manage_order;
			}

			if((empty($manage_time)))
			{
				$manage_time1 = '0';
			}
			else
			{
				$manage_time1 = $manage_time;
			}

			if((empty($manage_news)))
			{
				$manage_news1 = '0';
			}
			else
			{
				$manage_news1 = $manage_news;
			}
			$query = "INSERT INTO bz_add_admin SET admin_id = '".$logid."',password ='".$password."', name ='".$fname."',email = '".$email."',phone = '".$phone_no."',manage_user='".$manage_user1."',manage_payment='".$manage_payment1."',manage_order='".$manage_order1."',manage_timeslot='".$manage_time1."',manage_newsletter='".$manage_news1."',date=CURDATE()";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Admin has been added successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function contact_form( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$enquiry = mysqli_real_escape_string( $this->_con, $trimmed_data['enquiry'] );
			$click = mysqli_real_escape_string( $this->_con, $trimmed_data['click'] );
			$message = mysqli_real_escape_string( $this->_con, $trimmed_data['message'] );
			$button = mysqli_real_escape_string( $this->_con, $trimmed_data['button'] );
			$question1 = mysqli_real_escape_string( $this->_con, $trimmed_data['question1'] );
			$question2 = mysqli_real_escape_string( $this->_con, $trimmed_data['question2'] );
			$question3 = mysqli_real_escape_string( $this->_con, $trimmed_data['question3'] );
			$question4 = mysqli_real_escape_string( $this->_con, $trimmed_data['question4'] );
			$question5 = mysqli_real_escape_string( $this->_con, $trimmed_data['question5'] );
			$question6 = mysqli_real_escape_string( $this->_con, $trimmed_data['question6'] );
			$question7 = mysqli_real_escape_string( $this->_con, $trimmed_data['question7'] );
			$question8 = mysqli_real_escape_string( $this->_con, $trimmed_data['question8'] );
			$question9 = mysqli_real_escape_string( $this->_con, $trimmed_data['question9'] );
			$question10 = mysqli_real_escape_string( $this->_con, $trimmed_data['question10'] );
			$question11 = mysqli_real_escape_string( $this->_con, $trimmed_data['question11'] );
			
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_registration_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				$query = "INSERT INTO bz_contact_us SET language = '".$language."',name ='".$name."',email = '".$email."',country = '".$country."',enquiry = '".$enquiry."',click = '".$click."',message = '".$message."',button = '".$button."',question1 = '".$question1."',question2 = '".$question2."',question3 = '".$question3."',question4 = '".$question4."',question5 = '".$question5."',question6 = '".$question6."',question7 = '".$question7."',question8 = '".$question8."',question9 = '".$question9."',question10 = '".$question10."',question11 = '".$question11."',date=CURDATE()";
					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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


	public function updatecontact_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$name = mysqli_real_escape_string( $this->_con, $trimmed_data['name'] );
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$enquiry = mysqli_real_escape_string( $this->_con, $trimmed_data['enquiry'] );
			$click = mysqli_real_escape_string( $this->_con, $trimmed_data['click'] );
			$message = mysqli_real_escape_string( $this->_con, $trimmed_data['message'] );
			$button = mysqli_real_escape_string( $this->_con, $trimmed_data['button'] );
			$question1 = mysqli_real_escape_string( $this->_con, $trimmed_data['question1'] );
			$question2 = mysqli_real_escape_string( $this->_con, $trimmed_data['question2'] );
			$question3 = mysqli_real_escape_string( $this->_con, $trimmed_data['question3'] );
			$question4 = mysqli_real_escape_string( $this->_con, $trimmed_data['question4'] );
			$question5 = mysqli_real_escape_string( $this->_con, $trimmed_data['question5'] );
			$question6 = mysqli_real_escape_string( $this->_con, $trimmed_data['question6'] );
			$question7 = mysqli_real_escape_string( $this->_con, $trimmed_data['question7'] );
			$question8 = mysqli_real_escape_string( $this->_con, $trimmed_data['question8'] );
			$question9 = mysqli_real_escape_string( $this->_con, $trimmed_data['question9'] );
			$question10 = mysqli_real_escape_string( $this->_con, $trimmed_data['question10'] );
			$question11 = mysqli_real_escape_string( $this->_con, $trimmed_data['question11'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_contact_us SET language = '".$language."',name ='".$name."',email = '".$email."',country = '".$country."',enquiry = '".$enquiry."',click = '".$click."',message = '".$message."',button = '".$button."',question1 = '".$question1."',question2 = '".$question2."',question3 = '".$question3."',question4 = '".$question4."',question5 = '".$question5."',question6 = '".$question6."',question7 = '".$question7."',question8 = '".$question8."',question9 = '".$question9."',question10 = '".$question10."',question11 = '".$question11."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatecontact_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_contact_us SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatecontact_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_contact_us SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function deactivateadministrator( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_add_admin SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Admin has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateadministrator( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_add_admin SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Admin has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updateadmin( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$logid = mysqli_real_escape_string( $this->_con, $trimmed_data['logid'] );
			$password = mysqli_real_escape_string( $this->_con, $trimmed_data['password'] );
			$fname = mysqli_real_escape_string( $this->_con, $trimmed_data['fname'] );
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$phone_no = mysqli_real_escape_string( $this->_con, $trimmed_data['phone_no'] );
			$manage_user = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_user'] );
			$manage_payment = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_payment'] );
			$manage_order = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_order'] );
			$manage_time = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_time'] );
			$manage_news = mysqli_real_escape_string( $this->_con, $trimmed_data['manage_news'] );

			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['editid'], ENT_QUOTES ));

			if((empty($logid)) || (empty($password)) || (empty($fname)) || (empty($email)) || (empty($phone_no)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			if((empty($manage_user)))
			{
				$manage_user1 = '0';
			}
			else
			{
				$manage_user1 = $manage_user;
			}

			if((empty($manage_payment)))
			{
				$manage_payment1 = '0';
			}
			else
			{
				$manage_payment1 = $manage_payment;
			}

			if((empty($manage_order)))
			{
				$manage_order1 = '0';
			}
			else
			{
				$manage_order1 = $manage_order;
			}

			if((empty($manage_time)))
			{
				$manage_time1 = '0';
			}
			else
			{
				$manage_time1 = $manage_time;
			}

			if((empty($manage_news)))
			{
				$manage_news1 = '0';
			}
			else
			{
				$manage_news1 = $manage_news;
			}

			$query = "UPDATE bz_add_admin SET admin_id = '".$logid."',password ='".$password."', name ='".$fname."',email = '".$email."',phone = '".$phone_no."',manage_user='".$manage_user1."',manage_payment='".$manage_payment1."',manage_order='".$manage_order1."',manage_timeslot='".$manage_time1."',manage_newsletter='".$manage_news1."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Admin has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function searchadmin($tables,$coloums,$conditions,$endsql) 
	{
		// Trim all the incoming data:
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				// escape variables for security
				
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
			foreach($tables as $tables)
			{
				// escape variables for security
				$tablesvalues[] = "SELECT $coloums FROM $tables $condition $endsql";
			}
			
			$tables = "";	
			$tables .= @implode(' UNION ', $tablesvalues);
		}
		else
		{
			$tables = "";
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
			parent::add('e', '(*)No Record Matches Your Search.');
			return false;
		}

	}

	public function searchuser_byadmin($tables,$coloums,$conditions,$endsql,$ref_sql) 
	{
		// Trim all the incoming data:
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				// escape variables for security
				
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
			foreach($tables as $tables)
			{
				// escape variables for security
				$tablesvalues[] = "SELECT $coloums FROM $tables $condition $endsql $ref_sql";
			}
			
			$tables = "";	
			$tables .= @implode(' UNION ', $tablesvalues);
		}
		else
		{
			$tables = "";
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
			parent::add('e', '(*)No Record Matches Your Search.');
			return false;
		}

	}

	public function searchuser_gallery($tables,$coloums,$conditions,$endsql) 
	{
		// Trim all the incoming data:
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				// escape variables for security
				
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
			foreach($tables as $tables)
			{
				// escape variables for security
				$tablesvalues[] = "SELECT $coloums FROM $tables $condition $endsql";
			}
			
			$tables = "";	
			$tables .= @implode(' UNION ', $tablesvalues);
		}
		else
		{
			$tables = "";
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
			parent::add('e', '(*)No Record Matches Your Search.');
			return false;
		}

	}

	public function deactivatefaq( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_faq SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Faq has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatefaq( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_faq SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Faq has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function faq( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['faq'] );
			$answer = mysqli_real_escape_string( $this->_con, $trimmed_data['answer'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			if((empty($country)) || (empty($lang_country)) || (empty($answer))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_faq SET language = '".$lang_country."',faq ='".$country."',answer = '".$answer."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Faq has been saved successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updatefaq( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['faq'] );
			$answer = mysqli_real_escape_string( $this->_con, $trimmed_data['answer'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($country)) || (empty($id)) || (empty($lang_country)) || (empty($answer))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_faq SET language = '".$lang_country."',faq ='".$country."',answer = '".$answer."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Faq has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatecurrency( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_currency SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Currency has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatecurrency( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_currency SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Currency has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function currency( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['currency_symbol'] );
			$answer = mysqli_real_escape_string( $this->_con, $trimmed_data['currency_name'] );
			$value = mysqli_real_escape_string( $this->_con, $trimmed_data['currency_value'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			if((empty($country)) || (empty($lang_country)) || (empty($answer))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_currency SET language = '".$lang_country."',currency ='".$answer."',symbol = '".$country."',value='".$value."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Currency has been saved successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updatecurrency( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['currency_symbol'] );
			$answer = mysqli_real_escape_string( $this->_con, $trimmed_data['currency_name'] );
			$value = mysqli_real_escape_string( $this->_con, $trimmed_data['currency_value'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($country)) || (empty($id)) || (empty($lang_country)) || (empty($answer))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_currency SET language = '".$lang_country."',currency ='".$answer."',symbol = '".$country."',value='".$value."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Currency has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function addphotograph( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$caption = mysqli_real_escape_string( $this->_con, $trimmed_data['caption'] );
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			if((empty($language))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						
						$query = "INSERT INTO bz_photograph SET language ='".$language."',title='".$title."',caption='".$caption."', image ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in database successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function home( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$heading = mysqli_real_escape_string( $this->_con, $trimmed_data['heading'] );
			$description = mysqli_real_escape_string( $this->_con, $trimmed_data['description'] );
			if((empty($language))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						
						$query = "INSERT INTO bz_home SET language ='".$language."',description='".$description."',heading='".$heading."', banner ='".$filename."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been save in database successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updatephotograph( $postdata, $filesdata )
	{
		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$caption = mysqli_real_escape_string( $this->_con, $trimmed_data['caption'] );
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($title)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bz_photograph SET language ='".$language."',title='".$title."',caption='".$caption."', image ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activatephotograph( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_photograph SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Photograph has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatephotograph( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_photograph SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Photograph has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function addforum( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{
			
			$trimmed_data = $postdata;
			$topic = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['topic'], ENT_QUOTES ));
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			if((empty($language)) || (empty($topic))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						
						$query = "INSERT INTO bz_admin_forum SET language ='".$language."',user_id = 'admin', image ='".$filename."',description='".$topic."',date=CURDATE()";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Forum has been saved in database successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function updateforum( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$topic = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['topic'], ENT_QUOTES ));
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))|| (empty($topic))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bz_admin_forum SET language ='".$language."',user_id = 'admin', image ='".$filename."',description='".$topic."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Forum has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activateforum( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_admin_forum SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Forum has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateforum( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_admin_forum SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Forum has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function composenewsletter( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$subject = mysqli_real_escape_string( $this->_con, $trimmed_data['subject'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$newsletter = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['newsletter'], ENT_QUOTES ) );
			if((empty($title)) || (empty($subject)) || (empty($lang_country)) || (empty($newsletter))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_newsletter SET language = '".$lang_country."',title ='".$title."',subject = '".$subject."',newsletter='".$newsletter."',date=CURDATE()";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Newsletter has been saved successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deletenewsletter($table,$conditions) 
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
			parent::add('s', 'Newsletter has been deleted successfully.');	
			return true;
		}
		else
		{
			parent::add('e', 'Something went wrong. Please try again.');	
			return false;
		}

	}

	public function sendnewsletter( $data )
	{
		if( !empty( $data ))
		{
			
			$trimmed_data = $data;
			$sender_news = mysqli_real_escape_string( $this->_con, htmlentities($data['sender_news'], ENT_QUOTES) );
			
			if((empty($sender_news))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_newsletter where id = '$sender_news'";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$fetch1 = mysqli_fetch_assoc($result1);
			
			$query2 = "SELECT * FROM bz_newsletter_subscription where status = '1'";
			$result2 = mysqli_query($this->_con, $query2) or die(mysqli_error());		
	
			while($fetch2 = mysqli_fetch_assoc($result2))
			{
				$chat_exp = $fetch2['email'];
				$eng_email = $chat_exp;
				$subject = $fetch1['subject'];
				$message1 = $fetch1['newsletter'];
			
					$subject = $subject;
					$message ="<html><body>
					<div style='width:100%; border:10px solid #00A2B5; font-family:arial; font-size:18px; border-radius:10px;'><div style='color:#6B555A;'>".$message1."</div><br/><br/><div style='font-size:14px; text-align:center;'>2016 Sell on HMbyme!. All Rights Reserved </div><br/>".
					"</div></body></html>";
					if($this->sendemail($eng_email,$subject,$message))
					{
						//parent::add('s', 'Your Message has been Sent successfully');
						//return true;
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						//return false;
					}
			}
			  $title = $fetch1['id'];
			  $query = "INSERT INTO bz_send_newsletter SET newsletter_id = '".$title."',admin_id ='".$_SESSION['admin']['id']."',date=CURDATE()";

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Newsletter has been sent successfully.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function getrecordorderby($table,$coloums,$conditions,$endsql) 
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
			
		$query = "SELECT $coloums FROM $table $condition $endsql";
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

	public function deactivateshop( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_shop SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Shop has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateshop( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_shop SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Shop has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	/*
	public function header( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;

			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$lang_category = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			if((empty($category)) || (empty($lang_category))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_header SET language = '".$lang_category."',category ='".$category."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Header has been saved in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	*/
	
	
	public function updateheader( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			$lang_category = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			if((empty($category)) || (empty($id)) || (empty($lang_category))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "UPDATE bz_header SET language = '".$lang_category."',category ='".$category."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Header has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateheader( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_header SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Header has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateheader( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_header SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Header has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateregistration_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_registration_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateregistration_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_registration_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function registration_form( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$fname = mysqli_real_escape_string( $this->_con, $trimmed_data['fname'] );
			$lname = mysqli_real_escape_string( $this->_con, $trimmed_data['lname'] );
			$uname = mysqli_real_escape_string( $this->_con, $trimmed_data['uname'] );
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$cemail = mysqli_real_escape_string( $this->_con, $trimmed_data['cemail'] );
			$password = mysqli_real_escape_string( $this->_con, $trimmed_data['password'] );
			$cpassword = mysqli_real_escape_string( $this->_con, $trimmed_data['cpassword'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$phone = mysqli_real_escape_string( $this->_con, $trimmed_data['phone'] );
			$address1 = mysqli_real_escape_string( $this->_con, $trimmed_data['address1'] );
			$address2 = mysqli_real_escape_string( $this->_con, $trimmed_data['address2'] );
			$postal_code = mysqli_real_escape_string( $this->_con, $trimmed_data['postal_code'] );
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_registration_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				$query = "INSERT INTO bz_registration_form SET language = '".$language."',fname ='".$fname."',lname = '".$lname."',uname = '".$uname."',email = '".$email."',cemail = '".$cemail."',password = '".$password."',cpassword = '".$cpassword."',country = '".$country."',state = '".$state."',city = '".$city."',phone = '".$phone."',address1 = '".$address1."',address2 = '".$address2."',postal_code = '".$postal_code."',date=CURDATE()";

					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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

	public function updateregistration_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$fname = mysqli_real_escape_string( $this->_con, $trimmed_data['fname'] );
			$lname = mysqli_real_escape_string( $this->_con, $trimmed_data['lname'] );
			$uname = mysqli_real_escape_string( $this->_con, $trimmed_data['uname'] );
			$email = mysqli_real_escape_string( $this->_con, $trimmed_data['email'] );
			$cemail = mysqli_real_escape_string( $this->_con, $trimmed_data['cemail'] );
			$password = mysqli_real_escape_string( $this->_con, $trimmed_data['password'] );
			$cpassword = mysqli_real_escape_string( $this->_con, $trimmed_data['cpassword'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$phone = mysqli_real_escape_string( $this->_con, $trimmed_data['phone'] );
			$address1 = mysqli_real_escape_string( $this->_con, $trimmed_data['address1'] );
			$address2 = mysqli_real_escape_string( $this->_con, $trimmed_data['address2'] );
			$postal_code = mysqli_real_escape_string( $this->_con, $trimmed_data['postal_code'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_registration_form SET language = '".$language."',fname ='".$fname."',lname = '".$lname."',uname = '".$uname."',email = '".$email."',cemail = '".$cemail."',password = '".$password."',cpassword = '".$cpassword."',country = '".$country."',state = '".$state."',city = '".$city."',phone = '".$phone."',address1 = '".$address1."',address2 = '".$address2."',postal_code = '".$postal_code."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}


	public function deactivateproduct_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_product_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateproduct_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_product_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function product_form( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$sub_category = mysqli_real_escape_string( $this->_con, $trimmed_data['sub_category'] );
			$image = mysqli_real_escape_string( $this->_con, $trimmed_data['image'] );
			$price = mysqli_real_escape_string( $this->_con, $trimmed_data['price'] );
			$pro_price = mysqli_real_escape_string( $this->_con, $trimmed_data['pro_price'] );
			$weight = mysqli_real_escape_string( $this->_con, $trimmed_data['weight'] );
			$quantity = mysqli_real_escape_string( $this->_con, $trimmed_data['quantity'] );
			$size = mysqli_real_escape_string( $this->_con, $trimmed_data['size'] );
			$material = mysqli_real_escape_string( $this->_con, $trimmed_data['material'] );
			$product_desc = mysqli_real_escape_string( $this->_con, $trimmed_data['product_desc'] );
			$packing_det = mysqli_real_escape_string( $this->_con, $trimmed_data['packing_det'] );
			$add_variation = mysqli_real_escape_string( $this->_con, $trimmed_data['add_variation'] );
			$process_time = mysqli_real_escape_string( $this->_con, $trimmed_data['process_time'] );
			$ship_cost = mysqli_real_escape_string( $this->_con, $trimmed_data['ship_cost'] );
			$tag = mysqli_real_escape_string( $this->_con, $trimmed_data['tag'] );
			$coupon_type = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_type'] );
			$coupon_code = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_code'] );
			$coupon_amnt = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_amnt'] );
			$expires_on = mysqli_real_escape_string( $this->_con, $trimmed_data['expires_on'] );
			$coupon_desc = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_desc'] );
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_product_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				$query = "INSERT INTO bz_product_form SET language = '".$language."',title ='".$title."',category = '".$category."',sub_category = '".$sub_category."',image = '".$image."',price = '".$price."',pro_price = '".$pro_price."',weight = '".$weight."',quantity = '".$quantity."',size = '".$size."',material = '".$material."',product_desc = '".$product_desc."',packing_det = '".$packing_det."',add_variation = '".$add_variation."',process_time = '".$process_time."',ship_cost = '".$ship_cost."',tag = '".$tag."',coupon_type = '".$coupon_type."',coupon_code = '".$coupon_code."',coupon_amnt = '".$coupon_amnt."',expires_on = '".$expires_on."',coupon_desc = '".$coupon_desc."',date=CURDATE()";

					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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

	public function updateproduct_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$sub_category = mysqli_real_escape_string( $this->_con, $trimmed_data['sub_category'] );
			$image = mysqli_real_escape_string( $this->_con, $trimmed_data['image'] );
			$price = mysqli_real_escape_string( $this->_con, $trimmed_data['price'] );
			$pro_price = mysqli_real_escape_string( $this->_con, $trimmed_data['pro_price'] );
			$weight = mysqli_real_escape_string( $this->_con, $trimmed_data['weight'] );
			$quantity = mysqli_real_escape_string( $this->_con, $trimmed_data['quantity'] );
			$size = mysqli_real_escape_string( $this->_con, $trimmed_data['size'] );
			$material = mysqli_real_escape_string( $this->_con, $trimmed_data['material'] );
			$product_desc = mysqli_real_escape_string( $this->_con, $trimmed_data['product_desc'] );
			$packing_det = mysqli_real_escape_string( $this->_con, $trimmed_data['packing_det'] );
			$add_variation = mysqli_real_escape_string( $this->_con, $trimmed_data['add_variation'] );
			$process_time = mysqli_real_escape_string( $this->_con, $trimmed_data['process_time'] );
			$ship_cost = mysqli_real_escape_string( $this->_con, $trimmed_data['ship_cost'] );
			$tag = mysqli_real_escape_string( $this->_con, $trimmed_data['tag'] );
			$coupon_type = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_type'] );
			$coupon_code = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_code'] );
			$coupon_amnt = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_amnt'] );
			$expires_on = mysqli_real_escape_string( $this->_con, $trimmed_data['expires_on'] );
			$coupon_desc = mysqli_real_escape_string( $this->_con, $trimmed_data['coupon_desc'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_product_form SET language = '".$language."',title ='".$title."',category = '".$category."',sub_category = '".$sub_category."',image = '".$image."',price = '".$price."',pro_price = '".$pro_price."',weight = '".$weight."',quantity = '".$quantity."',size = '".$size."',material = '".$material."',product_desc = '".$product_desc."',packing_det = '".$packing_det."',add_variation = '".$add_variation."',process_time = '".$process_time."',ship_cost = '".$ship_cost."',tag = '".$tag."',coupon_type = '".$coupon_type."',coupon_code = '".$coupon_code."',coupon_amnt = '".$coupon_amnt."',expires_on = '".$expires_on."',coupon_desc = '".$coupon_desc."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	

	public function deactivatediscussion_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_discussion_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatediscussion_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_discussion_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function discussion_form( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$post = mysqli_real_escape_string( $this->_con, $trimmed_data['post'] );
			$image = mysqli_real_escape_string( $this->_con, $trimmed_data['image'] );
			$comment = mysqli_real_escape_string( $this->_con, $trimmed_data['comment'] );
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_discussion_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				
				$query = "INSERT INTO bz_discussion_form SET language = '".$language."',title ='".$title."',post = '".$post."',image = '".$image."',comment = '".$comment."',date=CURDATE()";

					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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

	public function updatediscussion_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$post = mysqli_real_escape_string( $this->_con, $trimmed_data['post'] );
			$image = mysqli_real_escape_string( $this->_con, $trimmed_data['image'] );
			$comment = mysqli_real_escape_string( $this->_con, $trimmed_data['comment'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_discussion_form SET language = '".$language."',title ='".$title."',post = '".$post."',image = '".$image."',comment = '".$comment."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateshop_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_shop_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateshop_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_shop_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function shop_form( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$shop_title = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_title'] );
			$shop_banner = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_banner'] );
			$shop_description = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_description'] );
			$shop_address1 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address1'] );
			$shop_address2 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address2'] );
			$postal_code = mysqli_real_escape_string( $this->_con, $trimmed_data['postal_code'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$language_id = mysqli_real_escape_string( $this->_con, $trimmed_data['language_id'] );
			$currency = mysqli_real_escape_string( $this->_con, $trimmed_data['currency'] );
			$bank_name = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_name'] );
			$bank_account = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_account'] );
			$bank_iban = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_iban'] );
			$bank_swift = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_swift'] );
			$payment_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['payment_policy'] );
			$refund_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['refund_policy'] );
			$shipping = mysqli_real_escape_string( $this->_con, $trimmed_data['shipping'] );
			$terms_use = mysqli_real_escape_string( $this->_con, $trimmed_data['terms_use'] );
			$return_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['return_policy'] );
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_shop_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
			
				$query = "INSERT INTO bz_shop_form SET language = '".$language."',shop_title ='".$shop_title."',shop_banner = '".$shop_banner."',shop_description = '".$shop_description."',shop_address1 = '".$shop_address1."',shop_address2 = '".$shop_address2."',postal_code = '".$postal_code."',country = '".$country."',state = '".$state."',city = '".$city."',language_id='".$language_id."',currency = '".$currency."',bank_name = '".$bank_name."',bank_account = '".$bank_account."',bank_iban = '".$bank_iban."',bank_swift = '".$bank_swift."',payment_policy = '".$payment_policy."',refund_policy = '".$refund_policy."',shipping = '".$shipping."',terms_use = '".$terms_use."',return_policy = '".$return_policy."',date=CURDATE()";

					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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

	public function updateshop_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$shop_title = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_title'] );
			$shop_banner = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_banner'] );
			$shop_description = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_description'] );
			$shop_address1 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address1'] );
			$shop_address2 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address2'] );
			$postal_code = mysqli_real_escape_string( $this->_con, $trimmed_data['postal_code'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$language_id = mysqli_real_escape_string( $this->_con, $trimmed_data['language_id'] );
			$currency = mysqli_real_escape_string( $this->_con, $trimmed_data['currency'] );
			$bank_name = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_name'] );
			$bank_account = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_account'] );
			$bank_iban = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_iban'] );
			$bank_swift = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_swift'] );
			$payment_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['payment_policy'] );
			$refund_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['refund_policy'] );
			$shipping = mysqli_real_escape_string( $this->_con, $trimmed_data['shipping'] );
			$terms_use = mysqli_real_escape_string( $this->_con, $trimmed_data['terms_use'] );
			$return_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['return_policy'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_shop_form SET language = '".$language."',shop_title ='".$shop_title."',shop_banner = '".$shop_banner."',shop_description = '".$shop_description."',shop_address1 = '".$shop_address1."',shop_address2 = '".$shop_address2."',postal_code = '".$postal_code."',country = '".$country."',state = '".$state."',city = '".$city."',language_id = '".$language_id."',currency = '".$currency."',bank_name = '".$bank_name."',bank_account = '".$bank_account."',bank_iban = '".$bank_iban."',bank_swift = '".$bank_swift."',payment_policy = '".$payment_policy."',refund_policy = '".$refund_policy."',shipping = '".$shipping."',terms_use = '".$terms_use."',return_policy = '".$return_policy."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatesearch_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_search_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatesearch_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_search_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function search_form( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$shop_title = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_title'] );
			$shop_banner = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_banner'] );
			$shop_description = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_description'] );
			$shop_address1 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address1'] );
			$shop_address2 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address2'] );
			$postal_code = mysqli_real_escape_string( $this->_con, $trimmed_data['postal_code'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$language_id = mysqli_real_escape_string( $this->_con, $trimmed_data['language_id'] );
			$currency = mysqli_real_escape_string( $this->_con, $trimmed_data['currency'] );
			$bank_name = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_name'] );
			$bank_account = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_account'] );
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_search_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
			
				$query = "INSERT INTO bz_search_form SET language = '".$language."',shop_title ='".$shop_title."',shop_banner = '".$shop_banner."',shop_description = '".$shop_description."',shop_address1 = '".$shop_address1."',shop_address2 = '".$shop_address2."',postal_code = '".$postal_code."',country = '".$country."',state = '".$state."',city = '".$city."',language_id='".$language_id."',currency = '".$currency."',bank_name = '".$bank_name."',bank_account = '".$bank_account."',date=CURDATE()";

					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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

	public function updatesearch_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$shop_title = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_title'] );
			$shop_banner = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_banner'] );
			$shop_description = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_description'] );
			$shop_address1 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address1'] );
			$shop_address2 = mysqli_real_escape_string( $this->_con, $trimmed_data['shop_address2'] );
			$postal_code = mysqli_real_escape_string( $this->_con, $trimmed_data['postal_code'] );
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$state = mysqli_real_escape_string( $this->_con, $trimmed_data['state'] );
			$city = mysqli_real_escape_string( $this->_con, $trimmed_data['city'] );
			$language_id = mysqli_real_escape_string( $this->_con, $trimmed_data['language_id'] );
			$currency = mysqli_real_escape_string( $this->_con, $trimmed_data['currency'] );
			$bank_name = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_name'] );
			$bank_account = mysqli_real_escape_string( $this->_con, $trimmed_data['bank_account'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_search_form SET language = '".$language."',shop_title ='".$shop_title."',shop_banner = '".$shop_banner."',shop_description = '".$shop_description."',shop_address1 = '".$shop_address1."',shop_address2 = '".$shop_address2."',postal_code = '".$postal_code."',country = '".$country."',state = '".$state."',city = '".$city."',language_id = '".$language_id."',currency = '".$currency."',bank_name = '".$bank_name."',bank_account = '".$bank_account."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatebutton_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_button_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatebutton_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_button_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function button_form( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$sub_category = mysqli_real_escape_string( $this->_con, $trimmed_data['sub_category'] );
			$image = mysqli_real_escape_string( $this->_con, $trimmed_data['image'] );
			$price = mysqli_real_escape_string( $this->_con, $trimmed_data['price'] );
			$pro_price = mysqli_real_escape_string( $this->_con, $trimmed_data['pro_price'] );
			$weight = mysqli_real_escape_string( $this->_con, $trimmed_data['weight'] );
			$quantity = mysqli_real_escape_string( $this->_con, $trimmed_data['quantity'] );
			$size = mysqli_real_escape_string( $this->_con, $trimmed_data['size'] );
			$material = mysqli_real_escape_string( $this->_con, $trimmed_data['material'] );
			$product_desc = mysqli_real_escape_string( $this->_con, $trimmed_data['product_desc'] );
			$packing_det = mysqli_real_escape_string( $this->_con, $trimmed_data['packing_det'] );
			$add_variation = mysqli_real_escape_string( $this->_con, $trimmed_data['add_variation'] );
			$process_time = mysqli_real_escape_string( $this->_con, $trimmed_data['process_time'] );
			$ship_cost = mysqli_real_escape_string( $this->_con, $trimmed_data['ship_cost'] );
			$tag = mysqli_real_escape_string( $this->_con, $trimmed_data['tag'] );
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_button_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				$query = "INSERT INTO bz_button_form SET language = '".$language."',title ='".$title."',category = '".$category."',sub_category = '".$sub_category."',image = '".$image."',price = '".$price."',pro_price = '".$pro_price."',weight = '".$weight."',quantity = '".$quantity."',size = '".$size."',material = '".$material."',product_desc = '".$product_desc."',packing_det = '".$packing_det."',add_variation = '".$add_variation."',process_time = '".$process_time."',ship_cost = '".$ship_cost."',tag = '".$tag."',date=CURDATE()";

					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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

	public function updatebutton_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$title = mysqli_real_escape_string( $this->_con, $trimmed_data['title'] );
			$category = mysqli_real_escape_string( $this->_con, $trimmed_data['category'] );
			$sub_category = mysqli_real_escape_string( $this->_con, $trimmed_data['sub_category'] );
			$image = mysqli_real_escape_string( $this->_con, $trimmed_data['image'] );
			$price = mysqli_real_escape_string( $this->_con, $trimmed_data['price'] );
			$pro_price = mysqli_real_escape_string( $this->_con, $trimmed_data['pro_price'] );
			$weight = mysqli_real_escape_string( $this->_con, $trimmed_data['weight'] );
			$quantity = mysqli_real_escape_string( $this->_con, $trimmed_data['quantity'] );
			$size = mysqli_real_escape_string( $this->_con, $trimmed_data['size'] );
			$material = mysqli_real_escape_string( $this->_con, $trimmed_data['material'] );
			$product_desc = mysqli_real_escape_string( $this->_con, $trimmed_data['product_desc'] );
			$packing_det = mysqli_real_escape_string( $this->_con, $trimmed_data['packing_det'] );
			$add_variation = mysqli_real_escape_string( $this->_con, $trimmed_data['add_variation'] );
			$process_time = mysqli_real_escape_string( $this->_con, $trimmed_data['process_time'] );
			$ship_cost = mysqli_real_escape_string( $this->_con, $trimmed_data['ship_cost'] );
			$tag = mysqli_real_escape_string( $this->_con, $trimmed_data['tag'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_button_form SET language = '".$language."',title ='".$title."',category = '".$category."',sub_category = '".$sub_category."',image = '".$image."',price = '".$price."',pro_price = '".$pro_price."',weight = '".$weight."',quantity = '".$quantity."',size = '".$size."',material = '".$material."',product_desc = '".$product_desc."',packing_det = '".$packing_det."',add_variation = '".$add_variation."',process_time = '".$process_time."',ship_cost = '".$ship_cost."',tag = '".$tag."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function sendemail( $eng_email, $subject, $message )
	{
		if( !empty( $eng_email ) && !empty( $subject ) && !empty( $message ) )
		{

			if ($this->is_email( $eng_email)) 
			{
				$eng_email = mysqli_real_escape_string( $this->_con, $eng_email);
			} 
			else 
			{				
				parent::add('e', 'Please enter a valid email address!');
				return false;
			}
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$headers .= 'From: '.APP_NAME.' <'.ADMIN_EMAIL.'>' . "\r\n";
			mail($eng_email, $subject, $message, $headers);
			return true;
			
		} 
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	} 


	public function adminchatform( $data )
	{
		if( !empty( $data ))
		{
			
			$search = mysqli_real_escape_string( $this->_con, htmlentities($data['search'], ENT_QUOTES) );
			//$subject = mysqli_real_escape_string( $this->_con, htmlentities($data['subject'], ENT_QUOTES) );
			$message1 = mysqli_real_escape_string( $this->_con, htmlentities($data['message'], ENT_QUOTES) );

			$chat_exp = explode(',',$search);

			if((empty($search)) || (empty($message1))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$admin = $_SESSION['admin']['id'];
			$admin_email = $_SESSION['admin']['email'];

			foreach($chat_exp as $chat_exp)
			{		
				

				$query1 = "SELECT * FROM bz_users where username = '$chat_exp'";
				$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
				$fetch1 = mysqli_fetch_assoc($result1);

				 $user_ids = $fetch1['id'];

				 $imp_user .= $user_ids.",";
				//$rint = $imp_user;
				
				$entered = date('Y-m-d H:i:s');
			/*
				$eng_email = $chat_exp;
				
			
					$subject = $subject;
					$message ="<html><body>
					<div style='100%; border:10px solid #00A2B5; font-family:arial; font-size:18px; border-radius:10px;'><div style='color:#6B555A;>".$message1."</div><br/><br/><div style='font-size:14px; text-align:center;'>2016 Sell on HMbyme!. All Rights Reserved </div><br/>".
					"</div></body></html>";
					if($this->sendemail($eng_email,$subject,$message))
					{
						//parent::add('s', 'Your Message has been Sent successfully');
						//return true;
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						//return false;
					}
				*/
			
		} 

			  $query = "INSERT INTO bz_admin_chat SET send_id = '".$admin."', rec_id = '".$imp_user."', rec_email = '".$search."',send_email='".$admin_email."', message = '".$message1."', send_status = '1', rec_status = '1', date_time = '".$entered."'";

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been sent successfully.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}


	public function deleteadmin_msg( $data )
	{
		if( !empty( $data ))
		{
	
		 $cnt=array();
		 $cnt=count($_POST['chk_del']);
		 for($i=0;$i<$cnt;$i++)
		  {
		    $del_id=$_POST['chk_del'][$i];

			$query = "UPDATE bz_admin_chat SET send_status = '3' where id = '".$del_id."'";
			mysqli_query($this->_con, $query);
		  }

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been sent to your trash box.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function getsentmessages($table,$coloums,$conditions,$endsql) 
	{	
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

	public function getsentmessages34($table,$coloums,$conditions) 
	{	
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

	public function getsentmessages1($table,$coloums,$conditions,$end_sql) 
	{	
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

		$query = "SELECT $coloums FROM $table $condition $end_sql";
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

	public function permdeleteadmin_msg( $data )
	{
		if( !empty( $data ))
		{
	
		 $cnt=array();
		 $cnt=count($_POST['chk_del']);
		 for($i=0;$i<$cnt;$i++)
		  {
		    $del_id=$_POST['chk_del'][$i];

		    $query1 = "SELECT * FROM bz_admin_chat where id = '$del_id'";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());
			$row1 = mysqli_fetch_object($result1);
			$ri = $row1->send_status;
			$vi = $row1->rec_status;
		    if($ri == '3')
		    {
			$query = "UPDATE bz_admin_chat SET send_status = '4' where id = '".$del_id."'";
			}
			if($vi == '3')
			{
			$query = "UPDATE bz_admin_chat SET rec_status = '4' where id = '".$del_id."'";	
			}
			mysqli_query($this->_con, $query);
		  }

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been Deleted Permanently.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function staradmin_msg( $data )
	{
		if( !empty( $data ))
		{
	
		 $cnt=array();
		 $cnt=count($_POST['chk_del']);
		 for($i=0;$i<$cnt;$i++)
		  {
		    $del_id=$_POST['chk_del'][$i];

			$query = "UPDATE bz_admin_chat SET favourite = '0' where id = '".$del_id."'";
			mysqli_query($this->_con, $query);
		  }

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been sent to your trash box.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function recoveradmin_msg( $data )
	{
		if( !empty( $data ))
		{
	
		 $cnt=array();
		 $cnt=count($_POST['chk_del']);
		 for($i=0;$i<$cnt;$i++)
		  {
		  	$del_id=$_POST['chk_del'][$i];

		  	$query1 = "SELECT * FROM bz_admin_chat where id = '$del_id'";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());
			$row1 = mysqli_fetch_object($result1);
			$ri = $row1->send_status;
			$vi = $row1->rec_status;
		    if($ri == '3')
		    {
			$query = "UPDATE bz_admin_chat SET send_status = '1' where id = '".$del_id."'";
			}
			if($vi == '3')
			{
			$query = "UPDATE bz_admin_chat SET rec_status = '1' where id = '".$del_id."'";	
			}
			mysqli_query($this->_con, $query);
		  }

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been Recovered Successfully.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}


	public function deleteinbox_msg( $data )
	{
		if( !empty( $data ))
		{
	
		 $cnt=array();
		 $cnt=count($_POST['chk_del']);
		 for($i=0;$i<$cnt;$i++)
		  {
		    $del_id=$_POST['chk_del'][$i];

			$query = "UPDATE bz_admin_chat SET rec_status = '3' where id = '".$del_id."'";
			mysqli_query($this->_con, $query);
		  }

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been sent to your trash box.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}
	}

	public function gettrash_number($table,$coloums,$conditions,$end_sql) 
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
			
		$query = "SELECT $coloums FROM $table $condition $end_sql";
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

	public function deactivate_owner( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_owner SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Seller has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activate_owner( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_owner SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Seller has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function adminreplymsg( $data )
	{
		if( !empty( $data ))
		{
			$rec_id = mysqli_real_escape_string( $this->_con, $data['rec_id'] );
			$rec_email = mysqli_real_escape_string( $this->_con, $data['rec_email'] );
			$message1 = mysqli_real_escape_string( $this->_con, htmlentities($data['message'], ENT_QUOTES) );


			if((empty($message1))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			$admin = $_SESSION['admin']['id'];
			$admin_email = $_SESSION['admin']['email'];

					
				

		
			/*
					$subject = $subject;
					$message ="<html><body>
					<div style='100%; border:10px solid #00A2B5; font-family:arial; font-size:18px; border-radius:10px;'><div style='color:#6B555A;>".$message1."</div><br/><br/><div style='font-size:14px; text-align:center;'>2016 Sell on HMbyme!. All Rights Reserved </div><br/>".
					"</div></body></html>";
					if($this->sendemail($eng_email,$subject,$message))
					{
						//parent::add('s', 'Your Message has been Sent successfully');
						//return true;
					}
					else
					{
						parent::add('e', 'Somthing went wrong. Please try again.');	
						//return false;
					}
				*/
			
			  $entered = @date('Y-m-d H:i:s');

			  $query = "INSERT INTO bz_admin_chat SET send_id = '".$admin."', rec_id = '".$rec_id."', rec_email = '".$rec_email."',send_email='".$admin_email."', message = '".$message1."', send_status = '1', rec_status = '1', date_time = '".$entered."'";

			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Message has been sent successfully.');	
				return true;
			}	
		 
		}
		else
		{
			parent::add('e', '(*) Fields are required.');	
			return false;
		}

	}

	public function header_form( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$logo = mysqli_real_escape_string( $this->_con, $trimmed_data['logo'] );
			$register = mysqli_real_escape_string( $this->_con, $trimmed_data['register'] );
			$signin = mysqli_real_escape_string( $this->_con, $trimmed_data['signin'] );
			$checkout = mysqli_real_escape_string( $this->_con, $trimmed_data['checkout'] );
			$mycart = mysqli_real_escape_string( $this->_con, $trimmed_data['mycart'] );
			$sellon = mysqli_real_escape_string( $this->_con, $trimmed_data['sellon'] );
			$search = mysqli_real_escape_string( $this->_con, $trimmed_data['search'] );
			$lang_sel = mysqli_real_escape_string( $this->_con, $trimmed_data['lang_sel'] );
			$myaccount = mysqli_real_escape_string( $this->_con, $trimmed_data['myaccount'] );
			$signout = mysqli_real_escape_string( $this->_con, $trimmed_data['signout'] );
			$myfavourite = mysqli_real_escape_string( $this->_con, $trimmed_data['myfavourite'] );
			
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_header_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				
				$query = "INSERT INTO bz_header_form SET language = '".$language."',logo ='".$logo."',register = '".$register."',signin = '".$signin."',checkout = '".$checkout."',mycart = '".$mycart."',sellon = '".$sellon."',search = '".$search."',lang_sel='".$lang_sel."',myaccount = '".$myaccount."',signout = '".$signout."',myfavourite = '".$myfavourite."',date=CURDATE()";
					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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


	public function updateheader_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$logo = mysqli_real_escape_string( $this->_con, $trimmed_data['logo'] );
			$register = mysqli_real_escape_string( $this->_con, $trimmed_data['register'] );
			$signin = mysqli_real_escape_string( $this->_con, $trimmed_data['signin'] );
			$checkout = mysqli_real_escape_string( $this->_con, $trimmed_data['checkout'] );
			$mycart = mysqli_real_escape_string( $this->_con, $trimmed_data['mycart'] );
			$sellon = mysqli_real_escape_string( $this->_con, $trimmed_data['sellon'] );
			$search = mysqli_real_escape_string( $this->_con, $trimmed_data['search'] );
			$lang_sel = mysqli_real_escape_string( $this->_con, $trimmed_data['lang_sel'] );
			$myaccount = mysqli_real_escape_string( $this->_con, $trimmed_data['myaccount'] );
			$signout = mysqli_real_escape_string( $this->_con, $trimmed_data['signout'] );
			$myfavourite = mysqli_real_escape_string( $this->_con, $trimmed_data['myfavourite'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_header_form SET language = '".$language."',logo ='".$logo."',register = '".$register."',signin = '".$signin."',checkout = '".$checkout."',mycart = '".$mycart."',sellon = '".$sellon."',search = '".$search."',lang_sel='".$lang_sel."',myaccount = '".$myaccount."',signout = '".$signout."',myfavourite = '".$myfavourite."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateheader_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_header_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateheader_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_header_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function footer_form( array $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$logo = mysqli_real_escape_string( $this->_con, $trimmed_data['logo'] );
			$register = mysqli_real_escape_string( $this->_con, $trimmed_data['register'] );
			$signin = mysqli_real_escape_string( $this->_con, $trimmed_data['signin'] );
			$checkout = mysqli_real_escape_string( $this->_con, $trimmed_data['checkout'] );
			$mycart = mysqli_real_escape_string( $this->_con, $trimmed_data['mycart'] );
			$sellon = mysqli_real_escape_string( $this->_con, $trimmed_data['sellon'] );
			$search = mysqli_real_escape_string( $this->_con, $trimmed_data['search'] );
			$lang_sel = mysqli_real_escape_string( $this->_con, $trimmed_data['lang_sel'] );
			$myaccount = mysqli_real_escape_string( $this->_con, $trimmed_data['myaccount'] );
			$signout = mysqli_real_escape_string( $this->_con, $trimmed_data['signout'] );
			$myfavourite = mysqli_real_escape_string( $this->_con, $trimmed_data['myfavourite'] );
			$copyright = mysqli_real_escape_string( $this->_con, $trimmed_data['copyright'] );
			$privacy_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['privacy_policy'] );
			
			if(empty($language)) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_footer_form where language = '$language' ";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'Data For This Language Already Saved');	
				return true;
			}
			else
			{
				
				$query = "INSERT INTO bz_footer_form SET language = '".$language."',logo ='".$logo."',register = '".$register."',signin = '".$signin."',checkout = '".$checkout."',mycart = '".$mycart."',sellon = '".$sellon."',search = '".$search."',lang_sel='".$lang_sel."',myaccount = '".$myaccount."',signout = '".$signout."',myfavourite = '".$myfavourite."',copyright='".$copyright."',privacy_policy='".$privacy_policy."',date=CURDATE()";
					if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Data has been saved successfully.');	
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


	public function updatefooter_form( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$logo = mysqli_real_escape_string( $this->_con, $trimmed_data['logo'] );
			$register = mysqli_real_escape_string( $this->_con, $trimmed_data['register'] );
			$signin = mysqli_real_escape_string( $this->_con, $trimmed_data['signin'] );
			$checkout = mysqli_real_escape_string( $this->_con, $trimmed_data['checkout'] );
			$mycart = mysqli_real_escape_string( $this->_con, $trimmed_data['mycart'] );
			$sellon = mysqli_real_escape_string( $this->_con, $trimmed_data['sellon'] );
			$search = mysqli_real_escape_string( $this->_con, $trimmed_data['search'] );
			$lang_sel = mysqli_real_escape_string( $this->_con, $trimmed_data['lang_sel'] );
			$myaccount = mysqli_real_escape_string( $this->_con, $trimmed_data['myaccount'] );
			$signout = mysqli_real_escape_string( $this->_con, $trimmed_data['signout'] );
			$myfavourite = mysqli_real_escape_string( $this->_con, $trimmed_data['myfavourite'] );
			$copyright = mysqli_real_escape_string( $this->_con, $trimmed_data['copyright'] );
			$privacy_policy = mysqli_real_escape_string( $this->_con, $trimmed_data['privacy_policy'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_footer_form SET language = '".$language."',logo ='".$logo."',register = '".$register."',signin = '".$signin."',checkout = '".$checkout."',mycart = '".$mycart."',sellon = '".$sellon."',search = '".$search."',lang_sel='".$lang_sel."',myaccount = '".$myaccount."',signout = '".$signout."',myfavourite = '".$myfavourite."',copyright='".$copyright."',privacy_policy='".$privacy_policy."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Data has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatefooter_form( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_footer_form SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatefooter_form( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_footer_form SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Record has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivateproduct( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_product SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Product has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activateproduct( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_product SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Product has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatenewssubs( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_newsletter_subscription SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'User Subscription has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatenewssubs( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_newsletter_subscription SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'User Subscription has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function search_newssubs($tables,$coloums,$conditions,$endsql) 
	{
		// Trim all the incoming data:
		$conditions = @array_map('trim', $conditions);
		
		if(!empty($conditions))
		{			
			foreach($conditions as $key=>$value)
			{
				// escape variables for security
				
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
			foreach($tables as $tables)
			{
				// escape variables for security
				$tablesvalues[] = "SELECT $coloums FROM $tables $condition $endsql";
			}
			
			$tables = "";	
			$tables .= @implode(' UNION ', $tablesvalues);
		}
		else
		{
			$tables = "";
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
			parent::add('e', '(*)No Record Matches Your Search.');
			return false;
		}

	}

	public function addcoupon( $postdata )
	{
		if(!empty( $postdata ))
		{	
			$trimmed_data = $postdata;
			$owner = $_SESSION['admin']['id'];
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			$type = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['type'], ENT_QUOTES ));
			$code = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['code'], ENT_QUOTES ));
			$amount = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['amount'], ENT_QUOTES ));
			$expire = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['expire'], ENT_QUOTES ));
			$descriptions = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['descriptions'], ENT_QUOTES ));
			if((empty($owner)) || (empty($type)) || (empty($code)) || (empty($amount)) || (empty($expire)) || (empty($descriptions)))
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_coupons where code = '$code'";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'This Coupon Code Already Exists');	
				return true;
			}


			else
			{
				$entered = @date('Y-m-d');
				$query = "INSERT INTO bz_coupons SET language='".$language."', owner = '".$owner."', type = '".$type."', code = '".$code."', amount = '".$amount."', expire = '".$expire."', date = '".$entered."', descriptions = '".$descriptions."'";
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Your Coupon has been saved successfully.');	
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
			parent::add('e', 'Somthing went wrong. Please try again.');	
			return false;
		}
	}

	public function deactivatecoupon( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_coupons SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Coupon has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatecoupon( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_coupons SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Coupon has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updatecoupon( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;
			$owner = $_SESSION['admin']['id'];
			$language = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['language'], ENT_QUOTES ));
			$type = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['type'], ENT_QUOTES ));
			$code = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['code'], ENT_QUOTES ));
			$amount = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['amount'], ENT_QUOTES ));
			$expire = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['expire'], ENT_QUOTES ));
			$descriptions = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['descriptions'], ENT_QUOTES ));
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($owner)) || (empty($type)) || (empty($code)) || (empty($amount)) || (empty($expire)) || (empty($descriptions)))
			{
				parent::add('e', '(*) Fields are required.');	
				return false;
			}

			$query1 = "SELECT * FROM bz_coupons where code = '$code'";
			$result1 = mysqli_query($this->_con, $query1) or die(mysqli_error());		
			$count1 = mysqli_num_rows($result1);
			if($count1 >= 1)
			{
				parent::add('e', 'This Coupon Code Already Exists');	
				return true;
			}

			else
			{
			 $query = "UPDATE bz_coupons SET language='".$language."', owner = '".$owner."', type = '".$type."', code = '".$code."', amount = '".$amount."', expire = '".$expire."', descriptions = '".$descriptions."' WHERE id = '".$id."'";
			
				if(mysqli_query($this->_con, $query))
				{
					parent::add('s', 'Coupon has been updated successfully.');	
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

	public function query( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			if((empty($country)) || (empty($lang_country))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_query SET language = '".$lang_country."',country ='".$country."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Query has been save in database successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	public function updatequery( $data )
	{
		if(!empty( $data ) )
		{
			$trimmed_data = $data;

			$country = mysqli_real_escape_string( $this->_con, $trimmed_data['country'] );
			$lang_country = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($country)) || (empty($id)) || (empty($lang_country))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			 $query = "UPDATE bz_query SET language = '".$lang_country."',country ='".$country."' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Query has been updated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function deactivatequery( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_query SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Query has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}
	
	public function activatequery( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_query SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Query has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function updatehome( $postdata, $filesdata )
	{

		if(!empty( $postdata ) &&  !empty( $filesdata ))
		{	
			$trimmed_data = $postdata;
			$language = mysqli_real_escape_string( $this->_con, $trimmed_data['language'] );
			$heading = mysqli_real_escape_string( $this->_con, $trimmed_data['heading'] );
			$description = mysqli_real_escape_string( $this->_con, $trimmed_data['description'] );
			$id = mysqli_real_escape_string( $this->_con, htmlentities($trimmed_data['id'], ENT_QUOTES ));
			if((empty($language)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			
			if(!empty($filesdata['image']['name']))
			{
				$validextensions = array("jpeg", "jpg", "png");
				$ext = explode('.', basename($filesdata['image']['name']));
				$file_extension = end($ext);
				$filename = md5(uniqid()) . "." . $ext[count($ext) - 1];
				$file_target_path = "../uploads/" . $filename;  
				
				if(($_FILES["image"]["size"] < 1000000) && in_array($file_extension, $validextensions)) 
				{
					if (move_uploaded_file($_FILES['image']['tmp_name'], $file_target_path)) 
					{
						$query = "UPDATE bz_home SET language ='".$language."', description ='".$description."',heading='".$heading."', banner ='".$filename."' WHERE id = '".$id."'";
						if(mysqli_query($this->_con, $query))
						{
							parent::add('s', 'Record has been updated successfully.');	
							return true;
						}	
					} 
					else 
					{
						$common->add('e', 'Somthing went wrong. Please try again.');	
						$common->redirect(APP_FULL_URL);
					}
				}
				else
				{
					$common->add('e', 'Somthing went wrong. Please try again.');	
					$common->redirect(APP_FULL_URL);
				}
			}
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function activatehome( $data )
	{	
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_home SET status ='1' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Content has been Activated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function deactivatehome( $data )
	{		
		if(!empty( $data ) )
		{
			$id = $_POST['id'];
			$query = "UPDATE bz_home SET status ='0' WHERE id = '".$id."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Content has been Deactivated successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

	public function sellershare( array $data )
	{
		if(!empty( $data ) )
		{

			$trimmed_data = $data;
			$seller = mysqli_real_escape_string( $this->_con, $trimmed_data['seller'] );
			$seller_id = mysqli_real_escape_string( $this->_con, $trimmed_data['seller_id'] );
			$id = mysqli_real_escape_string( $this->_con, $trimmed_data['id'] );
			$rm_date = @date('Y-m-d');
			if((empty($seller)) || (empty($id))) 
			{	
				parent::add('e', '(*) Fields are required.');	
				return false;
			}
			$query = "INSERT INTO bz_seller_share SET order_table_id = '".$id."',amount ='".$seller."',seller='".$seller_id."',date='".$rm_date."'";
			if(mysqli_query($this->_con, $query))
			{
				parent::add('s', 'Payment has been successfully.');	
				return true;
			}	
		} 
		else
		{
			parent::add('e', '(*)All Fields are required.');	
			return false;
		}
	}

}
