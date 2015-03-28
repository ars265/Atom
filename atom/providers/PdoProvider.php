<?php
namespace Atom\Providers;

use Atom\IProvider as IProvider;


/**
 * PdoProvider class.
 *
 * @implements IProvider
 */
class PdoProvider implements IProvider
{

    protected $dsn              = '';
    protected $username         = null;
    protected $password         = null;
    protected $options          = null;
    protected $last_resource    = null;
    protected $last_query       = '';
    protected $last_error       = '';
    protected $est_connection   = null;


    /**
     * Opens a connection using the PDO class. There is nothing forcing this to
     *  only be used by MySQL other than the QueryNodes built syntax. It may
     *  very well work with other PDO providers.
     *
     * @access protected
     * @return PDO
     */
    protected function open_connection() {

        static $pdo_reflection = null;

        //do a bit of performance enhancing, not to bad and not as fast as doing
        // so native but allows for cleaner instaniation.
        if ( null === pdo_reflection ) {
            $pdo_reflection = new ReflectionClass( 'PDO' );
        }

        $args = array( $this->dsn );
        $settings = array( 'username', 'password', 'options' );

        //until we hit a null case, load up the arguments
        foreach( $settings as $setting ) {

            if ( null === $this->{$setting} ) {
                break;
            }

            $args[] = $this->{$setting};
        }

        //instead of using the connection and then closing it, we could try to
        // make a persistant connection but in order of simplisity, we'll do
        // this for now and possibly add an option for that at a later date.
        // The other issue here is that if we reuse the connection, we need to
        // ensure that PDOStatement::closeCursor is called on the pervious
        // statement when preparing to run the next query to prevent failures.
        $connection = null;

        try {

            $connection = $pdo_reflection->newInstanceArgs( $args );
            $this->est_connection = $connection;

        } catch ( PDOException $exception ) {

            $this->last_error = $exception->getMessage();
            throw new Exception( 'Connection could not be established.' );
        }

        return $connection;
    }

    /**
     * Sets the connection to null.
     *
     * @access protected
     * @return void
     */
    protected function close_connection() {
        $this->est_connection = null;
    }

    /**
     * The constructor for the provider. For information about configuring the
     *  PDO driver see: http://php.net/manual/en/class.pdo.php
     *
     * @access public
     * @param Array $settings
     * @return void
     */
    public function __construct( $settings = array() ) {

        if ( array_key_exists( 'dsn', $settings ) ) {

            $this->dsn = $settings[ 'dsn' ];

        } else {

            throw new Exception( 'The setting "dsn" is required for this provider.' );
        }

        if ( array_key_exists( 'username', $settings ) ) {
            $this->username = $settings[ 'username' ];
        }

        if ( array_key_exists( 'password', $settings ) ) {
            $this->password = $settings[ 'password' ];
        }

        if ( array_key_exists( 'options', $settings ) ) {
            $this->options = $settings[ 'options' ];
        }
    }

    /**
     * Escapse a string to return a query safe value.
     *
     * @access public
     * @param String $string
     * @return String The escaped string
     */
    public function escape_string( $string ) {

        $connection = $this->open_connection();

        $result = $connection->quote( $string );

        if ( false === $result ) {
            $this->last_error = 'Your PDO driver does not support quoting or escaping.';
            throw new Exception( 'Your PDO driver does not support quoting or escaping.' );
        }

        return $result;
    }

    /**
     * Prepares a query for safe execution. Note: this does not execute the
     *  query, in order to execute the resource returned you must pass it to
     *  execute_query
     *
     * @access public
     * @param String $query
     * @return Resource
     */
    public function prepare_query( $query ) {

        $connection = $this->open_connection();
        $statement = false;

        try {

            $this->last_query = $query;
            $statement = $connection->prepare( $query );

        } catch ( PDOException $exception ) {

            $this->last_error = $exception->getMessage();
        }

        return $statement;
    }

    /**
     * Executes a query against a specified connection. The connection
     *  information can not change after the initialization of the constructor.
     *  the user is responisible for having the query properly escaped.
     *
     * @access public
     * @param String $query
     * @return mixed The resource or result from the query.
     */
    public function execute_query( $query ) {

        //if this thing is already a statement from being prepared, then we can
        // shorten the process and execute directly
        if ( $query instanceof PDOStatement ) {

            $statement = $query;

            $this->last_resource = $statement;

            if ( false === $statement->execute() ) {

                $this->last_error = $statement->errorInfo();
                return false;
            }

            return $statement;
        }

        $connection = $this->open_connection();

        $this->last_query = $query;
        $statement = $connection->query( $query );

        if ( false === $statement ) {

            $this->last_resource = $statement;
            //not sure if this will give us information about why the creation
            // of the statement failed, but should via the PHP docs
            $error = $connection->errorInfo();
            $this->last_error = $error[ 2 ];
        }

        return $statement;
    }

    /**
     * Returns a single value from the query. No matter the query, this is
     *  returned as the value from the first column and first row of the result.
     *
     * @access public
     * @param mixed &$resource
     * @return void
     */
    public function get_scalar( &$resource ) {

        if ( !$resource instanceof PDOStatement ) {
            $this->last_error = 'The only type of resource accepted is PDOStatement.';
            throw new Exception( 'The only type of resource accepted is PDOStatement.' );
        }

        $result = $resource->fetch( PDO::FETCH_NUM );

        //a comment on the PDO Statement fetch page states that false is also
        // returned if there were no results, which may mean no error? For this
        // reason we check for the resource error code
        if ( false === $result && null !== $resource->errorCode() ) {

            $error = $resource->errorInfo();
            $this->last_error = $error[ 2 ];
        }

        //don't allow any more queries using this resource
        $resource = null;

        $this->close_connection();

        return $result;
    }

    /**
     * Returns the next result of the query or false if all results have been
     *  exhausted.
     *
     * @access public
     * @param PDOStatement &$resource
     * @return Array|false
     */
    public function get_next( &$resource ) {

        if ( !$resource instanceof PDOStatement ) {
            $this->last_error = 'The only type of resource accepted is PDOStatement.';
            throw new Exception( 'The only type of resource accepted is PDOStatement.' );
        }

        $result = $resource->fetch( PDO::FETCH_ASSOC );

        if ( false === $result ) {

            //don't allow any more queries using this resource
            $resource = null;

            if ( null !== $resource->errorCode() ) {

                $error = $resource->errorInfo();
                $this->last_error = $error[ 2 ];
            }
        }

        $this->close_connection();

        return $result;
    }

    /**
     * Returns an array of associative arrays with the column name as the key
     *  and the column value. You should only use the resource once as it will
     *  be cleared after being executed and data returned.
     *
     * @access public
     * @param PDOStatement $resource
     * @return Array|false
     */
    public function get_all( &$resource ) {

        if ( !$resource instanceof PDOStatement ) {
            $this->last_error = 'The only type of resource accepted is PDOStatement.';
            throw new Exception( 'The only type of resource accepted is PDOStatement.' );
        }

        $result = $resource->fetchAll( PDO::FETCH_ASSOC );
        $resource = null;

        if ( false === $result ) {
            $error = $resource->errorInfo();
            $this->last_error = $error[ 2 ];
        }

        $this->close_connection();

        return $result;
    }

    /**
     * Returns the last query used to create a resource.
     *
     * @access public
     * @return String
     */
    public function get_last_query() {
        return $this->last_query;
    }

    /**
     * Returns the last error encountered by the provider instance.
     *
     * @access public
     * @return String
     */
    public function get_last_error() {
        return $this->last_error;
    }

}