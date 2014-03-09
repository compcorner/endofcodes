<?php
    require 'views/header.php';
?>

    <p>To begin playing, you must set up your bot.<a href=''> Start by reading the tutorial.</a></p><?php
    if ( $bot_success ) {
        ?><p class='check'>Your bot is correctly configured <img src='static/images/check.png' alt='check' /></p><?php
    }
    else if ( $bot_fail ) {
        ?><p class='error'>Your bot is incorrectly configured <img src='static/images/cancel.png' alt='cross' /></p><?php
        $errors = [
            'could_not_resolve' => 'Your bot hostname is invalid. Did you enter a valid hostname?',
            'could_not_connect' => 'Your bot is unreachable on the network. Did you enter your public IP address?',
            'http_code_not_ok' => 'Your bot is running, but responded with an invalid HTTP code. Did you write code to handle initiation?',
            'invalid_json' => 'Your bot is not sending valid JSON. Did you write code to generate JSON correctly?',
            'invalid_json_dictionary' => 'You must set the bot name, version, and your username. Did you build the correct JSON dictionary?',
            'username_mismatch' => 'Your bot is not using your username. Did you set your username correctly?'
        ];
        ?><p class='error'><?php
        if ( isset( $errors[ $error ] ) ) {
            echo $errors[ $error ];
        }
        else {
            ?>Unknown error<?php
        }
        ?></p><?php
        if ( !empty( $actual ) ) {
            ?><p>Your bot sent the following response which was unrecognized:

            <code><?php
            echo htmlspecialchars( $actual );
            ?></code></p><?php
        }
        if ( !empty( $expected ) ) {
            ?><p>We were expecting the following response instead:

            <code><?php
            echo htmlspecialchars( $expected );
            ?></code></p><?php
        }
    }
    $form = new Form( 'bot', 'update' );
    $form->output( function( $self ) use( $boturl_empty, $boturl_invalid ) {
        $self->createLabel( 'boturl', 'Bot URL' );
        if ( $boturl_empty ) {
            $self->createError( 'Please enter your bot URL' );
        }
        if ( $boturl_invalid ) {
            $self->createError( 'Please enter a valid HTTP URL' );
        }
        $self->createInput( 'text', 'boturl', 'boturl', $_SESSION[ 'user' ]->boturl );
        $self->createInput( 'submit', '', '', 'Save bot settings' );
    } );

    require 'views/footer.php';
?>
