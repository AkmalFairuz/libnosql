<?php

namespace libnosql;

class Table
{
    /**
     * Table Name.
     * @var string
     */
    private $table;

    /**
     * Table constructor.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
        @mkdir($this->getPath());
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return LibNoSQL::getPath() . "tables" . DIRECTORY_SEPARATOR . $this->getName() . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->table;
    }

    /**
     * You can use this function to delete table
     * @return void
     */
    public function drop(): void
    {
        LibNoSQL::removeDir($this->getPath());
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getString(string $key): ?string
    {
        $val = $this->getValue($key);
        if($val === null) return null;
        return (string)$val;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getValue(string $key): ?string
    {
        if (($path = $this->isExists($key)) !== false) {
            return file_get_contents($path);
        }
        return null;
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public function isExists(string $key)
    {
        $dir = $this->getPath() . $key[0] . DIRECTORY_SEPARATOR;
        if (is_dir($dir)) {
            $file = $dir . $key . LibNoSQL::FILE_EXTENSION;
            if (is_file($file)) {
                return $file;
            }
        }
        return false;
    }

    /**
     * @param string $key
     * @return object|null
     */
    public function getObject(string $key): ?object
    {
        $val = $this->getValue($key);
        if($val === null) return null;
        $val = unserialize($val);
        if ($val === false) {
            return null;
        }
        return (object)$val;
    }

    /**
     * @param string $key
     * @return int|null
     */
    public function getInt(string $key): ?int
    {
        $val = $this->getValue($key);
        if($val === null) return null;
        return (int)$val;
    }

    /**
     * @param string $key
     * @return float|null
     */
    public function getFloat(string $key): ?float
    {
        $val = $this->getValue($key);
        if($val === null) return null;
        return (float)$val;
    }

    /**
     * @param string $key
     * @param int $value
     */
    public function setInt(string $key, int $value): void
    {
        $this->setValue($key, $value);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setValue(string $key, $value): void
    {
        $path = $this->getPath() . $key[0] . DIRECTORY_SEPARATOR;
        if ($this->isExists($key) === false) {
            @mkdir($path);
        }
        $file = $path . $key . LibNoSQL::FILE_EXTENSION;
        file_put_contents($file, $value);
    }

    /**
     * @param string $key
     * @param float $value
     */
    public function setFloat(string $key, float $value): void
    {
        $this->setValue($key, $value);
    }

    /**
     * @param string $key
     * @param object $object
     */
    public function setObject(string $key, object $object): void
    {
        $serializedObj = serialize($object);
        if ($serializedObj === false) {
            return;
        }
        $this->setString($key, $serializedObj);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setString(string $key, string $value): void
    {
        $this->setValue($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $index
     */
    public function pushArray(string $key, $value, $index = null)
    {
        $ar = $this->getArray($key);
        if ($index === null) {
            $ar[] = $value;
        } else {
            $ar[$index] = $value;
        }
        $this->setArray($key, $value);
    }

    /**
     * @param string $key
     * @return array|null
     */
    public function getArray(string $key): ?array
    {
        $val = unserialize($this->getValue($key));
        return $val !== null && $val !== false ? $val : null;
    }

    /**
     * @param string $key
     * @param array $value
     */
    public function setArray(string $key, array $value): void
    {
        $value = serialize($value);
        $this->setValue($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function inArray(string $key, $value): bool
    {
        if (in_array($value, $this->getArray($key))) {
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     * @return int
     */
    public function countArray(string $key): int
    {
        return count($this->getArray($key));
    }

    /**
     * @param string $key
     * @param mixed $index
     * @return bool
     */
    public function existsArray(string $key, $index): bool
    {
        return isset($this->getArray($key)[$index]) ? true : false;
    }

    /**
     * @param string $key
     */
    public function reindexArray(string $key): void
    {
        $ar = $this->getArray($key);
        $output = array_values($ar);
        $this->setArray($key, $output);
    }

    /**
     * @param string $key
     * @param mixed $index
     */
    public function unsetArray(string $key, $index)
    {
        $ar = $this->getArray($key);
        if (isset($ar[$index])) {
            unset($ar[$index]);
            $this->setArray($key, $ar);
        }
    }

    /**
     * @param string $key
     */
    public function unset(string $key): void
    {
        $dir = $this->getPath() . $key[0] . DIRECTORY_SEPARATOR;
        if (is_dir($dir)) {
            $file = $dir . $key . LibNoSQL::FILE_EXTENSION;
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

}