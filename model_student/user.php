<?php
namespace Model\User;
use \Db;
use \PDOException;
/**
 * User model
 *
 * This file contains every db action regarding the users
 */

/**
 * Get a user in db
 * @param id the id of the user in db
 * @return an object containing the attributes of the user or null if error or the user doesn't exist
 */
function get($id) {
	$db = \Db::dbc();
	$sth =$db->prepare("SELECT * FROM USER WHERE IDUSER = :id");	
	try {
		
		if($sth->execute(array(":id" =>$id))){			
			foreach($sth->fetchAll() as $row) {
			 return (object) array(
        		"id" => $row["IDUSER"],
        		"username" => $row["NOMU"],
        		"name" => $row["PRENOMUSER"],
        		"password" => $row["MP"],
        		"email" => $row["ADRESSE"],
        		"avatar" => $row["AVATAR_PATH"]
   			 );	
			}
		}else{
			return NULL;
		 }
	}	
 	catch (\PDOException $e) {
	echo $e->getMessage();
	}

    
  
}

/**
 * Create a user in db
 * @param username the user's username
 * @param name the user's name
 * @param password the user's password
 * @param email the user's email
 * @param avatar_path the temporary path to the user's avatar
 * @return the id which was assigned to the created user, null if an error occured
 * @warning this function doesn't check whether a user with a similar username exists
 * @warning this function hashes the password
 */
function create($username, $name, $password, $email, $avatar_path) {
	/*$db = \Db::dbc();
	$sql="INSERT INTO USER (NOMU,PRENOMUSER, INSDATE,MP, ADRESSE,AVATAR_PATH)VALUES(:username,:name,now(),:password,:email,:avatar_path)";
	$sth = $db->prepare($sql);
	try{
		if($sth->execute(array(":username"=>$username,":name"=>$name,":password"=>hash_password($password),":email"=>$email,":avatar_path"=>$avatar_path))){
			return $db->lastInsertId();
		}
		else
			return NULL;
	}catch(PDOException $e){
        	echo 'failed：'.$e->getMessage();
        exit;
    	}
    return id;*/
	$db = \Db::dbc();
	$password=hash_password($password);
	$sql = "INSERT INTO USER(NOMU, INSDATE, ADRESSE, MP, AVATAR_PATH, PRENOMUSER) VALUES(:username,now(),:email,:password,:avatar_path,:name)";
	$sth = $db->prepare($sql);
	try{
		if($sth->execute(array(":username"=>$username,":name"=>$name,":password"=>hash_password($password),":email"=>$email,":avatar_path"=>$avatar_path))){
	       		return $db->lastInsertId();
	}
	else
		return NULL;

	}catch(\PDOException $e){
		echo $e->getMessage();
      }
	//return id;
}

/**
 * Modify a user in db
 * @param uid the user's id to modify
 * @param username the user's username
 * @param name the user's name
 * @param email the user's email
 * @warning this function doesn't check whether a user with a similar username exists
 */
function modify($uid, $username, $name, $email) {
	/*$db = \Db::dbc();
	$sql = ""*/
	$db = \Db::dbc();
	$sql="UPDATE USER SET NOMU = :username, PRENOMUSER = :name,ADRESSE=:email  WHERE IDUSER = :uid";
	$sth=$db->prepare($sql);
	try{
		$sth->execute(array(":uid"=>$uid,":username"=>$username,":name"=>$name,":email"=>$email));
	
	}catch(\PDOException $e){
        	echo $e->getMessage();
    	}
}

/**
 * Modify a user in db
 * @param uid the user's id to modify
 * @param new_password the new password
 * @warning this function hashes the password
 */
function change_password($uid, $new_password) {
	$db = \Db::dbc();
	$sql="UPDATE USER SET MP = :password  WHERE IDUSER = :uid";
	$sth=$db->prepare($sql);
	try{
		$sth->execute(array(":uid"=>$uid,":password"=>hash_password($new_password)));
	}catch(\PDOException $e){
        	echo $e->getMessage();
        exit;
    	}
}

