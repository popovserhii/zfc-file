<?php
namespace Popov\ZfcFile;

use Zend\ModuleManager\ModuleManager,
	Zend\EventManager\Event,
	Popov\Agere\File\Transfer\Adapter\Http;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

	public function init(ModuleManager $mm)
	{
		$mm->getEventManager()->getSharedManager()
			->attach(Controller\FileController::class, ['files.deleteFile'],
				function(Event $evt)
				{
					$this->deleteFile($evt);
				}
			);

		#$mm->getEventManager()->getSharedManager()
		#	->attach('Popov\Users\Controller\UsersController', ['users.deleteFile'],
		#		function(Event $evt)
		#		{
		#			$this->deleteFile($evt);
		#		}
		#	);

		#$mm->getEventManager()->getSharedManager()
		#	->attach('Popov\Users\Controller\StaffController', ['staff.deleteFile'],
		#		function(Event $evt)
		#		{
		#			$this->deleteFile($evt);
		#		}
		#	);


	}

	public function uploadFiles($evt)
	{
		$locator = $evt->getParam('locator');
		$service = $evt->getParam('service');

		// Reflection class
		$targetClass = get_class($evt->getTarget());
		$serviceReflection = $locator->get('ReflectionService');
		$classInfo = $serviceReflection->getClassInfo($targetClass);

		if (! is_null($evt->getParam('files')))
		{
			// Upload files
			$upload = new Http();
			$upload->setDestination($evt->getParam('destination'));
			$upload->setPrefixFileName($evt->getParam('fileName'));
			$uploadFiles = $upload->receive($evt->getParam('files'));
		}
		else
		{
			$uploadFiles = $evt->getParam('uploadFiles', array());
		}

		// Table entity
		/** @var \Popov\Entity\Service\EntityService $serviceEntity */
		$serviceEntity = $locator->get('EntityService');
		$oneItemEntity = $serviceEntity->getOneItem($classInfo['module'], 'namespace');

		if ($uploadFiles)
		{
			$service->saveData($evt->getParam('objectId'), $uploadFiles, $oneItemEntity);
		}
	}

	public function deleteFile($evt)
	{
		$filePath = $evt->getParam('filePath');

		$deleteFile = new Http();
		$deleteFile->delete($filePath);
		$deleteFile->deleteEmptyFolder(dirname($filePath));
	}

	public function removeDir($evt)
	{
		$removeDir = new Http();
		$removeDir->deleteFolder($evt->getParam('dir'));
	}
}
