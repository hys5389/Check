# Check

## 简介:
<br>

<font face="微软雅黑" size=3>在写项目的时候总是需要用到表单验证，很喜欢laravel的验证类，<br><br>但是又不能单个类拿出来，所以结合自己的思路，写了一个自己喜欢的验证类；</font>
<br>
<br>
### 适用php版本：
<br>
<br>
<font face="微软雅黑" size=5>php5.3++</font>
<br>
<br>

## 验证的实现方法：

<font face="微软雅黑" size=3><br></font>

```
Check::values($values)->rules($rules)->remsg($remsg)->check();

```
<br>

参数举例：<br>

1. $values -> 数组 （需要验证的值） 

	
	```
	$values ＝ ［
		'name' => 'abcdefg',
		'sex' => 'man',
	];
	```
2.  $rules -> array（验证的规则）

	```
	$rules ＝ ［
		'name' => 'required|between:2,10|numeric',
		'sex' => 'required',
	];
	```
3. $remsg -> array（自定义验证后返回的错误提示）

	```
	$remsg ＝ ［
		'required' => '{$name} 不能为空',
		'between' => '{$name} 必须在 {$option1} 与 {$option2} 之间',
	];
	```