/**
 * Modify a user in db
 * @param uid the user's id to modify
 * @param avatar_path the temporary path to the user's avatar
 */
function change_avatar($uid, $avatar_path) {
	$db = \Db::dbc();
	$sql='UPDATE USER SET AVATAR=? WHERE IDU =?';
	$sth=$db->prepare($sql);
	try{
		$sth->execute(array(":uid"=>$uid,":avatar_path"=>$avatar_path));
	}catch(\PDOException $e){
        	echo $e->getMessage();
        exit;
    	}
}

/**
 * Delete a user in db
 * @param id the id of the user to delete
 * @return true if the user has been correctly deleted, false else
 */
function destroy($id) {
	$db = \Db::dbc();
	$sql="DELETE FROM USER WHERE  IDUSER = :uid";
	$sth=$db->prepare($sql);
	try{
		if($sth->execute(array(":uid"=>$id))){
			return true;
		}
		else{
			return false;
		}
	}catch(\PDOException $e){
        	echo $e->getMessage();
        exit;
    	}
}

/**
 * Hash a user password
 * @param password the clear password to hash
 * @return the hashed password
 */
function hash_password($password) {
   //$pwd = md5($password);
    return $password;
    //return hash('md5', $password);
}

/**
 * Search a user
 * @param string the string to search in the name or username
 * @return an array of find objects
 */
function search($string) {
	$db = \Db::dbc();
	$sql="SELECT IDUSER FROM USER WHERE NOMU like :username OR PRENOMUSER like :name ";
	$sth=$db->prepare($sql);
	try{
		$sth->execute(array(":username"=>"%$string%",":name"=>"%$string%"));
			$n=0;
 			while($row=$sth->fetch()) {
    			$id[$n]=$row['IDUSER'];
			$arr[$n]=get($id[$n]);
			$n++;
 	 		}
  		return $arr;		
	}catch(\PDOException $e){
        	echo $e->getMessage();
        exit;
    	}    
	
	//return [get(1)];
}

/**
 * List users
 * @return an array of the objects of every users
 */
function list_all() {
	$db = \Db::dbc();
	$sql= "SELECT * FROM USER";
	$sth= $db->prepare($sql);
	try{
		$n=0;
		$sth->execute();		
 		while($row=$sth->fetch()) {
    			$id[$n]=$row['IDUSER'];
			$arry[$n]=get($id[$n]);
			$n++;
 	 	}
  		return $arry;

		
	}catch(\PDOException $e){
        	echo $e->getMessage();
        exit;
    	}
    //return [get(1)];
}

function getu($username)
{ 
  try 
    {  
    $db = \Db::dbc();
    $sql = "SELECT * FROM USER WHERE NOMU='$username'";
    $sth = $db->prepare($sql);
    $sth->execute();
    $row = $sth->fetch();
    if($row==false)
    {
      return NULL;
    }
    else
    {    return (object) array(
        "id" => $row['IDUSER'],
        "username" => $row['NOMU'],
        "name" => $row ['PRENOMUSER'],
        "password" => $row['MP'],
        "email" => $row['ADRESSE'],
        "avatar" => $row['AVATAR_PATH']);
      }
    }
    catch (\PDOException $e)
    {
      echo $e->getMessage();
    }
}

/**
 * Get a user from its username
 * @param username the searched user's username
 * @return the user object or null if the user doesn't exist
 */
function get_by_username($username) {
	$db = \Db::dbc();
	$sql ="SELECT *FROM USER WHERE NOMU= :username";
	$sth = $db->prepare($sql);
	$sth->execute(array(":username" => $username));
    	try {
		$row=$sth->fetch(); 
		if($row){			
			 return (object) array(
        		"id" => $row['IDUSER'],
        		"username" => $row['NOMU'],
        		"name" => $row['PRENOMUSER'],
        		"password" => $row['MP'],
        		"email" => $row['ADRESSE'],
        		"avatar" => $row['AVATAR_PATH']
   			 );	
			
		}else{
			return NULL;
		 }
	}	
 	catch (\PDOException $e) {
	print $e->getMessage();
	}

}

