<?php
namespace app\components;

class Jeen
{
    public static function echoln($obj)
    {
        if(\Yii::$app->getRequest()->getIsConsoleRequest()) {
            print_r($obj); echo PHP_EOL;
        } else {
            \yii\helpers\VarDumper::dump($obj,10,true);
            echo '<br>' . PHP_EOL;
        }
    }

    public static function getObjClassMethods($jobj,$scope = '',$self = false)
    {
        if(!is_object($jobj)) return [];
        $jclass = get_class($jobj);
        $jref = new \ReflectionClass($jclass);
        $jmethods = $jref->getMethods();
        $methods = [];
        foreach ($jmethods as $key => $value) {
            $params = '';
            $modname = \Reflection::getModifierNames($value->getModifiers());
            $modname = $modname[0];
            $claname = $value->getDeclaringClass()->getName();
            $t = //$key . '|' .
                $modname . ' ' .
                $claname . '->' .
                $value->getName() . '(';
            foreach ($value->getParameters() as $jparam) {
                $params .= $jparam;
            }
            for ($i = 1; $i < 10; $i++)
                $params = str_replace(']Parameter #' . $i . ' [', ',', $params);
            $params = str_replace('Parameter #0 [', '', $params);
            $params = str_replace(']', '', $params);
            $params = str_replace('<required>', '', $params);
            $params = str_replace('<optional>', '', $params);
            $params = str_replace(' ', '', $params);
            $t .= $params;
            $t .= ')';
            if($scope || $self) {
                if($self && $jclass != $claname) continue;
                if($scope && $modname != $scope) continue;
                $methods[] = $t;
            } else {
                $methods[] = $t;
            }
        }
        return $methods;
    }

    public static function show($jobj)
    { //打印对象调试信息
        echo '<meta charset="utf-8"/>';
        $jtype = gettype($jobj);
        echo '<br><div style="background:#eee;padding:10px;"><font color="red">Object Debug Informations By Jeen :</font>' . $jtype . ': -- Information -------------<br>';
        if ($jtype == 'array') {
            self::lsArray($jobj);
        } else if ($jtype == 'object') {
            $jclass = get_class($jobj);
            $jref = new \ReflectionClass($jclass);
            $jvars = $jref->getProperties();
            $jmethods = $jref->getMethods();
            echo 'Class : ' . $jclass . ' | has: ' .
                count($jvars) . ' vars  and  ' .
                count($jmethods) . ' methods .<br>You can see it in File:'
                . $jref->getFileName() . '<br>';
            $property_str = '<br><b>Properties:</b><hr>';
            foreach ($jvars as $property) {
                $modname = \Reflection::getModifierNames($property->getModifiers());
                $modname = $modname[0];
                $property_str .= $modname . ' <b>' . $property->getName() . '</b> <- '
                    . $property->getDeclaringClass()->getName() . '  <br>';
            }
            echo $property_str;

            echo '<br><b>Functions</b>:<hr>';
            foreach ($jmethods as $key => $value) {
                $params = '';
                $modname = \Reflection::getModifierNames($value->getModifiers());
                $modname = $modname[0];
                echo $key . '|<b>' . $modname . '</b> '
                    . $value->getDeclaringClass()->getName()
                    . '-><b>' . $value->getName() . '</b>(';
                foreach ($value->getParameters() as $jparam) {
                    $params .= $jparam;
                }
                for ($i = 1; $i < 10; $i++)
                    $params = str_replace(']Parameter #' . $i . ' [', ',', $params);
                $params = str_replace('Parameter #0 [', '', $params);
                $params = str_replace(']', '', $params);
                echo $params;
                echo ')<br>';
            }
        } else if ($jtype == 'boolean') {
            var_dump($jobj);
        } else {
            print_r($jobj);
        }
        echo '<br><br>' . $jtype . ': -- Information End ----------<br></div><br>';
        return 1;
    }

