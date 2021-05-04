<?php

/**
 * Dependency sorting/resolution algorithm for PHP
 *
 */
class Auxin_Dependency_Sorting {

    /**
     * The sorted list of dependency graph items
     *
     * @var      array
     */
    public $sorted  = array();

    /**
     * Stores the list of checked nodes in dependency graph
     *
     * @var      array
     */
    protected $checked = array();

    /**
     * Stores the input dependency graph
     *
     * @var     array
     */
    protected $graph   = array();


    function __construct(){}

    /**
     * Check and sort dependencies for each node
     *
     * @param  string $name      The node name
     * @param  array  $ancestors The nodes that are already collected
     * @return void
     */
    private function node_check( $name, $ancestors = array() ){
        $ancestors = (array) $ancestors;

        $ancestors[] = $name;
        $this->checked[ $name ] = true;

        // sometimes a dependency is not in main nodes list
        if( isset( $this->graph[ $name ] ) ){

            foreach ( $this->graph[ $name ] as $dependency ) {
                if( in_array( $dependency, $ancestors) ){
                    throw new Exception( "Circular dependency for following node detected: " . $name );
                }
                if( isset( $this->checked[ $dependency ] ) ){
                    continue;
                }
                $this->node_check( $dependency, $ancestors );
            }

        }

        if( ! in_array( $name, $this->sorted ) ){
            $this->sorted[] = $name;
        }
    }

    /**
     * Sorts and retrieves the list of sorted items based on defined dependencies
     *
     * @param  array $graph  The list of items and their dependencies
     * @return array         The sorted list of items based on defined dependencies
     */
    public function resolve( $graph ) {
        $this->graph = $graph;

        foreach ( $this->graph as $main_node => $ancestors) {
            $this->node_check( $main_node );
        }

        return $this->sorted;
    }

}

