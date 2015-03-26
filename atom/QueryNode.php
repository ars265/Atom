<?php
namespace Atom;

class QueryNode
{
    
    private $head = null;
    private $next = null;
    
    private $raw_result = null;
    
    /* SQL Constructs */
    //union
    //select
    //all
    //distinct
    //join
    //func
    //as
    //from
    //table
    //where
    //begin_group
    //end_group
    //and
    //or
    //group_by
    //having - possible alias of where?
    //order_by
    //limit - could already accept offset as param
    //offset
    
    
    /* custom functions */
    //compile - turns it into a string
    //first - returns the first element, this does not change the query
    //next - returns the results until no more rows are avilable in the result set
    //all - returns all of the records as an array of rows
    
    
}