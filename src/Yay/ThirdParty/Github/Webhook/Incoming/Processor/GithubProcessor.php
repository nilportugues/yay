<?php

namespace Yay\ThirdParty\Github\Webhook\Incoming\Processor;

use Symfony\Component\HttpFoundation\Request;
use Yay\Component\Webhook\Incoming\ProcessorInterface;

class GithubProcessor implements ProcessorInterface
{
    /* @var string */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function process(Request $request): void
    {
        $action = '';
        $username = '';

        $contents = $request->getContent(false);
        $data = json_decode($contents, true, 32);

        if ($request->headers->has('X-GitHub-Event')) {
            $action = $request->headers->get('X-GitHub-Event');
        }

        if ($data && isset($data['action']) && !empty($action)) {
            $action = sprintf('%s.%s', $action, $data['action']);
        }

        if ($data && isset($data['sender']['login'])) {
            $username = $data['sender']['login'];
        }

        if (!empty($action) && !empty($username)) {
            $request->attributes->set('action', $action);
            $request->attributes->set('username', $username);
        }
    }
}