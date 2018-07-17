<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingType;
use HousingBundle\Enum\HousingStateEnum;
use HousingBundle\Form\HousingTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Housing controller.
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class HousingDetailController extends Controller
{
}
