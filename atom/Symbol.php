<?php

namespace sn\atom;

/**
 * A symbol used to generate statements. This is a generic node type class.
 */
class Symbol {

    /**
     * The subject of the symbol, this is equivalent to the name or representation
     *  of the grammar production rule.
     *
     * (default value: null)
     *
     * @var string
     * @access protected
     */
    protected $subject = '';

    /**
     * The context or parameters passed to the Atom instance. These values are
     *  used when the statement is compiled.
     *
     * (default value: array())
     *
     * @var array
     * @access protected
     */
    protected $context = array();

    /**
     * The next Symbol in line of this token. Often this will be null when the
     *  Symbol is created and altered after when the next Symbol is created.
     *
     * (default value: null)
     *
     * @var null|\sn\atom\Symbol
     * @access protected
     */
    protected $lookahead = null;

    /**
     * The constructor for a Symbol instance.
     *
     * @access public
     * @param string $subject
     * @param array $context (default: array())
     * @param null|\sn\atom\Symbol $lookahead (default: null)
     * @return void
     */
    public function __construct( $subject, $context = array(), $lookahead = null ) {

        $this->subject = $subject;

        $this->set_context( $context );
        $this->set_lookahead( $lookahead );
    }

    /**
     * Returns the subject value of the Symbol instance.
     *
     * @access public
     * @return string
     */
    public function get_subject() {
        return $this->subject;
    }

    /**
     * Sets the subject value sof the Symbol instance.
     *
     * @access public
     * @param string $subject
     * @return string
     */
    public function set_subject( $subject ) {
        $this->subject = $subject;
    }

    /**
     * Returns the context value of the Symbol instance.
     *
     * @access public
     * @return array
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Sets the context value sof the Symbol instance.
     *
     * @access public
     * @param array $context
     * @return void
     */
    public function set_context( $context ) {

        if ( is_array( $context ) ) {
            $this->context = $context;
        }
    }

    /**
     * Returns the lookahead value of the Symbol instance.
     *
     * @access public
     * @return null|\sn\atom\Symbol
     */
    public function get_lookahead() {
        return $this->lookahead;
    }

    /**
     * Sets the lookahead value sof the Symbol instance.
     *
     * @access public
     * @param null|\sn\atom\Symbol $lookahead
     * @return void
     */
    public function set_lookahead( $lookahead ) {

        if ( null === $lookahead ||
             $lookahead instanceof \sn\atom\Symbol ) {

            $this->lookahead = $lookahead;
        }
    }
}