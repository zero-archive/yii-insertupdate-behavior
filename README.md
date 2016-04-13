# Yii InsertUpdateCommandBehavior

[![Latest Stable Version](https://poser.pugx.org/dotzero/yii-insertupdate-behavior/version)](https://packagist.org/packages/dotzero/yii-insertupdate-behavior)
[![License](https://poser.pugx.org/dotzero/yii-insertupdate-behavior/license)](https://packagist.org/packages/dotzero/yii-insertupdate-behavior)

The **InsertUpdateCommandBehavior** extension adds up some functionality to the default possibilites of yii´s **CDbCommand** implementation. Creates and executes an `INSERT` with `ON DUPLICATE KEY UPDATE` **MySQL** statement.

## Requirements:

Yii Framework 1.1.0 or later

## Installation:

Extract the release file under `'protected/components'`

## Basic usage:

    $command = Yii::app()->db->createCommand();
    $command->attachBehavior('InsertUpdateCommandBehavior', new InsertUpdateCommandBehavior);
    $command->insertUpdate('tbl_user', array(
        'name'=>'Tester',
        'email'=>'tester@example.com',
        'counter'=>'1'
    ), array(
        'name'=>'Tester',
        'email'=>'tester@example.com'
    ));

Creates and executes an `INSERT` with `ON DUPLICATE KEY UPDATE` **MySQL** statement

    INSERT INTO `tbl_user` (`name`, `email`, `counter`)
    VALUES ('Tester', 'tester@example.com', 1)
    ON DUPLICATE KEY UPDATE `name`='Tester', `email`='tester@example.com';

## Advanced usage:

    $command = Yii::app()->db->createCommand();
    $command->attachBehavior('InsertUpdateCommandBehavior', new InsertUpdateCommandBehavior);
    $command->insertUpdate('tbl_user', array(
        'name'=>'Tester',
        'email'=>'tester@example.com',
        'counter'=>'1'
    ), array(
        'name'=>'Tester',
        'email'=>'tester@example.com'
        'counter'=>new CDbExpression('LAST_INSERT_ID(counter)');
    ));

Creates and executes an `INSERT` with `ON DUPLICATE KEY UPDATE` **MySQL** statement

    INSERT INTO `tbl_user` (`name`, `email`, `counter`)
    VALUES ('Tester', 'tester@example.com', 1)
    ON DUPLICATE KEY UPDATE `name`='Tester', `email`='tester@example.com', `counter`=LAST_INSERT_ID(counter);
