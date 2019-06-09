<?php

namespace link\hefang\site\ueditor\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\exceptions\FileNotFoundException;
use link\hefang\helpers\FileHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\mvc\views\TextView;
use link\hefang\site\content\models\FileModel;
use link\hefang\site\ueditor\models\UEditorUploader;

class UEditorController extends BaseController
{
    private $config = [];
    private $fieldName = '';
    private $type = 'upload';

    private $allowFiles = [];
    private $listSize;
    private $path;


    private $allConfig = [];

    /**
     * UEditorController constructor.
     * @throws FileNotFoundException
     */
    public function __construct()
    {
        $configFile = PATH_APPLICATION . DS . 'ueditor.config.php';
        if (!is_file($configFile)) {
            throw new FileNotFoundException($configFile);
        }
        $this->allConfig = include($configFile);
    }

    public function action(): BaseView
    {
        $this->_checkLogin(null, true, false, [], true);
        $action = $this->_get("action");
        if (method_exists($this, $action)) {
            return call_user_func([$this, $action]);
        } else {
            return $this->result([
                'state' => '请求地址出错',
                'success' => false,
                'result' => '请求地址出错'
            ]);
        }
    }

    public function listimage(): BaseView
    {
        $this->allowFiles = $this->allConfig['imageManagerAllowFiles'];
        $this->listSize = $this->allConfig['imageManagerListSize'];
        $this->path = $this->allConfig['imageManagerListPath'];
        return $this->listfile(__FUNCTION__);
    }

    public function listfile(string $type = null): BaseView
    {
        $this->_checkLogin(null, true, false, [], true);
        if (StringHelper::isNullOrBlank($type)) {
            $this->allowFiles = $this->allConfig['fileManagerAllowFiles'];
            $this->listSize = $this->allConfig['fileManagerListSize'];
            $this->path = $this->allConfig['fileManagerListPath'];
        }
        $allowFiles = join("|", array_map(function ($item) {
            return str_replace('.', '', $item);
        }, $this->allowFiles));
        $size = $this->_request("size", $this->listSize);
        $start = $this->_request("start", 0);
        $end = $start + $size;
        $path = $_SERVER['DOCUMENT_ROOT'] . ($this->path{0} === '/' ? '' : '/') . $this->path;
        $files = $this->getFiles($path, $allowFiles);
        if (!count($files)) {
            return $this->result([
                "state" => "找不到匹配的文件",
                "success" => false,
                "result" => "找不到匹配的文件",
                "list" => [],
                "start" => $start,
                "total" => count($files)
            ]);
        }
        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
        /* 返回数据 */
        $result = [
            "state" => "SUCCESS",
            "success" => true,
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ];
        return $this->result($result);
    }

    public function config(): BaseView
    {
        $this->_checkAdmin();
        return $this->result($this->allConfig);
    }

    public function uploadscrawl(): BaseView
    {
        $this->config = [
            "pathFormat" => $this->allConfig['scrawlPathFormat'],
            "maxSize" => $this->allConfig['scrawlMaxSize'],
            "allowFiles" => $this->allConfig['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        ];
        $this->fieldName = $this->allConfig['scrawlFieldName'];
        return $this->uploadfile(__FUNCTION__);
    }

    public function uploadvideo(): BaseView
    {
        $this->config = [
            "pathFormat" => $this->allConfig['videoPathFormat'],
            "maxSize" => $this->allConfig['videoMaxSize'],
            "allowFiles" => $this->allConfig['videoAllowFiles']
        ];
        $this->fieldName = $this->allConfig['videoFieldName'];
        return $this->uploadfile(__FUNCTION__);
    }

    public function uploadimage(): BaseView
    {
        $this->config = [
            "pathFormat" => $this->allConfig['imagePathFormat'],
            "maxSize" => $this->allConfig['imageMaxSize'],
            "allowFiles" => $this->allConfig['imageAllowFiles']
        ];
        $this->fieldName = $this->allConfig['imageFieldName'];
        return $this->uploadfile(__FUNCTION__);
    }

    public function uploadfile(string $type = null): BaseView
    {
        $login = $this->_checkAdmin();
        if (!Mvc::getConfig("upload|enable", true)) {
            return $this->result([
                "success" => false,
                'state' => '文件上传功能已关闭'
            ]);
        }
        if (StringHelper::isNullOrBlank($type)) {
            $this->config = [
                "pathFormat" => $this->allConfig['filePathFormat'],
                "maxSize" => $this->allConfig['fileMaxSize'],
                "allowFiles" => $this->allConfig['fileAllowFiles']
            ];
            $this->fieldName = $this->allConfig['fileFieldName'];
        }
        $uploader = new UEditorUploader($this->fieldName, $this->config, $this->type);
        $m = new FileModel();
        try {
            $m->setId(RandomHelper::guid())
                ->setIsSecret(false)
                ->setFileName($uploader->getOriName())
                ->setMimeType($uploader->getMimeType())
                ->setSize($uploader->getFileSize())
                ->setSavePath($uploader->getFilePath())
                ->setUploadTime(TimeHelper::formatMillis())
                ->setLoginId($login->getId())
                ->setEnable(true)
                ->setUploadFrom($this->_header('referer'))
                ->insert();
        } catch (\Throwable $e) {
            Mvc::getLogger()->error('保存文件信息失败', $e->getMessage(), $e);
        }
        return $this->result($uploader->getFileInfo());
    }

    public function result(array $res): BaseView
    {
        $callback = $this->_get("callback");
        $result = json_encode($res, JSON_UNESCAPED_UNICODE);
        if (!StringHelper::isNullOrBlank($callback)) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                return $this->_text(htmlspecialchars($callback) . '(' . $result . ')');
            } else {
                return $this->_text(json_encode([
                    'state' => 'callback参数不合法',
                    'success' => false
                ], JSON_UNESCAPED_UNICODE), TextView::JSON);
            }
        } else {
            return $this->_text(json_encode($res, JSON_UNESCAPED_UNICODE), TextView::JSON);
        }
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param $allowFiles
     * @param array $files
     * @return array
     */
    function getFiles($path, $allowFiles, &$files = [])
    {
        if (!is_dir($path)) return null;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';
        $files = FileHelper::listFiles($path);
        $list = [];
        foreach ($files as $file) {
            if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                $list[] = [
                    'url' => str_replace(DS, '/', substr($file, strlen($_SERVER['DOCUMENT_ROOT']))),
                    'mtime' => filemtime($file)
                ];
            }
        }
        return $list;
    }
}