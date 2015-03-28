<?php
namespace Atom;

interface IProvider
{
    /**
     * The constructor for the provider. Passed settings in an array if needed
     *  for connection building or other options.
     *
     * @access public
     * @param Array $settings
     * @return void
     */
    public function __construct( $settings = array() );


    /**
     * Escapse a string to return a query safe value.
     *
     * @access public
     * @param String $string
     * @return String The escaped string
     */
    public function escape_string( $string );


    /**
     * Prepares a query for safe execution.
     *
     * @access public
     * @param String $query
     * @return Resource
     */
    public function prepare_query( $query );


    /**
     * Executes a query against a specified connection. The connection
     *  information can not change after the initialization of the constructor.
     *
     * @access public
     * @param String $query
     * @return mixed The resource or result from the query.
     */
    public function execute_query( $query );

    public function get_scalar( $resource );
    public function get_next( $resource );


    /**
     * get_all function.
     *
     * @access public
     * @param mixed $resource
     * @return Array|false
     */
    public function get_all( $resource );

    public function get_last_query();
    public function get_last_error();
}