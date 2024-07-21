<?php
namespace jext\jrbac\src;

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

    private $docDesAttr;
    public $controllerList = [];

    public function getPermissionList($controllers=[],$withAsterisk=true)
    {
        /** @var JDbManager $am */
        $am = \Yii::$app->getAuthManager();
        $this->docDesAttr = $am->docDesAttr;

        $controllerList = $controllers ? : $this->controllerList;
        $permissions = [];
        foreach ($controllerList as $controllerName) {
            if (!StringHelper::endsWith($controllerName,'Controller')) {
                continue;
            }
            $pathPrefix = $this->getPermissionPrefix($controllerName);
            $controllerReflect = new \ReflectionClass($controllerName);
            if ($withAsterisk) {
                $permissions[] = [
                    'path' => $pathPrefix . '*',
                    'description' => $this->getActionClassPermissionDescription($controllerName),
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
                            'description' => isset($diyAction['class']) ? $this->getActionClassPermissionDescription($diyAction['class']) : $methodClassName.'|'.$methodName.'|'.$diyActionName,
                        ];
                    }
                } else {
                    $permissions[] = [
                        'path' => $pathPrefix . $actionName,
                        'description' => $this->handleActionComment($method->getDocComment()) ? : ($pathPrefix . $actionName),
                    ];
                }
            }
        }
        return $permissions;
    }

    private function getActionClassPermissionDescription($actionClass)
    {
        $ref = new \ReflectionClass($actionClass);
        $des = $this->handleActionComment($ref->getDocComment());
        return $des ? : $actionClass;
    }

    private function handleActionComment($comment)
    {
        if (!$comment) {
            return '';
        } else {
            $attrs = (new DocParser())->parse($comment);
            if (isset($attrs[$this->docDesAttr])) {
                return $attrs[$this->docDesAttr];
            }
            if (isset($attrs['description'])) {
                return $attrs['description'];
            }
            return '';
        }
    }

    private function getPermissionPrefix($controllerClassName)
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
        } else if ($cCount == 6) {
            $cName = Inflector::camel2id(substr($cArray[5],0, (strlen($cArray[5]) - 10)));
            return "/{$cArray[2]}/$cName/";
        } else {
            throw new \Exception("Action Controller Class Name Parse Error");
        }
    }
    
}