/**
 * Get a user's followers
 * @param uid the user's id
 * @return a list of users objects
 */
function get_followers($uid) {
        /*
	$db = \Db::dbc();
	$List=array();
    	$sql ="SELECT * FROM SUIVRE INNER JOIN USER ON SUIVRE.IDUSER_SUIVI=USER.IDUSER WHERE SUIVRE.IDUSER_SUIVRE= :uid";
    	$sth=$db->prepare($sql);
    	$sth->execute(array(":uid"=>$uid));	
	try 
	{
    		if($sth)
	  		{
      		foreach($sth->fetchAll() as $row) 
      			{
      			$List[]=(object) array(
       			"id" => $row['IDUSER'],
        		"username" => $row['NOMU'],
        		"name" => $row ['PRENOMUSER'],
        		"password" => $row['MP'],
       			"email" => $row['ADRESSE'],
       			"avatar" => $row['AVATAR_PATH']);
      }
            return $List;
    }
	  else{
        return NULL;}
  }
  catch(\PDOException $e)
  {
  echo $e->getMessage();
  }*/

    try{
        $db = \Db::dbc();
	$sql = "SELECT * FROM SUIVRE INNER JOIN USER ON SUIVRE.IDUSER_SUIVI=USER.IDUSER WHERE SUIVRE.IDUSER_SUIVRE= :id";
        $sth = $db->prepare($sql);
    	$users = array();
	if($sth->execute(array(":id" => $uid))){
		foreach($sth->fetchAll() as $row) {
                        //var_dump($row);
                        $users[] = get($row["IDUSER_SUIVI"]);
			}
			return $users;
		}
		else{
			return null;
		}
    	
    } catch(\PDOException $e){
    	echo $e->getMessage();
    }    	
	//return [get(2)];
}

/**
 * Get the users our user is following
 * @param uid the user's id
 * @return a list of users objects
 */
function get_followings($uid) {
	try 
  {
    $db = \Db::dbc();
    $sql ="SELECT * FROM SUIVRE INNER JOIN USER ON SUIVRE.IDUSER_SUIVRE=USER.IDUSER WHERE SUIVRE.IDUSER_SUIVI='$uid'";
    $sth=$db->prepare($sql);
    $sth->execute();
    if($sth)
    {
      $List=array();
	  foreach($sth->fetchAll() as $row) 
      {
      $List[]=(object) array(
       "id" => $row['IDUSER'],
        "username" => $row['NOMU'],
        "name" => $row ['PRENOMUSER'],
        "password" => $row['MP'],
        "email" => $row['ADRESSE'],
        "avatar" => $row['AVATAR_PATH']);
      }
      return $List;
    }
    else{
        return NULL;}
  }
  catch(\PDOException $e)
  {
  echo $e->getMessage();
	//return [get(2)];
}
}
/**
 * Get a user's stats
 * @param uid the user's id
 * @return an object which describes the stats
 */
function get_stats($uid) {
    try{
        $db = \Db::dbc();
        $sql0 = "SELECT count(*) AS NB_POSTS FROM TWEET WHERE IDUSER = :id";
        $sql1 = "SELECT count(*) AS NB_FOLLOWERS FROM SUIVRE WHERE SUIVRE.IDUSER_SUIVRE = :id";
        $sql2 = "SELECT count(*) AS NB_FOLLOWINGS FROM SUIVRE WHERE SUIVRE.IDUSER_SUIVI = :id";
        $sth0 = $db->prepare($sql0);
        $sth1 = $db->prepare($sql1);
        $sth2 = $db->prepare($sql2);
        $nb_posts = 0;
        $nb_followers = 0;
        $nb_followings = 0;
	if($sth0->execute(array(":id" => $uid))){
		foreach($sth0->fetchAll() as $row) {
       		$nb_posts = $row["NB_POSTS"];
		     }
		}else{
		     return null;
		}
	if($sth1->execute(array(":id" => $uid))){
		foreach($sth1->fetchAll() as $row) {
       		$nb_followers = $row["NB_FOLLOWERS"];
		    }
		}else{
		     return null;
		}
	if($sth2->execute(array(":id" => $uid))){
		foreach($sth2->fetchAll() as $row) {
       		$nb_followings = $row["NB_FOLLOWINGS"];
		     }
		}
		else{
		    return null;
		}
                return (object) array(
                            "nb_posts" => $nb_posts,
                            "nb_followers" => $nb_followers,
                            "nb_following" => $nb_followings,
				);
    	
    } catch(\PDOException $e){
    	echo $e->getMessage();
    }
/*
    return (object) array(
        "nb_posts" => 10,
        "nb_followers" => 50,
        "nb_following" => 66
    );*/
}

