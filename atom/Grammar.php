<?php

namespace sn\atom;

/**
 * A base class for grammars.
 *
 * @abstract
 */
abstract class Grammar implements \sn\atom\I_Grammar {

    /**
     * The production rules for the grammer.
     *
     * (default value: array())
     *
     * @var array
     * @access protected
     */
    protected $production_rules = array();

    /**
     * Adds a production rule to the grammar.
     *
     * @access public
     * @param \sn\atom\Rule $nonterminal
     * @return void
     */
    public function add_production_rule( \sn\atom\Rule $rule ) {

        $representation = strtolower( $rule->get_representation() );
        $node = null;

        //this ensures that each production rules representation is unique as it
        // consists of the entire tree of parents paths
        while ( null !== ( $node = $rule->get_parent_rule() ) ) {

            $name = $node->get_representation();

            if ( !empty( $name ) ) {
                $representation = strtolower( $name ) . '_' . $representation;
            }
        }

        $this->production_rules[ $representation ] = $rule;
    }

    /**
     * Returns the production rules associated with this grammar instance.
     *
     * @access public
     * @return array
     */
    public function get_production_rules() {
        return array_values( $this->production_rules );
    }


    /**
     * Vaildates if the next symbol is a correct grammer match for the current
     *  symbol node.
     *
     * @access public
     * @abstract
     * @param \sn\atom\Symbol $current
     * @param \sn\atom\Symbol $next
     * @return void
     */
    abstract public function validate_symbols( \sn\atom\Symbol $current, \sn\atom\Symbol $next );
}