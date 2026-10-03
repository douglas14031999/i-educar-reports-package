<?php

class QuerySchoolHistoryConference extends QueryBridge
{
    protected function getDefaultData()
    {
        return [
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
    aluno.cod_aluno,
    matricula.cod_matricula,
    public.fcn_upper(pessoa.nome) AS nome_aluno,
    to_char(fisica.data_nasc, 'DD/MM/YYYY') AS data_nasc,
    fisica.sexo,
    fisica.cpf,
    COALESCE(pai.nome, 'Não informado') AS nm_pai,
    COALESCE(mae.nome, 'Não informado') AS nm_mae,
    view_situacao.texto_situacao_simplificado AS situacao_aluno,
    to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_atual,
    public.data_para_extenso(CURRENT_DATE) AS data_extenso
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
LEFT JOIN pmieducar.curso ON (curso.cod_curso = matricula.ref_cod_curso)
LEFT JOIN pmieducar.serie ON (serie.cod_serie = matricula.ref_ref_cod_serie)
LEFT JOIN cadastro.pessoa pai ON (pai.idpes = fisica.idpes_pai)
LEFT JOIN cadastro.pessoa mae ON (mae.idpes = fisica.idpes_mae)
INNER JOIN relatorio.view_situacao ON (
    view_situacao.cod_matricula = matricula.cod_matricula 
    AND view_situacao.cod_turma = turma.cod_turma 
    AND view_situacao.sequencial = matricula_turma.sequencial
)
WHERE instituicao.cod_instituicao = $P{instituicao}
  AND escola.cod_escola = $P{escola}
  AND escola_ano_letivo.ano = $P{ano}
  AND matricula.cod_matricula = $P{matricula}
LIMIT 1
SQL;
    }
}
