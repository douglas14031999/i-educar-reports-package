<?php

class QueryIndividualSheetEja extends QueryBridge
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
    COALESCE(turma_turno.nome, 'Noturno') AS periodo,
    aluno.cod_aluno,
    matricula.cod_matricula,
    public.fcn_upper(pessoa.nome) AS nome_aluno,
    to_char(fisica.data_nasc, 'DD/MM/YYYY') AS data_nasc,
    fisica.sexo,
    fisica.cpf,
    view_situacao.texto_situacao_simplificado AS situacao_aluno,
    componente_curricular.id AS cod_disciplina,
    componente_curricular.nome AS nm_disciplina,
    componente_curricular.ordenamento,
    COALESCE(replace(trunc(nccm.media_arredondada::numeric, 1)::TEXT, '.', ','), '-') AS media_final,
    COALESCE(falta_disciplina.total_faltas, 0) AS total_faltas,
    to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_atual,
    public.data_para_extenso(CURRENT_DATE) AS data_extenso,
    (SELECT fcn_upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_gestor = p.idpes LIMIT 1) AS gestor_escolar,
    (SELECT fcn_upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_secretario_escolar = p.idpes LIMIT 1) AS secretario_escolar
FROM pmieducar.instituicao
INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
INNER JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
INNER JOIN pmieducar.escola_ano_letivo ON (escola_ano_letivo.ref_cod_escola = escola.cod_escola)
INNER JOIN pmieducar.matricula ON (matricula.ref_ref_cod_escola = escola.cod_escola AND matricula.ano = escola_ano_letivo.ano AND matricula.ativo = 1)
INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = matricula.ref_cod_aluno AND aluno.ativo = 1)
INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
INNER JOIN cadastro.pessoa ON (pessoa.idpes = fisica.idpes)
INNER JOIN pmieducar.matricula_turma ON (matricula_turma.ref_cod_matricula = matricula.cod_matricula AND matricula_turma.ativo = 1)
INNER JOIN pmieducar.turma ON (turma.cod_turma = matricula_turma.ref_cod_turma AND turma.ativo = 1)
LEFT JOIN pmieducar.turma_turno ON (turma_turno.id = turma.turma_turno_id)
LEFT JOIN pmieducar.curso ON (curso.cod_curso = matricula.ref_cod_curso)
LEFT JOIN pmieducar.serie ON (serie.cod_serie = matricula.ref_ref_cod_serie)
INNER JOIN relatorio.view_situacao ON (
    view_situacao.cod_matricula = matricula.cod_matricula 
    AND view_situacao.cod_turma = turma.cod_turma 
    AND view_situacao.sequencial = matricula_turma.sequencial
)
LEFT JOIN relatorio.view_componente_curricular componente_curricular ON (componente_curricular.cod_turma = turma.cod_turma)
LEFT JOIN modules.nota_aluno ON (nota_aluno.matricula_id = matricula.cod_matricula)
LEFT JOIN modules.nota_componente_curricular_media nccm ON (nccm.nota_aluno_id = nota_aluno.id AND nccm.componente_curricular_id = componente_curricular.id)
LEFT JOIN (
    SELECT f.matricula_id, f.componente_curricular_id, SUM(f.quantidade) AS total_faltas
    FROM modules.falta_aluno fa
    INNER JOIN modules.falta_componente_curricular f ON (f.falta_aluno_id = fa.id)
    GROUP BY f.matricula_id, f.componente_curricular_id
) falta_disciplina ON (falta_disciplina.matricula_id = matricula.cod_matricula AND falta_disciplina.componente_curricular_id = componente_curricular.id)
WHERE instituicao.cod_instituicao = $P{instituicao}
  AND escola.cod_escola = $P{escola}
  AND escola_ano_letivo.ano = $P{ano}
  AND (CASE WHEN $P{curso} = 0 THEN TRUE ELSE curso.cod_curso = $P{curso} END)
  AND (CASE WHEN $P{serie} = 0 THEN TRUE ELSE serie.cod_serie = $P{serie} END)
  AND (CASE WHEN $P{turma} = 0 THEN TRUE ELSE turma.cod_turma = $P{turma} END)
  AND (CASE WHEN $P{matricula} = 0 THEN TRUE ELSE matricula.cod_matricula = $P{matricula} END)
  AND matricula_turma.sequencial = (
      SELECT MAX(mt.sequencial)
      FROM pmieducar.matricula_turma mt
      WHERE mt.ref_cod_matricula = matricula.cod_matricula
        AND mt.ref_cod_turma = turma.cod_turma
  )
  AND NOT public.verifica_existe_matricula_posterior_mesma_turma(view_situacao.cod_matricula, view_situacao.cod_turma)
ORDER BY pessoa.nome, componente_curricular.ordenamento, componente_curricular.nome
SQL;
    }
}
