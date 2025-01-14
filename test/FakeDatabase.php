<?php

require_once 'pages/IDatabase.php';

class FakeDatabase implements IDatabase
{
    public function __construct()
    {
        $this->queryCalled = false;
        $this->queryFakeResults = array();
        $this->queryFakeResultsForQuery = array();
        $this->queryLastStatement = '';
        $this->queryCalledForStatement = array();
        $this->executeCalled = false;
        $this->executeLastStatements = array();
        $this->executeLastArgs = array();
        $this->executeCalledForStatement = array();
        $this->executeFakeResultsForStatement = array();
        $this->getLastInsertIdCalled = false;
    }

    public $queryFakeResults;
    public $queryFakeResultsForQuery;
    public $queryLastStatement;
    public $queryCalled;
    public $queryCalledForStatement;
    public function query($statement)
    {
        $this->queryCalled = true;
        $this->queryLastStatement = $statement;
        if (array_key_exists($statement, $this->queryFakeResultsForQuery))
        {
            $this->queryCalledForStatement[$statement] = true;
            return $this->queryFakeResultsForQuery[$statement];
        }
        return $this->queryFakeResults;
    }

    public static function createResultRowsForColumns($columns, $data)
    {
        $rows = array();
        foreach ($data as $item)
        {
            $row = array();
            for ($i = 0; $i < count($columns); $i++)
            {
                $row[$columns[$i]] = $item[$i];
            }
            array_push($rows, $row);
        }
        return $rows;
    }

    public function execute($statement, $args)
    {
        $this->executeCalled = true;
        array_push($this->executeLastStatements, $statement);
        array_push($this->executeLastArgs, $args);
        if (array_key_exists($statement, $this->executeFakeResultsForStatement))
        {
            $this->executeCalledForStatement[$statement] = true;
            return $this->executeFakeResultsForStatement[$statement];
        }
        return $this->executeFakeResult;
    }
    public $executeCalled,
        $executeLastStatements, $executeLastArgs,
        $executeFakeResult,
        $executeCalledForStatement, $executeFakeResultsForStatement;

    public function getLastInsertId()
    {
        $this->getLastInsertIdCalled = true;
        return $this->getLastInsertIdFakeResult;
    }
    public $getLastInsertIdCalled, $getLastInsertIdFakeResult;
}
