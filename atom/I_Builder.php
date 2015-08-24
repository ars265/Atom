<?php

namespace sn\atom;

/**
 * A contract for concrete builders.
 */
interface I_Builder {

    /**
     * Requires a default constructor.
     *
     * @access public
     * @return void
     */
    public function __construct();

    /**
     * Returns the grammer associated with this builder instance.
     *
     * @access public
     * @return null|\sn\atom\I_Grammar
     */
    public function get_grammer();

    /**
     * Sets the grammer associated with this builder instance.
     *
     * @access public
     * @param \sn\atom\I_Grammar $grammar
     * @return void
     */
    public function set_grammer( \sn\atom\I_Grammar $grammar );

    /**
     * Generates an I_Statement from the head symbol node.
     *
     * @access public
     * @param \sn\atom\Symbol $head
     * @return \sn\atom\I_Statement
     */
    public function generate_statement( \sn\atom\Symbol $head );

    //string escaping should be added
}