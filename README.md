# Yii InsertUpdateCommandBehavior

[![Latest Stable Version](https://poser.pugx.org/dotzero/yii-insertupdate-behavior/version)](https://packagist.org/packages/dotzero/yii-insertupdate-behavior)
[![License](https://poser.pugx.org/dotzero/yii-insertupdate-behavior/license)](https://packagist.org/packages/dotzero/yii-insertupdate-behavior)

The **InsertUpdateCommandBehavior** extension adds up some functionality to the default possibilites of yii´s **CDbCommand** implementation. Creates and executes an `INSERT` with `ON DUPLICATE KEY UPDATE` **MySQL** statement.

## Requirements:

- [Yii Framework](https://github.com/yiisoft/yii) 1.1.14 or above
- [Composer](http://getcomposer.org/doc/)

## Install

### Via composer:

```bash
$ composer require dotzero/yii-insertupdate-behavior
```

### Add vendor path and import to your configuration file:

```php
    'aliases' => array(
        ...
        'vendor' => realpath(__DIR__ . '/../../vendor'),
    ),
    'import' => array(
        ...
        'vendor.dotzero.yii-insertupdate-behavior.*',
    ),
```

## Basic usage:

```php
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
```

Creates and executes an `INSERT` with `ON DUPLICATE KEY UPDATE` **MySQL** statement

```sql
INSERT INTO `tbl_user` (`name`, `email`, `counter`)
VALUES ('Tester', 'tester@example.com', 1)
ON DUPLICATE KEY UPDATE `name`='Tester', `email`='tester@example.com';
```

## Advanced usage:

```php
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
```

Creates and executes an `INSERT` with `ON DUPLICATE KEY UPDATE` **MySQL** statement

```sql
INSERT INTO `tbl_user` (`name`, `email`, `counter`)
VALUES ('Tester', 'tester@example.com', 1)
ON DUPLICATE KEY UPDATE `name`='Tester', `email`='tester@example.com', `counter`=LAST_INSERT_ID(counter);
```

## License

Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
