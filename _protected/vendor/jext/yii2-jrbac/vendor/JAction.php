<?php
namespace jext\jrbac\vendor;

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class JAction
{
    public static $instance;
    public static function getInstance()
    {
        if(empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    public $controllerList = [];

    public function getPermissionList($controllers=[],$withAsterisk=true)
    {
        $controllerList = $controllers ? : $this->controllerList;
        $permissions = [];
        foreach ($controllerList as $controllerName) {
            if (!StringHelper::endsWith($controllerName,'Controller')) {
                continue;
            }
            $pathPrefix = $this->getPremissionPrefix($controllerName);
            $controllerReflect = new \ReflectionClass($controllerName);
            if ($withAsterisk) {
                $permissions[] = [
                    'path' => $pathPrefix . '*',
                    'description' => $this->handleActionComment($controllerReflect->getDocComment())
                ];
            }
            $controllerMethods = $controllerReflect->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($controllerMethods as $method) {
                $methodClassName = $method->getDeclaringClass()->getName();
                if ($controllerName != $methodClassName) {
                    continue;
                }
                $methodName = $method->getName();
                if (!StringHelper::startsWith($methodName, 'action')) {
                    continue;
                }
                $actionName = Inflector::camel2id(substr($methodName,6));
                if ($actionName == 's') {
                    $diyActions = \Yii::createObject($methodClassName, ['','',[]])->$methodName();
                    foreach ($diyActions as $diyActionName=>$diyAction) {
                        $permissions[] = [
                            'path' => $pathPrefix . $diyActionName,
                            'description' => isset($diyAction['class']) ? $diyAction['class'] : $methodClassName.'|'.$methodName.'|'.$diyActionName,
                        ];
                    }
                } else {
                    $permissions[] = [
                        'path' => $pathPrefix . $actionName,
                        'description' => $this->handleActionComment($method->getDocComment())
                    ];
                }
            }
        }
        return $permissions;
    }

    private function handleActionComment($comment)
    {
        if (!$comment) {
            return '';
        } else {
            $cArray = explode('*',$comment);
            if (count($cArray) < 4) {
                return '';
            } else if (trim($cArray[2])) {
                return trim($cArray[2]);
            } else if (trim($cArray[3])) {
                return trim($cArray[3]);
            } else {
                return '';
            }
        }
    }

    private function getPremissionPrefix($controllerClassName)
    {
        $cArray = explode('\\', $controllerClassName);
        $cCount = count($cArray);
        if ($cCount == 3) {
            substr($cArray[2],0, (strlen($cArray[2]) - 10));
            $cName = Inflector::camel2id(substr($cArray[2],0, (strlen($cArray[2]) - 10)));
            return "/$cName/";
        } else if ($cCount == 4) {
            $cName = Inflector::camel2id(substr($cArray[3],0, (strlen($cArray[3]) - 10)));
            return "/{$cArray[1]}/$cName/";
        } else if ($cCount == 5) {
            $cName = Inflector::camel2id(substr($cArray[4],0, (strlen($cArray[4]) - 10)));
            return "/{$cArray[2]}/$cName/";
        } else { 
            throw new \Exception("Admin Action Controller Class Name Error");
        }
    }
    
}