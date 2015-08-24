<?php

namespace sn\atom;

/**
 * A class for building query strings.
 */
class Atom {

    /**
     * The leading node of the query
     *
     * (default value: null)
     *
     * @var null|atom\Symbol
     * @access protected
     */
    protected $head = null;

    /**
     * The ending node of the query.
     *
     * (default value: null)
     *
     * @var null|atom\Symbol
     * @access protected
     */
    protected $tail = null;

    /**
     * The builder instance to use to build the statement
     *
     * (default value: null)
     *
     * @var mixed
     * @access protected
     */
    protected $builder = null;

    /**
     * The constructor for the Atom instance.
     *
     * @access public
     * @param \sn\atom\I_Builder $builder (default: null)
     * @return void
     */
    public function __construct( $builder = null ) {

        //if this is a string class create an instance
        if ( is_string( $builder ) && class_exists( $builder ) ) {
            $builder = new $builder();
        }

        //if the builder is an instance of the builder interface set it. This
        // will not set the grammer and now it will still need set. This is the
        // reason the Atom class has the set grammar, for convenience.
        if ( $builder instanceof \sn\atom\I_Builder ) {
            $this->builder = $builder;
        }
    }

    /**
     * A magic function that constructs the next grammer defined element as part
     *  of the query.
     *
     * @access public
     * @param string $subject
     * @param array $context
     * @return \sn\atom\Atom
     */
    public function __call( $subject, $context ) {

        $new_instance = ( null === $this->head );
        $tail = null;

        if ( !$new_instance ) {
            $tail = $this->tail;
        }

        //create the new Symbol instance
        $node = new \sn\atom\Symbol( $subject, $context, null );

        $grammar = $this->builder->get_grammar();

        //find out if this is valid via the grammar
        $valid = $grammar->validate_symbols( $tail, $node );

        if ( !$valid ) {
            //throw exception
        }

        if ( $new_instance ) {

            $this->head = $node;
            $this->tail = $node;

        } else {

            //make the new Symbol the new tail
            $tail->set_lookahead( $node );
            $this->tail = $node;
        }

        //in order to make each method chainable we must always return $this
        return $this;
    }

    /**
     * Compiles the query and returns an I_Statement object
     *
     * @access public
     * @return \sn\atom\I_Statement
     */
    public function compile() {
        return $this->builder->generate_statement( $this->head );
    }

    /**
     * Returns the builder used for this Atom instance.
     *
     * @access public
     * @return null|\sn\atom\I_Builder
     */
    public function get_builder() {
        return $this->builder;
    }

    /**
     * Sets the builder instance used by the Atom instance.
     *
     * @access public
     * @param \sn\atom\I_Builder $builder
     * @return void
     */
    public function set_builder( \sn\atom\I_Builder $builder ) {
        $this->builder = $builder;
    }

    /**
     * A convenience method to set the grammar when setting the builder instance
     *  as a string during construction instead of a configured instance.
     *
     * @access public
     * @param \sn\atom\I_Grammar $grammar
     * @return void
     */
    public function set_grammar( \sn\atom\I_Grammar $grammar ) {

        if ( null !== $this->builder ) {
            $this->builder->set_grammer( $grammar );
        }
    }
}