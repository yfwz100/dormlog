<?php

use gtf\PDOBox;

class TaskHelper {
  static function getTaskByID($task_id) {
$sql =
<<<SQL
select * from task where task_id=:task_id
SQL;
    try {
      $dbh = PDOBox::get();
      $stat = $dbh->prepare($sql);
      $stat->bindParam(':task_id', $task_id, PDO::PARAM_INT);
      $stat->execute();
      $rs = $stat->fetch();
      
      $dbh = null;
      return $rs;
    } catch(PDOException $e) {
      echo 'Error!!';
    }
    return null;
  }

  static function getTasks($sql_cond=null, array $param=null) {
    $tasks_list = array();
    $sql =
<<<SQL
select * from task $sql_cond
SQL;
    try {
      $dbh = PDOBox::get();
      $stat = $dbh->prepare($sql);
      $stat->execute($param);

      while ($row = $stat->fetch()) {
        $tasks_list[] = $row;
      }
    } catch(PDOException $e) {
      echo 'Error!!';
    }
    return $tasks_list;
  }

  static function updateTask(array $task) {
    try {
      $dbh = PDOBox::get();

      $update_sql = 
<<<SQL
update task
set title=:title, description=:description, mod_date=datetime(), progress=:progress
where task_id=:task_id
SQL;
      $stat = $dbh->prepare($update_sql);
      $length = $stat->execute($task);
      if ($length == 1) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      return false;
    }
  }
  
  static function addTask(array $task) {
    try {
      $dbh = PDOBox::get();

      $add_sql =
<<<SQL
insert into task values(null,:title,:description,:author_id,datetime(),datetime(),:progress)
SQL;
      
      $stat = $dbh->prepare($add_sql);
      $length = $stat->execute($task);

      if ($length > 0) {
        return true;
      } else {
        return false;
      }

      $dbh = null;
    } catch (PDOException $e) {
      return false;
    }
  }

  static function removeTask($task_id) {
    try {
      $dbh = PDOBox::get();

      $del_sql =
<<<SQL
delete from task where task_id=:task_id
SQL;

      $stat = $dbh->prepare($del_sql);
      $length = $stat->execute(array(
        ":task_id"=>$task_id
      ));

      if ($length > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      return false;
    }
  }
}
