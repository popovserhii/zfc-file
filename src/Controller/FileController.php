<?php
namespace Popov\ZfcFile\Controller;

use Agere\Document\Service\DocumentService;
use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel;
use \Popov\ZfcFileUpload\Transfer\Adapter\Http as FileHttp;

class FileController extends AbstractActionController {

    public $serviceName = 'FileService';
    public $folderPublic = './public';
    public $folderVar = './var/documents/';


    public function getAction()
    {
        $route = $this->getEvent()->getRouteMatch();
        $id = (int) $route->getParam('id', 0);
        $folder = $route->getParam('document', '');
        $fileName = $route->getParam('file', '');

        $file = @file_get_contents($this->folderVar.$folder.'/'.$id.'/'.$fileName);

        $view = new ViewModel([
            'file'    => $file ? $file : '',
        ]);

        // Disable layouts; use this view model in the MVC event instead
        $view->setTerminal(true);

        return $view;
    }


    //------------------------------------AJAX----------------------------------------
    public function deleteFileAction()
    {
        $request = $this->getRequest();

        if ($request->isDelete() && $request->isXmlHttpRequest()) {
            $sm = $this->getServiceLocator();
            /** @var \Popov\ZfcFile\Service\FileService $service */
            $service = $sm->get($this->serviceName);

            // Access to page for current user
            #$responseEvent = $service->deleteFileAction(__CLASS__, []);
            #$message = $responseEvent->first()['message'];
            // END Access to page for current user

                $route = $this->getEvent()->getRouteMatch();
                $id = (int) $route->getParam('id');
                #$entityId = $route->getParam('parent');
                $filePath = '';

                // Table entity
                /** @var \Popov\Entity\Service\EntityService $serviceEntity */
                #$serviceEntity = $sm->get('EntityService');
                #$entity = $serviceEntity->getOneItem($entityId);



                /** @var DocumentService $serviceObject */
                #$serviceObject = $sm->get('DocumentService');
                #$pathUploadFiles = $serviceObject->getPathUploadFiles($entity->getMnemo());
                // END Object service

                // Session Module FileUpload
                #$params = [
                #    'locator'    => $sm,
                #    'mnemo'        => $entity->getMnemo(),
                #    'id'        => $id,
                #];

                #$responseEvent = $service->fileSession(__CLASS__, $params);
                #$fileName = $responseEvent->first();
                // END Session Module FileUpload

                #if ($fileName) {
                #    $filePath = session_id() . '/' . $fileName;
                if ($id) {
                    $file = $service->find($id);

                    if ($file) {
                        //$filePath = $item->getObjectId() . '/' . $item->getName();
                        $filePath = $file->getName();

                        // Delete file item
                        #$service->delete($file);
                        $service->getObjectManager()->remove($file);
                        $service->getObjectManager()->flush();
                    }
                }

                if ($filePath) {
                    $filePath = $this->folderPublic . '/' . $filePath;

                    $deleteFile = new FileHttp();
                    $deleteFile->delete($filePath);
                    $deleteFile->deleteEmptyFolder(dirname($filePath));

                    // Delete file
                    #$service->deleteFile(__CLASS__, [
                    #    'filePath' => ($otherPath) ? $otherPath : $this->folderPublic.$pathUploadFiles . '/' . $filePath,
                    #]);
                }

                $result = new JsonModel([
                    'message' => '',
                ]);

            return $result;
        } else {
            $this->getResponse()->setStatusCode(404);
        }
    }

    public function deleteCreatedFileAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost() && $request->isXmlHttpRequest())
        {
            $route = $this->getEvent()->getRouteMatch();
            $locator = $this->getServiceLocator();
            /** @var \Popov\ZfcFile\Service\FileService $service */
            $service = $locator->get($this->serviceName);

            $id = $route->getParam('id');
            $uri = $request->getUri();

            $path = substr($uri->getPath(), (strpos($uri->getPath(), $id) + strlen($id)));
            $path = explode('/', urldecode($path));
            $path[0] = str_replace('-', '_', $path[1]);
            $path[1] = $id;

            // Delete file
            $service->deleteFile(__CLASS__, [
                'filePath' => $this->folderVar.implode('/', $path),
            ]);

            return new JsonModel([
                'message'         => '',
                'undisabled'    => ucfirst($path[2]),
            ]);
        }
        else
            $this->getResponse()->setStatusCode(404);
    }

}