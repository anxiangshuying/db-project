<?php
namespace Model\Notification;
use \Db;
use \PDOException;
/**
 * Notification model
 *
 * This file contains every db action regarding the notifications
 */

/**
 * Get a liked notification in db
 * @param uid the id of the user in db
 * @return a list of objects for each like notification
 * @warning the post attribute is a post object
 * @warning the liked_by attribute is a user object
 * @warning the date attribute is a DateTime object
 * @warning the reading_date attribute is either a DateTime object or null (if it hasn't been read)
 */
function get_liked_notifications($uid) {
    try
    {   
    $db = \Db::dbc();
	$List=array();
    $sql ="SELECT AIMER.IDUSER,AIMER.IDTWEET,AIMERDATE,LLOOKORNOT FROM AIMER INNER JOIN TWEET ON(AIMER.IDTWEET=TWEET.IDTWEET) WHERE TWEET.IDUSER='$uid'";
    $sth=$db->prepare($sql);
    $sth->execute();
    $l=$sth->rowcount();
    if($l)
        {
            foreach($sth->fetchAll() as $row)
            { $o=new \DateTime($row['AIMERDATE']);
            $List[]=(object) array(
            "type" => "liked",
            "post" =>\Model\Post\get($row['IDTWEET']),
            "liked_by" =>\Model\User\get($row['IDUSER']),
            "date" => $o,
            "reading_date" => $row['LLOOKORNOT']);
                  
            }
        return $List;
        
        }
          else
        {
            return $List;
        }
    }
catch(\PDOException $e)
{
echo $e->getMessage();
}   

    // return [(object) array(
    //     "type" => "liked",
    //     "post" => \Model\Post\get(1),
    //     "liked_by" => \Model\User\get(3),
    //     "date" => new \DateTime("NOW"),
    //     "reading_date" => new \DateTime("NOW")
    // )];
}

/**
 * Mark a like notification as read (with date of reading)
 * @param pid the post id that has been liked
 * @param uid the user id that has liked the post
 */
function liked_notification_seen($pid, $uid) {
    try 
    {   
       $db = \Db::dbc();
       $date = new \DateTime();
       $ldate=$date->format('Y-m-d H:i:s');
       $sql = "UPDATE AIMER SET LLOOKORNOT='$ldate' WHERE IDTWEET='$pid' AND IDUSER='$uid'";
       
     $sth=$db->prepare($sql);
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
 * Get a mentioned notification in db
 * @param uid the id of the user in db
 * @return a list of objects for each like notification
 * @warning the post attribute is a post object
 * @warning the mentioned_by attribute is a user object
 * @warning the reading_date object is either a DateTime object or null (if it hasn't been read)
 */
function get_mentioned_notifications($uid) {
    try
    {   
    $db = \Db::dbc();
	$List=array();
    $sql ="SELECT TWEET.IDUSER,MENTION.IDTWEET,MLOOKORNOT,TWEET.TWDATE FROM MENTION INNER JOIN TWEET ON(MENTION.IDTWEET=TWEET.IDTWEET) WHERE MENTION.IDUSER='$uid'";
    $sth=$db->prepare($sql);
    $sth->execute();
    $l=$sth->rowcount();
   if($l)
        {
            foreach($sth->fetchAll() as $row){
              $o=new \DateTime($row['TWDATE']);
            $List[]=(object) array(
            "type" => "mentioned",
            "post" =>\Model\Post\get($row['IDTWEET']),
            "mentioned_by" =>\Model\User\get($row['IDUSER']),
            "date" => $o,
            "reading_date" => $row['MLOOKORNOT']);
            }
        return $List;
        
        }
          else
        {
            return $List;
        }
    }
catch(\PDOException $e)
{
echo $e->getMessage();
}   
    // return [(object) array(
    //     "type" => "mentioned",
    //     "post" => \Model\Post\get(1),
    //     "mentioned_by" => \Model\User\get(3),
    //     "date" => new \DateTime("NOW"),
    //     "reading_date" => null
    // )];
}

/**
 * Mark a mentioned notification as read (with date of reading)
 * @param uid the user that has been mentioned
 * @param pid the post where the user was mentioned
 */
function mentioned_notification_seen($uid, $pid) {
    try 
    {   
       $db = \Db::dbc();
       $date = new \DateTime("NOW");
       $mdate=$date->format('Y-m-d H:i:s');
       $sql = "UPDATE MENTION SET MLOOKORNOT='$mdate' WHERE IDTWEET='$pid' AND IDUSER='$uid'";
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
 * Get a followed notification in db
 * @param uid the id of the user in db
 * @return a list of objects for each like notification
 * @warning the user attribute is a user object which corresponds to the user following.
 * @warning the reading_date object is either a DateTime object or null (if it hasn't been read)
 */
function get_followed_notifications($uid) {
    try
    {   
    $db = \Db::dbc();
	$List=array();
    $sql ="SELECT IDUSER_SUIVI,FLOOKORNOT,SUIV_DATE FROM SUIVRE WHERE IDUSER_SUIVRE='$uid'";
    $sth=$db->prepare($sql);
    $sth->execute();
    $l=$sth->rowcount();
   if($l)
        {
            foreach($sth->fetchAll() as $row){
              $o=new \DateTime($row['SUIV_DATE']);
            $List[]=(object) array(
            "type" => "followed",
            "user" =>\Model\User\get($row['IDUSER_SUIVI']),
            "date" => $o,
            "reading_date" => $row['FLOOKORNOT']);
            }
        return $List;
        
        }
    else
        {
            return $List;
        }
    }
catch(\PDOException $e)
{
echo $e->getMessage();
}  
    // return [(object) array(
    //     "type" => "followed",
    //     "user" => \Model\User\get(1),
    //     "date" => new \DateTime("NOW"),
    //     "reading_date" => new \DateTime("NOW")
    // )];
}

/**
 * Mark a followed notification as read (with date of reading)
 * @param followed_id the user id which has been followed
 * @param follower_id the user id that is following
 */
function followed_notification_seen($followed_id, $follower_id) {
    try 
    {   
       $db = \Db::dbc();
       $date = new \DateTime("NOW");
       $fdate=$date->format('Y-m-d H:i:s');
       $sql = "UPDATE SUIVRE SET FLOOKORNOT='$fdate' WHERE IDUSER_SUIVRE='$followed_id' AND IDUSER_SUIVI='$follower_id'";
     $sth = $db->prepare($sql);
     $sth->execute();
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
     catch (\PDOException $e)
     {
       echo $e->getMessage();
     }

}
