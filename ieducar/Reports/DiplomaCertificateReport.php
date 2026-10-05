<?php

class DiplomaCertificateReport extends Portabilis_Report_ReportCore
{
    /**
     * @inheritdoc
     */
    public function templateName()
    {
        return 'diploma-certificate';
    }

    /**
     * @inheritdoc
     */
    public function requiredArgs()
    {
        $this->addRequiredArg('ano');
        $this->addRequiredArg('instituicao');
        $this->addRequiredArg('escola');
        $this->addRequiredArg('modelo');
    }

    /**
     * @return QueryDiplomaCertificate
     */
    public function getQuery()
    {
        return new QueryDiplomaCertificate();
    }

    /**
     * Formata a data de emissão por extenso (ex: 04 de outubro de 2026).
     *
     * @param string $date
     * @return string
     */
    protected function formatDataEmissao($date)
    {
        if (empty($date)) {
            $date = date('d/m/Y');
        }

        $meses = [
            1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
            5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
            9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
        ];

        $parts = explode('/', $date);
        if (count($parts) === 3) {
            $dia = (int) $parts[0];
            $mes = (int) $parts[1];
            $ano = $parts[2];
            if (isset($meses[$mes])) {
                return "{$dia} de {$meses[$mes]} de {$ano}";
            }
        }

        return $date;
    }

    /**
     * Retorna o caminho do arquivo de modelo HTML selecionado.
     *
     * @param int $modelo
     * @return string
     * @throws Exception
     */
    protected function getTemplateFilePath($modelo)
    {
        $baseDir = __DIR__ . '/../ModelosDiplomas';

        switch ((int) $modelo) {
            case 2:
                $file = $baseDir . '/diploma_modelo_2_azul_petroleo.html';
                break;
            case 3:
                $file = $baseDir . '/diploma_modelo_3_verde.html';
                break;
            case 1:
            default:
                $file = $baseDir . '/diploma_modelo_1_azul.html';
                break;
        }

        if (!file_exists($file)) {
            throw new Exception("O modelo de diploma selecionado não foi encontrado: {$file}");
        }

        return $file;
    }

