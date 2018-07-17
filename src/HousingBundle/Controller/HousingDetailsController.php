<?php

namespace HousingBundle\Controller;

use HousingBundle\Entity\Housing;
use HousingBundle\Enum\HousingStateEnum;
use HousingBundle\Form\HousingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ToolsBundle\Service\DataResponseAdapter;

/**
 * Housing controller.
 *
 * @Security("has_role('ROLE_PROPRIETARY')")
 */
class HousingDetailsController extends Controller
{

}
