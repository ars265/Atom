<?php
namespace Atom;

/**
 *
 * https://dev.mysql.com/doc/refman/4.1/en/select.html
 * https://dev.mysql.com/doc/refman/4.1/en/expressions.html
 *
 *https://dev.mysql.com/doc/refman/5.0/en/comparison-operators.html
 *
 */
class QueryNode
{

    private $head = null;
    private $next = null;
    private $previous = null;
    private $provider = null;

    private $compiled = false;
    private $partial_query = '';
    private $raw_result = null;


    //NEED TO FIX REPETITION PROBLEM THAT WOULD USE COMMAs


    public function __construct( $head = null, $previous = null, $provider = null ) {

        if ( null === $head ) {

            $this->head = $this;

        } else {

            $this->head = $head;
        }

        if ( null !== $previous ) {

            $this->previous = $previous;
        }

        if ( null !== $provider ) {

            $this->provider = $provider;
        }
    }

    protected function add_next() {

        $this->next = new QueryNode( $this->head, $this );
    }

    protected function ensure_not_compiled() {

        if ( $this->compiled ) {
            throw new Exception( 'A query can not be modified after being compiled.' );
        }
    }

    //and_group - ?reserved word and_where, and_having
    protected function and_group( $condition ) {

        $this->add_next();

        $partial_query = 'AND ' . trim( $condition );

        return $this->next;
    }

    //or_group - ?reserved word or_where, or_having
    protected function or_group( $condition ) {

        $this->add_next();

        $partial_query = 'OR ' . trim( $condition );

        return $this->next;
    }


    /* SQL Constructs */

    //select - followed by all, distinct, perform_function, and column
    public function select( $what = null ) {

        $this->add_next();

        if ( null === $what ) {

            $partial_query = 'SELECT';

        } else {

            $partial_query = 'SELECT ' . trim( $what );
        }

        return $this->next;
    }

    //all - followed by from
    public function all() {

        $this->add_next();

        $partial_query = 'ALL';

        return $this->next;
    }

    //distinct - followed by from
    public function distinct() {

        $this->add_next();

        $partial_query = 'DISTINCT';

        return $this->next;
    }

    //from - used to specify tables, could optionally use from()->table('table1')->as_alias('tbl1')->table
    //from - followed by table, where
    public function from( $where = null ) {

        $this->add_next();

        if ( null === $where ) {

            $partial_query = 'FROM';

        } else {

            $partial_query = 'FROM ' . trim( $where );
        }

        return $this->next;
    }

    //as - as was not used because it's a reserved word
    //as_alias followed by table, or on,  needs to add comma if multiple iterations
    public function as_alias( $alias ) {

        $this->add_next();

        $partial_query = 'AS ' . trim( $alias );

        return $this->next;
    }

    //func - can be after select or having, followed by as_alias or from
    public function perform_function( $fn ) {

        $this->add_next();

        $partial_query = trim( $fn );

        return $this->next;
    }

    //join - followed by table, as_alias, or on
    public function join( $type = 0, $where = null ) {

        $this->add_next();

        switch ( $type ) {

            case Join::LEFT:

                $partial_query = 'LEFT JOIN';
                break;

            case Join::RIGHT:

                $partial_query = 'RIGHT JOIN';
                break;

            case Join::INNER:

                $partial_query = 'INNER JOIN';
                break;

            case Join::STRAIGHT:

                $partial_query = 'STRAIGHT_JOIN';
                break;

            case Join::LEFT_OUTER:

                $partial_query = 'LEFT OUTER JOIN';
                break;

            case Join::RIGHT_OUTER:

                $partial_query = 'RIGHT OUTER JOIN';
                break;
        }

        if ( null !== $where ) {
            $partial_query .= ( ' ' . trim( $where ) );
        }

        return $this->next;
    }

    //on - followed by where, or join
    public function on( $condition ) {

        $this->add_next();

        $partial_query = trim( $condition );

        return $this->next;
    }

    //where - followed by and_where, or_where, begin_group, limit
    public function where( $condition = null ) {

        $this->add_next();

        if ( null === $condition ) {

            $partial_query = 'WHERE';

        } else {

            $partial_query = 'WHERE ' . trim( $condition );
        }

        return $this->next;
    }

    public function and_where( $condition ) {

        return $this->and_group( $condition );
    }

    public function or_where( $condition ) {

        return $this0>or_group( $condition );
    }

    //limit - could already accept offset as param
    public function limit( $limit, $offset = null ) {

        $this->add_next();

        $partial_query = 'LIMIT ';

        if ( null !== $offset ) {
            $partial_query .= ( intval( $offset ) . ', ' );
        }

        $partial_query .= intval( $limit );

        return $this->next;
    }

    //offset
    public function offset( $offset ) {

        $this->add_next();

        $partial_query = 'OFFSET ' . intval( $offset );

        return $this->next;
    }

    //begin_group
    public function begin_group( $condition = null ) {

        $this->add_next();

        if ( null === $condition ) {

            $partial_query = '(';

        } else {

            $partial_query = '( ' . trim( $condition );
        }

        return $this->next;
    }

    //end_group
    public function end_group() {

        $this->add_next();

        $partial_query = ')';

        return $this->next;
    }






















    //waiting on these
    //group_by
    //having - possible alias of where?
    //order_by
    //union

    /* query operators */
    //https://dev.mysql.com/doc/refman/5.0/en/comparison-operators.html
    //BETWEEN ... AND ...


    /* custom functions */
    //table - followed by as_alias, where
    public function table( $table ) {

        $this->add_next();

        $partial_query = '`' . $table . '`';

        return $this->next;
    }

    //column - need to add comma if multiple columns in a row
    public function column( $column ) {

        $this->add_next();

        $partial_query = '`' . $column . '`';

        return $this->next;
    }

    //compile - turns it into a string
    public function compile() {

        if ( $this->compiled ) {
            return $this;
        }

        if ( $this !== $this->head ) {
            $this->head->compile();
        }

        //trim each piece and join using space
        return $this;
    }

    //first - returns the first element, this does not change the query
    public function first() {


    }

    //next - returns the results until no more rows are avilable in the result set
    public function next() {


    }

    //all - returns all of the records as an array of rows
    public function all() {


    }

    //scalar - returns a scalar value such as the value returned from the SQL function COUNT.
    public function scalar() {


    }

    /*
     *
     * Examples
     *
     */

    /* Simple Select */
    //

    /* simple scalar (count) query */
    //select()->perform_function( 'COUNT( id )' )->as_alias( 'count' )->from( 'myTable' )->where( 'someColumn = 5' )->scalar();


    //select( 'A.column' )->from()->table( 'myTable' )->as_alias( 'A' )->where()->begin_group( 'column LIKE \'%me%\'' )->and_where( 'column2 = 1' )->end_group()

}