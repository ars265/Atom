<?php

namespace sn\atom;

/**
 * A base class for builders.
 *
 * @abstract
 */
abstract class Builder implements \sn\atom\I_Builder {

    /**
     * The grammar used by the builder
     *
     * (default value: null)
     *
     * @var null|\sn\atom\I_Grammar
     * @access protected
     */
    protected $grammar = null;

    /**
     * Empty default constructor.
     *
     * @access public
     * @return void
     */
    public function __construct() { }

    /**
     * Returns the grammer associated with this builder instance.
     *
     * @access public
     * @return null|\sn\atom\I_Grammar
     */
    public function get_grammer() {
        return $this->grammar;
    }

    /**
     * Sets the grammer associated with this builder instance.
     *
     * @access public
     * @param \sn\atom\I_Grammar $grammar
     * @return void
     */
    public function set_grammer( \sn\atom\I_Grammar $grammar ) {
        $this->grammar = $grammar;
    }

    /**
     * Generates an I_Statement from the head symbol node.
     *
     * @access public
     * @abstract
     * @param \sn\atom\Symbol $head
     * @return void
     */
    abstract public function generate_statement( \sn\atom\Symbol $head );
}