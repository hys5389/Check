<?php
/**
 * Created by PhpStorm.
 * User: hys
 * Date: 17/8/2
 * Time: 下午9:17
 */
namespace Check;
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
    protected static $nstance;

    /*------------------------------------------------------ */
//-- 开发者可以使用的方法
    /**
     * 1.values
     * 2.rules
     * 3.msgs
     * 4.check
     */
//-- Check::values($values)->rules([])->msgs([])->check();
    /*------------------------------------------------------ */
    protected function __construct($values)
    {
        if(!is_array($values) || empty($values))
        {
            $this->errors['system']['status'] ='error';
            $this->errors['system']['msg'] ='请填写正确形式的数据';
        }
        else
        {
            $this->values = $values;
        }
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
        static::getInstance($values);

        static::$instance->values=$values;
        return static::$instance;
    }

    //单例
    public static function getInstance($values=array())
    {
        if (!static::$instance) {
            static::$instance = new static($values);
        }
        else
        {
            static::$instance->init();
        }
        return static::$instance;
    }

    public function data($values)
    {
        $this->init();
        $this->values=$values;
        return $this;
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
        if(!is_array($msgs) || empty($msgs))
        {
            $this->errors['system']['status'] ='error';
            $this->errors['system']['msg'] ='请填写正确形式的错误消息';
            return $this;
        }
        $this->msgs = $msgs;
        return $this;
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
                    $one_rule=trim($one_rule);
                    //有参数
                    if(!strpos($one_rule,':') === false)
                    {
                        $method = explode(':',$one_rule);

                        $method[0] = trim($method[0]);
                        $method[1] = trim($method[1]);
                        if(method_exists ($this,$method[0]))
                        {
                            if(!strpos($method[1],',') === false)
                            {
                                $option =array();
                                $options = explode(',',$method[1]);
                                foreach ($options as $item) {
                                    $option[] = trim($item);
                                }
                            }
                            else
                            {
                                $option =$method[1];
                            }
                            $error=$this->{$method[0]}($v,$option);
                            //处理错误
                            if(!empty($error))
                            {
                                if($this->msgs[$k][$one_rule])
                                {
                                    $error=$this->msgs[$k][$one_rule];
                                }
                                $error = str_replace('{$name}',$k,$error);
                                foreach($option as $key=>$vo)
                                {
                                    $error = str_replace('{$option'.($key+1).'}',$vo,$error);
                                }
                                $this->errors[$k][] = $error;
                            }
                        }
                    }
                    //无参数
                    else
                    {

                        if(method_exists ($this,$one_rule))
                        {
                            $error=$this->{$one_rule}(trim($v));
                            //处理错误
                            if(!empty($error))
                            {
                                if($this->msgs[$k][$one_rule])
                                {
                                    $error=$this->msgs[$k][$one_rule];
                                }
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
    public function errors($re=''){

        if($re=='remove')
        {
            $this->errors=array();
            return true;
        }
        return $this->errors;
    }
    public function jsonErrors(){

        return json_encode($this->errors);
    }
    /*------------------------------------------------------ */
//-- 下面是内部操作的方法
    /*------------------------------------------------------ */
    private function init(){
        $this->values=array();
        $this->rules=array();
        $this->msgs=array();
        $this->errors=array();
    }
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

    protected function tel($value)
    {
        $pattern = '/^(\(\d{3,4}\)|\d{3,4}-)?\d{7,8}$/';
        if (!preg_match($pattern, $value))
        {
            return '{$name} 不是正确的电话号码';
        }
        else
        {
            return '';
        }
    }

    protected function url($value)
    {

        if(!preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\"])*$/", $value))
        {
            return '{$name} 不是url格式';
        }
        else
        {
            return '';
        }

    }
    protected function mobile($value)
    {

        if(!preg_match("/^1[0-9]{10}$/", $value))
        {
            return '{$name} 不是mobile格式';
        }
        else
        {
            return '';
        }

    }

    protected function fax($value)
    {

        if(!preg_match("/^(\d{3,4}-)?\d{7,8}$/", $value))
        {
            return '{$name} 不是mobile格式';
        }
        else
        {
            return '';
        }

    }
}