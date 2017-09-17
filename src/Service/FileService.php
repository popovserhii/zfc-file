<?php
namespace Popov\ZfcFile\Service;

use Popov\Agere\Service\AbstractEntityService,
    Popov\Logs\Event\Logs as LogsEvent;
use Popov\ZfcFile\Model\File;
use Popov\ZfcCore\Service\DomainServiceAbstract;
use Popov\ZfcCore\Service\DomainServiceAwareTrait;
use Popov\ZfcCore\Service\DomainServiceInterface;

class FileService extends DomainServiceAbstract {

    //protected $_entityAlias = 'Files';
    protected $entity = File::class;



    /**
     * @param string $fileName
     * @param string $path
     */
    public function downloadFile($fileName, $path)
    {
        $filePath = $path . '/' . $fileName;

        header('Content-Encoding: UTF-8');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        if (function_exists('mime_content_type')) {
            header('Content-Type: ' . mime_content_type($filePath));
        }
        $fp = fopen('php://output', 'w');
        $str = @file_get_contents($filePath);
        if (!$str) {
            $str = '';
        }
        fputs($fp, $str);
        fclose($fp);
        exit;
    }

    /**
     * @param string $fileName
     * @param string $path
     * @return bool
     */
    public function downloadOneArchive($fileName, $path)
    {
        $fileNameZip = $path.'/'.$fileName.'.zip';

        $zip = new \ZipArchive();
        $zip->open($fileNameZip, \ZipArchive::CREATE);
        $dirHandle = @opendir($path);

        if ($dirHandle)
        {
            while (false !== ($file = readdir($dirHandle)))
            {
                if ($file != '.' && $file != '..')
                {
                    $zip->addFile($path.'/'.$file, $file);
                }
            }

            $zip->close();

            header('Content-Encoding: UTF-8');
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment;filename="'.$fileName.'.zip"');

            readfile($fileNameZip);

            unlink($fileNameZip);
            exit;
        }
        else
        {
            return false;
        }
    }


    //------------------------------------------Events------------------------------------------
    /**
     * Module Files
     *
     * @param $class
     * @param $params
     */
    public function deleteFile($class, $params)
    {
        die(__METHOD__);
        $event = new LogsEvent();
        $event->events($class)->trigger('files.deleteFile', $this, $params);
    }

    /**
     * Module Users
     *
     * @param $class
     * @param $params
     * @return mixed
     */
    public function deleteFileAction($class, $params)
    {
        die(__METHOD__);
        $event = new LogsEvent();
        return $event->events($class)->trigger('files.deleteFileAction', $this, $params);
    }

    /**
     * Module FileUpload
     *
     * @param $class
     * @param $params
     * @return mixed
     */
    public function fileSession($class, $params)
    {
        die(__METHOD__);
        $event = new LogsEvent();
        return $event->events($class)->trigger('files.fileSession', $this, $params);
    }

}