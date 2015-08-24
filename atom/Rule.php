<?php

namespace sn\atom;

/**
 * The class representing a grammar rule.
 */
class Rule {

    /**
     * The form used to compile the rule as a symbol
     *
     * (default value: '')
     *
     * @var string
     * @access protected
     */
    protected $form = '';

    /**
     * The name or representation of the production rule. This is used when the
     *  user uses the symbol. As an example if the production rule was for a
     *  select statement then the for may be "SELECT %s" and the representation
     *  could be "select" as in Atom->select( '*' );
     *
     * (default value: '')
     *
     * @var string
     * @access protected
     */
    protected $representation = '';

    /**
     * The list of nonterminals or terminals that can represent the left side of
     *  the production rule.
     *
     * (default value: array())
     *
     * @var array
     * @access protected
     */
    protected $terminals = array();

    /**
     * The number of arguments required to send to teh production rule.
     *
     * (default value: 0)
     *
     * @var int
     * @access protected
     */
    protected $required_arguments = 0;

    /**
     * The maximum number of arguments allowed to be sent to the production rule.
     *  If this value is null then there is no maximum number of arguments.
     *
     * (default value: 0)
     *
     * @var int
     * @access protected
     */
    protected $maximum_arguments = 0;

    /**
     * The parent production rule.
     *
     * (default value: null)
     *
     * @var null|\sn\atom\Rule
     * @access protected
     */
    protected $parent_rule = null;

    /**
     * The constructor for the rule. If null is passed for the maximum number of
     *  arguments then there is no maximum allotment.
     *
     * @access public
     * @param string $form
     * @param string $name
     * @param array|string $terminals
     * @param int $r_args (default: 0)
     * @param int|null $m_args (default: 0)
     * @return void
     */
    public function __construct( $form, $name, $terminals, $r_args = 0, $m_args = 0 ) {

        $this->form = $form;
        $this->representation = $name;

        if ( null !== $m_args ) {
            $m_args = intval( $m_args );
        }

        $this->required_arguments = $r_args;
        $this->maximum_arguments = $m_args;

        if ( is_array( $terminals ) ) {
            $this->terminals = $terminals;
        } else {
            $this->terminals = explode( '|', $terminals );
        }
    }

    /**
     * Returns the form value of the production rule.
     *
     * @access public
     * @return string
     */
    public function get_form() {
        return $this->form;
    }

    /**
     * Sets the form value of the production rule.
     *
     * @access public
     * @param string $form
     * @return void
     */
    public function set_form( $form ) {
        $this->form = $form;
    }

    /**
     * Returns the representation value of the production rule.
     *
     * @access public
     * @return string
     */
    public function get_representation() {
        return $this->representation;
    }

    /**
     * Sets the representation value of the production rule.
     *
     * @access public
     * @param string $representation
     * @return void
     */
    public function set_representation( $name ) {
        $this->representation = $name;
    }

    /**
     * Returns the required arguments value of the production rule.
     *
     * @access public
     * @return int
     */
    public function get_required_arguments() {
        return $this->required_arguments;
    }

    /**
     * Sets the required arguments value of the production rule.
     *
     * @access public
     * @param int $required_arguments
     * @return void
     */
    public function set_required_arguments( $required_arguments ) {
        $this->required_arguments = intval( $required_arguments );
    }

    /**
     * Returns the maximum arguments value of the production rule.
     *
     * @access public
     * @return int
     */
    public function get_maximum_arguments() {
        return $this->maximum_arguments;
    }

    /**
     * Sets the value of the production rule. If null is passed then there is no
     *  maximum number of arguments.
     *
     * @access public
     * @param int|null $maximum_arguments
     * @return void
     */
    public function set_maximum_arguments( $maximum_arguments ) {

        if ( null !== $maximum_arguments ) {
            $maximum_arguments = intval( $maximum_arguments );
        }

        $this->maximum_arguments = $maximum_arguments;
    }

    /**
     * Adds a non-empty terminal to the production rule.
     *
     * @access public
     * @param string $terminal
     * @return void
     */
    public function add_terminal( $terminal ) {

        if ( !empty( $terminal ) ) {
            $this->terminals[] = $terminal;
        }
    }

    /**
     * Returns the terminals registered with the production rule.
     *
     * @access public
     * @return array
     */
    public function get_terminals() {
        return $this->terminals;
    }

    /**
     * Returns the parent rule of this production rule.
     *
     * @access public
     * @return null|\sn\atom\Rule
     */
    public function get_parent_rule() {
        return $this->parent_rule;
    }

    /**
     * Sets the parent rule of this production rule.
     *
     * @access public
     * @param \sn\atom\Rule $parent_rule
     * @return void
     */
    public function set_parent_rule( \sn\atom\Rule $parent_rule ) {
        $this->parent_rule = $parent_rule;
    }
}