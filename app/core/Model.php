<?php

abstract class Model
{
    protected $table = '';
    protected $primaryKeys = [];
    
    public static function __callStatic($name, $arguments)
    {
        $classname = get_called_class();
        if (method_exists($classname, $name)) {
            return call_user_func_array([$classname, $name], $arguments);
        }
        $model = new $classname();
        $q = AppDatabase::table($model->table);
        return call_user_func_array([$q, $name], $arguments);
    }
    
    public function save()
    {
        if ($this->exists()) {
            $q = null;
            foreach ($this->primaryKeys as $key) {
                $q = $q ? $q->where($key, $this->$key) : self::where($key, $this->$key);
            }
            $q->update($this->toArray());
        } else {
            $id = self::insert($this->toArray());
            if (count($this->primaryKeys) == 1) {
                $this->{$this->primaryKeys[0]} = $id;
            }
        }
    }
    
    public function exists()
    {
        $q = null;
        foreach ($this->primaryKeys as $key) {
            if (!$this->$key) {
                return false;
            }
            $q = $q ? $q->where($key, $this->$key) : self::where($key, $this->$key);
        }
        return $q->first() ? true : false;
    }
    
    public function toArray()
    {
        $data = [];
        $reflect = new ReflectionClass($this);
        foreach ($reflect->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
            $data[$prop->getName()] = $prop->getValue($this);
        }
        return $data;
    }
}