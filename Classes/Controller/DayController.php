<?php
namespace LeipzigUniversityLibrary\Bookings\Controller;

    /***************************************************************
     *
     *  Copyright notice
     *
     *  (c) 2014
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/

/**
 * ArticleController
 */
class DayController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * ArticleRepository
     *
     * @var \LeipzigUniversityLibrary\PubmanImporter\Domain\Repository\ArticleRepository
     * @inject
     */
    protected $ArticleRepository = NULL;

    /**
     * action show
     *
     * @param string $Article
     * @param string $Issue
     * @param string $Journal
     * @param string $Context
     * @return void
     */
    public function showAction($Article, $Issue = false, $Journal = false, $Context = false) {
        $this->ArticleRepository->setOptions($this->settings);

        $Article = $this->ArticleRepository->findByUid($Article);

        if ($Issue) $Article->setPid($Issue);

        $this->view->assign('Issue', $Issue);
        $this->view->assign('Journal', $Journal);
        $this->view->assign('Context', $Context);
        $this->view->assign('Article', $Article);
        $this->view->assign('RequestUri', $this->request->getRequestUri());
    }
}