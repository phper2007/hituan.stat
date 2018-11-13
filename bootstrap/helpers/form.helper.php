<?php
/**
 * 表单类函数集合
 * 
 * @author    Liu Bo <liubo@zhongyan.org>
 */

/**
 * 多选框
 *
 * @param string $name
 * @param array $options
 * @param array $checked
 * @param string $extra
 * @param string $class
 * @return string
 */
function form_checkboxes($name = '', $options = array(), $checked = array(), $extra = '', $class = 'checkbox')
{
    if (!is_array($checked))
    {
        $checked = array(
            $checked
        );
    }

    if ($extra != '') $extra = ' ' . $extra;

    $form = '';

    foreach ($options as $key=>$val)
    {
        $key = (string) $key;

        $sel = (in_array($key, $checked)) ? ' checked="checked"' : '';

        // class=\"{$class}\"
        $form .= "<label>\n";
        $form .= "<input type=\"checkbox\" name=\"{$name}[]\" value=\"{$key}\"{$sel}{$extra}>{$val}\n";
        $form .= "</label>\n";
    }

    return $form;
}

/**
 * 单选框
 *
 * @param string $name
 * @param array $options
 * @param string $checked
 * @param string $extra
 * @param string $class
 * @return string
 */
function form_radio($name = '', $options = array(), $checked = FALSE, $extra = '', $class = 'radio')
{
    if ($extra != '') $extra = ' ' . $extra;

    $form = '';

    foreach ($options as $key=>$val)
    {
        $key = (string) $key;

        $sel = $key == $checked ? ' checked="checked"' : '';

        //-inline
        $form .= "<label>\n";
        $form .= "<input type=\"radio\" name=\"{$name}\" value=\"{$key}\"{$sel}{$extra}>{$val}\n";
        $form .= "</label>\n";
    }

    return $form;
}

/**
 * 下拉菜单或仅有option
 *
 * @param array $options
 * @param string $selected
 * @param string $name
 * @param string $default_str
 * @param string $extra
 * @return string
 */
function form_option($options = array(), $selected = FALSE, $name = NULL, $default_str = '', $extra = '')
{
    if ($default_str) $options = array_merge_noreset(array(
        '' => $default_str
    ), $options);

    $option_str = '';
    if (!empty($options))
    {
        foreach ($options as $k=>$v)
        {
            $is_selected = $k == $selected ? ' selected="selected"' : '';

            $option_str .= "<option value=\"{$k}\"{$is_selected}>{$v}</option>";
        }
    }

    if ($name)
    {
        $option_str =  "<select name=\"{$name}\"{$extra}>
        {$option_str}
        </select>";
    }

    return $option_str;

}