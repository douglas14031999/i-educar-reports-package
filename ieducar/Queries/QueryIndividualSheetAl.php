<?php

class QueryIndividualSheetAl extends QueryBridge
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
    instituicao.cidade AS cidade_instituicao,
    escola.cod_escola,
    COALESCE(
        (SELECT j.fantasia FROM cadastro.juridica j WHERE j.idpes = escola.ref_idpes LIMIT 1),
        pessoa_escola.nome,
        relatorio.get_nome_escola(escola.cod_escola)
    ) AS nm_escola,
    curso.nm_curso,
    serie.nm_serie,
    turma.cod_turma,
    turma.nm_turma,
    COALESCE(turma_turno.nome, 'Não informado') AS periodo,
    aluno.cod_aluno,
    matricula.cod_matricula,
    public.fcn_upper(pessoa.nome) AS nome_aluno,
    to_char(fisica.data_nasc, 'DD/MM/YYYY') AS data_nasc,
    fisica.sexo,
    COALESCE(pai.nome, 'Não informado') AS nm_pai,
    COALESCE(mae.nome, 'Não informado') AS nm_mae,
    view_situacao.texto_situacao_simplificado AS situacao_aluno,
    componente_curricular.id AS cod_disciplina,
    componente_curricular.nome AS nm_disciplina,
    componente_curricular.abreviatura AS sigla_disciplina,
    componente_curricular.ordenamento,
    COALESCE(replace(trunc(nota1.nota::numeric, 1)::TEXT, '.', ','), '-') AS nota1,
    COALESCE(replace(trunc(nota2.nota::numeric, 1)::TEXT, '.', ','), '-') AS nota2,
    COALESCE(replace(trunc(nota3.nota::numeric, 1)::TEXT, '.', ','), '-') AS nota3,
    COALESCE(replace(trunc(nota4.nota::numeric, 1)::TEXT, '.', ','), '-') AS nota4,
    COALESCE(replace(trunc(nccm.media_arredondada::numeric, 1)::TEXT, '.', ','), '-') AS media_anual,
    COALESCE(replace(trunc(nota_rec.nota::numeric, 1)::TEXT, '.', ','), '-') AS nota_recuperacao,
    COALESCE(replace(trunc(nccm.media_arredondada::numeric, 1)::TEXT, '.', ','), '-') AS media_final,
    COALESCE(falta_disciplina.total_faltas, 0) AS total_faltas_disciplina,
    to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_atual,
    public.data_para_extenso(CURRENT_DATE) AS data_extenso,
    COALESCE((SELECT fcn_upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_gestor = p.idpes LIMIT 1), 'Direção Escolar') AS gestor_escolar,
    COALESCE((SELECT fcn_upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_secretario_escolar = p.idpes LIMIT 1), 'Secretaria Escolar') AS secretario_escolar
FROM pmieducar.instituicao
INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
LEFT JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
LEFT JOIN pmieducar.escola_ano_letivo ON (escola_ano_letivo.ref_cod_escola = escola.cod_escola AND escola_ano_letivo.ano = $P{ano})
INNER JOIN pmieducar.matricula ON (matricula.ref_ref_cod_escola = escola.cod_escola AND matricula.ano = $P{ano} AND matricula.ativo = 1)
INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = matricula.ref_cod_aluno AND aluno.ativo = 1)
INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
INNER JOIN cadastro.pessoa ON (pessoa.idpes = fisica.idpes)
INNER JOIN pmieducar.matricula_turma ON (matricula_turma.ref_cod_matricula = matricula.cod_matricula AND matricula_turma.ativo = 1)
INNER JOIN pmieducar.turma ON (turma.cod_turma = matricula_turma.ref_cod_turma AND turma.ativo = 1)
LEFT JOIN pmieducar.turma_turno ON (turma_turno.id = turma.turma_turno_id)
LEFT JOIN pmieducar.curso ON (curso.cod_curso = matricula.ref_cod_curso)
LEFT JOIN pmieducar.serie ON (serie.cod_serie = matricula.ref_ref_cod_serie)
LEFT JOIN cadastro.pessoa pai ON (pai.idpes = fisica.idpes_pai)
LEFT JOIN cadastro.pessoa mae ON (mae.idpes = fisica.idpes_mae)
INNER JOIN relatorio.view_situacao ON (
    view_situacao.cod_matricula = matricula.cod_matricula 
    AND view_situacao.cod_turma = turma.cod_turma 
    AND view_situacao.sequencial = matricula_turma.sequencial
)
LEFT JOIN relatorio.view_componente_curricular componente_curricular ON (componente_curricular.cod_turma = turma.cod_turma)
LEFT JOIN modules.nota_aluno ON (nota_aluno.matricula_id = matricula.cod_matricula)
LEFT JOIN modules.nota_componente_curricular nota1 ON (nota1.nota_aluno_id = nota_aluno.id AND nota1.componente_curricular_id = componente_curricular.id AND nota1.etapa = '1')
LEFT JOIN modules.nota_componente_curricular nota2 ON (nota2.nota_aluno_id = nota_aluno.id AND nota2.componente_curricular_id = componente_curricular.id AND nota2.etapa = '2')
LEFT JOIN modules.nota_componente_curricular nota3 ON (nota3.nota_aluno_id = nota_aluno.id AND nota3.componente_curricular_id = componente_curricular.id AND nota3.etapa = '3')
LEFT JOIN modules.nota_componente_curricular nota4 ON (nota4.nota_aluno_id = nota_aluno.id AND nota4.componente_curricular_id = componente_curricular.id AND nota4.etapa = '4')
LEFT JOIN modules.nota_componente_curricular nota_rec ON (nota_rec.nota_aluno_id = nota_aluno.id AND nota_rec.componente_curricular_id = componente_curricular.id AND nota_rec.etapa = 'Rc')
LEFT JOIN modules.nota_componente_curricular_media nccm ON (nccm.nota_aluno_id = nota_aluno.id AND nccm.componente_curricular_id = componente_curricular.id)
LEFT JOIN (
    SELECT f.matricula_id, f.componente_curricular_id, SUM(f.quantidade) AS total_faltas
    FROM modules.falta_aluno fa
    INNER JOIN modules.falta_componente_curricular f ON (f.falta_aluno_id = fa.id)
    GROUP BY f.matricula_id, f.componente_curricular_id
) falta_disciplina ON (falta_disciplina.matricula_id = matricula.cod_matricula AND falta_disciplina.componente_curricular_id = componente_curricular.id)
WHERE instituicao.cod_instituicao = $P{instituicao}
  AND (CASE WHEN $P{escola} = 0 THEN TRUE ELSE escola.cod_escola = $P{escola} END)
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
  ORDER BY pessoa.nome, componente_curricular.ordenamento, componente_curricular.nome
SQL;
    }
}
