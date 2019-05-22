<?php
namespace Model\Post;
use \Db;
use \PDOException;
/**
 * Post
 *
 * This file contains every db action regarding the posts
 */

/**
 * Get a post in db
 * @param id the id of the post in db
 * @return an object containing the attributes of the post or false if error
 * @warning the author attribute is a user object
 * @warning the date attribute is a DateTime object
 */

function get($id) {
    try
    {
        $db = \Db::dbc();
        $sql = "SELECT * FROM TWEET WHERE IDTWEET = '$id'";
        $sth = $db->prepare($sql);
        $sth->execute();
        $row = $sth->fetch();
        if($row==false)
        { 
          return NULL;
        }
        else
        {
        return (object) array(
        "id" => $row["IDTWEET"],
        "text" => $row["CONTIENT"],
        "date" => (object) array ($row ["TWDATE"]),
        "author" => \Model\User\get($row["IDUSER"]),
        );
        }
 
    }
    catch( \PDOException $e)
    {
        echo $e->getMessage();
    }
}

/**
 * Get a post with its likes, responses, the hashtags used and the post it was the response of
 * @param id the id of the post in db
 * @return an object containing the attributes of the post or false if error
 * @warning the author attribute is a user object
 * @warning the date attribute is a DateTime object
 * @warning the likes attribute is an array of users objects
 * @warning the hashtags attribute is an of hashtags objects
 * @warning the responds_to attribute is either null (if the post is not a response) or a post object
 */
function get_with_joins($id) {
    try 
    {     
      $db = \Db::dbc();
      $sql = "SELECT * FROM TWEET WHERE IDTWEET ='$id'";
      $sth = $db->prepare($sql);
      $sth->execute();
      if(empty($sth))
      {
          return NULL;
      }
      else
      {
        $row = $sth->fetch();
        return (object) array
         ("id" => $row['IDTWEET'],
           "text" => $row['CONTIENT'],
           "date" => $row ['TWDATE'],
           "likes" => get_likes($row['IDTWEET']),
           "hashtags" => \Model\hashtag\gethashtag($id),
           "author" => \Model\User\get($row['IDUSER']),
           "responds_to" => get($row['IDTWEET_REDONDRE'])
           );
      }
    }
    catch (\PDOException $e)
    {
      echo $e->getMessage();
    }   
}
 
/**
 * Create a post in db
 * @param author_id the author user's id
 * @param text the message
 * @param mentioned_authors the array of ids of users who are mentioned in the post
 * @param response_to the id of the post which the creating post responds to
 * @return the id which was assigned to the created post, null if anything got wrong
 * @warning this function computes the date
 * @warning this function adds the mentions (after checking the users' existence)
 * @warning this function adds the hashtags
 * @warning this function takes care to rollback if one of the queries comes to fail.
 */
function create($author_id, $text, $response_to=null) {
try{
     $db = \Db::dbc();
     $sql = "INSERT INTO TWEET (IDTWEET_REDONDRE,IDUSER,TWDATE,CONTIENT) 
             VALUES (:post_id_response,:user_id,:post_date,:post_content)";
     $res = $db->prepare($sql);
     //$db->beginTransaction();
     if($res->execute(array(":post_id_response"=>$response_to,":user_id"=>$author_id,":post_date"=>Date('Y/m/d H:i:s'),":post_content"=>$text))){
              $post_id = $db->lastInsertId();
         $mention_users = extract_mentions($text);
         //var_dump($mention_users);
         if($mention_users != null){
               foreach($mention_users as $mention){
               $user = \Model\User\get_by_username($mention);
                       if(!mention_user($post_id, $user->id)){
                             //$db->rollBack();
                             return false;
                          }  
                     }
                }
          $hashtags = extract_hashtags($text);
         if($hashtags != null){
               foreach($hashtags as $hashtag){
                    if(!\Model\Hashtag\attach($post_id, $hashtag)){
                             //$db->rollBack();
                             return false;     
                            }
                        }
                }
              //$db->commit();
              return $post_id;
            
     }else
          {
              return null;
      }
      //$db->rollBack();
      //$db->commit();
     }catch(\PDOException $e){
                echo $e->getMessage();
    }
}


