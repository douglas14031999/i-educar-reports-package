<?php

use App\Menu;

class SchoolHistoryConferenceController extends Portabilis_Controller_ReportCoreController
{
    protected $_processoAp = 999717;
    protected $_titulo = 'Histórico Escolar - Conferência';

    public function titulo()
    {
        $menu = Menu::query()->where('process', $this->_processoAp)->first();

        return $menu ? $menu->title : $this->_titulo;
    }

    protected function _preRender()
    {
        parent::_preRender();
        Portabilis_View_Helper_Application::loadStylesheet($this, 'intranet/styles/localizacaoSistema.css');
        $this->breadcrumb($this->titulo(), [
            'educar_index.php' => 'Escola',
        ]);
    }

    public function form()
    {
        $this->inputsHelper()->dynamic(['ano', 'instituicao']);
        $this->inputsHelper()->dynamic('escola', ['required' => true]);
        $this->inputsHelper()->simpleSearchMatricula(null, ['label' => 'Matrícula/Aluno', 'required' => true]);
        $this->loadResourceAssets($this->getDispatcher());
    }

    public function beforeValidation()
    {
        $this->report->addArg('ano', (int) $this->getRequest()->ano);
        $this->report->addArg('instituicao', (int) $this->getRequest()->ref_cod_instituicao);
        $this->report->addArg('escola', (int) $this->getRequest()->ref_cod_escola);
        $this->report->addArg('matricula', (int) $this->getRequest()->matricula_id);
    }

    public function report()
    {
        return new SchoolHistoryConferenceReport();
    }
}
