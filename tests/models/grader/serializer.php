<?php
    require_once 'models/grader/serializer.php';

    class SerializerTest extends UnitTestWithFixtures {
        public function testGameRequestParams() {
            $game = $this->buildGame();
            $game->initiateAttributes();
            $game->genesis();

            $this->assertTrue( method_exists( 'GraderSerializer', "gameRequestParams" ), 'GraderSerializer must have a "gameRequestParams" function' );

            $requestParams = GraderSerializer::gameRequestParams( $game );

            $this->assertTrue( isset( $requestParams[ 'gameid' ] ), 'gameid must exist in exported request params' );
            $this->assertEquals( $game->id, $requestParams[ 'gameid' ], 'gameid must be encoded properly to request params' );
            
            $this->assertTrue( isset( $requestParams[ 'W' ] ), 'W must exist in exported request params' );
            $this->assertEquals( $game->width, $requestParams[ 'W' ], 'W must be encoded properly to request params' );

            $this->assertTrue( isset( $requestParams[ 'H' ] ), 'H must exist in exported request params' );
            $this->assertEquals( $game->height, $requestParams[ 'H' ], 'H must be encoded properly to request params' );

            $this->assertTrue( isset( $requestParams[ 'M' ] ), 'M must exist in exported request params' );
            $this->assertEquals( $game->creaturesPerPlayer, $requestParams[ 'M' ], 'M must be encoded properly to request params' );

            $this->assertTrue( isset( $requestParams[ 'MAX_HP' ] ), 'MAX_HP must exist in exported request params' );
            $this->assertEquals( $game->maxHp, $requestParams[ 'MAX_HP' ], 'MAX_HP must be encoded properly to request params' );

            $players = json_decode( $requestParams[ 'players' ] );

            $this->assertTrue( isset( $requestParams[ 'players' ] ), 'players must exist in exported request params' );
            $this->assertTrue( is_array( $players ), 'players must be an array in exported request params' );
            $this->assertEquals( 4, count( $players ), 'players must contain correct number of users in exported request params' );

            $this->assertEquals( 1, $players[ 0 ]->userid, 'All players must exist in exported request params' );
            $this->assertEquals( 2, $players[ 1 ]->userid, 'All players must exist in exported request params' );
            $this->assertEquals( 3, $players[ 2 ]->userid, 'All players must exist in exported request params' );
            $this->assertEquals( 4, $players[ 3 ]->userid, 'All players must exist in exported request params' );

            $this->assertFalse( isset( $requestParams[ 'rounds' ] ), 'When includeRounds is not defined the attribute rounds must not be set' );
        }
        public function testSerializeRoundList() {
            $rounds = [ $this->buildRound() ];
            $requestParams = json_decode( GraderSerializer::serializeRoundList( $rounds ), true );
            $this->assertTrue( is_array( $requestParams ), 'decoded json must be an array' );
            $this->assertEquals( 1, count( $requestParams ), 'The correct number of rounds must be encoded' );
            $this->assertEquals( count( $rounds[ 0 ]->creatures ), count( $requestParams[ 1 ] ), 'Rounds must be encoded correclty' );
        }
        public function testRoundRequestParams() {
            $round = $this->buildRound();
            $game = $this->buildGame();
            $game->initiateAttributes();
            $user = $round->creatures[ 1 ]->user;

            $this->assertTrue( method_exists( 'GraderSerializer', "roundRequestParams" ), 'GraderSerializer must have a "roundRequestParams" function' );

            $requestParams = GraderSerializer::roundRequestParams( $round, $user, $game );

            $this->assertTrue( isset( $requestParams[ 'round' ] ), 'roundid must exist in exported request params' );
            $this->assertEquals( $round->id, $requestParams[ 'round' ], 'roundid must be encoded properly to request params' );

            $map = json_decode( $requestParams[ 'map' ] );
            $this->assertTrue( isset( $requestParams[ 'map' ] ), 'map must exist in exported request params' );
            $this->assertTrue( is_array( $map ), 'map must be an array in exported request params' );
            $this->assertEquals( 2, count( $map ), 'map must contain correct number of creatures in exported request params' );

            $this->assertEquals( 1, $map[ 0 ]->creatureid, 'All creatures must exist in exported request params' );
            $this->assertEquals( 2, $map[ 1 ]->creatureid, 'All creatures must exist in exported request params' );

            $this->assertTrue( isset( $requestParams[ 'myid' ] ), 'myid must exist in exported request params' );
            $this->assertEquals( $user->id, $requestParams[ 'myid' ], 'myid must be encoded properly to request params' );

            $this->assertTrue( isset( $requestParams[ 'W' ] ), 'W must exist in exported request params' );
            $this->assertEquals( $game->width, $requestParams[ 'W' ], 'W must be encoded properly to request params' );

            $this->assertTrue( isset( $requestParams[ 'H' ] ), 'H must exist in exported request params' );
            $this->assertEquals( $game->height, $requestParams[ 'H' ], 'H must be encoded properly to request params' );

            $this->assertTrue( isset( $requestParams[ 'gameid' ] ), 'gameid must exist in exported request params' );
            $this->assertEquals( $game->id, $requestParams[ 'gameid' ], 'gameid must be encoded properly to request params' );
        }
        public function testFlattenUser() {
            $user = $this->buildUser( 'vitsalis' );

            $this->assertTrue( method_exists( 'GraderSerializer', "flattenUser" ), 'GraderSerializer must have a "flattenUser" function' );

            $flattenedUser = GraderSerializer::flattenUser( $user );

            $this->assertTrue( isset( $flattenedUser[ 'username' ] ), 'username must exist in exported flattened data' ); 
            $this->assertEquals( $user->username, $flattenedUser[ 'username' ], 'username must be encoded properly to flattened data' );

            $this->assertTrue( isset( $flattenedUser[ 'userid' ] ), 'userid must exist in exported flattened data' ); 
            $this->assertEquals( $user->id, $flattenedUser[ 'userid' ], 'userid must be encoded properly to flattened data' );
        }
        public function testSerializeUserList() {
            $user1 = $this->buildUser( 'vitsalis' );
            $user2 = $this->buildUser( 'dionyziz' );
            $userList = [
                $user1->id => $user1,
                $user2->id => $user2 
            ];

            $this->assertTrue( method_exists( 'GraderSerializer', "serializeUserList" ), 'GraderSerializer must have a "serializeUserList" function' );

            $json = GraderSerializer::serializeUserList( $userList ); 
            $data = json_decode( $json );
            
            $this->assertTrue( is_array( $data ), 'Data returned from decoded json must be an array' );
            $this->assertEquals( count( $userList ), count( $data ), 'Decoded json must have the same number of users as userList has' );

            $this->assertEquals( $userList[ 1 ]->id, $data[ 0 ]->userid, 'All users must be serialized' );
            $this->assertEquals( $userList[ 2 ]->id, $data[ 1 ]->userid, 'All users must be serialized' );
        }
        public function testFlattenCreature() {
            $creature = $this->buildCreature( 1, 1, 2, $this->buildUser( 'vitsalis' ) );

            $this->assertTrue( method_exists( 'GraderSerializer', 'flattenCreature' ), 'GraderSerializer must have a "flattenCreature" function' );

            $flattenedCreature = GraderSerializer::flattenCreature( $creature );

            $this->assertTrue( isset( $flattenedCreature[ 'x' ] ), 'x must exist in exported flattened data' );
            $this->assertEquals( $creature->locationx, $flattenedCreature[ 'x' ], 'locationx must be encoded properly to flattened data' );

            $this->assertTrue( isset( $flattenedCreature[ 'y' ] ), 'y must exist in exported flattened data' );
            $this->assertEquals( $creature->locationy, $flattenedCreature[ 'y' ], 'locationy must be encoded properly to flattened data' );

            $this->assertTrue( isset( $flattenedCreature[ 'hp' ] ), 'hp must exist in exported flattened data' );
            $this->assertEquals( $creature->hp, $flattenedCreature[ 'hp' ], 'hp must be encoded properly to flattened data' );

            $this->assertTrue( isset( $flattenedCreature[ 'userid' ] ), 'userid must exist in exported flattened data' );
            $this->assertEquals( $creature->user->id, $flattenedCreature[ 'userid' ], 'userid must be encoded properly to flattened data' );

            $this->assertTrue( isset( $flattenedCreature[ 'creatureid' ] ), 'creatureid must exist in exported flattened data' );
            $this->assertEquals( $creature->id, $flattenedCreature[ 'creatureid' ], 'creatureid must be encoded properly to flattened data' );
        }
        public function testSerializeCreatureList() {
            $creature1 = $this->buildCreature( 1, 1, 2, $this->buildUser( 'vitsalis' ) );
            $creature2 = $this->buildCreature( 2, 3, 4, $this->buildUser( 'pkakelas' ) );
            $creatureList = [
                $creature1->id => $creature1,
                $creature2->id => $creature2
            ];

            $this->assertTrue( method_exists( 'GraderSerializer', 'serializeCreatureList' ), 'GraderSerializer must have a "serializeCreatureList" function' );

            $json = GraderSerializer::serializeCreatureList( $creatureList );
            $data = json_decode( $json );

            $this->assertTrue( is_array( $data ), 'Data returned from decoded json must be an array' );
            $this->assertEquals( count( $creatureList ), count( $data ), 'Decoded json must have the same number of creatures as creatureList has' );

            $this->assertEquals( $creatureList[ 1 ]->id, $data[ 0 ]->creatureid, 'All creatures must be serialized' );
            $this->assertEquals( $creatureList[ 2 ]->id, $data[ 1 ]->creatureid, 'All creatures must be serialized' );
        }
    }

    return new SerializerTest();
?>
