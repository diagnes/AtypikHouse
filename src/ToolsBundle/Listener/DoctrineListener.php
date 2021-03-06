<?php
namespace ToolsBundle\Listener;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class DoctrineListener
 */
class DoctrineListener
{
    /**
     * Do some action before the flush
     *
     * @param PreFlushEventArgs $event Get the preFlush argument
     *
     * @return void
     */
    public function preFlush(PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        foreach ($em->getUnitOfWork()->getScheduledEntityDeletions() as $object) {
            if (method_exists($object, 'getDeletedAt')) {
                if ($object->getDeletedAt() instanceof \Datetime) {
                    continue;
                }

                $object->setDeletedAt(new \DateTime());
                $em->merge($object);
                $em->persist($object);
            }
        }
    }
}