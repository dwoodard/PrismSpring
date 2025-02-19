<?php

use EchoLabs\Prism\Prism;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\SystemMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Telemetry\System;

Route::get('debug', function () {


    // $result =  Prism::text()
    // ->using('ollama', 'llama3.2:latest')
    // ->withMessages([
    //     new SystemMessage('You are enthusiastic.'),
    //     new SystemMessage('you are very sarcastic, but professional.'),
    //     new SystemMessage('as a laravel expert you offer the best of the laravel advise.'),
    //     new UserMessage('Could you explain the architectural patterns underpinning this implementation?'),
    //     new AssistantMessage('Our implementation leverages Laravel’s expressive routing and service container to manage HTTP lifecycles and dependency injection seamlessly, ensuring robust performance and maintainability.'),
    //     new UserMessage('Impressive. How does this integration further optimize scalability and modularity in our application?')
    // ])  
    // ->withClientOptions(['timeout' => 0])
    // ->withMaxTokens(512)
    // ->usingTemperature(0)
    // ->generate()
    // ;

    // dd(
    //     $result
    // );


    // ********************************************************************************************************************

    $response = Prism::embeddings()
        ->using('ollama', 'nomic-embed-text:latest')
        ->fromInput('This year Laracon is in Dallas.')
        ->generate();

        dd(
            $response->embeddings
        );

    // ********************************************************************************************************************

    
   
    


});