/**
 * Mention a user in a post
 * @param pid the post id
 * @param uid the user id to mention
 */
function mention_user($pid, $uid) {
    try 
    {   
    $db = \Db::dbc();
    $sql = "INSERT INTO MENTION(IDTWEET,IDUSER) VALUES('$pid','$uid')";
    $sth = $db->prepare($sql);
    $sth->execute();
     
    $row=$sth->rowcount();
    if(empty($row))
    {
       return false;
    }
    else
    {
     return true;
    }
    }
    catch (\PDOException $e)
    {
       echo $e->getMessage();
    }
}

/**
 * Get mentioned user in post
 * @param pid the post id
 * @return the array of user objects mentioned
 */
function get_mentioned($pid) {
    try{
        $db = \Db::dbc();
        $sql="SELECT CONTIENT FROM TWEET WHERE IDTWEET='$pid'";
        $sth = $db->prepare($sql);
        $sth->execute();
        $row=$sth->fetch();
        $Text=extract_mentions($row['CONTIENT']);
        return $List=array_map('\Model\User\getu', $Text);
      }
      catch (\PDOException $e)
      {
        echo $e->getMessage();
      }
    // return [];
}

/**
 * Delete a post in db
 * @param id the id of the post to delete
 */
function destroy($id) {
    try 
    {
     $db = \Db::dbc();
     $sql="DELETE FROM TWEET WHERE IDTWEET ='$id'";
     $sth = $db->prepare($sql);
     $sth->execute();
     $row=$sth->rowcount();
     if(empty($row))
     {
       return false;
     }
     else
     {
       return true;
     }
    }
     catch (\PDOException $e)
     {
        echo $e->getMessage();
     }
}

/**
 * Search for posts
 * @param string the string to search in the text
 * @return an array of find objects
 */
function search($string) {
    try 
    {
      $db = \Db::dbc();
      $List=array();
      $sql ="SELECT * FROM TWEET WHERE CONTIENT LIKE '%$string%'";
      $sth = $db->prepare($sql);
      $sth->execute();
      if($sth)
          {
            foreach ($sth ->fetchAll()as $row) {
          $List[]=get($row['IDTWEET']);
        }
  
          return $List;
      }
      else
      {
          return NULL;
      }
  
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
    }
    // return [];
}

/**
 * List posts
 * @param date_sorted the type of sorting on date (false if no sorting asked), "DESC" or "ASC" otherwise
 * @return an array of the objects of each post
 */
function list_all($date_sorted=false) {
    try 
    {
        $db = \Db::dbc();
        $List=array();
        if($date_sorted)
        {
            if($date_sorted=='DESC')
            {
                $sql ="SELECT * FROM TWEET ORDER BY TWDATE DESC";
            }
            else
            {
                $sql ="SELECT * FROM TWEET ORDER BY TWDATE ASC";
            }
        }
        else
        {
            $sql ="SELECT * FROM TWEET";
        }
        $sth = $db->prepare($sql);
        $sth->execute();
        foreach($sth ->fetchAll()as $row)
        {   
            $List[]= get($row['IDTWEET']);
        }
        return $List;
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
    }
    // return [];
}

/**
 * Get a user's posts
 * @param id the user's id
 * @param date_sorted the type of sorting on date (false if no sorting asked), "DESC" or "ASC" otherwise
 * @return the list of posts objects
 */
