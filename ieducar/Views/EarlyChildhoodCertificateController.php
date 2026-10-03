<?php

use App\Menu;

class EarlyChildhoodCertificateController extends Portabilis_Controller_ReportCoreController
{
    protected $_processoAp = 999706;
    protected $_titulo = 'Certificado de Conclusão da Educação Infantil';

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
        $this->inputsHelper()->dynamic(['ano', 'instituicao', 'escola']);
        $this->inputsHelper()->dynamic('curso');
        $this->inputsHelper()->dynamic('serie');
        $this->inputsHelper()->dynamic('turma');
        $this->inputsHelper()->simpleSearchMatricula(null, ['label' => 'Matrícula/Aluno', 'required' => false]);
        $this->inputsHelper()->checkbox('emitir_nome_diretor', ['label' => 'Emitir assinatura do gestor escolar', 'value' => true]);
        $this->inputsHelper()->checkbox('emitir_secretario_escolar', ['label' => 'Emitir assinatura do secretário escolar', 'value' => true]);
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
        return new EarlyChildhoodCertificateReport();
    }
}
