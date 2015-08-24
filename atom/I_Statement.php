<?php

namespace sn\atom;

/**
 * A contract for Statement types.
 */
interface I_Statement {

    /**
     * The constructor for the statement.
     *
     * @access public
     * @param \sn\atom\Symbol $node
     * @param string $query
     * @return void
     */
    public function __construct( \sn\atom\Symbol $node, $query );

    /**
     * Returns the statement as a string.
     *
     * @access public
     * @return string
     */
    public function get_query();
}