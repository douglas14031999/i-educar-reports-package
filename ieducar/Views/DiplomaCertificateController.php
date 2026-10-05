<?php

class DiplomaCertificateController extends Portabilis_Controller_ReportCoreController
{
    /**
     * @var int
     */
    protected $_processoAp = 999718;

    /**
     * @var string
     */
    protected $_titulo = 'Emissão de Diplomas de Conclusão';

    /**
     * @inheritdoc
     */
    protected function _preRender()
    {
        parent::_preRender();

        Portabilis_View_Helper_Application::loadStylesheet($this, 'intranet/styles/localizacaoSistema.css');

        $this->breadcrumb($this->_titulo, [
            'educar_index.php' => 'Escola',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function form()
    {
        $this->inputsHelper()->dynamic(['ano', 'instituicao', 'escola']);
        $this->inputsHelper()->dynamic('curso', ['required' => false]);
        $this->inputsHelper()->dynamic('serie', ['required' => false]);
        $this->inputsHelper()->dynamic('turma', ['required' => false]);
        $this->inputsHelper()->dynamic('matricula', [
            'required' => false,
            'label' => 'Matrícula do Aluno (deixe em branco para emitir toda a turma em lote)'
        ]);

        $this->inputsHelper()->select('modelo', [
            'label' => 'Modelo de Diploma',
            'resources' => [
                1 => 'Modelo 1 - Clássico Azul (Tradicional)',
                2 => 'Modelo 2 - Azul Petróleo com Dourado (Com Carga Horária)',
                3 => 'Modelo 3 - Verde Esmeralda (Com Dados Cadastrais Detalhados e CPF)'
            ],
            'value' => 1
        ]);

        $this->inputsHelper()->select('formato_saida', [
            'label' => 'Formato de Saída',
            'resources' => [
                'pdf' => 'Documento PDF (Download / Impressão Automática)',
                'html' => 'Visualização HTML (Direto no Navegador)'
            ],
            'value' => 'pdf'
        ]);

        $this->inputsHelper()->text('nome_secretario_educacao', [
            'label' => 'Nome do(a) Secretário(a) de Educação',
            'size' => 50,
            'required' => false,
            'placeholder' => 'Informe o nome para o campo de assinatura'
        ]);

        $this->inputsHelper()->text('nome_diretor', [
            'label' => 'Nome do(a) Diretor(a) da Escola',
            'size' => 50,
            'required' => false,
            'placeholder' => 'Informe o nome para o campo de assinatura'
        ]);

        $this->inputsHelper()->text('carga_horaria', [
            'label' => 'Carga Horária Padrão (Horas)',
            'size' => 15,
            'required' => false,
            'value' => '800'
        ]);

        $this->inputsHelper()->date('data_emissao', [
            'label' => 'Data de Emissão do Diploma',
            'required' => false,
            'value' => date('d/m/Y')
        ]);

        $this->loadResourceAssets($this->getDispatcher());
    }

    /**
     * @inheritdoc
     */
    public function beforeValidation()
    {
        $this->report->addArg('ano', (int) $this->getRequest()->ano);
        $this->report->addArg('instituicao', (int) ($this->getRequest()->ref_cod_instituicao ?: ($this->getRequest()->instituicao_id ?: $this->getRequest()->instituicao)));
        $this->report->addArg('escola', (int) ($this->getRequest()->ref_cod_escola ?: ($this->getRequest()->escola_id ?: $this->getRequest()->escola)));
        $this->report->addArg('curso', (int) ($this->getRequest()->ref_cod_curso ?: ($this->getRequest()->curso_id ?: $this->getRequest()->curso)));
        $this->report->addArg('serie', (int) ($this->getRequest()->ref_cod_serie ?: ($this->getRequest()->serie_id ?: $this->getRequest()->serie)));
        $this->report->addArg('turma', (int) ($this->getRequest()->ref_cod_turma ?: ($this->getRequest()->turma_id ?: $this->getRequest()->turma)));
        $this->report->addArg('matricula', (int) ($this->getRequest()->ref_cod_matricula ?: ($this->getRequest()->matricula_id ?: $this->getRequest()->matricula)));

        $this->report->addArg('modelo', (int) ($this->getRequest()->modelo ?: 1));
        $this->report->addArg('formato_saida', (string) ($this->getRequest()->formato_saida ?: 'pdf'));
        $this->report->addArg('nome_secretario_educacao', trim((string) $this->getRequest()->nome_secretario_educacao));
        $this->report->addArg('nome_diretor', trim((string) $this->getRequest()->nome_diretor));
        $this->report->addArg('carga_horaria', trim((string) $this->getRequest()->carga_horaria));
        $this->report->addArg('data_emissao', (string) $this->getRequest()->data_emissao);
    }

    /**
     * @return DiplomaCertificateReport
     */
    public function report()
    {
        return new DiplomaCertificateReport();
    }
}
