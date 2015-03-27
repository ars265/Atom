<?php
namespace Atom;


//yes I'm using a singleton, and yes, I know it's bad for my health :)
class Atom
{

    /**
     * The instance of the singleton.
     *
     * (default value: null)
     *
     * @var Atom|null
     * @access private
     * @static
     */
    private static $instance = null;

    /**
     * The provider instance used to perform functionality in different
     *  environments such as using MySQL PDO, WordPress, Yii, etc. This
     *  property must be set prior to use of most functions.
     *
     * (default value: null)
     *
     * @var IProvider|null
     * @access protected
     * @static
     */
    protected static $provider = null;


    //Keep the constructor private to not allow instances to be created.
    private function __construct() {}

    /**
     * Returns the instance of the singleton.
     *
     * @access protected
     * @static
     * @return Atom The class instance
     */
    protected static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Ensures that the provider was set before using relying on it to perform
     *  functionality. This method will throw an exception if the provider has
     *  not been set.
     *
     * @access protected
     * @static
     * @return void
     */
    protected static function ensure_provider() {

        if ( null === self::$provider ) {
            throw new Exception( 'A Provider must be set before using Atom.' );
        }
    }

    /**
     * Sets the provider instance that will be used to perform functionality
     *  across PHP platforms.
     *
     * @access public
     * @param IProvider $provider
     * @return void
     */
    public function set_provider( $provider ) {

        if ( !$provider instanceof IProvider ) {
            throw new Error( 'Provider must implement IProvider interface.' );
        }

        self::$provider = $provider;
    }

    /**
     * A basic generator for QueryNodes. This starts the query syntax and allows
     *  the query to be built hereafter using the query building nodes.
     *
     * @access public
     * @static
     * @param String|null $what (default: null)
     * @return QueryNode
     */
    public static function select( $what = null ) {

        self::ensure_provider();

        //create a new start node with the configured provider
        $head = new QueryNode( null, null, self::$provider );

        return $head->select( $what );
    }

    public static function query() {

        self::ensure_provider();

    }

    public static qoute() {

        self::ensure_provider();

    }

    public static escape() {

        self::ensure_provider();

    }
}