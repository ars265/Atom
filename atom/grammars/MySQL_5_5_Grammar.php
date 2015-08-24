<?php

namespace sn\atom\grammars;

//does not currently support the following:
// DO syntax
// HANDLER syntax
// LOAD_DATA_INFILE syntax
// LOAD_XML syntax
// UPDATE (multi-table) syntax *
// INSERT (DELAYED option) syntax
// INSERT (ON DUPLICATE KEY UPDATE option) syntax
// SELECT (SQL_SMALL_RESULT, SQL_BIG_RESULT, SQL_BUFFER_RESULT, SQL_CACHE, SQL_NO_CACHE, SQL_CALC_FOUND_ROWS options)





//REPLACE syntax
/*
REPLACE [LOW_PRIORITY | DELAYED]
    [INTO] tbl_name
    [PARTITION (partition_name,...)]
    SET col_name={expr | DEFAULT}, ...

or

REPLACE [LOW_PRIORITY | DELAYED]
    [INTO] tbl_name
    [PARTITION (partition_name,...)]
    [(col_name,...)]
    SELECT ...
*/

//UPDATE syntax
/*
UPDATE [LOW_PRIORITY] [IGNORE] table_reference
    SET col_name1={expr1|DEFAULT} [, col_name2={expr2|DEFAULT}] ...
    [WHERE where_condition]
    [ORDER BY ...]
    [LIMIT row_count]
*/

class MySQL_5_7_Grammar extends \sn\atom\Grammar {

    /**
     * Default constructor that build the production rule tree.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        $rules = array();

        //for information about these rules see:
        // https://dev.mysql.com/doc/refman/5.7/en/sql-syntax-data-manipulation.html


        $starts = new \sn\atom\Rule( '', '', 'call|delete|insert|select|replace|update' );
        $rules[] = $starts;

        //call syntax section
        $call_base = new \sn\atom\Rule( 'CALL %s( %s )', 'call', array(), 1, null );
        $call_base->set_parent_rule( $starts );
        $rules[] = $call_base;

        //delete syntax section
        $delete_base = new \sn\atom\Rule( 'DELETE ', 'delete', 'low_priority|quick|ignore|from' );
        $delete_base->set_parent_rule( $starts );
        $low_priority = new \sn\atom\Rule( 'LOW_PRIORITY ', 'low_priority', 'quick|ignore|from' );
        $low_priority->set_parent_rule( $delete_base );
        $quick = new \sn\atom\Rule( 'QUICK ', 'quick', 'ignore|from' );
        $quick->set_parent_rule( $delete_base );
        $ignore = new \sn\atom\Rule( 'IGNORE ', 'ignore', 'from' );
        $ignore->set_parent_rule( $delete_base );
        $from = new \sn\atom\Rule( 'FROM %s ', 'from', 'partition|where|order_by|limit', 1, 1 );
        $from->set_parent_rule( $delete_base );
        $partition = new \sn\atom\Rule( 'PARTITION ( %s ) ', 'partition', 'where|order_by|limit', 1, null );
        $partition->set_parent_rule( $from );
        $where = new \sn\atom\Rule( 'WHERE %s ', 'where', 'order_by|limit', 1, null );
        $where->set_parent_rule( $from );
        $order_by = new \sn\atom\Rule( 'ORDER BY %s ', '', 'order_by', 1, null );
        $order_by->set_parent_rule( $from );
        $limit = new \sn\atom\Rule( 'LIMIT %d ', 'limit', array(), 1, 1 );
        $limit->set_parent_rule( $from );
        $rules[] = $delete_base;
        $rules[] = $low_priority;
        $rules[] = $quick;
        $rules[] = $ignore;
        $rules[] = $from;
        $rules[] = $partition;
        $rules[] = $where;
        $rules[] = $order_by;
        $rules[] = $limit;

        //insert syntax section
        $insert_base = new \sn\atom\Rule( 'INSERT ', 'insert', 'low_priority|high_priority|ignore|into|partition|set|columns' );
        $insert_base->set_parent_rule( $starts );
        $low_priority = new \sn\atom\Rule( 'LOW_PRIORITY ', 'low_priority', 'ignore|into|partition|set|columns' );
        $low_priority->set_parent_rule( $insert_base );
        $high_priority = new \sn\atom\Rule( 'HIGH_PRIORITY ', 'high_priority', 'ignore|into|partition|set|columns' );
        $high_priority->set_parent_rule( $insert_base );
        $ignore = new \sn\atom\Rule( 'IGNORE ', 'ignore', 'into|partition|set|columns' );
        $ignore->set_parent_rule( $insert_base );
        $into = new \sn\atom\Rule( 'INTO %s', 'into', 'partition|set|columns', 1, 1 );
        $into->set_parent_rule( $insert_base );
        $partition = new \sn\atom\Rule( 'PARTITION ( %s ) ', 'partition', 'set|columns', 1, null );
        $partition->set_parent_rule( $insert_base );
        $set = new \sn\atom\Rule( 'SET %s', 'set', 'set|default' );
        $set->set_parent_rule( $insert_base );
        $columns = new \sn\atom\Rule( '( %s ) ', 'columns', 'select' );
        $columns->set_parent_rule( $insert_base );
        $rules[] = $insert_base;
        $rules[] = $low_priority;
        $rules[] = $high_priority;
        $rules[] = $ignore;
        $rules[] = $into;
        $rules[] = $partition;
        $rules[] = $set;
        $rules[] = $columns;

        //select syntax rules
        $select_base = new \sn\atom\Rule( 'SELECT ', 'select', 'all|distinct|distinct_row|high_priority' );
        $select_base->set_parent_rule( $starts );
        $all
        $all
        $distinct
        $distinct
        $distinct_row
        $distinct_row
        $high_priority
        $high_priority








//= new \sn\atom\Rule( '', '', '' );

//SELECT syntax
/*
SELECT
    [ALL | DISTINCT | DISTINCTROW ]
      [HIGH_PRIORITY]
      [STRAIGHT_JOIN]
    select_expr [, select_expr ...]
    [FROM table_references
    [WHERE where_condition]
    [GROUP BY {col_name | expr | position}
      [ASC | DESC], ... [WITH ROLLUP]]
    [HAVING where_condition]
    [ORDER BY {col_name | expr | position}
      [ASC | DESC], ...]
    [LIMIT {[offset,] row_count | row_count OFFSET offset}]
    [PROCEDURE procedure_name(argument_list)]
    [INTO OUTFILE 'file_name' export_options
      | INTO DUMPFILE 'file_name'
      | INTO var_name [, var_name]]
    [FOR UPDATE | LOCK IN SHARE MODE]]
*/

        foreach( $rules as $rule ) {
            $this->add_production_rule( $rule );
        }
    }

    /**
     * Vaildates if the next symbol is a correct grammer match for the current
     *  symbol node.
     *
     * @access public
     * @param \sn\atom\Symbol $current
     * @param \sn\atom\Symbol $next
     * @return boolean
     */
    public function validate_symbols( \sn\atom\Symbol $current, \sn\atom\Symbol $next ) {

    }
}