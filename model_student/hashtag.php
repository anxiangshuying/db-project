<?php
namespace Model\Hashtag;
use \Db;
use \PDOException;
/**
 * Hashtag model
 *
 * This file contains every db action regarding the hashtags
 */

/**
 * Attach a hashtag to a post
 * @param pid the post id to which attach the hashtag
 * @param hashtag_name the name of the hashtag to attach
 */
function attach($pid, $hashtag_name) {
    try 
    {
      $db = \Db::dbc();
      $sql="SELECT IDHASH FROM HASHTAG WHERE NOMTAG='$hashtag_name'";
      $sth=$db->prepare($sql);
      $sth->execute();
      $row=$sth->fetch();
      //var_dump($row);
      if ($row) {
      $sql1="INSERT INTO CONCERNER (IDHASH,IDTWEET) VALUES (?,?)";
      $sth1 = $db->prepare($sql1);
      $sth1->execute(array($row['IDHASH'],$pid));
      $row1=$sth1->rowcount();
      }
      else
      {
          $sql2="INSERT INTO HASHTAG (NOMTAG) VALUES ('$hashtag_name')";
          $sth2 = $db->prepare($sql2);
          $row2=$sth2->execute();
          $last_id=$db->lastInsertId();
          $sql1="INSERT INTO CONCERNER (IDHASH,IDTWEET) VALUES ('$last_id','$pid')";
          $sth1 = $db->prepare($sql1);
          $sth1->execute();
          $row1=$sth1->rowcount();
      }
      if($row1)
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

function addhashtag($hashtag_name){
  try{
  $db = \Db::dbc();
  $sql1="SELECT IDHASH FROM HASHTAG WHERE NOMTAG = '$hashtag_name'";
  $sth1 = $db->prepare($sql1);
  $sth1->execute();
  $row1=$sth1->rowcount();
  if($row1==0){

  $sql="INSERT INTO HASHTAG (NOMTAG) VALUES ('$hashtag_name')";
   $sth = $db->prepare($sql);
   $sth->execute();
   $row=$sth->rowcount();	
   if($row){
   	return true;
   }
   else
   {
   	return false;
   }
}
}
catch (\PDOException $e)
   {
      echo $e->getMessage();
   }

}
/**
 * List hashtags
 * @return a list of hashtags names
 */
function list_hashtags() {
    try{
        $db = \Db::dbc();
        $sql="SELECT * FROM TWEET";
        $sth = $db->prepare($sql);
        $sth->execute();
        foreach ($sth->fetchAll()as$row)
          {
        $List=\Model\Post\extract_hashtags($row['CONTIENT']);
        array_map('\Model\Hashtag\addhashtag', $List);
          }
        $sql1="SELECT NOMTAG FROM HASHTAG";
        $sth1 = $db->prepare($sql1);
        $sth1->execute();
        foreach ($sth1->fetchAll()as$row1)
         {
             $List2[]=($row1['NOMTAG']);
        }
          return $List2;
    }
      catch (\PDOException $e)
         {
            echo $e->getMessage();
         }
    // return ["Test"];
}

function gethashtag($pid){
    try{
    $db = \Db::dbc();
    $sql="SELECT * FROM CONCERNER INNER JOIN HASHTAG ON(CONCERNER.IDHASH=HASHTAG.IDHASH) WHERE IDTWEET='$pid'";
    $sth = $db->prepare($sql);
    $sth->execute();
    foreach ($sth->fetchAll()as$row)
      {
    $List[]=(object)array(
      "hashtagid"=>$row['IDHASH'],
      "hashtag"=>$row['NOMTAG']
    );
    return $List;
    }
    }
  catch (\PDOException $e)
     {
        echo $e->getMessage();
     }
  }

function getidhashtag($hashtag_name){
    try{
    $db = \Db::dbc();
    $sql="SELECT IDHASH FROM HASHTAG WHERE NOMTAG='$hashtag_name'";
     $sth = $db->prepare($sql);
     $sth->execute();
     $row=$sth->fetch();
     if($row){
      return $row['IDHASH'];
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
/**
 * List hashtags sorted per popularity (number of posts using each)
 * @param length number of hashtags to get at most
 * @return a list of hashtags
 */
function list_popular_hashtags($length) {
    try{
        $db = \Db::dbc();
        $sql="SELECT * FROM TWEET";
        $sth = $db->prepare($sql);
        $sth->execute();
           foreach($sth->fetchAll()as$row) 
           {
    
        $List=\Model\Post\extract_hashtags($row['CONTIENT']);
        $idh=array_map('\Model\hashtag\getidhashtag', $List);
        if($List){
        foreach ($idh as $h) {
        $sql4="SELECT * FROM HASHTAG WHERE IDHASH='$h'";
        $sth4 = $db->prepare($sql4);
        $sth4->execute();
        $line=$sth4->rowcount();
        if($line==0)
        {
        $sql2="INSERT INTO CONCERNER (IDHASH,IDTWEET) VALUES ('$h','$row[IDTWEET]')";
        $sth2 = $db->prepare($sql2);
        $sth2->execute();
        }
        }
      }
        }
        $sql3="SELECT NOMTAG FROM CONCERNER INNER JOIN HASHTAG ON(CONCERNER.IDHASH=HASHTAG.IDHASH) GROUP BY CONCERNER.IDHASH ORDER BY COUNT(CONCERNER.IDTWEET) DESC LIMIT $length";
        $sth3=$db->prepare($sql3);
        $sth3->execute();
        $List2=array();
        foreach ($sth3->fetchAll()as$row3) {
            $List2[]=$row3['NOMTAG'];
          } 
          return $List2; 
      }
    catch (\PDOException $e)
       {
          echo $e->getMessage();
       }

    // return ["Hallo"];
}

/**
 * Get posts for a hashtag
 * @param hashtag the hashtag name
 * @return a list of posts objects or null if the hashtag doesn't exist
 */
function get_posts($hashtag_name) {
    try{
        
            $db = \Db::dbc();
            $idht=getidhashtag($hashtag_name);
            $sql="SELECT IDTWEET FROM CONCERNER WHERE IDHASH = '$idht'";
            $sth = $db->prepare($sql);
            $sth->execute();
            $i=$sth->rowcount();
            if($i){
            foreach ($sth->fetchAll()as$row) {
            $List[]=\Model\Post\get($row['IDTWEET']);
            }
            return $List;
            }
            else{
              return NULL;
            }
        
          }
          catch (\PDOException $e)
           {
              echo $e->getMessage();
           }
    // return [\Model\Post\get(1)];
}

/** Get related hashtags
 * @param hashtag_name the hashtag name
 * @param length the size of the returned list at most
 * @return an array of hashtags names
 */
function get_related_hashtags($hashtag_name, $length) {
    try{
        
            $db = \Db::dbc();
            $idht=getidhashtag($hashtag_name);
            $sql="SELECT DISTINCT NOMTAG FROM CONCERNER INNER JOIN HASHTAG ON(CONCERNER.IDHASH=HASHTAG.IDHASH) WHERE IDTWEET IN(SELECT IDTWEET FROM CONCERNER WHERE IDHASH='$idht')AND CONCERNER.IDHASH<>'$idht'
            LIMIT $length";
            $sth = $db->prepare($sql);
            $sth->execute();
            $i=$sth->rowcount();
            if($i){
            foreach ($sth->fetchAll()as$row) {
            $List[]=$row['NOMTAG'];
            }
            return $List;
            }
            else{
              return NULL;
            }
        
          }
          catch (\PDOException $e)
           {
              echo $e->getMessage();
           }
    // return ["Hello"];
}
