<?php
/**
 * Created by PhpStorm.
 * User: hys
 * Date: 17/8/2
 * Time: 下午9:17
 */
//验证类
class Check
{
    //数据
    protected $values=array();
    //规则
    protected $rules=array();
    //报错代替
    protected $msgs=array();
    //错误信息
    protected $errors=array();
    //单例对象
    protected static $me;

/*------------------------------------------------------ */
//-- 开发者可以使用的方法
/**
 * 1.values
 * 2.rules
 * 3.msgs
 * 4.check
 */
//-- Check::values($values)->rules([])->remsg([])->check();
/*------------------------------------------------------ */
    protected function __construct($values)
    {
        $this->values = $values;
    }
    protected function __clone()
    {
        trigger_error('Clone is not allowed !',E_USER_WARNING);
    }
    //初始方法,获取需要验证的数据,以数组形式,
    //错误:return $this->errors
    //正确:返回当前对象
    public static function values($values)
    {
        if(!is_array($values) || empty($values))
        {
            static::$me->errors['system']['status'] ='error';
            static::$me->errors['system']['msg'] ='请填写正确形式的数据';
            return static::$me;
        }
        if(!(static::$me instanceof static))
        {

            static::$me = new static($values);
        }
        else
        {
            static::$me->values = $values;
        }
        return static::$me;
    }

    //验证规则,以数组形式
    public function rules($rules)
    {
        if(!is_array($rules) || empty($rules))
        {
            $this->errors['system']['status'] ='error';
            $this->errors['system']['msg'] ='请填写正确形式的规则';
            return $this;
        }
        $this->rules = $rules;
        return $this;
    }

    //配置错误信息,以数组形式
    public function msgs($msgs=array())
    {

    }

    //验证
    public function check()
    {
        $rules = $this->rules;
        foreach($this->values as $k => $v)
        {
            if(isset($rules[$k]))
            {
                $one_rules = explode('|',$rules[$k]);

                foreach($one_rules as $one_rule)
                {
                    //有参数
                    if(!strpos(trim($one_rule),':') === false)
                    {
                        $method = explode(':',$one_rule);
                        if(method_exists ($this,trim($method[0])))
                        {
                            if(!strpos(trim($method[1]),',') === false)
                            {
                                $option =array();
                                $options = explode(',',$method[1]);
                                foreach ($options as $item) {
                                    $option[] = trim($item);
                                }
                            }
                            else
                            {
                                $option =trim($method[1]);
                            }
                            $error=$this->{trim($method[0])}($v,$option);
                            if(!empty($error))
                            {
                                $error = str_replace('{$name}',$k,$error);
                                foreach($option as $key=>$v)
                                {
                                    $error = str_replace('{$option'.($key+1).'}',$v,$error);
                                }
                                $this->errors[$k][] = $error;
                            }
                        }
                    }
                    //无参数
                    else
                    {
                        if(method_exists ($this,trim($one_rule)))
                        {
                            $error=$this->{trim($one_rule)}(trim($v));
                            if(!empty($error))
                            {
                                $error = str_replace('{$name}',$k,$error);
                                $this->errors[$k][] = $error;
                            }
                        }

                    }
                }
            }
        }
        return $this;
    }
    public function errors(){

        return $this->errors;
    }
    public function jsonErrors(){

        return json_encode($this->errors);
    }
/*------------------------------------------------------ */
//-- 下面是内部操作的方法
/*------------------------------------------------------ */

/*------------------------------------------------------ */
//-- 下面是验证的方法
/*------------------------------------------------------ */

    /**
     * ------------------------------------------
     * 函数名称：required
     * 简要描述：检查数据是否为必填项
     * ------------------------------------------
     */
    protected function required($value)
    {
        if(empty($value))
        {
            return '{$name} 不能为空!';
        }
        else
        {
            return '';
        }
    }

    /**
     * ------------------------------------------
     * 函数名称：numeric
     * 简要描述：检查输入的是否为数字
     * 输入：string
     * ------------------------------------------
     */
    protected function numeric($value)
    {
        if(!preg_match("/^[0-9]+$/", $value))
        {
            return '{$name} 必须是数字!';
        }
        else
        {
            return '';
        }
    }

    protected function email($value)
    {
        $pattern = "/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)$/";

        if(!preg_match($pattern, $value))
        {
            return '{$name} 不是email格式';
        }
        else
        {
            return '';
        }

    }

    /**
     * ------------------------------------------
     * 函数名称：between
     * 简要描述：检查输入的长度占位 minLength 与 maxLength 之间
     * 输入：$value string;$option array
     * ------------------------------------------
     */
    protected function between($value,$option)
    {
        $minLength = $option[0];
        $maxLength = $option[1];

        $length = (strlen($value) + mb_strlen($value,'UTF8')) / 2;

        if($length < $minLength || $length > $maxLength)
        {
            return '{$name} 必须在 {$option1} ~ {$option2} 之间';
        }
        else
        {
            return '';
        }
    }
}