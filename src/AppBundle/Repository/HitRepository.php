<?php

namespace AppBundle\Repository;

use AppBundle\Document\Project;
use Doctrine\ODM\MongoDB\DocumentRepository;

class HitRepository extends DocumentRepository
{
    public function findNewest(Project $project, int $limit = 10)
    {
        return $this->createQueryBuilder()
            ->field('project')->equals($project)
            ->sort('timestamp', 'DESC')
            ->limit($limit)
            ->getQuery()
            ->execute();
    }
}
