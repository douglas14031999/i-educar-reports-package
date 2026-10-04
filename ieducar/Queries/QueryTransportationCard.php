<?php

class QueryTransportationCard extends QueryBridge
{
    protected function getDefaultData()
    {
        return [
            'curso' => 0,
            'serie' => 0,
            'turma' => 0,
            'matricula' => 0,
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
    turma.nm_turma,
    COALESCE(turma_turno.nome, 'Matutino') AS periodo,
    aluno.cod_aluno,
    matricula.cod_matricula,
    public.fcn_upper(pessoa.nome) AS nome_aluno,
    to_char(fisica.data_nasc, 'DD/MM/YYYY') AS data_nasc,
    fisica.sexo,
    fisica.cpf,
    COALESCE(rota.descricao, 'Linha Geral de Transporte') AS nm_rota,
    COALESCE(ponto.descricao, 'Ponto Principal') AS nm_ponto,
    to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_emissao_doc,
    escola_ano_letivo.ano AS ano_validade
FROM pmieducar.instituicao
INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
INNER JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
INNER JOIN pmieducar.escola_ano_letivo ON (escola_ano_letivo.ref_cod_escola = escola.cod_escola)
INNER JOIN pmieducar.matricula ON (matricula.ref_ref_cod_escola = escola.cod_escola AND matricula.ano = escola_ano_letivo.ano AND matricula.ativo = 1)
INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = matricula.ref_cod_aluno AND aluno.ativo = 1)
INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
INNER JOIN cadastro.pessoa ON (pessoa.idpes = fisica.idpes)
LEFT JOIN pmieducar.matricula_turma ON (matricula_turma.ref_cod_matricula = matricula.cod_matricula AND matricula_turma.ativo = 1)
LEFT JOIN pmieducar.turma ON (turma.cod_turma = matricula_turma.ref_cod_turma AND turma.ativo = 1)
LEFT JOIN pmieducar.turma_turno ON (turma_turno.id = turma.turma_turno_id)
LEFT JOIN pmieducar.curso ON (curso.cod_curso = matricula.ref_cod_curso)
LEFT JOIN pmieducar.serie ON (serie.cod_serie = matricula.ref_ref_cod_serie)
LEFT JOIN modules.pessoa_transporte pt ON (pt.ref_idpes = aluno.ref_idpes)
LEFT JOIN modules.ponto_transporte_escolar ponto ON (ponto.cod_ponto_transporte_escolar = pt.ref_cod_ponto_transporte_escolar)
LEFT JOIN modules.rota_transporte_escolar rota ON (rota.cod_rota_transporte_escolar = pt.ref_cod_rota_transporte_escolar)
WHERE instituicao.cod_instituicao = $P{instituicao}
  AND escola.cod_escola = $P{escola}
  AND escola_ano_letivo.ano = $P{ano}
  AND (CASE WHEN $P{curso} = 0 THEN TRUE ELSE curso.cod_curso = $P{curso} END)
  AND (CASE WHEN $P{serie} = 0 THEN TRUE ELSE serie.cod_serie = $P{serie} END)
  AND (CASE WHEN $P{turma} = 0 THEN TRUE ELSE turma.cod_turma = $P{turma} END)
  AND (CASE WHEN $P{matricula} = 0 THEN TRUE ELSE matricula.cod_matricula = $P{matricula} END)
ORDER BY pessoa.nome
LIMIT 100
SQL;
    }
}