/**
 * Verify the user authentification
 * @param username the user's username
 * @param password the user's password
 * @return the user object or null if authentification failed
 * @warning this function must perform the password hashing   
 */
function check_auth($username, $password) {
	try 
	{ $password=hash_password($password);
    $db = \Db::dbc();
    $sql ="SELECT * FROM USER WHERE NOMU=:username AND MP=:password";
    $sth=$db->prepare($sql);
    $sth->execute(array(":username"=>$username, ":password"=>$password));
    $row = $sth->fetch();
        if($row)
        {
        return (object) array(
        "id" => $row['IDUSER'],
        "username" => $row['NOMU'],
        "name" => $row ['PRENOMUSER'],
        "password" => $row['MP'],
        "email" => $row['ADRESSE'],
        "avatar" => $row['AVATAR_PATH']);
        }

        else
        {
	echo "here wrong";
        return NULL;
        }
    }
    catch(\PDOException $e)
    {
      echo $e->getMessage();
    }
}

/**
 * Verify the user authentification based on id
 * @param id the user's id
 * @param password the user's password (already hashed)
 * @return the user object or null if authentification failed
 */
function check_auth_id($id, $password) {
	try 
	{
	$db = \Db::dbc();
    $sql ="SELECT * FROM USER WHERE IDUSER=? AND MP=?";
	$sth=$db->prepare($sql);
    $sth->execute(array($id, $password));
    $row = $sth->fetch();
    if($row == false)
    {
        return NULL;
    }
    else
    {
    return (object) array
    (
    "id" => $row['IDUSER'],
    "username" => $row['NOMU'],
    "name" => $row ['PRENOMUSER'],
    "password" => $row['MP'],
    "email" => $row['ADRESSE'],
    "avatar" => $row['AVATAR_PATH']
    );
        }
    }
    catch(\PDOException $e)
    {
      echo $e->getMessage();
    }	
}

/**
 * Follow another user
 * @param id the current user's id
 * @param id_to_follow the user's id to follow
 */

function follow($id, $id_to_follow) {
	$db = \Db::dbc();
	$sql="INSERT INTO SUIVRE (IDUSER_SUIVI,IDUSER_SUIVRE,SUIV_DATE) VALUES ('$id','$id_to_follow',now())";
	$sth=$db->prepare($sql);
   	$sth->execute();
	try{
	$row=$sth->rowcount();
	    if($row)
	  {
		return true; 
	  } 
	  else 
	  {
		return false;
	  }
  }
  catch(\PDOException $e)
  {
  echo $e->getMessage();
  }
}

/**
 * Unfollow a user
 * @param id the current user's id
 * @param id_to_follow the user's id to unfollow
 */

function unfollow($id, $id_to_unfollow) {
	$db = Db::dbc();
	$sql="DELETE FROM SUIVRE WHERE IDUSER_SUIVI = '$id' AND IDUSER_SUIVRE ='$id_to_unfollow' ";
	$sth = $db->prepare($sql);
	try{
		$sth->execute();
	}catch(\PDOException $e){
        	echo $e->getMessage();
        exit;
    	}
}

