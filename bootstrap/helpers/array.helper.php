<?php
/**
 * 数组类函数集合
 */

/**
 * 从数组中取出指定键的值，并将原数组中删除
 * 不存在的键值不提取
 *
 * @param array $keys
 * @param array $data
 * @param array $new_data
 * @return array
 */
function take_by_keys($keys = array(), &$data, &$new_data)
{
    if (empty($keys)) return array();
    if (!is_array($keys)) $keys = array(
        $keys
    );

    $new_data = array();
    foreach ($keys as $index)
    {
        if (!isset($data[$index])) continue;

        $new_data[$index] = $data[$index];
        unset($data[$index]);
    }
}

/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @param mix $item_list 列表数组或字符串
 * @param string $field_name 字段名称
 *
 * @return void
 */
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list as $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}

/**
 * 将空替除
 *
 * @param str $str
 * @return bool
 */
function filter_empty($str)
{
    // if (empty($str) && $str !== '0')
    if (empty($str) && $str === '')
    {
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * 合并数组，但不重建索引
 *
 * @param array $arr1
 * @param array $arr2
 * @return array
 */
function array_merge_noreset($arr1, $arr2)
{
    if (empty($arr2)) return $arr1;

    foreach ($arr2 as $key=>$val)
    {
        $arr1[$key] = $val;
    }
    return $arr1;
}

/**
 * 通过递归将数据进行分级
 *
 * @param int $pid
 * @param array $arr
 * @param int $level
 * @param int $order
 * @return array
 */
function channel_options($pid, $arr, $level = 1, $order = 1)
{
    $level = $level ? $level : 1;
    $options = array();
    foreach ($arr as $key=>$var)
    {
        if ($key == $pid)
        {
            foreach ($var as $k=>$v)
            {
                $options[$k] = $v;
                // 层深
                $options[$k]['option_level'] = $level;

                // 当前层顺序
                $options[$k]['option_order'] = $order;

                // 是否有子类
                $options[$k]['option_is_leaf'] = 1;

                $children = channel_options($k, $arr, $level + 1);

                if (count($children) > 0)
                {
                    $options[$k]['option_is_leaf'] = 0;
                    $options = $options + $children;
                }

                $order++;
            }
        }
    }

    return $options;
}

/**
 * 从二维数组中取出两列，组织为一维数组(返回遇到的第一个key)
 *
 * @param array $data
 * @param string $k
 * @param string $v
 * @return array
 */
function array_get_field($data, $k_name = NULL, $v_name)
{
    if (empty($data)) return array();

    $newData = array();
    foreach ($data as $key=>$val)
    {
        $key = $k_name == NULL ? $key : $val[$k_name];

        if(!isset($newData[$key])) $newData[$key] = $val[$v_name];
    }

    return $newData;
}

/**
 * 比较二维数组中的某列，值相等eq或不等neq
 * 返回符合条件的数据
 * @param array $data
 * @param string $field
 * @param string $value
 * @param string $return eq|neq
 * @return array
 */
function array_ddim_filter($data, $field, $value, $return = 'eq')
{
    if (empty($data)) return array();

    $newData = array();
    foreach ($data as $key=>$val)
    {
        if($return == 'eq')
        {
            if($val[$field] == $value)
            {
                $newData[$key] = $val;
            }
        }
        else
        {
            if($val[$field] != $value)
            {
                $newData[$key] = $val;
            }
        }
    }

    return $newData;
}

/**
 * 指定字段做为数组中的key
 *
 * @param array $data
 * @param string $field
 * @return array
 */
function key_for_field($data, $field)
{
    if (empty($data)) return $data;

    $new_data = array();
    foreach ($data as $val)
    {
        if (!isset($val[$field])) return $data;
        $new_data[$val[$field]] = $val;
    }
    return $new_data;
}

/**
 * 通过orders中的key，为data进行重排序
 *
 * @param array $orders
 * @param array $data
 * @return array
 */
function reset_order_for_key($orders, $data)
{
    // print_arr($orders);
    $new_data = array();
    foreach ($orders as $key=>$val)
    {
        if (isset($data[$key])) $new_data[$key] = $data[$key];
    }

    return $new_data;
}

/**
 * 跟据条件格式化字符串返回
 *
 * @param array $condition
 * @param array $format_str
 * @param string $first_connector
 * @return string
 */
function get_condition_sql($condition, $format_str = array(), $first_connector = "and")
{
    /*
     * print_arr($condition);
     * print_arr('AAA');
     */
    if (!$condition || !is_array($condition)) return '';

    $new_con = array();
    foreach ($condition as $key=>$val)
    {
        if (!isset($format_str[$key]) || $val === '' || empty($format_str[$key])) continue;

        $new_con[] = str_replace('?', $val, $format_str[$key]);
    }

    if (empty($new_con)) return '';

    $new_str = implode(' and ', $new_con);

    $first_connector = $first_connector == "and" ? " and " : " where ";
    return $first_connector . $new_str;
}

/**
 * 使用新的配置信息，覆盖默认配置
 *
 * @param arrary $default
 * @param arrary $params
 * @return arrary
 */
function overwrite_config($default, $params)
{
    if (count($params) > 0)
    {
        foreach ($params as $key=>$val)
        {
            if (isset($default[$key]))
            {
                $default[$key] = $val;
            }
        }
    }

    return $default;
}

/**
 * 取得key后的下一个值
 *
 * @param array $data
 * @param string $now_key
 * @return string
 */
function array_get_other($data, $now_key)
{
    unset($data[$now_key]);

    return current($data);
}

/**
 * 将字符串转换为数组
 *
 * @param string $data
 * @return array
 */
function string2array($data)
{
    return $data ? (is_array($data) ? $data : unserialize(stripslashes($data))) : array();
}

/**
 * 将数组转换为字符串
 *
 * @param array $data
 * @return string
 */
function array2string($data)
{
    return $data ? addslashes(serialize($data)) : '';
}

/**
 * 为二维数组增加标签，如当前序号、总数量、首个等
 *
 * @param array $data
 * @return array
 */
function array_add_label($data)
{
    if (!$data || !is_array($data)) return;

    $count = count($data);

    $i = 1;
    foreach ($data as $key=>$val)
    {
        if (!is_array($val)) continue;

        $val['_current'] = $i;

        if ($i == 1)
        {
            $val['_first'] = true;
        }

        if ($i == $count)
        {
            $val['_last'] = true;
        }

        $val['_total'] = $count;

        $data[$key] = $val;
        $i++;
    }
    return $data;
}

/**
 * 过滤特殊字符
 *
 * @param array $arr
 */
function array_addslashes(& $arr)
{
    foreach ($arr as $k=>$v)
    {
        if (!is_array($v))
        {
            $arr[$k] = addslashes($v);
        }
        else
        {
            array_addslashes($arr[$k]);
        }
    }
}

/**
 * 递归编码字符串
 *
 * @param array $str
 * @return string
 */
function urlencode_recursive($str)
{
    if (is_array($str))
    {
        foreach ($str as $key=>$value)
        {
            $str[urlencode($key)] = urlencode_recursive($value);
        }
    }
    else
    {
        $str = urlencode($str);
    }

    return $str;
}

/**
 * 递归返回数组中所有的值
 *
 * @param array $array
 * @return array
 */
function array_values_recursive($array)
{
    $temp = array();
    foreach ($array as $key=>$value)
    {
        if (is_numeric($key))
        {
            $temp[] = is_array($value) ? array_values_recursive($value) : $value;
        }
        else
        {
            $temp[$key] = is_array($value) ? array_values_recursive($value) : $value;
        }
    }
    return $temp;
}

/**
 * 重新生成数组key值
 *
 * @param array $arr
 * @param int $i
 */
function array_key_start($arr, $i = 0)
{
    if (!$arr || !is_array($arr)) return false;

    if ($i == 0) return array_values($arr);

    $new_arr = array();
    foreach ($arr as $val)
    {
        $new_arr[$i] = $val;
        $i++;
    }

    return $new_arr;
}

/**
 * 以引用的方式对utf8汉字进行排行中文排序
 *
 * @param array $array
 * @return boolean
 */
function utf8_array_asort(&$array)
{
    if (!isset($array) || !is_array($array))
    {
        return false;
    }
    foreach ($array as $k=>$v)
    {
        $array[$k] = iconv('UTF-8', 'GBK//IGNORE', $v);
    }
    asort($array);
    foreach ($array as $k=>$v)
    {
        $array[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
    }
    return true;
}

/**
 * 返回key相交的值
 *
 * @param array $arr_val
 * @param array $dict
 * @param string $separator
 * @return string
 */
function array_intersect_key_value($arr_val, $dict, $separator = ',')
{
    return implode($separator, array_intersect_key($dict, array_flip($arr_val)));
}

/**
 * 为数组填补(日)空值
 *
 * @param array $data
 * @param date $end_date
 * @param date $begin_date
 * @param number $dv
 */
function fill_date_day($data, $end_date = NULL, $begin_date = NULL, $dv = 0)
{
    if (!$data && $end_date === NULL && $begin_date === NULL)
    {
        return array();
    }

    $begin_date = $begin_date === NULL ? min_key($data) : $begin_date;
    $end_date = $end_date === NULL ? max_key($data) : $end_date;

    $new_data = array();
    while ( $begin_date <= $end_date )
    {
        $new_data[$begin_date] = isset($data[$begin_date]) ? $data[$begin_date] : $dv;

        $begin_date = strtodate("+1 day", $begin_date);
    }

    return $new_data;
}

/**
 * 取数组中最小的key
 *
 * @param array $data
 */
function min_key($data)
{
    return min(array_keys($data));
}

/**
 * 取数组中最大的key
 *
 * @param array $data
 * @return mixed
 */
function max_key($data)
{
    return max(array_keys($data));
}

/**
 * 去除数组指定的keys
 * @param array $arr
 * @param array $keys
 * @return array
 */
function array_remove($arr, $keys)
{
    if (!is_array($keys)) $keys = array($keys);

    foreach ($keys as $key)
    {
        unset($arr[$key]);
    }

    return $arr;
}

/**
 * 移除指定的字符
 *
 * @param string $item
 * @param string $key
 * @param string $char
 */
function remove_char(&$item, $key, $char)
{
    $item = str_replace($char, '', $item);
}


/**
 * 去重后比较交集信息
 * @param array $arr
 * @param array $dict
 * @return array
 */
function array_unique_dict($arr, $dict = array())
{
    if(is_array($arr))
    {
        $arr = implode(',', $arr);
    }

    // p($string, 0 , '$string');

    $arr = explode(',', $arr);
    // p($arr, 0 , '$$arr');
    $uarr = array_unique($arr);

    $uarr = array_combine($uarr, $uarr);

    if (!$dict) return $uarr;

    return array_intersect_key($dict, $uarr);
}

/**
 * 在数组中搜索给定的值，返回全部相应的键名
 * @param string $needle
 * @param array $haystack
 * @return array
 */
function array_search_all($needle, $haystack)
{
    foreach ($haystack as $k=>$v)
    {
        if ($haystack[$k] == $needle)
        {
            $array[] = $k;
        }
    }
    return ($array);
}

/**
 * 数组不重复排列集合(笛卡尔积)
 * @param array $arrs
 * @param int $_current_index
 * @return array
 */
function getArrSet($arrs,$_current_index=-1)
{
    //总数组
    static $_total_arr;
    //总数组下标计数
    static $_total_arr_index;
    //输入的数组长度
    static $_total_count;
    //临时拼凑数组
    static $_temp_arr;

    //进入输入数组的第一层，清空静态数组，并初始化输入数组长度
    if($_current_index<0)
    {
        $_total_arr=array();
        $_total_arr_index=0;
        $_temp_arr=array();
        $_total_count=count($arrs)-1;
        getArrSet($arrs,0);
    }
    else
    {
        //循环第$_current_index层数组
        foreach($arrs[$_current_index] as $v)
        {
            //如果当前的循环的数组少于输入数组长度
            if($_current_index<$_total_count)
            {
                //将当前数组循环出的值放入临时数组
                $_temp_arr[$_current_index]=$v;
                //继续循环下一个数组
                getArrSet($arrs,$_current_index+1);

            }
            //如果当前的循环的数组等于输入数组长度(这个数组就是最后的数组)
            else if($_current_index==$_total_count)
            {
                //将当前数组循环出的值放入临时数组
                $_temp_arr[$_current_index]=$v;
                //将临时数组加入总数组
                $_total_arr[$_total_arr_index]=$_temp_arr;
                //总数组下标计数+1
                $_total_arr_index++;
            }

        }
    }

    return $_total_arr;
}

/**
 * 返回数组的笛卡尔积
 * @param array $data
 * @return array
 */
function combineDika($data) {
    $result = array();
    foreach (array_shift($data) as $k=>$item) {
        $result[] = array($k=>$item);
    }


    foreach ($data as $k => $v) {
        $result2 = array();
        foreach ($result as $k1=>$item1) {
            foreach ($v as $k2=>$item2) {
                $temp     = $item1;
                $temp[$k2]   = $item2;
                $result2[] = $temp;
            }
        }
        $result = $result2;
    }
    return $result;
}


/**
 * 寻找数组中指定百分位值
 * @param array $arr
 * @param int $percent
 * @return number
 */
function find_percent($arr, $percent)
{
    if(empty($arr)) return 0;

    sort($arr);
    //print_arr($arr);
    $counts = count($arr);
    $single = 100 / $counts;

    $pos = round($percent/$single);


    //p($pos, 0, $percent);

    if($pos < 0) $pos = 0;
    elseif ($pos >= $counts) $pos = $counts-1;

    //p($pos, 0, $percent);

    return $arr[$pos];
}



/**
 * 依次检查是否为true，否则输出后位参数
 * @param string $var1
 * @param string $var2
 * @param string $var3
 */
function daout($var1, $var2 = array(), $var3 = array())
{
    if($var1 || count($var1) > 0)
    {
        return $var1;
    }
    elseif($var2 || count($var2) > 0)
    {
        return $var2;
    }
    else
    {
        return $var3;
    }
}

/**
 * 使用比较数组的值对array key进行排序
 * 不存在的放在最后
 * @param array $array
 * @param array $cmp_array
 * @return array
 */
function ksort_by_array($array, $cmp_array)
{
    if(!$array || !$cmp_array) return $array;

    $new_array = array();

    foreach ($cmp_array as $key)
    {
        if(isset($array[$key]))
        {
            $new_array[$key] = $array[$key];
            unset($array[$key]);
        }
    }

    if($array)
    {
        $new_array = array_merge_noreset($new_array, $array);
    }

    return $new_array;
}


function array_multisort_assoc($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
    if(is_array($arrays)){
        foreach ($arrays as $array){
            if(is_array($array)){
                $key_arrays[] = $array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
    return $arrays;
}


function array_group($data, $field)
{
    if (empty($data)) return $data;

    $new_data = array();
    foreach ($data as $key => $val)
    {
        if (!isset($val[$field])) return $data;
        $new_data[$val[$field]][$key] = $val;
    }
    return $new_data;
}

function array_group_merge($data)
{
    if (empty($data)) return $data;

    $new_data = array();
    foreach ($data as $key => $val)
    {
        if(!$val) continue;

        foreach ($val as $k => $v)
        {
            $new_data[$k] = $v;
        }
    }
    return $new_data;
}