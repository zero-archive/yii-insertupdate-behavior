<?php
/**
 * InsertUpdateCommandBehavior class file.
 *
 * @package YiiInsertUpdateCommandBehavior
 * @author dZ <mail@dotzero.ru>
 * @link http://www.dotzero.ru
 * @link https://github.com/dotzero/YiiInsertUpdateCommandBehavior
 * @license MIT
 * @version 1.0 (1-nov-2012)
 */

/**
 * The InsertUpdateCommandBehavior extension adds up some functionality to the default
 * possibilites of yii´s CDbCommand implementation. Creates and executes an
 * INSERT with ON DUPLICATE KEY UPDATE MySQL statement.
 *
 * Requirements:
 * Yii Framework 1.1.0 or later
 *
 * Installation:
 * Extract the release file under 'protected/components'
 *
 * Basic usage:
 * $command = Yii::app()->db->createCommand();
 * $command->attachBehavior('InsertUpdateCommandBehavior', new InsertUpdateCommandBehavior);
 * $command->insertUpdate('tbl_user', array(
 *     'name'=>'Tester',
 *     'email'=>'tester@example.com',
 *     'counter'=>'1'
 * ), array(
 *     'name'=>'Tester',
 *     'email'=>'tester@example.com'
 * ));
 *
 * Creates and executes an INSERT SQL with ON DUPLICATE KEY UPDATE MySQL statement
 * INSERT INTO `tbl_user` (`name`, `email`, `counter`) VALUES ('Tester', 'tester@example.com', 1)
 * ON DUPLICATE KEY UPDATE `name`='Tester', `email`='tester@example.com';
 *
 * Advanced usage:
 * $command = Yii::app()->db->createCommand();
 * $command->attachBehavior('InsertUpdateCommandBehavior', new InsertUpdateCommandBehavior);
 * $command->insertUpdate('tbl_user', array(
 *     'name'=>'Tester',
 *     'email'=>'tester@example.com',
 *     'counter'=>'1'
 * ), array(
 *     'name'=>'Tester',
 *     'email'=>'tester@example.com'
 *     'counter'=>new CDbExpression('LAST_INSERT_ID(counter)');
 * ));
 *
 * Creates and executes an INSERT SQL with ON DUPLICATE KEY UPDATE MySQL statement
 * INSERT INTO `tbl_user` (`name`, `email`, `counter`) VALUES ('Tester', 'tester@example.com', 1)
 * ON DUPLICATE KEY UPDATE `name`='Tester', `email`='tester@example.com', `counter`=LAST_INSERT_ID(counter);
 */
class InsertUpdateCommandBehavior extends CBehavior
{
    /**
     * Creates and executes an INSERT with ON DUPLICATE KEY UPDATE SQL statement.
     * The method will properly escape the column names, and bind the values to be inserted or updated
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column data (name=>value) to be inserted into the table.
     * @param array $update the column data (name=>value) to be updated in the table.
     * @return integer number of rows affected by the execution.
     */
    public function insertUpdate($table, $columns, $update)
    {
        $params = array();
        $names = array();
        $placeholders = array();
        $lines = array();

        foreach($columns as $name => $value)
        {
            $names[] = $this->owner->connection->quoteColumnName($name);

            if($value instanceof CDbExpression)
            {
                $placeholders[] = $value->expression;

                foreach($value->params as $n => $v)
                {
                    $params[$n] = $v;
                }
            }
            else
            {
                $placeholders[] = ':' . $name;
                $params[':' . $name] = $value;
            }
        }

        foreach($update as $name => $value)
        {
            if($value instanceof CDbExpression)
            {
                $lines[] = $this->owner->connection->quoteColumnName($name) . '=' . $value->expression;

                foreach($value->params as $n => $v)
                {
                    $params[$n] = $v;
                }
            }
            else
            {
                $lines[] = $this->owner->connection->quoteColumnName($name) . '=:' . $name;
                $params[':' . $name] = $value;
            }
        }

        $sql='INSERT INTO ' . $this->owner->connection->quoteTableName($table)
            . ' (' . implode(', ', $names) . ') VALUES ('
            . implode(', ', $placeholders) . ')'
            . ' ON DUPLICATE KEY UPDATE '.implode(', ', $lines);

        return $this->owner->setText($sql)->execute($params);
    }
}
