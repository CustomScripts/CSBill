<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\DashboardBundle\Controller;

use CSBill\CoreBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * Homepage action.
     */
    public function indexAction()
    {
	return $this->render('CSBillDashboardBundle:Default:index.html.twig');
    }
}
