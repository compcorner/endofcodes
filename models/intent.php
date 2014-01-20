<?php
    define( 'DIRECTION_NONE', 0 );
    define( 'DIRECTION_NORTH', 1 );
    define( 'DIRECTION_EAST', 2 );
    define( 'DIRECTION_SOUTH', 3 );
    define( 'DIRECTION_WEST', 4 );
    define( 'ACTION_NONE', 0 );
    define( 'ACTION_MOVE', 1 );
    define( 'ACTION_ATACK', 2 );

    function convertDirection( $direction ) {
        $directionMap = array(
            'NONE' => DIRECTION_NONE,
            'NORTH' => DIRECTION_NORTH,
            'EAST' => DIRECTION_EAST,
            'SOUTH' => DIRECTION_SOUTH,
            'WEST' => DIRECTION_WEST
        );
        if ( isset( $directionMap[ $direction ] ) ) {
            return $directionMap[ $direction ];
        }
        $directionMap = array_flip( $directionMap );
        if ( isset( $directionMap[ $direction ] ) ) {
            return $directionMap[ $direction ];
        }
        return false;
    }

    function convertAction( $action ) {
        $actionMap = array(
            'NONE' => ACTION_NONE,
            'MOVE' => ACTION_MOVE,
            'ATACK' => ACTION_ATACK
        );
        if ( isset( $actionMap[ $action ] ) ) {
            return $actionMap[ $action ];
        }
        $actionMap = array_flip( $actionMap );
        if ( isset( $actionMap[ $action ] ) ) {
            return $actionMap[ $action ];
        }
        return false;
    }

    class Intent {
        public $action;
        public $direction;
        public $creature;

        public function __construct( $action, $direction ) {
            $this->direction = $direction;
            $this->action = $action;
        }
    }
?>
