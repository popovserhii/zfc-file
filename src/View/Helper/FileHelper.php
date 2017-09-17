<?php
namespace Popov\ZfcFile\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Popov\Agere\File\Transfer\Adapter\Http;

class FileHelper extends AbstractHelper
{
	/**
	 * @param string $pathFile
	 * @param object $items
	 * @param string $url
	 * @param string $classDelete
	 * @param string $imageSrc
	 * @return string
	 */
	public function filesList($pathFile, $items, $url = '', $classDelete = 'file-delete', $imageSrc = '')
	{
	    die(__METHOD__);
		$image = ($imageSrc != '') ? ' <img src="'.$imageSrc.'">' : '';
		$options = '';

		if ($items)
		{
			foreach ($items as $item)
			{
				$options .= '<li>
			<a href="'.$pathFile.$item->getName().'" target="_blank">'.$item->getName().$image.'</a>';

				if ($url != '')
				{
					$options .= '<input type="hidden" name="id" value="'.$item->getId().'">
				<a href="'.$url.'/'.$item->getId().'" class="'.$classDelete.'">Удалить</a>';
				}

				$options .= '</li>';
			}
		}

		return '<ul>'.$options.'</ul>';
	}

	/**
	 * @param array $validatorData
	 * @return string
	 */
	public function messagesByValidator(array $validatorData)
	{
	    die(__METHOD__);
		$li = '';

		foreach ($validatorData as $message)
		{
			$li .= '<li>'.$message.'</li>';
		}

		return $li;
	}

    /**
     * @param string $dir
     * @return bool
     */
    public function emptyDir($dir)
    {
        $http = new Http();
        return $http->emptyDir($dir);
    }

}