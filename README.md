# validate

## 多语言错误信息提示


validate验证器提供了默认错误信息规则，详细如下：

```php
private $defaultErrorMsg = [
    'activeUrl' => ':fieldName必须是可访问的网址',
        'alpha' => ':fieldName只能是字母',
        'alphaNum' => ':fieldName只能是字母和数字',
        'alphaDash' => ':fieldName只能是字母数字下划线和破折号',
        'between' => ':fieldName只能在 :arg0 - :arg1 之间',
        'bool' => ':fieldName只能是布尔值',
        'decimal' => ':fieldName只能是小数',
        'dateBefore' => ':fieldName必须在日期 :arg0 之前',
        'dateAfter' => ':fieldName必须在日期 :arg0 之后',
        'equal' => ':fieldName必须等于:arg0',
        'different' => ':fieldName必须不等于:arg0',
        'equalWithColumn' => ':fieldName必须等于:arg0的值',
        'differentWithColumn' => ':fieldName必须不等于:arg0的值',
        'float' => ':fieldName只能是浮点数',
        'func' => ':fieldName自定义验证失败',
        'inArray' => ':fieldName必须在 :arg0 范围内',
        'integer' => ':fieldName只能是整数',
        'isIp' => ':fieldName不是有效的IP地址',
        'notEmpty' => ':fieldName不能为空',
        'numeric' => ':fieldName只能是数字类型',
        'notInArray' => ':fieldName不能在 :arg0 范围内',
        'length' => ':fieldName的长度必须是:arg0',
        'lengthMax' => ':fieldName长度不能超过:arg0',
        'lengthMin' => ':fieldName长度不能小于:arg0',
        'betweenLen' => ':fieldName的长度只能在 :arg0 - :arg1 之间',
        'money' => ':fieldName必须是合法的金额',
        'max' => ':fieldName的值不能大于:arg0',
        'min' => ':fieldName的值不能小于:arg0',
        'regex' => ':fieldName不符合指定规则',
        'allDigital' => ':fieldName只能由数字构成',
        'required' => ':fieldName必须填写',
        'timestamp' => ':fieldName必须是一个有效的时间戳',
        'timestampBeforeDate' => ':fieldName必须在:arg0之前',
        'timestampAfterDate' => ':fieldName必须在:arg0之后',
        'timestampBefore' => ':fieldName必须在:arg0之前',
        'timestampAfter' => ':fieldName必须在:arg0之后',
        'url' => ':fieldName必须是合法的网址',
    ]
```

默认错误例子

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-11-19
 * Time: 上午10:47
 */

require_once "./vendor/autoload.php";

$data = ['name' => 'blank', 'age' => 25];   // 验证数据
$validate = new \EasySwoole\Validate\Validate();
$validate->addColumn('name')->required();   // 给字段加上验证规则
$validate->addColumn('age')->required()->max(18);
$bool = $validate->validate($data); // 验证结果
if ($bool) {
    var_dump("验证通过");
} else {
    var_dump($validate->getError()->__toString());
}
/*
 * 输出结果： string(23) "age的值不能大于18"
 */
```


## 多语言错误信息提示

多语言错误信息提示文件例子:/Lang/en-us/validate.php
```php
<?php
 return [
    'required'   => ':fieldName max required',
];
```

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-11-19
 * Time: 上午10:47
 */

require_once "./vendor/autoload.php";

$data = ['name' => 'blank', 'age' => 25];   // 验证数据
$validate = new \EasySwoole\Validate\Validate();
$validate->addColumn('name')->required();   // 给字段加上验证规则
$validate->addColumn('age')->required()->max(18);
$langErrorMsgFile = '/Lang/en-us/validate.php';// 指定错误信息规则语言文件
$bool = $validate->validate($data,$langErrorMsgFile); // 验证结果
if ($bool) {
    var_dump("验证通过");
} else {
    var_dump($validate->getError()->__toString());
}
/*
 * 输出结果： string(23) "age的值不能大于18"
 */
```


## 自定义错误信息提示

自定义错误例子

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-11-19
 * Time: 上午10:47
 */

require_once "./vendor/autoload.php";

$data = ['name' => 'blank', 'age' => 25];   // 验证数据
$validate = new \EasySwoole\Validate\Validate();
$validate->addColumn('name')->required('名字不为空');   // 给字段加上验证规则
$validate->addColumn('age')->required('年龄不为空')->func(function($params, $key) {
    return $params instanceof \EasySwoole\Spl\SplArray && $key == 'age' && $params[$key] == 18;
}, '只允许18岁的进入');
$bool = $validate->validate($data); // 验证结果
if ($bool) {
    var_dump("验证通过");
} else {
    var_dump($validate->getError()->__toString());
}
/*
 * 输出结果： string(23) "只允许18岁的进入"
 */
```
