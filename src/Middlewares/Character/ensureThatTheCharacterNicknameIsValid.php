<?php

namespace App\Zaptank\Middlewares\Character;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ensureThatTheCharacterNicknameIsValid {

    public function __invoke(Request $request, RequestHandler $handler) :Response {

        if(!isset($_POST['nickname']) || empty(trim($_POST['nickname']))) {
            $body = json_encode([
                'success' => false,
                'message' => 'Você não digitou o nome do personagem.',
                'status_code' => 'empty_nickname'
            ]);

            $response = new Response();
            $response->getBody()->write($body);
            return $response; 
        }

        $nickname = $_POST['nickname'];
    
        $pattern = '/[^' . preg_quote($_ENV['CHARACTERS_ALLOWED_IN_THE_NICKNAME'], '/') . ']/';

        if(preg_match($pattern, $nickname)) {
            $body = json_encode([
                'success' => false,
                'message' => 'O nome do personagem contém caracteres especiais.',
                'status_code' => 'invalid_special_characters'
            ]);
        
            $response = new Response();
            $response->getBody()->write($body);
            return $response;
        }
    
        if(mb_strlen($nickname) < 3 || mb_strlen($nickname) > 16) {
            $body = json_encode([
                'success' => false,
                'message' => 'O Nome do seu personagem deve ter entre 3 a 16 caracteres.',
                'status_code' => 'invalid_special_characters'
            ]);

            $response = new Response();
            $response->getBody()->write($body);
            return $response;
        }        
          
        return $handler->handle($request);
    }
}