    public static function lsArray($arr, $i = 1)
    { //递归遍历数组
        $flag = \Yii::$app->getRequest()->getIsConsoleRequest();
        $space = $flag ? "\t" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $br = $flag ? PHP_EOL : '<br>';
        for ($pre = '', $j = 0; $j < $i; $j++)
            $pre .= $space;
        $colors = array('#000000', '#002060', '#c00000', '#00b050', '#ff0000', '#0070c0', '#00b0f0', '#7030a0', '#c0504d', '#ffc000', '#000000');
        $color = $colors[$i];
        foreach ($arr as $key => $item) {
            if (gettype($item) == 'array') {
                echo $pre.($flag ? "\$array" . $i . "['$key'] == array()" : "<span style='color:$color;'>\$array" . $i . "['$key'] == array()</span>").$br;
                self::lsArray($item, $i + 1);
            } else
                echo $pre.($flag? "\$array" . $i . "['$key'] == " . $item:"<span style='color:$color;'>\$array" . $i . "['$key'] == " . ($item) . "</span>").$br;
        }
    }


    /**打印对象调试信息
     * @param $jobj
     */
    public static function jshow($jobj)
    {
        $flag = \Yii::$app->getRequest()->getIsConsoleRequest();
        $br = $flag ? PHP_EOL : '<br>';
        $jtype = gettype($jobj);
        echo $flag ? "$br==Object Debug Info By Jeen==$br" : '<br><div style="background:#eee;padding:10px;"><font color="red">Object Debug Informations By Jeen :</font><br/>';
        echo $jtype . ': -- Information -------------'.$br;
        if ($jtype == 'array') {
            self::lsArray($jobj);
        } elseif ($jtype == 'boolean') {
            var_dump($jobj);
        } elseif ($jtype == 'object') {
            $jclass = get_class($jobj);
            $jref = new \ReflectionClass($jclass);
            $jvars = $jref->getProperties();
            $jmethods = $jref->getMethods();
            echo 'Class : ' . $jclass . ' | has: ' .
                count($jvars) . ' vars and ' .
                count($jmethods) . ' methods .'.$br.'You can see it in File:'
                . $jref->getFileName() . $br;
            $property_str = $flag ? "$br== Properties ==$br" : '<br><b>Properties:</b><hr>';
            foreach ($jvars as $key=>$property) {
                $modname = \Reflection::getModifierNames($property->getModifiers());
                $callmod = isset($modname[1]) && $modname[1] == 'static' ? '::$' : '->';
                $modname = $modname[0] ? : 'public';
                $property_str .= $flag ? ("$key|$modname " . $property->getDeclaringClass()->getName() . $callmod . $property->getName() . $br) : ("$key|$modname " . $property->getDeclaringClass()->getName() . "$callmod<b>" . $property->getName() . "</b>$br");
            }
            echo $property_str;
            echo $flag ? "$br== Functions ==$br" : '<br><b>Functions</b>:<hr>';
            foreach ($jmethods as $key => $value) {
                $params = '';
                $modname = \Reflection::getModifierNames($value->getModifiers());
                $callmod = isset($modname[1]) && $modname[1] == 'static' ? '::' : '->';
                $modname = $modname[0] ? : 'public';
                echo $flag ? ("$key|$modname " . $value->getDeclaringClass()->getName() . $callmod . $value->getName() . '(') : ("$key|<b>$modname</b> " . $value->getDeclaringClass()->getName() . "$callmod<b>" . $value->getName() . '</b>(');
                foreach ($value->getParameters() as $jparam)
                {
                    $params .= $jparam;
                }
                for ($i = 1; $i < 10; $i++)
                    $params = str_replace(']Parameter #' . $i . ' [', ',', $params);
                $params = str_replace('Parameter #0 [', '', $params);
                $params = str_replace(']', '', $params);
                echo $params;
                echo ')'.$br;
            }
        } else {
            print_r($jobj);
        }
        echo $flag? "$br==$jtype --Information End == $br": $br.$br. $jtype . ': -- Information End ----------<br></div>'.$br;
    }


    //递归解析Json字符串或数组Json键值
    public static function handleBodyParams($t)
    {
        if(is_numeric($t) || is_bool($t)) return $t;
        if(is_string($t)) {
            $temp = json_decode($t,true,512,JSON_BIGINT_AS_STRING);
            if($temp === null || $temp === false) return $t;
            if(is_int($temp)) {
                $tArr = str_split($t);
                return $tArr[0] ? $temp : $t;
            } else if(!is_array($temp)) {
                return $temp;
            }
            $t = json_decode($t,true,512,JSON_BIGINT_AS_STRING);
            foreach($t as $k=>$v) {
                $t[$k] = self::handleBodyParams($v);
            }
        } else if(is_array($t)) {
            foreach($t as $k=>$v) {
                $t[$k] = self::handleBodyParams($v);
            }
        } else {
            return [];
        }
        return $t;
    }


}