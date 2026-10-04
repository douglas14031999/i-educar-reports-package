<?php

class QueryTeacherReceiptStub extends QueryBridge
{
    protected function getDefaultData()
    {
        return [
            'curso' => 0,
            'serie' => 0,
            'turma' => 0,
        ];
    }

    protected function query()
    {
        return <<<'SQL'
SELECT 
    instituicao.cod_instituicao,
    public.fcn_upper(instituicao.nm_instituicao) AS nm_instituicao,
    public.fcn_upper(instituicao.nm_responsavel) AS nm_responsavel,
    escola.cod_escola,
    pessoa_escola.nome AS nm_escola,
    curso.nm_curso,
    serie.nm_serie,
    turma.cod_turma,
    turma.nm_turma,
    COALESCE(turma_turno.nome, 'Não informado') AS periodo,
    COALESCE(pessoa_servidor.nome, 'Docente da Turma') AS nm_docente,
    COALESCE(componente_curricular.nome, 'Geral') AS nm_disciplina,
    escola_ano_letivo.ano,
    to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_atual,
    public.data_para_extenso(CURRENT_DATE) AS data_extenso
FROM pmieducar.instituicao
INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
INNER JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
INNER JOIN pmieducar.escola_ano_letivo ON (escola_ano_letivo.ref_cod_escola = escola.cod_escola)
INNER JOIN pmieducar.turma ON (turma.ref_ref_cod_escola = escola.cod_escola AND turma.ano = escola_ano_letivo.ano AND turma.ativo = 1)
LEFT JOIN pmieducar.turma_turno ON (turma_turno.id = turma.turma_turno_id)
LEFT JOIN pmieducar.curso ON (curso.cod_curso = turma.ref_cod_curso)
LEFT JOIN pmieducar.serie ON (serie.cod_serie = turma.ref_ref_cod_serie)
LEFT JOIN relatorio.view_componente_curricular componente_curricular ON (componente_curricular.cod_turma = turma.cod_turma)
LEFT JOIN pmieducar.servidor_alocacao ON (servidor_alocacao.ref_cod_escola = escola.cod_escola AND servidor_alocacao.ano = turma.ano)
LEFT JOIN pmieducar.servidor ON (servidor.cod_servidor = servidor_alocacao.ref_cod_servidor)
LEFT JOIN cadastro.pessoa pessoa_servidor ON (pessoa_servidor.idpes = servidor.cod_servidor)
WHERE instituicao.cod_instituicao = $P{instituicao}
  AND escola.cod_escola = $P{escola}
  AND escola_ano_letivo.ano = $P{ano}
  AND (CASE WHEN $P{curso} = 0 THEN TRUE ELSE curso.cod_curso = $P{curso} END)
  AND (CASE WHEN $P{serie} = 0 THEN TRUE ELSE serie.cod_serie = $P{serie} END)
  AND (CASE WHEN $P{turma} = 0 THEN TRUE ELSE turma.cod_turma = $P{turma} END)
GROUP BY 
    instituicao.cod_instituicao,
    instituicao.nm_instituicao,
    instituicao.nm_responsavel,
    escola.cod_escola,
    pessoa_escola.nome,
    curso.nm_curso,
    serie.nm_serie,
    turma.cod_turma,
    turma.nm_turma,
    turma_turno.nome,
    pessoa_servidor.nome,
    componente_curricular.nome,
    escola_ano_letivo.ano
ORDER BY turma.nm_turma, pessoa_servidor.nome
LIMIT 100
SQL;
    }
}
