<?php

class QueryClassRecordBackCover extends QueryBridge
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
    instituicao.cidade AS cidade_instituicao,
    escola.cod_escola,
    pessoa_escola.nome AS nm_escola,
    curso.nm_curso,
    serie.nm_serie,
    turma.cod_turma,
    turma.nm_turma,
    COALESCE(turma_turno.nome, 'Não informado') AS periodo,
    escola_ano_letivo.ano,
    COUNT(DISTINCT matricula.cod_matricula) AS total_matriculados,
    COUNT(DISTINCT CASE WHEN view_situacao.cod_situacao = 1 THEN matricula.cod_matricula END) AS total_aprovados,
    COUNT(DISTINCT CASE WHEN view_situacao.cod_situacao = 2 THEN matricula.cod_matricula END) AS total_reprovados,
    COUNT(DISTINCT CASE WHEN view_situacao.cod_situacao = 4 THEN matricula.cod_matricula END) AS total_transferidos,
    COUNT(DISTINCT CASE WHEN view_situacao.cod_situacao = 6 THEN matricula.cod_matricula END) AS total_abandonos,
    to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_atual,
    public.data_para_extenso(CURRENT_DATE) AS data_extenso,
    (SELECT fcn_upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_gestor = p.idpes LIMIT 1) AS gestor_escolar,
    (SELECT fcn_upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_secretario_escolar = p.idpes LIMIT 1) AS secretario_escolar
FROM pmieducar.instituicao
INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
INNER JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
INNER JOIN pmieducar.escola_ano_letivo ON (escola_ano_letivo.ref_cod_escola = escola.cod_escola)
INNER JOIN pmieducar.turma ON (turma.ref_ref_cod_escola = escola.cod_escola AND turma.ano = escola_ano_letivo.ano AND turma.ativo = 1)
LEFT JOIN pmieducar.turma_turno ON (turma_turno.id = turma.turma_turno_id)
LEFT JOIN pmieducar.curso ON (curso.cod_curso = turma.ref_cod_curso)
LEFT JOIN pmieducar.serie ON (serie.cod_serie = turma.ref_ref_cod_serie)
LEFT JOIN pmieducar.matricula_turma ON (matricula_turma.ref_cod_turma = turma.cod_turma AND matricula_turma.ativo = 1)
LEFT JOIN pmieducar.matricula ON (matricula.cod_matricula = matricula_turma.ref_cod_matricula AND matricula.ano = turma.ano AND matricula.ativo = 1)
LEFT JOIN relatorio.view_situacao ON (
    view_situacao.cod_matricula = matricula.cod_matricula 
    AND view_situacao.cod_turma = turma.cod_turma 
    AND view_situacao.sequencial = matricula_turma.sequencial
)
WHERE instituicao.cod_instituicao = $P{instituicao}
  AND escola.cod_escola = $P{escola}
  AND escola_ano_letivo.ano = $P{ano}
  AND turma.cod_turma = $P{turma}
GROUP BY 
    instituicao.cod_instituicao,
    instituicao.nm_instituicao,
    instituicao.nm_responsavel,
    instituicao.cidade,
    escola.cod_escola,
    pessoa_escola.nome,
    curso.nm_curso,
    serie.nm_serie,
    turma.cod_turma,
    turma.nm_turma,
    turma_turno.nome,
    escola_ano_letivo.ano,
    escola.ref_idpes_gestor,
    escola.ref_idpes_secretario_escolar
SQL;
    }
}
