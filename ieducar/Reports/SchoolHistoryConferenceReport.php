<?php

use iEducar\Reports\JsonDataSource;

class SchoolHistoryConferenceReport extends Portabilis_Report_ReportCore
{
    use JsonDataSource;

    public function templateName()
    {
        return 'school-history-conference';
    }

    public function requiredArgs()
    {
        $this->addRequiredArg('ano');
        $this->addRequiredArg('instituicao');
        $this->addRequiredArg('escola');
        $this->addRequiredArg('matricula');
    }

    public function getJsonData()
    {
        $query = new QuerySchoolHistoryConference();

        return [
            'main' => $query->get($this->args),
            'header' => Portabilis_Utils_Database::fetchPreparedQuery($this->getSqlHeaderReport())
        ];
    }
}