    /**
     * Sobrescreve dumps() para gerar e retornar o binário do PDF (ou HTML) dos diplomas.
     *
     * @return string
     * @throws Exception
     */
    public function dumps($options = [])
    {
        $rows = $this->getQuery()->get($this->args);

        if (empty($rows)) {
            throw new Exception('Nenhum registro de aluno concluinte/aprovado encontrado para os filtros selecionados.');
        }

        $modeloId = (int) ($this->args['modelo'] ?? 1);
        $templatePath = $this->getTemplateFilePath($modeloId);
        $htmlTemplate = file_get_contents($templatePath);

        // Separa o cabeçalho HTML, a seção .sheet e o fechamento sem risco de PCRE backtrack limit
        $bodyOpenPos = stripos($htmlTemplate, '<body');
        if ($bodyOpenPos === false) {
            throw new Exception('Tag <body> não encontrada no modelo HTML de diploma.');
        }
        $bodyTagEndPos = strpos($htmlTemplate, '>', $bodyOpenPos);
        if ($bodyTagEndPos === false) {
            throw new Exception('Fechamento da tag <body> não encontrado.');
        }

        $bodyClosePos = strripos($htmlTemplate, '</body>');
        if ($bodyClosePos === false) {
            throw new Exception('Tag </body> não encontrada no modelo HTML de diploma.');
        }

        $headPart = substr($htmlTemplate, 0, $bodyTagEndPos + 1);
        $bodyTemplate = substr($htmlTemplate, $bodyTagEndPos + 1, $bodyClosePos - ($bodyTagEndPos + 1));
        $tailPart = substr($htmlTemplate, $bodyClosePos);

        // Garante que a quebra de página por aluno no CSS seja estrita em A4 paisagem
        $headPart = str_replace(
            '</style>',
            '@media print { .sheet { page-break-after: always !important; break-after: page !important; zoom: 1 !important; } }
             .sheet { page-break-after: always; break-after: page; }
             </style>',
            $headPart
        );

        // Extrai o bloco <section class="sheet ...">...</section> de forma limpa e direta
        $secOpenPos = stripos($bodyTemplate, '<section');
        $secClosePos = strripos($bodyTemplate, '</section>');

        if ($secOpenPos !== false && $secClosePos !== false) {
            $secTagEndPos = strpos($bodyTemplate, '>', $secOpenPos);
            $secTag = substr($bodyTemplate, $secOpenPos, $secTagEndPos - $secOpenPos + 1);
            if (preg_match('/class=["\']([^"\']*)["\']/i', $secTag, $classMatch)) {
                $sheetClass = $classMatch[1];
            } else {
                $sheetClass = 'sheet m' . $modeloId;
            }
            $singleSheet = substr($bodyTemplate, $secTagEndPos + 1, $secClosePos - ($secTagEndPos + 1));
        } else {
            $sheetClass = 'sheet m' . $modeloId;
            $singleSheet = $bodyTemplate;
        }

        $dataEmissaoExtenso = $this->formatDataEmissao($this->args['data_emissao'] ?? date('d/m/Y'));
        $secEducacao = (string) ($this->args['nome_secretario_educacao'] ?? '');
        $diretorEscola = (string) ($this->args['nome_diretor'] ?? '');
        $cargaDefault = (string) ($this->args['carga_horaria'] ?? '800');

        $sectionsHtml = '';
        $totalAlunos = count($rows);
        $idx = 0;

        foreach ($rows as $row) {
            $idx++;
            $cargaHoraria = !empty($row['carga_horaria']) ? (string) $row['carga_horaria'] : $cargaDefault;
            $cpf = !empty($row['cpf_aluno']) ? (string) $row['cpf_aluno'] : 'Não informado';
            $pai = !empty($row['nome_pai']) ? (string) $row['nome_pai'] : 'Pai não informado';
            $mae = !empty($row['nome_mae']) ? (string) $row['nome_mae'] : 'Mãe não informada';

            $replacements = [
                '{{nome_aluno}}' => (string) ($row['nome_aluno'] ?? ''),
                '{{data_nascimento}}' => (string) ($row['data_nascimento'] ?? ''),
                '{{naturalidade}}' => (string) ($row['naturalidade'] ?? 'Não informada'),
                '{{nacionalidade}}' => (string) ($row['nacionalidade'] ?? 'Brasileira'),
                '{{cpf_aluno}}' => $cpf,
                '{{nome_pai}}' => $pai,
                '{{nome_mae}}' => $mae,
                '{{nome_escola}}' => (string) ($row['nome_escola'] ?? ''),
                '{{nome_curso}}' => (string) ($row['nome_curso'] ?? ''),
                '{{nome_serie}}' => (string) ($row['nome_serie'] ?? ''),
                '{{nome_turma}}' => (string) ($row['nome_turma'] ?? ''),
                '{{ano_conclusao}}' => (string) ($row['ano_conclusao'] ?? $this->args['ano']),
                '{{carga_horaria}}' => $cargaHoraria,
                '{{data_emissao}}' => $dataEmissaoExtenso,
                '{{nome_secretario_educacao}}' => $secEducacao,
                '{{nome_diretor}}' => $diretorEscola,
            ];

            $sheetContent = str_replace(array_keys($replacements), array_values($replacements), $singleSheet);

            // Não aplica quebra forçada apenas após a última página
            $pageBreakStyle = ($idx < $totalAlunos) ? 'style="page-break-after: always; break-after: page;"' : '';
            $sectionsHtml .= "<section class=\"{$sheetClass}\" {$pageBreakStyle}>{$sheetContent}</section>\n";
        }

        $fullHtml = $headPart . "\n" . $sectionsHtml . "\n" . $tailPart;

        // Se o usuário solicitou visualização HTML
        if (($this->args['formato_saida'] ?? 'pdf') === 'html') {
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: inline; filename="diplomas.html"');
            return $fullHtml;
        }

        // Renderização em PDF via Google Chrome / Chromium Headless do servidor
        $tempDir = sys_get_temp_dir();
        $uniqueId = uniqid('diploma_', true);
        $tempHtml = $tempDir . '/' . $uniqueId . '.html';
        $tempPdf = $tempDir . '/' . $uniqueId . '.pdf';

        file_put_contents($tempHtml, $fullHtml);

        $chromeBin = is_executable('/usr/bin/google-chrome') ? '/usr/bin/google-chrome' :
                     (is_executable('/snap/bin/chromium') ? '/snap/bin/chromium' :
                     (is_executable('/usr/bin/chromium') ? '/usr/bin/chromium' : 'google-chrome'));

        $cmd = escapeshellcmd($chromeBin) .
            ' --headless=new --no-sandbox --disable-gpu --disable-dev-shm-usage --print-to-pdf-no-header' .
            ' --landscape --paper-width=11.6929 --paper-height=8.2677' .
            ' --print-to-pdf=' . escapeshellarg($tempPdf) . ' ' . escapeshellarg($tempHtml) . ' 2>&1';

        exec($cmd, $output, $returnCode);

        if (file_exists($tempPdf) && filesize($tempPdf) > 0) {
            $pdfContent = file_get_contents($tempPdf);
            @unlink($tempHtml);
            @unlink($tempPdf);
            return $pdfContent;
        }

        // Fallback seguro: se o Chrome headless falhar, entrega o HTML com instrução de impressão
        @unlink($tempHtml);
        @unlink($tempPdf);

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="diplomas.html"');
        return $fullHtml;
    }
}
