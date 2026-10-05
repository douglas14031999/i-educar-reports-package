<?php

class QueryDiplomaCertificate extends QueryBridge
{
    /**
     * @inheritdoc
     */
    protected function getDefaultData()
    {
        return [
            'ano' => 0,
            'instituicao' => 0,
            'escola' => 0,
            'curso' => 0,
            'serie' => 0,
            'turma' => 0,
            'matricula' => 0,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function query()
    {
        return <<<'SQL'
SELECT 
    instituicao.cod_instituicao,
    escola.cod_escola,
    pessoa_escola.nome AS nome_escola,
    curso.cod_curso,
    curso.nm_curso AS nome_curso,
    serie.cod_serie,
    serie.nm_serie AS nome_serie,
    serie.carga_horaria,
    turma.cod_turma,
    turma.nm_turma AS nome_turma,
    aluno.cod_aluno,
    pessoa_aluno.nome AS nome_aluno,
    fisica.sexo,
    to_char(fisica.data_nasc, 'DD/MM/YYYY') AS data_nascimento,
    CASE 
        WHEN fisica.cpf IS NOT NULL AND length(lpad(fisica.cpf::varchar, 11, '0')) = 11 THEN 
            substr(lpad(fisica.cpf::varchar, 11, '0'), 1, 3) || '.' ||
            substr(lpad(fisica.cpf::varchar, 11, '0'), 4, 3) || '.' ||
            substr(lpad(fisica.cpf::varchar, 11, '0'), 7, 3) || '-' ||
            substr(lpad(fisica.cpf::varchar, 11, '0'), 10, 2)
        ELSE 'Não informado'
    END AS cpf_aluno,
    CASE fisica.nacionalidade
        WHEN 1 THEN 'Brasileira'
        WHEN 2 THEN 'Naturalizada brasileira'
        WHEN 3 THEN 'Estrangeira'
        ELSE 'Brasileira'
    END AS nacionalidade,
    COALESCE(mun_nasc.nome || ' – ' || mun_nasc.sigla_uf, 'Não informada') AS naturalidade,
    COALESCE(pessoa_pai.nome, fisica.nome_pai, 'Não informado') AS nome_pai,
    COALESCE(pessoa_mae.nome, fisica.nome_mae, 'Não informada') AS nome_mae,
    matricula.cod_matricula,
    matricula.ano AS ano_conclusao,
    matricula.aprovado
FROM pmieducar.instituicao
INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
INNER JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
INNER JOIN pmieducar.matricula ON (matricula.ref_ref_cod_escola = escola.cod_escola AND (matricula.ano = $P{ano} OR $P{ano} = 0) AND matricula.ativo = 1)
INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = matricula.ref_cod_aluno AND aluno.ativo = 1)
INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
INNER JOIN cadastro.pessoa pessoa_aluno ON (pessoa_aluno.idpes = fisica.idpes)
LEFT JOIN cadastro.pessoa pessoa_pai ON (pessoa_pai.idpes = fisica.idpes_pai)
LEFT JOIN cadastro.pessoa pessoa_mae ON (pessoa_mae.idpes = fisica.idpes_mae)
LEFT JOIN public.municipio mun_nasc ON (mun_nasc.idmun = fisica.idmun_nascimento)
INNER JOIN pmieducar.matricula_turma ON (matricula_turma.ref_cod_matricula = matricula.cod_matricula AND matricula_turma.ativo = 1)
INNER JOIN pmieducar.turma ON (turma.cod_turma = matricula_turma.ref_cod_turma AND turma.ativo = 1)
INNER JOIN pmieducar.curso ON (curso.cod_curso = turma.ref_cod_curso)
INNER JOIN pmieducar.serie ON (serie.cod_serie = turma.ref_ref_cod_serie)
WHERE (instituicao.cod_instituicao = $P{instituicao} OR $P{instituicao} = 0)
  AND (escola.cod_escola = $P{escola} OR $P{escola} = 0)
  AND (curso.cod_curso = $P{curso} OR $P{curso} = 0)
  AND (serie.cod_serie = $P{serie} OR $P{serie} = 0)
  AND (turma.cod_turma = $P{turma} OR $P{turma} = 0)
  AND (matricula.cod_matricula = $P{matricula} OR $P{matricula} = 0)
  AND matricula_turma.sequencial = (
      SELECT MAX(mt.sequencial) 
      FROM pmieducar.matricula_turma mt 
      WHERE mt.ref_cod_matricula = matricula.cod_matricula 
        AND mt.ativo = 1
  )
ORDER BY 
    curso.nm_curso ASC,
    serie.nm_serie ASC,
    turma.nm_turma ASC,
    public.fcn_upper_nrm(pessoa_aluno.nome) ASC
SQL;
    }
}
