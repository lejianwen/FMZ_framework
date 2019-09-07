<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 14:28
 */

namespace lib;

class file
{
    protected $filename;
    protected $file_spl;
    protected $up_info;
    public $error;

    public function __construct($file)
    {
        $this->filename = $file;
        $this->file_spl = new \SplFileInfo($file);
        return $this;
    }

    public function setUpInfo($info)
    {
        $this->up_info = $info;
        return $this;
    }

    /**
     * 移动上传文件到指定目录
     * @param string $path 目录
     * @param bool $filename 文件名： true生成随机名，空字符串表示使用原文件名
     * @param bool $overwrite 是否覆盖
     * @return bool|string
     */
    public function moveUpFile($path, $filename = true, $overwrite = false)
    {
        $filename = $this->buildFileName($filename);
        $to_file = $path . $filename;
        if (!$this->checkPath(dirname($to_file))) {
            return false;
        }
        if (!is_uploaded_file($this->filename)) {
            $this->error = 'file is not uploaded : ' . $this->filename;
            return false;
        }
        if (!$overwrite && is_file($to_file)) {
            $this->error = 'file exists : ' . $to_file;
            return false;
        }
        if (!move_uploaded_file($this->filename, $to_file)) {
            $this->error = 'file move upload error！';
            return false;
        }
        return $filename;
    }

    public function move($path, $filename = true, $overwrite = false)
    {
        $filename = $this->buildFileName($filename);
        $to_file = $path . $filename;
        if (!$this->checkPath(dirname($to_file))) {
            return false;
        }
        if (!$overwrite && is_file($to_file)) {
            $this->error = 'file exists : ' . $to_file;
            return false;
        }
        if (!rename($this->filename, $to_file)) {
            $this->error = 'file move error！';
            return false;
        }
        return $filename;
    }

    public function copy($path, $filename = true, $overwrite = false)
    {
        $filename = $this->buildFileName($filename);
        $to_file = $path . $filename;
        if (!$this->checkPath(dirname($to_file))) {
            return false;
        }
        if (!$overwrite && is_file($to_file)) {
            $this->error = 'file exists : ' . $to_file;
            return false;
        }
        if (!copy($this->filename, $to_file)) {
            $this->error = 'file upload error！';
            return false;
        }
        return $filename;
    }

    /**
     * 创建文件名
     * @param bool $filename
     * @return bool|string
     */
    protected function buildFileName($filename = true)
    {
        if (true === $filename) {
            //生成随机名字
            $new_filename = uniqid() . mt_rand(1000, 9999) . '.' . $this->getExtension();
        } elseif ('' === $filename) {
            //保留原有名字
            $new_filename = $this->getOldFileName();
        } else {
            $new_filename = $filename;
        }
        return $new_filename;
    }

    /**
     * 获取旧的文件名，包含扩展名
     * @return string
     */
    protected function getOldFileName()
    {
        if (is_uploaded_file($this->filename)) {
            $old_name = $this->getUpInfo('name');
        } else {
            $old_name = $this->file_spl->getFilename();
            if ($this->getExtension()) {
                $old_name .= '.' . $this->getExtension();
            }
        }
        return $old_name;
    }

    public function checkPath($path)
    {
        if (is_dir($path)) {
            return true;
        }

        if (mkdir($path, 0755, true)) {
            return true;
        } else {
            $this->error = "dir {$path} create fail！";
            return false;
        }
    }


    public function getSpl()
    {
        return $this->file_spl;
    }

    public function getUpInfo($name = '')
    {
        if ($name) {
            return $this->up_info[$name];
        }
        return $this->up_info;
    }

    protected function getExtension()
    {
        if (is_uploaded_file($this->filename)) {
            return $this->getUpExtension();
        } else {
            return $this->file_spl->getExtension();
        }
    }

    /**
     * 获取上传的文件的扩展名
     * @return mixed
     * @author Lejianwen
     */
    protected function getUpExtension()
    {
        return pathinfo($this->getUpInfo('name'), PATHINFO_EXTENSION);
    }
}