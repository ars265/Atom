<?php

namespace sn\atom;

/**
 * The contract for grammars.
 */
interface I_Grammar {

    /**
     * Adds a production rule to the grammar.
     *
     * @access public
     * @param \sn\atom\Rule $rule
     * @return void
     */
    public function add_production_rule( \sn\atom\Rule $rule );

    /**
     * Returns the production rules associated with this grammar instance.
     *
     * @access public
     * @return array
     */
    public function get_production_rules();

    /**
     * Vaildates if the next symbol is a correct grammer match for the current
     *  symbol node.
     *
     * @access public
     * @param \sn\atom\Symbol $current
     * @param \sn\atom\Symbol $next
     * @return boolean
     */
    public function validate_symbols( \sn\atom\Symbol $current, \sn\atom\Symbol $next );
}