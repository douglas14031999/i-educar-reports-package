<?php

use App\Menu;

class EarlyChildhoodCommitmentTermController extends Portabilis_Controller_ReportCoreController
{
    protected $_processoAp = 999703;
    protected $_titulo = 'Termos de Compromisso da Educação Infantil';

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
        $this->inputsHelper()->dynamic('curso', ['required' => false]);
        $this->inputsHelper()->dynamic('serie', ['required' => false]);
        $this->inputsHelper()->dynamic('turma', ['required' => false]);
        $this->inputsHelper()->simpleSearchMatricula(null, ['label' => 'Matrícula/Aluno', 'required' => false]);
        $this->inputsHelper()->checkbox('emitir_nome_diretor', ['label' => 'Emitir assinatura do gestor escolar']);
        $this->inputsHelper()->checkbox('emitir_secretario_escolar', ['label' => 'Emitir assinatura do secretário escolar']);
        $this->loadResourceAssets($this->getDispatcher());
    }

    public function beforeValidation()
    {
        $this->report->addArg('ano', (int) $this->getRequest()->ano);
        $this->report->addArg('instituicao', (int) $this->getRequest()->ref_cod_instituicao);
        $this->report->addArg('escola', (int) $this->getRequest()->ref_cod_escola);
        $this->report->addArg('curso', (int) $this->getRequest()->ref_cod_curso);
        $this->report->addArg('serie', (int) $this->getRequest()->ref_cod_serie);
        $this->report->addArg('turma', (int) $this->getRequest()->ref_cod_turma);
        $this->report->addArg('matricula', (int) $this->getRequest()->matricula_id);
        $this->report->addArg('emitir_nome_diretor', (bool) $this->getRequest()->emitir_nome_diretor);
        $this->report->addArg('emitir_secretario_escolar', (bool) $this->getRequest()->emitir_secretario_escolar);
    }

    public function report()
    {
        return new EarlyChildhoodCommitmentTermReport();
    }
}
