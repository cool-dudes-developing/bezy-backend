<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use App\Services\MethodService;
use Illuminate\Http\Request;

class EndpointController
{
    public function __construct(
        private readonly MethodService $methodService
    )
    {
    }

    private function createRegexFromScheme($scheme): string
    {
        // Escape slashes in the scheme to avoid conflicts in the regex
        $escapedScheme = preg_quote($scheme, '#');

        // Replace placeholders (e.g., :user) with named capture groups in regex
        $regexPattern = preg_replace_callback(
            '/\\\:([a-zA-Z_][a-zA-Z0-9_]*)/', // Match :placeholder
            function ($matches) {
                // Return a named capture group for each placeholder
                return '(?P<' . $matches[1] . '>[^/]+)';
            },
            $escapedScheme
        );

        // Add start and end delimiters to the pattern
        return '#^' . $regexPattern . '$#';
    }

    function matchUrlWithScheme($url, $scheme): array|bool
    {
        $pattern = $this->createRegexFromScheme($scheme);

        // Initialize an array to hold the matched parameters
        $matches = [];

        // Perform the regular expression match
        if (preg_match($pattern, $url, $matches)) {
            // Filter out numeric keys from the matches array
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        } else {
            // Return false if the URL does not match the pattern
            return false;
        }
    }

    public function executor(Request $request, string $project, string $uri)
    {
        // find project by id or slug
        $project = Project::find($project);
        // cycle through all endpoints in the project
        foreach ($project->methods()->where('type', 'endpoint')->whereNotNull('http_method')->whereNotNull('uri')->get() as $method) {
            if ($method->http_method === $request->method()) {
                if (($params = $this->matchUrlWithScheme($uri, $method->uri)) !== false) {
                    if ($request->isJson())
                        $params['Body'] = $request->json()->all();
                    else if ($request->getContent())
                        $params['Body'] = $request->getContent();

                    if ($request->query())
                        $params = array_merge($params, $request->query());

                    return $this->methodService->execute($project, $method, $params);
                }
            }
        }

        return response()->json(['error' => 'Route not found'], 404);
    }
}
