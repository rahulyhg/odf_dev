<?php

namespace SqlParser\Tests\Builder;

use SqlParser\Components\OptionsArray;
use SqlParser\Components\Expression;
use SqlParser\Components\Condition;
use SqlParser\Components\Limit;
use SqlParser\Statements\SelectStatement;

use SqlParser\Tests\TestCase;

class StatementTest extends TestCase
{

    public function testBuilder()
    {
        $stmt = new SelectStatement();

        $stmt->options = new OptionsArray(array('DISTINCT'));

        $stmt->expr[] = new Expression('sakila', 'film', 'film_id', 'fid');
        $stmt->expr[] = new Expression('COUNT(film_id)');

        $stmt->from[] = new Expression('', 'film', '');
        $stmt->from[] = new Expression('', 'actor', '');

        $stmt->where[] = new Condition('film_id > 10');
        $stmt->where[] = new Condition('OR');
        $stmt->where[] = new Condition('actor.age > 25');

        $stmt->limit = new Limit(1, 10);

        $this->assertEquals(
            'SELECT DISTINCT `sakila`.`film`.`film_id` AS `fid`, COUNT(film_id) ' .
            'FROM `film`, `actor` ' .
            'WHERE film_id > 10 OR actor.age > 25 ' .
            'LIMIT 10, 1 ',
            (string) $stmt
        );
    }
}
