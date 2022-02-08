<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class Mail
{
    private $api_key = '3bbb7b93f26fa23b18b65cbb12518504';
    private $api_secret_key = 'ea302dc33928ab64378056d5cd1e8ab6';

    public function Send($to_email, $to_name, $subject, $content)
    {   
        $mj = new Client($this->api_key, $this->api_secret_key, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "sun.julie0@gmail.com",
                        'Name' => "Reflet Sucré"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3600126,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}