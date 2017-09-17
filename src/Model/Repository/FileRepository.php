<?php

namespace Popov\ZfcFile\Model\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Popov\ZfcCore\Model\Repository\EntityRepository;

class FileRepository extends EntityRepository {

    protected $alias = 'file';

	/**
	 * @param string $entityMnemo
	 * @param int|array $itemId
	 * @return array
	 */
	public function findAllItems($entityMnemo, $itemId)
	{
        $entityAlias = 'entity';
        $creatorAlias = 'c';

        $qb = $this->createQueryBuilder($this->alias);
        $qb->addSelect($entityAlias)
            ->innerJoin($this->alias.'.entity', $entityAlias)
            ->addSelect($creatorAlias)
            ->leftJoin($this->alias.'.createdBy', $creatorAlias)
            ->where($entityAlias.'.mnemo = :mnemo')
            ->andWhere($qb->expr()->in($this->getFieldAlias('itemId'), $itemId))
            ->setParameters(['mnemo' => $entityMnemo]);

        return $qb->getQuery()->getResult();
	}

}