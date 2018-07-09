<?php

namespace ToolsBundle\Enum;

abstract class AbstractEnum
{
    /**
     * @var mixed __DEFAULT
     */
    protected const __DEFAULT = null;

    /**
     * @var \ReflectionClass[] $reflectClass
     */
    protected static $reflectClass;

    /**
     * @var mixed $globalValue
     */
    protected $globalValue;

    /**
     * AbstractEnum constructor.
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($value = null)
    {
        $this->set($value);
    }

    /**
     * @param mixed $value
     *
     * @return AbstractEnum
     *
     * @throws \UnexpectedValueException
     */
    public function set($value) : self
    {
        if (!$value) {
            $value = static::__DEFAULT;
        } elseif (!static::has($value)) {
            throw new \UnexpectedValueException("Aucune valeur n'a été trouvé");
        }

        $this->globalValue = $value;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public static function has($value) : bool
    {
        if (!static::$reflectClass || !array_key_exists(static::class, static::$reflectClass)) {
            static::$reflectClass[static::class] = new \ReflectionClass(static::class);
        }

        return \in_array($value, static::$reflectClass[static::class]->getConstants(), true);
    }

    /**
     * @param bool $constName
     *
     * @return array
     */
    public static function __toArray(bool $constName = false) : array
    {
        if (!static::$reflectClass || !array_key_exists(static::class, static::$reflectClass)) {
            static::$reflectClass[static::class] = new \ReflectionClass(static::class);
        }

        $array = [];

        $reflect = static::$reflectClass[static::class];
        foreach ($reflect->getConstants() as $key => $constant) {
            if ($constant !== null) {
                if ($constName) {
                    $array[$key] = $key;
                } else {
                    $array[$constant] = $constant;
                }
            }
        }
        return $array;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->get();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->globalValue;
    }
}