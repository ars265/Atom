<?php

namespace sn\atom;

/**
 * A basic implementation for the statement interface.
 */
class Statement implements \sn\atom\I_Statement {

    /**
     * The lead node that generated the statement and query.
     *
     * (default value: null)
     *
     * @var null|\sn\atom\Symbol
     * @access protected
     */
    protected $head = null;

    /**
     * The query generated from comiling the statement nodes
     *
     * (default value: '')
     *
     * @var string
     * @access protected
     */
    protected $query = '';


    /**
     * The constructor for the statement.
     *
     * @access public
     * @param \sn\atom\Symbol $node
     * @param string $query
     * @return void
     */
    public function __construct( \sn\atom\Symbol $node, $query ) {

        $this->head = $node;
        $this->query = $query;
    }

    /**
     * Returns the statement as a string.
     *
     * @access public
     * @return string
     */
    public function get_query() {
        return $this->query;
    }
}