function list_user_posts($id, $date_sorted="DESC") {
    try 
    {
        $db = \Db::dbc();
        $List=array();
        if($date_sorted)
        {
            if($date_sorted=='DESC')
            {
                $sql ="SELECT * FROM TWEET WHERE IDUSER='$id' ORDER BY TWDATE DESC";
            }
            else
            {
                $sql ="SELECT * FROM TWEET WHERE IDUSER='$id' ORDER BY TWDATE ASC";
            }
        }
        else
        {
            $sql ="SELECT * FROM TWEET WHERE IDUSER='$id";
        }
        $sth = $db->prepare($sql);
        $sth->execute();
        foreach($sth ->fetchAll()as $row)
        {   
             $List[]= get($row['IDTWEET']);
        }
        return $List;
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
    }
    // return [];
}

/**
 * Get a post's likes
 * @param pid the post's id
 * @return the users objects who liked the post
 */
function get_likes($pid) {
    try 
    {   
        $db = \Db::dbc();
        $List=array();
        $sql = "SELECT * FROM AIMER  WHERE IDTWEET='$pid'";
        $sth = $db->prepare($sql);
        $sth->execute();
        if($sth)
        {
            foreach ($sth ->fetchAll()as$row) 
            {
                $List[]= \Model\User\get($row['IDUSER']);
            }
        return $List;
        }
        else
        { 
            return NULL;
        } 
    }  
    catch (\PDOException $e)
    {
      echo $e->getMessage();
    }
    // return [];
}

/**
 * Get a post's responses
 * @param pid the post's id
 * @return the posts objects which are a response to the actual post
 */
function get_responses($pid) {
    try 
    {   
        $db = \Db::dbc();
        $List=array();
        $sql = "SELECT * FROM TWEET  WHERE IDTWEET_REDONDRE=?";
        $sth = $db->prepare($sql);
        $sth->execute(array($pid));
        if($sth)
        {
            foreach ($sth ->fetchAll()as$row) 
            {
                $List[]= get($row['IDTWEET']);
            }
        return $List;
        }
        else
        { 
        return NULL;
        } 
    }  
    catch (\PDOException $e)
    {
      echo $e->getMessage();
    }
    // return [];
}

/**
 * Get stats from a post (number of responses and number of likes
 */
function get_stats($pid) {
    try 
    {
        $db = \Db::dbc();
        $sql1 ="SELECT * FROM AIMER WHERE IDTWEET='$pid'";
        $res=$db->query($sql1);
        $rowAimer=$res->fetchAll();
      
      
        $sql2 ="SELECT * FROM TWEET WHERE IDTWEET_REDONDRE='$pid'";
        $res2=$db->query($sql2);
        $rowResponse=$res2->fetchAll();
  
        return (object) array(
          "nb_likes" => count($rowAimer),
          "nb_responses" => count($rowResponse));
        }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
    }
    // return (object) array(
    //     "nb_likes" => 10,
    //     "nb_responses" => 40
    // );
}

/**
 * Like a post
 * @param uid the user's id to like the post
 * @param pid the post's id to be liked
 */
function like($uid, $pid) {
    try 
    {   
       $db = \Db::dbc();
       $date = new \DateTime("NOW");
       $ldate=$date->format('Y-m-d H:i:s');
       $sql = "INSERT INTO AIMER(IDUSER,IDTWEET,AIMERDATE) VALUES('$uid','$pid','$ldate')";
       $sth = $db->prepare($sql);
       $sth->execute();
       $row=$sth->rowcount();
        if(empty($row))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    catch (\PDOException $e)
    {
       echo $e->getMessage();
    }
}

/**
 * Unlike a post
 * @param uid the user's id to unlike the post
 * @param pid the post's id to be unliked
 */
function unlike($uid, $pid) {
    try 
    {   
        $db = \Db::dbc();
        $sql = "DELETE FROM AIMER WHERE IDUSER='$uid'AND IDTWEET='$pid'";
        $sth = $db->prepare($sql);
        $sth->execute();
        $row=$sth->rowcount();
        if(empty($row))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    catch (\PDOException $e)
    {
       echo $e->getMessage();
    }
}

