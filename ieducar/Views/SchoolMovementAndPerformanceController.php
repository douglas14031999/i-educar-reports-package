<?php

require_once __DIR__ . '/GeneralMovementController.php';

class SchoolMovementAndPerformanceController extends GeneralMovementController
{
    protected $_processoAp = 999890;

    protected $_titulo = 'Rendimento e movimento escolar';

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Rendimento e movimento escolar', [
            'educar_index.php' => 'Escola',
        ]);
    }
}
