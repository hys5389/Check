# Check

##简介:
<br>

<font face="微软雅黑" size=3>在写项目的时候总是需要用到表单验证，很喜欢laravel的验证类，<br><br>但是又不能单个类拿出来，所以结合自己的思路，写了一个自己喜欢的验证类；</font>
<br>
<br>
##验证的是实现方法：

<font face="微软雅黑" size=3><br></font>

``` 
Check::values($values)->rules([])->remsg([])->check();

```
