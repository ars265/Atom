<?php
namespace Atom;

use Atom\QueryNode as QueryNode;

class Atom
{
    
    private static $instance = null;
    
    
    
    
    public function __construct() {}
    
    protected static function get_instance() {
        
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public static function select( $what ) {
        
        $head = QueryNode();
        return $head->select( $what );
    }
    
    public static function query() {
        
        
    }
    
    
    public static qoute() {
        
        
    }
    
    public static escape() {
        
        
    }
}